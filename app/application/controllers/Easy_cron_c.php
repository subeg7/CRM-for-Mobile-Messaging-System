<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Easy_cron_c extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->config->load('easy_config');
		$this->load->model(array('curd_m','sendsms_m'));
	}
	public function cronRunState(){
		$res = $this->curd_m->get_search("SELECT * FROM system_flag WHERE fld_type ='que_job'",'object');
		if($res ==NULL) return TRUE;
		else{
			return $res[0]->fld_val;
		}
	}
	public function cron_process($nonce){
		if($this->config->item('cron_nonce')!=trim($nonce)){ die(show_404());	}
		//if(!$this->common_m->cronState()) die('state');
		if(!$this->cronRunState()) die('temproarly cron disabled');
		
		// firs get the no scheduler jobs
		$que = $this->curd_m->get_search('SELECT * FROM que_job WHERE schedule = 0 AND state=1 ORDER BY date ASC LIMIT 100','object');	
		if($que!=NULL){
			foreach($que as $row){
				$numbers = array();
				$senderId = array();
				$queNumber =  $this->curd_m->get_search('SELECT cellNumbers FROM que_detail WHERE que_id = "'.$row->fld_int_id.'"','object');
				foreach($queNumber as $num){ $numbers[] = $num->cellNumbers;}
				$queSenderid = explode(',', $row->sender_id);
				foreach($queSenderid as $sender){
					$queSenderid = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_id ='.$sender,'object');
					$senderId[] = $queSenderid[0]->operator.'_'.$sender;
				}
				
				$res = $this->sendsms_m->quickSend(array(
								'numbers'=>implode(',',$numbers),
								'addressbook'=>NULL,
								'message'=>$row->message,
								'messageType'=>$row->messageType,
								'senderId'=>implode(',',$senderId ),
								'userId'=>$row->users_id,
								'date'=>time(),
								'agentId'=>$row->agent_id,
								'excludeNumber'=>NULL,
								'queid'=>uniqid(),
								'balanceType'=>$row->fld_balance_type,
								'sendby'=>'cron_user'
							));
				if($res =='1701'){
					$this->db->where('que_id', $row->fld_int_id);
					$this->db->delete('que_detail'); 
					
					$this->db->where('fld_int_id', $row->fld_int_id);
					$this->db->delete('que_job'); 
				}else{
				}
			
			}
		}// end of normal que jobs
		//for scheduler dates
		$queSche = $this->curd_m->get_search('SELECT * FROM que_job WHERE schedule = 1 AND state=1 ORDER BY date ASC LIMIT 100','object');
		if($queSche!=NULL){
			foreach($queSche as $row){
				$numbers = array();
				$senderId = array();
				$scheduleDate = explode(',',trim($row->schedule_date) );
				asort($scheduleDate);
				
				$selectedDate = NULL;
				foreach($scheduleDate as $schdate){
					if($schdate < time()){
						if($selectedDate==NULL){ $selectedDate = $schdate;}
					} 
				}
				
				if($selectedDate!=NULL){
					$queNumber =  $this->curd_m->get_search('SELECT cellNumbers FROM que_detail WHERE que_id = "'.$row->fld_int_id.'"','object');
					foreach($queNumber as $num){ $numbers[] = $num->cellNumbers;}
					$queSenderid = explode(',', $row->sender_id);
					
					foreach($queSenderid as $sender){
						$queSenderid = $this->curd_m->get_search('SELECT * FROM easy_senderid WHERE fld_int_id ='.$sender,'object');
						$senderId[] = $queSenderid[0]->operator.'_'.$sender;
					}
					
					$res = $this->sendsms_m->quickSend(array(
									'numbers'=>implode(',',$numbers),
									'addressbook'=>NULL,
									'message'=>$row->message,
									'messageType'=>$row->messageType,
									'senderId'=>implode(',',$senderId ),
									'userId'=>$row->users_id,
									'date'=>time(),
									'agentId'=>$row->agent_id,
									'excludeNumber'=>NULL,
									'queid'=>uniqid(),
									'balanceType'=>$row->fld_balance_type,
									'sendby'=>'cron_user'
								));
					if($res =='1701'){
						array_splice($scheduleDate,array_search($selectedDate,$scheduleDate),1);
						if(sizeof($scheduleDate) == 0){
							$this->db->where('que_id', $row->fld_int_id);
							$this->db->delete('que_detail'); 
							
							$this->db->where('fld_int_id', $row->fld_int_id);
							$this->db->delete('que_job'); 
						}
						else{
							$this->curd_m->get_update('que_job',array(
														'fld_int_id'=>$row->fld_int_id,
														'schedule_date'=>implode(',',$scheduleDate), 
													));
						}
						
					}else{
					}
				/////////////
				}
			}
		}// end of scheduler date que	
		die('1701');
	}
	
}
