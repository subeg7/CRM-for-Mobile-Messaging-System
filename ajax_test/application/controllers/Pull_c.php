<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pull_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('pull_m','curd_m','common_m'));
		$this->userPrivileges = $this->vas->getUserPrivileges();
		//include_once ("assets/connector/grid_connector.php");

	}
	public function pullView($type,$id=NULL){
		if($type=='addShortcode'){
			if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('pull/addShortcode');
		}
		elseif($type=='editShortcode'){
			if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}
			$code = $this->curd_m->getData('shortcode',$id);
			$this->load->view('pull/editShortcode',array('shortcode'=>$code) );
		}
		elseif($type=='assignShortcode'){
			if(!$this->common_m->isMyClinet($id)){
				$this->vas->expire_message('window','data','Users is not your Client');
			}
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$userPriv = $this->vas->getUserPrivileges($id);
			if($this->session->userdata('userId')==1){
				$this->data['shortcode'] = $this->curd_m->getData('shortcode',array('assign_to'=>1),'object');
			}
			else{// if not admin user_shortcode
				$this->data['shortcode'] = $this->curd_m->get_search('SELECT s.fld_int_id AS fld_int_id ,s.fld_chr_name AS fld_chr_name FROM user_shortcode u INNER JOIN shortcode s WHERE u.fld_user_id='.$this->session->userdata('userId').' AND u.fld_shortcode_id=s.fld_int_id AND s.assign_to IN(1,'.$this->session->userdata('userId').') ','object');

			}
			//$reouteType = $this->curd_m->getData('user_route',array('fld_user_id'=>$id),'object');
			$this->data['admin'] = ($this->session->userdata('userId')==1)?'admin':'none';
			$userShortcode= $this->curd_m->get_search('SELECT u.assign_type AS assign_type, s.fld_int_id AS fld_int_id ,s.fld_chr_name AS fld_chr_name FROM user_shortcode u INNER JOIN shortcode s WHERE s.fld_int_id=u.fld_shortcode_id AND u.fld_user_id='.$id,'object');
			$this->data['userShortcode'] = ($userShortcode==NULL)?'none':$userShortcode;
			$this->load->view('pull/assignShortcode',$this->data);
		}
		elseif($type=='addCategory'){
			if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('pull/addCategory');
		}
		elseif($type=='editCategory'){
			if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}
			$this->data['category'] = $this->curd_m->getData('category',$id);

			$this->load->view('pull/editCategory',$this->data);

		}
		elseif($type=='addKey'){
			if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$res = $this->curd_m->getData('category',NULL,'object');
			$this->data['category'] = ($res===FALSE)?'none':$res;
			$shc = $this->curd_m->get_search('SELECT s.fld_chr_name AS name ,s.fld_int_id AS id FROM shortcode s INNER JOIN user_shortcode u WHERE u.fld_shortcode_id = s.fld_int_id AND u.fld_user_id='.$this->session->userdata('userId'),'object');
			$this->data['shortcode'] = ($shc ===NULL)?'none':$shc ;
			$this->data['route'] = ($this->vas->hasPrivileges('pullroute',$this->session->userdata('userId')))?'route':'none';
			$this->data['priv'] = $this->userPrivileges ;
			$this->load->view('pull/addKey',$this->data);
		}
		elseif($type=='VP'){
			if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('pull/VP');
		}
		elseif($type=='PTP'){
			if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$add = $this->curd_m->getData('addressbook_info',array('fld_int_userid'=>$this->session->userdata('userId') ),'object');
			$this->data['addressbook'] = ($add===FALSE)?'none':$add;
			$temp = $this->curd_m->getData('sms_templates',array('fld_user_id'=>$this->session->userdata('userId') ),'object');
			$this->data['template'] = ($temp===FALSE)?'none':$temp;

			$this->load->view('pull/PTP',$this->data);
		}
		elseif($type=='addScheme'){
			if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->load->view('pull/addScheme');
		}
		elseif($type == 'uploadData'){
			if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$res =$this->curd_m->get_search('SELECT p.fld_int_id AS id,p.keys_name AS name,s.fld_chr_name AS sname FROM pull_keys p INNER JOIN category c INNER JOIN shortcode s WHERE p.fld_category_id=c.fld_int_id AND c.upload_data=1 AND s.fld_int_id=p.fld_shortcode_id','object');
			$this->data['scheData'] = ($res==NULL)?'none':$res;
			$res = $this->curd_m->get_search('SELECT * FROM scheme','object');
			$this->data['schemes'] = ($res==NULL)?'none':$res;
			$this->load->view('pull/uploadDb',$this->data);
		}
		elseif($type=='uploadDb'){
			echo file_get_contents('assets/js/upload.js');
		}
		elseif($type == 'pullreport'){
			$this->load->view('report/pull_report_v');
		}
		elseif($type =='pullerror'){
			$this->load->view('report/pull_error_v');
		}
		elseif($type=='pullfeedback'){
			$this->load->view('report/pull_feedback_v');
		}
		elseif($type =='keydetail'){
			$mainkey = $this->curd_m->get_search('SELECT k.keys_name, k.sucess_message AS sucess_message, k.disable_message AS disable_message,k.fail_message AS fail_message ,c.category AS category FROM pull_keys k INNER JOIN category c WHERE k.fld_int_id='.$id.' AND k.fld_category_id = c.fld_int_id' ,'object');
			$this->data['mainkey'] = ($mainkey==NULL)?'none':$mainkey;
			if($mainkey!=NULL){
				if($mainkey[0]->category ==='ptp'){
					$subkey = $this->curd_m->get_search('SELECT  k.keys_name, k.sucess_message AS sucess_message, k.disable_message AS disable_message,k.fail_message AS fail_message ,a.fld_chr_name AS aname,t.fld_chr_title AS tname FROM pull_keys k INNER JOIN sms_templates t INNER JOIN addressbook_info a WHERE k.main_keys_id='.$id.' AND k.fld_addbook_id=a.fld_int_id AND k.fld_temp_id=t.fld_int_id','object');
					$this->data['subkey'] = ($subkey==NULL)?'none':$subkey;
				}else{
					$subkey = $this->curd_m->getData('pull_keys',array('main_keys_id'=>$id ),'object');
					$this->data['subkey'] = ($subkey==FALSE)?'none':$subkey;
				}
			}

			$this->load->view('pull/keyDetail_v',$this->data);
		}
	}
	public function deleteItem($item){
		$table = NULL;
		$id = ($this->input->post('id'))?explode(',',$this->input->post('id')):die('** No Item found for deletion');

		if($item=='shortcode'){
			if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}
			if($this->curd_m->getData('pull_keys',array('fld_shortcode_id'=>$id[0]) ) !== FALSE || $this->curd_m->getData('user_shortcode',array('fld_shortcode_id'=>$id[0]) ) !== FALSE)
				die("Unable to Delete , It has been assigned to another units");
			$table = 'shortcode'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='category'){
			if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}
			if($this->curd_m->getData('pull_keys',array('fld_category_id'=>$id[0]) ) !== FALSE)
				die("Unable to Delete , It has been assigned to another units");
			$table = 'category'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='scheme'){
			if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$table = 'scheme'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='keys'){
			$data = $this->curd_m->get_search('SELECT * FROM pull_keys WHERE fld_int_id ='.$id[0],'object');
			if($data[0]->state == 9 ){
				if($this->curd_m->get_delete('pull_keys',array('fld_int_id'=>array($data[0]->fld_int_id))) ){
					$this->common_m->setNotice('key','delete_request',$this->session->userdata('userId'),$data[0]->fld_user_id,$this->userPrivileges,strtolower($data[0]->keys_name));
					die('sucess');
				}else{ die('** Error In item deletion');}
			}

			if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}

			if($this->curd_m->get_update('pull_keys',array('fld_int_id'=>$id[0],'state'=>0))){
				$this->common_m->setNotice('key','delete',$this->session->userdata('userId'),$data[0]->fld_user_id,$this->userPrivileges,strtolower($data[0]->keys_name));
				die('sucess');
			}else{
				die('** Error In item deletion');
			}
		}
		if($table!=NULL){
			die( ($this->curd_m->get_delete($table,$data))?'sucess':'** Error In item deletion');
		}
		die('** Invalid Query');
	}
	/*********all pull render functions*************/
	public function renderShortcode(){
		if(!$this->vas->verifyPrivillages(array('PULL','USER_MANAGE'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->pull_m->renderShortcode();
	}
	public function renderCategory(){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->pull_m->renderCategory();
	}
	public function renderUpload($id,$uniqueid=NULL,$counts=NULL){
		if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->pull_m->renderUpload($id,$uniqueid,$counts);
	}
	public function renderKeys($data=NULL){
		if(!$this->vas->verifyPrivillages(array('USER_MANAGE','PULL'),$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if($data=='search'){
			if(!$this->input->get('state') && !$this->input->get('keyname') && !$this->input->get('shortcode') && !$this->input->get('category'))
				die('Enter At-least one feild');

		}
		$this->pull_m->renderKeys(array(
										'privileges' => $this->userPrivileges,
										'type'=>($data==NULL)?'none':$data,
										'state'=>(!$this->input->get('state') || $this->input->get('state')=='none')?NULL:$this->input->get('state'),
										'keyname'=>(!$this->input->get('keyname'))?NULL:strtolower($this->input->get('keyname')),
										'shortcode'=>(!$this->input->get('shortcode') || $this->input->get('shortcode')=='none')?NULL:$this->input->get('shortcode'),
										'category'=>(!$this->input->get('category') || $this->input->get('category')=='none')?NULL:$this->input->get('category'),
									));
	}

	public function renderKeyTree(){
		if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->pull_m->renderKeyTree();
	}
	public function renderScheme(){
		if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->pull_m->renderScheme();
	}
	public function renderTreeList($type,$err=NULL){
		$this->pull_m->renderTreeList($type,$this->userPrivileges,$err);

	}
	public function renderFeedback($id,$from=NULL,$till=NULL){
		$this->pull_m->renderFeedback($id,$from,$till);
	}
	public function getFeedbackList(){
		$this->pull_m->getFeedbackList($this->session->userdata('userId'),$this->userPrivileges);
	}
	public function renderError($id,$type,$from=NULL,$till=NULL){
		$this->pull_m->renderError($id,$type,$from,$till);
	}
	/*********all pull render functions end*************/
	public function shortcode($type , $id = NULL){

		if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}

		if(sizeof($this->input->post())==1 && $type == 'edit') die('** No data found for update');
		if($this->input->post('shortcode')  || $type=='new') $this->form_validation->set_rules('shortcode','Shortcode', 'required|numeric');
		if($this->input->post('description')|| $type=='new') $this->form_validation->set_rules('description','Description','required|max_length[200]');

		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }

		$res = $this->pull_m->shortcode(array(
									'fld_chr_name'=>($this->input->post('shortcode'))?strtolower($this->input->post('shortcode')):NULL,
									'fld_chr_description'=>($this->input->post('description'))?strtolower($this->input->post('description')):NULL,
									),$type,$id);
		if($res === TRUE ){	echo 'sucess';	}
		else{ echo $res; }

	}
	public function category($type , $id = NULL){

		if($this->session->userdata('userId')!=1){ die('**Warning:Not Sufficient Privileges');}

		if(sizeof($this->input->post())==1 && $type == 'edit') die('** No data found for update');
		if($this->input->post('category')  || $type=='new') $this->form_validation->set_rules('category','Category', 'required|alpha|max_length[20]');
		if($this->input->post('description')|| $type=='new') $this->form_validation->set_rules('description','Description','required|max_length[200]');

		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }

		$res = $this->pull_m->category(array(
									'category'=>($this->input->post('category'))?strtolower($this->input->post('category')):NULL,
									'description'=>($this->input->post('description'))?strtolower($this->input->post('description')):NULL,
									'upload_data'=>($this->input->post('upload')=='')?2:1
									),$type,$id);
		if($res === TRUE ){	echo 'sucess';	}
		else{ echo $res; }

	}

	public function assignShortcode(){

		$this->form_validation->set_rules('assignType','Assign Type', 'required|alpha');
		$this->form_validation->set_rules('shortcode','Shortcode List', 'required|numeric');
		$this->form_validation->set_rules('assignTo','User Selection', 'required|numeric');
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }

		$assignType = $this->input->post('assignType');
		$assignTo = $this->input->post('assignTo');
		$shortcode = $this->input->post('shortcode');
		if(trim($assignType)=='dedicated'){
			if(!$this->vas->hasPrivileges('pullroute',$assignTo)){
				die('**Error : To assign dedicated shortcode the user must have pullroute feature');
			}
		}

		$res = $this->pull_m->assignShortcode($assignTo,$shortcode,$assignType );
		die(($res===TRUE)?'sucess':$res);
	}
	public function removeShortcode(){
		$this->form_validation->set_rules('assignType','Assign Type', 'required|alpha');
		$this->form_validation->set_rules('shortcode','Shortcode List', 'required|numeric');
		$this->form_validation->set_rules('assignTo','User Selection', 'required|numeric');
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }

		$assignType = $this->input->post('assignType');
		$assignTo = $this->input->post('assignTo');
		$shortcode = $this->input->post('shortcode');

		$res = $this->pull_m->removeShortcode($assignTo,$shortcode,$assignType );
		die(($res===TRUE)?'sucess':$res);
	}

	public function keys($type=NULL){
		if(!$this->vas->verifyPrivillages('PULL',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if($this->input->post('category')=='none')die('Category field is required');
		if($this->input->post('shortcode')=='none')die('Shortcode field is required');

		$this->form_validation->set_rules('mainkey','Main key', 'required|alpha_numeric|max_length[25]');
		$this->form_validation->set_rules('disable','Dissable Message', 'required|max_length[400]');
		if ($this->form_validation->run() == FALSE){ die(validation_errors()); }

		$cate = $this->curd_m->getData('category',array('fld_int_id'=>$this->input->post('category') ),'object');
		if($cate===FALSE)die('fail');
		if($cate[0]->category=='fb'){
			if(!$this->input->post('sucess')) die('Sucess Message Feild Required');
			//if(strlen($this->input->post('sucess')) >160)echo ('Sucess Message feild cannot exceed 160 characters in length');
		}
		if($cate[0]->category!='fb' && $cate[0]->category!='ptp'){
			if(!$this->input->post('fail')) die('Fail/Help Message Feild Required');
			//if(strlen($this->input->post('sucess')) >160)echo ('Sucess Message feild cannot exceed 160 characters in length');
		}

		$rec = $this->curd_m->get_search('SELECT fld_int_id FROM pull_keys WHERE keys_name="'.strtolower($this->input->post('mainkey')).'" AND fld_shortcode_id= '.$this->input->post('shortcode'),'object');
		if($rec !==NULL) die('Key Already Exist');

		$mainkey = $this->curd_m->get_insert('pull_keys',array(
											'fld_user_id'=>$this->session->userdata('userId'),
											'fld_shortcode_id'=>$this->input->post('shortcode'),
											'fld_category_id'=>$this->input->post('category'),
											'keys_name'=>strtolower($this->input->post('mainkey')),
											'main_keys_id'=>0,
											'sucess_message'=>(!$this->input->post('sucess'))?'none':$this->input->post('sucess') ,
											'disable_message'=>$this->input->post('disable'),
											'state'=>9,
											'fld_addbook_id'=>0,
											'fld_temp_id'=>0,
											'fail_message'=>(!$this->input->post('fail'))?'none':$this->input->post('fail') ,
											'date'=>time(),
										));
		if($mainkey===FALSE) die('Error Found Unable to add new key');
		$subkeyArr = array();
		if($cate[0]->category=='vp' || $cate[0]->category=='ptp' ){
			$subkey = explode(',',$this->input->post('subkeys'));
			if($cate[0]->category=='ptp'){
				foreach($subkey as $val){
					$subk = explode('_',$val);
					$subkeyArr[] = array(
										'fld_user_id'=>$this->session->userdata('userId'),
										'fld_shortcode_id'=>0,
										'fld_category_id'=>0,
										'keys_name'=>strtolower($subk[0]),
										'main_keys_id'=>$mainkey,
										'sucess_message'=>$subk[3],
										'disable_message'=>'none',
										'fld_addbook_id'=>$subk[1],
										'fld_temp_id'=>$subk[2],
										'date'=>time(),
									);
				}
			}
			elseif($cate[0]->category=='vp'){
				foreach($subkey as $val){
					$subk = explode('_',$val);
					$subkeyArr[] = array(
										'fld_user_id'=>$this->session->userdata('userId'),
										'fld_shortcode_id'=>0,
										'fld_category_id'=>0,
										'keys_name'=>strtolower($subk[0]) ,
										'main_keys_id'=>$mainkey,
										'sucess_message'=>$subk[1],
										'disable_message'=>'none',
										'fld_addbook_id'=>0,
										'fld_temp_id'=>0,
										'date'=>time(),
									);
				}
			}
			$mainkey = $this->curd_m->get_insert('pull_keys',$subkeyArr,'batch');
		}
		$this->common_m->setNotice('key','add',$this->session->userdata('reseller'),$this->session->userdata('userId'),$this->userPrivileges,strtolower($this->input->post('mainkey')));
		die('sucess');
	}
	public function operateKeys($type,$id){
		$res = $this->pull_m->operateKeys($type,$id,$this->userPrivileges);
		die($res);
	}
	public function scheme(){
		$this->form_validation->set_rules('name','Scheme Name', 'required|max_length[40]');
		$this->form_validation->set_rules('scheme','Scheme ', 'required');
		$this->form_validation->set_rules('details','Message ', 'required');
		if ($this->form_validation->run() == FALSE){ die(validation_errors()); }
		$res = $this->pull_m->scheme(array(
										'scheme_name'=>$this->input->post('name'),
										'fld_user_id'=>$this->session->userdata('userId'),
										'scheme'=>$this->input->post('scheme'),
										'detail'=>$this->input->post('details'),
										'date'=>time(),
									));
		die($res);
	}
	public function uploadDb(){
		$data = ($this->input->post('data'))?json_decode($this->input->post('data')):NULL;
		$keyId = ($this->input->post('keyId'))?($this->input->post('keyId')):NULL;
		if($keyId===NULL || $data == NULL) die('error');
		$insertArr = array();
		foreach($data as $val){
			$count = $this->common_m->count_message(1,$val->m);
			$insertArr[] = array(
							'fld_int_id'=>uniqid(),
							'key_id'=>trim($keyId),
							'message'=>$val->m,
							'identity'=>$val->i,
							'count'=>$count['msg_len']
							);
		}
		//var_dump($insertArr);
		$insertArr = array_chunk($insertArr,40);

		//die();
		$affectedRow = 0;
		foreach($insertArr as $arr){
			if($this->db->insert_batch('upload_data', $arr) ) $affectedRow = $affectedRow + (int)$this->db->affected_rows();
			else die('error');
		}
		die((string)$affectedRow);
	}
	public function getPullReport($type, $id,$form,$till){
		$res = $this->pull_m->getPullReport($type, $id,$form,$till, $this->userPrivileges);
		if($res !== FALSE){
			echo json_encode($res);
		}
		else echo 'fail';
	}
	public function truncateUpload($id){
		if(!$this->vas->verifyPrivillages('UPLOAD_DATA',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		if($this->curd_m->getData('upload_data',array('key_id'=>$id))===FALSE) die('Empty No Data found');
		die( ($this->curd_m->get_delete('upload_data',array('key_id'=>array($id) ))===TRUE)?'sucess':'**Error : Unable to Truncate' );
	}
	///////////////////////end of classs
}
