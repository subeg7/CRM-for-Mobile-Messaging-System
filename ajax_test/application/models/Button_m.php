<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Button_m	 extends CI_Model
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
	public function renderSenderId($data){
		$userid = $this->session->userdata('userId');
		$query = NULL; $rows=NULL;
		$this->priv = $data['privileges'];
		$searchArr = array();


		//user prev check
		if($userid == 1){
			$query = 'SELECT s.fld_int_id AS id,u.company AS reqestby,s.fld_description AS descript,u.fld_reseller_id AS resname,s.fld_chr_senderid AS senderid,s.fld_int_default AS spriority,o.acronym AS operator,g.fld_char_gw_name AS gname,g.fld_int_priority AS gpriority, s.fld_int_request AS state,s.fld_chr_ondate AS date FROM easy_senderid s INNER JOIN gateway g INNER JOIN operator o INNER JOIN users u WHERE s.fld_int_userid=u.id AND s.fld_gateway=g.fld_int_id AND s.operator=o.fld_int_id ';

			$rows = "id,reqestby,senderid,descript,gateway,state,date,spriority";
			$prinRowsName = 'Request By/Reseller Name,Sender ID,Description,Operator/Gateway,State,Date,Priority';
		}
		elseif(in_array('USER_MANAGE',$data['privileges'])){
			$reseller_id = 39;
			$query = 'SELECT u.company AS reqby, s.fld_int_id AS id,s.fld_chr_senderid AS senderid,o.acronym AS operator,g.fld_char_gw_name AS gname,g.fld_int_priority AS gpriority, s.fld_int_request AS state,s.fld_chr_ondate AS date FROM easy_senderid s INNER JOIN gateway g INNER JOIN operator o INNER JOIN users u WHERE s.fld_int_userid=u.id AND s.fld_gateway=g.fld_int_id AND s.operator=o.fld_int_id AND u.fld_reseller_id='.$reseller_id;
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

}



	public function renderReport($data){
		$userid = $this->session->userdata('userId');
		$query = NULL; $rows=NULL;
		$this->priv = $data['privileges'];
		$searchArr = array();

			if($userid==1){
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company,r.company AS rcompany FROM outbox o INNER JOIN users u INNER JOIN users r WHERE o.fld_reseller_id=r.id AND o.fld_int_userid=u.id AND o.fld_int_ondate BETWEEN 0'." AND ".time()." ORDER BY o.fld_int_ondate DESC";
				$rows = 'fld_int_id,xcompany,fld_chr_sender,fld_chr_message,fld_user_data';
			}
			elseif(in_array('USER_MANAGE',$data['privileges'])){
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company FROM outbox o INNER JOIN users u WHERE o.fld_reseller_id='.$userid.' AND u.id = o.fld_int_userid AND o.fld_int_ondate BETWEEN 0 AND '.time()." ORDER BY o.fld_int_ondate DESC";
				$rows = 'fld_int_id,company,fld_chr_sender,fld_chr_message,fld_user_data';
			}
			elseif(!in_array('USER_MANAGE',$data['privileges'])){
				$reseller_id = 39;
				$query ='SELECT o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company FROM outbox o INNER JOIN users u WHERE o.fld_reseller_id='.$reseller_id.' AND u.id = o.fld_int_userid AND o.fld_int_ondate BETWEEN 0 AND '.time()." ORDER BY o.fld_int_ondate DESC";
				// $rows = 'fld_int_id,fld_chr_sender,fld_chr_message,fld_user_data';//default
				$rows = 'fld_int_id,fld_chr_sender,fld_chr_message,fld_user_data';
				$prinRowsName = 'Sender Id,Message,Type/Count,Cell No.,Date,No-user_manage_priv';

			}



		if($data['type']=='download' || $data['type']=='search'){
			$like = '';
			$query =$query.$like;



			//die(var_dump($query));
			if($data['type']=='download'){
				$res = $this->dhxload->getCsvData(array(
											// 'callback'=>array($this,'senderidCalback'),
											'query'=>$query,
											'rows'=>$rows,
											'prinRowsName'=>$prinRowsName
											));

											// echo"calling the dhx load sucess";
											// 	exit("sdfs");


				$folderName = $this->curd_m->getData('users',array('id'=>$this->session->userdata('userId') ),'object');





				if($res != NULL) $res = $this->common_m->getExcecl($res, $folderName[0]->fld_transaction_id);
				die( var_dump($res));
			}
		}


}

}
