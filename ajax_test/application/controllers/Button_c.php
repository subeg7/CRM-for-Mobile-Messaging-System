<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once("application/core/ESY_Controller.php");

class Button_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('curd_m','button_m','common_m','sendsms_m'));
		$this->userPrivileges = $this->vas->getUserPrivileges();

	}
	
	// function test($var){
	// 	echo "working fine with:".$var;

	// }

	public function test($data=NULL){
		if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}

		if($data=='search' || $data =='download'){
			$err= array();
			if($data=='download'){
				if($this->input->get('id')){
					$res = $this->curd_m->get_search('SELECT * FROM download_code WHERE fld_code="'.$this->input->get('id').'"','object');
					// print_r($res);
					// exit(); 
					if($res===FALSE) die('**Error : Invalid Request');
					if($res[0]->expireTime < time()) die('**Error : Invalid Request');
				}else{
					die('**Error : Invalid Request');
				}
			}
			if($this->input->get('searchStart') || $this->input->get('searchTill') ){
				if(!$this->input->get('searchStart')) die('Start Date feild is required');
				if(!$this->input->get('searchTill')) die('Till Date feild is required');
			}
		}

		$this->button_m->renderReport(array(
												'senderid'=>($this->input->get('senderId'))?$this->input->get('senderId'):NULL,
												'reqby'=>($this->input->get('reqby'))?$this->input->get('reqby'):NULL,
												'state'=>($this->input->get('state')=='none')?NULL:$this->input->get('state'),
												'sdate'=>($this->input->get('searchStart'))?$this->input->get('searchStart'):NULL,
												'tdate'=>($this->input->get('searchTill'))?$this->input->get('searchTill'):NULL,
												'privileges' => $this->userPrivileges,
												'type'=>"download"
												));

	}



}
