<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pull_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('curd_m');
		$this->load->library('dhxload','vas');
		$this->priv = NULL;
	}

	/*************start of render functions*************/
	public function renderShortcode(){
		$query = ($this->session->userdata('userId')==1)?"SELECT * FROM shortcode ORDER BY fld_int_id ASC"
					:"SELECT s.fld_int_id AS fld_int_id,s.fld_chr_name AS fld_chr_name, s.fld_chr_description AS fld_chr_description FROM shortcode s INNER JOIN user_shortcode u WHERE u.fld_user_id=".$this->session->userdata('userId')." AND u.fld_shortcode_id=s.fld_int_id ORDER BY s.fld_int_id ASC";
		$row = ($this->session->userdata('userId')==1)? "fld_int_id,fld_chr_name,fld_chr_description,assign_to":"fld_int_id,fld_chr_name,fld_chr_description";
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'shortcodeCalback'),
									'query'=>$query,
									'rows'=>$row,
								   ));
	}

	public function shortcodeCalback($item){
		$item->set_value('fld_chr_description',ucwords($item->get_value('fld_chr_description')));
		if($item->get_value('assign_to')){
			if($item->get_value('assign_to')!=1){
				$res = $this->curd_m->get_search('SELECT company FROM users WHERE id='.$item->get_value('assign_to'),'object');
				if($res==NULL){
					$item->set_value('assign_to','None');
				}
				else{
					$item->set_value('assign_to',ucwords($res[0]->company));
				}
			}
			else{
				$item->set_value('assign_to','None');
			}
		}

	}
	public function renderFeedback($id,$from,$till){
		date_default_timezone_set("Asia/Kathmandu");
		$from = strtotime(date('Y-m-d',$from)) ;
		$till = strtotime(date('Y-m-d',$till))+86400 ;
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'errorCalback'),
									'query'=>'SELECT p.fld_int_id AS fld_int_id, p.sender AS sender,p.text AS text,o.acronym AS operator FROM pull_report p INNER JOIN operator o WHERE p.mainkey ='.$id.' AND o.fld_int_id=p.operator AND p.mo_mt = 1 AND p.error = 0 AND p.date BETWEEN '.$from.' AND '.$till,
									'rows'=>"fld_int_id,sender,operator,text",
								   ));

	}
	public function renderError($id,$type,$from,$till){
		$query = ($type=='keys')?'p.mainkey ='.$id:'p.shortcode ='.$id;
		date_default_timezone_set("Asia/Kathmandu");
		$from = strtotime(date('Y-m-d',$from)) ;
		$till = strtotime(date('Y-m-d',$till))+86400 ;
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'errorCalback'),
									'query'=>'SELECT p.fld_int_id AS fld_int_id,p.sender AS sender,p.text AS text,o.acronym AS operator,p.error_detail AS error_detail FROM pull_report p INNER JOIN operator o WHERE '.$query.' AND p.operator = o.fld_int_id AND p.mo_mt = 1 AND p.error = 1 AND p.date BETWEEN '.$from.' AND '.$till,
									'rows'=>"fld_int_id,sender,operator,text,error_detail",
								   ));
	}
	public function errorCalback($item){
		$item->set_value('operator','<span style="color:blue;">'.strtoupper($item->get_value('operator')).'</span>');
	}
	public function renderUpload($id,$uniqueid,$counts){
		$query = "SELECT * FROM upload_data WHERE key_id = ".$id;
		if($uniqueid!=NULL && $uniqueid!='none') $query.= ' AND identity="'.$uniqueid.'" ';
		if($counts!=NULL){
			if($counts=='over_single') $query.= ' AND count > 1 ';
			else $query.= ' AND count = 1';

		}

		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									//'callback'=>array($this,'uploadCallback'),
									'query'=>$query." ORDER BY fld_int_id ASC",
									'rows'=>"fld_int_id,identity,message,count",
								   ));
	}
	public function renderCategory(){
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'categoryCallback'),
									'query'=>"SELECT * FROM category ORDER BY fld_int_id ASC",
									'rows'=>"fld_int_id,category,description,upload_data",
								   ));
	}
	public function categoryCallback($item){
		$item->set_value('description',ucwords($item->get_value('description')));
		$item->set_value('category',strtoupper($item->get_value('category')));
		if($item->get_value('upload_data')== 2) $item->set_value('upload_data','<span style="color:red;">Non-Uploadable</span>');
		elseif($item->get_value('upload_data')== 1) $item->set_value('upload_data','<span style="color:green;">Uploadable</span>');
	}
	public function renderKeyTree(){
		$res = $this->curd_m->get_search('SELECT p.fld_int_id AS id,p.keys_name AS name,s.fld_chr_name AS sname FROM pull_keys p INNER JOIN category c INNER JOIN shortcode s WHERE p.fld_category_id=c.fld_int_id AND c.upload_data=1 AND s.fld_int_id=p.fld_shortcode_id AND p.fld_user_id='.$this->session->userdata('userId'),'object');
		$keysData= '<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1"><item   text="IS Key Lists" id="keys" open="1">';
		if($res!=NULL){
			foreach($res as $row){
				$sname = $row->name.(string) htmlspecialchars(" <span style='color:blue;'>( ".$row->sname." )</span>");
				$keysData.='<item text="'.$sname.'" id="'.$row->id.'"  im0="keys.png" ></item>';
			}
		}
        $keysData .='</item></tree>';
		die($keysData);
	}
	public function renderKeys($data){
		$userid = $this->session->userdata('userId');
		$query= NULL;
		$searchArr = array();
		$this->priv = $data['privileges'];
		if($userid == 1){
			$query = "SELECT u.company AS user,k.company AS rese, p.fld_int_id AS id,p.keys_name AS kname,p.date AS date,p.state AS status,s.fld_chr_name AS sname,c.category AS cname FROM pull_keys p INNER JOIN category c INNER JOIN shortcode s INNER JOIN users u INNER JOIN users k WHERE s.fld_int_id = p.fld_shortcode_id AND c.fld_int_id = p.fld_category_id AND p.main_keys_id=0 AND p.fld_user_id=u.id AND u.fld_reseller_id = k.id AND p.state > 0";
			$rows = "id,kname,user,rese,sname,cname,date,status";
			$prinRowsName = 'kname,user,rese,sname,cname,date,status';
		}
		elseif(in_array('USER_MANAGE',$data['privileges']) ){
			$query = "SELECT u.company AS user, p.fld_int_id AS id,p.keys_name AS kname,p.date AS date,p.state AS status,s.fld_chr_name AS sname,c.category AS cname FROM pull_keys p INNER JOIN category c INNER JOIN shortcode s INNER JOIN users u WHERE s.fld_int_id = p.fld_shortcode_id AND c.fld_int_id = p.fld_category_id AND p.main_keys_id=0 AND u.fld_reseller_id=".$userid." AND p.fld_user_id=u.id AND p.state > 0";
			$rows = "id,kname,user,sname,cname,date,status";
			$prinRowsName = 'kname,user,sname,cname,date,status';
		}
		elseif(in_array('PULL',$data['privileges']) ){
			$query = "SELECT p.fld_int_id AS id,p.keys_name AS kname,p.date AS date,p.state AS status,s.fld_chr_name AS sname,c.category AS cname FROM pull_keys p INNER JOIN category c INNER JOIN shortcode s WHERE s.fld_int_id = p.fld_shortcode_id AND c.fld_int_id = p.fld_category_id AND p.main_keys_id=0 AND p.fld_user_id=".$userid." AND p.state > 0";
			$rows = "id,kname,sname,cname,date,status";
			$prinRowsName = 'kname,sname,cname,date,status';
		}
		if( $data['type']=='search' || $data['type']=='operate'){
			$like = '';
			if($data['state']!==NULL ){
				$like .=($data['state']=='enable')?' AND p.state = 1':' AND p.state >1';
				$searchArr[]='state='.$data['state'];
			}
			if($data['shortcode']!==NULL){
				$like .=' AND p.fld_shortcode_id ='.$data['shortcode'];
				$searchArr[] = 'shortcode='.$data['shortcode'];
			}
			if($data['category']!==NULL){
				$like .=' AND p.fld_category_id ='.$data['category'];
				$searchArr[] = 'category='.$data['category'];
			}
			if($data['keyname']!==NULL){
				$like .=' AND p.keys_name LIKE "'.$data['keyname'].'%" ';
				$searchArr[] = 'keyname='.$data['keyname'];
			}
			$query =$query.$like;
		}

		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'keysCallback'),
									'query'=>$query.' ORDER BY p.fld_int_id ASC',
									'rows'=>$rows,
									'userdata'=>(sizeof($searchArr)>0)?implode('__',$searchArr):''
								   ));
	}
	public function keysCallback($item){
		$item->set_value('cname',strtoupper($item->get_value('cname')));
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date') ) );
		if($item->get_value('status')==1) $item->set_value('status','<span style="color:green;">Enabled</span>');
		else{
			if($item->get_value('status')==9){
				$item->set_value('status','<span style="color:blue;">Requested</span>');
			}else{
				if($this->session->userdata('userId') == 1){
					if($item->get_value('status')==2){
						$item->set_value('status','<span style="color:red;">Disabled <span style="color:green;">[ Client ]</span></span>');
					}
					elseif($item->get_value('status')==3){
						$item->set_value('status','<span style="color:red;">Disabled <span style="color:green;">[ Reseller ]</span></span>');
					}
					elseif($item->get_value('status')==4){
						$item->set_value('status','<span style="color:red;">Disabled <span style="color:green;">[ Self ]</span></span>');
					}
				}
				elseif(in_array('PULL',$this->priv)){
					if($item->get_value('status')==2){
						$item->set_value('status','<span style="color:red;">Disabled</span>');
					}
					elseif($item->get_value('status')>2){
						$item->set_value('status','<span style="color:red;">Disabled <span style="color:green;">[ Admin ]</span></span>');
					}
				}
				elseif(in_array('USER_MANAGE',$this->priv)  && !in_array('PULL',$this->priv)){
					if($item->get_value('status')==2){
						$item->set_value('status','<span style="color:red;">Disabled <span style="color:green;">[ Client ]</span></span>');
					}
					elseif($item->get_value('status')==3){
						$item->set_value('status','<span style="color:red;">Disabled <span style="color:green;">[ Self ]</span></span>');
					}
					elseif($item->get_value('status')==4){
						$item->set_value('status','<span style="color:red;">Disabled <span style="color:green;">[ Admin ]</span></span>');
					}
				}
			}
		}

	}
	public function renderScheme(){
		$userid = $this->session->userdata('userId');
		$query = 'SELECT fld_int_id AS id ,scheme_name AS name,scheme AS sche,detail AS detail, date AS date FROM scheme  WHERE fld_user_id='.$this->session->userdata('userId').' ORDER BY fld_int_id ASC';
		$rows = 'id,name,date,sche,detail';
		$this->dhxload->dhxDynamicLoad(array(
									'posStart'=>(isset($_GET["posStart"]) )?$_GET['posStart']:0,
									'count'=>(isset($_GET["count"]) )?$_GET['count']:10,
									'callback'=>array($this,'schemeCallback'),
									'query'=>$query,
									'rows'=>$rows,
									//'userdata'=>(sizeof($searchArr)>0)?implode('__',$searchArr):''
								   ));
	}
	public function schemeCallback($item){
		date_default_timezone_set("Asia/Kathmandu");
		$item->set_value('date',date('Y-m-d H:i:s',$item->get_value('date') ) );
		$item->set_value('uname',ucwords($item->get_value('uname') ) );
		$item->set_value('name',ucwords($item->get_value('name') ) );
	}
	public function renderTreeList($type,$priv,$err=NULL){
		$xml = ''; $query = '';
		$user_id = $this->session->userdata('userId');

		if($type=='shortcode'){
			if($user_id==1){
				$query = 'SELECT * FROM shortcode';
			}else{
				if($err == NULL){
					$query = 'SELECT s.fld_int_id AS fld_int_id,s.fld_chr_name AS fld_chr_name FROM user_shortcode u INNER JOIN shortcode s WHERE u.fld_shortcode_id=s.fld_int_id AND u.fld_user_id = '.$user_id;
				}
				else{ // error report ,only for dedicated shortcode
					$query = 'SELECT fld_int_id, fld_chr_name FROM shortcode  WHERE assign_to = '.$user_id;
				}
			}
			$sh = $this->curd_m->get_search($query,'object');
			$xml .='<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1"><item text="Shortcode Lists" id="list" open="1">';
			if($sh!= NULL){
				foreach($sh as $row){
					$xml .= '<item text="'.(string) htmlspecialchars(ucwords($row->fld_chr_name) ).'" id="'.$row->fld_int_id.'"  im0="shortcode.png" ></item>';
				}
			}


		}
		elseif($type=='user'){
			if($user_id == 1){
				$query = 'SELECT DISTINCT u.id AS id, u.company AS company FROM users u INNER JOIN pull_keys k WHERE k.fld_user_id = u.id';
			}
			elseif(in_array('USER_MANAGE',$priv)){ // for reseller
				$query = 'SELECT DISTINCT u.id AS id, u.company AS company FROM users u INNER JOIN pull_keys k WHERE k.fld_user_id = u.id AND u.fld_reseller_id = '.$user_id;
			}
			$us = $this->curd_m->get_search($query,'object');
			$xml .='<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1"><item text="User Lists" id="list" open="1">';
			if($us!==NULL){
				foreach($us as $row){
					$xml .= '<item text="'.(string) htmlspecialchars( ucwords($row->company) ).'" id="'.$row->id.'"  im0="users.png" ></item>';
				}
			}
		}
		elseif($type == 'keys'){
			if($user_id==1){
				$query = 'SELECT k.fld_int_id AS id, k.keys_name AS kname,s.fld_chr_name AS sname,c.category AS cname FROM pull_keys k INNER JOIN shortcode s INNER JOIN category c WHERE k.main_keys_id =0 AND s.fld_int_id= k.fld_shortcode_id AND k.fld_category_id= c.fld_int_id AND k.state > 0';
			}
			elseif(in_array('USER_MANAGE',$priv)){ // for reseller
				$query = 'SELECT k.fld_int_id AS id, k.keys_name AS kname,s.fld_chr_name AS sname,c.category AS cname FROM pull_keys k INNER JOIN shortcode s INNER JOIN category c INNER JOIN users u WHERE k.main_keys_id =0 AND s.fld_int_id= k.fld_shortcode_id AND k.fld_category_id= c.fld_int_id AND u.id = k.fld_user_id AND k.state > 0 AND u.fld_reseller_id ='.$user_id;
			}
			elseif(!in_array('USER_MANAGE',$priv)){ // for clients
				$query = 'SELECT k.fld_int_id AS id, k.keys_name AS kname,s.fld_chr_name AS sname,c.category AS cname FROM pull_keys k INNER JOIN shortcode s INNER JOIN category c WHERE k.main_keys_id =0 AND k.state > 0 AND s.fld_int_id= k.fld_shortcode_id AND k.fld_category_id= c.fld_int_id AND k.fld_user_id='.$user_id;
			}
			$key = $this->curd_m->get_search($query,'object');
			$xml .='<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1"><item text="Key Lists" id="list" open="1">';
			if($key!==NULL){
				foreach($key as $row){
					$xml .= '<item text="'.(string) htmlspecialchars(ucwords($row->kname).'<span style="color:blue;"> ( '.$row->sname.' '.strtoupper($row->cname).' )</span>') .'" id="'.$row->id.'"  im0="keys.png" ></item>' ;
				}
			}
		}
		$xml .= '</item></tree>';
		header("Content-type: text/xml");
		die($xml);
	}
	public function getFeedbackList($userid,$priv){
		$query = NULL;
		if($userid == 1){
			$query = 'SELECT k.keys_name AS kname,s.fld_chr_name AS sname, k.fld_int_id AS id FROM pull_keys k INNER JOIN category c INNER JOIN shortcode s WHERE c.fld_int_id = k.fld_category_id AND c.category ="fb" AND s.fld_int_id = k.fld_shortcode_id AND k.main_keys_id=0';
		}
		elseif(in_array('USER_MANAGE',$priv)){
			$query = 'SELECT k.keys_name AS kname,s.fld_chr_name AS sname, k.fld_int_id AS id FROM pull_keys k INNER JOIN category c INNER JOIN shortcode s INNER JOIN users u WHERE c.fld_int_id = k.fld_category_id AND c.category ="fb" AND s.fld_int_id = k.fld_shortcode_id AND k.main_keys_id=0 AND u.id = k.fld_user_id AND u.fld_reseller_id = '.$userid;
		}
		elseif(!in_array('USER_MANAGE',$priv)){
			$query = 'SELECT k.keys_name AS kname,s.fld_chr_name AS sname, k.fld_int_id AS id FROM pull_keys k INNER JOIN category c INNER JOIN shortcode s WHERE c.fld_int_id = k.fld_category_id AND c.category ="fb" AND s.fld_int_id = k.fld_shortcode_id AND k.main_keys_id=0 AND k.fld_user_id = '.$userid;
		}

		$fb =  $this->curd_m->get_search($query,'object');
		$xml ='<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1"><item text="Feedback Key Lists" id="list" open="1">';
		if($fb!==NULL){
			foreach($fb as $row){
				$xml .= '<item text="'.(string) htmlspecialchars(ucwords($row->kname).'<span style="color:blue;"> ( '.$row->sname.' )</span>') .'" id="'.$row->id.'"  im0="keys.png" ></item>' ;
			}
		}
		$xml .= '</item></tree>';
		header("Content-type: text/xml");
		die($xml);
	}
	/*************end of render functions*************/
	public function shortcode($data, $type ,$id=NULL){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update

		foreach($data as $key=>$val){
				if($val !==NULL){
					$arr[$key] = $val;
				}
				else{  unset($data[$key]);	}
		}
		$res = (isset($data['fld_chr_name']))?$this->curd_m->checkRecur('shortcode',array('fld_chr_name__Shortcode'=>$data['fld_chr_name'])):TRUE;
		if($type=='new' && $res === TRUE){
			return ($this->curd_m->get_insert('shortcode',$arr)!==FALSE)?TRUE:FALSE;
		}
		elseif($type=='edit' && $res === TRUE){
			if($id==NULL || $id == FALSE) return '** No item found to edit ';

			return ($this->curd_m->get_update('shortcode',$arr))?TRUE:'** Update error ';
		}
		else return $res;
	}
	public function category($data, $type ,$id=NULL){
		$arr = ( $type == 'new')? array() : array('fld_int_id'=>$id);// if not new it assign first element for update

		foreach($data as $key=>$val){
				if($val !==NULL){
					$arr[$key] = $val;
				}
				else{  unset($data[$key]);	}
		}
		$res = (isset($data['category']))?$this->curd_m->checkRecur('category',array('category__Category'=>$data['category'])):TRUE;
		if($type=='new' && $res === TRUE){
			return ($this->curd_m->get_insert('category',$arr)!==FALSE)?TRUE:FALSE;
		}
		elseif($type=='edit' && $res === TRUE){
			if($id==NULL || $id == FALSE) return '** No item found to edit ';

			return ($this->curd_m->get_update('category',$arr))?TRUE:'** Update error ';
		}
		else return $res;
	}
	public function assignShortcode($assignTo,$shortcode,$assignType ){
		$query = $this->curd_m->get_search('SELECT * FROM user_shortcode WHERE fld_user_id='.$assignTo.' AND fld_shortcode_id='.$shortcode);
		if($query!== NULL)return '**Warning : Shortcode Already Assigned';
		if($assignType=='dedicated'){
			$query = $this->curd_m->get_search('SELECT * FROM user_shortcode WHERE fld_shortcode_id='.$shortcode);
			if($query!== NULL)return '**Warning : Shortcode Already Assigned to Other Users';

			if($this->curd_m->get_update('shortcode',array('fld_int_id'=>$shortcode,'assign_to'=>$assignTo))){
				$res = $this->curd_m->get_insert('user_shortcode',array('fld_user_id'=>$assignTo,'fld_shortcode_id'=>$shortcode,'assign_type'=>$assignType));
				return ($res===FALSE)?'**Error : Operation Fail':TRUE;
			}
			return '**Error : Operation Fail';
		}
		elseif($assignType=='normal'){
			$res = $this->curd_m->get_insert('user_shortcode',array('fld_user_id'=>$assignTo,'fld_shortcode_id'=>$shortcode,'assign_type'=>$assignType));
			return ($res===FALSE)?'**Error : Operation Fail':TRUE;
		}
	}
	public function removeShortcode($assignTo,$shortcode,$assignType ){

		if($assignType=='dedicated'){
			if($this->curd_m->get_update('shortcode',array('fld_int_id'=>$shortcode,'assign_to'=>1))){
				$this->db->where('fld_shortcode_id',$shortcode);
				$this->db->where('fld_user_id', $assignTo);
				$this->db->delete('user_shortcode');
				return ($this->db->affected_rows() > 0)?TRUE: '**Error : Operation Fail';
			}
			return '**Error : Operation Fail';
		}
		elseif($assignType=='normal'){
			$this->db->where('fld_shortcode_id',$shortcode);
			$this->db->where('fld_user_id', $assignTo);
			$this->db->delete('user_shortcode');
			return ($this->db->affected_rows() > 0)?TRUE: '**Error : Operation Fail';
		}
	}

	public function operateKeys($type,$id, $priv){
		$state = ($type=='disable')?(($this->session->userdata('userId')==1)?4:((in_array('PULL',$priv))?2:3)): 1;
		$data = $this->curd_m->get_search('SELECT * FROM pull_keys WHERE fld_int_id ='.$id,'object');
		if($data===NULL){
			return '** Error : Unable to Update Key State';
		}else{
			if($data[0]->state == 9 && $this->session->userdata('userId')!=1)  return "Not enough priviliges";
			if($data[0]->state == 1 && $type=='enable') return 'Key is already enabled';
			if(in_array('PULL',$priv) && $data[0]->state > 2 && $this->session->userdata('userId')!=1){
				return "** Warning : This Key is disabled by Admin ";
			}
			elseif(in_array('USER_MANAGE',$priv) && $data[0]->state > 3 && $this->session->userdata('userId')!= 1){
				return "** Warning : This Key is disabled by Admin ";
			}
		}

		$arr = array(
					'fld_int_id'=>$id,
					'state'=>$state
					);
		$res = $this->curd_m->get_update('pull_keys',$arr);

		if($res===TRUE){
			$this->common_m->setNotice('key',$type,$this->session->userdata('userId'),$data[0]->fld_user_id,$this->userPrivileges,strtolower($data[0]->keys_name));
			return 'sucess';
		}else{ return'**Error : Unable to Update Keys State';}
	}
	public function scheme($data){
		$res = $this->curd_m->checkRecur('scheme',array('scheme_name__Scheme Name'=>$data['scheme_name']));
		if($res===TRUE){
			$res1 = $this->curd_m->get_insert('scheme',$data);
			if($res1!==FALSE) return 'sucess';
			else return '** Error : Unable to Add new scheme';
		}
		else return $res;
	}

	public function getPullReport( $type , $id ,$from,$till,$priv ){
	//	die(var_dump($type));
		$query ='';
		$userid = $this->session->userdata('userId');
		date_default_timezone_set("Asia/Kathmandu");
		$from = strtotime(date('Y-m-d',$from)) ;
		$till = strtotime(date('Y-m-d',$till))+86400 ;

		if($type =='shortcode'){
			if($userid ==1){
				$query = 'SELECT SUM(p.count) AS count,o.acronym ,p.mo_mt AS mo_mt FROM pull_report p INNER JOIN operator o WHERE p.shortcode = '.$id.' AND o.fld_int_id = p.operator AND p.date BETWEEN '.$from.' AND '.$till.' GROUP BY p.mo_mt,p.operator';
			}
			elseif(in_array('USER_MANAGE',$priv)){
				$query = 'SELECT SUM(p.count) AS count,o.acronym ,p.mo_mt AS mo_mt FROM pull_report p INNER JOIN operator o INNER JOIN users u WHERE p.shortcode = '.$id.' AND o.fld_int_id = p.operator AND u.id = p.fld_user_id AND u.fld_reseller_id = '.$userid.' AND p.date BETWEEN '.$from.' AND '.$till.' GROUP BY p.mo_mt,p.operator';
			}
			elseif(in_array('PULL',$priv)){
				$query = 'SELECT SUM(p.count) AS count,o.acronym ,p.mo_mt AS mo_mt FROM pull_report p INNER JOIN operator o WHERE p.shortcode = '.$id.' AND o.fld_int_id = p.operator AND p.fld_user_id = '.$userid.' AND p.date BETWEEN '.$from.' AND '.$till.' GROUP BY p.mo_mt,p.operator';
			}
			$res = $this->curd_m->get_search($query,'object');
			if($res != NULL){
				return $res;
			}
			else return FALSE;
		}
		elseif($type =='keys'){
			$this->config->load('easy_config');
			$res = $this->curd_m->get_search('SELECT c.category AS category FROM pull_keys k INNER JOIN category c WHERE k.fld_category_id=c.fld_int_id AND k.fld_int_id ='.$id,'object');
			if($res!=NULL){
				if(in_array($res[0]->category,$this->config->item('subkey_category'))){
					$query = 'SELECT SUM(p.count) AS count,p.mo_mt AS mo_mt,k.keys_name AS acronym FROM pull_report p INNER JOIN pull_keys k WHERE p.mainkey ='.$id.' AND p.sub_key = k.fld_int_id AND p.date BETWEEN '.$from.' AND '.$till.' GROUP BY p.mo_mt, p.sub_key ORDER BY count DESC';
				}
				else{
					$query = 'SELECT SUM(p.count) AS count,o.acronym AS acronym,p.mo_mt AS mo_mt FROM pull_report p INNER JOIN operator o WHERE p.mainkey ='.$id.' AND p.date BETWEEN '.$from.' AND '.$till.' AND o.fld_int_id=p.operator GROUP BY p.mo_mt,p.operator';
				}
				//die($query);
				$res = $this->curd_m->get_search($query,'object');
				if($res != NULL){
					return $res;
				}
				else return FALSE;
				}
		}
		elseif($type =='user'){
			$query = 'SELECT SUM(p.count) AS count,o.acronym ,p.mo_mt AS mo_mt FROM pull_report p INNER JOIN operator o WHERE o.fld_int_id = p.operator AND p.fld_user_id = '.$id.' AND p.date BETWEEN '.$from.' AND '.$till.' GROUP BY p.mo_mt,p.operator';
			$res = $this->curd_m->get_search($query,'object');
			if($res != NULL){
				return $res;
			}
			else return FALSE;
		}
	}


// model ends
}
