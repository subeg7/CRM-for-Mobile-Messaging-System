<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PullApi extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->config->load('easy_config');
		$this->load->model(array('pullApi_m'));
		
	}
	
	public function index(){
		if(isset($_REQUEST['nonce'])){
			if($this->config->item('pull_nonce')!=trim($_REQUEST['nonce'])){ die(show_404());	}	
		}
		else{
			die(show_404());
		}
		$req_shortcode 	= (isset($_REQUEST['shortcode']))?trim($_REQUEST['shortcode']) : NULL;
		$req_sender 	= (isset($_REQUEST['sender']))? trim($_REQUEST['sender']) : NULL;
		$req_text 		= (isset($_REQUEST['text']))?	strtolower(trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $_REQUEST['text']))) : NULL;
		$res 			= $this->pullApi_m->authPull(array(
												'sender'	=> $req_sender,
												'shortcode'	=>$req_shortcode,
												'text'=>$req_text,
												'subkey_category'=>$this->config->item('subkey_category')
											));
				
		if(!is_array($res))	die($res); // if authentication fail return error/authentication message
		
		$detail = '';
		$error_q = 0;
		$calbackMesage = '';
		if(in_array(strtolower($res['category']),$this->config->item('subkey_category'))){
			if( strtolower($res['category']) == 'vp'){
			
				$calbackMesage = $res['subMsg']['sucess'];
			}
			elseif( strtolower($res['category']) == 'ptp'){
				
				$template = $this->pullApi_m->get_search('SELECT t.fld_chr_msg AS fld_chr_msg, t.fld_int_mst AS fld_int_mst,t.fld_user_id AS fld_user_id,u.fld_reseller_id AS fld_reseller_id,u.fld_balance_type AS fld_balance_type FROM sms_templates t INNER JOIN users u WHERE t.fld_int_id='.$res['subMsg']['template'].' AND u.id=t.fld_user_id','object');
				$operator = $this->pullApi_m->get_search('SELECT * FROM operator','object');
				$senderIdArr = array();
				foreach( $operator as $row ){
					$senderIdArr[] = $row->fld_int_id.'_none';
				}
				$result = NULL;
				if($this->pullApi_m->pushApiState()){
					$result = $this->pullApi_m->quickSend(array(
							'numbers'=>NULL,
							'addressbook'=>$res['subMsg']['addressbook'],
							'message'=>$template[0]->fld_chr_msg,
							'schedule'=> 0,
							'messageType'=>$template[0]->fld_int_mst,
							'eventName'=>'none',
							'senderId'=>implode(',',$senderIdArr),
							'schedulerDates'=>'none',
							'userId'=>$template[0]->fld_user_id,
							'date'=>time(),
							'agentId'=>$template[0]->fld_reseller_id,
							'excludeNumber'=>NULL,
							'queid'=>uniqid(),
							'balanceType'=>$template[0]->fld_balance_type,
							'sendby'=>'pulluser'
						));
				}
				else{
					$result = $this->pullApi_m->addQue(array(
								'numbers'=>NULL,
								'addressbook'=>$res['subMsg']['addressbook'],
								'message'=>$template[0]->fld_chr_msg,
								'schedule'=> 0,
								'messageType'=>$template[0]->fld_int_mst,
								'eventName'=>'none',
								'senderId'=>implode(',',$senderIdArr),
								'schedulerDates'=>'none',
								'userId'=>$template[0]->fld_user_id,
								'date'=>time(),
								'agentId'=>$template[0]->fld_reseller_id,
								'excludeNumber'=>NULL,
								'queid'=>uniqid(),
								'balanceType'=>$template[0]->fld_balance_type,
								'sendby'=>'nonappuser'
							));
				}
				if($result=='1701'){
					$calbackMesage = $res['subMsg']['sucess'];
				}
				elseif($result=='1502'){
					$calbackMesage = 'Sending SMS Fail : Account Disabled Temporary';
				}
				elseif($result=='1507'){
					$calbackMesage = 'Sending SMS Fail : Insufficient credit';
				}
				elseif($result=='1507'){
					$calbackMesage = 'Sending SMS Fail : Insufficient credit';
				}
				elseif($result=='1801'){
					$calbackMesage = 'Sending SMS Fail : missing connection on mobile operator network';
				}
				else{
					$calbackMesage = 'Sending SMS Fail';
				}				
			}
		}else{
			
			if( strtolower($res['category']) == 'is'){
				$subkey = explode(' ',$res['text']);
				$isMsg = $this->pullApi_m->getData('upload_data',array('key_id'=>$res['mainkey'],'identity'=>'"'.$subkey[1].'"') ,'object');
				if($isMsg!==FALSE) $calbackMesage = $isMsg[0]->message;
				else  $calbackMesage = $res['mainkeyMsg']['fail'];
			}
			elseif( strtolower($res['category']) == 'fb'){
				$calbackMesage = $res['mainkeyMsg']['sucess'];
			}
			
			elseif( strtolower($res['category']) == 'rt' || strtolower($res['category']) == 'dedicatedroute'){
				
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$res['url']);
				curl_setopt($ch,CURLOPT_HEADER,0);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_POST,1);
				
				$query_data = array(
								'nonce'		=>$res['nonce'],
								'shortcode'	=>$req_shortcode,
								'sender'	=>$req_sender,
								'text'		=>$req_text,
						 );
							 
				curl_setopt($ch,CURLOPT_POSTFIELDS,$query_data);
				$result = curl_exec($ch);
				curl_close($ch);
				if($result===FALSE){
					$calbackMesage = (strtolower($res['category']) == 'rt')?$res['mainkeyMsg']['fail']:'Sorry, No services found according to your query';
					$error_q = 1;
					$detail ='fail_query';
				}
				else{
					$calbackMesage = $result;
				}
	
			}//end of rt
		}
		
		$msgType = (strlen($calbackMesage) != strlen(utf8_decode($calbackMesage)))?2:1;
		$calbackCount = $this->pullApi_m->count_message($msgType ,$calbackMesage);
		$this->pullApi_m->reportEntry('mt',array(
											'id'		=>uniqid().(string)rand(10,100),
											'key'		=>$res['mainkey'],
											'subkey'	=>$res['subkey'],
											'user'		=>$res['user'],
											'track'		=>$res['trackId'],
											'shortcode'	=>$res['shortcode'],
											'sender'	=>$res['sender'],
											'count'		=>$calbackCount['msg_len'],
											'text'		=>$calbackMesage, // sending error message
											'error'		=>$error_q,
											'detail'	=>$detail,
											'operator'	=>$res['operator'],
		   								));
	   	die($calbackMesage);
	}
	// end of class
}
