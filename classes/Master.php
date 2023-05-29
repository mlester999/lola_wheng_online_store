<?php
require_once('../config.php');
Class Master extends DBConnection {
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

	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('category_id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		// $check = $this->conn->query("SELECT * FROM `categories` where `category_name` = '{$category_name}'")->num_rows;
		$check = $this->conn->query("SELECT * FROM `categories` where `category_name` = '{$category_name}' ".(!empty($category_id) ? " and category_id != {$category_id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Category already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($category_id)){
			$sql = "INSERT INTO `categories` set {$data} ";
		}else{
			$sql = "UPDATE `categories` set {$data} where category_id = '{$category_id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$category_id = !empty($category_id) ? $category_id : $this->conn->insert_id;
			$resp['category_id'] = $category_id;
			$resp['status'] = 'success';
			if(empty($category_id))
				$resp['msg'] = "New Category successfully saved.";
			else
				$resp['msg'] = " Category successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}

	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `categories` where category_id = '{$category_id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}

	function save_measurement(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('measurement_id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		} 
		$check = $this->conn->query("SELECT * FROM `measurements` where `measurement_name` = '{$measurement_name}' ".(!empty($measurement_id) ? " and measurement_id != {$measurement_id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Unit of Measurement already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($measurement_id)){
			$sql = "INSERT INTO `measurements` set {$data} ";
			$save_message = "New Unit of Measurement successfully saved.";
		}else{
			$sql = "UPDATE `measurements` set {$data} where measurement_id = '{$measurement_id}' ";
			$save_message = "Unit of Measurement successfully updated.";
		}
		$save = $this->conn->query($sql);
		if($save){
			$measurement_id = !empty($measurement_id) ? $measurement_id : $this->conn->insert_id;
			$resp['measurement_id'] = $measurement_id;
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']); 
			return json_encode($resp);
	}

	function delete_measurement(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `measurements` where measurement_id = '{$measurement_id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Unit of Measurement successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function save_supplier(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `suppliers` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Supplier already exists.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `suppliers` set {$data} ";
			$save_message = "New Supplier successfully saved.";
		}else{
			$sql = "UPDATE `suppliers` set {$data} where id = '{$id}' ";
			$save_message = "Supplier successfully updated.";
		}
		$save = $this->conn->query($sql);
		if($save){
			$id = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $id;
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}

	function save_return(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$v = htmlspecialchars($this->conn->real_escape_string($v));
				$data .= " `{$k}`='{$v}' ";
			}
		}
		// $check = $this->conn->query("SELECT * FROM `returned_orders`")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		// if($check > 0){
		// 	$resp['status'] = 'failed';
		// 	$resp['msg'] = "Returned Item already exists.";
		// 	return json_encode($resp);
		// 	exit;
		// }
		if(empty($id)){
			$updateItemQtyQuery = "UPDATE `products` SET quantity = quantity + $product_quantity WHERE id = $product_id;";

			$sql = "INSERT INTO `returned_orders` set {$data} ";
			$save_message = "Returned Item successfully saved.";
		}else{
			$updateItemQtyQuery = "UPDATE `products` SET quantity = quantity + $product_quantity WHERE id = $product_id;";

			$sql = "UPDATE `returned_orders` set {$data} where id = '{$id}' ";
			$save_message = "Returned Item successfully updated.";
		}
		$save = $this->conn->query($sql);
		$addQuantity = $this->conn->query($updateItemQtyQuery);

		if($save){
			$id = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $id;
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}

	function delete_supplier(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `suppliers` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Supplier successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
 
	function save_product(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) 
					$data .=",";
					$v = htmlspecialchars($this->conn->real_escape_string($v));
					$data .= " `{$k}`='{$v}' ";
			}
		}


		// $check = $this->conn->query("SELECT * FROM `products` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;

		if($this->capture_err())
			return $this->capture_err();

		// if($check > 0){
		// 	$resp['status'] = 'failed';
		// 	$resp['msg'] = "Product already exists.";
		// 	return json_encode($resp);
		// 	exit;
		// }

		if(empty($id)){
			$sql = "INSERT INTO `products` set {$data} ";
			$save_message = "New Product successfully saved.";

		}else{
			$sql = "UPDATE `products` set {$data} where id = '{$id}' ";
			$save_message = "Product successfully updated.";
		}
		
		$save = $this->conn->query($sql);

		if($save){
			$checkId = empty($id);

			$id = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $id;
			$resp['status'] = 'success';
			$resp['msg'] = $save_message;

			if($checkId && $quantity > 0) {
				$i_transaction_code = $supplier_id . $category_id . $unit_id . '-' . strtotime(date('Y-m-d H:i:s'));

				$orderSql = "INSERT INTO `orders_2` (supplier_id, category_id, product_id, unit_id, quantity, barcode, transaction_code, status, datetime_ordered, retail_pct, ceiling_point, reordering_point, critical_point, expiry_date, expiry_gap, original_price, retail_price, ceiling_q, reordering_q, critical_q)
						VALUES ($supplier_id, $category_id, $id, $unit_id, $quantity, '$code', '$i_transaction_code', 1, NOW(), $retail_pct, 0, $reordering_point, $critical_point, '$expiration_date', $expiration_gap, $original_price, $retail_price, $ceiling_q, $reordering_q, $critical_q)";
	
				$orderSave = $this->conn->query($orderSql);
			}

			if(!empty($_FILES['product_image']['tmp_name'])){
				if(!is_dir(base_app."uploads/products/$id"))
					mkdir(base_app."uploads/products/$id",0777,true);
					$product_image_path = "uploads/products/$id";

				$ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
				$product_image = "$product_image_path/$id.$ext";
				$accept = array('image/jpeg','image/png');

				if(!in_array($_FILES['product_image']['type'],$accept)){
					$err = "Image file type is invalid";
				}

				if($_FILES['product_image']['type'] == 'image/jpeg')
					$uploadfile = imagecreatefromjpeg($_FILES['product_image']['tmp_name']);
				elseif($_FILES['product_image']['type'] == 'image/png')
					$uploadfile = imagecreatefrompng($_FILES['product_image']['tmp_name']);

				if(!$uploadfile){
					$err = "Image is invalid";
				}

				list($width, $height) = getimagesize($_FILES['product_image']['tmp_name']);
				
				if($width > 1200 || $height > 480){
					if($width > $height){
						$perc = ($width - 1200) / $width;
						$width = 1200;
						$height = $height - ($height * $perc);
					}else{
						$perc = ($height - 480) / $height;
						$height = 480;
						$width = $width - ($width * $perc);
					}
				}

				$temp = imagescale($uploadfile,$width,$height);

				if(is_file(base_app.$product_image))
					unlink(base_app.$product_image);


				if($_FILES['product_image']['type'] == 'image/jpeg')
					$upload = move_uploaded_file($_FILES['product_image']['tmp_name'], base_app . '/' . $product_image);
				elseif($_FILES['product_image']['type'] == 'image/png')
					$upload = move_uploaded_file($_FILES['product_image']['tmp_name'], base_app . '/' . $product_image);
					
				if($upload){
					$this->conn->query("UPDATE `products` SET `product_image` = CONCAT('{$product_image}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$id}'");
				}

				imagedestroy($temp);
			}

			$findImage = $this->conn->query("SELECT COUNT(*) FROM `products` WHERE product_image = '' AND id = '{$id}'");

			$rowImage = mysqli_fetch_assoc($findImage);
			
			if($rowImage['COUNT(*)'] == 1) {
				$this->conn->query("UPDATE `products` SET `product_image` = CONCAT('uploads/product_placeholder.png', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$id}'");
			}

			if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);

		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
	}

	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `products` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Product successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
		break;
	case 'delete_category':
		echo $Master->delete_category();
		break;
	case 'save_measurement':
		echo $Master->save_measurement();
		break;
	case 'delete_measurement':
		echo $Master->delete_measurement();
		break;
	case 'save_supplier':
		echo $Master->save_supplier();
		break;
	case 'delete_supplier':
		echo $Master->delete_supplier();
		break;
	case 'save_product':
		echo $Master->save_product();
		break;
	case 'delete_product':
		echo $Master->delete_product();
		break;
	case 'save_return':
		echo $Master->save_return();
		break;
	default:
		// echo $sysset->index();
		break;
}