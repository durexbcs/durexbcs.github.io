<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class dashboard extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$result = $this->model->fetch("*", INSTAGRAM_SCHEDULES, getDatabyUser(0), "id", "desc", 0, 10000);
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNTS, getDatabyUser(0));
		$activity = $this->model->getActivitys();

		//PROCESS GROUPS
		$group_count = array(
			"profile" => count($accounts),
		);


		//PROCESS SCHEDULE
		$minday = "";
		$maxday = "";

		$total = array(
			"total"        => 0,
			"queue"        => 0,
			"success"      => 0,
			"failure"      => 0,
			"processing"   => 0,
			"repeat"       => 0,
		);

		//POST
		$post = array(
			"total"        => 0,
			"queue"        => 0,
			"success"      => 0,
			"failure"      => 0,
			"processing"   => 0,
			"repeat"       => 0,
		);
		$post_day  = array();

		//POST
		$message = array(
			"total"        => 0,
			"queue"        => 0,
			"success"      => 0,
			"failure"      => 0,
			"processing"   => 0,
			"repeat"       => 0,
		);
		$message_day  = array();

		if(!empty($post)){
			foreach ($result as $key => $row) {
				switch ($row->category) {
					case 'post':
						$post['total']++;
						$total['total']++;
						switch ($row->status) {
							case 1:
								$post['processing']++;
								$total['processing']++;
								break;
							case 2:
								$post['queue']++;
								$total['queue']++;
								break;
							case 3:
								$post['success']++;
								$total['success']++;

								//Process day
								if(compare_day($minday, $row->created)){
									$minday = date("Y-m-d", strtotime($row->created));
								}

								if(compare_day($maxday, $row->created, "max")){
									$maxday = date("Y-m-d", strtotime($row->created));
								}

								//Process chart day
								$date = date("Y-m-d", strtotime($row->created));
								if(!isset($post_day[$date])){
									$post_day[$date] = 0;
								}
								$post_day[$date] += 1;
								break;
							case 4:
								$post['failure']++;
								$total['failure']++;
								break;
							case 5:
								$post['repeat']++;
								$total['repeat']++;
								break;
						}
						break;
					case 'message':
						$post['total']++;
						$total['total']++;
						switch ($row->status) {
							case 1:
								$post['processing']++;
								$total['processing']++;
								break;
							case 2:
								$post['queue']++;
								$total['queue']++;
								break;
							case 3:
								$post['success']++;
								$total['success']++;

								//Process day
								if(compare_day($minday, $row->created)){
									$minday = date("Y-m-d", strtotime($row->created));
								}

								if(compare_day($maxday, $row->created, "max")){
									$maxday = date("Y-m-d", strtotime($row->created));
								}

								//Process chart day
								$date = date("Y-m-d", strtotime($row->created));
								if(!isset($post_day[$date])){
									$post_day[$date] = 0;
								}
								$post_day[$date] += 1;
								break;
							case 4:
								$post['failure']++;
								$total['failure']++;
								break;
							case 5:
								$post['repeat']++;
								$total['repeat']++;
								break;
						}
						break;
				}
			}
		}



		$data = array(
			'group'             => (object)$group_count,
			'total'             => (object)$total,
			'post'              => (object)$post,
			'message'           => (object)$message,
			'activity'          => $activity
		);

		$this->template->title('Dashboard');
		$this->template->build('index', $data);
	}
	
}