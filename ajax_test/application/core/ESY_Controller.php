<?php (defined('BASEPATH')) OR exit('No direct script access allowed');



class ESY_Controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('vas','form_validation','session'));
		$this->load->helper(array('url','language'));

		if (!$this->vas->logged_in())
		{
			if($this->input->get('object') ){
				$this->vas->expire_message($this->input->get('object'));
			}elseif($this->input->post('object')){
				$this->vas->expire_message($this->input->post('object'));
			}
			else{
				die(show_404());
			}
		}
		else if ($this->vas->logged_in()){
			if(!$this->vas->checkUserState($this->session->userdata('userId'))){
				$this->vas->logout();
				$this->vas->removeLoginState();
				if($this->input->get('object') ){
					$this->vas->expire_message($this->input->get('object'),'disable');
				}elseif($this->input->post('object')){
					$this->vas->expire_message($this->input->post('object'),'disable');
				}
			}
			$this->vas->addLoginState();
		}
	}




}
