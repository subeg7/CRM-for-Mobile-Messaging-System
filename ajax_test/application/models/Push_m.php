<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Push_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(array('curd_m','common_m'));
		$this->load->library('dhxload','vas');
		$this->priv = NULL;
	}

	/*************start of render functions*************/
	public function renderGateway(){
		if($this->session->userdata('userId')==1){
			$query = "SELECT g.fld_int_id AS id, g.fld_char_gw_name AS gname, g.fld_char_username AS username,o.acronym AS operator,g.fld_char_hostname AS hostname, g.fld_chr_smscid AS smscid, g.fld_int_port AS port, g.fld_int_priority AS priority FROM gateway g INNER JOIN operator o WHERE g.fld_chr_operator = o.fld_int_id ORDER BY g.fld_int_id ASC";
			$row = "id,gname,username,operator,hostname,smscid,port,priority";
		}
		else{
			$query = "SELECT g.fld_int_id AS id, g.fld_char_gw_name AS gname,o.acronym AS operator FROM gateway g INNER JOIN operator o INNER JOIN user_gateway AS u  WHERE u.fld_user_id = ".$this->session->userdata('userId')." AND u.fld_gw_id = g.fld_int_id AND g.fld_chr_operator = o.fld_int_id  ORDER BY g.fld_int_id ASC";
			$row = "id,gname,operator";
		}
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'gatewayCalback'),
									'query'=>$query ,
									'rows'=>$row,
								   ));
	}
	public function gatewayCalback($item){
		if($item->get_value('priority')){
			if($item->get_value('priority')==1){
				$item->set_value('priority','<span class="red">Default</span>');
			}
			else{
				$item->set_value('priority','Regular');
			}
		}
	}
	public function renderQueue($priv){
		$this->priv = $priv;
		$query = NULL;
		$rows = NULL;
		$userTye = NULL;
		if($this->session->userdata('userId')==1){
			$query = 'SELECT q.fld_int_id AS fld_int_id,r.company AS rcompany,q.fld_int_cell_no AS fld_int_cell_no, u.company AS company,q.message AS message, q.date AS date, q.state AS state,q.messageType AS messageType, q.sender_id AS sender_id, q.schedule AS schedule,q.messageCount AS messageCount FROM que_job q INNER JOIN users u INNER JOIN users r WHERE q.users_id = u.id AND q.agent_id=r.id';
			$rows = "fld_int_id,company,rcompany,sender,message,messageType,number,date,state";
		}
		elseif($this->vas->verifyPrivillages('PUSH',$this->priv )){
			$query = 'SELECT * FROM que_job WHERE users_id ='.$this->session->userdata('userId');
			$rows = "fld_int_id,sender,message,messageType,number,date,state";
		}
		elseif($this->vas->verifyPrivillages('USER_MANAGE',$this->priv ) ){
			$query = 'SELECT q.fld_int_id AS fld_int_id,q.fld_int_cell_no AS fld_int_cell_no, u.company AS company,q.message AS message, q.date AS date, q.state AS state,q.messageType AS messageType, q.sender_id AS sender_id, q.schedule AS schedule,q.messageCount AS messageCount FROM que_job q INNER JOIN users u  WHERE q.users_id = u.id AND u.fld_reseller_id='.$this->session->userdata('userId');
			$rows = "fld_int_id,company,sender,message,messageType,number,date,state";
		}

		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'queueCalback'),
									'query'=>$query,
									'rows'=>$rows,
								   ));
	}
	public function queueCalback($item){
		if($item->get_value('rcompany')) $item->set_value('rcompany',ucwords($item->get_value('rcompany')));
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date')));

		$item->set_value('company',ucwords($item->get_value('company')));
		$senderId = $this->curd_m->get_search('SELECT fld_chr_senderid FROM easy_senderid WHERE fld_int_id IN ('.$item->get_value('sender_id').')','object');
		$normmSend = array();
		foreach($senderId as $row){
			$normmSend[] = $row->fld_chr_senderid;
		}
		$item->set_value('sender','<span style="color:green;">[</span> '.implode(' <span style="color:green;">]</br></br>[</span> ',$normmSend).' <span style="color:green;">]</span>');
		/*$cell = $this->curd_m->get_search('SELECT count(q.fld_int_id) AS count, o.acronym AS acro FROM que_detail q INNER JOIN operator o WHERE q.que_id="'.$item->get_value('fld_int_id').'" AND o.fld_int_id = q.operator group by q.operator','object');

		$cellArr = array();
		if($cell !=NULL){
			foreach($cell as $row){
				$cellArr[] = '<span style="color:blue">'.strtoupper($row->acro).'</span> : '.$row->count;
			}
		}*/

		//q.fld_int_cell_no AS fld_int_cell_no,
		//die(var_dump($cell ));
		$item->set_value('number',str_replace(',','</br></br>',$item->get_value('fld_int_cell_no')) );
		$type = $item->get_value('messageType');
		if($type == 1) $type = 'Text';
		elseif($type == 2) $type = 'Unicode';
		elseif($type == 3) $type = 'Flash Text';
		elseif($type == 4) $type = 'Flash Unicode';
		$item->set_value('messageType','<span style="color:green">'.$type.'</span></br></br>'.str_replace('-',' ( ',$item->get_value('messageCount')) .' )');

		if($item->get_value('schedule') == 1 )  $item->set_value('message','<span style="color:blue">[ Scheduler Job ]</span></br></br>'.$item->get_value('message'));
		$state = $item->get_value('state');
		if($state== 1){
			$item->set_value('state','<span style="color:green;">Enabled</span>');
		}else{
			if($this->session->userdata('userId')==1){
				if($state == 2) $item->set_value('state','Disabled </br><span style="color:green;">[ Client ]</span>');
				elseif($state == 3) $item->set_value('state','Disabled </br><span style="color:green;">[ Reseller ]</span>');
				elseif($state == 4) $item->set_value('state','Disabled </br><span style="color:green;">[ Self ]</span>');
			}
			elseif($this->vas->verifyPrivillages('PUSH',$this->priv )){
				if($state == 2) $item->set_value('state','Disabled </br><span style="color:green;">[ Self ]</span>');
				elseif($state > 2) $item->set_value('state','Disabled </br><span style="color:green;">[ Admin ]</span>');


			}
			elseif($this->vas->verifyPrivillages('USER_MANAGE',$this->priv )){
				if($state == 2) $item->set_value('state','Disabled </br><span style="color:green;">[ Client ]</span>');
				elseif($state == 3) $item->set_value('state','Disabled </br><span style="color:green;">[ Self ]</span>');
				elseif($state == 4) $item->set_value('state','Disabled </br><span style="color:green;">[ Admin ]</span>');
			}
		}
	}
	public function renderContact($id){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>0,
									'callback'=>array($this,'contactCalback'),
									'query'=>"SELECT a.fld_int_id AS fld_int_id , o.acronym AS operator, a.fld_chr_name AS name , a.fld_chr_phone AS number FROM addressbook a INNER JOIN operator o WHERE o.fld_int_id=a.fld_int_career AND a.fld_int_addb_id = ".$id,
									'rows'=>"fld_int_id,name,number,operator",
								   ));
	}
	public function contactCalback($item){
		$item->set_value('operator',strtoupper($item->get_value('operator')));
	}
	public function renderAddressbook(){
		$addressbook = $this->curd_m->getData('addressbook_info',array('fld_int_userid'=>$this->session->userdata('userId') ),'object');
		$bookXml='<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1"><item   text="Address Books Lists" id="books" open="1">';
		if($addressbook!==FALSE){
			foreach($addressbook as $row){
				$bookXml .= '<item text="'.(string) htmlspecialchars(ucwords($row->fld_chr_name)).'" id="'.$row->fld_int_id.'"  im0="addressbooklist.png" ></item>';
			}
		}
		$bookXml .= '</item></tree>';
		header("Content-type: text/xml");
		die($bookXml);

	}
	public function renderTemplate(){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>0,
									'callback'=>array($this,'templateCalback'),
									'query'=>"SELECT * FROM sms_templates WHERE fld_user_id=".$this->session->userdata('userId'),
									'rows'=>"fld_int_id,fld_chr_title,fld_chr_msg,typeCount,date",
								   ));

	}
	public function templateCalback($item){
		$arr = array('1'=>'Text','2'=>'Unicode','3'=>'Flash Text','4'=>'Flash Unicode');
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('fld_int_date')));
		$item->set_value('typeCount', $item->get_value('fld_int_msg_cnt').'&nbsp;&nbsp; /&nbsp;&nbsp; <span style="color:blue;">'.$arr[(string)$item->get_value('fld_int_mst')].'</span>' );
	}
	public function renderMsgTemplate($id){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>0,
									'callback'=>array($this,'msgTemplateCalback'),
									'query'=>"SELECT fld_int_id AS id,template AS template,date AS date,sender_id AS senderid FROM msgTemplate WHERE user_id=".$id,
									'rows'=>"id,senderid,operator,template,date",
								   ));
	}
	public function msgTemplateCalback($item){
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('dates')));
		$temp = json_decode($item->get_value('template'));
		$template = array();
		foreach($temp as $key=>$tem){
			$template[] = strtoupper($key).' : '.$tem;
		}
		$item->set_value('template',implode('</br></br>',$template));
		if($item->get_value('senderid')==0){
			$item->set_value('senderid','[ <span style="color:red;">To All Request</span> ]');
			$item->set_value('operator','[ <span style="color:red;">All Operator</span> ]');
		}
		else{
			$sen = $this->curd_m->get_search('SELECT s.fld_chr_senderid AS fld_chr_senderid,o.acronym AS acro FROM easy_senderid s INNER JOIN operator o WHERE s.fld_int_id='.$item->get_value('senderid').' AND o.fld_int_id=s.operator','object');

			$item->set_value('senderid',$sen[0]->fld_chr_senderid);
			$item->set_value('operator',strtoupper($sen[0]->acro));
		}
	}
	public function renderSchduler($id){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>0,
									'callback'=>array($this,'queCalback'),
									'query'=>"SELECT * FROM que_job WHERE schedule=1 AND users_id=".$id,
									'rows'=>"fld_int_id,event_name,schedule_date,message,cellnumber,senderid,date,state",
								   ));
	}
	public function queCalback($item){
		date_default_timezone_set("Asia/Kathmandu");
		$arr = array('1'=>'Text','2'=>'Unicode','3'=>'Flash Text','4'=>'Flash Unicode');
		$item->set_value('event_name',ucwords($item->get_value('event_name')));
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date')));
		//
		$sen_arr = explode(',',$item->get_value('sender_id'));
		$senNorm_arr = array();
		foreach($sen_arr as $sen){
			$sender = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_id='.$sen,'object');
			if($sender !=NULL) $senNorm_arr[]= $sender[0]->fld_chr_senderid;
		}

		$item->set_value('senderid','<span style="color:blue;">[ '.implode(' ]</br></br>[ ',$senNorm_arr).' ]</span>');
		$item->set_value('message','[ <span style="color:blue">'.$arr[$item->get_value('messageType')].'</span> - '.implode('( ',explode('-',$item->get_value('messageCount'))).' ) ]</br></br>'.$item->get_value('message'));


		$schDate = explode(',',$item->get_value('schedule_date'));
		$schArr = array();
		foreach($schDate as $val){
			$schArr[] = date('Y-m-d H:i:s',$val);
		}
		$item->set_value('schedule_date',implode( '</br></br>',$schArr ));
		$cell = $this->curd_m->get_search('SELECT count(q.fld_int_id) AS count, o.acronym AS acro FROM que_detail q INNER JOIN operator o WHERE que_id="'.$item->get_value('fld_int_id').'" AND o.fld_int_id = q.operator group by q.operator','object');
		$cellArr = array();
		foreach($cell as $row){
			$cellArr[] = '<span style="color:blue">'.strtoupper($row->acro).'</span> : '.$row->count;
		}
		$item->set_value('cellnumber',implode( '</br></br>',$cellArr ));
		if($item->get_value('state')==1) $item->set_value('state','<span style="color:green">Enable</span>');
		elseif($item->get_value('state')> 2) $item->set_value('state','<span style="color:red">Disable </br> By Admin</span>');
		else $item->set_value('state','<span style="color:red">Disable</span>');
	}

	
	public function renderSenderId($data){
		$userid = $this->session->userdata('userId');
		$query = NULL; $rows=NULL;
		$this->priv = $data['privileges'];
		$searchArr = array();
		if($userid == 1){
			$query = 'SELECT s.fld_int_id AS id,u.company AS reqestby,s.fld_description AS descript,u.fld_reseller_id AS resname,s.fld_chr_senderid AS senderid,s.fld_int_default AS spriority,o.acronym AS operator,g.fld_char_gw_name AS gname,g.fld_int_priority AS gpriority, s.fld_int_request AS state,s.fld_chr_ondate AS date FROM easy_senderid s INNER JOIN gateway g INNER JOIN operator o INNER JOIN users u WHERE s.fld_int_userid=u.id AND s.fld_gateway=g.fld_int_id AND s.operator=o.fld_int_id ';

			$rows = "id,reqestby,senderid,descript,gateway,state,date,spriority";
			$prinRowsName = 'Request By/Reseller Name,Sender ID,Description,Operator/Gateway,State,Date,Priority';
		}
		elseif(in_array('USER_MANAGE',$data['privileges'])){
			$query = 'SELECT u.company AS reqby, s.fld_int_id AS id,s.fld_chr_senderid AS senderid,o.acronym AS operator,g.fld_char_gw_name AS gname,g.fld_int_priority AS gpriority, s.fld_int_request AS state,s.fld_chr_ondate AS date FROM easy_senderid s INNER JOIN gateway g INNER JOIN operator o INNER JOIN users u WHERE s.fld_int_userid=u.id AND s.fld_gateway=g.fld_int_id AND s.operator=o.fld_int_id AND u.fld_reseller_id='.$userid;
			$rows = "id,reqby,senderid,gateway,state,date";
			$prinRowsName = 'Request By,Sender ID,Operator/Gateway,State,Date';
		}
		elseif(in_array('PUSH',$data['privileges'])){
			$query = 'SELECT s.fld_int_id AS id,s.fld_chr_senderid AS senderid,o.acronym AS operator,g.fld_char_gw_name AS gname,g.fld_int_priority AS gpriority, s.fld_int_request AS state,s.fld_description AS descript,s.fld_chr_ondate AS date FROM easy_senderid s INNER JOIN gateway g INNER JOIN operator o WHERE s.fld_int_userid='.$userid.' AND s.fld_gateway=g.fld_int_id AND s.operator=o.fld_int_id';
			$rows = "id,senderid,gateway,state,descript,date";
			$prinRowsName = 'Sender ID,Operator/Gateway,Description,State,Date';
		}
		if($data['type']=='download' || $data['type']=='search'){
			$like = '';
			if($data['sdate']!==NULL && $data['tdate']!==NULL){
				$like .=' AND s.fld_chr_ondate BETWEEN '.$data['sdate']." AND ".$data['tdate'];
				$searchArr[]='sdate='.$data['sdate']; $searchArr['tdate']='tdate='.$data['tdate'];
			}
			if($data['senderid']!==NULL){
				$like .=' AND s.fld_chr_senderid LIKE "'.$data['senderid'].'%" ';
				$searchArr[] = 'senderId='.$data['senderid'];
			}
			if($data['reqby']!==NULL){
				$like .=' AND u.company LIKE "'.$data['reqby'].'%" ';
				$searchArr[] = 'reqby='.$data['reqby'];
			}
			if($data['state']!==NULL){
				if( (int)$data['state'] > 2) $like .=' AND s.fld_int_request > 2';
				else $like .=' AND s.fld_int_request= '.$data['state'];
				$searchArr[] = 'state='.$data['state'];
			}
			$query =$query.$like;
			//die(var_dump($query));
			if($data['type']=='download'){
				$res = $this->dhxload->getCsvData(array(
											'callback'=>array($this,'senderidCalback'),
											'query'=>$query.' ORDER BY s.fld_int_userid ASC',
											'rows'=>$rows,
											'prinRowsName'=>$prinRowsName
											));

				$folderName = $this->curd_m->getData('users',array('id'=>$this->session->userdata('userId') ),'object');
				if($res != NULL) $res = $this->common_m->getExcecl($res, $folderName[0]->fld_transaction_id);
				die( var_dump($res));
			}
		}

		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>10,
									'callback'=>array($this,'senderidCalback'),
									'query'=>$query.' ORDER BY s.fld_int_userid ASC',
									'rows'=>$rows,
									'userdata'=>(sizeof($searchArr)>0)?implode('__',$searchArr):'',
								   ));

	}



	public function senderidCalback($item){
		if($item->get_value('gpriority')==1) $item->set_value('gateway','Default <span style="color:blue;">[ '.strtoupper($item->get_value('operator')).' ]</span>');
		else $item->set_value('gateway','<span style="color:green;">'.$item->get_value('gname') .'</span> <span style="color:blue;">[ '.strtoupper($item->get_value('operator')) .' ]</span>');
		if($item->get_value('descript')  ){
			if(trim($item->get_value('descript')) ==''){
				$item->set_value('descript','<san style="color:red">none</span>');
			}
			else{
				$item->set_value('descript',ucwords($item->get_value('descript')));
			}
		}
		if($item->get_value('reqby')){
			$item->set_value('reqby',ucwords($item->get_value('reqby')));
		}
		if($item->get_value('resname') || $item->get_value('resname')==0){
			$res = NULL;
			if((int)$item->get_value('resname')=== 0){
				$res = $this->curd_m->get_search('SELECT company FROM users WHERE id=1','object');

			}else{
				$res = $this->curd_m->get_search('SELECT company FROM users  WHERE id='.$item->get_value('resname'),'object');
			}
			if($res !==NULL){

				$item->set_value('reqestby',ucwords($item->get_value('reqestby')).'</br><span style="color:green">[ '.ucwords($res[0]->company).' ]</span>');
				//$item->set_value('resname',ucwords($res[0]->company));
			}
			else{$item->set_value('reqestby',ucwords($item->get_value('reqestby')) );
			}
		}
		if($item->get_value('spriority')){
			if((int)$item->get_value('spriority')==1)$item->set_value('spriority','<span class="red">Default</span>');
			else $item->set_value('spriority','Regular');
		}
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date')));
		if((int)$item->get_value('state') == 1 ) $item->set_value('state','Requested');
		elseif( (int)$item->get_value('state') == 2 ) $item->set_value('state','<span style="color:green;">Approved</span>');
		elseif((int)$item->get_value('state') == 3 || (int)$item->get_value('state') == 4){
			if($this->session->userdata('userId')==1){
				if((int)$item->get_value('state') == 3)$item->set_value('state','<span style="color:red;">Disapproved <span style="color:green;">[ Reseller ]</span></span>');
				else $item->set_value('state','<span style="color:red;">Disapproved </br><span style="color:green;">[ Self ]</span></span>');
			}
			elseif(in_array('PUSH',$this->priv)){
				$item->set_value('state','<span style="color:red;">Disapproved </span>');
			}elseif(in_array('USER_MANAGE',$this->priv)){
				if((int)$item->get_value('state') == 3)$item->set_value('state','<span style="color:red;">Disapproved </br><span style="color:green;">[ Self ]</span></span>');
				else $item->set_value('state','<span style="color:red;">Disapproved </br><span style="color:green;">[ Admin ]</span></span>');
			}

		}
	}
	public function gateway($data,$type,$id){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update
		foreach($data as $key=>$val){
				if($val !==NULL){
					$arr[$key] = $val;
				}
				else{  unset($data[$key]);	}
		}
		if($type == 'edit'){
			if(isset($arr['fld_chr_operator'])){
				$gw = $this->curd_m->get_search('SELECT * FROM gateway WHERE fld_int_id='.$id,'object');
				if(!isset($arr['fld_int_priority']) )
					$arr['fld_int_priority'] = $gw[0]->fld_int_priority;
			}
			if(isset($arr['fld_int_priority']) && $arr['fld_int_priority'] ==1){
				$gw = $this->curd_m->get_search('SELECT * FROM gateway WHERE fld_int_id='.$id,'object');
				if(!isset($arr['fld_chr_operator']) )
					$arr['fld_chr_operator'] = $gw[0]->fld_chr_operator;
			}
		}
		$res = (isset($arr['fld_char_gw_name']))?$this->curd_m->checkRecur('gateway',array('fld_char_gw_name__Gateway Name'=>$arr['fld_char_gw_name'])):TRUE;

		if($res === TRUE){
			$res = (isset($arr['fld_chr_smscid']))?$this->curd_m->checkRecur('gateway',array('fld_chr_smscid__SMSCID'=>$arr['fld_chr_smscid'])):TRUE;
		}
		else{
			die(ucwords($res));
		}
		if($res === TRUE){
			$res = (isset($arr['fld_chr_operator']) && isset($arr['fld_int_priority']) && $arr['fld_int_priority'] ==1)
						?$this->curd_m->checkRecur('gateway',array('fld_chr_operator'=>$arr['fld_chr_operator'],'fld_int_priority'=>$arr['fld_int_priority']),'multiple'):TRUE;
			if($res!==TRUE) die('Selected Operator Default Gateway '.$res);
		}
		else{
			die(ucwords($res));
		}
		if($type=='new' && $res == TRUE){
			return ($this->curd_m->get_insert('gateway',$arr)!==FALSE)?TRUE:FALSE;
		}
		elseif($type=='edit' && $res == TRUE){

			return $this->curd_m->get_update('gateway',$arr);
		}
	}


	public function addressbook($data,$type,$id){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update
		foreach($data as $key=>$val){
				if($val !==NULL){
					$arr[$key] = $val;
				}
				else{  unset($data[$key]);	}
		}
		if(isset($arr['fld_chr_name'])){
		$res = $this->curd_m->get_search('SELECT * FROM addressbook_info WHERE fld_chr_name="'.$arr['fld_chr_name'].'" AND fld_int_userid='.$this->session->userdata('userId'),'object');
		if($res !== NULL ) die('Addressbook Already exist');
		}
		if($type=='new' ){
			$arr['fld_int_userid'] = $this->session->userdata('userId');
			$arr['fld_chr_ondate'] = time();
			$arr['fld_int_status'] = 1;
			if($this->curd_m->get_insert('addressbook_info',$arr)!==FALSE){
				return TRUE;
			}
		}
		elseif($type=='edit'){
			$info = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$arr['fld_int_id']),'object');
			if($this->curd_m->get_update('addressbook_info',$arr)){
				if($info !== FALSE){
					 return TRUE;
				}
				else return FALSE;
			}
		}

		//$noticeMessage = ( $type == 'new')? 'Addressbook ,'.$arr['fld_chr_name'].', Has been Created':
	}
	public function template($data,$type,$id){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update
		foreach($data as $key=>$val){
				if($val !==NULL){
					$arr[$key] = $val;
				}
				else{  unset($data[$key]);	}
		}

		$res = (isset($arr['fld_chr_title']))?$this->curd_m->checkRecur('sms_templates',array('fld_chr_title__Template Name'=>$arr['fld_chr_title'])):TRUE;

		if($res !== TRUE ) die(ucwords($res));
		if($type=='new' ){
			$arr['fld_user_id'] = $this->session->userdata('userId');
			$arr['fld_int_date'] = time();
			return ($this->curd_m->get_insert('sms_templates',$arr)!==FALSE)?TRUE:FALSE;
		}
		elseif($type=='edit'){
			return ($this->curd_m->get_update('sms_templates',$arr))?TRUE:'Update Fail';
		}
	}

	public function msgTemplate($data){

		$res = $this->curd_m->get_search('SELECT * FROM msgTemplate WHERE sender_id="'.$data['sender_id'].'" AND user_id='.$data['user_id'],'object');
		if($res !==NULL ) die('Template already exist for Selected Sender ID');
		return ($this->curd_m->get_insert('msgTemplate',$data)!==FALSE)?TRUE:'Error Unable to entry new template';
	}





	public function repeatContactChk($adbId, $number){
		$res = $this->curd_m->get_search('SELECT * FROM addressbook WHERE fld_int_addb_id = '.$adbId.' AND fld_chr_phone="'.$number.'"');
		return ($res != NULL) ? FALSE : TRUE;
	}


	public function addSenderId($data){

		$gw = $data['gw'];
		if($data['gw']=='default'){
			$gw = $this->curd_m->get_search('SELECT * FROM gateway WHERE fld_int_priority = 1 AND fld_chr_operator='.$data['operator'],'object');
			if($gw==NULL) return '** Error : Unable to request sender ID';
			$gw = $gw[0]->fld_int_id;
		}
		$res = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_userid='.$data['userid'].' AND fld_chr_senderid="'.$data['senderid'].'" AND fld_gateway='.$gw,'object');

		if($res ==NULL) $res = 0;
		elseif($res[0]->fld_int_default == 1){
			return "Requested sender Id Already Exist as Default";
		}
		else if($res[0]->fld_int_request == 2 ) $res = 1;
		else $res = 0;

		if($data['userid']== 1){
			$res1 = $this->curd_m->get_search('SELECT COUNT(*) AS counts FROM easy_senderid WHERE fld_gateway='.$gw.' AND fld_int_default=1','object');
			if($res1!=NULL){
				if((int)$res1[0]->counts > 0)	return "Default sender Already Exist for Selected Operator";
			}
		}
		else{
			$senders = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_chr_senderid="'.$data['senderid'].'" AND fld_gateway='.$gw.' AND fld_int_userid='.$data['userid'],'object');
			if($senders !=NULL) return "Requested sender Id Already Exist";
		}

		$res1 = $this->curd_m->get_insert('easy_senderid',array(
										'fld_int_userid'=>$data['userid'],
										'fld_chr_senderid'=>$data['senderid'],
										'fld_int_request'=>($data['userid']== 1)?2:1,
										'fld_int_default'=>($data['userid']== 1)?1:2,
										'fld_gateway'=>$gw,
										'fld_chr_ondate'=>time(),
										'fld_already_exist'=>$res,
										'fld_description'=>$data['description'],
										'operator'=>$data['operator']
									));

		return  ($res1===FALSE)?'** Error : Unable to request sender ID':TRUE;
	}
	public function uploadSenderId($data){

		$gw = $data['gw'];
		if($data['gw']=='default'){
			$gw = $this->curd_m->get_search('SELECT * FROM gateway WHERE fld_int_priority = 1 AND fld_chr_operator='.$data['operator'],'object');
			if($gw==NULL) return '** Error : Unable to request sender ID due to gateway';
			$gw = $gw[0]->fld_int_id;
		}
		$datArr = array();
		foreach($data['senderid'] as $val){
			$datArr[] = array(
							'fld_int_userid'=>$data['userid'],
							'fld_chr_senderid'=>$val,
							'fld_int_request'=>1,
							'fld_int_default'=>2,
							'fld_gateway'=>$gw,
							'fld_chr_ondate'=>time(),
							'fld_already_exist'=>0,
							'fld_description'=>$data['description'],
							'operator'=>$data['operator']
						);
		}
		$sizeofSenderid = sizeof($datArr);
		$datArr = array_chunk($datArr,25);

		foreach($datArr as $val){
			$res1 = $this->curd_m->get_insert('easy_senderid',$val,'batch');
			if($res1===FALSE) return '** Error : Unable to request sender ID';
		}

		$this->common_m->setNotice('senderid','upload',$this->session->userdata('reseller'),$this->session->userdata('userId'),$this->userPrivileges,($sizeofSenderid.' sender ID uploaded for approval'));
		return  TRUE;
	}
	public function senderIdOperate($type,$id,$priv){
		$state = ($type=='disapprove')?($this->session->userdata('userId')==1)?4:3 : 2;
		$data = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_id IN( '.implode(',',$id) .')','object');
		if($data===NULL){
			return '** Error : Unable to Update Sender ID State';
		}else{
			foreach($data as $row){
				if($this->session->userdata('userId')==1){
					if($type =='disapprove' && $row->fld_int_request ==4  ){
						return "** Warning : Sender ID already disapproved";
					}
				}
				elseif(in_array('PUSH',$priv)){
					if($row->fld_int_request > 2 ){
						return "** Warning : This sender ID is disabled by Admin ";
					}
					elseif($row->fld_int_request ==2 && $type =='disapprove' ){
						return "** Warning :Don't Have enough privileges to disapprove ,approved Sender ID";
					}
					elseif($type =='approve' ){
						return "** Warning : Don't Have enough privileges to approve Sender ID";
					}

				}
				elseif(in_array('USER_MANAGE',$priv)){
					if($row->fld_int_request > 3 ){
						return "** Warning : This sender ID is disabled by Admin ";
					}
					elseif($type =='disapprove' && $row->fld_int_request ==3 ){
						return "** Warning : Sender ID already disapproved";
					}
				}
			}
		}
		$arr = array();
		foreach($id as $val){
			$arr[] = array(
						'fld_int_id'=>$val,
						'fld_int_request'=>$state
						);
		}
		$res = $this->curd_m->get_update('easy_senderid',$arr,'batch');

		if($res===TRUE){
			foreach($data as $row){
				$this->common_m->setNotice('senderid',$type,$this->session->userdata('userId'),$row->fld_int_userid,$this->userPrivileges,$row->fld_chr_senderid);
			}
			return 'sucess';
		}
		else return '**Error : Unable to Update Sender ID State';
	}
// model ends
}
