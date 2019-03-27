<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Vas
{
	public $domain_name = NULL;

	public function __construct()
	{
		$this->load->model(array('curd_m'));
		$this->load->helper(array('cookie'));
	}
	public function __get($var)
	{
		return get_instance()->$var;
	}
	/*There are many dthmlx component has been used in front end and to show alert session end this function helps
	different component according to their defined format.
	*/
	public function expire_message($obj,$type='expire',$msg = NULL){
		if( $obj==='window' || $obj==='load'){
			if($type=='expire')
				die('<div><script type="text/javascript"> obj.session_expire("'.$type.'");</script></div>');
			elseif($type=='disable')
				die('<div><script type="text/javascript"> obj.session_expire("'.$type.'");</script></div>');
			elseif($type=='data')
				die('<div><script type="text/javascript"> obj.message_show("'.$msg.'","error");</script></div>');
		}
		elseif( $obj==='grid' ){
			header("Content-Type:text/xml");
			if($type=='expire')
			die('<?xml version="1.0" encoding="iso-8859-1" ?><rows><userdata name="session">expire</userdata></rows>');
			elseif($type=='disable')
			die('<?xml version="1.0" encoding="iso-8859-1" ?><rows><userdata name="session">disable</userdata></rows>');
			elseif($type=='message')
			die('<?xml version="1.0" encoding="iso-8859-1" ?><rows><userdata name="session">message</userdata><userdata name="message">'.htmlspecialchars($msg).'</userdata></rows>');
		}
		elseif( $obj==='tree' ){
			header("Content-type: text/xml");
			if($type=='expire'){
				die('<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1">
				<item text="Lists" id="session" open="1"><userdata name="session">expire</userdata></item></tree>');
			}
			elseif($type=='disable'){
				die('<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1">
				<item text="Lists" id="session" open="1"><userdata name="session">disable</userdata></item></tree>');
			}

		}
		elseif( $obj==='ajax' ){
			die('expire');
		}
	}
	/* return all the users assigned privileges
	*/
	public function getUserPrivileges($id=NULL){
		$userId = ($id==NULL)? $this->session->userdata('userId'):$id;
		$res = $this->curd_m->get_search("SELECT fld_privileges FROM users_privileges WHERE fld_user_id=".$userId,'object');
		$priv = array();
		if ($res !== NULL){
			if(sizeof($res)==1){
				if($res[0]->fld_privileges=='default'){
					$res = $this->curd_m->get_search("SELECT p.fld_privileges AS fld_privileges FROM group_privileges p INNER JOIN users_groups g WHERE p.fld_group_id=g.group_id AND g.user_id=".$userId,'object');
				}
			}
			foreach ($res as $row){	$priv[] = $row->fld_privileges;	}
		   	return $priv;
		}
		return FALSE;
	}

	public function getGroupPrivileges($id){
		$res = $this->curd_m->get_search("SELECT fld_privileges FROM group_privileges WHERE fld_group_id=".$id,'object');
		if($res!==NULL){
			foreach ($res as $row){	$priv[] = $row->fld_privileges;	}
		   	return $priv;
		}
		return FALSE;
	}
	public function hasPrivileges($name,$userid){
		$res = $this->curd_m->get_search("SELECT u.fld_int_id FROM feature f INNER JOIN user_feature u WHERE u.fld_user_id=".$userid." AND f.fld_int_id = u.fld_feature_id AND f.fld_chr_feature='".trim(strtolower($name ))."'",'object');
		return $res || FALSE;
	}
	/*
	This functions checks the dependency privileges
	*/
	public function checkPriviDependency($assignPriv){
		$this->config->load('easy_priv');
		$depnPriv = $this->config->item('DEPENDENT_PRIV');

		foreach( $assignPriv as $val){
			if(isset($depnPriv[$val])){
				if( strpos($depnPriv[$val], '|') ===FALSE){
					if(!in_array($depnPriv[$val],$assignPriv)){
						return '** Dependency ERROR :'. $val.' Privileges required '.$depnPriv[$val] .' Privileges';
					}
				}
				else{
					$priv = explode('|',$depnPriv[$val]);
					foreach($priv as $val1){
						if(in_array(str_replace(' ', '', $val1),$assignPriv)){
							return TRUE;
						}
					}
					return '** Dependency ERROR :'. $val.' Privileges required '.implode('|',$priv) .' Privileges';
					/*if(!in_array(trim($privD[0]),$assignPriv) && !in_array(trim($privD[1]),$assignPriv)){
						return '** Dependency ERROR :'. $val.' Privileges required '.$depnPriv[$val] .' Privileges';
					}*/
				}
			}
		}

		return TRUE;
	}
	public function checkCLosePrivileges($priv){
		$this->config->load('easy_priv');
		$closePriv =$this->config->item('CLOSED_PRIV');
		foreach($priv as $val){
			if(isset($closePriv[$val])){
				if( strpos($closePriv[$val], '|') ===FALSE){
					if(!in_array($closePriv[$val],$priv)){
						return '** Closed Privileges ERROR ERROR :'. $val.' Privileges cannot have '.$closePriv[$val] .' Privileges';
					}

				}
				else{
					$clPriv = explode('|',$closePriv[$val]);
					foreach($clPriv as $prvCl){
						if(in_array(str_replace(' ', '', $prvCl),$priv)){
							return '** Closed Privileges ERROR :'. $val.' Privileges cannot have '.str_replace('|', '</br>',$closePriv[$val]) .' Privileges';
						}
					}
				}
			}
		}
		return TRUE;
	}
	/* This function adds the time in login_validate column for detecting whether the user is online or not
	*/
	public function addLoginState(){
		$userId = $this->session->userdata('userId');
		$res = $this->curd_m->get_search("SELECT login_validate  FROM users WHERE id=".$userId,'object');
		if($res!==NULL && sizeof($res)==1){
			if( (time()-$res[0]->login_validate) > $this->config->item('sess_time_to_update')){
				return $this->curd_m->get_update("users",array('id'=>$userId,'login_validate'=>time()));
			}
		}
		return FALSE;
	}
	// update login state to 0 on logout
	public function removeLoginState(){
		$userId = $this->session->userdata('userId');
		return $this->curd_m->get_update("users",array('id'=>$userId,'login_validate'=>0));
	}
	public function checkUserState($id){
		do{
			$res = $this->curd_m->get_search("SELECT active, fld_reseller_id FROM users WHERE id=".$id,'object');
			if( (int)$res[0]->active > 1){ return FALSE;}
			else{$id = (int)$res[0]->fld_reseller_id; }
		}while($id > 0);
		return TRUE;
	}
	/*
	adding extra data to session for future use
	*/
	public function setSessionUserData(){
		$data = array();
		$res = $this->curd_m->get_search("SELECT u.fld_balance_type AS balanceType,u.fld_reseller_id AS fld_reseller_id, g.group_id AS group_id, r.name AS name  FROM users u INNER JOIN users_groups g INNER JOIN groups r WHERE u.id=".$this->session->userdata('userId')." AND u.id = g.user_id AND g.group_id=r.id ",'object');

		if($res!==NULL && sizeof($res)==1){
			$data['reseller'] = $res[0]->fld_reseller_id;
			$data['gname'] = $res[0]->name;
			$data['groupId'] = $res[0]->group_id;
			$data['balanceType'] = $res[0]->balanceType;
		}
		else{
			return FALSE;
		}
		$res = $this->curd_m->get_search("SELECT r.fld_int_id AS fld_int_id ,r.fld_chr_acro AS fld_chr_acro FROM country r INNER JOIN users_country c INNER JOIN users u WHERE u.id=".$this->session->userdata('userId')." AND u.id = c.fld_user_id AND c.fld_country_id=r.fld_int_id ",'object');
		if($res!==NULL && sizeof($res)==1){
			$data['countryId'] = $res[0]->fld_int_id;
			$data['countryAcro'] = $res[0]->fld_chr_acro;
		}
		else{
			return FALSE;
		}
		$this->session->set_userdata($data);
		return TRUE;


	}
	// check if the user is loged in or not
	// returns bool , TRUE OR FALSE
	public function logged_in()
	{
		return (bool) $this->session->userdata('identity');
	}
	/*This function verifies of the user has particular privileges or not
	*/
	public function verifyPrivillages($priv,$userPriv){
		if(is_array($priv)){
			foreach($priv as $val){
				if(in_array($val , $userPriv)) return TRUE;
			}
		}else{
			return in_array($priv , $userPriv);
		}
		return FALSE;
	}
	public function checkLoginStat(){
		$res = $this->curd_m->get_search('SELECT * FROM system_flag WHERE fld_type="login"','object');
		if($res ==NULL) return TRUE;
		else{
			return ($res[0]->fld_val==0)?FALSE:TRUE;
		}
	}
	/*This function generates the dhtmlx ribbon json format according to user assigned privileges
	*/
	public function gen_Ribbon(){

		$userPriv = $this->getUserPrivileges();
		$ribbon = '[';
		if($this->verifyPrivillages('PUSH',$userPriv)){//sms tab
			$ribbon = $ribbon.'{id: "sms", text: "SMS", items: [
					{type: "block", text: "send sms",text_pos: "bottom", mode: "cols", list: [
						{type: "button", id:"sendsms",text: "Send SMS",isbig: true, img: "/sendsms.png" },
					]},
					{type: "block", text: "contacts",text_pos: "bottom", mode: "cols", list: [
						{type: "button", id:"addressbook",text: "Address Book",isbig: true, img: "/adressbook.png" },
					]},
					{type: "block", text: "scheduler",text_pos: "bottom", mode: "cols", list: [
						{type: "button", id:"scheduler",text: "Scheduler",isbig: true, img: "/scheduler.png" },
					]},
					{type: "block", text: "Messages",text_pos: "bottom", mode: "cols", list: [
						{type: "button", id:"template",text: "Messages",isbig: true, img: "/save_msg.png" },
					]},';
				if($this->verifyPrivillages('REMOTE_USER',$userPriv)){//sms tab
					$ribbon = $ribbon.'{type: "block", text: "template",text_pos: "bottom", mode: "cols", list: [
						{type: "button", id:"temp_msg",text: "Templates",isbig: true, img: "/template.png" },
					]},';
				}
				$ribbon = $ribbon.']},';
		}
		// usermanage tab
		if($this->verifyPrivillages(array('USER_MANAGE'),$userPriv)){

			$ribbon= $ribbon.'{id: "users", text: "Users", items: [
					{type: "block", text: "Users",text_pos: "bottom", mode: "cols", list: [
						{type: "button", id:"userList", text: "Users List" , isbig: true, img: "/users.png"},
						{type:"buttonCombo", id: "combo_st",text:"", items: [
							{value: "1", text: "Approved Clients", selected: true},
							{value: "0", text: "Suspended Clients"}
						]},
						{type: "button", id:"gen_exl_list", text: "Generate EXCEL" , img: "/excel.png"},

					]},';

			$ribbon= $ribbon.'{type: "block", text: "add/remove & report",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"cl_sms_tran", text: "Client Transaction Report",  img: "/transaction.png"},
					{type: "button", id:"addBal", text: "Add/remove Balance",  img: "/balance.png"},
					{type: "button", id:"rst_pass", text: "Password Reset" , img: "/reset.png"},
				]},';

			$ribbon= $ribbon.'{type: "block", text: "assign",text_pos: "bottom", mode: "cols", list: [';
			$ribbon= $ribbon.'{type: "button", id:"assignshortcode", text: "Assign ShortCode" , img: "/assignshortcode.png"},';
			$ribbon= $ribbon.'{type: "button", id:"assigngateway", text: "Assign Gateway" , img: "/assigngateway.png"},';
			$ribbon= $ribbon.'{type: "button", id:"assignfeature", text: "Assign Feature" , img: "/features.png"},';
			$ribbon= $ribbon.']},';
			$ribbon= $ribbon.']},';
		}
		// push tab
		if($this->verifyPrivillages(array('PUSH','USER_MANAGE'),$userPriv)){
			$ribbon= $ribbon.'{id: "push", text: "Push", items: [';

				$ribbon= $ribbon.'{type: "block", text: "Que",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"cron",isbig: true,text: "Que Jobs", isbig: true, img: "/cron.png"},
				]},';
				$ribbon= $ribbon.'{type: "block", text: "gateway",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"gateway", isbig: true,text: "Gateway", img: "/gateway.png"},
				]},';
				$ribbon= $ribbon.'{type: "block", text: "sender ID",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"sender_id",isbig: true,text: "Sender ID",  img: "/senderid.png"}
				]},';
			$ribbon= $ribbon.']},';
		}
		//pull tab
		if($this->verifyPrivillages(array('PULL','USER_MANAGE'),$userPriv)){
			$ribbon= $ribbon.'{id: "pull", text: "Pull", items: [';

				$ribbon= $ribbon.'{type: "block", text: "shortcode",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"shortcode",isbig: true,text: "Shortcode", isbig: true, img: "/shortcode.png"},
				]},';
				if($this->session->userdata('userId')==1){
					$ribbon= $ribbon.'{type: "block", text: "category",text_pos: "bottom", mode: "cols", list: [
						{type: "button", id:"category", isbig: true,text: "Category", img: "/category.png"},
					]},';
				}
				$ribbon= $ribbon.'{type: "block", text: "keys",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"keys",isbig: true,text: "Key List",  img: "/keys.png"}
				]},';
			if($this->verifyPrivillages(array('PULL'),$userPriv)){
				$ribbon= $ribbon.'{type: "block", text: "uploaddb",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"upload",isbig: true,text: "upload DB", img: "/uploaddb.png"},
				]},';
			}
			if($this->verifyPrivillages(array('PULL'),$userPriv)){
				$ribbon= $ribbon.'{type: "block", text: "scheme",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"scheme",isbig: true,text: "Scheme",  img: "/scheme.png"}
				]}';
			}
			$ribbon= $ribbon.']},';
		}
		// manage tab
		if($this->session->userdata('userId')==1){
			$ribbon= $ribbon.'{id: "manage", text: "Manage", items: [
				{type: "block", text: "groups",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"group",isbig: true,text: "Groups",  img: "/usergroup.png"}
				]},
				{type: "block", text: "cell prefix",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"prefix", isbig: true,text: "Cell Prefix", img: "/prefix.png"},
				]},
				{type: "block", text: "operator",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"operator",isbig: true,text: "Operator",  img: "/operator.png"}
				]},
				{type: "block", text: "county",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"country",isbig: true,text: "Country",  img: "/country.png"}
				]},
				{type: "block", text: "features",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"feature",isbig: true,text: "Features",  img: "/features.png"}
				]}
			]},';
		}
		//report tab
		if($this->verifyPrivillages(array('USER_MANAGE','PULL','PUSH'),$userPriv)){
			$ribbon= $ribbon.'{id: "report", text: "Report", items: [';
			if($this->verifyPrivillages(array('PUSH','USER_MANAGE'),$userPriv)){
				$ribbon= $ribbon.'{type: "block", text: "push report",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"smsreport", text: "Transaction",isbig: true, img: "/reportsTran.png" },
					{type: "button", id:"creditlog",text: "Credit",isbig: true, img: "/creditreport.png" },
					{type: "button", id:"sentbox",text: "Sent",isbig: true, img: "/sentbox.png" },';
				if($this->verifyPrivillages(array('USER_MANAGE'),$userPriv)){
					$ribbon= $ribbon.'{type: "button", id:"dailyreport",text: "Daily",isbig: true, img: "/dailyreport.png" },';
				}
				$ribbon= $ribbon.']},';
			}
			if($this->verifyPrivillages(array('PULL','USER_MANAGE'),$userPriv)){
				$ribbon= $ribbon.'{type: "block", text: "pull report",text_pos: "bottom", mode: "cols", list: [
					{type: "button", id:"pullreport", text: "Report",isbig: true, img: "/pullreport.png" },
					{type: "button", id:"error_report",text: "Error",isbig: true, img: "/errorreport.png" },
					{type: "button", id:"feedback",text: "Feedback",isbig: true, img: "/feedback.png" },';
				if($this->session->userdata('userId')==1){// only for admin
					$ribbon= $ribbon.'{type: "button", id:"detailsPull",text: "Details",isbig: true, img: "/details.png" },';
				}

				$ribbon= $ribbon.']},';
			}
			$ribbon= $ribbon.']},';
		}

		$ribbon = $ribbon.']';
		return $ribbon;
	}
	/*This function generates the dhtmlx toolbar xml format according to user assigned privileges
	*/
	public function gen_Toolbar(){
		$toolbar ='<?xml version="1.0"?><toolbar>';
		$toolbar = $toolbar.'<item id="pullReportSort" type="text" text="Search By" img="/detail.png" />';

		$toolbar = $toolbar.'<item id="newuser" type="button" text="Add User" img="/adduser.png"/>';
		$toolbar = $toolbar.'<item id="userapprove" type="button" text="Approve" img="/userapprove.png"/>';
		$toolbar = $toolbar.'<item id="usersuspend" type="button" text="Suspend" img="/usersuspend.png"/>';
		$toolbar = $toolbar.'<item id="pedit" type="button" text="Edit Privileges" img="/edit.png"/>';

		$toolbar = $toolbar.'<item id="newgateway" type="button" text="Add Gateway" img="/assigngateway.png"/>';

		$toolbar = $toolbar.'<item id="newsenderid" type="button" text="Add SenderID" img="/senderid.png"/>';

		$toolbar = $toolbar.'<item id="newpackage" type="button" text="Add Package" img="/package.png"/>';

		$toolbar = $toolbar.'<item id="newshortcode" type="button" text="Add shortcode" img="/assignshortcode.png"/>';

		$toolbar = $toolbar.'<item id="newcategory" type="button" text="Add Category" img="/category.png"/>';

		$toolbar = $toolbar.'<item id="newkey" type="button" text="Add key" img="/keys.png"/>';

		$toolbar = $toolbar.'<item id="newscheme" type="button" text="Add Scheme" img="/scheme.png"/>';

		$toolbar = $toolbar.'<item id="newgroup" type="button" text="Add Group" img="/usergroup.png"/>';
		$toolbar = $toolbar.'<item id="newprefix" type="button" text="Add Prefix" img="/prefix.png"/>';
		$toolbar = $toolbar.'<item id="newoperator" type="button" text="Add Operator" img="/operator.png"/>';
		$toolbar = $toolbar.'<item id="newcountry" type="button" text="Add Country" img="/country.png"/>';
		$toolbar = $toolbar.'<item id="newfeature" type="button" text="Add Feature" img="/features.png"/>';



		$toolbar = $toolbar.'<item id="newscheduler" type="button" text="Add Schedule" img="/scheduler.png"/>';
		$toolbar = $toolbar.'<item id="newmsgtemplate" type="button" text="Add Template" img="/template.png"/>';
		$toolbar = $toolbar.'<item id="newaddressbook" type="button" text="Add Address Book" img="/adressbook.png"/>';
		$toolbar = $toolbar.'<item id="newcontact" type="button" text="Add Contact" img="/contact.png" />';
		$toolbar = $toolbar.'<item id="newtemplate" type="button" text="Add Message" img="/save_msg.png"/>';
		$toolbar = $toolbar.'<item id="aedit" type="buttonSelect" text="Edit" img="/edit.png">';
		$toolbar = $toolbar.'<item type="button"	id="address_edit"  text="Address Book" img="/adressbook.png"/>';
		$toolbar = $toolbar.'<item type="button"	id="contact_edit" text="Contact"    img="/contact.png"    />';
		$toolbar = $toolbar.'</item>';
		$toolbar = $toolbar.'<item id="adelete" type="buttonSelect" text="Delete" img="/edit.png">';
		$toolbar = $toolbar.'<item type="button"	id="address_delete"  text="Address Book" img="/adressbook.png"/>';
		$toolbar = $toolbar.'<item type="button"	id="contact_delete" text="Contact"    img="/contact.png"    />';
		$toolbar = $toolbar.'</item>';
		$toolbar = $toolbar.'<item id="edit" type="button" text="Edit" img="/edit.png" />';

		$toolbar = $toolbar.'<item id="approve" type="button" text="Approve" img="/approve.png"/>';
		$toolbar = $toolbar.'<item id="disapprove" type="button" text="Disapprove" img="/disapprove.png" />';


		$toolbar = $toolbar.'<item id="enable" type="button" text="Enable" img="/enable.png" />';
		$toolbar = $toolbar.'<item id="disable" type="button" text="Disable" img="/disable.png" />';

		$toolbar = $toolbar.'<item id="stopcron" type="button" text="Stop Cron" img="/stopcron.png" />';
		$toolbar = $toolbar.'<item id="resumecron" type="button" text="Resume Cron" img="/resumecron.png" />';

		//$toolbar = $toolbar.'<item id="edit" type="button" text="Edit" img="/edit.png" />';

		$toolbar = $toolbar.'<item id="delete" type="button" text="Delete" img="/delete.png" />';

		$toolbar = $toolbar.'<item id="todayReport" type="button" text="Today Report" img="/detail.png" />';

		$toolbar = $toolbar.'<item id="upload" type="button" text="Upload" img="/upload.png" />';
		$toolbar = $toolbar.'<item id="detail" type="button" text="Detail" img="/detail.png" />';
		$toolbar = $toolbar.'<item id="empty" type="button" text="Empty" img="/empty.png" />';

		$toolbar = $toolbar.'<item id="select" type="button" text="Select All" img="/selectall.png" />';
		$toolbar = $toolbar.'<item id="disselect" type="button" text="Dis-select" img="/disselect.png" />';
		$toolbar = $toolbar.'<item id="excel" type="button" text="Excel_this" img="/excel.png" />';

		$toolbar = $toolbar.'<item id="excel_new" type="button" text="Download_excel_new" img="/excel.png" />';
		
		$toolbar = $toolbar.'<item id="search" type="button" text="Search" img="/search.png" />';
		$toolbar = $toolbar.'</toolbar>';

		return $toolbar;
	}
	public function logout()
	{
		$identity = $this->config->item('identity', 'ion_auth');

                if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->unset_userdata( array($identity => '', 'id' => '', 'user_id' => '') );
                }
                else
                {
                	$this->session->unset_userdata( array($identity, 'id', 'user_id') );
                }

		// delete the remember me cookies if they exist
		if (get_cookie($this->config->item('identity_cookie_name', 'ion_auth')))
		{
			delete_cookie($this->config->item('identity_cookie_name', 'ion_auth'));
		}
		if (get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
		{
			delete_cookie($this->config->item('remember_cookie_name', 'ion_auth'));
		}

		// Destroy the session
		$this->session->sess_destroy();

		//Recreate the session
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->sess_create();
		}
		else
		{
			if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
				session_start();
			}
			$this->session->sess_regenerate(TRUE);
		}
		return TRUE;
	}
	public function veryfy_url($userid){
		$this->config->load('easy_config');
		if($userid==1 && $this->config->item('admin_login_domain') ==$_SERVER['HTTP_HOST']) return TRUE;
		elseif( $userid==1 && $this->config->item('admin_login_domain') !=$_SERVER['HTTP_HOST'] ) return FALSE;
		do{
			$res = $this->curd_m->get_search("SELECT f.fld_chr_feature AS fld_chr_feature,u.extra_info AS extra_info,u.fld_chr_title AS fld_chr_title FROM feature f INNER JOIN user_feature u  WHERE u.fld_feature_id = f.fld_int_id AND u.fld_user_id=".$userid,'object');
			if($res !=NULL){

				foreach($res as $row){
					if($row->fld_chr_feature == 'subbranding'){

						if($_SERVER['HTTP_HOST'] == trim($row->extra_info)){

							$this->domain_name=  $row->fld_chr_title;
							return TRUE;
						}
					else return FALSE;
					}
				}
			}
			$user = $this->curd_m->get_search("SELECT * FROM users  WHERE id=".$userid,'object');
			if($user==NULL) die(show_404());
			$userid = $user[0]->fld_reseller_id;
			//var_dump($user[0]->fld_reseller_id);exit;
		}
		while($userid!=1);

		$res = $this->curd_m->get_search("SELECT f.fld_chr_feature AS fld_chr_feature,u.extra_info AS extra_info,u.fld_chr_title AS fld_chr_title FROM feature f INNER JOIN user_feature u  WHERE u.fld_feature_id = f.fld_int_id AND u.fld_user_id=".$userid,'object');
		if($res == NULL){
			if($this->config->item('admin_login_domain') ==$_SERVER['HTTP_HOST'] ){
				return TRUE;
			}
			else return FALSE;
		}
		else{
			foreach($res as $row){
				if($row->fld_chr_feature == 'subbranding'){
					if($_SERVER['HTTP_HOST'] == trim($res[0]->extra_info)){
						$this->domain_name=  $res[0]->fld_chr_title;
						return TRUE;
					}
					else return FALSE;
				}
			}
		}
		return TRUE;
	}

	/**end of class***/
}
