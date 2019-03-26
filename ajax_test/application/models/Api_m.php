<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Api_m extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('sendsms_m');
	}
	public function api_user_varification($key){
		$key = $this->sendsms_m->get_search('SELECT u.id AS id,u.fld_balance_type AS fld_balance_type,u.fld_reseller_id AS fld_reseller_id,u.active AS active FROM remote_access k INNER JOIN users u WHERE k.fld_api_key="'.$key.'" AND u.id=k.fld_user_id','object');
		if($key==NULL) return '1501';
		// if user is disabled , reject all request
		if($key[0]->active > 1 ) return '1502';
		// if api user reseller is disabled then reject all api request
		if(!$this->checkUserState($key[0]->id)) return '1502';
		if(!in_array('PUSH',$this->sendsms_m->getUserPrivileges($key[0]->id))) return '1502';
		return array(
					'userid'=>$key[0]->id,
					'balanceType'=> strtolower($key[0]->fld_balance_type),
					'reseller'=>$key[0]->fld_reseller_id,
					);

	}

	public function checkUserState($id){
		do{
			$res = $this->sendsms_m->get_search("SELECT active, fld_reseller_id FROM users WHERE id=".$id,'object');
			if( (int)$res[0]->active > 1){ return FALSE;}
			else{$id = (int)$res[0]->fld_reseller_id; }
		}while($id > 1);
		return TRUE;
	}
	public function getSenderId($senderid,$userid){
		$selectedSenderId = array();
		$query = (strtolower($senderid)=='none')? "SELECT fld_int_id,operator FROM easy_senderid WHERE fld_int_default=1":"SELECT fld_int_id, operator FROM easy_senderid WHERE fld_chr_senderid='".$senderid."' AND fld_int_userid=".$userid;

		$send = $this->sendsms_m->get_search($query,'object');

		if($send==NULL) return FALSE;
		foreach($send as $sen){
			$selectedSenderId [] = $sen->operator.'_'.$sen->fld_int_id;
		}
		return implode(',',$selectedSenderId);
	}



	/*********
	below these two functions adds one more further step to send sms but it removes dependencies among this model with
	others system models which isolates api model from rest model
	**********/
	public function quickSend($data){
		$res = $this->sendsms_m->quickSend($data);
		return $res;
	}
	public function addQue($data){
		$res = $this->sendsms_m->addQue($data);
		return  $res;
	}

	public function pushApiState(){
		return $res = $this->sendsms_m->pushApiState();
	}
	public function getUserPrivileges($userid){
		return $res = $this->sendsms_m->getUserPrivileges($userid);
	}
	///// end of class
}
