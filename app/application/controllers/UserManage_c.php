<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserManage_c extends ESY_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ion_auth'));
		$this->load->model(array('curd_m','common_m','userManage_m'));
		$this->userPrivileges = $this->vas->getUserPrivileges();
	
	}
	public function userView($type,$id=NULL){
		if($type=='addUsers'){ 
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$id = $this->session->userdata('groupId');
			$this->data['userType'] = $this->curd_m->get_search('SELECT g.name AS name , g.id AS id FROM sub_group s INNER JOIN groups g WHERE s.fld_group_id='.$id.' AND s.fld_sub_group_id = g.id','object');
			$country = ($this->session->userdata('userId')==1)?$this->curd_m->get_search('SELECT * FROM country','object'):$this->curd_m->get_search('SELECT * FROM country WHERE fld_int_id='.$this->session->userdata('countryId'),'object');
			$this->data['country'] = ($country==NULL)?'none':$country;
			$this->data['balanceType'] = strtolower($this->session->userdata('balanceType'));
			$this->data['isadmin'] = ($this->session->userdata('userId')==1)?'admin':'client'; 
			$this->load->view('userManage/addUsers',$this->data);
		}
		elseif($type=='detail'){
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$this->data['userDetail'] = $this->userManage_m->getUserDetail($id);
			$this->load->view('userManage/usersDetail',$this->data);
		}
		
		elseif($type=='privEdit'){
			if(!$this->common_m->isMyClinet($id)) 
				die("** Error : You don't have privileges to Modify privileges to other's client");
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			$group = $this->curd_m->get_search('SELECT group_id FROM users_groups WHERE user_id='.$id,'object');
			
			$this->data['groupPriv'] = $this->getGroupPrivileges($group[0]->group_id,'nonajax');
			$this->data['userPriv'] = $this->vas->getUserPrivileges($id);
			
			$this->data['userName'] = $this->curd_m->get_search('SELECT company FROM users WHERE id='.$id,'object');
			$this->load->view('userManage/editPrivileges',$this->data);
		}
		elseif( $type =='passwordReset'){
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			if(!$this->common_m->isMyClinet($id )) 
				die("** Error : You don't have privileges to add balance to other's client");
			$this->load->view('userManage/passwordReset');
		}
		elseif( $type =='addBalance'){
			$operator = 'none';
			if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
			if(!$this->common_m->isMyClinet($id )) 
				die("** Error : You don't have privileges to Add other's client balance");
			$res = $this->common_m->checkBalanceType($id );
			if(!$res ) $this->vas->expire_message('window','data',"** Error : Operation Fail");
			if(strtolower($res)== 'postpaid'){
				$this->vas->expire_message('window','data',"** Warning : This users has POSTPAID Balance Type");
			}
			elseif(strtolower($res)== 'single'){
				$this->data['balType']='single';
			}
			elseif(strtolower($res)== 'seperate'){
				$this->data['balType']='seperate';
				$operator=$this->common_m->getOperatorByCountry($id);
			}
			$this->data['operator'] = $operator;
			$priv = $this->vas->getUserPrivileges($id);
			$this->data['usermanage'] = (in_array('USER_MANAGE',$priv))?TRUE:FALSE;
			$this->load->view('userManage/addBalance',$this->data);
		}
		
	}
	/*public function checkRoute($id,$req = 'ajax'){
		if($req =='ajax') $this->common_m->checkRoute($id,$req);
		else return $this->common_m->checkRoute($id,$req);
		
	}
	public function checkRemote($id,$req = 'ajax'){
		if($req =='ajax') $this->common_m->checkRemote($id,$req);
		else return $this->common_m->checkRemote($id,$req);
		
	}*/
	public function assignFeature($type,$id,$userId){
		if($type == 'assign'){
			if($this->input->post('featureType')=='subbranding'){
				$this->form_validation->set_rules('domain_url', 'Sub Domain url', 'required|max_length[250]');
				$this->form_validation->set_rules('title', 'Title', 'required|max_length[100]');
				if ($this->form_validation->run() == FALSE){ echo validation_errors(); return; }
			}
			elseif($this->input->post('featureType')=='pullroute' && $this->input->post('userManagePriv')=='no'){
				$this->form_validation->set_rules('route_url', 'Route url', 'required|max_length[100]');
				if ($this->form_validation->run() == FALSE){ echo validation_errors(); return; }
			}		
			
			
			if($this->curd_m->getData('user_feature',array('fld_feature_id'=>$id,'fld_user_id'=>$userId) ) !== FALSE){
				die("Feature Assigned Already");
			}
			// assigining feature
			if($this->input->post('featureType')=='subbranding'){
				$res = $this->curd_m->get_insert('user_feature',array('fld_feature_id'=>$id,'fld_user_id'=>$userId,'extra_info'=>$this->input->post('domain_url'),'fld_chr_title'=>$this->input->post('title')  ));
			}
			else{
				$res = $this->curd_m->get_insert('user_feature',array('fld_feature_id'=>$id,'fld_user_id'=>$userId));
				$userPriv = $this->vas->getUserPrivileges($userId);
				if($this->input->post('featureType')=='pullroute' && $res!==FALSE && !in_array('USER_MANAGE',$userPriv )){
					$res = $this->curd_m->get_insert('user_route',array('fld_user_id'=>$userId,'fld_route_url'=>$this->input->post('route_url'),'nonce'=>uniqid('PULL',TRUE) ));
				}
				elseif($this->input->post('featureType')=='api' && $res!==FALSE && !in_array('USER_MANAGE',$userPriv )){
					$res = $this->curd_m->get_insert('remote_access',array('fld_user_id'=>$userId,'fld_api_key'=> uniqid("EASY",TRUE) ) );
				}
				
			}
			
			die(( $res !== FALSE)?'sucess':'** Error : Feature Assign Fail');
		}
		elseif($type == 'remove'){
			$res = $this->curd_m->get_search('SELECT f.fld_chr_feature AS fname FROM feature f INNER JOIN user_feature u WHERE u.fld_user_id='.$userId.' AND u.fld_feature_id = f.fld_int_id AND f.fld_int_id ='.$id,'object');
			if($res ==FALSE) die('** Invalid operation');
			
			if($res[0]->fname=='pullroute'){
				$this->db->where('fld_user_id',$userId);
				$this->db->delete('user_route'); 
			}
			elseif($res[0]->fname=='api'){
				$this->db->where('fld_user_id',$userId);
				$this->db->delete('remote_access'); 
			}
			$this->db->where('fld_feature_id',$id);
			$this->db->where('fld_user_id',$userId);
			$this->db->delete('user_feature'); 
			die(( $this->db->affected_rows() >0)?'sucess':'** Error : Feature Remove Fail');
		}
	}
	public function getGroupPrivileges($id,$req = 'ajax'){
		$priv = $this->vas->getGroupPrivileges($id);
		if($priv !== FALSE){
			$this->data = array();
			$this->config->load('easy_priv');
			$userManage= $this->config->item('PRIVILEGES');
			foreach($userManage as $key=>$val){
				if(in_array($key,$priv))	$arrData['privileges'][$key] = $val;
			}
			
			if($req == 'ajax'){
				die(json_encode($arrData));
			} else return $arrData;
		}
		die('none');
	}
	function renderUser($type,$search=NULL){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$data = array();
		if($data=='download'){
			if($this->input->get('id')){	
				$res = $this->curd_m->get_search('SELECT * FROM download_code WHERE fld_code="'.$this->input->get('id').'"','object');
				if($res===FALSE) die('**Error : Invalid Request');
				if($res[0]->expireTime < time()) die('**Error : Invalid Request');
			}else{
				die('**Error : Invalid Request');
			}
		}
		$data = array(
					'userid' =>($this->input->get('userid'))?$this->input->get('userid'):'none',
					'name' 	=>($this->input->get('name'))?$this->input->get('name'):'none',
					'reseller' =>($this->input->get('reseller'))?$this->input->get('reseller'):'none',
					'group'=>($this->input->get('group'))?$this->input->get('group'):'none',
					'state'	=>($this->input->get('state'))?$this->input->get('state'):'none',
					'feature'	=>($this->input->get('feature'))?$this->input->get('feature'):'none',
				);
			
		
		$this->userManage_m->renderUser($type,$this->session->userdata('userId'),$search,$data);
	}
	function addUser(){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		
		$this->form_validation->set_rules('org_name', 'Organization Name', 'required|max_length[100]');
		$this->form_validation->set_rules('org_phone', 'Organization Phone Number', 'required|numeric');
		$this->form_validation->set_rules('person_name', 'Contact Person Full Name', 'required|max_length[80]');
		
		$this->form_validation->set_rules('person_phone', 'Contact Person Number', 'required|numeric');
		$this->form_validation->set_rules('address', 'Full Address', 'required|max_length[200]');
		$this->form_validation->set_rules('username', 'User Name', 'required|max_length[100]');
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[20]|min_length[8]');
		$this->form_validation->set_rules('email', 'Email', 'required|max_length[100]');
		$this->form_validation->set_rules('country', 'Country', 'required');
		$this->form_validation->set_rules('group', 'User Type', 'required');
		$this->form_validation->set_rules('balance', 'Balance Type', 'required|alpha');
		
		if ($this->form_validation->run() == FALSE){ echo validation_errors(); return; }
		
		
		$email    		= strtolower($this->input->post('email'));
        $identity 		= $this->input->post('username');
        $password 		= $this->input->post('password');
		$url 			= $this->input->post('url');
		$country 		= ($this->input->post('country')=='default')?$this->session->userdata('countryId'):$this->input->post('country');
		$asignPriv 		= explode(',',$this->input->post('privileges'));
		$agent 			= $this->session->userdata('userId');
		$group 			= $this->input->post('group');
		$userTranId     = (int)$this->userManage_m->getUserTransactionId();
		$balance		= strtoupper($this->input->post('balance'));
		
		$additional_data = array(
			'contact_person' 	=> $this->input->post('person_name'),
			'fld_reseller_id'  	=> $agent,
			'company'    		=> $this->input->post('org_name'),
			'phone'      		=> $this->input->post('org_phone'),
			'address'    		=> $this->input->post('address'),
			'contact_number'	=> $this->input->post('person_phone'),
			'fld_transaction_id'=> $userTranId,
			'fld_balance_type'	=> $balance,
		);
		
		if((int)$agent !== 1){
			if($this->session->userdata('balanceType')!=='POSTPAID'){
				$agentAppBal = $this->common_m->getBalance($agent,'appbal');
				if($agentAppBal > 0){
					if($this->common_m->updateBalance($agent,($agentAppBal-1),'appbal')===TRUE){
						$res_message = 'deduced : User ,'.$this->input->post('org_name').', Created ';
						$this->curd_m->get_insert('balance_transaction',array(
																	'fld_int_id'=>uniqid(),
																	'fld_user_id'=>$agent,
																	'fld_transaction_type'=>2,
																	'fld_transaction_descripition'=> $res_message ,
																	'fld_balance_type'=>'appbal',
																	'fld_amount'=>1,
																	'fld_balance_after_update'=>( (int)$agentAppBal-1 ),
																	'date'=>time(),
																));
					}
				}
				else die('** Warning : Not Sufficient Application Balance');
				
				////////////
			}
			
		}
		// regestering user
        $registerId = $this->ion_auth->register($identity, $password, $email, $additional_data,array($group ));
        if( $registerId ){
			
			//inserting country
		    if($this->curd_m->get_insert('users_country',array('fld_country_id'=>$country,'fld_user_id'=>$registerId ))===FALSE){
				$this->curd_m->undoEntry(array('users'=>'id '.$registerId));
				die('** Error : User Registration Fail');
			}
			//inserting privileges
			if($this->curd_m->get_insert('users_privileges',array('fld_user_id'=>$registerId,'fld_privileges'=>'default'))===FALSE){
				$this->curd_m->undoEntry(array('users'=>'id '.$registerId,'users_country'=>'fld_user_id '.$registerId));
				die('** Error : User Registration Fail');
			}
			echo 'sucess';
           
		}
		else{
			die($this->ion_auth->errors());
		}
		// end of add user function
	}
	
	public function manageUser($id,$type){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->userManage_m->manageUser($id,$type);
	}
	
	public function manageUserPrivileges($id){
		
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		$this->form_validation->set_rules('priv', 'Privileges Feild', 'required');
		if ($this->form_validation->run() == FALSE){ echo validation_errors(); return; }
		$res = $this->userManage_m->manageUserPrivileges($id,$this->input->post('priv'));
		die( ($res===TRUE)?'sucess':$res );
	}
	public function resetPassword(){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		
		$this->form_validation->set_rules('userid', 'User ID', 'required|numeric');
		$this->form_validation->set_rules('password', 'New Password', 'required|max_length[20]|min_length[8]');
		$this->form_validation->set_rules('conpassword', 'Conform Password', 'required|matches[password]');
		if ($this->form_validation->run() == FALSE){ echo validation_errors(); return; }
		if(!$this->common_m->isMyClinet($this->input->post('userid') )) 
				die("** Error : You don't have privileges to reset other's client password");
		$res = $this->userManage_m->resetPassword($this->input->post('userid'),$this->input->post('password'));
		die( ($res===TRUE)?'sucess':$res);
		
	}
	public function addBalance(){
		if(!$this->vas->verifyPrivillages('USER_MANAGE',$this->userPrivileges )){ die('**Warning:Not Sufficient Privileges');}
		
		$this->form_validation->set_rules('userid', 'User ID', 'required|numeric');
		$this->form_validation->set_rules('unit', 'SMS Unit', 'required|numeric');
		
		if ($this->form_validation->run() == FALSE){ echo validation_errors(); return; }
		
		$category = $this->common_m->checkBalanceType($this->input->post('userid') );
		if( strtolower($category) =='postpaid')die('Post- Paid balance User');
		if('seperate' == strtolower($category) ){
			if($this->input->post('operator') && $this->input->post('operator')=='none') die('Balance Type Feild is Required');
		}
		
		$balanceType = ($this->input->post('operator') !='appbal')?$category:'appbal';
		$operator = ($this->input->post('operator') && $this->input->post('operator')!='none')?($this->input->post('operator') =='appbal')?NULL:$this->input->post('operator'):NULL;
		$des = ($this->input->post('description'))?$this->input->post('description'):NULL;
		$res = $this->userManage_m->addBalance($this->input->post('userid'),$this->input->post('unit'),strtolower($balanceType),$operator,$des);
		die( ($res===TRUE)?'sucess':$res);
		
	}
	public function resetPasswordIndi(){
		
		$this->form_validation->set_rules('password', 'New Password', 'required|max_length[20]|min_length[8]');
		$this->form_validation->set_rules('conpassword', 'Conform Password', 'required|matches[password]');
		if ($this->form_validation->run() == FALSE){ echo validation_errors(); return; }
		$res = $this->userManage_m->resetPassword($this->session->userdata('userId'),$this->input->post('password'));
		die( ($res===TRUE)?'sucess':$res);
		
	}
	/************end of class***********/
}





