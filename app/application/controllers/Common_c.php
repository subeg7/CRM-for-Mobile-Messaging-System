<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Common_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('curd_m','common_m'));
		$this->userPrivileges = $this->vas->getUserPrivileges();
			
	}
	public function load_View($type,$id=NULL){
		date_default_timezone_set("Asia/Kathmandu");
		if($type=='home'){
			
			$userid = $this->session->userdata('userId');
			$opeArr = array();
			$operator = $this->curd_m->getData('operator',array('country_id'=>$this->session->userdata('countryId')),'object');
			
			foreach($operator as $row){
				$opeArr[$row->fld_int_id] = strtoupper($row->acronym).' [ '. ucwords($row->description).' ]';
			}
			$this->data['operator'] = $opeArr;
			$detail= $this->curd_m->getData('users',array('id'=>$userid),'object');
			$this->data['data'] = $detail[0];
			// fetching balance
			if($detail[0]->fld_balance_type=='SEPERATE'){
				$bal = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid,'object');
				if($bal!=NULL){
					$balance = array();
					foreach($bal  as $row){
						
						if($row->balance_type=='appbal') $balance['APP. BALANCE'] = $row->amount;
						else $balance[$opeArr[$row->balance_type]] = $row->amount;
					}
					$this->data['balance'] = $balance;
				}
				else{
					$this->data['balance'] = 'none';
				}			
			}
			else{
				$this->data['balance'] = 'postpaid';
			}
			// fetching transaction
			$query = '';
			if($userid==1){
				$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' GROUP BY fld_int_career'; 
			}
			elseif(in_array('USER_MANAGE',$this->userPrivileges)){
				$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_int_agentid='.$userid.' AND fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' GROUP BY fld_int_career'; 
			}
			elseif(!in_array('USER_MANAGE',$this->userPrivileges)){
				$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_int_userid='.$userid.' AND fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' GROUP BY fld_int_career'; 
			}
			$tranArr = array();
			$transaction = $this->curd_m->get_search($query,'object');
			if($transaction!=NULL){
				foreach($transaction as $row){
					$tranArr[$opeArr[$row->fld_int_career]] = $row->sums;
				}
				$this->data['transaction'] = $tranArr;
			}
			else{
				$this->data['transaction']='none';
			}
			//fetching notification
			$datePrev = strtotime(date("Y/m/d"))-(60*60*24*7);
			/*if(in_array('USER_MANAGE',$this->userPrivileges)){
				$view = ($this->session->userdata('userId')==1)?'view_admin':'view_reseller';
				$query ='SELECT n.fld_int_id AS fld_int_id ,n.description AS description ,u.company AS company FROM notice n INNER JOIN users u WHERE n.'.$view.' = 0 AND date BETWEEN '.$datePrev." AND ".time().' AND type IN( 3,8) AND u.id=n.action_by AND n.action_to=0'; 
			}
			else{
				$query ='SELECT fld_int_id AS fld_int_id ,description AS description ,action_by AS company FROM notice  WHERE action_to = '.$userid.' AND view_client = 0 AND date BETWEEN '.$datePrev." AND ".time(); 
			}*/
			$query ='SELECT * FROM activity_log  WHERE user_id = '.$userid.' AND view = 0 AND type IN( 3,8) AND date BETWEEN '.$datePrev." AND ".time(); 
			$notification = $this->curd_m->get_search($query,'object');
			
			$this->data['notification']= ($notification==NULL)?'none':$notification;
			$this->data['privi'] = $this->userPrivileges;
			$this->load->view('viewCollect/homeView_v',$this->data );
		}
		elseif($type=='verifyNumber'){ 
			if($id==NULL) die('Invalid Request');
			$addBookNumber = $this->curd_m->getData('addressbook',array('fld_int_addb_id'=>$id),'object');
			if($addBookNumber===FALSE) die('AddressBook Empty!!!');
			foreach($addBookNumber as $row){
				$this->data['numbers'][] = array($row->fld_int_id,$row->fld_chr_name,$row->fld_chr_phone);
			}
			$this->load->view('viewCollect/verifyNumber',$this->data );
			
		}
		else if($type == 'faultVerify'){
			$this->load->view('viewCollect/faultVerify' );
		}
		else if($type == 'searchSenderId'){
			$this->load->view('viewCollect/searchSenderId' );
		}
		else if($type == 'searchkeys'){
			$res = $this->curd_m->getData('category',NULL,'object');
			$this->data['category'] = ($res===FALSE)?'none':$res;
			if($this->session->userdata('userId')==1){
				$shc = $this->curd_m->get_search('SELECT fld_chr_name AS name ,fld_int_id AS id FROM shortcode','object');
			}
			elseif(in_array('USER_MANAGE',$this->userPrivileges)){
				$shc = $this->curd_m->get_search('SELECT s.fld_chr_name AS name ,s.fld_int_id AS id FROM shortcode s INNER JOIN user_shortcode u WHERE u.fld_shortcode_id = s.fld_int_id AND u.fld_user_id='.$this->session->userdata('userId'),'object');
			}
			$this->data['priv'] = $this->userPrivileges;
			$this->data['shortcode'] = ($shc ===NULL)?'none':$shc ;
			$this->load->view('viewCollect/searchkeys',$this->data );
		}
		else if($type == 'userSearch'){
			if($this->session->userdata('userId')==1){
				$group = $this->curd_m->get_search('SELECT id, name FROM groups','object');
				$feature = $this->curd_m->get_search('SELECT * FROM feature','object');
			}
			else{
				$group = $this->curd_m->get_search('SELECT g.id AS id, g.name AS name FROM groups g INNER JOIN sub_group u WHERE u.fld_sub_group_id=g.id AND u.fld_group_id='.$this->session->userdata('groupId'),'object');
				$feature = $this->curd_m->get_search('SELECT f.fld_int_id,f.fld_chr_feature FROM feature f INNER JOIN user_feature u WHERE u.fld_feature_id=f.fld_int_id AND u.fld_user_id='.$this->session->userdata('userId'),'object');
			}
			$this->data['admin'] =($this->session->userdata('userId')==1)?'admin':'none';
			$this->data['group'] =($group==NULL)?'none':$group;
			$this->data['feature'] =($feature==NULL)?'none':$feature;
			
			$this->load->view('viewCollect/userSearch',$this->data );
		}
		elseif($type=='creditlog'){
			$this->load->view('viewCollect/creditSearch' );
			
		}
		elseif($type=='sentbox'){
			$this->data['priv'] = $this->userPrivileges;
			$this->load->view('viewCollect/outboxSearch',$this->data );
			
		}
		elseif($type=='smsreport'){
			$this->load->view('viewCollect/smsSearch' );
			
		}
		elseif($type=='settings'){
			$userid = $this->session->userdata('userId');
			$data = $this->curd_m->get_search('SELECT * FROM users WHERE id='.$userid,'object');
			if($data !=NULL){
				$operator = $this->curd_m->get_search('SELECT * FROM operator WHERE country_id='.$this->session->userdata('countryId'),'object');
				$this->data['operator'] = ($operator==NULL)?'none':$operator;
				$bal = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid,'object');
				if($bal!=NULL){
					$balance = array();
					foreach($bal  as $row){
						$balance[$row->balance_type] = $row->amount;
					}
					$this->data['balance'] = $balance;
				}
				else{
					$this->data['balance'] = 'none';
				}				
			}else{
				die('Invalid Request');
			}
			$this->data['data'] = $data;
			$this->data['admin'] =($this->session->userdata('userId')==1)?'admin':'none';
			$this->data['priv'] = $this->userPrivileges;
			if(in_array('USER_MANAGE',$this->userPrivileges)){
				$query=($userid == 1)?'SELECT count(*) AS cnt FROM users':'SELECT count(*) AS cnt FROM users WHERE fld_reseller_id='.$userid;				
				$usercount = $this->curd_m->get_search($query,'object');
				$this->data['usercount'] = ($usercount != NULL)?$usercount[0]->cnt: 0;
			}
			else{
				$this->data['usercount'] = 'none';
			}
			if($userid==1){
				$sys = $this->curd_m->get_search('SELECT * FROM system_flag','object');
				$sys_op = array();
				if($sys!=NULL){
					foreach($sys as $row){
						$sys_op[$row->fld_type] = $row->fld_val;
					}
				}
				$this->data['sys_option'] = ($sys==NULL)?'none':$sys_op;
			}
			
			$this->load->view('viewCollect/settings',$this->data );
		}
		elseif($type == 'notification'){
			$this->data['user'] =(in_array('USER_MANAGE',$this->userPrivileges))?'admin':'client';
			$this->load->view('viewCollect/notification',$this->data  );
		}
		elseif($type=='detailpull'){
			$data = $this->curd_m->get_search('SELECT * FROM shortcode','object');
			$this->data['shortcode'] = ($data==NULL)?'none':$data;
			$this->load->view('viewCollect/searchPullDetail',$this->data );
		}
		elseif($type=='uploadData'){
			$this->load->view('viewCollect/searchUpload' );
		}
		elseif($type == 'assignFeature'){
			if(!$this->common_m->isMyClinet($id)){
				$this->vas->expire_message('window','data','Users is not your Client');
			}
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			if($this->session->userdata('userId')==1){
				$this->data['feature'] = $this->curd_m->getData('feature',NULL,'object');
			}
			else{
				$this->data['feature'] = $this->curd_m->get_search('SELECT f.fld_int_id ,f.fld_chr_feature FROM feature f INNER JOIN user_feature u WHERE u.fld_feature_id=f.fld_int_id AND u.fld_user_id='.$this->session->userdata('userId').' AND f.fld_chr_feature!="subbranding"','object');
			}
			$userFeature = $this->curd_m->get_search('SELECT f.fld_int_id AS fld_int_id ,f.fld_chr_feature AS fld_chr_feature,u.extra_info AS extra_info ,u.fld_chr_title AS fld_chr_title FROM feature f INNER JOIN user_feature u WHERE u.fld_feature_id = f.fld_int_id AND u.fld_user_id = '.$id,'object');
			$this->data['userFeature'] = ($userFeature!=NULL)?$userFeature:'none';
			$privUser= $this->vas->getUserPrivileges($id);
			$this->data['usermanagePriv'] =(in_array('USER_MANAGE',$privUser))?'yes':'no';
			$this->load->view('sysManage/assignFeature',$this->data);
		}
	}
	public function verifyNumber(){
		$arr = array('valid_Number'=>array());
		if(!$this->input->post('number')) die('error');
		$operator = $this->common_m->getOperatorByCountry($this->session->userdata('userId'));
		$numb = $string = trim(preg_replace('/\s/', '', $this->input->post('number')),',');
		$res = $this->common_m->verifyNumber( explode(',',$numb ) );
		foreach($res as $key=>$row){
			if($key=='fault') $arr['fault']= $res['fault'];
			elseif($key=='repeat') $arr['repeat']= $res['repeat'];
			else{
				$arr['valid_Number']= array_merge($arr['valid_Number'],$row);
			}
		}
		
		/*foreach($operator as $row){
			if(isset($arr['valid_Number'])){
				if(isset($res[$row->fld_int_id])) $arr['valid_Number']= array_merge($arr['valid_Number'],$res[$row->fld_int_id]);
			}
			else{
				if(isset($res[$row->fld_int_id])) $arr['valid_Number']= $res[$row->fld_int_id];
			}
		}
		if(isset($res['fault'])) $arr['fault']= $res['fault'];
		if(isset($res['repeat'])) $arr['repeat']= $res['repeat'];*/
		die( json_encode($arr) );
	}
	
	public function getDetail($type,$id=NULL){
		if($type=='template'){
			$template = $this->curd_m->getData('sms_templates',array('fld_int_id'=>$id),'object');
			die( ($template ===FALSE)?'none':json_encode($template));
		}
		elseif($type=='userGateway'){
			if($this->session->userdata('userId')==1){
				/*$gw = $this->curd_m->get_search('SELECT fld_char_gw_name AS name,fld_int_id AS id FROM gateway WHERE fld_int_priority=1 AND fld_chr_operator = '.$id,'object');
				die( ($gw ===NULL)?'none':json_encode($gw));*/
				die('none');
			}else{
				$gw = $this->curd_m->get_search('SELECT g.fld_char_gw_name AS name,g.fld_int_id AS id FROM gateway g INNER JOIN user_gateway u WHERE u.fld_gw_id=g.fld_int_id AND u.fld_user_id='.$this->session->userdata('userId').' AND g.fld_chr_operator = '.$id,'object');
			die( ($gw ===NULL)?'none':json_encode($gw));
			}
			
		}
		
		
		
	}
	public function rdyDownload(){
		$code = uniqid('dwn',TRUE);
		$res = $this->curd_m->get_insert('download_code',array(
									'fld_int_id'=>uniqid(),
									'fld_user_id'=>$this->session->userdata('userId'),
									'fld_code'=>$code,
									'expireTime'=>(time()+30)
								));
		die(($res!==FALSE)?$code:'fail');
	}
	public function changeSetings($type=NULL,$val=NULL){
		if($type==NULL || $val==NULL)die('Please Enter Valid Value');
		if(trim($val) == '' || trim($val) == ' ') die('Please Enter Valid Value');
		$table = NULL; $arr = NULL;
		$val = trim(urldecode($val));
		if($type == 'org_phone'){
			$table = 'users'; $arr = array('id'=>$this->session->userdata('userId'),'phone'=>$val );
		}
		elseif($type == 'contact_person'){
			$table = 'users'; $arr = array('id'=>$this->session->userdata('userId'),'contact_person'=>$val );
		}
		elseif($type == 'contact_phone'){
			$table = 'users'; $arr = array('id'=>$this->session->userdata('userId'),'contact_number'=>$val );
		}
		elseif($type == 'email'){
			$table = 'users'; $arr = array('id'=>$this->session->userdata('userId'),'email'=>$val );
		}
		elseif($type == 'address'){
			$table = 'users'; $arr = array('id'=>$this->session->userdata('userId'),'address'=>$val );
		}
		elseif($type == 'username'){
			$table = 'users'; $arr = array('id'=>$this->session->userdata('userId'),'username'=>$val );
		}
		
		die( ($this->curd_m->get_update($table,$arr)===FALSE)?'Unable to update Settings':'sucess');
	}
	
	public function getTodayNotice(){
		$this->common_m->todayNotification($this->userPrivileges);		
	}
	public function searchNotice(){
		date_default_timezone_set("Asia/Kathmandu");	
		$userid = NULL;	
		if(!$this->input->get('from') || !$this->input->get('till') ){
			$this->vas->expire_message('grid','message','One of the Date feild is empty');
		}
		if(strtotime($this->input->get('from')) > strtotime( $this->input->get('till') ) ){
			$this->vas->expire_message('grid','message','Invalid Date range');
		}
		if($this->input->get('usreid')!=''){
			if($this->session->userdata('userId')==1){
				$users = $this->curd_m->get_search('SELECT id FROM users WHERE fld_transaction_id='.$this->input->get('usreid'),'object');
			}else{
				$users = $this->curd_m->get_search('SELECT id FROM users WHERE fld_reseller_id= '.$this->session->userdata('userId').' AND fld_transaction_id='.$this->input->get('usreid'),'object');
			}
			if($users!=NULL) $userid = $users[0]->id;
			else{
				$this->vas->expire_message('grid','message','Invalid User ID');
			}
		}
		
		$this->common_m->searchNotice(array(
										'from'=>strtotime( $this->input->get('from') ),
										'till'=>((int)strtotime( $this->input->get('till') )+86399),
										'userid'=>$userid,
										'type'=>($this->input->get('type')=='none')?NULL:$this->input->get('type'),
										'priv'=>$this->userPrivileges
									));
	}
	
	public function noticeViewState($id){
		die($this->common_m->noticeViewState($id));
	}
	

	// end of  class
}





