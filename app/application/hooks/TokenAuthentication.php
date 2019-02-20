<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*/

class TokenAuthentication {
	
	function __construct(){
		$this->CI =& get_instance();
	}
	function validate(){	
		$this->CI->data['test']=$this->CI->input->get('test');
	}
}