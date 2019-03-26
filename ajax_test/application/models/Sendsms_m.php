<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************
many of the  function in this model is taken exactly from curd_m and common_m to make this 
model and functionality isolate from whole system , if any changes needed to those model method please change
in this model too if necessary
***************************/

class Sendsms_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();	
		$this->description = array();
		
	}
	
	/*************start of render functions*************/
		
	
	public function addQue($data){
		
		$operator_name = array();
		$operQuery = $this->get_search('SELECT * FROM operator','object');
		foreach($operQuery as $oper){
			$operDetail[$oper->fld_int_id] = strtoupper($oper->acronym);
		}
		
		$number = $this->getValidNumbers($data['sendby'],$data['addressbook'],$data['excludeNumber'],$data['numbers'],$data['userId']);
		if(!is_array($number) || sizeof($number)==0) {
			if($data['sendby']=='appuser') return 'No valid number found for sending SMS';
			else if($data['sendby']=='nonappuser') return '1505';	
		}
		$count = $this->count_message($data['messageType'],$data['message']);
		$operator = array_keys($number); 
		foreach($operator as $operid){
			$countDetails[] = $operDetail[$operid].' : '.sizeof($number[$operid]);
		}
		if(sizeof($operator) >1){// if api request has multiple operator numbers then reject request
			if($data['sendby']=='nonappuser') return '1505';
		}
		$error = array();
		/********** checking balance*****************/
		$balanceType = trim(strtolower($data['balanceType']));
		if($balanceType == 'seperate'){
			foreach($operator as $oper){
				$check = $this->checkBalance($balanceType,( (int)$count['msg_len']*sizeof($number[$oper]) ),$oper,$data['userId']);
				if( !is_int($check)) $error[] = $check;
			}
		}
		// if error found terminate process with message or error code
		if(sizeof($error) > 0){ 
			if($data['sendby'] == 'nonappuser')	return '1507'; // send error code if user isnot app user
			else  return 'fail__'.implode('</br>',$error );
		};
		// preparing array for que_detail entry
		$numberArr = array();
		foreach($number as $key=>$val){
			foreach($val as $val1){
				$numberArr[] = array(
									'fld_int_id'=>uniqid('',TRUE),
									'que_id'=>$data['queid'],
									'cellNumbers'=>$val1,
									'operator'=>$key,
								);
			}
		
		}
		
		/* adding sender id according to the operator number contain*/
		$senderIdList = explode(',',$data['senderId']);
		$normSenderList = array();
		foreach($senderIdList as $list){
			$list = explode('_',$list);
			if(in_array($list[0],$operator)){
				if($list[1]==='none'){
					$sendQurId = $this->get_search('SELECT fld_int_id AS id FROM easy_senderid WHERE fld_int_default=1 AND operator='.$list[0],'object');
					$normSenderList[] = $sendQurId[0]->id;
				}
				else $normSenderList[]= $list[1];
			}
		}
		if(sizeof($normSenderList)==0){
			if($data['sendby'] == 'nonappuser'){	
				if($this->hasPrivileges('avoid',$data['userId'])){
					foreach($operator as $oper){
						$sendQurId = $this->get_search('SELECT fld_int_id AS id FROM easy_senderid WHERE fld_int_default=1 AND operator='.$oper,'object');
						$normSenderList[] = $sendQurId[0]->id;
					}
				}
				else{
					return '1503';
				}
			} 
			else return 'Error : Sending Fail';
		}
		$numberArr = array_chunk($numberArr,25);
		$sch_date = 'none';
		if($data['schedulerDates']!='none'){
			$sch_date = explode(',',$data['schedulerDates']);
			asort($sch_date);
			$sch_date = implode(',',$sch_date);
		}
		
		$res = $this->get_insert('que_job',array(
										'fld_int_id'=>$data['queid'],
										'users_id'=>$data['userId'],
										'schedule'=>$data['schedule'],
										'sender_id'=>(string) implode(',',$normSenderList),
										'event_name'=>$data['eventName'],
										'date'=>$data['date'],
										'schedule_date'=>$sch_date ,
										'message'=>$data['message'],
										'messageType'=>$data['messageType'],
										'messageCount'=>$count['msg_len'].'-'.$count['char_len'],
										'agent_id'=>$data['agentId'],
										'fld_balance_type'=>$balanceType ,
										'fld_int_cell_no'=>implode(',',$countDetails),
									));
		if($res !==FALSE){
			foreach($numberArr as $val){
			
				$res = $this->get_insert('que_detail',$val,'batch');
				if($res === FALSE){
					$this->undoEntry(array('que_job'=>'fld_int_id '.$data['queid'],'que_detail'=>'que_id '.$data['queid']));
					if($data['sendby'] == 'nonappuser')	return '1900'; // send error code if user isnot app user
					else  return 'fail__**Error : Unable to add new Que Job';
				}
			}
			// if sucessfully added in que
			if($data['sendby']=='appuser') return  array_reverse($this->description);
			else if($data['sendby']=='nonappuser') return '1701';
		}else{
			if($data['sendby'] == 'nonappuser')	return '1900'; // send error code if user isnot app user
			else  return 'fail__**Error : Unable to add new Que Job';
		}
	}
	
	/******************** 	
	quick sms 
	***************************************************/
	
	public function quickSend($data){
		
		$agentId = $data['agentId'];
		$balance = array();
		$gateway = array();
		$error	= array();
		$operDetail = array();
		$countDetails = array();// counts all the valid numbers accordings to operator
		
		$operQuery = $this->get_search('SELECT * FROM operator','object');
		foreach($operQuery as $oper){
			$operDetail[$oper->fld_int_id] = strtoupper($oper->acronym);
		}
		$number = $this->getValidNumbers($data['sendby'],$data['addressbook'],$data['excludeNumber'],$data['numbers'],$data['userId']);
		
		if(!is_array($number) || $number===FALSE || sizeof($number)==0){ 
			if($data['sendby']=='appuser') return 'No valid number found for sending SMS';
			
			else if($data['sendby']=='nonappuser' || $data['sendby']=='cron_user' || $data['sendby']=='pulluser') return '1505';
		}
		//if(sizeof($number));
		$operator = array_keys($number); // operator according to sent numbers
		
		if(sizeof($operator) >1){ // if api request has multiple operator numbers then reject request
			if($data['sendby']=='nonappuser') return '1505';
		}
		foreach($operator as $operid){
			$countDetails[] = $operDetail[$operid].' : '.sizeof($number[$operid]);
		}		
		// CHECKING IF MESSAGE TEMPLATE EXIST OR NOT FOR ALL REQUEST
		$template = $this->get_search('SELECT * FROM msgTemplate WHERE user_id='.$data['userId'].' AND sender_id=0','object');
		if($template!=NULL){
			$templates = json_decode($template[0]->template);
			foreach($templates as $key=>$val){
				if($key =='header') $data['message'] =  $val."\r\n".$data['message'];
				elseif($key =='footer') $data['message'] =  $data['message']."\r\n".$val;
			}
		}
		/* adding sender id according to the operator contains
		** AND fetch gateway according to sender id
		*/
		$senderIdList = explode(',',$data['senderId']);
		$normSenderList = array();
		$normSenderNameList = array();
		foreach($senderIdList as $list){
			$list = explode('_',$list);
			if(in_array($list[0],$operator)){
				if($list[1]==='none'){
					$sendQurId = $this->get_search('SELECT s.fld_int_id AS id ,s.fld_chr_senderid AS fld_chr_senderid FROM easy_senderid s INNER JOIN gateway g WHERE g.fld_int_priority=1 AND s.fld_int_default=1 AND g.fld_int_id = s.fld_gateway AND s.operator='.$list[0],'object');
					$normSenderList[$list[0]] = $sendQurId[0]->id;
					$normSenderNameList[$list[0]] = $sendQurId[0]->fld_chr_senderid;
				}
				else{ 
					$sendQurId = $this->get_search('SELECT fld_int_id AS id ,fld_chr_senderid AS fld_chr_senderid FROM easy_senderid  WHERE fld_int_id ='.$list[1],'object');
					if($sendQurId!=NULL){
						$normSenderNameList[$list[0]] = $sendQurId[0]->fld_chr_senderid;
						$normSenderList[$list[0]] = $list[1];
					}
				}
				// FETCHING MESSAGE TEMPLATE ACCORDING TO SENDER ID
				if($template==NULL){
					$template = $this->get_search('SELECT * FROM msgTemplate WHERE user_id='.$data['userId'].' AND sender_id='.$normSenderList[$list[0]],'object');
					if($template!=NULL){
						$templates = json_decode($template[0]->template);
						foreach($templates as $key=>$val){
							if($key =='header') $data['message'] =  $val."\r\n".$data['message'];
							elseif($key =='footer') $data['message'] =  $data['message']."\r\n".$val;
						}
					}
				}
				
				// FETCHING GATEWAY ACCORDING TO SENDER ID
				$gwQuery = $this->get_search('SELECT g.fld_char_username AS fld_char_username,g.fld_char_password AS fld_char_password,g.fld_chr_smscid AS fld_chr_smscid FROM easy_senderid s INNER JOIN gateway g WHERE g.fld_int_id = s.fld_gateway AND s.fld_int_id = '.$normSenderList[$list[0]],'object');
				if($gwQuery!=NULL){
					$gateway[$list[0]] = array(
											'username'=>$gwQuery[0]->fld_char_username,
											'password'=>$gwQuery[0]->fld_char_password,
											'smsc'=>$gwQuery[0]->fld_chr_smscid,
										);
				}
				else{ ///////////// gateway not found
					if($data['sendby'] == 'nonappuser'|| $data['sendby']=='cron_user' || $data['sendby']=='pulluser')	return '1503'; 
					else return 'Error : Sending Fail';
				}
			}
		}
		
		if(sizeof($normSenderList)==0){
			if($data['sendby'] == 'nonappuser' ){	
				if($this->hasPrivileges('avoid',$data['userId']) ){
					foreach($operator as $oper){
						$sendQurId = $this->get_search('SELECT s.fld_int_id AS id ,s.fld_chr_senderid AS fld_chr_senderid FROM easy_senderid s INNER JOIN gateway g WHERE g.fld_int_priority=1 AND s.fld_int_default=1 AND g.fld_int_id = s.fld_gateway AND s.operator='.$oper,'object');
						$normSenderList[$oper] = $sendQurId[0]->id;
						$normSenderNameList[$oper] = $sendQurId[0]->fld_chr_senderid;
						
						// FETCHING MESSAGE TEMPLATE ACCORDING TO SENDER ID
						if($template==NULL){
							$template = $this->get_search('SELECT * FROM msgTemplate WHERE user_id='.$data['userId'].' AND sender_id='.$normSenderList[$oper],'object');
							if($template!=NULL){
								$templates = json_decode($template[0]->template);
								foreach($templates as $key=>$val){
									if($key =='header') $data['message'] =  $val."\r\n".$data['message'];
									elseif($key =='footer') $data['message'] =  $data['message']."\r\n".$val;
								}
							}
						}
						$gwQuery = $this->get_search('SELECT g.fld_char_username AS fld_char_username,g.fld_char_password AS fld_char_password,g.fld_chr_smscid AS fld_chr_smscid FROM easy_senderid s INNER JOIN gateway g WHERE g.fld_int_id = s.fld_gateway AND s.fld_int_id = '.$normSenderList[$oper],'object');
						if($gwQuery!=NULL){
							$gateway[$oper] = array(
													'username'=>$gwQuery[0]->fld_char_username,
													'password'=>$gwQuery[0]->fld_char_password,
													'smsc'=>$gwQuery[0]->fld_chr_smscid,
												);
						}
						else{ ///////////// gateway not found
							if($data['sendby'] == 'nonappuser'|| $data['sendby']=='cron_user' || $data['sendby']=='pulluser')	return '1503'; 
							else return 'Error : Sending Fail';
						}
					///////////////////////
					}
				}
				else{
					return '1503';
				}
			} 
			else return 'Error : Sending Fail';
		}
		$count = $this->count_message($data['messageType'],$data['message']);
		/********** checking balance*****************/
		$balanceType = trim(strtolower($data['balanceType']));
		if($balanceType == 'seperate'){
			foreach($operator as $oper){
				$check = $this->checkBalance($balanceType,( (int)$count['msg_len']*sizeof($number[$oper]) ),$oper,$data['userId']);
				
				if( !is_int($check)) {$error[] = $check; }
				else $balance[$oper] = $check;
			}
		}
		// if error found terminate process with message or error code
		if(sizeof($error) > 0){ 
			if($data['sendby'] == 'nonappuser' || $data['sendby']=='cron_user' || $data['sendby']=='pulluser')	return '1507'; // send error code if user isnot app user
			else  return 'fail__'.implode('</br>',$error );
		};
		/********** updating balance balance*****************/
		foreach($operator as $oper){
			$unit = ( (int)$count['msg_len']*sizeof($number[$oper]) );
			if(!$this->updateBalance($data['userId'],($balance[$oper]-$unit),$balanceType,$oper))return '** Error : Operation Fail';
			
			$this->get_insert('balance_transaction',array(
															'fld_int_id'=>uniqid('',TRUE),
															'fld_track_id'=>$data['queid'],
															'fld_user_id'=>$data['userId'],
															'fld_transaction_type'=>2,
															'fld_transaction_descripition'=>'deduced : Balance deduced due to Sending SMS',
															'fld_balance_type'=> $oper,
															'fld_amount'=>$unit,
															'fld_balance_after_update'=>$balance[$oper]-$unit,
															'date'=>$data['date'],
														));
			$this->get_insert('sms_transaction',array(
															'fld_int_id'=>uniqid('',TRUE),
															'fld_track_id'=>$data['queid'],
															'fld_int_userid'=>$data['userId'],
															'fld_int_agentid'=>$agentId,
															'fld_int_units_deducted'=>$unit,
															'fld_int_career'=> $oper,
															'fld_chr_ondate'=>$data['date'],
														));
		}
		/***** preparing array for sending sms*********/
		$send_sms = array();
		$outboxDetails = array();
		$message = ( $data['messageType']==2 || $data['messageType']==4)?mb_convert_encoding($data['message'], "UTF-16BE"):$data['message'];
				
		if( $data['messageType']==2 || $data['messageType']==4){
			$data['charset'] = 'UTF-16BE';
			$data['coding'] = 2;
		}
		else {
			$data['coding'] = 0;
			$data['charset'] = NULL;
		}
		if( $data['messageType']==3 || $data['messageType']==4){
			$data['mclass'] = 0;
		}
		else{
			$data['mclass'] = NULL;
		}
		foreach($number as $key=>$val){
			foreach($val as $val1){
				$uniqEachEntry =  uniqid('',TRUE);
			
				$outboxDetails[] = array(
									'fld_int_id'=>$uniqEachEntry,
									'fld_oid'=>$data['queid'],
									'fld_mobile_number'=>$val1,
									'fld_operator'=>$key,
									'status'=>1,
									'admin_state'=>0
								);
				
				
				$send_sms[] = array(
									'momt'=>'MT',
									'sender'=>$normSenderNameList[$key],
									'receiver'=>$val1,
									'msgdata'=> urlencode($message),
									'dlr_mask'=>31,
									'dlr_url'=>$uniqEachEntry,
									'charset'=>$data['charset'],
									'coding'=>$data['coding'],
									'mclass'=>$data['mclass'] ,
									'smsc_id'=>$gateway[$key]['smsc'],
									'service'=>$gateway[$key]['username'],
									
								);						
			}
		}
		
		$outboxDetails = array_chunk($outboxDetails,25);
		$send_sms = array_chunk($send_sms,25);
				
		$count = $this->count_message($data['messageType'],$data['message']);
		$res = $this->get_insert('outbox',array(
										'fld_int_id'=>$data['queid'],
										'fld_int_userid'=>$data['userId'],
										'fld_chr_sender'=>(string) implode(',',$normSenderNameList),
										//'fld_chr_sender'=>(string) implode(',',$normSenderList),
										'fld_int_ondate'=>$data['date'],
										'fld_chr_message'=>$data['message'],
										'messageType'=>$data['messageType'],
										'fld_msg_number'=>$count['msg_len'].'-'.$count['char_len'],
										'fld_int_cell_no'=>implode(',',$countDetails),
										'fld_reseller_id'=>$data['agentId'],
										'fld_user_data'=>(isset($data['userdata']))?$data['userdata']:'', 
									));
		if($res !==FALSE){
			foreach($outboxDetails as $val){
				$res = $this->get_insert('outbox_detail',$val,'batch');
				if($res === FALSE){
					$this->undoEntry(array('outbox'=>'fld_int_id '.$data['queid'],'outbox_detail'=>'fld_oid '.$data['queid']));
					if($data['sendby']=='appuser') return '**Error : Sending SMS fail';
					else if($data['sendby']=='nonappuser' || $data['sendby']=='cron_user' || $data['sendby']=='pulluser') return '1900';
				}
			}
			foreach($send_sms as $val){
				$res = $this->get_insert('send_push',$val,'batch');
				//$res = $this->get_insert('send_push_test',$val,'batch'); // for test purpose
				if($res === FALSE){
					if($data['sendby']=='appuser') return '**Error : Sending SMS fail';
					else if($data['sendby']=='nonappuser' || $data['sendby']=='cron_user' || $data['sendby']=='pulluser') return '1900';
				}
			}
			// if sucessfully sms sent
			if($data['sendby']=='appuser') return  array_reverse($this->description);
			else if($data['sendby']=='nonappuser' || $data['sendby']=='cron_user' || $data['sendby']=='pulluser') return '1701';
		}else{
			if($data['sendby']=='appuser') return '**Error : Sending SMS fail';
			else if($data['sendby']=='nonappuser' || $data['sendby']=='cron_user' || $data['sendby']=='pulluser') return '1900';
		}
	}
	public function sendEomSms($data){
		
		$numbers = array();
		$balance= array();
		$count = array();
		$normSenderList = array();
		$normSenderNameList = array();
		$unit = array();
		$error = array();
		$operDetail= array();
		$numberOfEom = 0;
		$messageCounts = array();
		$balanceType = strtolower($data['balanceType']);
		
		$operQuery = $this->get_search('SELECT * FROM operator','object');
		foreach($operQuery as $oper){
			$operDetail[$oper->fld_int_id] = strtoupper($oper->acronym);
		}
		
		foreach($data['eomArr'] as $row){
			$numbers[] = $row['number'];			
		}
		$arr = $this->getValidNumbers($data['sendby'],NULL,NULL,implode(',',$numbers),$data['userId']);
		if(!is_array($arr) || $arr===FALSE || sizeof($arr)==0){ 
			 return 'fail__**Error : No Valid Numbers found';
		}
	
		$operator = array_keys($arr); // operator according to sent numbers
		foreach($operator as $oper){ // initilizing unit array
			$unit[$oper] = 0;
		}
		foreach($arr as $key=>$row){
			foreach($row as $num){
				$count = $this->count_message($data['msgType'],$data['eomArr'][$num]['message']);
				$unit[$key] = $unit[$key] + (int)$count['msg_len'];
				$numberOfEom ++;
				$messageCounts[$num] = $count['msg_len'].'-'.$count['char_len'];
			}
		}
		
		/********** checking balance*****************/
		if($balanceType  == 'seperate'){
		
			foreach($operator as $oper){
				$check = $this->checkBalance($balanceType ,(int)$unit[$oper],$oper,$data['userId']);
				
				if( !is_int($check)) {$error[] = $check; }
				else $balance[$oper] = $check;
			}
		}
		
		// if error found terminate process with message or error code
		if(sizeof($error) > 0){ 
			if($data['sendby'] == 'nonappuser')	return 'errorcode'; // send error code if user isnot app user
			else  return 'fail__'.implode('</br>',$error );
		};
		
		$senderIdList = explode(',',$data['senderid']);
		foreach($senderIdList as $list){
			$list = explode('_',$list);
			if(in_array($list[0],$operator)){
				
				if($list[1]==='none'){
					$sendQurId = $this->get_search('SELECT s.fld_int_id AS id ,s.fld_chr_senderid AS fld_chr_senderid FROM easy_senderid s INNER JOIN gateway g WHERE g.fld_int_priority=1 AND s.fld_int_default=1 AND g.fld_int_id = s.fld_gateway AND s.operator='.$list[0],'object');
					$normSenderList[$list[0]] = $sendQurId[0]->id;
					$normSenderNameList[$list[0]] = $sendQurId[0]->fld_chr_senderid;
				}
				else{ 
					$sendQurId = $this->get_search('SELECT fld_int_id AS id ,fld_chr_senderid AS fld_chr_senderid FROM easy_senderid  WHERE fld_int_id ='.$list[1],'object');
					$normSenderNameList[$list[0]] = $sendQurId[0]->fld_chr_senderid;
					$normSenderList[$list[0]] = $list[1];
				}
				
				// FETCHING GATEWAY ACCORDING TO SENDER ID
				$sendQurId = $this->get_search('SELECT g.fld_char_username AS fld_char_username,g.fld_char_password AS fld_char_password,g.fld_chr_smscid AS fld_chr_smscid FROM easy_senderid s INNER JOIN gateway g WHERE g.fld_int_id = s.fld_gateway AND s.fld_int_id = '.$normSenderList[$list[0]],'object');
				if($sendQurId!=NULL){
					$gateway[$list[0]] = array(
											'username'=>$sendQurId[0]->fld_char_username,
											'password'=>$sendQurId[0]->fld_char_password,
											'smsc'=>$sendQurId[0]->fld_chr_smscid,
										);
				}
				else return 'Error : Sending Fail';///////////// gateway not found
			}
		}
		/********** updating balance balance*****************/
		
		foreach($operator as $oper){
			
		
			//$unit = ( (int)$count['msg_len']*sizeof($number[$oper]) );
			if(!$this->updateBalance($data['userId'],($balance[$oper]-$unit[$oper]),$balanceType ,$oper))return '** Error : Operation Fail';
			
			$this->get_insert('balance_transaction',array(
										'fld_int_id'=>uniqid('',TRUE),
										'fld_track_id'=>$data['queid'],
										'fld_user_id'=>$data['userId'],
										'fld_transaction_type'=>2,
										'fld_transaction_descripition'=>'deduced : Balance deduced due to Sending '.$numberOfEom.' EOM SMS',
										'fld_balance_type'=> $oper,
										'fld_amount'=>$unit[$oper],
										'fld_balance_after_update'=>$balance[$oper]-$unit[$oper],
										'date'=>$data['date'],
										));
			$this->get_insert('sms_transaction',array(
										'fld_int_id'=>uniqid('',TRUE),
										'fld_track_id'=>$data['queid'],
										'fld_int_userid'=>$data['userId'],
										'fld_int_agentid'=>$this->session->userdata('reseller'),
										'fld_int_units_deducted'=>$unit[$oper],
										'fld_int_career'=> $oper,
										'fld_chr_ondate'=>$data['date'],
										));
		}
		
		/***** preparing array for sending sms*********/
		$send_sms = array();
		$outboxDetails = array();
		
				
		if( $data['msgType']==2 || $data['msgType']==4){
			$data['charset'] = 'UTF-16BE';
			$data['coding'] = 2;
		}
		else {
			$data['coding'] = 0;
			$data['charset'] = NULL;
		}
		if( $data['msgType']==3 || $data['msgType']==4){
			$data['mclass'] = 0;
		}
		else{
			$data['mclass'] = NULL;
		}
		foreach($arr as $key=>$val){
			foreach($val as $val1){
				$uniqEachEntry =  uniqid('',TRUE);
				$res = $this->get_insert('outbox',array(
										'fld_int_id'=>$uniqEachEntry,
										'eom_track_id'=>$data['queid'],
										'fld_int_userid'=>$data['userId'],
										'fld_chr_sender'=>$normSenderNameList[$key],
										'fld_int_ondate'=>$data['date'],
										'fld_chr_message'=>$data['eomArr'][$val1]['message'],
										'messageType'=>$data['msgType'],
										'fld_msg_number'=>$messageCounts[$val1],
										'fld_user_data'=>'',
										'fld_reseller_id'=>$data['agentId'],
										'fld_int_cell_no'=>$operDetail[$key].' : 1',
									));
				$res = $this->get_insert('outbox_detail',array(
									'fld_int_id'=>$uniqEachEntry,
									'fld_oid'=>$uniqEachEntry,
									'fld_mobile_number'=>$val1,
									'fld_operator'=>$key,
									'status'=>1,
									'admin_state'=>0
								));
								
								
				$message = ( $data['msgType']==2 || $data['msgType']==4)?mb_convert_encoding($data['eomArr'][$val1]['message'], "UTF-16BE"):$data['eomArr'][$val1]['message'];
				$res = $this->get_insert('send_push',array(									
									'momt'=>'MT',
									'sender'=>$normSenderNameList[$key],
									'receiver'=>$val1,
									'msgdata'=> urlencode($message),
									'dlr_mask'=>31,
									'dlr_url'=>$uniqEachEntry,
									'charset'=>$data['charset'],
									'coding'=>$data['coding'],
									'mclass'=>$data['mclass'] ,
									'smsc_id'=>$gateway[$key]['smsc'],
									'service'=>$gateway[$key]['username'],
								));								
			}
			
		}	
		return array_reverse($this->description);	
	}// end of function
	
	/*
	validate numbers including addressbook addressbook
	*/
	public function getValidNumbers($userType,$adbid,$excludeNumb,$sentNumbers,$userId){
		$number = array();
		if($adbid !==NULL){
			$addbook = explode(',',$adbid);

			foreach($addbook as $val){
				$num = $this->getData('addressbook',array('fld_int_addb_id'=>(int)$val),'object' );
				if($num !==FALSE){
					foreach($num as $row){
						if($excludeNumb!=NULL){
							if(!in_array($row->fld_int_id,$excludeNumb))  $number[] = $row->fld_chr_phone;
						}else{
							$number[] = $row->fld_chr_phone;
						}
					}
				}
			}
			if(sizeof($number)== 0 &&  $sentNumbers==NULL){
				if($sentNumbers ==NULL) return 'fail__**Warning : No Valid Number found or Selected Address book is empty';
			}
		}
		
		if($sentNumbers!==NULL){
			$sentNumbers =str_replace("\r","", str_replace("\n","",$sentNumbers));
			$number = array_merge(explode(',',trim($sentNumbers,',')),$number);
		}
			
		$arr = $this->verifyNumber($number,$userId);
		$number = array();
		foreach($arr as $key=>$val){
			if($key !=='repeat' && $key !=='fault'){
				foreach($val as $val1){
					$number[$key][] = $val1;
				}
			}
			else{
				if($key =='repeat'){
					if($userType =='appuser'){
						$this->description[]  =sizeof($arr['repeat']).' Cell Numbers Found To be repeated [ '.implode(', ',$arr['repeat']).' ]';
					}
				}
				elseif($key=='fault'){
					if($userType =='appuser'){
						$this->description[]  =sizeof($arr['fault']).' Cell Numbers Found To be Fault [ '.implode(', ',$arr['fault']).' ]';
					}
					else return FALSE;
				}
			}
			
		}
		if($userType =='appuser'){
			$operator_name = $this->getOperatorName();
			foreach($number as $key=>$val){
				$this->description[]  =sizeof($number[$key]).' '.$operator_name[$key].' Cell Numbers';
			}
		}
		
		return $number;
	}// end of function
	public function getOperatorName(){
		$temp_ope = $this->getData('operator',NULL,'object');
		$operator_name = array();
		if($temp_ope!==FALSE){
			foreach($temp_ope as $row){
				$operator_name[$row->fld_int_id] = strtoupper($row->acronym);
			}
		}
		else return FALSE;
		return $operator_name;
	}
	
	/******************
	sub-depending  functions
	******************/
	public function get_search($sql, $type='array'){
		$query = $this->db->query($sql);
		$res = NULL;
		if($query->num_rows() > 0){
			if($type=='array'){   $res = $query->result_array();}
			elseif($type=='object'){ $res = $query->result();	}
		}
		$query->free_result();
		return $res; 
	}
	public function get_insert($table,$data, $type='normal'){
		if($type=='normal'){
			$this->db->insert($table, $data); 
			return ($this->db->affected_rows() >0)? $this->db->insert_id(): FALSE;
		}
		elseif($type=='batch'){
			$this->db->insert_batch($table, $data); 
			return $this->db->affected_rows() == sizeof($data);
		}
		
	}
	function undoEntry($dataArr){
		if(!is_array($dataArr)) return FALSE;
		foreach($dataArr as $key=>$val){
			$val = explode(' ', $val);
			$this->get_delete($key,array($val[0]=>array($val[1]) ));
		}
		return TRUE;
	}
	public function getData($from,$id=NULL,$type='array'){
		if($id==NULL){
			$res = $this->get_search('SELECT * FROM '.$from,$type);
		}
		elseif(is_array($id)){
			$query = 'SELECT * FROM '.$from.' WHERE ';
			foreach($id as $key=>$val){ $query .= $key.' = '.$val.' AND '; }
			$res = $this->get_search( trim($query,'AND '),$type);
		}
		else{
			if($from=='groups') $id_field = 'id';
			else $id_field = 'fld_int_id';
			$res = $this->get_search('SELECT * FROM '.$from.' WHERE '.$id_field.'='.$id,$type);
		}
		return ($res!=NULL)?$res: FALSE;
	}
	function count_message($ms_type, $message){
		$size = 160; 
		$ms_type =  $ms_type;
		$len = mb_strlen($message,'UTF-8'); 
		$msg_len = 0;
		$res = array();
		if     ( $ms_type == 2 && $len <= 70 ) $size = 70;
		else if( $ms_type == 2 && $len > 70  ) $size = 67;
		else if( $ms_type != 2 && $len <= 160) $size = 160;
		else if( $ms_type != 2 && $len > 160 ) $size = 153;		
		
		$char_len= ( $len-((int)($len/$size)*$size));
		
		if($char_len!=0){
			$msg_len = (int)(($len+$size)/$size);
		}
		else{
			$msg_len = (int)(($len)/$size);
		}
		$res['msg_len']= $msg_len;
		
		if($char_len!=0){
			$res['char_len'] =  ($size- $char_len) ;
		}else{
			$res['char_len'] = $char_len;
		}		
		
		return $res;
		
	}
	public function getCountry($id){
		$res = $this->get_search('SELECT c.fld_int_id AS fld_int_id,c.fld_chr_code AS fld_chr_code FROM country c INNER JOIN users_country u WHERE c.fld_int_id = u. fld_country_id AND u.fld_user_id='.$id,'object');
		if( $res ==NULL) return FALSE;
		return array($res[0]->fld_int_id,$res[0]->fld_chr_code);
	}
	public function getPrefix($countryId){
		$res = $this->get_search('SELECT p.prefix AS prefix,p.operator_id AS operator FROM prefix p INNER JOIN operator o WHERE p.operator_id = o.fld_int_id AND o.country_id='.$countryId,'object');
		if( $res ==NULL) return FALSE;
		foreach($res as $row){
			$arr[$row->operator][] = $row->prefix;
		}
		return $arr;
	}
	public function verifyNumber($data,$userId){
		if(!is_array($data)) return ('Invalid Format');
		$number_validated = array();
		$country = $this->getCountry($userId);
		$countryId = $country[0];
		$prefix = $this->getPrefix($countryId);
		
		$alPrifix = array();
		foreach($prefix as $key => $val){
			foreach($val as $val1){
				$alPrifix[$val1]= $key;
			}
		}
		foreach($data as $val){
			$val = trim($val);
			if(ctype_digit($val)){
				
				if( strlen($val)== (10+strlen($country[1])) ){
					
					$numCountryId = substr($val,0,strlen($country[1]));
					$numPrefix = substr($val,-10,3);
					$number = substr($val,3);
					if($numCountryId === $country[1]){
						if(isset($alPrifix[$numPrefix])){
							if(isset($number_validated[$alPrifix[$numPrefix]]) && in_array($number,$number_validated[$alPrifix[$numPrefix]]) ){
								$number_validated['repeat'][] = $number;
							} 
							else $number_validated[$alPrifix[$numPrefix]][]= $number;
						}
						else $number_validated['fault'][] = $val;
					}
					else $fault[] = $val;
				}
				elseif( strlen($val)==10 ){
					
					$numPrefix = substr($val,0,3);
					$number = $val;
					if( isset($alPrifix[$numPrefix]) ){ 
						if(isset($number_validated[$alPrifix[$numPrefix]]) && in_array($number,$number_validated[$alPrifix[$numPrefix]])){
							$number_validated['repeat'][] = $number;
						} 
						else $number_validated[$alPrifix[$numPrefix]][]= $number;
					}
					else $number_validated['fault'][] = $val;
				}
				else{
					$number_validated['fault'][] = $val;
				}
			}
			else{
				$number_validated['fault'][] = $val;
			}
		}
		
		return $number_validated;
	}
	
	public function checkBalance($balanceType,$amount,$oper=NULL,$userId=NULL){
		$userId = ($userId!=NULL)?$userId: $this->session->userdata('userId');
		if($balanceType == 'seperate'){
			$balance = (int)$this->getBalance($userId,$balanceType,$oper);
			if( $amount > $balance ){
				$opName = $this->getData('operator',array('fld_int_id'=>$oper),'object');
				return 'Insufficient Balance of '.strtoupper($opName[0]->acronym).' By '.($amount- $balance).' Units';
			}
			return $balance;	
		}
	}
	public function getBalance($userid,$baltype,$operator=NULL){
		$res = NULL;
		if($baltype == 'seperate'){
			if($operator==NULL) return FALSE;
			$res = $this->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="'.$operator.'" LIMIT 1','object');
		}
		elseif($baltype =='appbal'){
			$res = $this->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="appbal" LIMIT 1','object');
		}
		if($res == NULL) return 0;
		else{
			return $res[0]->amount;
		}
	}
	public function updateBalance($userid,$unit,$baltype,$operator=NULL){
		$res = NULL;
		$type ='';
		if($userid ==1 ) return TRUE;
		if($baltype == 'seperate'){
			if($operator==NULL) return FALSE;
			$type = $operator;
			$res = $this->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="'.$operator.'" LIMIT 1','object');
		}
		elseif($baltype =='appbal'){
			$type = 'appbal';
			$res = $this->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="appbal" LIMIT 1','object');
		}
		if($res == NULL){
			return $this->get_insert('user_balance',array('fld_user_id'=>$userid,'balance_type'=>$type,'amount'=>$unit ));
		}
		else{
			$this->db->where('fld_user_id', $userid);
			$this->db->where('balance_type', $type);
			$this->db->update('user_balance', array('amount' => $unit)); 
			
			return $this->db->affected_rows() >0;
		}
	}
	
	public function pushApiState(){
		$res = $this->sendsms_m->get_search('SELECT * FROM system_flag WHERE fld_type="push_api"','object');
		if($res ==NULL) return TRUE;
		else{
			return ($res[0]->fld_val==0)?FALSE:TRUE;
		}
	}
	/* return all the users assigned privileges 
	*/
	public function getUserPrivileges($id=NULL){
		$userId = ($id==NULL)? $this->session->userdata('userId'):$id;
		$res = $this->get_search("SELECT fld_privileges FROM users_privileges WHERE fld_user_id=".$userId,'object');
		$priv = array();
		if ($res !== NULL){
			if(sizeof($res)==1){
				if($res[0]->fld_privileges=='default'){
					$res = $this->get_search("SELECT p.fld_privileges AS fld_privileges FROM group_privileges p INNER JOIN users_groups g WHERE p.fld_group_id=g.group_id AND g.user_id=".$userId,'object');
				}
			}
			foreach ($res as $row){	$priv[] = $row->fld_privileges;	}
		   	return $priv;
		} 
		return FALSE;
	}
	public function hasPrivileges($name,$userid){
		$res = $this->get_search('SELECT u.fld_int_id FROM feature f INNER JOIN user_feature u WHERE u.fld_user_id='.$userid.' AND f.fld_int_id = u.fld_feature_id AND f.fld_chr_feature="'.trim(strtolower($name )).'"','object');
		return $res || FALSE;
	}
// model ends
}









