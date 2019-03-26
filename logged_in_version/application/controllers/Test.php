<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
    	$this->load->helper('url');
	}



	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function set()
	{
		// echo"";
		print_r($this->router->routes);
		// return $this->router->routes;


		$this->session->set_userdata('some_name', 'some_value');
		$this->session->set_userdata("keyvarlogin","valueawsome");
		echo " <br>SETTING 1:".$this->session->userdata('some_name');
		echo " <br>SETTING 2:".$this->session->userdata('keyvarlogin');

		// redirect('get','refresh');
		// exit;
	}

	public function get()
	{
		echo"getter<br>";

		if($this->session->userdata('keyvarlogin')){
			// $this->load->library('session');
			echo " <br>value in session:".$this->session->userdata('some_name');
			echo " <br>value in session:".$this->session->userdata('keyvarlogin');

		}else{
			echo"session is empty";
		}
	}
}
