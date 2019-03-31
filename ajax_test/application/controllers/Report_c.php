<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once("application/core/ESY_Controller.php");
class Report_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('report_m','curd_m'));
		$this->userPrivileges = $this->vas->getUserPrivileges();
	}
	public function reportView($type,$id){
		if($type == 'sentbox'){
			$operator = $this->curd_m->get_search('SELECT * FROM operator','object');
			if($operator!=NULL){
				$opeList =  array();
				foreach($operator as $ope){ $opeList[]=$ope->acronym;}
				$this->data['operator'] = implode(',',$opeList);
			}
			else $this->data['operator'] = 'none';
			$number = $this->curd_m->get_search('SELECT o.acronym AS acronym,d.status AS status,d.admin_state AS admin_state ,d.fld_mobile_number AS fld_mobile_number FROM outbox_detail d INNER JOIN operator o WHERE d.fld_operator=o.fld_int_id AND d.fld_oid = "'.$id.'"','object');
			$this->data['numbers'] = ($number==NULL)?'none':json_encode($number);
			$this->data['user'] = ($this->session->userdata('userId')==1)?'admin':'user';
			$this->load->view('report/sentboxDetail_v',$this->data);
		}
	}
	public function renderTodayReport($type,$userid=NULL){
		$userid = ($userid==NULL)?$this->session->userdata('userId'):$userid;
		$priv = $this->vas->getUserPrivileges($userid);
		$this->report_m->renderTodayReport($type,$priv,$userid );
	}
	public function sumCreditlog($startDate='today',$tillDate=NULL,$userid = NULL){
		$userid = ($userid==NULL)?$this->session->userdata('userId'):$userid;

		if($startDate==='today'){
			$startDate = strtotime(date("Y/m/d"));
			$tillDate = time();
		}
		else{
			$tillDate = ($tillDate!=NULL)?(int)$tillDate+86399:0;
		}
		$sum = $this->report_m->sumCreditlog($startDate,$tillDate,$userid );
		die( ($sum=='none')?'none':json_encode($sum));
	}
	/*public function sumCreditlogIndi($startDate='today',$tillDate=NULL){
		if($startDate==='today'){
			$startDate = strtotime(date("Y/m/d"));
			$tillDate = time();
		}
		else{
			$tillDate = ($tillDate!=NULL)?(int)$tillDate+86399:0;
		}
		$sum = $this->report_m->sumCreditlog($startDate,$tillDate,$this->session->userdata('userId') );
		die( ($sum=='none')?'none':json_encode($sum));
	}*/
	public function renderCredit($search=NULL,$userid = NULL){
		if($search==NULL){
			$this->report_m->renderCredit();
		}else{
			date_default_timezone_set("Asia/Kathmandu");
			$this->report_m->renderCredit(array(
										'type'=>($this->input->get('type'))?$this->input->get('type'):NULL,
										'from'=>($this->input->get('from'))?strtotime($this->input->get('from')):NULL,
										'till'=>($this->input->get('till'))?strtotime($this->input->get('till')):NULL,
										'userid'=>($userid == NULL)?NULL:$userid,
									),$search);
		}

	}
	public function renderOutbox($search=NULL){
		// return"successfull rendering of outbox";
		if($search==NULL){
			$this->report_m->renderOutbox();
		}else{

			date_default_timezone_set("Asia/Kathmandu");
			$this->report_m->renderOutbox($this->userPrivileges,array(
										'operator'=>($this->input->get('operator'))?$this->input->get('operator'):NULL,
										'number'=>($this->input->get('number'))?$this->input->get('number'):NULL,
										'senderid'=>($this->input->get('senderid'))?$this->input->get('senderid'):NULL,
										'userdata'=>($this->input->get('userdata'))?$this->input->get('userdata'):NULL,
										'from'=>($this->input->get('from'))?strtotime($this->input->get('from')):NULL,
										'till'=>($this->input->get('till'))?strtotime($this->input->get('till')):NULL
									
									),$search);
		}

	}
	public function renderSmsTransaction($search=NULL,$userid = NULL){
		// return "";
		if($search==NULL){
			$this->report_m->renderSmsTransaction();
		}else{
			date_default_timezone_set("Asia/Kathmandu");
			$priv = ($userid == NULL)?$this->vas->getUserPrivileges():$this->vas->getUserPrivileges($userid);
			$this->report_m->renderSmsTransaction($priv,array(
										'from'=>($this->input->get('from'))?strtotime($this->input->get('from')):NULL,
										'till'=>($this->input->get('till'))?strtotime($this->input->get('till')):NULL,
										'userid'=>($userid == NULL)?NULL:$userid,
									),$search);
		}

	}
	public function detailPull($search=NULL){
		$this->report_m->detailPull(array(
									'shortcode'=>($this->input->get('shortcode')!='none')?$this->input->get('shortcode'):NULL,
									'overCount'=>($this->input->get('overCount'))?$this->input->get('overCount'):NULL,
									'from'=>($this->input->get('from'))?strtotime($this->input->get('from')):NULL,
									'till'=>($this->input->get('till'))?strtotime($this->input->get('till')):NULL,
									'today'=>($this->input->get('today'))?$this->input->get('today'):NULL,
									'error'=>($this->input->get('shortcode')!='none')?$this->input->get('shortcode'):NULL,
									'search'=>$search
									));
	}



	// end of  classs
}
