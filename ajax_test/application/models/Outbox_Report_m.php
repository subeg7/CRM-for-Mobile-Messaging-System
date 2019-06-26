<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Outbox_Report_m	 extends CI_Model
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

		$dateRangeStart = $data['searchStart'];
		$dateRangeTill = $data['searchTill'];

		$userType=NULL;
			if($userid==1){
				//ADMIN code
				$query = "select fld_chr_sender as fld_chr_sender,fld_chr_message as fld_chr_message,  fld_msg_number as fld_msg_number, fld_int_cell_no  as fld_int_cell_no,fld_int_ondate as fld_int_ondate

					from outbox 

				 	where fld_int_ondate between ".$dateRangeStart." and ".$dateRangeTill;

				$rows = 'fld_int_id,fld_chr_sender,fld_int_cell_no,fld_chr_message,fld_msg_number,fld_int_ondate';
				$prinRowsName = 'S.N,Sender Id,NTC,AXIATA,SMARTCELL,Message,Char Count,Date';
				$userType="ADMIN";
			}
			elseif(in_array('USER_MANAGE',$data['privileges'])){
				//RESELLER code
				$query = "select fld_chr_sender as fld_chr_sender,fld_chr_message as fld_chr_message,  fld_msg_number as fld_msg_number, fld_int_cell_no  as fld_int_cell_no,fld_int_ondate as fld_int_ondate

					from outbox 

				 	where fld_reseller_id=".$userid.

				 	" and fld_int_ondate between ".$dateRangeStart." and ".$dateRangeTill;

				$rows = 'fld_int_id,fld_chr_sender,fld_int_cell_no,fld_chr_message,fld_msg_number,fld_int_ondate';
				$prinRowsName = 'S.N,Sender Id,NTC,AXIATA,SMARTCELL,Message,Char Count,Date';
				$userType="RESELLER";

			}
			elseif(!in_array('USER_MANAGE',$data['privileges'])){
				//CLIENT code
				$query = "select fld_chr_sender as fld_chr_sender,fld_chr_message as fld_chr_message,  fld_msg_number as fld_msg_number, fld_int_cell_no  as fld_int_cell_no,fld_int_ondate as fld_int_ondate

					from outbox 

				 	where fld_int_userid=".$userid.

				 	" and fld_int_ondate between ".$dateRangeStart." and ".$dateRangeTill;

				$rows = 'fld_int_id,fld_chr_sender,fld_int_cell_no,fld_chr_message,fld_msg_number,fld_int_ondate';
				$prinRowsName = 'S.N,Sender Id,NTC,AXIATA,SMARTCELL,Message,Char Count,Date';
				$userType="CLIENT";


			}


		if($data['type']=='download' || $data['type']=='search'){
			$like = '';
			$query =$query.$like;


			$folderName = $this->curd_m->getData('users',array('id'=>$this->session->userdata('userId') ),'object');

			

			// $csvHeader = "\r\n"."\r\n"."\r\n"."UserName: ".$this->session->userdata('username').", , ,From,".date('l jS  F Y',$dateRangeStart)."\r\n"
			// 	.'Address :'. str_replace(","," ",$this->session->userdata('address')).","."\r\n".
			// 	"Phone:".$this->session->userdata('contact_number').",,,To,".date('l jS  F Y',$dateRangeTill)."\r\n"."\r\n Type of Report: ".$userType." Bulk Sms Outbox Report"."\r\n"."\r\n";

			$csvHeader = "\r\n"."\r\n"."\r\n"."UserName: ".$this->session->userdata('username').", , ,From,".date('l jS  F Y',$dateRangeStart)."\r\n"
				.'Address :'. str_replace(","," ",$this->session->userdata('address')).","."\r\n".
				"Phone:".$this->session->userdata('contact_number').",,,To,".date('l jS  F Y',$dateRangeTill)."\r\n"."\r\n Type of Report: ".$userType." Bulk Sms Outbox Report"."\r\n"."\r\n";

				

			date_default_timezone_set("Asia/Kathmandu");


			if($data['type']=='download'){
				$res = $this->dhxload->getCsvData(array(
											// 'callback'=>array($this,'senderidCalback'),
											'query'=>$query,
											'rows'=>$rows,
											'prinRowsName'=>$prinRowsName,
											'csvHeader'=>$csvHeader,
											));

											




				$reportType = "Outbox";
				if($res != NULL) $res = $this->common_m->getExcecl($res, $folderName[0],$reportType);
				die( var_dump($res));//default
				// if($res != NULL) $res = $this->common_m->getExcecl($res, );
				// die( var_dump($res));//modified for folder name
			}
		}


}

}
