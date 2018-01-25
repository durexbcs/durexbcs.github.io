<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class instagram_accounts extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$data = array(
			"result" => $this->model->getAccounts()
		);
		$this->template->title(l('Instagram accounts'));
		$this->template->build('index', $data);
	}

	public function add(){
		$data = array(
			"result" => $this->model->getAccounts()
		);
		$this->template->title(l('Instagram accounts'));
		$this->template->build('index', $data);
	}

	public function add_account(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, getDatabyUser(0));
		$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".get("id")."'".getDatabyUser());
		$data = array(
			'result' => $account,
			'count'  => count($accounts)
		);
		$this->load->view("add_account", $data);
	}

	public function update(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, getDatabyUser(0));
		$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".get("id")."'".getDatabyUser());
		$data = array(
			'result' => $account,
			'count'  => count($accounts)
		);
		$this->template->title(l('Instagram accounts'));
		$this->template->build('update', $data);
	}

	public function ajax_update(){
		$username = post('username');
		$password = post('password');

		if($username == "" || $password == ""){
			ms(array(
				"st"  => "error",
				"label" => "bg-red",
				"txt" => l('Please input all fields')
			));
		}

		$IG_Oauth = Instagram_Login($username, $password);
		if(is_array($IG_Oauth) && isset($IG_Oauth['st'])){
			ms($IG_Oauth);
		}

		//IG Info 
		$IG_Info = $IG_Oauth->getSelfUsernameInfo();
		if($IG_Info->status != "ok"){
			ms(array(
				"st"  => "error",
				"label" => "bg-red",
				"txt" => l('Connect failure')
			));
		}

		$fid  = $IG_Oauth->username_id;
		$data = array(
			"uid"           => session("uid"),
			"fid"           => $fid,
			"avatar"        => $IG_Info->user->profile_pic_url,
			"username"      => $IG_Info->user->username,
			"password"      => $password,
			"checkpoint"    => 0,
		);
				
		$id = (int)post("id");
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, "uid = ".session("uid"));
		if($id == 0){
			if(count($accounts) < getMaximumAccount()){
				$checkAccount = $this->model->get("*", INSTAGRAM_ACCOUNTS, "fid = '".$fid."' AND uid = ".session("uid"));
				if(!empty($checkAccount)){
					ms(array(
						"st"    => "error",
						"label" => "bg-red",
						"txt"   => l('This instagram account already exists')
					));
				}

				$this->db->insert(INSTAGRAM_ACCOUNTS, $data);
				$id = $this->db->insert_id();
			}else{
				ms(array(
					"st"    => "error",
					"label" => "bg-orange",
					"txt"   => l('Oh sorry! You have exceeded the number of accounts allowed, You are only allowed to update your account')
				));
			}
		}else{
			$checkAccount = $this->model->get("*", INSTAGRAM_ACCOUNTS, "fid = '".$fid."' AND id != '".$id."' AND uid = ".session("uid"));
			if(!empty($checkAccount)){
				ms(array(
					"st"    => "error",
					"label" => "bg-red",
					"txt"   => l('This instagram account already exists')
				));
			}

			$this->db->update(INSTAGRAM_ACCOUNTS, $data, array("id" => post("id")));
		}

		ms(array(
			"st"    => "success",
			"label" => "bg-light-green",
			"txt"   => l('Update successfully')
		));
	}

	public function ajax_get_groups(){
		$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".post("id")."'".getDatabyUser());
		if(!empty($account)){
			switch (post("type")) {
				case 'page':
					$IG_Oauth = Instagram_Login($account->username, $account->password);
					if(is_array($IG_Oauth) && isset($IG_Oauth['st'])){
						ms($IG_Oauth);
					}else{
						//IG Info 
						$IG_Info = $IG_Oauth->getSelfUsernameInfo();
						if($IG_Info->status != "ok"){
							ms(array(
								"st"  => "error",
								"label" => "bg-red",
								"txt" => l('Connect failure')
							));
						}

						$data = array(
							"checkpoint" => 0,
							"avatar" => $IG_Info->user->profile_pic_url
						);

						$this->db->update(INSTAGRAM_ACCOUNTS, $data, array("id" => post("id")));
						
						ms(array(
							"st"    => "success",
							"label" => "bg-light-green",
							"txt"   => l('Update successfully')
						));
					}
					break;
			}
			ms(array(
				'st' 	=> 'success',
				"label" => "bg-light-green",
				'txt' 	=> l('Successfully')
			));
		}else{
			ms(array(
				'st' 	=> 'error',
				"label" => "bg-red",
				'txt' 	=> l('Update failure')
			));
		}
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', INSTAGRAM_ACCOUNTS, "id = '{$id}'".getDatabyUser());
		if(!empty($POST)){
			switch (post("action")) { 
				case 'delete':
					$this->db->delete(INSTAGRAM_ACCOUNTS, "id = '{$id}'".getDatabyUser());
					$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."'".getDatabyUser());
					$this->db->delete(INSTAGRAM_HISTORY, "account_id = '".$id."'".getDatabyUser());
					$this->db->delete(INSTAGRAM_ACTIVITY, "account_id = '".$id."'".getDatabyUser());
					break;
				
				case 'active':
					$this->db->update(INSTAGRAM_ACCOUNTS, array("status" => 1), "id = '{$id}'".getDatabyUser());
					$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'like' OR category = 'comment' OR category = 'follow' OR category = 'followback' OR category = 'unfollow' OR category = 'repost')".getDatabyUser());
					$this->db->delete(INSTAGRAM_ACTIVITY, "account_id = '".$id."'".getDatabyUser());
					$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'post' OR category = 'message')".getDatabyUser());
					break;

				case 'disable':
					$this->db->update(INSTAGRAM_ACCOUNTS, array("status" => 0), "id = '{$id}'".getDatabyUser());
					$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'like' OR category = 'comment' OR category = 'follow' OR category = 'followback' OR category = 'unfollow' OR category = 'repost')".getDatabyUser());
					$this->db->delete(INSTAGRAM_ACTIVITY, "account_id = '".$id."'".getDatabyUser());
					$this->db->delete(INSTAGRAM_HISTORY, "account_id = '".$id."'".getDatabyUser());
					$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'post' OR category = 'message')".getDatabyUser());
					break;
			}
		}

		ms(array(
			'st' 	=> 'success',
			'txt' 	=> l('Successfully')
		));
	}

	public function ajax_action_multiple(){
		$ids =$this->input->post('id');
		if(!empty($ids)){
			foreach ($ids as $id) {
				$POST = $this->model->get('*', INSTAGRAM_ACCOUNTS, "id = '{$id}'".getDatabyUser());
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							$this->db->delete(INSTAGRAM_ACCOUNTS, "id = '{$id}'".getDatabyUser());
							$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."'".getDatabyUser());
							$this->db->delete(INSTAGRAM_HISTORY, "account_id = '".$id."'".getDatabyUser());
							$this->db->delete(INSTAGRAM_ACTIVITY, "account_id = '".$id."'".getDatabyUser());
							break;
						case 'active':
							$this->db->update(INSTAGRAM_ACCOUNTS, array("status" => 1), "id = '{$id}'".getDatabyUser());
							$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'like' OR category = 'comment' OR category = 'follow' OR category = 'followback' OR category = 'unfollow' OR category = 'repost')".getDatabyUser());
							$this->db->delete(INSTAGRAM_ACTIVITY, "account_id = '".$id."'".getDatabyUser());
							$this->db->delete(INSTAGRAM_HISTORY, "account_id = '".$id."'".getDatabyUser());
							$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'post' OR category = 'message')".getDatabyUser());
							break;

						case 'disable':
							$this->db->update(INSTAGRAM_ACCOUNTS, array("status" => 0), "id = '{$id}'".getDatabyUser());
							$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'like' OR category = 'comment' OR category = 'follow' OR category = 'followback' OR category = 'unfollow' OR category = 'repost')".getDatabyUser());
							$this->db->delete(INSTAGRAM_ACTIVITY, "account_id = '".$id."'".getDatabyUser());
							$this->db->delete(INSTAGRAM_SCHEDULES, "account_id = '".$id."' AND (category = 'post' OR category = 'message')".getDatabyUser());
							break;
					}
				}
			}
		}

		print_r(json_encode(array(
			'st' 	=> 'success',
			'txt' 	=> l('Successfully')
		)));
	}
}