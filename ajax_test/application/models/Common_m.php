<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('curd_m');
		$this->load->library(array('dhxload'));
		$this->priv = NULL;
	}
	public function checkRoute($id,$req = 'ajax'){
		$priv = $this->vas->getGroupPrivileges($id);
		if($priv !== FALSE){
			if($req =='ajax') die((in_array('REMOTE_ROUTE',$priv))?'exist':'none');
			else return (in_array('REMOTE_ROUTE',$priv))?'exist':'none';
		}
		if($req =='ajax') die('** Error: Privileges Group Error');
		else return FALSE;
	}
	public function checkRemote($id,$req = 'ajax'){
		$priv = $this->vas->getGroupPrivileges($id);
		if($priv !== FALSE){
			if($req =='ajax') die((in_array('REMOTE_USER',$priv))?'exist':'none');
			else return (in_array('REMOTE_USER',$priv))?'exist':'none';
		}
		if($req =='ajax') die('** Error: Privileges Group Error');
		else return FALSE;
	}
	public function getGroup($id){
		$res = $this->curd_m->get_search('SELECT g.id AS id, g.name AS name FROM users_groups u INNER JOIN groups g WHERE g.id=u.group_id AND u.user_id='.$id,'object');
		if( $res ==NULL) return FALSE;
		return $res[0];
	}
	public function isMyClinet($id){
		$res = $this->curd_m->get_search('SELECT fld_reseller_id FROM users WHERE id='.$id,'object');
		if( $res ==NULL) return FALSE;
		return ($this->session->userdata('userId')===$res[0]->fld_reseller_id)?TRUE: FALSE;
	}
	public function checkBalanceType($id){
		$res = $this->curd_m->get_search('SELECT fld_balance_type FROM users WHERE id='.$id,'object');
		if( $res ==NULL) return FALSE;
		return $res[0]->fld_balance_type;
	}
	public function getCountry($id){
		$res = $this->curd_m->get_search('SELECT c.fld_int_id AS fld_int_id,c.fld_chr_code AS fld_chr_code FROM country c INNER JOIN users_country u WHERE c.fld_int_id = u. fld_country_id AND u.fld_user_id='.$id,'object');
		if( $res ==NULL) return FALSE;
		return array($res[0]->fld_int_id,$res[0]->fld_chr_code);
	}
	public function getSiblingOperator($id){
		$res = $this->curd_m->get_search('SELECT o.fld_int_id AS fld_int_id, o.acronym AS acronym, o.description AS description, o.country_id AS country_id FROM operator t INNER JOIN operator o WHERE t.country_id = o.country_id AND t.fld_int_id='.$id,'object');
		if( $res ==NULL) return FALSE;
		return $res;
	}
	public function getOperatorByCountry($id){
		$res = $this->getCountry($id);
		if($res===FALSE) return FALSE;
		$res = $this->curd_m->get_search('SELECT * FROM operator WHERE country_id='.$res[0],'object');
		if( $res ==NULL) return FALSE;
		return $res;
	}
	public function getPrefix($countryId){
		$res = $this->curd_m->get_search('SELECT p.prefix AS prefix,p.operator_id AS operator FROM prefix p INNER JOIN operator o WHERE p.operator_id = o.fld_int_id AND o.country_id='.$countryId,'object');
		if( $res ==NULL) return FALSE;
		foreach($res as $row){
			$arr[$row->operator][] = $row->prefix;
		}
		return $arr;
	}
	function count_message($ms_type, $message){
		$size = 160;
		$ms_type =  $ms_type;
		$len = mb_strlen($message,'UTF-8');
		$msg_len = 0;
		$res = array();
		if     ( $ms_type == 2 && $len <= 70 ) $size = 70;
		else if( $ms_type == 2 && $len > 70  ) $size = 67;
		else if( $ms_type != 2 && $len <= 160) $size = 160;
		else if( $ms_type != 2 && $len > 160 ) $size = 153;
		$char_len= ( $len-((int)($len/$size)*$size));
		if($char_len!=0){
			$msg_len = (int)(($len+$size)/$size);
		}
		else{
			$msg_len = (int)(($len)/$size);
		}
		$res['msg_len']= $msg_len;
		if($char_len!=0){
			$res['char_len'] =  ($size- $char_len) ;
		}else{
			$res['char_len'] = $char_len;
		}
		return $res;
	}
	public function verifyNumber($data,$userId=NULL){
		if(!is_array($data)) return ('Invalid Format');
		$number_validated = array();
		$userId = ($userId==NULL)?$this->session->userdata('userId'):$userId;
		$country = $this->getCountry($userId);
		$countryId = $country[0];
		$prefix = $this->getPrefix($countryId);
		$alPrifix = array();
		foreach($prefix as $key => $val){
			foreach($val as $val1){
				$alPrifix[$val1]= $key;
			}
		}
		foreach($data as $val){
			$val = trim($val);
			if(ctype_digit($val)){
				if( strlen($val)== (10+strlen($country[1])) ){
					$numCountryId = substr($val,0,strlen($country[1]));
					$numPrefix = substr($val,-10,3);
					$number = substr($val,3);
					if($numCountryId === $country[1]){
						if(isset($alPrifix[$numPrefix])){
							if(isset($number_validated[$alPrifix[$numPrefix]]) && in_array($number,$number_validated[$alPrifix[$numPrefix]]) ){
								$number_validated['repeat'][] = $number;
							}
							else $number_validated[$alPrifix[$numPrefix]][]= $number;
						}
						else $number_validated['fault'][] = $val;
					}
					else $fault[] = $val;
				}
				elseif( strlen($val)==10 ){
					$numPrefix = substr($val,0,3);
					$number = $val;
					if( isset($alPrifix[$numPrefix]) ){
						if(isset($number_validated[$alPrifix[$numPrefix]]) && in_array($number,$number_validated[$alPrifix[$numPrefix]])){
							$number_validated['repeat'][] = $number;
						}
						else $number_validated[$alPrifix[$numPrefix]][]= $number;
					}
					else $number_validated['fault'][] = $val;
				}
				else{
					$number_validated['fault'][] = $val;
				}
			}
			else{
				$number_validated['fault'][] = $val;
			}
		}
		return $number_validated;
	}
	public function getExcecl($data,$folderName){
		$folderName = "download/".$folderName;
		$fileName = time();//default
		// $fileName = "sfsdkfjls_sdfskdjfsdfjslkdfj_sdlkfjsdfksjdfhksdfh_dsjfsdklfjsldfkj_3234234_4234234234_".time();
			// $fileName= "id_2000_"
		$filePath = $folderName.'/'.$fileName;
		if(!is_dir($folderName)){
			mkdir($folderName,0777);
		}
		$file = fopen($filePath.'.csv', "w");
		fwrite($file,$data);
		fclose($file);
		chmod($filePath.'.csv', 0777);
		$zip = new ZipArchive();
		// if($zip->open($filePath."zip")) {echo"file is all good";}
		// else {echo"bad file status";}
		// exit("deadend debug");
		if(! $zip->open($filePath.".zip")) {//default
			exit("here");//buttonDebug
			return FALSE;//default
		}//default
		// $zip->addFile($filePath.'.csv',$fileName.'.csv');
		// $zip->close();
		// if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}
		header('Pragma: public'); 	// required
		header('Expires: 0');		// no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($filePath.'.csv')).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.basename($filePath.'.csv').'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($filePath.'.csv'));	// provide file size
		header('Connection: close');
		readfile($filePath.'.csv');		// push it out
		// echs;
		exit("");
		// return $filePath.'.csv';
	}
	public function getBalance($userid,$baltype,$operator=NULL){
		$res = NULL;
		if($baltype == 'seperate'){
			if($operator==NULL) return FALSE;
			$res = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="'.$operator.'" LIMIT 1','object');
		}
		/*elseif($baltype =='single'){
			$res = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="single" LIMIT 1','object');
		}*/
		elseif($baltype =='appbal'){
			$res = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="appbal" LIMIT 1','object');
		}
		if($res == NULL) return 0;
		else{
			return $res[0]->amount;
		}
	}
	public function updateBalance($userid,$unit,$baltype,$operator=NULL){
		if($userid ==1 ) return TRUE;
		if($baltype == 'seperate'){
			if($operator==NULL) return FALSE;
			$type = $operator;
			$res = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="'.$operator.'" LIMIT 1','object');
		}
		/*elseif($baltype =='single'){
			$type = 'single';
			$res = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="single" LIMIT 1','object');
		}*/
		elseif($baltype =='appbal'){
			$type = 'appbal';
			$res = $this->curd_m->get_search('SELECT * FROM user_balance WHERE fld_user_id='.$userid.' AND balance_type="appbal" LIMIT 1','object');
		}
		if($res == NULL){
			return $this->curd_m->get_insert('user_balance',array('fld_user_id'=>$userid,'balance_type'=>$type,'amount'=>$unit ));
		}
		else{
			$this->db->where('fld_user_id', $userid);
			$this->db->where('balance_type', $type);
			$this->db->update('user_balance', array('amount' => $unit));
			return $this->db->affected_rows() >0;
		}
	}
	public function checkBalance($balanceType,$amount,$oper=NULL,$userId=NULL){
		$userId = ($userId!=NULL)?$userId: $this->session->userdata('userId');
		if($balanceType == 'seperate'){
			$balance = (int)$this->getBalance($userId,$balanceType,$oper);
			if( $amount > $balance ){
				$opName = $this->curd_m->getData('operator',array('fld_int_id'=>$oper),'object');
				return 'Insufficient Balance of '.strtoupper($opName[0]->acronym).' By '.($amount- $balance).' Units';
			}
			return $balance;
		}
		/*if($balanceType == 'single'){
			$balance = (int)$this->common_m->getBalance($this->session->userdata('userId'),$balanceType,NULL);
			if( $amount > $balance ){
				return 'Insufficient Balance By '.($amount- $balance).' Units';
			}
			return TRUE;
		}*/
	}
	public function insert_notice($message ,$type, $action_by,$actin_to=NULL){
		$array = array('addressbook'=>1,'quejob'=>2,'senderid'=>3,'operator'=>4,'prefix'=>5,'group'=>6,'country'=>7,'key'=>8);
		return $this->curd_m->get_insert('notice',array(
												'fld_int_id'=>uniqid(),
												'action_by'=> $action_by,
												'action_to'=>($actin_to==NULL)?0:$actin_to,
												'description'=>$message,
												'date'=>time(),
												'type'=>$array[$type],
												));
	}
	public function cronState(){
		$res = $this->curd_m->get_search("SELECT * FROM system_flag WHERE fld_type IN ('push_api', 'que_job') AND fld_val=0",'object');
		if($res ==NULL) return TRUE;
		else{
			return FALSE;
		}
	}
	public function todayNotification($priv){
		$this->priv = $priv;
		date_default_timezone_set("Asia/Kathmandu");
		$userid = $this->session->userdata('userId');
		$query = 'SELECT *  FROM activity_log WHERE user_id='.$userid.' AND date BETWEEN '.strtotime(date("Y/m/d"))." AND ".time();
		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
								'callback'=>array($this,'todayNoticeCalback'),
								'query'=>$query,
								'rows'=>'fld_int_id,date,description',
							));
	}
	public function todayNoticeCalback($item){
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date')));
	}
	public function searchNotice($data){
		$query = '';
		$this->priv = $data['priv'];
		$userid =($data['userid']!=NULL) ?$data['userid']:$this->session->userdata('userId');
		date_default_timezone_set("Asia/Kathmandu");
		$query = 'SELECT *  FROM activity_log WHERE user_id='.$userid.' AND date BETWEEN '.$data['from']." AND ".$data['till'];
		/*if($userid ==1){
			$query = 'SELECT n.fld_int_id AS id, n.description AS description, n.action_by AS actionBy,n.date AS date, n.action_to AS actionTo  FROM notice n INNER JOIN users u WHERE ( n.action_to='.$userid.' OR n.action_by='.$userid.' ) AND u.id=n.action_by AND n.date BETWEEN '.$data['from']." AND ".$data['till'];
		}
		else{
			$query = 'SELECT n.fld_int_id AS id, n.description AS description, n.action_by AS actionBy,n.date AS date, n.action_to AS actionTo  FROM notice n INNER JOIN users u WHERE ( n.action_to='.$userid.' OR n.action_by='.$userid.') AND u.id=n.action_by AND n.date BETWEEN '.$data['from']." AND ".$data['till'];
		}*/
		if($data['type']!=NULL) $query .= ' AND type = '.$data['type'];
		$this->dhxload->dhxDynamicLoad(array(
								'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
								'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
								'callback'=>array($this,'todayNoticeCalback'),
								'query'=>$query.' ORDER BY date DESC',
								'rows'=>'fld_int_id,date,description',
							));
	}
	public function noticeViewState($id){
		$id = array_chunk(explode('_',$id),15);
		foreach($id as $listId){
			$idImploded = '"'.implode('","', $listId).'"';
			$query = $this->db->query('UPDATE activity_log SET view =1 WHERE fld_int_id IN ('.$idImploded .')');
			if($this->db->affected_rows() == 0 ) return 'Error : Unable to proceed action';
		}
		return 'sucess';
	}
	public function setNotice($type,$operatoin,$userid,$clientid,$priv,$extraInfo=NULL){
		$array = array('addressbook'=>1,'quejob'=>2,'senderid'=>3,'key'=>8);
		$operType = array('enable'=>'enabled','disable'=>'disabled','delete'=>'deleted','approve'=>'approved','disapprove'=>'disapproved','request'=>'requested','add'=>'added','remove'=>'removed');
		$userName = array();
		$name = $this->curd_m->get_search('SELECT id, company FROM users WHERE id IN('.$userid.','.$clientid.')','object');
		foreach($name as $row){
			$userName[$row->id] = $row->company;
		}
		$data = array();
		if($type== 'quejob'){
			if(in_array('USER_MANAGE',$priv)){
				$data = array(array(
								'user_id'=>$userid,
								'description'=>'Que Job '.$operType[$operatoin].' of '.$userName[$clientid].' // '.(($extraInfo==NULL)?'':$extraInfo) ,
								'date'=>time(),
								'type'=>$array[$type],
								),array(
								'user_id'=>$clientid,
								'description'=>'Que Job '.$operType[$operatoin].' by Admin '.(($userid > 1)?'[ '.$userName[$userid].' ]':'').'//'.(($extraInfo==NULL)?'':$extraInfo),
								'date'=>time(),
								'type'=>$array[$type],
								)
							);
			}else{
				$data = array(array(
								'user_id'=>$userid,
								'description'=>'Que Job '.$operType[$operatoin].' by [ Self ]'.'//'.(($extraInfo==NULL)?'':$extraInfo),
								'date'=>time(),
								'type'=>$array[$type],
								)
							);
			}
		}
		elseif($type=='addressbook'){
			$description = '';
			if($operatoin =='contact_add')
				$description ='New Contact Added in Addressbook [ '.$extraInfo.' ]';
			elseif($operatoin =='contact_edit' || $operatoin =='contact_delete' || $operatoin =='contact_upload' || $operatoin =='address_delete' || $operatoin =='edit')
				$description =$extraInfo;
			elseif($operatoin =='new' )
				$description ='New Addressbook Created [ '.$extraInfo.' ]';
			$data = array(array(
								'user_id'=>$userid,
								'description'=>$description,
								'date'=>time(),
								'type'=>$array[$type],
								)
							);
		}
		elseif($type=='senderid'){
			if($operatoin == 'request'){
				if($this->session->userdata('userId')==1){
					$data = array(array(
								'user_id'=>1,
								'description'=>'Default Sender ID [ '.$extraInfo.' ] added',
								'date'=>time(),
								'type'=>$array[$type],
								)
							);
				}else{
				$data = array(array(
								'user_id'=>1,
								'description'=>'Sender ID [ '.$extraInfo.' ] requested by '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								),array(
								'user_id'=>$userid,
								'description'=>'Sender ID [ '.$extraInfo.' ] requested by '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								),array(
								'user_id'=>$clientid,
								'description'=>'Sender ID [ '.$extraInfo.' ] requested',
								'date'=>time(),
								'type'=>$array[$type],
								)
							);
				}
			}
			elseif($operatoin=='upload'){
				$data = array(array(
								'user_id'=>1,
								'description'=>$extraInfo.' by '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								),array(
								'user_id'=>$userid,
								'description'=>$extraInfo.' by '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								),array(
								'user_id'=>$clientid,
								'description'=>$extraInfo,
								'date'=>time(),
								'type'=>$array[$type],
								)
							);
			}
			elseif($operatoin=='approve' ||  $operatoin=='disapprove' || $operatoin=='delete' ){
				$data = array(array(
								'user_id'=>$userid,
								'description'=>'Sender ID [ '.$extraInfo.' ] '.$operType[$operatoin].' of '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								'view'=>1
								),array(
								'user_id'=>$clientid,
								'description'=>'Sender ID [ '.$extraInfo.' ] '.$operType[$operatoin].' by Admin '.(($userid > 1)?'[ '.$userName[$userid].' ]':''),
								'date'=>time(),
								'type'=>$array[$type],
								'view'=>0
								)
							);
			}
		}
		elseif($type=='key'){
			if($operatoin=='add' ){
				$data = array(array(
								'user_id'=>1,
								'description'=>'New Key [ '.$extraInfo.' ] requested by '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								),array(
								'user_id'=>$userid,
								'description'=>'New Key [ '.$extraInfo.' ] requested by '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								),array(
								'user_id'=>$clientid,
								'description'=>'New Key [ '.$extraInfo.' ] requested',
								'date'=>time(),
								'type'=>$array[$type],
								)
							);
			}
			elseif($operatoin=='disable' || $operatoin=='enable'){
				if(in_array('USER_MANAGE',$priv)){
					$data = array(array(
								'user_id'=>$userid,
								'description'=>'Key [ '.$extraInfo.' ] '.$operType[$operatoin].' of '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								'view'=>1
								),array(
								'user_id'=>$clientid,
								'description'=>'Key [ '.$extraInfo.' ] '.$operType[$operatoin].' by Admin // '.(($userid==1)?'':$userName[$userid]),
								'date'=>time(),
								'type'=>$array[$type],
								'view'=>0
								)
							);
				}else{
					$data = array(array(
								'user_id'=>$clientid,
								'description'=>'Key [ '.$extraInfo.' ] '.$operType[$operatoin],
								'date'=>time(),
								'type'=>$array[$type],
								'view'=>1
								)
							);
				}
			}
			elseif($operatoin=='delete'){
				$data = array(array(
								'user_id'=>$userid,
								'description'=>'Key [ '.$extraInfo.' ] deleted of '.$userName[$clientid],
								'date'=>time(),
								'type'=>$array[$type],
								'view'=>1
								),array(
								'user_id'=>$clientid,
								'description'=>'Key [ '.$extraInfo.' ] deleted by Admin',
								'date'=>time(),
								'type'=>$array[$type],
								'view'=>0
								)
							);
			}
			elseif($operatoin=='delete_request'){
				if(in_array('USER_MANAGE',$priv)){
					$data = array(array(
									'user_id'=>$userid,
									'description'=>'Requested Key [ '.$extraInfo.' ] deleted of '.$userName[$clientid],
									'date'=>time(),
									'type'=>$array[$type],
									'view'=>1
									),array(
									'user_id'=>$clientid,
									'description'=>'Requested Key [ '.$extraInfo.' ] deleted by Admin // '.(($userid==1)?'':$userName[$userid]),
									'date'=>time(),
									'type'=>$array[$type],
									'view'=>0
									)
								);
				}else{
					$data = array(array(
									'user_id'=>$clientid,
									'description'=>'Requested Key [ '.$extraInfo.' ] deleted ',
									'date'=>time(),
									'type'=>$array[$type],
									'view'=>1
									)
								);
				}
			}
		}
		$this->curd_m->get_insert('activity_log',$data,'batch');
	}
// model ends
}