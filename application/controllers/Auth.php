<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		// CI Bootstrap libraries
		$this->load->library('form_builder');
		$this->load->library('system_message');
		//$this->load->library('email_client');

		$this->push_breadcrumb('Auth');
	}

	/**
	 * Login page
	 */
	public function login()
	{
		$form = $this->form_builder->create_form();

		if ($form->validate())
		{
			// passed validation
			$identity = $this->input->post('email');
			$password = $this->input->post('password');
			$remember = ($this->input->post('remember')=='on');
			

			
			if ($this->ion_auth->login($identity, $password, $remember))
			{
				// success
				$messages = $this->ion_auth->messages();
				$this->system_message->set_success($messages);

				// redirect to user dashboard
				redirect('account');
			}
			else
			{
				// failed
				$errors = $this->ion_auth->errors();

				echo ""; 
				$this->system_message->set_error($errors);
				refresh();
			}
		}

		// display form
		$this->mViewData['form'] = $form;
		$this->render('auth/login');
	}

	/**
	 * Logout
	 */
	public function logout()
	{
		$this->ion_auth->logout();	
		redirect();
	}

	/**
	 * Activation
	 */
	public function activate($id = NULL, $code = NULL)
	{
		if ( empty($id) )
		{
			redirect();
		}
		else if ( !empty($code) )
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// success
			$messages = $this->ion_auth->messages();
			$this->system_message->set_success($messages);
			redirect('auth/login');
		}
		else
		{
			// failed
			$errors = $this->ion_auth->errors();
			$this->system_message->set_error($errors);
			redirect('auth/forgot_password');
		}
	}

	/**
	 * Forgot Password page
	 */
	public function forgot_password()
	{
		$form = $this->form_builder->create_form();

		if ($form->validate())
		{
			// passed validation
			$identity = $this->input->post('email');
			$user = $this->ion_auth->forgotten_password($identity);

			if ($user)
			{
				if (!$this->config->item('use_ci_email', 'ion_auth'))
				{
					// send email using Email Client library
					$subject = $this->lang->line('email_forgotten_password_subject');
					$email_view = $this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth');
					$this->email_client->send($identity, $subject, $email_view, $user);	
				}

				// success
				$messages = $this->ion_auth->messages();
				$this->system_message->set_success($messages);
				redirect('auth/login');
			}
			else
			{
				// failed
				$errors = $this->ion_auth->errors();
				$this->system_message->set_error($errors);
				refresh();
			}
		}

		// display form
		$this->mViewData['form'] = $form;
		$this->render('auth/forgot_password');
	}

	/**
	 * Reset Password page
	 */
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			redirect();
		}

		// check whether code is valid
		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			$form = $this->form_builder->create_form();

			if ($form->validate())
			{
				// passed validation
				$identity = $user->email;
				$password = $this->input->post('password');
				
				// confirm update password
				if ( $this->ion_auth->reset_password($identity, $password) )
				{
					if (!$this->config->item('use_ci_email', 'ion_auth'))
					{
						// send email using Email Client library
						$subject = $this->lang->line('email_new_password_subject');
						$email_view = $this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password_complete', 'ion_auth');
						$data = array('identity' => $identity);
						$this->email_client->send($identity, $subject, $email_view, $data);
					}

					// success
					$messages = $this->ion_auth->messages();
					$this->system_message->set_success($messages);
					redirect('auth/login');
				}
				else
				{
					// failed
					$errors = $this->ion_auth->errors();
					$this->system_message->set_error($errors);
					redirect('auth/reset_password/' . $code);
				}
			}

			// display form
			$this->mViewData['form'] = $form;
			$this->render('auth/reset_password');
		}
		else
		{
			// code invalid
			$errors = $this->ion_auth->errors();
			$this->system_message->set_error($errors);
			redirect('auth/forgot_password', 'refresh');
		}
	}

	 /**
	 * OAuth Calback Function - Login page
	 */
	public function studentlogin()
	{
		$form = $this->form_builder->create_form();

		if($this->session->userdata('login') == true){
			redirect('auth/profile');
		}

		if (isset($_GET['code'])) {
				
			$this->googleplus->getAuthenticate();

			$userData = $this->googleplus->getUserInfo();
			
		
			$this->load->model('atc_student_registration_model', 'registration');
            $is_registered = $this->registration->getStudentByEmail($userData['email']);

			if($is_registered) {
				$this->session->set_userdata('login',true);
				$this->session->set_userdata('user_profile',$this->googleplus->getUserInfo());

				$this->session->set_userdata('identity', 'student@gmail.com');
				$this->session->set_userdata('email', 'student@gmail.com');
				$this->session->set_userdata('user_id', 10);

				redirect('auth/profile');
			} else {
				echo "User not exist<hr> <pre>"; print_r($this->googleplus->getUserInfo()); exit;
			}
		} 

		redirect($this->googleplus->loginURL());
	}

	public function profile() {
		
		if($this->session->userdata('login') != true){
			redirect('/');
		}

		$this->mViewData['user_profile'] = $this->session->userdata('user_profile');
		$this->render('student/profile');
		
	}
}
