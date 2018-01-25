<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class home extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		if(session("uid")) redirect(url("dashboard"));
		$data = array();
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('login', $data);
	}

	public function login(){
		if(session("uid")) redirect(url("dashboard"));
		$data = array();
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('login', $data);
	}

	public function register(){
		if(session("uid")) redirect(url("dashboard"));
		$data = array();
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('register', $data);
	}

	public function forgot_password(){
		$data = array();
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('forgot_password', $data);
	}

	public function reset_password(){
		$data = array();
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('reset_password', $data);
	}

	public function contact(){
		$data = array();
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('contact', $data);
	}

	public function timezone(){
		$data = array();
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('timezone', $data);
	}

	public function add_account(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, getDatabyUser(0));
		$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".get("id")."'".getDatabyUser());
		$data = array(
			'result' => $account,
			'count'  => count($accounts)
		);
		$this->template->set_layout("home");
		$this->template->title(TITLE);
		$this->template->build('add_account', $data);
	}
	
	public function logout(){
		delete_cookie('folderid');
		unset_session('uid');
		redirect(PATH);
	}

	public function facebook(){
		redirect(FACEBOOK_GET_LOGIN_URL());
	}

	public function google(){
		redirect(GOOGLE_GET_LOGIN_URL());
	}

	public function twitter(){
		redirect(TWITTER_GET_LOGIN_URL());
	}
}