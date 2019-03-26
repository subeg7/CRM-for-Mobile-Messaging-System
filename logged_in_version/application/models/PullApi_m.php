<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class PullApi_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('sendsms_m');
		$this->data = array(
							'empty'=>'Please Enter valid service key',
							'no_service'=>'Sorry, No services found according to your query',
						);
	}

	public function authPull($data){
		$err 		= NULL;
		$shId 		= NULL; // shortcode ID
		$mainKeyId 	= 0;
		$keyMessage = NULL;
		$subKeyId 	= 0;
		$skeyMessage= NULL;
		$text 		= NULL;
		$category 	= NULL;
		$userId		= NULL;
		$state 		= NULL;
		$dedicated  = NULL;
		$operator	= NULL;
		$addbook 	= 0;
		$template	= 0;

		$textCount  = $this->sendsms_m->count_message(1,$data['text']);
		if($data['sender']==NULL) $err = 'empty_sender';
		if($err == NULL){ // if no error proceed
			$operator = $this->getOperator($data['sender']);
		}
		if($err == NULL){ // if no error proceed
			if($data['shortcode']==NULL) $err = 'empty_shortcode'; // before data query
		}
		if($err == NULL){ // if no error proceed
			$shIds = $this->sendsms_m->get_search('SELECT * FROM shortcode WHERE fld_chr_name="'.trim($data['shortcode']).'"','object');
			if($shIds == NULL) $err =  'invalid_shortcode'; // after data query
			else{
				$shId = $shIds[0]->fld_int_id;
				$trackId = (string)uniqid().'tr';
				// if shortcode is dedicated terminate process from here
				if($shIds[0]->assign_to > 1){

					$userId	= $shIds[0]->assign_to;
					$routeUrl =$this->sendsms_m->get_search('SELECT * FROM user_route WHERE fld_user_id='.$shIds[0]->assign_to,'object');
					/*if($routeUrl==NULL) $err =  'no_routeurl';
					else{*/
					if($routeUrl!==NULL){
						$this->reportEntry('mo',array(
											'id'		=>uniqid().(string)rand(10,100),
											'key'		=>0,
											'subkey'	=>0,
											'user'		=>$userId,
											'track'		=>$trackId,
											'shortcode'	=>$shId,
											'count'		=>$textCount['msg_len'],
											'sender'	=>$data['sender'],
											'text'		=>$data['text'],
											'error'		=>0,
											'detail'	=>'',
											'operator'	=>$operator
					   ));
						return array(
							'category'	=>'dedicatedRoute',
							'mainkey'	=>0,
							'subkey'	=>0,
							'shortcode'	=>$shId,
							'trackId'	=>$trackId,
							'mainkeyMsg'=>'none',
							'subMsg' 	=>'none',
							'user'		=>$userId,
							'sender'	=>$data['sender'],
							'text'		=>$data['text'],
							'url'		=>$routeUrl[0]->fld_route_url,
							'nonce'		=>$routeUrl[0]->nonce,
							'operator'	=>$operator,

						);
					}
				}
			}

		}
		if($data['text']==NULL || $data['text']=='' || $data['text']==' ') $err = 'empty_text';
		if($err == NULL){ // if no error proceed
			$text = explode(' ',$data['text']);
			$mainKeyDetail = $this->sendsms_m->get_search('SELECT c.category AS category,k.sucess_message AS sMessage,k.disable_message AS dMessage, k.fail_message AS fMessage,k.state AS state,k.fld_user_id AS user,k.fld_int_id AS keyid,k.fld_addbook_id AS addbookid,k.fld_temp_id AS templates FROM pull_keys k INNER JOIN category c WHERE k.keys_name="'.trim($text[0]).'" AND k.main_keys_id = 0 AND k.state!=0 AND k.fld_category_id  = c.fld_int_id AND k.fld_shortcode_id='.$shId,'object');
			if($mainKeyDetail == NULL || $mainKeyDetail[0]->state==9) $err = 'invalid_mainkey'; // after data query
			else{

				$category  = $mainKeyDetail[0]->category;
				$userId	   = $mainKeyDetail[0]->user;
				$stat	   = $mainKeyDetail[0]->state;
				$mainKeyId = $mainKeyDetail[0]->keyid;

				$keyMessage = array('sucess'=>$mainKeyDetail[0]->sMessage,'fail'=>$mainKeyDetail[0]->fMessage,'disable'=>$mainKeyDetail[0]->dMessage);

				// if user has routing assign don't process further
				//$isroute = $this->is_route($userId);
				//if($isroute!==FALSE && $stat==1){
				if(strtolower($category)=='rt' && $stat==1){
					$trackId = (string)uniqid().'tr';

					$this->reportEntry('mo',array(
										'id'		=>uniqid().(string)rand(10,100),
										'key'		=>$mainKeyId,
										'subkey'	=>0,
										'user'		=>$userId,
										'track'		=>$trackId,
										'shortcode'	=>$shId,
										'count'		=>$textCount['msg_len'],
										'sender'	=>$data['sender'],
										'text'		=>$data['text'],
										'error'		=>0,
										'detail'	=>'',
										'operator'	=>$operator
				   ));
				   $isroute = $this->is_route($userId);
				   if($isroute!==FALSE && $stat==1){
					   return array(
									'route'		=>'route',
									'url'		=>$isroute[0],
									'nonce'		=>$isroute[1],
									'category'	=>$category,
									'mainkey'	=>$mainKeyId,
									'subkey'	=>0,
									'shortcode'	=>$shId,
									'trackId'	=>$trackId,
									'mainkeyMsg'=>$keyMessage,
									'subMsg' 	=>'none',
									'user'		=>$userId,
									'sender'	=>$data['sender'],
									'text'		=>$data['text'],
									'operator'	=>$operator,

								);
				   }
				   else{
					    $err =  'no_routeurl';
				   }
				}//end of isroute
				/////////////////////////////////////////////////
				if(in_array($mainKeyDetail[0]->category,$data['subkey_category']) && $stat==1){
					$subKeyId = $this->sendsms_m->get_search('SELECT fld_int_id,disable_message,sucess_message,fail_message,fld_temp_id,fld_addbook_id FROM pull_keys WHERE keys_name="'.trim($text[1]).'" AND main_keys_id ='.$mainKeyId,'object');
					if($subKeyId==NULL){
						$err = 'invalid_subkey';
						$this->data['invalid_subkey']=$mainKeyDetail[0]->fMessage;
					}
					else{
						$skeyMessage = array('sucess'=>$subKeyId[0]->sucess_message,'fail'=>$subKeyId[0]->fail_message,'disable'=>$subKeyId[0]->disable_message,'addressbook'=>$subKeyId[0]->fld_addbook_id,'template'=>$subKeyId[0]->fld_temp_id );
						$subKeyId = $subKeyId[0]->fld_int_id;

					}
				}
				elseif($stat != 1 ){
					$err = 'disable_key';
				}
			}
		}
		if($err == NULL){ // if no error proceed
			$trackId = (string)uniqid().'tr';
			$this->reportEntry('mo',array(
								'id'		=>uniqid().(string)rand(10,100),
								'key'		=>$mainKeyId,
								'subkey'	=>$subKeyId,
								'user'		=>$userId,
								'track'		=>$trackId,
								'shortcode'	=>$shId,
								'sender'	=>$data['sender'],
								'count'		=>$textCount['msg_len'],
								'text'		=>$data['text'],
								'error'		=>0,
								'detail'	=>'',
								'operator'	=>$operator
		   ));
			return array(
						'category'	=>$category,
						'mainkey'	=>$mainKeyId,
						'subkey'	=>$subKeyId,
						'shortcode'	=>$shId,
						'trackId'	=>$trackId,
						'mainkeyMsg'=>$keyMessage,
						'subMsg' 	=>($subKeyId!=0)?$skeyMessage:'none',
						'user'		=>$userId,
						'sender'	=>$data['sender'],
						'text'		=>$data['text'],
						'route'		=>'none',
						'url'		=>'',
						'nonce'		=>'',
						'operator'	=>$operator,

					);
		}
		else{
			$calbackMesage = NULL;
			$trackId = (string)uniqid().'tr';
			if( $err == 'empty_text' ||  $err == 'invalid_mainkey'){
				$mainKeyId = 0;
				$userId = 0;
				$calbackMesage = ( $err == 'empty_text')?$this->data['empty']:$this->data['no_service'];
			}
			elseif( $err == 'invalid_subkey'){
				$subKeyId = 0;
				$calbackMesage = $this->data['invalid_subkey'];
			}
			elseif( $err == 'disable_key'){
				$calbackMesage = $keyMessage['disable'];
			}
			elseif($err =='no_routeurl'){
				$calbackMesage = $this->data['no_service'];
			}
			$this->reportEntry('mo',array(
								'id'		=>uniqid().(string)rand(10,100),
								'key'		=>$mainKeyId,
								'subkey'	=>$subKeyId,
								'user'		=>$userId,
								'track'		=>$trackId,
								'shortcode'	=>$shId,
								'sender'	=>$data['sender'],
								'text'		=>$data['text'],
								'count'		=>$textCount['msg_len'],
								'error'		=>1,
								'detail'	=>$err,
								'operator'	=>$operator
		   ));
		   $calbackCount = $this->sendsms_m->count_message(1,$calbackMesage);
		   $this->reportEntry('mt',array(
								'id'		=>uniqid().(string)rand(10,100),
								'key'		=>$mainKeyId,
								'subkey'	=>$subKeyId,
								'user'		=>$userId,
								'track'		=>$trackId,
								'shortcode'	=>$shId,
								'sender'	=>$data['sender'],
								'count'		=>$calbackCount['msg_len'],
								'text'		=>$calbackMesage, // sending error message
								'error'		=>1,
								'detail'	=>$err,
								'operator'	=>$operator
		   ));
		}
		return $calbackMesage;
	}

	public function reportEntry($type, $data){
		return $this->sendsms_m->get_insert('pull_report',array(
													'fld_int_id'=>$data['id'],
													'mainkey'=>$data['key'],
													'sub_key'=>$data['subkey'],
													'fld_user_id'=>$data['user'],
													'track'=>$data['track'],
													'sender'=>$data['sender'],
													'shortcode'=>$data['shortcode'],
													'mo_mt'=>($type=='mo')?1:2,
													'count'=>$data['count'],
													'text'=>$data['text'],
													'error'=>$data['error'],
													'error_detail'=>$data['detail'],
													'operator'	=>$data['operator'],
													'date'=>time()
												));

	}
	public function is_route($userid){
		$query = $this->db->query("SELECT * FROM user_route WHERE fld_user_id =".$userid);
		$row = $query->row();
		if (isset($row)){
				return array($row->fld_route_url,$row->nonce);
		}else return FALSE;
	}
	public function is_dedicated($shortcode){
		$query = $this->db->query("SELECT * FROM shortcode WHERE fld_chr_name ='".trim($shortcode)."' AND assign_to != 1");
		$row = $query->row();
		if (isset($row)){
				return $row->assign_to;
		}else return FALSE;
	}

	public function getOperator($cellNumber){
		$prefix = array();
		$numPrefix = NULL;
		if(strlen(trim($cellNumber) ) == 10){
			$numPrefix = substr($cellNumber,0,3);
			//return $prefix[$numPrefix];
		}
		elseif(strlen(trim($cellNumber) ) == 13){
			$numPrefix = substr($cellNumber,-10,3);
			//return $prefix[$numPrefix];
		}
		$query = $this->db->query("SELECT operator_id FROM prefix p  WHERE prefix='".$numPrefix."'");
		$row = $query->row();
		return (isset($row))?$row->operator_id:0;
	}

	/*********
	below these two functions adds one more further step to send sms but it removes dependencies among this model with
	others system models which isolates api model from rest model
	**********/
	public function quickSend($data){
		return $this->sendsms_m->quickSend($data);
	}
	public function addQue($data){
		return $this->sendsms_m->addQue($data);
	}
	public function pushApiState(){
		return $this->sendsms_m->pushApiState();
	}
	public function get_search($sql, $type='array'){
		return $this->sendsms_m->get_search($sql, $type);
	}
	public function getData($from,$id=NULL,$type='array'){
		return $this->sendsms_m->getData($from,$id,$type);
	}
	public function count_message($ms_type, $message){
		return $this->sendsms_m-> count_message($ms_type, $message);
	}
// model ends
}
