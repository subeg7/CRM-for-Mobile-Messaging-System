<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once("application/core/ESY_Controller.php");

class Button_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('curd_m','push_m','common_m','sendsms_m'));
		$this->userPrivileges = $this->vas->getUserPrivileges();

	}
	
	function test(){
		echo "working fine";
	}

	public function renderTodayReport($type,$priv,$userid){
		$userid = ($userid==NULL)?$this->session->userdata('userId'):$userid;
		$this->priv= $priv;
		if($type == 'creditlog'){ // credit log  report
			$query ='SELECT * FROM balance_transaction WHERE fld_user_id='.$userid.' AND date BETWEEN '.strtotime(date("Y/m/d"))." AND ".time();
			$rows = 'fld_int_id,fld_balance_type,fld_amount,fld_transaction_descripition,fld_balance_after_update,date';
		}
		elseif($type =='smsreport'){// sms report
			if($userid==1){

				$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' GROUP BY fld_int_career';
			}
			elseif(in_array('USER_MANAGE',$priv)){
				$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_int_agentid='.$userid.' AND fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' GROUP BY fld_int_career';
			}
			elseif(!in_array('USER_MANAGE',$priv)){
				$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_int_userid='.$userid.' AND fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' GROUP BY fld_int_career';
			}
			$rows = 'fld_int_career,fld_int_career,sums,smsdate';
		}
		elseif($type =='sentbox'){ // sent box report
			if($userid==1){
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company,r.company AS rcompany FROM outbox o INNER JOIN users u INNER JOIN users r WHERE o.fld_reseller_id=r.id AND o.fld_int_userid=u.id AND o.fld_int_ondate BETWEEN 0'." AND ".time()." ORDER BY o.fld_int_ondate DESC";
				$rows = 'fld_int_id,xcompany,fld_chr_sender,fld_chr_message,cell,ondate,fld_user_data';
			}
			elseif(in_array('USER_MANAGE',$priv)){
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company FROM outbox o INNER JOIN users u WHERE o.fld_reseller_id='.$userid.' AND u.id = o.fld_int_userid AND o.fld_int_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time()." ORDER BY o.fld_int_ondate DESC";
				$rows = 'fld_int_id,company,fld_chr_sender,fld_chr_message,cell,ondate,fld_user_data';
			}
			elseif(!in_array('USER_MANAGE',$priv)){
				$query ='SELECT * FROM outbox WHERE fld_int_userid='.$userid.' AND fld_int_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time()." ORDER BY fld_int_ondate DESC";
				$rows = 'fld_int_id,fld_chr_sender,fld_chr_message,typeCount,cell,ondate,fld_user_data';
			}


		}
		elseif($type == 'dailyreport'){
			if($userid==1){
				$query ='SELECT DISTINCT u.company AS dcompany ,u.id AS usersid FROM users u INNER JOIN sms_transaction s WHERE s.fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' AND s.fld_int_userid=u.id';
			}
			elseif(in_array('USER_MANAGE',$priv)){
				$query ='SELECT DISTINCT u.company AS dcompany ,u.id AS usersid FROM users u INNER JOIN sms_transaction s WHERE s.fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' AND s.fld_int_userid=u.id AND s.fld_int_agentid='.$this->session->userdata('userId');
			}
				$rows = 'usersid,dcompany,transaction';

		}
		// return $query;
		// echo"console.log('file found')";
		// echo $query;
		// exit("terminated");
		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
								'callback'=>array($this,'todayCalback'),
								'query'=>$query,
								'rows'=>$rows
							));
	}


	/*********all push render functions end*************/

}
