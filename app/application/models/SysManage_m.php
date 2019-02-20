<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class SysManage_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('curd_m');
		$this->load->library('dhxload','vas');
	}
	
	/****** grid render functions start ****************/
	public function renderCountry(){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'rows'=>"fld_int_id,fld_chr_name,fld_chr_acro,fld_chr_code",
									'callback'=>array($this,'countryCalback'),
									'query'=>"SELECT * FROM country ORDER BY fld_int_id ASC",
								   ));
	}
	
	public function countryCalback($item){
		$item->set_value('fld_chr_name',ucwords($item->get_value('fld_chr_name')));
		$item->set_value('fld_chr_acro',strtoupper($item->get_value('fld_chr_acro')));	
	}
	public function renderOperator(){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'operatorCalback'),
									'query'=>"SELECT o.fld_int_id AS id, o.acronym AS acronym, o.description AS description,c.fld_chr_name AS name FROM operator o INNER JOIN country c WHERE c.fld_int_id = o.country_id ORDER BY o.fld_int_id ASC",
									'rows'=>"id,acronym,description,name",
								   ));
	}
	
	public function operatorCalback($item){
		$item->set_value('description',ucwords($item->get_value('description')));
		$item->set_value('name',ucwords($item->get_value('name')));
		$item->set_value('acronym',strtoupper($item->get_value('acronym')));	
	}
	public function renderFeature(){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'featureCalback'),
									'query'=>"SELECT * FROM feature",
									'rows'=>"fld_int_id,feature,description",
								   ));
	}
	
	public function featureCalback($item){
		$item->set_value('description',ucwords($item->get_value('fld_chr_desc')));
		$item->set_value('feature',ucwords($item->get_value('fld_chr_feature')));
	}
	
	
	public function renderPrefix(){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'prefixCalback'),
									'query'=>"SELECT p.fld_int_id AS id,p.prefix AS prefix, o.acronym AS acronym, o.description AS description FROM prefix p INNER JOIN operator o WHERE p.operator_id = o.fld_int_id ORDER BY p.fld_int_id ASC",
									'rows'=>"id,prefix,acronym,description",
								   ));
	}
	public function prefixCalback($item){
		$item->set_value('description',ucwords($item->get_value('description')));
		$item->set_value('acronym',strtoupper($item->get_value('acronym')));	
	}
	public function renderGroup(){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'groupCalback'),
									'query'=>"SELECT * FROM groups g WHERE id!=1 ORDER BY id ASC",
									'rows'=>"id,name,description,privilegs",
								   ));
	}
	public function groupCalback($item){

		$res = $this->curd_m->get_search('SELECT fld_privileges FROM group_privileges WHERE fld_group_id='.$item->get_value('id'),'object' );
		if($res !== NULL){
			foreach($res as $val){
				$priv[]= $val->fld_privileges;
			}
			$priv = '<p style="font-size:10px;line-height: 14px;"><span class="red"> [ </span>'.implode('<span class="red"> ] [ </span>',$priv).'<span class="red"> ] </span></p>';
			$item->set_value('privilegs',$priv);
		}
		else{
			$item->set_value('privilegs','No privileges Assign');
		}
		
	}
	public function blockedNumber($arr){
		
	}
	/****** grid render functions ends ****************/
	
	public function country($data, $type ,$id=NULL){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update
		foreach($data as $key=>$val){
				if($val !==NULL){
					$key=explode('__',$key);
					$arr[$key[0]] = $val;
				}
				else{  unset($data[$key]);	}
		}
		$res = $this->curd_m->checkRecur('country',$data);
		if($type=='new' && $res === TRUE){
			return ($this->curd_m->get_insert('country',$arr))?TRUE : '** Error: Adding Country fail';
		}
		elseif($type=='edit' && $res === TRUE){
			if($id==NULL || $id == FALSE) return '** No item found to edit ';
			return ($this->curd_m->get_update('country',$arr))?TRUE:'** Update error ';
		}
		else return $res;
	}
	
	public function operator($data, $type ,$id=NULL){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update
		
		foreach($data as $key=>$val){
			if($val !==NULL){	$arr[$key] = $val;	}
			else{  unset($data[$key]);	}
			//die(var_dump($arr));
		}
		if(isset($data['acronym'])){
			$res = $this->curd_m->checkRecur('operator',array('acronym__Operator'=>$data['acronym']));
		}
		else{ $res = TRUE;	
		}
		if($type=='new' && $res === TRUE){
			return ($this->curd_m->get_insert('operator',$arr)!==FALSE)?TRUE:FALSE;
		}
		elseif($type=='edit' && $res === TRUE){
			if($id==NULL || $id == FALSE) return '** No item found to edit ';
			return ($this->curd_m->get_update('operator',$arr))?TRUE:'** Update error ';
		}
		else return $res;
	}
	public function feature($data, $type ,$id=NULL){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update
		
		foreach($data as $key=>$val){
			if($val !==NULL){	$arr[$key] = $val;	}
			else{  unset($data[$key]);	}
		}
		if(isset($data['fld_chr_feature'])){
			$res = $this->curd_m->checkRecur('feature',array('fld_chr_feature__Feature'=>$data['fld_chr_feature']));
		}
		else $res = TRUE;
		if($type=='new' && $res === TRUE){
			return ($this->curd_m->get_insert('feature',$arr)!==FALSE)?TRUE:FALSE;
		}
		elseif($type=='edit' && $res === TRUE){
			if($id==NULL || $id == FALSE) return '** No item found to edit ';
			return ($this->curd_m->get_update('feature',$arr))?TRUE:'** Update error ';
		}
		else return $res;
	}
	
	
	
	public function prefix($data, $type ,$id=NULL){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update
		$prefixData = NULL;
		foreach($data as $key=>$val){
			if($val !==NULL){	$arr[$key] = $val;	}
			else{  unset($data[$key]);	}
		}
		$res = NULL;
		if($type=='edit'){
			$prefixData = $this->curd_m->get_search('SELECT * FROM prefix WHERE fld_int_id='.$id,'object');
			if(!isset($data['operator_id']) && isset($data['prefix'])){
				$res = $this->curd_m->get_search('SELECT prefix FROM prefix  WHERE operator_id = '.$prefixData[0]->operator_id.' AND prefix="'.$data['prefix'].'"');
			}
			elseif(!isset($data['prefix']) && isset($data['operator_id'])){
				$res = $this->curd_m->get_search('SELECT prefix FROM prefix  WHERE operator_id = '.$data['operator_id'].' AND prefix="'.$prefixData[0]->prefix.'"');
			}		
		}
		else{
			$res = $this->curd_m->get_search('SELECT prefix FROM prefix  WHERE operator_id = '.$data['operator_id'].' AND prefix="'.$data['prefix'].'"');
		}
		
	//	$res = $this->curd_m->get_search('SELECT * FROM prefix p INNER JOIN operator o WHERE p.prefix="'.$data['prefix'].'" AND p.operator_id=o.fld_int_id AND o.country_id='.$res[0]['country_id']);
		
		
		if($type=='new' && $res === NULL){
			//$this->common_m->insert_notice('Prefix [ '.$arr['prefix'].' ] added','prefix',$this->session->userdata('userId'));	
			return ($this->curd_m->get_insert('prefix',$arr)!==FALSE)?TRUE:FALSE;
		}
		elseif($type=='edit' && $res === NULL){
			if($id==NULL || $id == FALSE) return '** No item found to edit  ';
			$data = $this->curd_m->get_search('SELECT * FROM prefix WHERE fld_int_id='.$id,'object');
			//$this->common_m->insert_notice('Prefix [ '.$data[0]->prefix.' ] modified' ,'prefix',$this->session->userdata('userId'));	
			return ($this->curd_m->get_update('prefix',$arr))?TRUE:'** Update error ';
		}
		else die('<p>Operator prefix already Exist</p>');
	}
	
	public function group($data, $type ,$id=NULL){
		$arr = ( $type == 'new')? array() : array('id'=>$id);
		if(isset($data['privileges'])){ 
			if(in_array('USER_MANAGE',$data['privileges']) && $data['subgroup']==NULL) return 'No Sub Group found';
			$close = $this->vas->checkCLosePrivileges($data['privileges']);
			if($close !== TRUE) return $close;
		}
		// check if other assign groups contains user manage privileges or not , to maintain only one level hiearachy 
		if($data['subgroup']!=NULL){
			foreach($data['subgroup'] as $val){				
				$grpPrivs = $this->vas->getGroupPrivileges($val);
				if(in_array('USER_MANAGE',$grpPrivs)) return "Group Can't create other groups which has USER_MANAGE privilege";
			}
		}
		
		// check if group already exist
		if($data['name']!=NULL){
			$res = $this->curd_m->checkRecur('groups',array('name__Group Name'=>$data['name']));
			if($res !== TRUE) return $res;
		}
		
		foreach($data as $key=>$val){	
			if($val !==NULL && $key!='privileges' && $key!='admin'){	
				$arr[$key] = $val; 
			} 
		}
		// for new group
		if($type=='new'){
			$id = $this->curd_m->get_insert('groups',array('name'=>$data['name'],'description'=>$data['description']));
			if($id== FALSE) return '*** Unalbe to Create Group';
			if($data['admin']!== NULL ){
				$this->curd_m->get_insert('sub_group',array('fld_group_id'=>1,'fld_sub_group_id'=>$id));
			}
			
		}
		// for editing group
		elseif($type=='edit' ){
			if($id==NULL || $id == '') return '** No item found to edit ';
			// if name or to descrirption edit
			if((isset($arr['name']) || isset($arr['description'])) ){
				$edit_arr = array();
				foreach($arr as $key=>$row){
					if($key!=='subgroup')$edit_arr[$key]=$row;
				}
				if(!$this->curd_m->get_update('groups',$edit_arr)) return '** Update error ';
			}
			
			if($data['subgroup']!==NULL){
				$this->curd_m->get_delete('sub_group',array('fld_group_id'=> array($id)) );
			}
			if($data['admin']!== NULL){
				if($this->curd_m->get_search('SELECT * FROM sub_group WHERE fld_group_id=1 AND fld_sub_group_id='.$id,'object')==NULL){
					$this->curd_m->get_insert('sub_group',array('fld_group_id'=>1,'fld_sub_group_id'=>$id));
				}
			}
			else{
				$this->db->query('DELETE FROM sub_group WHERE fld_group_id=1 AND fld_sub_group_id='.$id);
			}
			if($data['privileges']!=NULL){
				$this->curd_m->get_delete('group_privileges',array('fld_group_id'=> array($id)) );
			}
		}
		// if privileges is set
		if($data['privileges']!=NULL){
			foreach($data['privileges'] as $val){
				$priv[] = array('fld_privileges'=>$val,'fld_group_id'=>$id,);
			}
			$this->curd_m->get_insert('group_privileges',$priv,'batch');
		}
		// if subgroup is set
		if($data['subgroup']!=NULL){
			foreach($data['subgroup'] as $val){		
				$subGrop[] = array('fld_group_id'=>$id,'fld_sub_group_id'=>$val);		
			}
			$this->curd_m->get_insert('sub_group',$subGrop,'batch');
		}
		return TRUE;
		
	}
	
	
	// model ends
}
