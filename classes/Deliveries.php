<?php
require_once('../config.php');
Class Deliveries extends DBConnection {
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
		$sessionId = $_POST['session_id'];

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

	function update_return_selected() {
		$currentDate = "";
		$rows_selected = $_POST['rows_selected'];
		$status = $_POST['status'];
		$productId = $_POST['product_id'];
		$addQuantity = $_POST['product_qty'];
		$sessionId = $_POST['session_id'];
		$qryUpdate = "";
		$queryUpdateQty = "";
		$orderUpdateQty = "";

		if($status == 1) {
			$currentDate = NULL;
			$qryUpdate = "UPDATE `returned_orders` SET `status`='" . $status . "', `datetime_returned`='". $currentDate ."' WHERE id IN (" . $rows_selected . ")";
			$orderUpdateQty = "UPDATE `sales_transactions_2` SET status = 7 WHERE session_id = '" . $sessionId . "';";
		} else if($status == 2) {
			$currentDate = NULL;
			$qryUpdate = "UPDATE `returned_orders` SET `status`='" . $status . "', `datetime_returned`='". $currentDate ."' WHERE id IN (" . $rows_selected . ")";
			$orderUpdateQty = "UPDATE `sales_transactions_2` SET status = 8 WHERE session_id = '" . $sessionId . "';";
		} else if($status == 3) {
			$currentDate = date('Y-m-d H:i:s');
			$qryUpdate = "UPDATE `returned_orders` SET `status`='" . $status . "', `datetime_returned`='". $currentDate ."' WHERE id IN (" . $rows_selected . ")";
			$orderUpdateQty = "UPDATE `sales_transactions_2` SET status = 1 WHERE session_id = '" . $sessionId . "';";
		} else {
			$currentDate = NULL;
			$qryUpdate = "UPDATE `returned_orders` SET `status`='" . $status . "', `datetime_returned`='". $currentDate ."' WHERE id IN (" . $rows_selected . ")";
			$orderUpdateQty = "UPDATE `sales_transactions_2` SET status = 9 WHERE session_id = '" . $sessionId . "';";
		}

		$update = $this->conn->query($qryUpdate);
		$update = $this->conn->query($orderUpdateQty);

		$save_message = "Selected returned items successfully updated.";

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

$deliveries = new Deliveries();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'update_sale_selected': 
		echo $deliveries->update_sale_selected();
		break;
	case 'update_return_selected': 
		echo $deliveries->update_return_selected();
		break;
	default:
		// echo $sysset->index();
		break;
}