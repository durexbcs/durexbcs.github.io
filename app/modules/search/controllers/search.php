<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class search extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		permission_view();
	}

	public function index(){
		$data = array(
			"accounts" => $account = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, getDatabyUser(0))
		);
		$this->template->title(l('Instagram search'));
		$this->template->build('index', $data);
	}

	public function ajax_search(){
		$id      = (int)post("account");
		$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$id."'".getDatabyUser());
		if(!empty($account)){
			if(post("keyword")){
				switch (post("type")) {
					case 'tag':
						$i = Instagram_Loader($account->username, $account->password);
						$result = Instagram_Get_Feed($i, 'search_tags', post("keyword"));

						ms(array(
							"st"  => "success",
							"label" => "bg-green",
							"txt" => l('Successfully'),
							"result" => json_encode($this->load->view("ajax_search_tags", array("result" => $result), true))
						));
						break;

					case 'followers':
						$i = Instagram_Loader($account->username, $account->password);
						$result = Instagram_Get_Follow($i, 'followers', (int)post("limit"));

						ms(array(
							"st"  => "success",
							"label" => "bg-green",
							"txt" => l('Successfully'),
							"result" => json_encode($this->load->view("ajax_search_tags", array("result" => $result), true))
						));
						break;
						
					case 'following':
						$i = Instagram_Loader($account->username, $account->password);
						$result = Instagram_Get_Follow($i, 'following', (int)post("limit"));

						ms(array(
							"st"  => "success",
							"label" => "bg-green",
							"txt" => l('Successfully'),
							"result" => json_encode($this->load->view("ajax_search_tags", array("result" => $result), true))
						));
						break;

					default:
						$i = Instagram_Loader($account->username, $account->password);
						$result = Instagram_Get_Feed($i, 'search_users', post("keyword"));

						ms(array(
							"st"  => "success",
							"label" => "bg-green",
							"txt" => l('Successfully'),
							"result" => json_encode($this->load->view("ajax_search_usernames", array("result" => $result), true))
						));
						break;
						break;
				}
			}else{
				ms(array(
					"st"  => "error",
					"label" => "bg-red",
					"txt" => l('Keyword is required'),
				));
			}
		}else{
			ms(array(
				"st"  => "error",
				"label" => "bg-red",
				"txt" => l('Instagram account not exist'),
			));
		}
	}

	public function ajax_open_search_tags(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, "uid = '".session("uid")."'");
		print_r($this->load->view("ajax_open_search_tags", array("accounts" => $accounts), true));
	}
	
	public function search_tag(){
		if(post("tag") != ""){
			$account = (int)post("account");
			$IG = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$account."'".getDatabyUser());
			if(!empty($IG)){
				$result = Instagram_Search_Hashtags($IG, post("tag"));
				if(is_object($result)){
					if(isset($result->status) && $result->status == "ok"){
						ms(array(
							"st"  => "success",
							"label" => "bg-green",
							"txt" => l('Successfully'),
							"result" => json_encode($this->load->view("ajax_tags", array("result" => $result->results), true))
						));
					}
					
				}else{
					ms(array(
						"st"  => "error",
						"label" => "bg-red",
						"txt" => $result
					));
				}
			}else{
				ms(array(
					"st"  => "error",
					"label" => "bg-red",
					"txt" => l('Instagram account does not exist')
				));
			}
		}else{
			ms(array(
				"st"  => "error",
				"label" => "bg-red",
				"txt" => l('Please enter hashtag')
			));
		}	
	}

	public function ajax_open_add_comments(){
		print_r($this->load->view("ajax_open_add_comments", array(), true));
	}

	public function ajax_open_add_messages(){
		print_r($this->load->view("ajax_open_add_messages", array(), true));
	}

	public function ajax_open_search_locations(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, "uid = '".session("uid")."'");
		print_r($this->load->view("ajax_open_search_locations", array("accounts" => $accounts), true));
	}

	public function search_location(){
		if(post("lat") != "" && post("lng") != "" && post("keyword") != ""){
			$account = (int)post("account");
			$IG = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$account."'".getDatabyUser());
			if(!empty($IG)){
				$result = Instagram_Search_Locations($IG, post("lat"), post("lng"), post("keyword"));
				if(is_object($result)){
					if(isset($result->status) && $result->status == "ok"){
						ms(array(
							"st"  => "success",
							"label" => "bg-green",
							"txt" => l('Successfully'),
							"result" => json_encode($this->load->view("ajax_locations", array("result" => $result->venues), true))
						));
					}
					
				}else{
					ms(array(
						"st"  => "error",
						"label" => "bg-red",
						"txt" => $result
					));
				}
			}else{
				ms(array(
					"st"  => "error",
					"label" => "bg-red",
					"txt" => l('Instagram account does not exist')
				));
			}
		}else{
			ms(array(
				"st"  => "error",
				"label" => "bg-red",
				"txt" => l('Please enter location')
			));
		}	
	}

	public function ajax_open_search_usernames(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, "uid = '".session("uid")."'");
		print_r($this->load->view("ajax_open_search_usernames", array("accounts" => $accounts), true));
	}

	public function search_username(){
		if(post("username") != ""){
			$account = (int)post("account");
			$IG = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$account."'".getDatabyUser());
			if(!empty($IG)){
				$result = Instagram_Search_Usernames($IG, post("username"));
				if(is_object($result)){
					if(isset($result->status) && $result->status == "ok"){
						ms(array(
							"st"  => "success",
							"label" => "bg-green",
							"txt" => l('Successfully'),
							"result" => json_encode($this->load->view("ajax_usernames", array("result" => $result->users), true))
						));
					}
					
				}else{
					ms(array(
						"st"  => "error",
						"label" => "bg-red",
						"txt" => $result
					));
				}
			}else{
				ms(array(
					"st"  => "error",
					"label" => "bg-red",
					"txt" => l('Instagram account does not exist')
				));
			}
		}else{
			ms(array(
				"st"  => "error",
				"label" => "bg-red",
				"txt" => l('Please enter username')
			));
		}	
	}
}