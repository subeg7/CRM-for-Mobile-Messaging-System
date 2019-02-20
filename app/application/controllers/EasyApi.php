<?php defined('BASEPATH') OR exit('No direct script access allowed');

class EasyApi extends CI_Controller {
	
	/***************
				1501 = 'Invalid key';
				1502 = 'User Temporary Disabled';
				1503 = 'Invalid Sender ID';
				1504 = 'Invalid Message.';
				1505 = 'Invalid Destination.';
				1506 = 'Invalid TYPE.';
				1507 = 'Insufficient credit.';
				1509 = 'externalInfo Field Contains Invalid Character ';
				 				
				1701 = 'Request Accepted.';
				1702 = 'Request Accepted but some of the blocked numbers contain.';
				
				1801 = 'Access denied temporarily due to missing connection on mobile operator network or system Maintainance';
				1900 = 'sent sms fail';
				
		***************/
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('language','url'));	
		$this->load->model(array('api_m','sendsms_m'));
		
	}
	function index(){
		
		/********** Checking values sent in request***********/
		$key  	   = (isset($_REQUEST['key']))?	  		trim($_REQUEST['key'])     		: die('1501');
		$sender    = (isset($_REQUEST['source']))?  trim(str_replace(' ', '',$_REQUEST['source']))	: die('1503');
		$mobile    = (isset($_REQUEST['destination']))? $_REQUEST['destination']   		: die('1505');
		//$message   = (isset($_REQUEST['message']))?rawurldecode($_REQUEST['message']) 		: die('1504');
		$message   = (isset($_REQUEST['message']))?		urldecode($_REQUEST['message']) : die('1504');
		$extInfo   = (isset($_REQUEST['externalInfo']))?	$_REQUEST['externalInfo'] 	: '';	
		$msg_type  = (isset($_REQUEST['type']))?(($_REQUEST['type']>=1 && $_REQUEST['type']<=4)?$_REQUEST['type']:die('1506')):die('1506');
		
		$sendMsgType = (strlen($message) != strlen(utf8_decode($message)))?array(2,4):array(1,3);
		if(!in_array($msg_type ,$sendMsgType)) die('1506');
		// verifying if externalInfo feild contains extra character other than alpha-numeric
		if($extInfo!=NULL){
			$extInfo = ( ctype_alnum($extInfo) ) ? $extInfo : die('1509');
			if(strlen($extInfo)>35) die('1509');
		}
		// for  old api users
		if($sender =='33234' || $sender =='3234' || $sender=='none,none' || $sender=='33234,none' || $sender=='none,33234' || $sender=='3234,none' || $sender =='none,3234' || $sender=='33234,33234' || $sender=='3234,3234') $sender ='none';
		/*****************************************************************************/
		
		/*
		 Verifying the user authentication
		*/
		$userdata = $this->api_m->api_user_varification($key); 
		if(!is_array($userdata)) die( $userdata);
		
		$senderid = $this->api_m->getSenderId($sender,$userdata['userid']);
		if($senderid===FALSE) die('1503');
		
		$number = $this->sendsms_m->getValidNumbers('nonappuser',NULL,NULL,$mobile ,$userdata['userid']);
		if(!is_array($number) || sizeof($number)==0) die('1505');
			
		$apiState = $this->sendsms_m->pushApiState();
		$privi =  $this->sendsms_m->getUserPrivileges($userdata['userid']);
		$operator = array_keys($number); 
		
		if(sizeof($operator)>1 && !$this->sendsms_m->hasPrivileges('avoid',$userdata['userid']) ){
			die('1505');
		}
		foreach($operator as $row){
			if(!isset($senderid[$row])) $senderid[$row]=$row.'_none';
			if($apiState){
				$res = $this->sendsms_m->quickSend(array(
								'numbers'=>implode(',',$number[$row]),
								'addressbook'=>NULL,
								'message'=>$message,
								'messageType'=>$msg_type,
								'senderId'=>$senderid[$row] ,
								'userId'=>$userdata['userid'],
								'date'=>time(),
								'agentId'=>$userdata['reseller'],
								'excludeNumber'=>NULL,
								'queid'=>uniqid(),
								'balanceType'=>$userdata['balanceType'],
								'sendby'=>'nonappuser',
								'userdata'=>$extInfo,
								'privi'=>$privi,
							));
				if( $res!='1701'){
					die($res);
				}
				
			}
			else{
				$res = $this->sendsms_m->addQue(array(
							'numbers'=>implode(',',$number[$row]),
							'addressbook'=>NULL,
							'message'=>$message,
							'schedule'=> 0,
							'messageType'=>$msg_type,
							'eventName'=>'none',
							'senderId'=>$senderid[$row],
							'schedulerDates'=>'none',
							'userId'=>$userdata['userid'],
							'date'=>time(),
							'agentId'=>$userdata['reseller'],
							'excludeNumber'=>NULL,
							'queid'=>uniqid(),
							'balanceType'=>$userdata['balanceType'],
							'sendby'=>'nonappuser',
							'privi'=>$privi,
						));
				if( $res!='1701'){
					die($res);
				}
			}
				
		}
		die('1701');
	}	
	
	public function validateNumbers(){
		
		$key 	= (isset($_REQUEST['key']))? $_REQUEST['key'] : die('1501');
		$mobile = (isset($_REQUEST['destination']))?(( $_REQUEST['destination']==' ')? die(json_encode('No Destination found for validation') ): $_REQUEST['destination']): die(json_encode('No Destination found for validation'));
		
		
		$userdata = $this->api_m->api_user_varification($key); 
		if(!is_array($userdata)) die( $userdata);
		$mobile = explode(',',trim($mobile, ','));
		
		$numbers = $this->sendsms_m->verifyNumber($mobile,$userdata['userid']);
		$operQuery = $this->sendsms_m->get_search('SELECT * FROM operator','object');
		$operDetail = array();
		foreach($operQuery as $oper){
			$operDetail[$oper->fld_int_id] = strtoupper($oper->acronym);
		}
		$numberDetail = array();
		foreach($numbers as $key=>$val){
			if(isset($operDetail[$key])) $numberDetail[$operDetail[$key]]=implode(',',$val); 
			else $numberDetail[strtoupper($key)] = implode(',',$val); 
		}
		die(json_encode($numberDetail));		
	}
	
	function balance(){
		$key = (isset($_REQUEST['key']))? $_REQUEST['key'] : die('1501');
		$userdata = $this->api_m->api_user_varification($key); 
		if(!is_array($userdata)) die( $userdata);
		
		$operQuery = $this->sendsms_m->get_search('SELECT * FROM operator','object');
		$balanceDetail = array();
		$balances = array();
		foreach($operQuery as $oper){
			$balances[strtoupper($oper->acronym)] = $this->sendsms_m->getBalance( $userdata['userid'],'seperate',$oper->fld_int_id);
		}
		
		die(json_encode($balances));
	}
	/*************************End of Class*****************************/
}