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

				1801 = 'Access denied temporarily due to missing connection on mobile operator network or system Maintainance';
				1900 = 'sent sms fail';

		***************/
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('language','url'));
		$this->load->model(array('api_m'));

	}
	function index(){

		/********** Checking values sent in request***********/
		$key  	   = (isset($_REQUEST['key']))?	  		trim($_REQUEST['key'])     		: die('1501');
		$sender    = (isset($_REQUEST['source']))?	  	trim($_REQUEST['source']) 		: die('1503');
		$mobile    = (isset($_REQUEST['destination']))? $_REQUEST['destination']   		: die('1505');
		$message   = (isset($_REQUEST['message']))?		rawurldecode($_REQUEST['message']) 			: die('1504');
		$extInfo   = (isset($_REQUEST['externalInfo']))?	$_REQUEST['externalInfo'] 	: '';
		$msg_type  = (isset($_REQUEST['type']))?( ($_REQUEST['type']>=1 && $_REQUEST['type']<=4 )?$_REQUEST['type']:die('1506')):die('1506');

		$sendMsgType = (strlen($message) != strlen(utf8_decode($message)))?array(2,4):array(1,3);
		if(!in_array($msg_type ,$sendMsgType)) die('1506');
		// verifying if externalInfo feild contains extra character other than alpha-numeric
		if($extInfo!=NULL){
			$extInfo = ( ctype_alnum($extInfo) ) ? $extInfo : die('1509');
			if(strlen($extInfo)>35) die('1509');
		}

		/*****************************************************************************/

		/*
		 Verifying the user authentication
		*/
		$userdata = $this->api_m->api_user_varification($key);
		$privi = NULL;
		if(!is_array($userdata)) die( $userdata);
		$senderid = $this->api_m->getSenderId($sender,$userdata['userid']);
		$privi =  $this->api_m->getUserPrivileges($userdata['userid']);
		if($senderid===FALSE) die('1503');

		if($this->api_m->pushApiState()){

			$res = $this->api_m->quickSend(array(
							'numbers'=>$mobile,
							'addressbook'=>NULL,
							'message'=>$message,
							'messageType'=>$msg_type,
							'senderId'=>$senderid ,
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
			die($res);
		}
		else{
			$res = $this->api_m->addQue(array(
						'numbers'=>$mobile,
						'addressbook'=>NULL,
						'message'=>$message,
						'schedule'=> 0,
						'messageType'=>$msg_type,
						'eventName'=>'none',
						'senderId'=>$senderid ,
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
			die($res);
		}
	}

	/*************************End of Class*****************************/
}
