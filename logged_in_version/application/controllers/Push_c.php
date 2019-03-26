<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Push_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('curd_m','push_m','common_m','sendsms_m'));
		$this->userPrivileges = $this->vas->getUserPrivileges();

	}
	public function pushView($type,$id=NULL){
		if($type=='sendsms' ) {
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$operator = $this->common_m->getOperatorByCountry($this->session->userdata('userId'));
			$adbok = $this->curd_m->getData('addressbook_info',array('fld_int_userid'=>$this->session->userdata('userId')),'object');
			$sender=$this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_userid='.$this->session->userdata('userId').' AND fld_int_default=2 AND fld_int_request=2','object');
			$template=$this->curd_m->getData('sms_templates',array('fld_user_id'=>$this->session->userdata('userId')),'object');
			$this->data['template'] = ($template===FALSE)?'none':$template;
			$this->data['operator'] =($operator===FALSE)?'none':$operator;
			$this->data['addressbook'] =($adbok===FALSE)?'none':$adbok;
			$this->data['senderid'] = ($sender==NULL)?'none':$sender;
			$this->load->view('push/sendSms_v',$this->data);
		}
		elseif($type == 'addScheduler'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$operator = $this->common_m->getOperatorByCountry($this->session->userdata('userId'));
			$adbok = $this->curd_m->getData('addressbook_info',array('fld_int_userid'=>$this->session->userdata('userId')),'object');
			$template=$this->curd_m->getData('sms_templates',array('fld_user_id'=>$this->session->userdata('userId')),'object');
			$sender=$this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_userid='.$this->session->userdata('userId').' AND fld_int_default=2 AND fld_int_request=2','object');
			$this->data['template'] = ($template===FALSE)?'none':$template;
			$this->data['addressbook'] =($adbok===FALSE)?'none':$adbok;
			$this->data['operator'] =($operator===FALSE)?'none':$operator;
			$this->data['senderid'] = ($sender==NULL)?'none':$sender;
			$this->load->view('push/addScheduler',$this->data);
		}
		elseif($type == 'addMsgTemplate'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$operator = $this->common_m->getOperatorByCountry($this->session->userdata('userId'));
			$sender=$this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_userid='.$this->session->userdata('userId').' AND fld_int_default=2 AND fld_int_request=2','object');
			$this->data['priv'] = $this->userPrivileges;
			$this->data['operator'] =($operator===FALSE)?'none':$operator;
			$this->data['senderid'] = ($sender==NULL)?'none':$sender;
			$this->load->view('push/addMsgTemplate',$this->data);
		}
		elseif($type=='addGateway'){
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->data['country'] = $this->curd_m->getData('country',NULL,'object');
			$this->load->view('push/addGateway',$this->data);
		}
		elseif($type=='clientTransaction'){
			//if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			//$this->data['country'] = $this->curd_m->getData('country',NULL,'object');
			$this->load->view('push/clientTransaction');
		}


		elseif($type=='editGateway'){
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->data['gateway'] = $this->curd_m->getData('gateway',array('fld_int_id'=>$id),'object');
			$this->data['operator'] = $this->common_m->getSiblingOperator($this->data['gateway'][0]->fld_chr_operator);
			$this->load->view('push/editGateway',$this->data);
		}
		elseif($type=='assignGateway'){
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			if(!$this->common_m->isMyClinet($id)){
				$this->vas->expire_message('window','data','Users is not your Client');
			}
			if($this->session->userdata('userId')==1){
				$this->data['gateway'] = $this->curd_m->getData('gateway',array('fld_int_priority'=>2),'object');
			}
			else{
				$agentGateway= $this->curd_m->get_search('SELECT g.fld_char_gw_name AS fld_char_gw_name, g.fld_int_id AS fld_int_id FROM user_gateway u INNER JOIN gateway g WHERE g.fld_int_id=u.fld_gw_id AND u.fld_user_id='.$this->session->userdata('userId'),'object');
				$this->data['gateway'] = ($agentGateway==NULL)?'none':$agentGateway;
			}
			$userGateway= $this->curd_m->get_search('SELECT g.fld_char_gw_name AS fld_char_gw_name, g.fld_int_id AS fld_int_id  FROM user_gateway u INNER JOIN gateway g WHERE g.fld_int_id=u.fld_gw_id AND u.fld_user_id='.$id,'object');
			$this->data['userGateway'] = ($userGateway==NULL)?'none':$userGateway;

			$this->load->view('push/assignGateway',$this->data);
		}
		elseif($type=='addAddressbook'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('push/addAddressbook');
		}
		elseif($type=='editAddressbook'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->data['addressbook'] = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$id ),'object');
			$this->load->view('push/editAddressbook',$this->data);
		}
		elseif($type=='addContact'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('push/addContact');
		}
		elseif($type=='editContact'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$id = explode('_',$id);
			$detail= $this->curd_m->get_search('SELECT * FROM addressbook WHERE fld_int_id IN ('.implode(',',$id).')','object');
			if($detail==NULL)	$this->vas->expire_message('window','data',"** Warning Invalid Selection");

			$this->data['detail'] =  $detail;
			$this->load->view('push/editContact',$this->data);
		}
		elseif($type=='uploadAddress'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('push/uploadAddress');
		}
		elseif($type=='addressDetail'){
			$this->data['addressDetail'] = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$id),'object');
			$res= $this->curd_m->get_search('SELECT count( * ) AS cnts,fld_int_career AS operator FROM addressbook WHERE fld_int_addb_id = '.$id.' GROUP BY fld_int_career;','object');
			$num_count= array();

			$oper = $this->common_m->getOperatorByCountry($this->session->userdata('userId'));
			if($oper == NULL) die('No Operator found ');
			foreach($oper as $row){
				if($res !=NULL){
					foreach($res as $row1){
						if($row->fld_int_id == $row1->operator) $num_count[strtoupper($row->acronym)] = $row1->cnts;
						else{
							if(!isset($num_count[strtoupper($row->acronym)])) $num_count[strtoupper($row->acronym)] = 0;
						}
					}
				}
				else{
					$num_count[strtoupper($row->acronym)] = 0;
				}

			}

			$this->data['counts'] = $num_count;
			$this->load->view('push/addressbookDetail',$this->data);
		}
		elseif($type=='addSenderId'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges ) && $this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}
			$operator = $this->common_m->getOperatorByCountry($this->session->userdata('userId'));
			$this->data['operator'] =($operator===FALSE)?'none':$operator;
			$this->load->view('push/addSenderId',$this->data);
		}
		elseif($type=='addTemplate'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('push/addTemplate');
		}
		elseif($type=='editTemplate'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->data['template'] = $this->curd_m->getData('sms_templates',array('fld_int_id'=>$id ),'object');
			$this->load->view('push/editTemplate',$this->data);
		}
		elseif($type=='uploadSenderId'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$operator = $this->common_m->getOperatorByCountry($this->session->userdata('userId'));
			$this->data['operator'] =($operator===FALSE)?'none':$operator;
			$this->load->view('push/uploadSenderId',$this->data);
		}
		elseif($type=='editScheduler'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$detail=$this->curd_m->get_search('SELECT * FROM que_job WHERE fld_int_id="'.$id.'"','object');
			$template=$this->curd_m->getData('sms_templates',array('fld_user_id'=>$this->session->userdata('userId')),'object');
			$this->data['template'] = ($template===FALSE)?'none':$template;
			$this->data['detail'] = ($detail==NULL)?die('Error : error found in operation'):$detail[0];
			$this->load->view('push/editScheduler',$this->data);
		}

	}
	public function renderGateway(){
		if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->push_m->renderGateway();
	}
	public function renderAddressbook(){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){die('**Warning:Not Sufficient Privileges');}
		$this->push_m->renderAddressbook();
	}
	public function renderQueue(){
		if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){die('**Warning:Not Sufficient Privileges');}

		$this->push_m->renderQueue($this->userPrivileges );
	}
	public function renderSenderId($data=NULL){
		if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}

		if($data=='search' || $data =='download'){
			$err= array();
			if($data=='download'){
				if($this->input->get('id')){
					$res = $this->curd_m->get_search('SELECT * FROM download_code WHERE fld_code="'.$this->input->get('id').'"','object');
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

		$this->push_m->renderSenderId(array(
												'senderid'=>($this->input->get('senderId'))?$this->input->get('senderId'):NULL,
												'reqby'=>($this->input->get('reqby'))?$this->input->get('reqby'):NULL,
												'state'=>($this->input->get('state')=='none')?NULL:$this->input->get('state'),
												'sdate'=>($this->input->get('searchStart'))?$this->input->get('searchStart'):NULL,
												'tdate'=>($this->input->get('searchTill'))?$this->input->get('searchTill'):NULL,
												'privileges' => $this->userPrivileges,
												'type'=>($data==NULL)?'none':$data
												));

	}
	public function renderTemplate(){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->push_m->renderTemplate();
	}
	public function renderContact($id){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->push_m->renderContact($id);
	}
	public function renderMsgTemplate(){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->push_m->renderMsgTemplate($this->session->userdata('userId'));
	}
	public function renderSchduler(){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->push_m->renderSchduler($this->session->userdata('userId'));
	}
	public function gateway($type, $id=NULL){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if($type == 'edit' && sizeof($this->input->post())==1 ) die('** No data found for update');
		if( $type=='new' || $this->input->post('gname')) $this->form_validation->set_rules('gname','Gateway Name', 'required|alpha_numeric|max_length[20]');
		if( $type=='new' || $this->input->post('host')) $this->form_validation->set_rules('host','Host Name', 'required|max_length[100]');
		if( $type=='new' || $this->input->post('port')) $this->form_validation->set_rules('port','Port', 'required|numeric');
		if( $type=='new' || $this->input->post('smsc')) $this->form_validation->set_rules('smsc','SMSCID', 'required|max_length[50]');
		if( $type=='new' || $this->input->post('username')) $this->form_validation->set_rules('username','Username','required|max_length[40]');
		if( $type=='new' || $this->input->post('password')) $this->form_validation->set_rules('password','Password','required|max_length[20]');
		if( $type=='new' || $this->input->post('priority')) $this->form_validation->set_rules('priority','Priority', 'required|numeric');
		if( $type=='new' || $this->input->post('operator')) $this->form_validation->set_rules('operator','Operator', 'required|numeric');

		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }

		if($this->input->post('operator') == 'none') die('Operator feild Required');

		$res = $this->push_m->gateway(array(
									'fld_char_username'=>($this->input->post('username'))?$this->input->post('username'):NULL,
									'fld_char_hostname'=>$this->input->post('host')?$this->input->post('host'):NULL,
									'fld_char_password'=>$this->input->post('password')?$this->input->post('password'):NULL,
									'fld_chr_smscid'=>$this->input->post('smsc')?$this->input->post('smsc'):NULL,
									'fld_chr_operator'=>$this->input->post('operator')?$this->input->post('operator'):NULL,
									'fld_char_gw_name'=>$this->input->post('gname')?$this->input->post('gname') : NULL,
									'fld_int_port'=>$this->input->post('port')?$this->input->post('port'):NULL,
									'fld_int_priority'=>$this->input->post('priority')?$this->input->post('priority'):NULL,
								),$type,$id);
		die( ($res===TRUE)?'sucess':$res);
	}
	public function deleteItem($item){
		$table = NULL;
		$id = ($this->input->post('id'))?explode(',',$this->input->post('id')):die('** No Item found for deletion');

		if($item=='gateway'){
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			if($this->curd_m->getData('user_gateway',array('fld_gw_id'=>$id[0]) ) !== FALSE)
				die("Unable to Delete ,It Has been assigned to user");
			$table = 'gateway'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='address_delete'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->curd_m->get_delete('addressbook',array('fld_int_addb_id'=>array($id[0]) ));
			$table = 'addressbook_info'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='contact_delete'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$table = 'addressbook'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='template'){
			if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$table = 'sms_templates'; $data = array('fld_int_id'=>$id);
		}
		elseif($item == 'sender_id'){
			if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$data = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_id IN( '.implode(',',$id) .')','object');
			if($data===NULL){
				return '** Error : Unable to Update Sender ID State';
			}else{
				foreach($data as $row){
					if($row->fld_int_request==2 && $this->session->userdata('userId')!=1)
					die( "** Warning : you don't have privileges to delete approved sender ID");
				}
			}
			foreach($data as $row){
				$this->common_m->setNotice('senderid','delete',1,$row->fld_int_userid,$this->userPrivileges,$row->fld_chr_senderid);
			}
			$table = 'easy_senderid'; $data = array('fld_int_id'=>$id);
		}
		elseif($item == 'scheduler' || $item =='cron'){
			if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$users = $this->session->userdata('userId');
			$quejobs = $this->curd_m->get_search('SELECT * FROM que_job WHERE fld_int_id="'.$id[0].'"','object');
			if($users!=1 && in_array('USER_MANAGE',$this->userPrivileges) && $quejobs[0]->state >3 ){
				die('Que Job is disabled by admin');
			}
			elseif($users!=1 && !in_array('USER_MANAGE',$this->userPrivileges) && $quejobs[0]->state >2 ){
				die('Que Job is disabled by admin');
			}

			if($this->curd_m->get_delete('que_detail',array('que_id'=>$id))){
				$table = 'que_job'; $data = array('fld_int_id'=>$id);
				$this->common_m->setNotice('quejob','delete',$this->session->userdata('userId'),$quejobs[0]->users_id,$this->userPrivileges,'[ Message : '.$quejobs[0]->message.' | Message Count : '.$quejobs[0]->messageCount.' | '.$quejobs[0]->fld_int_cell_no.' ]');

			}else{
				die(($item =='PUSH')?'Error : Unable to delete Que Job':'Error : Unable to delete Scheduler Job');
			}
		}

		if($table!=NULL){
			$info = NULL;
			if($item=='contact_delete'){
				$info=$this->curd_m->get_search('SELECT i.fld_chr_name AS fld_chr_name FROM addressbook_info i INNER JOIN addressbook a WHERE a.fld_int_id='.$id[0].' AND i.fld_int_id=a.fld_int_addb_id','object');
			}
			elseif($item=='address_delete'){
				$info = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$id[0]),'object');
			}
			if($this->curd_m->get_delete($table,$data)){
				if($item=='contact_delete'){
					$res = $this->common_m->setNotice('addressbook','contact_delete',$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,sizeof($data).'- contact deleted from addressbook [ '.$info[0]->fld_chr_name.' ]');
				}
				elseif($item=='address_delete'){
					$res = $this->common_m->setNotice('addressbook','address_delete',$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,'Addressbook [ '.$info[0]->fld_chr_name.' ] deleted');
				}
				die('sucess');
			}else{
				die('** Error In item deletion');
			}
		}
		die('** Invalid Query');
	}

	public function assignGateway($type,$id,$userId=NULL){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if($type == 'assign'){
			if($this->curd_m->getData('user_gateway',array('fld_gw_id'=>$id,'fld_user_id'=>$userId) ) !== FALSE)
				die("Gateway Assigned Already");

			$res = $this->curd_m->get_insert('user_gateway',array('fld_gw_id'=>$id,'fld_user_id'=>$userId));
			die(( $res !== FALSE)?'sucess':'** Error : Gateway Assign Fail');
		}
		elseif($type == 'remove'){
			$this->db->where('fld_gw_id',$id);
			$this->db->where('fld_user_id',$userId);
			$this->db->delete('user_gateway');
			die(( $this->db->affected_rows() >0)?'sucess':'** Error : Gateway Remove Fail');
		}
	}
	public function addressbook($type, $id=NULL){

		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){
			die('**Warning:Not Sufficient Privileges');
		}

		if($type == 'edit' && sizeof($this->input->post())==1 ) die('** No data found for update');
		if( $type=='new' || $this->input->post('name'))
						$this->form_validation->set_rules('name','Addressbook Name','required|max_length[50]');
		if( $type=='new' || $this->input->post('description'))
						$this->form_validation->set_rules('description','Description', 'required|max_length[200]');

		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }
		$info = ($id==NULL)?'':$this->curd_m->getData('addressbook_info',array('fld_int_id'=>$id),'object');
		$res = $this->push_m->addressbook(array(
									'fld_chr_name'=>($this->input->post('name'))?htmlentities(strtolower($this->input->post('name'))) :NULL,
									'fld_chr_desc'=>$this->input->post('description')?strtolower($this->input->post('description')) :NULL,
								),$type,$id);
		if($res===FALSE){

			die($res);
		}else{
			if($type=='new'){
				$this->common_m->setNotice('addressbook',$type,$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,$this->input->post('name'));
			}
			elseif($type=='edit'){
				$des = '';

				if($this->input->post('name')){
					$des = 'Addressbook Name edited // '.$info[0]->fld_chr_name.' to '.$this->input->post('name');
				}
				elseif($this->input->post('description')){
					$des = 'Addressbook [ '.$info[0]->fld_chr_name.' ] description edited';
				}
				$this->common_m->setNotice('addressbook',$type,$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,$des);
			}
			die('sucess');
		}
	}
	public function addContact($adbId){

		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$count = 0;
		$arr = array();
		$numbers = array();
		$names = array();
		for($i=1; $i<11; $i++){

			if($this->input->post('mobile'.$i) ){
				$numbers[] = $this->input->post('mobile'.$i);
				$names[$this->input->post('mobile'.$i)] = $this->input->post('name'.$i);
				$count++;
			}
		}
		if($count==0) die('noentry');

		$validNumber = $this->common_m->verifyNumber($numbers);
		$invlaidNumb = array();
		$alreadyExist = array();
		if( isset($validNumber['fault'])) $invlaidNumb['fault']=$validNumber['fault'];
		if( isset($validNumber['repeat'])) $invlaidNumb['repeat']=$validNumber['repeat'];
		if(sizeof($invlaidNumb) >0) die(json_encode($invlaidNumb));

		foreach($validNumber as $key=>$val ){
			foreach($val as $val1){
				if(!$this->push_m->repeatContactChk($adbId,$val1)){
					$alreadyExist[] = $val1;
				}
				$arr[] = array(
							'fld_chr_name'=>$names[$val1],
							'fld_chr_phone'=>$val1,
							'fld_int_addb_id'=>$adbId,
							'fld_int_career'=>$key,
							);
			}
		}
		if(sizeof($alreadyExist) > 0) die(json_encode( array('exist'=>$alreadyExist)) );

		if($this->curd_m->get_insert('addressbook',$arr,'batch')){
			$info = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$adbId),'object');
			if($info !== FALSE){
				$res = $this->common_m->setNotice('addressbook','contact_add',$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,$info[0]->fld_chr_name);

				die('sucess');
			}
		}

	}
	public function editContact($abid){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if(sizeof($this->input->post())==1) die('** No data found for update');
		$data = array();

		foreach($this->input->post() as $key=>$val){
			$var = explode('_',$key);
			$arr = array();
			//echo json_encode($var);
			if(ctype_digit($var[0]) ){
				$arr['fld_int_id'] = $var[0];
				if(substr($var[1],0,4)=='name'){

					$arr['fld_chr_name'] = $val;
				}
				elseif(substr($var[1],0,6)=='mobile'){

					$validNumber = $this->common_m->verifyNumber(array($val));
					if( isset($validNumber['fault'])) die('invalid Number found');
					if( isset($validNumber['repeat'])) die('Similar number found in entry');
					if(!$this->push_m->repeatContactChk($abid,$val)) die($val.' Number already exist');
					$keys = array_keys($validNumber);
					$arr['fld_chr_phone'] = $val;
					$arr['fld_int_career'] = $keys[0];


				}
			}
			if(sizeof($arr) > 0) $data[] = $arr;
		}

		$res = $this->curd_m->get_update('addressbook',$data,'batch');

		if($res===TRUE ){
			$info = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$abid),'object');
			if($info !== FALSE){
				//die(sizeof($data).'- contact edited of addressbook [ '.$info[0]->fld_chr_name.' ]');
				$res = $this->common_m->setNotice('addressbook','contact_edit',$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,sizeof($data).'- contact edited of addressbook [ '.$info[0]->fld_chr_name.' ]');

				die('sucess');
			}
		}
		else{
			die( '** Warning : Unable to update contact');
		}
	}
	public function emptyContact($adbid){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if($this->curd_m->getData('addressbook',array('fld_int_addb_id'=>$adbid))===FALSE) die('Addressbook is Empty');
		if( $this->curd_m->get_delete('addressbook',array('fld_int_addb_id'=>array($adbid) ))===TRUE){
			$info = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$adbid),'object');
			$this->common_m->setNotice('addressbook','contact_upload',$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,'Addressbook [ '.$info[0]->fld_chr_name.' ] emptyied');
			die('sucess');
		}else{
			die('**Error : Unable to Truncate addressbook' );
		}
	}

	public function uploadAddress($abid){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->form_validation->set_rules('data','File', 'required');
		$this->form_validation->set_rules('type','Upload Type', 'required');
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }
		$data = explode('_',$this->input->post('data'));
		$type = $this->input->post('type');
		$exist = array();
		$numbers = array();
		foreach($data as $val){
			if($type == 'both'){
				$val = explode(',',$val);
				$val = $val[1];
			}
			if(!$this->push_m->repeatContactChk($abid,$val) ){ $exist[]= $val;}
			else $numbers[] = $val;
		}
		$error = array();
		if(sizeof($exist) > 0){ $error[] ='exist:'.implode(' , ',$exist) ;}
		if(sizeof($numbers) == 0 ) {
			if(sizeof($error) > 0 ) die( implode('_',$error) );
			else die('No valid numbers found for upload');
		}
		$validNumber = $this->common_m->verifyNumber($numbers);


		$numbers =array();
		foreach( $validNumber as $key => $val){
			foreach($val as $val1){
				$numbers[$val1] = $key;
			}
		}
		$arr = array();
		foreach($data as $val){

				if($type == 'both'){
					$val = explode(',',$val);
					if(isset($numbers[$val[1]])){
						$arr[]= array(
								'fld_chr_name'=>$val[0],
								'fld_chr_phone'=>$val[1],
								'fld_int_career'=>$numbers[$val[1]],
								'fld_int_addb_id'=>$abid,
							);
					}
				}
				else{
					if(isset($numbers[$val])){
						$arr[]= array(
								'fld_chr_name'=>'Undefined',
								'fld_chr_phone'=>$val,
								'fld_int_career'=>$numbers[$val],
								'fld_int_addb_id'=>$abid,
							);
					}
				}
		}
		if($this->curd_m->get_insert('addressbook',$arr,'batch')===TRUE){
			$info = $this->curd_m->getData('addressbook_info',array('fld_int_id'=>$abid),'object');
			$this->common_m->setNotice('addressbook','contact_upload',$this->session->userdata('userId'),$this->session->userdata('userId'),$this->userPrivileges,sizeof($arr).'- contact uploaded in addressbook [ '.$info[0]->fld_chr_name.' ]');
			if( isset($validNumber['fault']) ) $error[] ='invalid:'.implode(' , ', $validNumber['fault']);
			if( isset($validNumber['repeat'])) $error[] = 'similar:'.implode(' , ',$validNumber['repeat']);

			if(sizeof($error) > 0 ) die( implode('_',$error) );
			else die('sucess');
		}
		else die('error: **Error: unable to upload contact');
		//die( ($this->curd_m->get_insert('addressbook',$arr,'batch')===TRUE)?'sucess':'error: **Error: unable to upload contact');
	}

	public function template($type, $id=NULL){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges)){die('**Warning:Not Sufficient Privileges');}
		if($type == 'edit' && sizeof($this->input->post())==1 ) die('** No data found for update');
		if( $type=='new' || $this->input->post('name')) $this->form_validation->set_rules('name','Template Name', 'required|max_length[100]');
		if( $type=='new' || $this->input->post('msgType')) $this->form_validation->set_rules('msgType','Message Type', 'required');
		if( $type=='new' || $this->input->post('nepbox')) $this->form_validation->set_rules('nepbox','Message', 'required');

		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }

		if( $type=='new' || ($this->input->post('nepbox') && $this->input->post('msgType')) ){
			$mCount = $this->common_m->count_message($this->input->post('msgType'),$this->input->post('nepbox'));
			$mCount = $mCount['msg_len'];
		}
		else{
			$mCount = NULL;
		}
		$res = $this->push_m->template(array(
									'fld_chr_title'=>$this->input->post('name')?$this->input->post('name'):NULL,
									'fld_int_mst'=>$this->input->post('msgType')?$this->input->post('msgType'):NULL,
									'fld_chr_msg'=>$this->input->post('nepbox')?$this->input->post('nepbox'):NULL,
									'fld_int_msg_cnt'=>$mCount,

								),$type,$id);
		die( ($res===TRUE)?'sucess':$res);
	}
	public function sendEomSms(){
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->form_validation->set_rules('data','Message Data', 'required');
		$this->form_validation->set_rules('senderid','Sender ID', 'required');
		$this->form_validation->set_rules('msgType','Message Type', 'required');

		if ($this->form_validation->run() == FALSE){ die( validation_errors()); }
		$data = json_decode($this->input->post('data'));
		$data = $data->rows;
		$eomArr = array();
		foreach($data as $row){
			$number =   trim(str_replace("\r","",str_replace("\n","",$row->data[0])));
			$eomArr[$number] = array('number'=>$number,'message'=>$row->data[1]);
		}

		$res = $this->sendsms_m->sendEomSms(array(
												'eomArr'=>$eomArr,
												'senderid'=>$this->input->post('senderid'),
												'msgType'=>$this->input->post('msgType'),
												'userId'=>$this->session->userdata('userId'),
												'balanceType'=>$this->session->userdata('balanceType'),
												'date'=>time(),
												'queid'=>uniqid(),
												'sendby'=>'appuser',
												'agentId'=>$this->session->userdata('reseller')
											));
		die(is_array($res)?'sucess__'.json_encode($res):$res);
	}
	public function sendSms(){

		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if(!$this->input->post('numbers') && !$this->input->post('addressbook')) die('**Warning : There is no valid number list found');


		$this->form_validation->set_rules('message','Message', 'required');
		$this->form_validation->set_rules('messageType','Message Type', 'required|numeric');
		if(!$this->input->post('processType')){
			$this->form_validation->set_rules('eventName','Event Name', 'required|max_length[100]');
			$this->form_validation->set_rules('dates','Enter Dates', 'required');
		}
		if ($this->form_validation->run() == FALSE){ die( validation_errors()); }
		$dataArr = array();
		$res = NULL;
		if($this->input->post('processType')){
			if($this->input->post('processType') == 'default'){
				//die($this->input->post('message'));
				$res = $this->sendsms_m->addQue(array(
						'numbers'=>($this->input->post('numbers'))?$this->input->post('numbers'):NULL,
						'addressbook'=> ($this->input->post('addressbook'))?$this->input->post('addressbook'):NULL,
						'message'=>$this->input->post('message'),
						'schedule'=> 0,
						'messageType'=>$this->input->post('messageType'),
						'eventName'=>'none',
						'senderId'=>$this->input->post('sendidList'),
						'schedulerDates'=>'none',
						'userId'=>$this->session->userdata('userId'),
						'date'=>time(),
						'agentId'=>$this->session->userdata('reseller'),
						'excludeNumber'=>($this->input->post('excludeNumber')!='none')?explode(',',$this->input->post('excludeNumber')):NULL,
						'queid'=>uniqid(),
						'balanceType'=>$this->session->userdata('balanceType'),
						'sendby'=>'appuser'
					));
			}
			elseif($this->input->post('processType') == 'quick'){

				$res = $this->sendsms_m->quickSend(array(
						'numbers'=>($this->input->post('numbers'))?$this->input->post('numbers'):NULL,
						'addressbook'=> ($this->input->post('addressbook'))?$this->input->post('addressbook'):NULL,
						'message'=>$this->input->post('message'),
						'schedule'=> 0,
						'messageType'=>$this->input->post('messageType'),
						'eventName'=>'none',
						'senderId'=>$this->input->post('sendidList'),
						'schedulerDates'=>'none',
						'userId'=>$this->session->userdata('userId'),
						'date'=>time(),
						'agentId'=>$this->session->userdata('reseller'),
						'excludeNumber'=>($this->input->post('excludeNumber')!='none')?explode(',',$this->input->post('excludeNumber')):NULL,
						'queid'=>uniqid(),
						'balanceType'=>$this->session->userdata('balanceType'),
						'sendby'=>'appuser'
					));
			}

		}
		else{
			$res = $this->sendsms_m->addQue(array(
						'numbers'=>($this->input->post('numbers'))?$this->input->post('numbers'):NULL,
						'addressbook'=> ($this->input->post('addressbook'))?$this->input->post('addressbook'):NULL,
						'message'=>$this->input->post('message'),
						'schedule'=> 1,
						'messageType'=>$this->input->post('messageType'),
						'eventName'=>$this->input->post('eventName'),
						'senderId'=>$this->input->post('sendidList'),
						'schedulerDates'=>$this->input->post('dates'),
						'userId'=>$this->session->userdata('userId'),
						'date'=>time(),
						'agentId'=>$this->session->userdata('reseller'),
						'excludeNumber'=>($this->input->post('excludeNumber')!='none')?explode(',',$this->input->post('excludeNumber')):NULL,
						'queid'=>uniqid(),
						'balanceType'=>$this->session->userdata('balanceType'),
						'sendby'=>'appuser'
					));
		}


		die(is_array($res)?'sucess__'.json_encode($res):$res);
	}
	public function addSenderId(){

		if($this->input->post('operator')=='none') die('Select Operator feild required');
		$this->form_validation->set_rules('gateway','Select Gateway', 'required');
		$this->form_validation->set_rules('senderid','Sender ID', 'required|max_length[11]');
		if ($this->form_validation->run() == FALSE){ die( validation_errors()); }
		$res = $this->push_m->addSenderId(array(
											'gw'=>$this->input->post('gateway'),
											'senderid'=>preg_replace('/\s/', '', $this->input->post('senderid') ),
											'userid'=>$this->session->userdata('userId'),
											'description'=>($this->input->post('descrption'))?$this->input->post('descrption'):'none',
											'operator'=>$this->input->post('operator')
											));
		if($res===TRUE){

			$this->common_m->setNotice('senderid','request',$this->session->userdata('reseller'),$this->session->userdata('userId'),$this->userPrivileges,preg_replace('/\s/', '', $this->input->post('senderid') ));
			die('sucess');
		}
		else{die($res);
		}
	}
	public function uploadSenderId(){

		$this->form_validation->set_rules('gateway','Select Gateway', 'required');
		$this->form_validation->set_rules('senderid','Sender ID', 'required');
		$this->form_validation->set_rules('operator','Select Operator', 'required');
		if ($this->form_validation->run() == FALSE){ die( validation_errors()); }
		$res = $this->push_m->uploadSenderId(array(
											'gw'=>$this->input->post('gateway'),
											'senderid'=>explode(',', $this->input->post('senderid') ),
											'userid'=>$this->session->userdata('userId'),
											'description'=>'none',
											'operator'=>$this->input->post('operator')
											));
		die( ($res===TRUE)?'sucess':$res);
	}
	public function senderIdOperate($type){
		if($type == 'disapprove'){
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges ) ){ die('**Warning:Not Sufficient Privileges');}
		}
		else{
			if($this->session->userdata('userId')!=1 ){ die('**Warning:Not Sufficient Privileges');}
		}

		if(!$this->input->post('id')) die('** Error : No Sender ID Selected');
		$res = $this->push_m->senderIdOperate( $type,explode(',',$this->input->post('id')),$this->userPrivileges);
		if($res===TRUE){
			die( 'sucess');
		}else{die( $res);}
	}
	public function editQuejob($id){
		if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->form_validation->set_rules('messageType','Message Type', 'required|numeric');
		$this->form_validation->set_rules('nepbox','Message', 'required');
		if($this->form_validation->run() == FALSE){ die( validation_errors()); }
		$msgCount = $this->common_m->count_message($this->input->post('messageType'),$this->input->post('nepbox'));
		$res = $this->curd_m->get_update('que_job',array(
										'fld_int_id'=>$id,
										'message'=>$this->input->post('nepbox'),
										'messageType'=>$this->input->post('messageType'),
										'messageCount'=>$msgCount['msg_len'].'-'.$msgCount['char_len']
									));
		die(($res==TRUE)?'sucess':'Unable to update Scheduler Job');
	}

	public function queJobOperation($type,$id){
		if(!$this->vas->verifyPrivillages(array('PUSH','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$stat = NULL;
		$curStat = $this->curd_m->getData('que_job',array('fld_int_id'=>'"'.$id.'"'),'object');
		//die(var_dump($curStat));
		if($type=='enable'){
			if($curStat[0]->state ==1)  die('Already Enabled');
			elseif($this->session->userdata('userId')==1){
				$stat = 1;
			}
			elseif($this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){
				if( $curStat[0]->state < 3)$stat = 1;
				elseif( $curStat[0]->state > 2) die('Disabled By Admin');
			}
			elseif($this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){
				if( $curStat[0]->state < 4)$stat = 1;
				elseif( $curStat[0]->state > 3) die('Disabled By Admin');
			}
		}
		elseif($type=='disable'){
			if($this->session->userdata('userId')==1){
				if( $curStat[0]->state < 4)$stat = 4;
				elseif( $curStat[0]->state == 4) die('Already Disabled');
			}
			elseif($this->vas->verifyPrivillages('PUSH',$this->userPrivileges )){
				if( $curStat[0]->state == 1)$stat = 2;
				elseif( $curStat[0]->state == 2) die('Already Disabled');
				elseif( $curStat[0]->state > 2) die('Disabled By Admin');
			}
			elseif($this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){
				if( $curStat[0]->state < 3)$stat = 3;
				elseif( $curStat[0]->state == 3) die('Already Disabled');
				elseif( $curStat[0]->state > 3) die('Disabled By Admin');
			}
		}

		$res = $this->curd_m->get_update('que_job',array(
										'fld_int_id'=>$id,
										'state'=>$stat
									));
		$this->common_m->setNotice('quejob',$type,$this->session->userdata('userId'),$curStat[0]->users_id,$this->userPrivileges);
		die(($res==TRUE)?'sucess':'Unable to update state');
	}
	public function msgTemplate(){
		$template = array();
		$senderId = NULL;
		if(!$this->vas->verifyPrivillages('PUSH',$this->userPrivileges)){die('**Warning:Not Sufficient Privileges');}
		$this->form_validation->set_rules('typeChoosen','Request Type', 'required');
		if($this->form_validation->run() == FALSE){ echo validation_errors();	return; }

		if($this->input->post('typeChoosen')=='specific'){
			if(!$this->input->post('sendertype') || !$this->input->post('senderId') )die('Sender Type feild is required');

			if($this->input->post('sendertype')=='default'){
				$senderId = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_default=1 AND operator='.$this->input->post('senderId') ,'object');

				if($senderId== NULL) die('Defalt Sender ID not found');
				$senderId =$senderId[0]->fld_int_id;
			}
			elseif($this->input->post('sendertype')=='specific'){
				$senderId = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_chr_senderid="'.$this->input->post('senderId').'" AND fld_int_userid='.$this->session->userdata('userId'),'object');
				if($senderId== NULL) die('Invalid Sender ID');
				$senderId =$senderId[0]->fld_int_id;
			}
			else die('Sender Type feild is required');

		}
		elseif($this->input->post('typeChoosen')=='all'){
			$senderId = 0;
		}

		if(!$this->input->post('header') && !$this->input->post('footer'))die();
		if($this->input->post('header'))$template['header']=trim($this->input->post('header'),"\r\n");
		if($this->input->post('footer'))$template['footer']=trim($this->input->post('footer'),"\r\n");

		$res = $this->push_m->msgTemplate(array(
									'sender_id'=>$senderId,
									'template'=>(sizeof($template)>0)?json_encode($template):NULL,
									'date'=>time(),
									'user_id'=>$this->session->userdata('userId')
								));
		die( ($res===TRUE)?'sucess':$res);
	}
	/*********all push render functions end*************/

}
