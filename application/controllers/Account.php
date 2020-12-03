<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Account_model','accounts');
		//$this->load->model('Common_model','common');
		// only login users can access Account controller
		$this->verify_login();
	}

	public function index() {

		$this->mViewData['user'] = $this->mUser;

		$this->render('account');
	}
}
