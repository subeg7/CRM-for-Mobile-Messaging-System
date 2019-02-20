<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SysManage_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('gname')!== 'admin'){
			die(show_404());
		}
		$this->load->model(array('sysManage_m','curd_m','common_m'));
	}
	public function sysView($item, $id=NULL){
		if($item=='addCountry'){
			$this->load->view('sysManage/addCountry');
		}
		elseif($item == 'editCountry'){
			if($id == NULL ){ die("** No. Country selected for edit");}
			$this->data['country'] = $this->curd_m->getData('country',$id);
			$this->load->view('sysManage/editCountry',$this->data);
		}
		elseif($item == 'addOperator'){
			$this->data['country'] = $this->curd_m->getData('country',NULL,'object');
			$this->load->view('sysManage/addOperator',$this->data);
		}
		elseif($item == 'addFeature'){
			$this->load->view('sysManage/addFeature');
		}
		elseif($item == 'editOperator'){
			$this->data['country'] = $this->curd_m->getData('country',NULL,'object');
			$this->data['operator'] = $this->curd_m->getData('operator',$id);
			$this->load->view('sysManage/editOperator',$this->data);
		}
		elseif($item == 'editFeature'){
			$this->data['feature'] = $this->curd_m->getData('feature',$id);
			$this->load->view('sysManage/editFeature',$this->data);
		}
		elseif($item == 'addPrefix'){
			$this->data['operator'] = $this->curd_m->getData('operator',NULL,'object');
			$this->load->view('sysManage/addPrefix',$this->data);
		}
		elseif($item == 'editPrefix'){
			$this->data['operator'] = $this->curd_m->getData('operator',NULL,'object');
			$this->data['prefix'] = $this->curd_m->getData('prefix',$id);
			$this->load->view('sysManage/editPrefix',$this->data);
		}
		elseif($item == 'blockNumber'){
			$this->data['country'] = $this->curd_m->getData('country',NULL,'object');
			$this->load->view('sysManage/blockNumber',$this->data);
		}
		
		elseif($item == 'addGroup' || $item == 'editGroup'){
			$this->config->load('easy_priv');
			$this->data['privileges'] = $this->config->item('PRIVILEGES');
			if( $item == 'editGroup'){ 
				$this->data['group'] = $this->curd_m->getData('groups',$id);
				$this->data['underAdmin'] = $this->curd_m->getData('sub_group',array('fld_group_id'=>1,'fld_sub_group_id'=>$id));
				$priv = $this->curd_m->getData('group_privileges',array('fld_group_id'=>$id));
				$this->data['subgroup'] = $this->curd_m->get_search('SELECT g.id AS id, g.name AS name,g.description AS description FROM groups g INNER JOIN sub_group s WHERE s.fld_sub_group_id = g.id AND s.fld_group_id='.$id,'object');
				foreach($priv as $val){
					$this->data['priv'][] = $val['fld_privileges'];
				}
			}
			($item=='addGroup')?$this->load->view('sysManage/addGroup',$this->data):$this->load->view('sysManage/editGroup',$this->data);
		}
	}
	/*************** all delete functions ***************************************/
	public function deleteItem($item){
		$table = NULL;
		$id = ($this->input->post('id'))?explode(',',$this->input->post('id')):die('** No Item found for deletion');
		
		if($item=='country'){ 
			if($this->curd_m->getData('users_country',array('fld_country_id'=>$id[0]) ) !== FALSE)
				die("Unable to Delete , It has been assigned to another units");
			$table = 'country'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='operator'){
			if($this->curd_m->getData('prefix',array('operator_id'=>$id[0]) ) !== FALSE)
				die("Unable to Delete , It has been assigned to another units");
			$table = 'operator'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='feature'){
			if($this->curd_m->getData('user_feature',array('fld_feature_id'=>$id[0]) ) !== FALSE)
				die("Unable to Delete , It has been assigned to users");
			$table = 'feature'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='prefix'){
			
			$table = 'prefix'; $data = array('fld_int_id'=>$id);
		}
		elseif($item=='group'){
			if($this->curd_m->getData('users_groups',array('group_id'=>$id[0]) ) !== FALSE)
				die("Unable to Delete , It has been assigned to another units");
			$table = 'groups'; $data = array('id'=>$id);
			$this->curd_m->get_delete('group_privileges',array('fld_group_id'=>$id));
			$this->curd_m->get_delete('sub_group',array('fld_group_id'=>$id));
			$this->curd_m->get_delete('sub_group',array('fld_sub_group_id'=>$id));
		}
		if($table!=NULL){
			die( ($this->curd_m->get_delete($table,$data))?'sucess':'** Error In item deletion');
		}
		die('** Invalid Query');
	}
	/***************rendering country***************************************/
	public function renderCountry(){
		$this->sysManage_m->renderCountry();
	}
	public function renderOperator(){
		$this->sysManage_m->renderOperator();
	}
	public function renderPrefix(){
		$this->sysManage_m->renderPrefix();
	}
	public function renderGroup(){
		$this->sysManage_m->renderGroup();
	}
	public function renderFeature(){
		$this->sysManage_m->renderFeature();
	}
	
	/***************add or edit country***************************************/
	public function blockedNumber(){
		
		$this->form_validation->set_rules('numbers', 'Cell Numbers', 'required');
		$this->form_validation->set_rules('countrySelected', 'Country', 'required');
		if ($this->form_validation->run() == FALSE){ die( validation_errors());}
		
		$validateNumbers = $this->common_m->verifyNumber(explode('_',$this->input->post('numbers')),NULL,$this->input->post('countrySelected'));
		if( isset($validateNumbers['fault']) || isset($validateNumbers['repeat'])) die('Fault number Found');
		die(var_dump($validateNumbers));
		$res = $this->sysManage_m->blockedNumber();
		if($res === TRUE ){	die('sucess');	}
		else{ die($res); }
	}
	public function country($type , $id = NULL){
		if(sizeof($this->input->post())==1 && $type == 'edit') die('** No data found for update');
		if($this->input->post('name')   || $type=='new') $this->form_validation->set_rules('name', 'Country Name', 'required');
		if($this->input->post('acronym')|| $type=='new') $this->form_validation->set_rules('acronym', 'Acronym', 'required|alpha');
		if($this->input->post('code')   || $type=='new') $this->form_validation->set_rules('code', 'Country code', 'required|numeric');
	  
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }
		$res = $this->sysManage_m->country(array(
									'fld_chr_name__Country name'=>($this->input->post('name'))?strtolower($this->input->post('name')):NULL,
									'fld_chr_acro__Acronym'=>($this->input->post('acronym'))?strtolower($this->input->post('acronym')):NULL,
									'fld_chr_code__Country code'=>($this->input->post('code'))?$this->input->post('code'):NULL,
									),$type,$id);
		if($res === TRUE ){	echo 'sucess';	}
		else{ echo $res; }
		
	}
	public function operator($type , $id = NULL){
		if(sizeof($this->input->post())==1 && $type == 'edit') die('** No data found for update');
		if($this->input->post('acronym')    || $type=='new') $this->form_validation->set_rules('acronym','Operator acronym', 'required|alpha');
		if($this->input->post('description')|| $type=='new') $this->form_validation->set_rules('description','Description','required');
		if($this->input->post('country')    || $type=='new') $this->form_validation->set_rules('country', 'Country', 'required|numeric');
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }
		$res = $this->sysManage_m->operator(array(
									'description'=>($this->input->post('description'))?strtolower($this->input->post('description')):NULL,
									'acronym'=>($this->input->post('acronym'))?strtolower($this->input->post('acronym')):NULL,
									'country_id'=>($this->input->post('country'))?$this->input->post('country'):NULL,
									),$type,$id);
		if($res === TRUE ){	echo 'sucess';	}
		else{ echo $res; }
		
	}
	public function feature($type , $id = NULL){
		if(sizeof($this->input->post())==1 && $type == 'edit') die('** No data found for update');
		if($this->input->post('feature') || $type=='new') $this->form_validation->set_rules('feature','Feature', 'required|max_length[50]');
		if($this->input->post('description')|| $type=='new') $this->form_validation->set_rules('description','Description','required|max_length[300]');
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }
		$res = $this->sysManage_m->feature(array(
									'fld_chr_desc'=>($this->input->post('description'))?strtolower($this->input->post('description')):NULL,
									'fld_chr_feature'=>($this->input->post('feature'))?strtolower($this->input->post('feature')):NULL,
									),$type,$id);
		if($res === TRUE ){	echo 'sucess';	}
		else{ echo $res; }
		
	}
	
	
	
	
	public function prefix($type , $id = NULL){
		
		if(sizeof($this->input->post())==1 && $type == 'edit') die('** No data found for update');
		if($this->input->post('prefix')    || $type=='new') $this->form_validation->set_rules('prefix','Operator prefix', 'required|numeric');
		if($this->input->post('operator')|| $type=='new') $this->form_validation->set_rules('operator','Operator','required');
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }
		$res = $this->sysManage_m->prefix(array(
									'prefix'=>($this->input->post('prefix'))?strtolower($this->input->post('prefix')):NULL,
									'operator_id'=>($this->input->post('operator'))?strtolower($this->input->post('operator')):NULL,
									),$type,$id);
		if($res === TRUE ){	echo 'sucess';	}
		else{ echo $res; }
		
	}
	public function group($type , $id=NULL ){
		if(sizeof($this->input->post())==1 && $type == 'edit') die('** No data found for update');
		if($this->input->post('name')    || $type=='new') $this->form_validation->set_rules('name','Group Name', 'required');
		if($this->input->post('description')|| $type=='new') $this->form_validation->set_rules('description','Description','required');
		if($this->input->post('privileges') || $type=='new') $this->form_validation->set_rules('privileges','Group Privileges', 'required');
		if ($this->form_validation->run() == FALSE){ echo validation_errors();	return; }
		
		$res = $this->sysManage_m->group(array(
									'name'=>($this->input->post('name'))?strtolower($this->input->post('name')):NULL,
									'description'=>($this->input->post('description'))?strtolower($this->input->post('description')):NULL,
									'privileges'=>($this->input->post('privileges'))?explode(',',$this->input->post('privileges')):NULL,
									'subgroup'=>($this->input->post('subgroup'))?explode(',',$this->input->post('subgroup')):NULL,
									'admin'=>($this->input->post('admin'))?$this->input->post('admin'):NULL,
									),$type,$id);
		if($res === TRUE ){	echo 'sucess';	}
		else{ echo $res; }
	}
	
	public function getGroup( $id=NULL ){ 
		$agentGrps = $this->curd_m->get_search('SELECT distinct fld_group_id FROM group_privileges p WHERE p.fld_privileges="USER_MANAGE"','object');
		if($agentGrps == NULL){
			echo 'fail'; 
		}else{
			$grps = array();
			foreach($agentGrps as $row){ $grps[] = $row->fld_group_id;	}
			$res = $this->curd_m->get_search('SELECT * FROM groups WHERE id NOT IN ('.implode(',',$grps).') ');
			if($res!==FALSE) echo json_encode($res);
			else echo 'fail'; 
		}
		/*if($id == NULL) $res = $this->curd_m->get_search('SELECT g.name AS name,g.description AS description FROM groups g INNER JOIN group_privileges p WHERE g.id!=1 AND g.id=p.fld_group_id AND p.fld_privileges NOT IN( "USER_MANAGE")');
		else $res = $this->curd_m->get_search('SELECT * FROM groups WHERE id NOT IN (1,'.$id.') AND'); 
		if($res!==FALSE) echo json_encode($res);
		else echo 'fail';*/
	}
	
	public function getOperatorBycountry($countryId){
		$res = $this->curd_m->getData('operator',array('country_id'=>$countryId),'object');
		echo ($res==NULL)?'none':json_encode($res);

	}
	
	public function systemSettings($type){
		$arr = array(
					'enable_login'	=>array('fld_type'=>'login','fld_val'=>1),
					'disable_login'	=>array('fld_type'=>'login','fld_val'=>0),
					'enable_que'	=>array('fld_type'=>'que_job','fld_val'=>1),
					'disable_que'	=>array('fld_type'=>'que_job','fld_val'=>0),
					'enable_push'	=>array('fld_type'=>'push_api','fld_val'=>1),
					'disable_push'	=>array('fld_type'=>'push_api','fld_val'=>0),
				);
		if(!isset($arr[$type])) die('Invalid Query');
		if($this->curd_m->get_update('system_flag',$arr[$type])){
			if($type=='disable_push'){
				$this->curd_m->get_update('system_flag',$arr['disable_que']);
			}
			die('sucess');
		}
		else{ die('Unalbe to change system Settings');}
	}
	
	
	/// end of  controller
}
