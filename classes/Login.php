<?php
require_once '../config.php';

class Login extends DBConnection {

	private $settings;

	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}

	public function __destruct(){
		parent::__destruct();
	}

	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}

	public function login(){
		extract($_POST);

		$stmt = $this->conn->prepare("SELECT * from `accounts` where `username` = ? LIMIT 1");
		$stmt->bind_param('s',$username);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows > 0){ 
			while ($row = $result->fetch_assoc()) {
				if(password_verify($password,$row['password'])) {				
					$this->settings->set_userdata('id',$row['id']);
					$this->settings->set_userdata('full_name',$row['full_name']);
					$this->settings->set_userdata('image_path',$row['image_path']);
					$this->settings->set_userdata('username',$row['username']);
					$this->settings->set_userdata('is_archived',$row['is_archived']);
					$this->settings->set_userdata('date_added',$row['date_added']);
					$this->settings->set_userdata('login_type',$row['type']);
					
					return json_encode(array('status'=>'success','login_type'=>$row['type']));
				} else {
					return json_encode(array('status'=>'incorrect'));		
				}
			}
		} else {
			return json_encode(array('status'=>'incorrect'));
		}
	}

	public function logout(){
		if($this->settings->sess_des()){
			redirect('login.php');
		}
	}

	// function login_customer(){
	// 	extract($_POST);
	// 	$stmt = $this->conn->prepare("SELECT * from customer_list where email = ? and `password` = ? ");
	// 	$password = md5($password);
	// 	$stmt->bind_param('ss',$email,$password);
	// 	$stmt->execute();
	// 	$result = $stmt->get_result();
	// 	if($result->num_rows > 0){
	// 		$res = $result->fetch_array();
	// 		foreach($res as $k => $v){
	// 			$this->settings->set_userdata($k,$v);
	// 		}
	// 		$this->settings->set_userdata('login_type',2);
	// 		$resp['status'] = 'success';
	// 	}else{
	// 		$resp['status'] = 'failed';
	// 		$resp['msg'] = 'Incorrect Email or Password';
	// 	}
	// 	if($this->conn->error){
	// 		$resp['status'] = 'failed';
	// 		$resp['_error'] = $this->conn->error;
	// 	}
	// 	return json_encode($resp);
	// }

	// public function logout_customer(){
	// 	if($this->settings->sess_des()){
	// 		redirect('?');
	// 	}
	// }
}

$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();

switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	case 'login_customer':
		echo $auth->login();
		break;
	case 'logout_customer':
		echo $auth->logout();
		break;
	default:
		echo $auth->index();
		break;
}

