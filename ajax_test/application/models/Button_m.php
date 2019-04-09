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

	// public fun



	public function renderReport($data){
		$userid = $this->session->userdata('userId');
		// echo"wow";exit();
		// print_r($this->session->userdata);
		// exit(); 
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
				// $reseller_id = 39;
				// echo "user id is:".$userid;
				// $query ='SELECT u.id as user_id , o.fld_int_id AS fld_int_id,o.fld_int_cell_no AS fld_int_cell_no,o.eom_track_id AS eom_track_id,o.fld_chr_sender AS fld_chr_sender,o.fld_chr_message AS fld_chr_message,o.fld_msg_number AS fld_msg_number,o.messageType AS messageType,o.fld_int_ondate AS fld_int_ondate,o.fld_user_data AS fld_user_data,u.company AS company 

				// 	FROM outbox o INNER JOIN users u 

				// 	WHERE o.fld_int_userid='.$userid.' AND  o.fld_int_ondate BETWEEN 0 AND '.time()." ORDER BY o.fld_int_ondate DESC";

				$query = "select fld_chr_sender as fld_chr_sender,fld_chr_message as fld_chr_message,  fld_msg_number as fld_msg_number, fld_int_cell_no  as fld_int_cell_no,fld_int_ondate as fld_int_ondate

					from outbox

				 	where fld_int_userid=".$userid;

				// exit($query);
				// $rows = 'fld_int_id,fld_chr_sender,fld_chr_message,fld_user_data';//default
				$rows = 'fld_int_id,fld_chr_sender,fld_chr_message,fld_msg_number,fld_int_cell_no,fld_int_ondate';
				$prinRowsName = 'S.N,Sender Id,Message,Char Count,Chunk Cell,Date';
				// $prinRowsName = 'Sender Id,Message,Type/Count,Cell No.,Date,No-user_manage_priv';

			}

			// echo $query;
			// exit();



		if($data['type']=='download' || $data['type']=='search'){
			$like = '';
			$query =$query.$like;

			// $fileName 

			$folderName = $this->curd_m->getData('users',array('id'=>$this->session->userdata('userId') ),'object');

			

			// print_r($this->session->userdata('address'));exit();
			$csvHeader = "\r\n"."\r\n"."\r\n"."UserName: ".$this->session->userdata('username').", , ,From,Tuesday 02 March 2019"."\r\n"
				.'Address :'. strip_tags($this->session->userdata('address')).","."\r\n".
				"Phone:".$this->session->userdata('contact_number').",,,To,Wednesday 03 March 2019"."\r\n"."\r\n"."Bulk Sms Outbox Report"."\r\n"."\r\n";

			date_default_timezone_set("Asia/Kathmandu");

			$csvFooter_1 ="\r\n"."\r\n".",,Total Records:";
			$csvFooter_2="\r\n"."\r\n"."Report Generated on:".date('l jS  F Y h:i:s A');

			// echo"footer_2".$csvFooter_2;exit();



			if($data['type']=='download'){
				$res = $this->dhxload->getCsvData(array(
											// 'callback'=>array($this,'senderidCalback'),
											'query'=>$query,
											'rows'=>$rows,
											'prinRowsName'=>$prinRowsName,
											'csvHeader'=>$csvHeader,
											'csvFooter_1'=>$csvFooter_1,
											'csvFooter_2'=>$csvFooter_2,
											));

											// echo"calling the dhx load sucess";
											// 	exit("sdfs");


				





				if($res != NULL) $res = $this->common_m->getExcecl($res, $folderName[0]);
				die( var_dump($res));//default
				// if($res != NULL) $res = $this->common_m->getExcecl($res, );
				// die( var_dump($res));//modified for folder name
			}
		}


}

}
