<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class schedules extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$type = segment(2);
		if(!$type) redirect(PATH);

		$data = array();
		$this->template->title(l('Schedules'));
		$this->template->build('index', $data);
	}

	public function ajax_page(){
		$results = $this->model->get_cd_list();
        echo json_encode($results);
	}

	public function ajax_add_schedules(){
		$data = array();
		$groups = $this->input->post('accounts');

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
			case 'like':
				if(count($groups) == 0){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Select at least one account instagram')
					));
				}

				//Target
				$target = array();
				if(post("enable_tag")){ $target['tag'] = 1; }
				if(post("enable_timeline")){ $target['timeline'] = 1; }
				if(post("enable_popular")){ $target['popular'] = 1; }
				if(post("enable_your_feed")){ $target['your_feed'] = 1; }
				if(post("enable_explore_tab")){ $target['explore_tab'] = 1; }

				//Tags
				$tags = post('tags');

				$data = array(
					"category"    => post('type'),
					"type"        => post('type'),
					"title"       => json_encode((array)$target),
					"comment"     => json_encode((array)$tags)
				);
				break;
			case 'comment':
				if(count($groups) == 0){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Select at least one account instagram')
					));
				}

				//Target
				$target = array();
				if(post("enable_tag")){ $target['tag'] = 1; }
				if(post("enable_timeline")){ $target['timeline'] = 1; }
				if(post("enable_popular")){ $target['popular'] = 1; }
				if(post("enable_your_feed")){ $target['your_feed'] = 1; }
				if(post("enable_explore_tab")){ $target['explore_tab'] = 1; }

				//Tags
				$tags = post('tags');
				$comments = post('comments');

				$data = array(
					"category"    => post('type'),
					"type"        => post('type'),
					"title"       => json_encode((array)$target),
					"description" => json_encode((array)$tags),
					"message"     => json_encode((array)$comments),
				);
				break;
			case 'follow':
				if(count($groups) == 0){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Select at least one account instagram')
					));
				}

				//Target
				$target = array();
				if(post("enable_tag")){ $target['tag'] = 1; }
				if(post("enable_location")){ $target['location'] = 1; }
				if(post("enable_username")){ $target['username'] = 1; }

				//Tags
				$tags = post('tags');
				$locations = post('locations');
				$usernames = post('usernames');

				$data = array(
					"category"    => post('type'),
					"type"        => post('type'),
					"title"       => json_encode((array)$target),
					"description" => json_encode((array)$tags),
					"url"         => json_encode((array)$locations),
					"image"       => json_encode((array)$usernames),
				);
				break;
			case 'unfollow':
				if(count($groups) == 0){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Select at least one account instagram')
					));
				}

				$data = array(
					"category"    => post('type'),
					"type"        => post('type')
				);
				break;
			case 'followback':
				if(count($groups) == 0){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Select at least one account instagram')
					));
				}

				//Message
				$messages = post('messages');

				$data = array(
					"category"    => post('type'),
					"type"        => post('type'),
					"message"     => json_encode((array)$messages)
				);
				break;
			case 'repost':
				if(count($groups) == 0){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Select at least one account instagram')
					));
				}
				//Target
				$target = array();
				if(post("enable_tag")){ $target['tag'] = 1; }
				if(post("enable_location")){ $target['location'] = 1; }
				if(post("enable_username")){ $target['username'] = 1; }

				//Tags
				$tags = post('tags');
				$locations = post('locations');
				$usernames = post('usernames');
				$data = array(
					"category"    => post('type'),
					"type"        => post('type'),
					"title"       => json_encode((array)$target),
					"description" => json_encode((array)$tags),
					"url"         => json_encode((array)$locations),
					"image"       => json_encode((array)$usernames),
				);
				break;
			case 'message':
				if(count($groups) == 0){
					ms(array(
						"st"    => "valid",
						"label" => "bg-red",
						"txt"   => l('Select at least one account instagram')
					));
				}

				//Target
				$target = array();
				if(post("enable_tag")){ $target['tag'] = 1; }
				if(post("enable_location")){ $target['location'] = 1; }

				//Tags
				$tags = post('tags');
				$locations = post('locations');

				$data = array(
					"category"    => post('type'),
					"type"        => post('type'),
					"title"       => json_encode((array)$target),
					"description" => json_encode((array)$tags),
					"url"         => json_encode((array)$locations),
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

		$PostonHour = (int)post("repeat");
		$repeat     = 60/$PostonHour*60;

		$data["speed"]       = (int)post("speed");
		$data["repeat_post"] = 1;
		$data["repeat_time"] = $repeat;
		$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
		
		$count = 0;
		$deplay = (int)post('delay')*60;
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

		$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
		$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
		$time_post = $date->format('Y-m-d H:i:s');
		foreach ($groups as $key => $group) {
			$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
			$data["uid"]            = session("uid");
			$data["group_type"]     = "profile";
			$data["account_id"]     = $account->id;
			$data["account_name"]   = $account->username;
			$data["group_id"]       = $group;
			$data["name"]           = $group;
			$data["privacy"]        = 0;
			$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
			$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
			$data["status"]         = 1;
			$data["caption"]        = $PostonHour;
			$data["deplay"]         = $deplay;
			$data["changed"]        = NOW;
			$data["created"]        = NOW;

			$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
			if(!empty($check)){
				$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
			}else{
				$this->db->insert(INSTAGRAM_SCHEDULES, $data);
			}
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

	public function ajax_add_multi_schedules(){
		$count = 0;
		$groups = $this->input->post('accounts');

		if(!check_expiration()  && session("admin") != 1){
			if(post('video_url') == ""){
				ms(array(
					"st"    => "valid",
					"label" => "bg-red",
					"txt"   => l('Notice: Out of date! System auto stop all activity on all your instagram accounts.')
				));
			}
		}

		if(count($groups) == 0){
			ms(array(
				"st"    => "valid",
				"label" => "bg-red",
				"txt"   => l('Select at least one account instagram')
			));
		}

		$filter = array(
			"media_age" => post("filter_media_age"),
			"media_type" => post("filter_media_type"),
			"min_likes" => (int)post("filter_min_likes"),
			"max_likes" => (int)post("filter_max_likes"),
			"min_comments" => (int)post("filter_min_comments"),
			"max_comments" => (int)post("filter_max_comments"),
			"user_relation" => post("filter_user_relation"),
			"user_profile" => post("filter_user_profile"),
			"min_followers" => (int)post("filter_min_followers"),
			"max_followers" => (int)post("filter_max_followers"),
			"min_followings" => (int)post("filter_min_followings"),
			"max_followings" => (int)post("filter_max_followings"),
			"gender" => post("filter_gender")
		);

		if(post("todo_like")){
			$data = array();

			//------------------------//
			//Target
			$target = array();
			if(post("enable_tag")){ $target['tag'] = 1; }
			if(post("enable_timeline")){ $target['timeline'] = 1; }
			if(post("enable_popular")){ $target['popular'] = 1; }
			if(post("enable_your_feed")){ $target['your_feed'] = 1; }
			if(post("enable_explore_tab")){ $target['explore_tab'] = 1; }
			if(post("enable_location")){ $target['location'] = 1; }
			if(post("enable_username")){ $target['username'] = 1; }

			//Tags
			$tags = post('tags');
			$locations = post('locations');
			$usernames = post('usernames');

			$data = array(
				"category"    => "like",
				"type"        => "like",
				"title"       => json_encode((array)$target),
				"description" => json_encode((array)$tags),
				"url"         => json_encode((array)$locations),
				"image"       => json_encode((array)$usernames),
				"filter"      => json_encode((array)$filter)
			);
			//------------------------//

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

			$PostonHour = (int)post("repeat_like");
			$repeat     = 60/$PostonHour*60;

			$data["speed"]       = (int)post("speed");
			$data["repeat_post"] = 1;
			$data["repeat_time"] = $repeat;
			$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
			
			$deplay = (int)post('delay')*60;
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

			$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
			$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
			$time_post = $date->format('Y-m-d H:i:s');
			foreach ($groups as $key => $group) {
				$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
				$data["uid"]            = session("uid");
				$data["group_type"]     = "profile";
				$data["account_id"]     = $account->id;
				$data["account_name"]   = $account->username;
				$data["group_id"]       = $group;
				$data["name"]           = $group;
				$data["privacy"]        = 0;
				$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
				$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
				$data["caption"]        = $PostonHour;
				$data["deplay"]         = $deplay;
				$data["changed"]        = NOW;
				
				$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
				if(!empty($check)){
					$data["status"] = 1;
					$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
				}else{
					$data["created"] = NOW;
					$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				}
				$count++;
			}
		}else{
			foreach ($groups as $key => $group) {
				$this->db->delete(INSTAGRAM_SCHEDULES, array("account_id" => $group, "category" => "like"));
			}
		}

		if(post("todo_comment")){
			$data = array();

			//------------------------//
			//Target
			$target = array();
			if(post("enable_tag")){ $target['tag'] = 1; }
			if(post("enable_timeline")){ $target['timeline'] = 1; }
			if(post("enable_popular")){ $target['popular'] = 1; }
			if(post("enable_your_feed")){ $target['your_feed'] = 1; }
			if(post("enable_explore_tab")){ $target['explore_tab'] = 1; }
			if(post("enable_location")){ $target['location'] = 1; }
			if(post("enable_username")){ $target['username'] = 1; }

			//Tags
			$tags = post('tags');
			$locations = post('locations');
			$usernames = post('usernames');
			$comments = post('comments');

			$data = array(
				"category"    => "comment",
				"type"        => "comment",
				"title"       => json_encode((array)$target),
				"description" => json_encode((array)$tags),
				"comment"     => json_encode((array)$comments),
				"url"         => json_encode((array)$locations),
				"image"       => json_encode((array)$usernames),
				"filter"      => json_encode((array)$filter)
			);			
			//-----------------------//

			if(post('time_post') == ""){
				$json[] = array(
					"st"    => "valid",
					"label" => "bg-red",
					"text"  => l('Time post is required')
				);
			}

			$PostonHour = (int)post("repeat_comment");
			$repeat     = 60/$PostonHour*60;

			$data["speed"]       = (int)post("speed");
			$data["repeat_post"] = 1;
			$data["repeat_time"] = $repeat;
			$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
			
			$deplay = (int)post('delay')*60;
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

			$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
			$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
			$time_post = $date->format('Y-m-d H:i:s');
			foreach ($groups as $key => $group) {
				$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
				$data["uid"]            = session("uid");
				$data["group_type"]     = "profile";
				$data["account_id"]     = $account->id;
				$data["account_name"]   = $account->username;
				$data["group_id"]       = $group;
				$data["name"]           = $group;
				$data["privacy"]        = 0;
				$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
				$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
				$data["caption"]        = $PostonHour;
				$data["deplay"]         = $deplay;
				$data["changed"]        = NOW;

				$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
				if(!empty($check)){
					$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					$data["status"] = 1;
				}else{
					$data["created"] = NOW;
					$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				}
				$count++;
			}
		}else{
			foreach ($groups as $key => $group) {
				$this->db->delete(INSTAGRAM_SCHEDULES, array("account_id" => $group, "category" => "comment"));
			}
		}

		if(post("todo_follow")){
			$data = array();

			//------------------------//
			//Target
			$target = array();
			if(post("enable_tag")){ $target['tag'] = 1; }
			if(post("enable_location")){ $target['location'] = 1; }
			if(post("enable_username")){ $target['username'] = 1; }

			//Tags
			$tags = post('tags');
			$locations = post('locations');
			$usernames = post('usernames');

			$data = array(
				"category"    => "follow",
				"type"        => "follow",
				"title"       => json_encode((array)$target),
				"description" => json_encode((array)$tags),
				"url"         => json_encode((array)$locations),
				"image"       => json_encode((array)$usernames),
				"filter"      => json_encode((array)$filter)
			);			
			//-----------------------//

			if(post('time_post') == ""){
				$json[] = array(
					"st"    => "valid",
					"label" => "bg-red",
					"text"  => l('Time post is required')
				);
			}

			$PostonHour = (int)post("repeat_follow");
			$repeat     = 60/$PostonHour*60;

			$data["speed"]       = (int)post("speed");
			$data["repeat_post"] = 1;
			$data["repeat_time"] = $repeat;
			$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
			
			$deplay = (int)post('delay')*60;
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

			$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
			$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
			$time_post = $date->format('Y-m-d H:i:s');
			foreach ($groups as $key => $group) {
				$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
				$data["uid"]            = session("uid");
				$data["group_type"]     = "profile";
				$data["account_id"]     = $account->id;
				$data["account_name"]   = $account->username;
				$data["group_id"]       = $group;
				$data["name"]           = $group;
				$data["privacy"]        = 0;
				$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
				$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
				$data["caption"]        = $PostonHour;
				$data["deplay"]         = $deplay;
				$data["changed"]        = NOW;

				$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
				if(!empty($check)){
					$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					$data["status"] = 1;
				}else{
					$data["created"] = NOW;
					$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				}
				$count++;
			}
		}else{
			foreach ($groups as $key => $group) {
				$this->db->delete(INSTAGRAM_SCHEDULES, array("account_id" => $group, "category" => "follow"));
			}
		}

		if(post("todo_followback")){
			$data = array();

			//------------------------//
			//Message
			$messages = post('messages');

			$data = array(
				"category"    => "followback",
				"type"        => "followback",
				"message"     => json_encode((array)$messages),
				"filter"      => json_encode((array)$filter)
			);	
			//-----------------------//

			if(post('time_post') == ""){
				$json[] = array(
					"st"    => "valid",
					"label" => "bg-red",
					"text"  => l('Time post is required')
				);
			}

			$PostonHour = (int)post("repeat_followback");
			$repeat     = 60/$PostonHour*60;

			$data["speed"]       = (int)post("speed");
			$data["repeat_post"] = 1;
			$data["repeat_time"] = $repeat;
			$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
			
			$deplay = (int)post('delay')*60;
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

			$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
			$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
			$time_post = $date->format('Y-m-d H:i:s');
			foreach ($groups as $key => $group) {
				$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
				$data["uid"]            = session("uid");
				$data["group_type"]     = "profile";
				$data["account_id"]     = $account->id;
				$data["account_name"]   = $account->username;
				$data["group_id"]       = $group;
				$data["name"]           = $group;
				$data["privacy"]        = 0;
				$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
				$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
				$data["caption"]        = $PostonHour;
				$data["deplay"]         = $deplay;
				$data["changed"]        = NOW;

				$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
				if(!empty($check)){
					$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					$data["status"] = 1;
				}else{
					$data["created"] = NOW;
					$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				}
				$count++;
			}
		}else{
			foreach ($groups as $key => $group) {
				$this->db->delete(INSTAGRAM_SCHEDULES, array("account_id" => $group, "category" => "followback"));
			}
		}

		if(post("todo_unfollow")){
			$data = array();

			//------------------------//
			$data = array(
				"category"    => "unfollow",
				"type"        => "unfollow",
				"filter"      => json_encode((array)$filter)
			);
			//-----------------------//

			if(post('time_post') == ""){
				$json[] = array(
					"st"    => "valid",
					"label" => "bg-red",
					"text"  => l('Time post is required')
				);
			}

			$PostonHour = (int)post("repeat_unfollow");
			$repeat     = 60/$PostonHour*60;

			$data["speed"]       = (int)post("speed");
			$data["repeat_post"] = 1;
			$data["repeat_time"] = $repeat;
			$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
			
			$deplay = (int)post('delay')*60;
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

			$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
			$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
			$time_post = $date->format('Y-m-d H:i:s');
			foreach ($groups as $key => $group) {
				$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
				$data["uid"]            = session("uid");
				$data["group_type"]     = "profile";
				$data["account_id"]     = $account->id;
				$data["account_name"]   = $account->username;
				$data["group_id"]       = $group;
				$data["name"]           = $group;
				$data["privacy"]        = 0;
				$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
				$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
				$data["caption"]        = $PostonHour;
				$data["deplay"]         = $deplay;
				$data["changed"]        = NOW;

				$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
				if(!empty($check)){
					$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					$data["status"] = 1;
				}else{
					$data["created"] = NOW;
					$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				}
				$count++;
			}
		}else{
			foreach ($groups as $key => $group) {
				$this->db->delete(INSTAGRAM_SCHEDULES, array("account_id" => $group, "category" => "unfollow"));
			}
		}

		if(post("todo_repost")){
			$data = array();

			//------------------------//
			//Target
			$target = array();
			if(post("enable_tag")){ $target['tag'] = 1; }
			if(post("enable_location")){ $target['location'] = 1; }
			if(post("enable_username")){ $target['username'] = 1; }

			//Tags
			$tags = post('tags');
			$locations = post('locations');
			$usernames = post('usernames');

			$data = array(
				"category"    => "repost",
				"type"        => "repost",
				"title"       => json_encode((array)$target),
				"description" => json_encode((array)$tags),
				"url"         => json_encode((array)$locations),
				"image"       => json_encode((array)$usernames),
				"filter"      => json_encode((array)$filter)
			);
			//-----------------------//

			if(post('time_post') == ""){
				$json[] = array(
					"st"    => "valid",
					"label" => "bg-red",
					"text"  => l('Time post is required')
				);
			}

			$PostonHour = (int)post("repeat_repost");
			$repeat     = 60/$PostonHour*60;

			$data["speed"]       = (int)post("speed");
			$data["repeat_post"] = 1;
			$data["repeat_time"] = $repeat;
			$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
			
			$deplay = (int)post('delay')*60;
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

			$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
			$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
			$time_post = $date->format('Y-m-d H:i:s');
			foreach ($groups as $key => $group) {
				$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
				$data["uid"]            = session("uid");
				$data["group_type"]     = "profile";
				$data["account_id"]     = $account->id;
				$data["account_name"]   = $account->username;
				$data["group_id"]       = $group;
				$data["name"]           = $group;
				$data["privacy"]        = 0;
				$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
				$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
				$data["caption"]        = $PostonHour;
				$data["deplay"]         = $deplay;
				$data["changed"]        = NOW;

				$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
				if(!empty($check)){
					$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					$data["status"] = 1;
				}else{
					$data["created"] = NOW;
					$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				}
				$count++;
			}
		}else{
			foreach ($groups as $key => $group) {
				$this->db->delete(INSTAGRAM_SCHEDULES, array("account_id" => $group, "category" => "repost"));
			}
		}

		if(post("todo_deletemedia")){
			$data = array();

			//------------------------//
			//Target
			$target = array();

			//Tags
			$tags = post('tags');
			$locations = post('locations');

			$data = array(
				"category"    => "deletemedia",
				"type"        => "deletemedia",
				"title"       => json_encode((array)$target),
				"filter"      => json_encode((array)$filter)
			);
			//-----------------------//

			if(post('time_post') == ""){
				$json[] = array(
					"st"    => "valid",
					"label" => "bg-red",
					"text"  => l('Time post is required')
				);
			}

			$PostonHour = (int)post("repeat_deletemedia");
			$repeat     = 60/$PostonHour*60;

			$data["speed"]       = (int)post("speed");
			$data["repeat_post"] = 1;
			$data["repeat_time"] = $repeat;
			$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
			
			$deplay = (int)post('delay')*60;
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

			$date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_USER));
			$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
			$time_post = $date->format('Y-m-d H:i:s');
			foreach ($groups as $key => $group) {
				$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
				$data["uid"]            = session("uid");
				$data["group_type"]     = "profile";
				$data["account_id"]     = $account->id;
				$data["account_name"]   = $account->username;
				$data["group_id"]       = $group;
				$data["name"]           = $group;
				$data["privacy"]        = 0;
				$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post) + $list_deplay[$key]);
				$data["time_post_show"] = date("Y-m-d H:i:s", $time_now + $list_deplay[$key]);
				$data["caption"]        = $PostonHour;
				$data["deplay"]         = $deplay;
				$data["changed"]        = NOW;

				$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$account->id."' AND category = '".$data['category']."'");
				if(!empty($check)){
					$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					$data["status"] = 1;
				}else{
					$data["created"] = NOW;
					$this->db->insert(INSTAGRAM_SCHEDULES, $data);
				}
				$count++;
			}
		}else{
			foreach ($groups as $key => $group) {
				$this->db->delete(INSTAGRAM_SCHEDULES, array("account_id" => $group, "category" => "deletemedia"));
			}
		}

		//-----------Save Activity-------------//
		//Target
		$todo = array(
			'like' => post("todo_like")?1:0,
			'comment'  => post("todo_comment")?1:0,
			'follow'  => post("todo_follow")?1:0,
			'followback'  => post("todo_followback")?1:0,
			'unfollow'  => post("todo_unfollow")?1:0,
			'repost'  => post("todo_repost")?1:0,
			'deletemedia'  => post("todo_deletemedia")?1:0,
		);

		//Target
		$targets = array(
			'tag' => post("enable_tag")?1:0,
			'location'  => post("enable_location")?1:0,
			'username'  => post("enable_username")?1:0,
			'timeline'  => post("enable_timeline")?1:0,
			'popular'  => post("enable_popular")?1:0,
			'your_feed'  => post("enable_your_feed")?1:0,
			'explore_tab'  => post("enable_explore_tab")?1:0
		);

		//Tags
		$tags = post('tags');
		$locations = post('locations');
		$usernames = post('usernames');
		$comments = post('comments');
		$messages = post('messages');

		//Speed
		$speed = array(
			"repost" => (int)post("repeat_repost"),
			"like" => (int)post("repeat_like"),
			"comment" => (int)post("repeat_comment"),
			"deletemedia" => (int)post("repeat_deletemedia"),
			"follow" => (int)post("repeat_follow"),
			"followback" => (int)post("repeat_followback"),
			"unfollow" => (int)post("repeat_unfollow"),
			"delay" => (int)post("delay"),
			"type" => (int)post("speed"),
		);

		$data_activity = array(
			"todo" => json_encode($todo),
			"targets" => json_encode($targets),
			"speed" => json_encode($speed),
			"tags" => json_encode($tags),
			"usernames" => json_encode($usernames),
			"locations" => json_encode($locations),
			"comments" => json_encode($comments),
			"messages" => json_encode($messages),
			"filter" => json_encode((array)$filter)
		);

		//-----------Save Activity-------------//
		foreach ($groups as $key => $group) {
			$account = $this->model->get("*", INSTAGRAM_ACCOUNTS, "id = '".$group."'".getDatabyUser());
			$activity = array(
				"uid" => session("uid"),
				"account_id" => $account->id,
				"account_name" => $account->username,
				"data" => json_encode($data_activity),
				"status" => 1,
				"created" => NOW
			);

			$check = $this->model->get("*", INSTAGRAM_ACTIVITY, "account_id = '".$account->id."'".getDatabyUser());
			if(!empty($check)){
				$this->db->update(INSTAGRAM_ACTIVITY, $activity, array("id" => $check->id));
			}else{
				$this->db->insert(INSTAGRAM_ACTIVITY, $activity);
			}
		}
		ms(array(
			"st"    => "success",
			"label" => "bg-green",
			"txt"   => l('Successfully')
		));
	}

	public function ajax_enable_activity(){
		$id = (int)post("id");
		$activity = $this->model->get("*", INSTAGRAM_ACTIVITY, "id = '".$id."'".getDatabyUser());

		if(!check_expiration()  && session("admin") != 1){
			if(post('video_url') == ""){
				ms(array(
					"st"    => "valid",
					"label" => "bg-red",
					"txt"   => l('Notice: Out of date! System auto stop all activity on all your instagram accounts.')
				));
			}
		}
		
		if(!empty($activity)){
			if($activity->status == 3 || $activity->status == 1){
				$schedule = json_decode($activity->data);
				$todo = json_decode($schedule->todo);
				$targets = json_decode($schedule->targets);
				$comments = json_decode($schedule->comments);
				$locations = json_decode($schedule->locations);
				$usernames = json_decode($schedule->usernames);
				$messages = json_decode($schedule->messages);
				$speed = json_decode($schedule->speed);
				$tags = json_decode($schedule->tags);

				if($todo->like == 1){
					$data = array();

					//------------------------//
					//Target
					$target = array();
					if($targets->tag == 1){ $target['tag'] = 1; }
					if($targets->location == 1){ $target['location'] = 1; }
					if($targets->username == 1){ $target['username'] = 1; }
					if($targets->timeline == 1){ $target['timeline'] = 1; }
					if($targets->popular == 1){ $target['popular'] = 1; }
					if($targets->your_feed == 1){ $target['your_feed'] = 1; }
					if($targets->explore_tab == 1){ $target['explore_tab'] = 1; }

					$data = array(
						"category"    => "like",
						"type"        => "like",
						"title"       => json_encode((array)$target),
						"description" => json_encode((array)$tags)
					);
					//------------------------//

					$PostonHour = (int)$speed->like;
					$repeat     = 60/$PostonHour*60;

					$data["speed"]       = (int)$speed->type;
					$data["repeat_post"] = 1;
					$data["repeat_time"] = $repeat;
					$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
					
					$deplay = (int)$speed->delay*60;
					$time_post_show = strtotime(NOW) + 60;

					$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
					$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
					$time_post = $date->format('Y-m-d H:i:s');
					$data["uid"]            = session("uid");
					$data["group_type"]     = "profile";
					$data["account_id"]     = $activity->account_id;
					$data["account_name"]   = $activity->account_name;
					$data["group_id"]       = $activity->account_id;
					$data["name"]           = $activity->account_id;
					$data["privacy"]        = 0;
					$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post));
					$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show);
					$data["caption"]        = $PostonHour;
					$data["deplay"]         = $deplay;
					$data["status"]         = 5;
					$data["changed"]        = NOW;

					$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$activity->account_id."' AND category = '".$data['category']."'");
					if(!empty($check)){
						$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					}else{
						$data["created"]        = NOW;
						$this->db->insert(INSTAGRAM_SCHEDULES, $data);
					}
				}

				if($todo->comment == 1){
					$data = array();

					//------------------------//
					//Target
					$target = array();
					if($targets->tag == 1){ $target['tag'] = 1; }
					if($targets->location == 1){ $target['location'] = 1; }
					if($targets->username == 1){ $target['username'] = 1; }
					if($targets->timeline == 1){ $target['timeline'] = 1; }
					if($targets->popular == 1){ $target['popular'] = 1; }
					if($targets->your_feed == 1){ $target['your_feed'] = 1; }
					if($targets->explore_tab == 1){ $target['explore_tab'] = 1; }

					$data = array(
						"category"    => "comment",
						"type"        => "comment",
						"title"       => json_encode((array)$target),
						"description" => json_encode((array)$tags),
						"comment"     => json_encode((array)$comments),
					);		
					//------------------------//

					$PostonHour = (int)$speed->comment;
					$repeat     = 60/$PostonHour*60;

					$data["speed"]       = (int)$speed->type;
					$data["repeat_post"] = 1;
					$data["repeat_time"] = $repeat;
					$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
					
					$deplay = (int)$speed->delay*60;
					$time_post_show = strtotime(NOW) + 60;

					$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
					$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
					$time_post = $date->format('Y-m-d H:i:s');
					$data["uid"]            = session("uid");
					$data["group_type"]     = "profile";
					$data["account_id"]     = $activity->account_id;
					$data["account_name"]   = $activity->account_name;
					$data["group_id"]       = $activity->account_id;
					$data["name"]           = $activity->account_id;
					$data["privacy"]        = 0;
					$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post));
					$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show);
					$data["caption"]        = $PostonHour;
					$data["deplay"]         = $deplay;
					$data["status"]         = 5;
					$data["changed"]        = NOW;

					$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$activity->account_id."' AND category = '".$data['category']."'");
					if(!empty($check)){
						$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					}else{
						$data["created"]        = NOW;
						$this->db->insert(INSTAGRAM_SCHEDULES, $data);
					}
				}

				if($todo->follow == 1){
					$data = array();

					//------------------------//
					//Target
					$target = array();
					if($targets->tag == 1){ $target['tag'] = 1; }
					if($targets->location == 1){ $target['location'] = 1; }
					if($targets->username == 1){ $target['username'] = 1; }

					$data = array(
						"category"    => "follow",
						"type"        => "follow",
						"title"       => json_encode((array)$target),
						"description" => json_encode((array)$tags),
						"url"         => json_encode((array)$locations),
						"image"       => json_encode((array)$usernames),
					);			
					//------------------------//

					$PostonHour = (int)$speed->follow;
					$repeat     = 60/$PostonHour*60;

					$data["speed"]       = (int)$speed->type;
					$data["repeat_post"] = 1;
					$data["repeat_time"] = $repeat;
					$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
					
					$deplay = (int)$speed->delay*60;
					$time_post_show = strtotime(NOW) + 60;

					$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
					$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
					$time_post = $date->format('Y-m-d H:i:s');
					$data["uid"]            = session("uid");
					$data["group_type"]     = "profile";
					$data["account_id"]     = $activity->account_id;
					$data["account_name"]   = $activity->account_name;
					$data["group_id"]       = $activity->account_id;
					$data["name"]           = $activity->account_id;
					$data["privacy"]        = 0;
					$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post));
					$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show);
					$data["caption"]        = $PostonHour;
					$data["deplay"]         = $deplay;
					$data["status"]         = 5;
					$data["changed"]        = NOW;

					$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$activity->account_id."' AND category = '".$data['category']."'");
					if(!empty($check)){
						$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					}else{
						$data["created"]        = NOW;
						$this->db->insert(INSTAGRAM_SCHEDULES, $data);
					}
				}

				if($todo->followback == 1){
					$data = array();

					//------------------------//
					//Target
					$data = array(
						"category"    => "followback",
						"type"        => "followback",
						"message"     => json_encode((array)$messages)
					);		
					//------------------------//

					$PostonHour = (int)$speed->followback;
					$repeat     = 60/$PostonHour*60;

					$data["speed"]       = (int)$speed->type;
					$data["repeat_post"] = 1;
					$data["repeat_time"] = $repeat;
					$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
					
					$deplay = (int)$speed->delay*60;
					$time_post_show = strtotime(NOW) + 60;

					$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
					$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
					$time_post = $date->format('Y-m-d H:i:s');
					$data["uid"]            = session("uid");
					$data["group_type"]     = "profile";
					$data["account_id"]     = $activity->account_id;
					$data["account_name"]   = $activity->account_name;
					$data["group_id"]       = $activity->account_id;
					$data["name"]           = $activity->account_id;
					$data["privacy"]        = 0;
					$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post));
					$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show);
					$data["caption"]        = $PostonHour;
					$data["deplay"]         = $deplay;
					$data["status"]         = 5;
					$data["changed"]        = NOW;

					$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$activity->account_id."' AND category = '".$data['category']."'");
					if(!empty($check)){
						$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					}else{
						$data["created"]        = NOW;
						$this->db->insert(INSTAGRAM_SCHEDULES, $data);
					}
				}

				if($todo->unfollow == 1){
					$data = array();

					//------------------------//
					//Target
					$data = array(
						"category"    => "unfollow",
						"type"        => "unfollow"
					);		
					//------------------------//

					$PostonHour = (int)$speed->followback;
					$repeat     = 60/$PostonHour*60;

					$data["speed"]       = (int)$speed->type;
					$data["repeat_post"] = 1;
					$data["repeat_time"] = $repeat;
					$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
					
					$deplay = (int)$speed->delay*60;
					$time_post_show = strtotime(NOW) + 60;

					$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
					$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
					$time_post = $date->format('Y-m-d H:i:s');
					$data["uid"]            = session("uid");
					$data["group_type"]     = "profile";
					$data["account_id"]     = $activity->account_id;
					$data["account_name"]   = $activity->account_name;
					$data["group_id"]       = $activity->account_id;
					$data["name"]           = $activity->account_id;
					$data["privacy"]        = 0;
					$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post));
					$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show);
					$data["caption"]        = $PostonHour;
					$data["deplay"]         = $deplay;
					$data["status"]         = 5;
					$data["changed"]        = NOW;

					$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$activity->account_id."' AND category = '".$data['category']."'");
					if(!empty($check)){
						$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					}else{
						$data["created"]        = NOW;
						$this->db->insert(INSTAGRAM_SCHEDULES, $data);
					}
				}
				if($todo->repost == 1){
					$data = array();

					//------------------------//
					//Target
					$target = array();
					if($targets->tag == 1){ $target['tag'] = 1; }
					if($targets->location == 1){ $target['location'] = 1; }
					if($targets->username == 1){ $target['username'] = 1; }

					//Tags
					$data = array(
						"category"    => 'repost',
						"type"        => 'repost',
						"title"       => json_encode((array)$target),
						"description" => json_encode((array)$tags),
						"url"         => json_encode((array)$locations),
						"image"       => json_encode((array)$usernames),
					);
					//------------------------//

					$PostonHour = (int)$speed->repost;
					$repeat     = 60/$PostonHour*60;

					$data["speed"]       = (int)$speed->type;
					$data["repeat_post"] = 1;
					$data["repeat_time"] = $repeat;
					$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
					
					$deplay = (int)$speed->delay*60;
					$time_post_show = strtotime(NOW) + 60;

					$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
					$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
					$time_post = $date->format('Y-m-d H:i:s');
					$data["uid"]            = session("uid");
					$data["group_type"]     = "profile";
					$data["account_id"]     = $activity->account_id;
					$data["account_name"]   = $activity->account_name;
					$data["group_id"]       = $activity->account_id;
					$data["name"]           = $activity->account_id;
					$data["privacy"]        = 0;
					$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post));
					$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show);
					$data["caption"]        = $PostonHour;
					$data["deplay"]         = $deplay;
					$data["status"]         = 5;
					$data["changed"]        = NOW;


					$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$activity->account_id."' AND category = '".$data['category']."'");
					if(!empty($check)){
						$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					}else{
						$data["created"]        = NOW;
						$this->db->insert(INSTAGRAM_SCHEDULES, $data);
					}
				}

				if($todo->deletemedia == 1){
					$data = array();

					//------------------------//
					//Target
					$target = array();
					if($targets->tag == 1){ $target['tag'] = 1; }
					if($targets->username == 1){ $target['username'] = 1; }
					if($targets->location == 1){ $target['location'] = 1; }

					$data = array(
						"category"    => "deletemedia",
						"type"        => "deletemedia",
						"title"       => json_encode((array)$target),
						"description" => json_encode((array)$tags),
						"url"         => json_encode((array)$locations),
					);
					//------------------------//

					$PostonHour = (int)$speed->deletemedia;
					$repeat     = 60/$PostonHour*60;

					$data["speed"]       = (int)$speed->type;
					$data["repeat_post"] = 1;
					$data["repeat_time"] = $repeat;
					$data["repeat_end"]  = date("Y-m-d", strtotime("2025-01-01"));
					
					$deplay = (int)$speed->delay*60;
					$time_post_show = strtotime(NOW) + 60;

					$date = new DateTime(date("Y-m-d H:i:s", $time_post_show), new DateTimeZone(TIMEZONE_USER));
					$date->setTimezone(new DateTimeZone(TIMEZONE_SYSTEM));
					$time_post = $date->format('Y-m-d H:i:s');
					$data["uid"]            = session("uid");
					$data["group_type"]     = "profile";
					$data["account_id"]     = $activity->account_id;
					$data["account_name"]   = $activity->account_name;
					$data["group_id"]       = $activity->account_id;
					$data["name"]           = $activity->account_id;
					$data["privacy"]        = 0;
					$data["time_post"]      = date("Y-m-d H:i:s", strtotime($time_post));
					$data["time_post_show"] = date("Y-m-d H:i:s", $time_post_show);
					$data["caption"]        = $PostonHour;
					$data["deplay"]         = $deplay;
					$data["status"]         = 5;
					$data["changed"]        = NOW;


					$check = $this->model->get("*", INSTAGRAM_SCHEDULES, "account_id = '".$activity->account_id."' AND category = '".$data['category']."'");
					if(!empty($check)){
						$this->db->update(INSTAGRAM_SCHEDULES, $data, array("id" => $check->id));
					}else{
						$data["created"]        = NOW;
						$this->db->insert(INSTAGRAM_SCHEDULES, $data);
					}
				}

				$this->db->update(INSTAGRAM_ACTIVITY, array("status" => 5), "id = '".$activity->id."'");

				ms(array(
					"st"    => "success",
					"label" => "bg-green",
					"txt"   => l('Successfully'),
					"status"=> '<span class="badge bg-light-green">'.l('Started').'</span> '.l('Activity'),
					"btn"   => '<button type="button" class="btn bg-grey btn-lg waves-effect uc btnActivityAll" style="width: 28%;">'.l('Stop').'</button>'
				));
			}else{
				$this->db->update(INSTAGRAM_SCHEDULES, array("status" => 3), "account_id = '".$activity->account_id."'");
				$this->db->update(INSTAGRAM_ACTIVITY, array("status" => 3), "id = '".$activity->id."'");
				ms(array(
					"st"    => "success",
					"label" => "bg-green",
					"txt"   => l('Successfully'),
					"status"=> '<span class="badge bg-red">'.l('Stoped').'</span> '.l('Activity'),
					"btn"   => '<button type="button" class="btn bg-red btn-lg waves-effect uc btnActivityAll" style="width: 28%;">'.l('Start').'</button>'
				));
			}
		}else{
			ms(array(
				"st"    => "valid",
				"label" => "bg-red",
				"txt"   => l('Activity not exist')
			));
		}
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', INSTAGRAM_SCHEDULES, "id = '{$id}'".getDatabyUser());
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					$this->db->delete(INSTAGRAM_SCHEDULES, "id = '{$id}'".getDatabyUser());
					break;
				
				case 'active':
					$this->db->update(INSTAGRAM_SCHEDULES, array("status" => 1), "id = '{$id}'".getDatabyUser());
					break;

				case 'disable':
					$this->db->update(INSTAGRAM_SCHEDULES, array("status" => 0), "id = '{$id}'".getDatabyUser());
					break;
			}
		}

		ms(array(
			'st' 	=> 'success',
			'txt' 	=> l('successfully')
		));
	}

	public function ajax_action_multiple(){
		$ids =$this->input->post('id');
		if(!empty($ids)){
			foreach ($ids as $id) {
				$POST = $this->model->get('*', INSTAGRAM_SCHEDULES, "id = '{$id}'".getDatabyUser());
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							$this->db->delete(INSTAGRAM_SCHEDULES, "id = '{$id}'");
							break;

						case 'active':
							$this->db->update(INSTAGRAM_SCHEDULES, array("status" => 1), "id = '{$id}'".getDatabyUser());
							break;

						case 'disable':
							$this->db->update(INSTAGRAM_SCHEDULES, array("status" => 0), "id = '{$id}'".getDatabyUser());
							break;
					}
				}
			}
		}

		if(post("action") == "delete_all"){
			$this->db->delete(INSTAGRAM_SCHEDULES, "category = '".post("category")."'".getDatabyUser());
		}

		ms(array(
			'st' 	=> 'success',
			'txt' 	=> l('Successfully')
		));
	}
}