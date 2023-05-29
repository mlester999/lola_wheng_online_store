<?php
require_once('../config.php');
Class Orders extends DBConnection {
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

	function save_order_modal() {
		$product_id = $_POST['product_id'];
		$supplier_id = $_POST['supplier_id'];
		$category_id = $_POST['category_id'];
		$measurement_id = $_POST['unit_id'];
		$barcode = $_POST['barcode'];
		$quantity = $_POST['quantity'];
		$original_price = $_POST['original_price'];
		$retail_pct = $_POST['retail_pct'];
		$retail_price = $_POST['retail_price'];
		$reordering_point = $_POST['reordering_point'];
		$critical_point = $_POST['critical_point'];
		$expiration_date = $_POST['expiration_date'];
		$expiration_gap = $_POST['expiration_gap'];
		$ceiling_q = $_POST['ceiling_q'];
		$reordering_q = $_POST['reordering_q'];
		$critical_q = $_POST['critical_q'];

		$transaction_code = $supplier_id . $category_id . $measurement_id . $product_id . '-' . strtotime(date('Y-m-d H:i:s'));
		$status = 0;
		$datetime_ordered = date('Y-m-d H:i:s');

			// $query_run = $this->conn->query("INSERT INTO `orders_2` (supplier_id, category_id, product_id, unit_id, quantity, transaction_code, `status`, datetime_ordered) VALUES ('$i_supplier_id', '$i_category_id', '$i_product_id', '$i_unit_id', '$i_quantity', '$i_transaction_code', '$i_status', '$i_datetime_ordered')");

		$query_run = $this->conn->query("INSERT INTO `orders_2` (supplier_id, category_id, unit_id, product_id, barcode, quantity, original_price, retail_pct, retail_price, reordering_point, critical_point, `expiry_date`, expiry_gap, ceiling_q, reordering_q, critical_q, transaction_code, `status`, datetime_ordered) 
										VALUES ('$supplier_id', '$category_id', '$measurement_id', '$product_id', '$barcode', '$quantity', '$original_price', '$retail_pct', '$retail_price', '$reordering_point', '$critical_point', '$expiration_date', '$expiration_gap', '$ceiling_q', '$reordering_q', '$critical_q', '$transaction_code', '$status', '$datetime_ordered')");

		if($query_run) {
			$save_message = "New Order request successfully saved.";
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;

			$this->settings->set_flashdata('success',$resp['msg']); 
			return json_encode($resp);
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$query_run}]";

			$this->settings->set_flashdata('failed',$resp['err']); 
			return json_encode($resp);
		}
	}


	function save_order() {
		$row_num = $_POST['row_num'];
		$supplier_id = $_POST['supplier_id'];
		$category_id = $_POST['category_id'];
		$product_id = $_POST['product_id'];
		$unit_id = $_POST['unit_id'];
		$quantity = $_POST['quantity'];

		foreach($row_num as $index => $rows)
		{
			$i_supplier_id = $supplier_id[$index];
			$i_category_id = $category_id[$index];
			$i_product_id = $product_id[$index];
			$i_unit_id = $unit_id[$index];
			$i_quantity = $quantity[$index];
			$i_transaction_code = $i_supplier_id . $i_category_id . $i_unit_id . '-' . strtotime(date('Y-m-d H:i:s'));
			$i_status = 0;
			$i_datetime_ordered = date('Y-m-d H:i:s');

			$query_run = $this->conn->query("INSERT INTO `orders_2` (supplier_id, category_id, product_id, unit_id, quantity, transaction_code, `status`, datetime_ordered) VALUES ('$i_supplier_id', '$i_category_id', '$i_product_id', '$i_unit_id', '$i_quantity', '$i_transaction_code', '$i_status', '$i_datetime_ordered')");
		}

		if($query_run) {
			$save_message = "New Order(s) successfully saved.";
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;

			$this->settings->set_flashdata('success',$resp['msg']); 
			return json_encode($resp);
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$query_run}]";

			$this->settings->set_flashdata('failed',$resp['msg']); 
			return json_encode($resp);
		}
	}

	function update_order() {

		$status_1 = $_POST['status'];
		$originalQty = $_POST['originalQuantity'];
		$modifiedQty = $_POST['quantity'];
		$qtyLeft = intval($originalQty) - intval($modifiedQty);

		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if($k == 'originalQuantity' && $v == $originalQty) {
				continue; // skip this key/value pair and move on to the next loop iteration
			  }

			if(!in_array($k,array('order_id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}

		if($this->capture_err())
			return $this->capture_err();

		if(!empty($order_id)){
	
			$checkDuplicateQuery = "SELECT * FROM `orders_2` WHERE transaction_code = '". $transaction_code ."' AND status = 1;";

			$resultDuplicate = $this->conn->query($checkDuplicateQuery);

			$sql = "UPDATE `orders_2` SET {$data} WHERE order_id = '{$order_id}' ";
			$save_message = "Order successfully updated.";

			if(mysqli_num_rows($resultDuplicate) == 0) {
				if($status_1 == 1) {
					$sql_1 = "INSERT INTO products (supplier_id, unit_id, category_id, `code`, name, description, unit_size, quantity, ceiling_point, reordering_point, critical_point, expiration_date, expiration_gap, original_price, retail_price, is_archived, product_image, ceiling_q, reordering_q, critical_q, retail_pct)
								SELECT o2.supplier_id, o2.unit_id, o2.category_id
										, p.code AS product_code, p.name AS product_name
										, p.description
										, p.unit_size
										, o2.quantity
										, p.ceiling_point
										, o2.reordering_point
										, o2.critical_point
										, o2.expiry_date
										, o2.expiry_gap
										, o2.original_price
										, o2.retail_price
										, 0 as is_archived
										, p.product_image
										, o2.ceiling_q
										, o2.reordering_q
										, o2.critical_q
										, o2.retail_pct
								FROM products p
									LEFT JOIN orders_2 o2 ON p.id = o2.product_id AND o2.order_id = $order_id
								WHERE 
									o2.order_id = $order_id
								";
				}
	
			}

			if(mysqli_num_rows($resultDuplicate) > 0) {
				if($status_1 == 1) {
					$sql_1 = "UPDATE `products` SET quantity = quantity + $modifiedQty WHERE code = '". $barcode ."'
								AND id = (
								SELECT id
								FROM products
								WHERE code = '". $barcode ."'
								ORDER BY date_added DESC
								LIMIT 1
					)";
				}
			}

			if($status_1 == 1 && $originalQty != $modifiedQty) {
				$sql_2 = "INSERT INTO `orders_2` (supplier_id, category_id, product_id, unit_id, quantity, barcode, transaction_code, `status`, datetime_ordered, retail_pct, ceiling_point, reordering_point, critical_point, `expiry_date`, expiry_gap, original_price, retail_price, ceiling_q, reordering_q, critical_q) 
							SELECT o2.supplier_id, o2.category_id, o2.product_id, o2.unit_id
									, $qtyLeft AS quantity
									, o2.barcode
									, o2.transaction_code
									, 0 as `status`
									, NOW() as datetime_ordered
									, o2.retail_pct
									, o2.ceiling_point
									, o2.reordering_point
									, o2.critical_point
									, o2.expiry_date
									, o2.expiry_gap
									, o2.original_price
									, o2.retail_price
									, o2.ceiling_q
									, o2.reordering_q
									, o2.critical_q
							FROM orders_2 o2
							WHERE 
								o2.order_id = $order_id";
			}
		}

		$update = $this->conn->query($sql);
	
		if($update){
			if($status_1 == 1) {
				$insert_prod = $this->conn->query($sql_1);
				if(isset($sql_2)) {
					$insert_prod = $this->conn->query($sql_2);
				}
			}
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;
			$this->settings->set_flashdata('success',$save_message);
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}

		return json_encode($resp);
	}


	function update_order_selected() {
		$rows_selected = $_POST['rows_selected'];
		$status = $_POST['status'];

		// $qryUpdate = "";
		// $rowsArr = explode(",", $rows_selected);
		// if(count($rowsArr) != 0)
		// {
			// foreach($rowsArr as $order_id) {
			// 	$order_id = trim($order_id);
			// 	$qryUpdate .= "UPDATE `orders_2` SET `status`='" . $status . "' WHERE order_id IN '" . $order_id . "';";
			// }

			$qryUpdate = "UPDATE `orders_2` SET `status`='" . $status . "' WHERE order_id IN (" . $rows_selected . ")";

			$update = $this->conn->query($qryUpdate);

			$save_message = "Selected orders successfully updated.";
			// $resp['qry'] = $qryUpdate;

			if($status == 1) {
				$sql_2 = "INSERT INTO products (supplier_id, unit_id, category_id, `code`, name, description, unit_size, quantity, ceiling_point, reordering_point, critical_point, expiration_date, expiration_gap, original_price, retail_price, is_archived, product_image, ceiling_q, reordering_q, critical_q, retail_pct)
								SELECT o2.supplier_id, o2.unit_id, o2.category_id
										, p.code AS product_code, p.name AS product_name
										, p.description
										, p.unit_size
										, o2.quantity
										, p.ceiling_point
										, o2.reordering_point
										, o2.critical_point
										, o2.expiry_date
										, o2.expiry_gap
										, o2.original_price
										, o2.retail_price
										, 0 as is_archived
										, p.product_image
										, o2.ceiling_q
										, o2.reordering_q
										, o2.critical_q
										, o2.retail_pct
								FROM products p
									LEFT JOIN orders_2 o2 ON p.id = o2.product_id
								WHERE 
									o2.order_id IN (" . $rows_selected . ")
				";
			}

			if($update){
				if($status ==1) {
					$insert_prod_2 = $this->conn->query($sql_2);
				}
				$resp['status'] = 'success';
				$resp['msg'] = $save_message;
				$this->settings->set_flashdata('success',$save_message);
			}else{
				$resp['status'] = 'failed';
				$resp['err'] = $this->conn->error."[{$qryUpdate}]";
			}
	
			return json_encode($resp);
		// }
	}

	function delete_order(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `orders_2` WHERE order_id = '{$order_id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Order successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

}

$orders = new Orders();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save_order_modal':
		echo $orders->save_order_modal();
		break;
	case 'save_order':
		echo $orders->save_order();
		break;
	case 'update_order':
		echo $orders->update_order();
		break;
	case 'delete_order': 
		echo $orders->delete_order();
		break;
	case 'update_order_selected': 
		echo $orders->update_order_selected();
		break;
	default:
		// echo $sysset->index();
		break;
}