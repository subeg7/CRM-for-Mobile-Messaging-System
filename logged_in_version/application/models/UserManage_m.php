<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class UserManage_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('curd_m','ion_auth_model','common_m');
		$this->load->library('dhxload','vas');
		$this->operator = array();
	}

	public function getUserTransactionId(){
		$res = $this->curd_m->get_search('SELECT fld_transaction_id FROM users ORDER BY id DESC LIMIT 1','object');
		return ($res!==NULL) ?((int)$res[0]->fld_transaction_id + 10): FALSE;
	}
	public function renderUser($type , $userId,$search,$data){
		$searchArr = array();
		$operator = $this->curd_m->get_search('SELECT * FROM operator','object');
		if($operator!==NULL){
			foreach($operator as $row){
				$this->operator[$row->fld_int_id] =  strtoupper($row->acronym);
			}
		}

		$type = ($type=='approve')?'=1': '>1';
		if($userId == 1){
			if($data['feature']!=='none' && $search=='search' || $search=='download' ){
				$query = "SELECT u.id AS id,u.fld_balance_type AS fld_balance_type,u.fld_transaction_id AS tid,u.login_validate AS login,u.company AS company,s.company AS resel,u.contact_person AS name,u.contact_number AS contact,g.name AS gname FROM users u INNER JOIN users s INNER JOIN groups g INNER JOIN users_groups l INNER JOIN user_feature f WHERE u.fld_reseller_id = s.id AND u.active ".$type." AND u.id = l.user_id AND l.group_id = g.id AND f.fld_user_id = u.id AND f.fld_feature_id =".$data['feature'];
				$searchArr[]='feature='.$data['feature'];
			}else{
				$query = "SELECT u.id AS id,u.fld_balance_type AS fld_balance_type,u.fld_transaction_id AS tid,u.login_validate AS login,u.company AS company,s.company AS resel,u.contact_person AS name,u.contact_number AS contact,g.name AS gname FROM users u INNER JOIN users s INNER JOIN groups g INNER JOIN users_groups l  WHERE u.fld_reseller_id = s.id AND u.active ".$type." AND u.id = l.user_id AND l.group_id = g.id";
			}
			$row = "id,tid,group,company,resel,name,contact,bal,country,status";
			$prinRowsName = 'Client ID,Group/Feature,User,Reseller,Contact Person,Contact No.,Balance,Country,Status';
		}else{
			$priv = $this->vas->getUserPrivileges($userId);
			$user_id = (in_array('USER_MANAGE',$priv))?$userId: $this->session->userdata('reseller');
			if($data['feature']!=='none' && $search=='search' || $search=='download' ){
				$query = "SELECT u.id AS id,u.fld_balance_type AS fld_balance_type,u.fld_transaction_id AS tid,u.login_validate AS login,u.company AS company,u.contact_person AS name,u.contact_number AS contact,g.name AS gname FROM users u INNER JOIN groups g INNER JOIN users_groups l INNER JOIN user_feature f WHERE fld_reseller_id = ".$userId." AND active ".$type." AND u.id = l.user_id AND l.group_id = g.id AND f.fld_user_id = u.id AND f.fld_feature_id =".$data['feature'];
				$searchArr[]='feature='.$data['feature'];
			}else{
				$query = "SELECT u.id AS id,u.fld_balance_type AS fld_balance_type,u.fld_transaction_id AS tid,u.login_validate AS login,u.company AS company,u.contact_person AS name,u.contact_number AS contact,g.name AS gname FROM users u INNER JOIN groups g INNER JOIN users_groups l  WHERE fld_reseller_id = ".$userId." AND active ".$type." AND u.id = l.user_id AND l.group_id = g.id";
			}


			$row = "id,tid,group,company,name,contact,bal,country,status";
			$prinRowsName = 'Client ID,Group,User,Contact Person,Contact No.,Balance,Country,Status';
		}

		if( $search=='search' || $search=='download' ){
			$like = '';
			if($data['userid']!=='none' ){
				$like .=' AND u.fld_transaction_id ='.$data['userid'];
				$searchArr[]='userid='.$data['userid'];
			}
			if($data['name']!=='none' ){
				$like .=' AND u.company LIKE "%'.$data['name'].'%" ';
				$searchArr[]='name='.$data['name'];
			}
			if($data['reseller']!=='none' ){
				$like .=' AND s.company LIKE "%'.$data['reseller'].'%" ';
				$searchArr[]='reseller='.$data['reseller'];
			}

			if($data['group']!=='none' ){
				$like .=' AND g.id ='.$data['group'];
				$searchArr[]='group='.$data['group'];
			}
			if($data['state']!=='none' ){
				$like .=($data['state']=='offline')?' AND u.login_validate < '.(time()-$this->config->item('sess_expiration')):' AND u.login_validate > '.(time()-$this->config->item('sess_expiration'));
				$searchArr[]='state='.$data['state'];
			}

			$query =$query.$like;
			if($search=='download'){
				$res = $this->dhxload->getCsvData(array(
											'callback'=>array($this,'userCalback'),
											'query'=>$query.' ORDER BY u.id ASC',
											'rows'=>$row,
											'prinRowsName'=>$prinRowsName
											));

				$folderName = $this->curd_m->getData('users',array('id'=>$this->session->userdata('userId') ),'object');
				if($res != NULL) $res = $this->common_m->getExcecl($res, $folderName[0]->fld_transaction_id);
				die( var_dump($res));
			}
		}
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'rows'=>$row,
									'callback'=>array($this,'userCalback'),
									'query'=>$query.' ORDER BY u.id ASC',
									'userdata'=>(sizeof($searchArr)>0)?implode('__',$searchArr):'',
								   ));
	}

	public function userCalback($item){
		/*$res = $this->curd_m->get_search('SELECT g.name AS name FROM groups g INNER JOIN users_groups u WHERE u.user_id='.$item->get_value('id').' AND u.group_id=g.id');*/
		$userFeature = $this->curd_m->get_search('SELECT f.fld_int_id AS fld_int_id ,f.fld_chr_feature AS fld_chr_feature,u.extra_info AS extra_info FROM feature f INNER JOIN user_feature u WHERE u.fld_feature_id = f.fld_int_id AND u.fld_user_id = '.$item->get_value('id'),'object');
		if($userFeature==NULL){
			$item->set_value('group',ucwords($item->get_value('gname')));
		}else{
			$featureData = ucwords($item->get_value('gname')).'</br>';
			foreach($userFeature as $row){
				$featureData .='<span style="color:red;">[<span style="color:blue;">'.ucwords($row->fld_chr_feature).'</span>]</span>';
			}
			$item->set_value('group',$featureData);
		}

		$res = $this->curd_m->get_search('SELECT c.fld_chr_acro AS name FROM country c INNER JOIN users_country u WHERE u.fld_user_id='.$item->get_value('id').' AND u.fld_country_id=c.fld_int_id');
		$item->set_value('country',strtoupper($res[0]['name']));

		//$item->set_value('bal', 1000);


		if(!$item->get_value('resel')){
			$item->set_value('resel', 'test');
		}
		if($item->get_value('login')==0){
			$item->set_value('status','<span style="color:red;">Offline</span>');
		}
		else{
			if( (time()-$this->config->item('sess_expiration')) >$item->get_value('login') || (time()-$this->config->item('sess_expiration')) == $item->get_value('login') ){
				$item->set_value('status','<span style="color:red;">Offline</span>');
			}
			else{
				$item->set_value('status','<span style="color:green;">Online</span>');
			}
		}
		if($item->get_value('fld_balance_type')=='POSTPAID'){
			$item->set_value('bal','<span style="color:blue;">POSTPAID</span>');
		}else{
		//	$operator = $this->curd_m->get_search('SELECT * FROM operator');
			$tempBal = array();
			$balance = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$item->get_value('id'),'object');
			if($balance !=NULL){
				foreach($balance as $row){
					if($row->balance_type == 'appbal'){
						$tempBal[] = '<span style="color:blue;">App.: </span>'.$row->amount;
					}
					else{
						$tempBal[] = '<span style="color:blue;">'.$this->operator[$row->balance_type].'</span> : '.$row->amount;
					}
				}
				$item->set_value('bal',implode('</br> ',$tempBal));
			}else{
				$item->set_value('bal','-----');
			}
		}

	}
	public function userCalbackPrint(){/*
		$userFeature = $this->curd_m->get_search('SELECT f.fld_int_id AS fld_int_id ,f.fld_chr_feature AS fld_chr_feature,u.extra_info AS extra_info FROM feature f INNER JOIN user_feature u WHERE u.fld_feature_id = f.fld_int_id AND u.fld_user_id = '.$item->get_value('id'),'object');
		if($userFeature==NULL){
			$item->set_value('group',ucwords($item->get_value('gname')));
		}else{
			$item->set_value('group',ucwords($item->get_value('gname')).'/'.ucwords($userFeature[0]->fld_chr_feature));
		}

		$res = $this->curd_m->get_search('SELECT c.fld_chr_acro AS name FROM country c INNER JOIN users_country u WHERE u.fld_user_id='.$item->get_value('id').' AND u.fld_country_id=c.fld_int_id');
		$item->set_value('country',strtoupper($res[0]['name']));

		//$item->set_value('bal', 1000);



		if($item->get_value('login')==0){
			$item->set_value('status','Offline');
		}
		else{
			if( (time()-$this->config->item('sess_expiration')) >$item->get_value('login') || (time()-$this->config->item('sess_expiration')) == $item->get_value('login') ){
				$item->set_value('status','Offline');
			}
			else{
				$item->set_value('status','Online');
			}
		}
		if($item->get_value('fld_balance_type')=='POSTPAID'){
			$item->set_value('bal','POSTPAID');
		}else{
		//	$operator = $this->curd_m->get_search('SELECT * FROM operator');
			$tempBal = array();
			$balance = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$item->get_value('id'),'object');
			if($balance !=NULL){
				foreach($balance as $row){
					if($row->balance_type == 'appbal'){
						$tempBal[] = 'App.: '.$row->amount;
					}
					else{
						$tempBal[] = $this->operator[$row->balance_type].' : '.$row->amount;
					}
				}
				$item->set_value('bal',implode('/',$tempBal));
			}else{
				$item->set_value('bal','-----');
			}
		}
	*/}
	public function manageUser($id,$type){
		$user_id = $this->session->userdata('userId');
		$stat = ($type=='approve')?1:( ($user_id == 1) ? '3' : '2');
		$res = $this->curd_m->get_update('users',array('id'=>$id,'active'=>(int)$stat));
		return ($res===TRUE)?die('sucess'): die('** Error: Unable to update User status');

	}
	public function getUserDetail($id){
		$arr['detail']= $this->curd_m->get_search('SELECT u.username AS username,u.contact_person AS person, u.address AS address, u.company AS company , u.contact_number AS personnumber , u.phone AS orgphone,u.email AS email, u.fld_transaction_id AS tranid,u.fld_balance_type AS baltype, g.company AS resname FROM users u INNER JOIN users g WHERE u.id='.$id.' AND g.id = u.fld_reseller_id	','object');
		$arr['group']= $this->curd_m->get_search('SELECT g.name FROM groups g INNER JOIN users_groups u WHERE u.group_id = g.id AND u.user_id='.$id,'object');
		$arr['country']= $this->curd_m->get_search('SELECT c.fld_chr_acro FROM country c INNER JOIN users_country u WHERE u.fld_country_id=c.fld_int_id AND u.fld_user_id='.$id,'object');
		$arr['privileges'] = $this->getUserPrivileges($id);
		if($this->vas->hasPrivileges('api',$id) && !isset($arr['privileges']['privileges']['USER_MANAGE'])){
			$arr['api'] = $this->curd_m->get_search('SELECT * FROM remote_access WHERE fld_user_id='.$id,'object');
		}
		if($this->vas->hasPrivileges('pullroute',$id) && !isset($arr['privileges']['privileges']['USER_MANAGE'])){
			$arr['route'] = $this->curd_m->get_search('SELECT * FROM user_route WHERE fld_user_id='.$id,'object');
		}
		//die(var_dump($arr['privileges']));
		/*if( isset($arr['privileges']['pushPrivileges']['REMOTE_USER']) ){
			//die('sfdsdf');
			$arr['api'] = $this->curd_m->get_search('SELECT * FROM remote_access WHERE fld_user_id='.$id,'object');
		}
		if(isset($arr['privileges']['pullPrivileges']['REMOTE_ROUTE']) ){
			$arr['route'] = $this->curd_m->get_search('SELECT * FROM user_route WHERE fld_user_id='.$id,'object');
		}*/

		return $arr;
	}


	public function getUserPrivileges($id){
		$priv = $this->vas->getUserPrivileges($id);
		if($priv !== FALSE){
			$this->data = array();
			$this->config->load('easy_priv');
			$privileges= $this->config->item('PRIVILEGES');
			foreach($privileges as $key=>$val){
				if(in_array($key,$priv))	$userPriv['privileges'][$key] = $val;
			}

			return $userPriv;
		}
		return FALSE;
	}

	public function manageUserPrivileges($id,$assignPriv){
		$assignPriv = explode(',',$assignPriv);
		$res = $this->vas->checkCLosePrivileges($assignPriv);
		if($res !== TRUE) return $res;
		$res = $this->vas->checkPriviDependency($assignPriv);
		if($res === TRUE){

			$grp = $this->common_m->getGroup($id);

			$grp_priv = $this->vas->getGroupPrivileges($grp->id);
			if($grp_priv ===FALSE) return 'Undefined User Group privileges';
			$userPriv = $this->vas->getUserPrivileges($id);
			if($userPriv==FALSE) return 'Undefined Privileges of users Selected';
			sort($userPriv);
			sort($assignPriv);
			sort($grp_priv);

			if(implode(' ',$grp_priv) !== implode(' ',$assignPriv)){
				if(implode(' ',$userPriv) === implode(' ',$assignPriv)) return 'Same privileges';
			}
			$del = $this->curd_m->get_delete('users_privileges',array('fld_user_id'=>array($id)));
			if($del == TRUE){
				if(implode(' ',$grp_priv) === implode(' ',$assignPriv)){
					$this->curd_m->get_insert('users_privileges',array('fld_privileges'=>'default','fld_user_id'=>$id));
				}else{
				$newPriv = array();
					foreach($assignPriv as $val ){
						$newPriv[] = array('fld_privileges'=>$val,'fld_user_id'=>$id);
					}
					$del = $this->curd_m->get_insert('users_privileges',$newPriv,'batch');
				}
			}
		}
		else return $res;

		return TRUE;
	}

	public function resetPassword($id,$password){
		$query = $this->curd_m->get_search('SELECT username FROM users WHERE id='.$id,'object');
		if($query==NULL) return '** Error : Operation Fail';
		$identity = $query[0]->username;

		if( $identity != NULL ){

			if($this->ion_auth_model->hash_password_db($id, $password))
				return '** Warning : Previous password and new password same, There is nothing to reset';

			if($this->ion_auth_model->reset_password($identity, $password))	return TRUE;
			else $this->ion_auth_model->errors();

		}
		else return "Operation Fail";

	}
	public function addBalance($userid,$unit,$baltype,$operator,$des){
		$balArr = array();
		$date = time();
		$reselerId = $this->session->userdata('userId');
		$userName = $this->curd_m->get_search('SELECT company FROM users WHERE id='.$userid,'object');
		$reselerBalTyp = strtolower($this->common_m->checkBalanceType($reselerId) );
		$resellerBal = 0;
	//	$transacationBalanceType = ($baltype=='appbal')?'appbal':
		if( $reselerBalTyp !='postpaid'){
			if($reselerBalTyp =='seperate' && $baltype =='single') return '**Error : Mismatched Balance Type of User';
			elseif($reselerBalTyp =='postpaid' && $baltype =='postpaid') return '**Error : Mismatched Balance Type of User';
			$resellerBal = $this->common_m->getBalance($reselerId,$baltype,$operator);
			if($resellerBal===FALSE || $resellerBal===0 || $unit > $resellerBal) return '** Warning : Not Enough Balance';
			if(!$this->common_m->updateBalance($reselerId,($resellerBal-$unit),$reselerBalTyp,$operator)) return '** Error : Operation Fail';
		}
		// insertion in balance transaction for credit log
		if($des == NULL){
			$res_message = ($unit > 0)?'deduced : balance alloted to '.$userName[0]->company:'alloted : Balance deduced from '.$userName[0]->company;
		}
		else{
			$res_message = ($unit > 0)?'deduced : balance alloted to '.$userName[0]->company .' // '. $des:'alloted : Balance deduced from'.$userName[0]->company .' // '.$des;
		}
		$this->curd_m->get_insert('balance_transaction',array(
																'fld_int_id'=>uniqid(),
																'fld_user_id'=>$reselerId,
																'fld_transaction_type'=>($unit > 0)?2:3,
																'fld_transaction_descripition'=> $res_message ,
																'fld_balance_type'=>($baltype=='appbal' || $operator==NULL)?$baltype:$operator ,
																'fld_amount'=>($unit > 0)?$unit: (-1*$unit),
																'fld_balance_after_update'=>($reselerId != 1 &&  $reselerBalTyp !='postpaid')?($resellerBal-$unit):0,
																'date'=>$date,
															));
		$userBal = $this->common_m->getBalance($userid,$baltype,$operator);

		if($userBal===FALSE){
			return '** Error : Operation Fail';
		}
		if(!$this->common_m->updateBalance($userid,($userBal+$unit),$baltype,$operator)) return '** Error : Operation Fail';
		if($des == NULL){
			$user_message = ($unit > 0)?'alloted : balance alloted by admin':'deduced : balance deduced by admin' ;
		}
		else{
			$user_message = ($unit > 0)?'alloted :  balance alloted by admin // '.$des : 'deduced : balance deduced by admin // '.$des;
		}
		$this->curd_m->get_insert('balance_transaction',array(
																'fld_int_id'=>uniqid(),
																'fld_user_id'=>$userid,
																'fld_transaction_type'=>($unit > 0)?1:2,
																'fld_transaction_descripition'=> $user_message,
																'fld_balance_type'=>($baltype=='appbal'|| $operator==NULL)?$baltype:$operator ,
																'fld_amount'=>($unit > 0)?$unit: (-1*$unit),
																'fld_balance_after_update'=>($userBal+$unit),
																'date'=>$date,
															));
		return TRUE;

	}
// model ends
}
