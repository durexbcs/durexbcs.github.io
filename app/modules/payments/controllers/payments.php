<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class payments extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
		if(hashcheck()){
			redirect(PATH);
		}
	}

	public function index(){
		$data = array(
			"payment" => $this->model->get("*", PAYMENT),
			"package" => $this->model->fetch("*", PACKAGE, "status = 1 AND type = 2", "orders", "ASC")
		);
		$this->template->set_layout("home");
		$this->template->title(l('Pricing'));
		$this->template->build('index', $data);
	}

	public function type(){
		$data = array(
			"payment" => $this->model->get("*", PAYMENT),
			"package" => $this->model->fetch("*", PACKAGE, "status = 1 AND type = 2", "orders", "ASC")
		);
		$this->template->set_layout("home");
		$this->template->title(l('Pricing'));
		$this->template->build('type', $data);
	}
	
	public function do_payment_pagseguro(){
		$payment = $this->model->get("*", PAYMENT);
		$package = $this->model->get("*", PACKAGE, "id = '".(int)get("package")."' AND status = 1");
		if(!empty($package )){
		    $data['email'] = $payment->pagseguro_email;
			$data['token'] = $payment->pagseguro_token;
			$data['currency'] = $payment->currency;
			$data['itemId1'] = $package->id;
			$data['itemDescription1'] = $package->name;
			$data['itemAmount1'] = number_format($package->price,2);
			$data['itemQuantity1'] = '1';
			$data['reference'] = 'REF'.strtoupper(random_string(8));
			$data['redirectURL'] = cn("pagseguro_notify_payment");

		    $header = array('Content-Type' => 'application/json; charset=UTF-8;');
		    if($payment->sandbox == 0){
			    $response = curlExec("https://ws.pagseguro.uol.com.br/v2/checkout", $data, $header);
			    $json = json_decode(json_encode(simplexml_load_string($response)));
		    	header('Location: https://pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $json->code);
		    }else{
		    	$response = curlExec("https://ws.sandbox.pagseguro.uol.com.br/v2/checkout", $data, $header);
			    $json = json_decode(json_encode(simplexml_load_string($response)));
		    	header('Location: https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $json->code);
		    }
		}else{
			redirect(cn());
		}
	}

	public function pagseguro_notify_payment(){
		$payment = $this->model->get("*", PAYMENT);
		$header = array('Content-Type' => 'application/json; charset=UTF-8;');
		if($payment->sandbox == 0){
			$response = curlExec("https://ws.pagseguro.uol.com.br/v2/transactions/".get("transaction_id")."?email=".$payment->pagseguro_email."&token=".$payment->pagseguro_token, null, $header);
	    }else{
			$response = curlExec("https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/".get("transaction_id")."?email=".$payment->pagseguro_email."&token=".$payment->pagseguro_token, null, $header);
	    }
		$result = json_decode(json_encode(simplexml_load_string($response)));

		if(is_object($result)){
			switch ($result->status) {
				case 1:
					$status = "Pending";
					break;
				case 2:
					$status = "Awaiting Fulfillment";
					break;
				case 3:
					$status = "Completed";
					break;
				case 6:
					$status = "Refund";
					break;
				case 7:
					$status = "Cancel";
					break;
				
				default:
					$status = "";
					break;
			}

			$data = array(
				"type"            => "pagseguro",
				"uid"             => session("uid"),
				"invoice"         => $result->code,
				"last_name"       => $result->code,
				"business"        => $result->sender->name,
				"payer_email"     => $result->sender->email,
				"item_name"       => $result->items->item->id,
				"item_number"     => $result->items->item->description,
				"mc_gross"        => $result->grossAmount,
				"feeAmount"       => $result->feeAmount,
				"netAmount"       => $result->netAmount,
				"payment_date"    => date("Y-m-d H:i:s", strtotime($result->lastEventDate)),
				"payment_status"  => $result->status,
				"full_data"       => json_encode($result),
				"created"         => NOW
			);

			$this->db->insert(PAYMENT_HISTORY, $data);
			if($result->status == 3){
				$user = $this->model->get("*", USER_MANAGEMENT, "id = '".session("uid")."'");
				if(!empty($user)){
					$package_new = $this->model->get("*", PACKAGE, "id = '".$result->items->item->id."'");
					$package_old = $this->model->get("*", PACKAGE, "id = '".$user->package_id."'");
					$package_id = $package_new->id;
					if(!empty($package_old)){
						if(strtotime(NOW) < strtotime($user->expiration_date)){
							$date_now = date("Y-m-d", strtotime(NOW));
							$date_expiration = date("Y-m-d", strtotime($user->expiration_date));
							$diff = abs(strtotime($date_expiration) - strtotime($date_now));
							$days = floor($diff/86400);

							$day_added = round(($package_old->price/$package_new->price)*$days);
							$total_day = $package_new->day + $day_added;
							$expiration_date = date('Y-m-d', strtotime("+".$total_day." days"));
						}else{
							$expiration_date = date('Y-m-d', strtotime("+".$package_new->day." days"));
						}
					}else{
						$expiration_date = date('Y-m-d', strtotime("+".$package_new->day." days"));
					}

					$data = array(
						"package_id"      => $package_id,
						"expiration_date" => $expiration_date
					);

					$this->db->update(USER_MANAGEMENT, $data, "id = '".session("uid")."'");
				}
			}
		}

		redirect(PATH);
	}

	public function do_payment(){
		$payment = $this->model->get("*", PAYMENT);
		$payment = $this->model->get("*", PAYMENT);
		$package = $this->model->get("*", PACKAGE, "id = '".(int)get("package")."'");
		if(empty($payment) || empty($package) || !session("uid")) redirect(PATH);

		$config['business'] 			= $payment->paypal_email;
		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
		$config['return'] 				= cn().'notify_payment';
		$config['cancel_return'] 		= cn().'cancel_payment';
		$config['notify_url'] 			= cn().'process_payment'; //IPN Post
		$config['production'] 			= ($payment->sandbox == 1)?FALSE:TRUE; //Its false by default and will use sandbox
		$config["invoice"]				= random_string('numeric',8); //The invoice id
		$config["currency_code"]     	= $payment->currency; //The invoice id
		
		$this->load->library('paypal',$config);
		$this->paypal->add($package->name, $package->price, 1, $package->id); //Third item with code
		$this->paypal->pay(); //Proccess the payment
	}

	public function process_payment(){
		
	}

	public function notify_payment(){
		$result = $this->input->post();
		if(!empty($result)){
			$item_name = "";
			$item_number = 0;
			if(isset($result['item_number'])){
				$item_name   = $result['item_name'];
				$item_number = $result['item_number'];
			}

			if(isset($result['item_number1'])){
				$item_name   = $result['item_name1'];
				$item_number = $result['item_number1'];
			}

			if(isset($result['item_number2'])){
				$item_name   = $result['item_name2'];
				$item_number = $result['item_number2'];
			}

			if(isset($result['item_number3'])){
				$item_name   = $result['item_name3'];
				$item_number = $result['item_number3'];
			}

			if(isset($result['item_number4'])){
				$item_name   = $result['item_name4'];
				$item_number = $result['item_number4'];
			}

			if(isset($result['item_number5'])){
				$item_name   = $result['item_name5'];
				$item_number = $result['item_number5'];
			}

			$data = array(
				"uid"             => session("uid"),
				"invoice"         => (int)$result['invoice'],
				"first_name"      => $result['first_name'],
				"last_name"       => $result['last_name'],
				"business"        => $result['business'],
				"payer_email"     => $result['payer_email'],
				"item_name"       => $item_name,
				"item_number"     => (int)$item_number,
				"address_street"  => isset($result['address_street'])?$result['address_street']:"",
				"address_city"    => isset($result['address_city'])?$result['address_city']:"",
				"address_country" => isset($result['address_country'])?$result['address_country']:"",
				"mc_gross"        => $result['mc_gross'],
				"mc_currency"     => $result['mc_currency'],
				"payment_date"    => date("Y-m-d H:i:s", strtotime($result['payment_date'])),
				"payment_status"  => $result['payment_status'],
				"full_data"       => json_encode($result),
				"created"         => NOW
			);
			$this->db->insert(PAYMENT_HISTORY, $data);
			if($result['payment_status'] == "Completed"){
				$user = $this->model->get("*", USER_MANAGEMENT, "id = '".session("uid")."'");
				if(!empty($user)){
					$package_new = $this->model->get("*", PACKAGE, "id = '".$item_number."'");
					$package_old = $this->model->get("*", PACKAGE, "id = '".$user->package_id."'");
					$package_id = $package_new->id;
					if(!empty($package_old)){
						if(strtotime(NOW) < strtotime($user->expiration_date)){
							$date_now = date("Y-m-d", strtotime(NOW));
							$date_expiration = date("Y-m-d", strtotime($user->expiration_date));
							$diff = abs(strtotime($date_expiration) - strtotime($date_now));
							$days = floor($diff/86400);

							$day_added = round(($package_old->price/$package_new->price)*$days);
							$total_day = $package_new->day + $day_added;
							$expiration_date = date('Y-m-d', strtotime("+".$total_day." days"));
						}else{
							$expiration_date = date('Y-m-d', strtotime("+".$package_new->day." days"));
						}
					}else{
						$expiration_date = date('Y-m-d', strtotime("+".$package_new->day." days"));
					}

					$data = array(
						"package_id"      => $package_id,
						"expiration_date" => $expiration_date
					);

					$this->db->update(USER_MANAGEMENT, $data, "id = '".session("uid")."'");
				}
			}
		}
		redirect(PATH);
	}

	public function cancel_payment(){
		redirect(url('payments'));
	}

}