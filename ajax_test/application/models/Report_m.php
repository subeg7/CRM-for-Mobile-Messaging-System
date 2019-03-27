<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(array('curd_m','common_m'));
		$this->load->library('dhxload');
		$this->priv = array();
		date_default_timezone_set("Asia/Kathmandu");
		$this->startDate = 0;
		$this->endDate = 0;
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
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company,r.company AS rcompany FROM outbox o INNER JOIN users u INNER JOIN users r WHERE o.fld_reseller_id=r.id AND o.fld_int_userid=u.id AND o.fld_int_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time()." ORDER BY o.fld_int_ondate DESC";
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
		//echo $query;
		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
								'callback'=>array($this,'todayCalback'),
								'query'=>$query,
								'rows'=>$rows
							));
	}


	public function todayCalback($item){

		date_default_timezone_set("Asia/Kathmandu");
		if($item->get_value('date')){
			$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date')));
		}
		if($item->get_value('fld_int_career')){
			if($this->startDate > 0 && $this->endDate > 0 ){
				$item->set_value('smsdate',date('Y-m-d H:i:s',$this->startDate).' - '. date('Y-m-d H:i:s',$this->endDate));
			}else{


				$item->set_value('smsdate','Today');
			}
			$operator = $this->curd_m->getData('operator',array('fld_int_id'=>$item->get_value('fld_int_career')),'object');
			if($operator!==FALSE){
				$item->set_value('fld_int_career',strtoupper($operator[0]->acronym));
			}
		}
		if($item->get_value('fld_balance_type')){
			if($item->get_value('fld_balance_type') !='appbal'){
				$operator = $this->curd_m->getData('operator',array('fld_int_id'=>$item->get_value('fld_balance_type')),'object');
				if($operator!==FALSE){
					$item->set_value('fld_balance_type',strtoupper($operator[0]->acronym));
				}
				else{
					$item->set_value('fld_balance_type',strtoupper($item->get_value('fld_balance_type')));
				}
			}
			else{
				$item->set_value('fld_balance_type',strtoupper($item->get_value('fld_balance_type')));
			}
		}
		if($item->get_value('fld_transaction_type')){
			if($item->get_value('fld_transaction_type')==2){
				$item->set_value('fld_transaction_descripition','<span style="color:red;">[ '.str_replace(':',' ]</span>',$item->get_value('fld_transaction_descripition') ));
			}

			elseif($item->get_value('fld_transaction_type')==1 || $item->get_value('fld_transaction_type')==3){
				$item->set_value('fld_transaction_descripition','<span style="color:green;">[ '.str_replace(':',' ]</span>',$item->get_value('fld_transaction_descripition') ));
			}
		}


		// this feild has only in outbox
		if($item->get_value('eom_track_id')){
			$type = array(1=>'TEXT',2=>'Unicode',3=>'TEXT FLASH',4=>'UNICODE FLASH');
			/*$resOper = $this->curd_m->get_search('SELECT count(d.fld_mobile_number) AS counts ,o.acronym AS operator FROM outbox_detail d INNER JOIN operator o WHERE d.fld_oid="'.$item->get_value('fld_int_id').'" AND d.fld_operator=o.fld_int_id GROUP BY d.fld_operator','object');
			$cell = array();
			foreach($resOper as $row){
				$cell[] = strtoupper($row->operator.' : '.$row->counts);
			}
			o.fld_int_cell_no AS fld_int_cell_no,*/
			$item->set_value('cell','[ '.str_replace(',',' ]</br></br>[ ',$item->get_value('fld_int_cell_no')).' ]'  );
			$item->set_value('ondate',date('Y-m-d H:i:s',$item->get_value('fld_int_ondate')));
			if($item->get_value('messageType') && $item->get_value('fld_msg_number') ){
				$item->set_value('typeCount',$type[$item->get_value('messageType')].'</br></br>'.$item->get_value('fld_msg_number')  );
			}
			if($item->get_value('fld_chr_sender')){
				$sender = explode(',',$item->get_value('fld_chr_sender'));
				/*$senderId = array();
				foreach($sender as $sen){
					$resSen = $this->curd_m->get_search('SELECT fld_chr_senderid FROM easy_senderid WHERE fld_int_id='.$sen,'object');
					//die(var_dump($resSen));
					$senderId[] = $resSen[0]->fld_chr_senderid;
				}
				$item->set_value('fld_chr_sender','[ '.implode(' ]</br></br>[ ',$senderId).' ]'  );*/
				$item->set_value('fld_chr_sender','[ '.implode(' ]</br></br>[ ',$sender).' ]'  );
			}
			if($item->get_value('company')){
				$item->set_value('company',ucwords($item->get_value('company')) );
			}
			if($this->session->userdata('userId')==1){ // if admin
				$item->set_value('xcompany', '<span style="color:green">'.ucwords($item->get_value('company')).'</span></br></br><span style="color:red">'.ucwords($item->get_value('rcompany')).'</span>' );

			}
			if(in_array('USER_MANAGE',$this->priv)){ // if reseller
				if($item->get_value('eom_track_id')!='none'){
					$item->set_value('fld_chr_message','<span style="color:green">'.$type[$item->get_value('messageType')].' - '.str_replace('-',' ( ',$item->get_value('fld_msg_number')).' ) / EOM - '.$item->get_value('eom_track_id').'</span></br></br>'.$item->get_value('fld_chr_message') );
				}else{
					$item->set_value('fld_chr_message','<span style="color:green">'.$type[$item->get_value('messageType')].' - '.str_replace('-',' ( ',$item->get_value('fld_msg_number')).' )</span></br></br>'.$item->get_value('fld_chr_message') );
				}

			}
		}
		// this feild has only in daily report
		if($item->get_value('dcompany')){
			$item->set_value('dcompany',ucwords($item->get_value('dcompany')) );
			$tranArr = array();
			$transaction = $this->curd_m->get_search('SELECT SUM(s.fld_int_units_deducted) AS sums,o.acronym AS operator FROM sms_transaction s INNER JOIN operator o WHERE s.fld_int_userid='.$item->get_value('usersid').' AND s.fld_chr_ondate BETWEEN '.strtotime(date("Y/m/d"))." AND ".time().' AND s.fld_int_career=o.fld_int_id GROUP BY fld_int_career','object');
			if($transaction!=NULL){

				foreach($transaction as $row){
					$tranArr[] = strtoupper($row->operator).' : '.$row->sums;
				}

			}
			$item->set_value('transaction','[ '.implode(' ] [ ',$tranArr).' ]' );
		}



	}
	public function renderCredit($data=NULL,$search=NULL){
		$userid = ($data['userid']!=NULL)?$data['userid']:$this->session->userdata('userId');
		$query ='SELECT * FROM balance_transaction WHERE fld_user_id='.$userid ;
		if($search != NULL){
			$like = '';
			if($data['from']!==NULL && $data['till']!==NULL){
				$like .=' AND date BETWEEN '.$data['from']." AND ".((int)$data['till']+86399);
			}
			if($data['type']!==NULL && $data['type']!=='none'){
				$like .=' AND fld_transaction_type='.$data['type'];
			}
			$query =$query.$like;
		}
		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
								'callback'=>array($this,'creditCalback'),
								'query'=>$query,
								'rows'=>'fld_int_id,fld_balance_type,fld_amount,fld_transaction_descripition,fld_balance_after_update,date'
							));
	}
	public function creditCalback($item){
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date')));

		if($item->get_value('fld_balance_type') !='appbal'){
			$operator = $this->curd_m->getData('operator',array('fld_int_id'=>$item->get_value('fld_balance_type')),'object');
			if($operator!==FALSE){
				$item->set_value('fld_balance_type',strtoupper($operator[0]->acronym));
			}
			else{
				$item->set_value('fld_balance_type',strtoupper($item->get_value('fld_balance_type')));
			}
		}
		else{
			$item->set_value('fld_balance_type',strtoupper($item->get_value('fld_balance_type')));
		}
		if($item->get_value('fld_transaction_type')==2){
			$item->set_value('fld_transaction_descripition','<span style="color:red;">[ '.str_replace(':',' ]</span>',$item->get_value('fld_transaction_descripition') ));
		}
		elseif($item->get_value('fld_transaction_type')==1){
			$item->set_value('fld_transaction_descripition','<span style="color:green;">[ '.str_replace(':',' ]</span>',$item->get_value('fld_transaction_descripition') ));
		}
	}

	public function renderOutbox($priv,$data=NULL,$search=NULL){
		$this->priv= $priv;
		$query = NULL;
		if($this->session->userdata('userId')==1){
			if($search != NULL && $data['number']!==NULL && $data['number']!=='none'){
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company,r.company AS rcompany FROM outbox o INNER JOIN users u INNER JOIN users r  INNER JOIN outbox_detail d WHERE o.fld_reseller_id=r.id AND o.fld_int_userid=u.id AND d.fld_oid=o.fld_int_id';
			}
			else{
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company,r.company AS rcompany FROM outbox o INNER JOIN users u INNER JOIN users r  WHERE o.fld_reseller_id=r.id AND o.fld_int_userid=u.id';
			}
			$rows = 'fld_int_id,xcompany,fld_chr_sender,fld_chr_message,cell,ondate,fld_user_data';
		}
		elseif(in_array('USER_MANAGE',$priv)){
			if($search != NULL && $data['number']!==NULL && $data['number']!=='none'){
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company FROM outbox o INNER JOIN users u INNER JOIN outbox_detail d WHERE o.fld_reseller_id='.$this->session->userdata('userId').' AND u.id = o.fld_int_userid AND d.fld_oid=o.fld_int_id';
			}
			else{
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company FROM outbox o INNER JOIN users u WHERE o.fld_reseller_id='.$this->session->userdata('userId').' AND u.id = o.fld_int_userid';
			}
			$rows = 'fld_int_id,company,fld_chr_sender,fld_chr_message,cell,ondate,fld_user_data';
		}
		elseif(!in_array('USER_MANAGE',$priv)){
			if($search != NULL && $data['number']!==NULL && $data['number']!=='none'){
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data FROM outbox o INNER JOIN outbox_detail d WHERE o.fld_int_userid='.$this->session->userdata('userId').' AND d.fld_oid=o.fld_int_id';
			}
			else{
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data FROM outbox o  WHERE o.fld_int_userid='.$this->session->userdata('userId');
			}
			$rows = 'fld_int_id,fld_chr_sender,fld_chr_message,typeCount,cell,ondate,fld_user_data';
		}
		if($search != NULL){
			$like = '';
			if($data['from']!==NULL && $data['till']!==NULL){
				$like .=' AND o.fld_int_ondate BETWEEN '.$data['from']." AND ".((int)$data['till']+86399);
			}
			if($data['number']!==NULL && $data['number']!=='none'){
				$like .=' AND d.fld_mobile_number="'.$data['number'].'"';
			}
			if($data['userdata']!==NULL && $data['userdata']!=='none'){
				$like .=' AND o.fld_user_data LIKE "%'.$data['userdata'].'"';
			}
			if($data['senderid']!==NULL && $data['senderid']!=='none' ){

				/*$res = $this->curd_m->get_search('SELECT fld_int_id FROM easy_senderid WHERE fld_chr_senderid="'.$data['senderid'].'" AND operator='.$data['operator'],'object');
				if($res != NULL)*/
					$like .=' AND o.fld_chr_sender LIKE "%'.$data['senderid'].'%"';
					//$like .=' AND o.fld_chr_sender  REGEXP  '.(string)$res[0]->fld_int_id;
			}
			$query =$query.$like;
		}

		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
								'callback'=>array($this,'todayCalback'),
								'query'=>$query,
								'rows'=>$rows
							));
	}

	public function renderSmsTransaction($priv,$data=NULL,$search=NULL){
		$this->priv= $priv;
		$userid = ($data['userid']==NULL)?$this->session->userdata('userId'):$data['userid'];
		if($userid==1){
			$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE ';
		}
		elseif(in_array('USER_MANAGE',$priv)){
			$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_int_agentid='.$userid;
		}
		elseif(!in_array('USER_MANAGE',$priv)){
			$query ='SELECT SUM(fld_int_units_deducted) AS sums,fld_int_career FROM sms_transaction WHERE fld_int_userid='.$userid;
		}
		$rows = 'fld_int_career,fld_int_career,sums,smsdate';
		if($search != NULL){
			$like = '';
			if($data['from']!==NULL && $data['till']!==NULL){
				$this->startDate = $data['from'];
				$this->endDate = ((int)$data['till']+86399);
				if($userid==1){
					$like .=' fld_chr_ondate BETWEEN '.$data['from']." AND ".((int)$data['till']+86300);
				}
				else{
					$like .=' AND fld_chr_ondate BETWEEN '.$data['from']." AND ".((int)$data['till']+86399);
				}
			}
			$query =$query.$like;
		}

		/*$query = $this->db->query($query.' GROUP BY fld_int_career');
		$xmlData = "<?xml version='1.0' encoding='utf-8' ?>";
		$xmlData = $xmlData."<rows total_count='".$totalCount."' pos='".$posStart."'>";
		if($query->num_rows() > 0){
		}*/

		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>0,
								'callback'=>array($this,'todayCalback'),
								'query'=>$query.' GROUP BY fld_int_career',
								'rows'=>$rows
							));
	}

	public function detailPull($data){
		if($data['search']!=NULL){
			if($data['from']!=NULL && $data['till']!=NULL){
				if($data['from'] > $data['till']) die('Invalid Date Range');
			}
			elseif($data['from']==NULL || $data['till']==NULL){
				die('Invalid Date Range');
			}
			$data['till'] = (int)$data['till'] + 86400;
		}elseif($data['today']!=NULL){
			$data['from'] = strtotime(date("Y/m/d"));
			$data['till'] = time();
		}
		$query ='SELECT o.fld_int_id AS fld_int_id,o.sender AS sender,s.fld_chr_name AS shortcode,p.acronym AS acronym,o.mo_mt AS mo_mt,o.count AS count,o.text AS text,o.date AS date,o.error AS error,o.error_detail AS error_detail FROM pull_report o INNER JOIN operator p INNER JOIN shortcode s WHERE o.date BETWEEN '.$data['from']." AND ".$data['till'].' AND p.fld_int_id = o.operator AND s.fld_int_id=o.shortcode ';
		if($data['search']!=NULL){
			if($data['overCount']!==NULL){
				if($data['overCount']=='over_single')
					$query .=' AND o.count > 1';
				elseif($data['overCount']=='single')
					$query .=' AND o.count = 1';
			}
			if($data['shortcode']!==NULL){
				$query .=' AND o.shortcode = '.$data['shortcode'];
			}
			//$query =$query.$like;
		}
		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>12,
								'callback'=>array($this,'detailCallback'),
								'query'=>$query.' ORDER BY o.date DESC',
								'rows'=>'fld_int_id,acronym,sender,shortcode,text,type,date'
							));
	}
	public function detailCallback($item){
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date')));
		$item->set_value('acronym',strtoupper($item->get_value('acronym')));
		$type =($item->get_value('mo_mt')==1)?'M.O.':'M.T.';
		$item->set_value('type',$type.'</br>[ '.$item->get_value('count').' ]');


	}
	public function sumCreditlog($startDate, $endDate,$userid){
		$userOperator = array();
		$userid= ($userid==NULL)?$this->session->userdata('userId'):$userid;
		$operator = $this->common_m->getOperatorByCountry($userid);
		if($operator === FALSE) return 'none';
		foreach($operator as $row){
			$userOperator[$row->fld_int_id]= $row->acronym;
		}

		$query ='SELECT sum(fld_amount) AS amount,fld_transaction_type AS tType,fld_balance_type AS operator FROM balance_transaction WHERE fld_user_id='.$userid.' AND date BETWEEN '.$startDate." AND ".$endDate." GROUP BY fld_transaction_type, fld_balance_type";
		$res = $this->curd_m->get_search($query,'object');
		if( $res==NULL) return 'none';
		$sum = array();
		foreach($res as $row){
			if($row->operator=='appbal') $sum[$row->tType][] = "Application Balance : ".$row->amount;
			else $sum[$row->tType][] = strtoupper($userOperator[$row->operator])." : ".$row->amount;
		}
		$detail = array();
		foreach($sum as $key=>$row){
			$detail[$key] = '[ '.implode(' ] [ ',$row).' ]';
		}
		return $detail;

	}
// model ends
}
