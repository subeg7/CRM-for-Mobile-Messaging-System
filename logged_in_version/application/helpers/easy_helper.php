<?php

	
	if ( ! function_exists('authentication_fail_message'))
	{
		function expire_message($obj){
			if( $obj==='window' || $obj==='load'){	
				die('<div><script type="text/javascript"> obj.session_expire();</script></div>');					
			}
			elseif( $obj==='grid' ){	
				header("Content-Type:text/xml");
				die('<?xml version="1.0" encoding="iso-8859-1" ?><rows><userdata name="session">expire</userdata></rows>');					
			}
			elseif( $obj==='tree' ){
				header("Content-type: text/xml");
				die('<?xml version="1.0" encoding="iso-8859-1" ?><tree id="0" radio="1">
				<item text="Lists" id="session" open="1"><userdata name="session">expire</userdata></item></tree>');
	
			}
			elseif( $obj==='ajax' ){
				die('expire');
			}
		}
	}
	if ( ! function_exists('authentication_fail_message'))
	{
		function logged_in()
		{
			return (bool) $this->session->userdata('identity');
		}
	}
	

?>