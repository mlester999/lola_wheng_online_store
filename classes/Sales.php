<?php
require_once('../config.php');
Class Sales extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}

	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}

	function update_sale_selected() {
		$rows_selected = $_POST['rows_selected'];
		$status = $_POST['status'];

		$qryUpdate = "UPDATE `sales_transactions_2` SET `status`='" . $status . "' WHERE transaction_id IN (" . $rows_selected . ")";

		$update = $this->conn->query($qryUpdate);

		$save_message = "Selected sales successfully updated.";

		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;
			$this->settings->set_flashdata('success',$save_message);
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$qryUpdate}]";
		}

		return json_encode($resp);
	}
}

$sales = new Sales();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'update_sale_selected': 
		echo $sales->update_sale_selected();
		break;
	default:
		// echo $sysset->index();
		break;
}