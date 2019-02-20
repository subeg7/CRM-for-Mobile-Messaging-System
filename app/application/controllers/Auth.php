<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation','vas'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->data['hostName'] = explode('.',$_SERVER['HTTP_HOST']); 
	}

	// redirect if needed, otherwise display the user list
	function index()
	{
		// echo("at index page");
		if (!$this->ion_auth->logged_in())
		// if(false)
		{
			// redirect them to the login page
			print("user hasnot logged in");
			redirect('login', 'refresh');
		}
		else
		{
			// print("user has logged in");
			// return;
			//$verify = $this->vas->veryfy_url();
			
			//$this->data['hostName'] = explode('.',$_SERVER['HTTP_HOST']); 

			// print("point 1");
				// return;


			if($this->session->userdata('userId')!=1 && !$this->vas->checkLoginStat()){
				// if(false)//preventing admin{}
				$this->data['message'] = 'System Is Disabled Temporary. Please Try later';
				$this->data['isLogin'] = FALSE;
				$logout = $this->ion_auth->logout();
				$this->_render_page('auth/login', $this->data);
				
			}else{
				$verify = $this->vas->veryfy_url($this->session->userdata('userId'));
				// print("user has logged in");
			// return;
				if($verify ===FALSE){
					// if(FALSE){
					$this->data['message'] = 'Incorrect Login';
					$this->data['isLogin'] = FALSE;
					$logout = $this->ion_auth->logout();
					$this->_render_page('auth/login', $this->data);
				}else{
					// print("user passed");
					// return;

					$this->data['domainTitle'] = ($this->vas->domain_name!=NULL)? $this->vas->domain_name:'Easy SMS Service';
					$this->data['isLogin'] = TRUE;
					$this->data['hostName'] = ($this->vas->domain_name!=NULL)? $this->vas->domain_name:'Easy SMS Service';
					$this->data['ribbon'] = $this->vas->gen_Ribbon();
					$this->data['toolbar'] = $this->vas->gen_Toolbar();
					$this->data['isAdmin'] = ($this->session->userdata('userId')==1)?'admin':'none'; 
					$this->data['priv'] =  $this->vas->getUserPrivileges();
					
					$this->_render_page('auth/index', $this->data);
				}
			}
		}
	}

	// log the user in
	function login()
	{
		// print("trying to log in");
		if ($this->ion_auth->logged_in())
		{
			// redirect('/', 'refresh');
		}
		$this->data['title'] = "Login";

		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			// print("calling auth_login");
			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful			
				// print("login success");	
				$this->vas->setSessionUserData();
				// print("vasdata:"+$this->vas->s);
				// return;	
				redirect('/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				print("loign failed!!!");	
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			if(!$this->input->get('logout') )
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			else
				$this->data['message'] = 'Logout Sucessfully';
			$this->data['isLogin'] = FALSE;
			$this->data['hostName'] = explode('.',$_SERVER['HTTP_HOST']); 
			$this->_render_page('auth/login', $this->data);
		}
	}

	// log the user out
	function logout()
	{
		// log the user out
		$this->vas->removeLoginState();
		$logout = $this->ion_auth->logout();
		die(TRUE);
	}

	// forgot password
	function forgot_password()
	{
		$this->data['isLogin'] = FALSE;
		// setting validation rules by checking wheather identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if(empty($identity)) {

	            		if($this->config->item('identity', 'ion_auth') != 'email')
		            	{
		            		$this->ion_auth->set_error('forgot_password_identity_not_found');
		            	}
		            	else
		            	{
		            	   $this->ion_auth->set_error('forgot_password_email_not_found');
		            	}

		                $this->session->set_flashdata('message', 'Incorrect Username');
                		redirect("/forgot_password", 'refresh');
            		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	// reset password - final step for forgotten password
	public function reset_password()
	{
		if(!$_REQUEST['code'])
		{
			show_404();
		}
		$code = $_REQUEST['code'];
		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('/reset_password?code='. $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("forgot_password", 'refresh');
		}
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

}
