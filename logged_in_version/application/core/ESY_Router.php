<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */

class ESY_Router extends CI_Router {
		
        public function __construct()
        {
                parent::__construct();
				
        }
		
		protected function _set_request($segments = array())
		{	
			$segments = $this->_validate_request($segments);
			// If we don't have any segments left - try the default controller;
			// WARNING: Directories get shifted out of the segments array!
			if (empty($segments))
			{
				return;
			}		
			if ($this->translate_uri_dashes === TRUE)
			{
				$segments[0] = str_replace('-', '_', $segments[0]);
				if (isset($segments[1]))
				{
					$segments[1] = str_replace('-', '_', $segments[1]);
				}
			}
	
			$this->set_class($segments[0]);
			if (isset($segments[1]))
			{
				$this->set_method($segments[1]);
			}
			else
			{
				$segments[1] = 'index';
			}
	
			array_unshift($segments, NULL);
			unset($segments[0]);
			$this->uri->rsegments = $segments;
		}
	
		protected function _validate_request($segments)
		{
			
			$c = count($segments);
			$directory_override = isset($this->directory);
	
			// Loop through our segments and return as soon as a controller
			// is found or when such a directory doesn't exist
			if(!isset($segments[1])){
				show_404();
				return;
			} 
			if($segments[0].'/'.$segments[1]!==$this->config->item('socket')) return array();
			else{ 
				array_shift($segments);
				array_shift($segments);
				if(empty($segments)) return $segments;
			}
			while ($c-- > 0)
			{
				$test = $this->directory
					.ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);
				if ( ! file_exists(APPPATH.'controllers/'.$test.'.php')
					&& $directory_override === FALSE
					&& is_dir(APPPATH.'controllers/'.$this->directory.$segments[0])
				)
				{
					$this->set_directory(array_shift($segments), TRUE);
					continue;
				}
	
				return $segments;
			}
	
			// This means that all segments were actually directories
			return $segments;
		}
	
		protected function _set_default_controller()
		{
			
			if (empty($this->default_controller))
			{
				show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
			}
			$default_url_array = explode('/',$this->default_controller);
			
			if(!isset($default_url_array[1])){
				
				show_404();
				return;
			} 
			if($default_url_array[0].'/'.$default_url_array[1]!==$this->config->item('socket')){
				return;				
			}
			else{
				array_shift($default_url_array);
				array_shift($default_url_array);
				if(empty($default_url_array)){
					show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
				}
				else{
					$this->default_controller = implode('/',$default_url_array);
				}
				
			}
			// Is the method being specified?
			if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
			{
				$method = 'index';
			}
	
			if ( ! file_exists(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
			{
				// This will trigger 404 later
				return;
			}
	
			$this->set_class($class);
			$this->set_method($method);
	
			// Assign routed segments, index starting from 1
			$this->uri->rsegments = array(
				1 => $class,
				2 => $method
			);
	
			log_message('debug', 'No URI present. Default controller set.');
		}
	
	
	
	
	
}