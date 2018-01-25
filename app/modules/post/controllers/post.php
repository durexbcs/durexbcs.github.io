<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class post extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		permission_view();
	}

	public function index(){
		$data = array(
			"result"     => $this->model->getAllAccount(),
			"save"       => $this->model->fetch("*", SAVE, "status = 1 AND category = 'post'".getDatabyUser()),
			"categories" => $this->model->fetch("*", CATEGORIES, "category = 'post'".getDatabyUser())
		);
		$this->template->title(l('Auto post')); 
		$this->template->build('index', $data);
	}
	
	public function ajax_post_now(){
		$spintax = new Spintax();
		$data = array();

		if(!check_expiration()  && session("admin") != 1){
			if(post('video_url') == ""){
				ms(array(
					"st"    => "valid",
					"label" => "bg-red",
					"txt"   => l('Notice: Out of date! System auto stop all activity on all your instagram accounts.')
				));
			}
		}

		switch (post('type')) {
			case 'video':
				if(post('video_url') == ""){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Video is required')
					));
				}

				$data = array(
					"category"    => "post",
					"type"        => post('type'),
					"image"       => $spintax->process(post('video_url')),
					"message"     => $spintax->process(post('message')),
				);
				break;
			default:
				if(post('image_url') == ""){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Image is required')
					));
				}

				$data = array(
					"category"  => "post",
					"type"      => post('type'),
					"image"     => $spintax->process(post('image_url')),
					"message"   => $spintax->process(post('message'))
				);
				break;
		}

		$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "username = '".post('group')."'".getDatabyUser());
		if(post('group')){
			$data["uid"]            = session("uid");
			$data["group_type"]     = "profile";
			$data["account_id"]     = $account->id;
			$data["account_name"]   = post('group');
			$data["group_id"]       = post('group');
			$data["name"]           = post('group');
			$data["privacy"]        = 0;
			$data["time_post"]      = NOW;
			$data["changed"]        = NOW;
			$data["created"]        = NOW;
			$data["deplay"]         = 180;
			$data["status"]         = 4;

			$date = new DateTime(NOW, new DateTimeZone(TIMEZONE_SYSTEM));
			$date->setTimezone(new DateTimeZone(TIMEZONE_USER));
			$time_post_show = $date->format('Y-m-d H:i:s');

			$data["time_post_show"] = $time_post_show;
			if(!empty($account)){
				$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				$id = $this->db->insert_id();

				$data['username'] = $account->username;
				$data['password'] = $account->password;
				$data['fid'] = $account->fid;

				$row = (object)$data;

				$response = (object)Instagram_Post($row);
				$this->db->update(INSTAGRAM_SCHEDULES, array(
					"status" => ($response->st == "success")?3:4,
					"result" => (isset($response->id))?$response->id:"",
					"message_error" => ($response->st == "success")?$response->txt:"",
				), "id = {$id}");

				if($response->st == "success"){
					ms(array(
						"st"    => "success",
						"label" => "bg-light-green",
						"txt"   => "<span class='col-green'>".l('Post successfully')." <a href='https://instagram.com/p/".$response->code."' target='_blank'><i class='col-light-blue fa fa-external-link-square' aria-hidden='true'></i></a></span>"
					));
				}else{
					ms(array(
						"st"    => "error",
						"label" => "bg-red",
						"txt"   => "<span class='col-red'>".$response->txt."</span>"
					));
				}
			}else{
				ms(array(
					"st"    => "error",
					"label" => "bg-red",
					"txt"   => "<span class='col-red'>".l('Instagram account not exist')."</span>"
				));
			}
		}else{
			ms(array(
				"st"    => "error",
				"label" => "bg-red",
				"txt"   => "<span class='col-red'>".l('Have problem with this item')."</span>"
			));
		}
	}

	public function ajax_save_schedules(){
		$data = array();
		$groups = $this->input->post('id');
		switch (post('type')) {
			case 'video':
				if(post('video_url') == ""){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Video is required')
					));
				}

				$data = array(
					"category"    => "post",
					"type"        => post('type'),
					"image"       => post('video_url'),
					"message"     => post('message'),
				);
				break;
			default:
				if(post('image_url') == ""){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Image is required')
					));
				}

				$data = array(
					"category"  => "post",
					"type"      => post('type'),
					"image"     => post('image_url'),
					"message"   => post('message')
				);
				break;
		}

		if(post('time_post') == ""){
			$json[] = array(
				"st"    => "valid",
				"label" => "bg-red",
				"text"  => l('Time post is required')
			);
		}

		if(empty($groups)){
			ms(array(
				"st"    => "valid",
				"label" => "bg-red",
				"txt"   => l('Select at least a instagram account')
			));
		}

		if(post('auto_repeat') != 0){
			$data["repeat_post"] = 1;
			$data["repeat_time"] = (int)post("auto_repeat");
			$data["repeat_end"]  = date("Y-m-d", strtotime(post('repeat_end')));
		}else{
			$data["repeat_post"] = 0;
		}

		$count = 0;
		$deplay = (int)post('deplay')*60;
		$list_deplay = array();
		for ($i=0; $i < count($groups); $i++) { 
			$list_deplay[] = $deplay*$i;
		}

		$auto_pause = (int)post('auto_pause');
		if($auto_pause != 0){
			$pause = 0;
			$count_deplay = 0;
			for ($i=0; $i < count($list_deplay); $i++) { 
				$item_deplay = 1;
				if($auto_pause == $count_deplay){
					$pause += post('time_pause')*60;
					$count_deplay = 0;
				}

				$list_deplay[$i] += $pause;
				$count_deplay++;
			}
		}

		shuffle($list_deplay);

		$time_post_show = strtotime(post('time_post').":00");
		$time_now  = strtotime(NOW) + 60;
		if($time_post_show < $time_now){
			$time_post_show = $time_now;
		}

		$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
		$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
		$time_post = $date->format('Y-m-d H:i:s');
		foreach ($groups as $key => $group) {
			$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "username = '".$group."'".getDatabyUser());
			$data["uid"]            = session("uid");
			$data["group_type"]     = "profile";
			$data["account_id"]     = $account->id;
			$data["account_name"]   = $group;
			$data["group_id"]       = $group;
			$data["name"]           = $group;
			$data["privacy"]        = 0;
			$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
			$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show + $list_deplay[$key]);
			$data["status"]         = 1;
			$data["deplay"]         = $deplay;
			$data["changed"]        = NOW;
			$data["created"]        = NOW;

			$this->db->insert(INSTAGRAM_SCHEDULES, $data);
			$count++;
		}

		if($count != 0){
			ms(array(
				"st"    => "success",
				"label" => "bg-green",
				"txt"   => l('Successfully')
			));
		}else{
			ms(array(
				"st"    => "valid",
				"label" => "bg-red",
				"txt"   => l('The error occurred during processing')
			));
		}
	}
}