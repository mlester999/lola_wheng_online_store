<?php
    session_start();
    require_once ('../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $customer_contact_number = $_POST['customer_contact_number'];
    $customer_home_address = $_POST['customer_home_address'];
    $transaction_number = strtotime(date('Y-m-d H:i:s'));
    $session_id = $_SESSION['cart_unique_key'];
    $total_amount = $_POST['total_amount'];
    $amount_rendered = $_POST['amount_rendered'];
    $changed = $_POST['changed'];
    $discount_pct = $_POST['discount_pct'];
    $vat_pct = $_POST['vat_pct'];
    $delivery_fee = $_POST['delivery_fee'];
    $status = $_POST['status'];
    $overall_total = $_POST['overall_total'];
    $datetime_checkedout = date('Y-m-d H:i:s');
    $ordered_online = 1;

    $sql_submit = "INSERT INTO `sales_transactions_2` (`customer_id`, `customer_name`, `customer_contact_number`, `customer_home_address`, `transaction_number`, `session_id`, `total_amount`, `amount_rendered`, `changed`, `discount_pct`, `vat_pct`, `delivery_fee`, `overall_total`, `status`, `ordered_online`) 
                        VALUES ('$customer_id', '$customer_name', '$customer_contact_number', '$customer_home_address', '$transaction_number', '$session_id', '$total_amount', '$amount_rendered', '$changed', '$discount_pct', '$vat_pct', '$delivery_fee', '$overall_total', '$status', '$ordered_online') 
                        ON DUPLICATE KEY UPDATE total_amount='$total_amount', amount_rendered='$amount_rendered', changed='$changed', discount_pct='$discount_pct', vat_pct='$vat_pct', delivery_fee='$delivery_fee', overall_total='$overall_total', datetime_checkedout='$datetime_checkedout'";

    $sql_move_cart_items = "INSERT INTO `sales_transactions_2_items` SELECT * FROM `cart` WHERE session_id='$session_id'";

    if(mysqli_query($conn,$sql_submit) && mysqli_query($conn,$sql_move_cart_items)) {
        $sql_update_qty = "SELECT * FROM sales_transactions_2_items WHERE session_id='$session_id'";
        $result = mysqli_query($conn, $sql_update_qty);

        if(mysqli_num_rows($result) > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $product_id = $row['product_id'];
                $sales_quantity = $row['quantity'];

                $sql_product = "SELECT * FROM products WHERE id='$product_id'";

                $product = mysqli_query($conn, $sql_product);

                if(mysqli_num_rows($product) > 0)
                {
                    while($prod = mysqli_fetch_array($product))
                    {
                        $existing_quantity = $prod['quantity'];
                        $barcode = $prod['code'];

                        if($existing_quantity >= $sales_quantity) {
                            $get_product_id = "SELECT id,code FROM products WHERE code='$barcode' AND id='$product_id'  LIMIT 1 OFFSET 0";

                            $this_prod_id = mysqli_query($conn, $get_product_id);
                            if(mysqli_num_rows($this_prod_id) > 0) {
                                while($uprod = mysqli_fetch_array($this_prod_id)) {
                                    $updated_qty = $existing_quantity - $sales_quantity;
                                    $product_id = $uprod['id'];
                                    $sql_update_quantity = "UPDATE products SET quantity='$updated_qty' WHERE id='$product_id' ";
                                    if(mysqli_query($conn, $sql_update_quantity)) {
                                        echo json_encode(array("statusCode"=>200));
                                    }
                                }
                            }
                        } else {
                            $get_product_id1 = "SELECT id,code,quantity FROM products WHERE code='$barcode' LIMIT 1 OFFSET 0";
                            $get_product_id2 = "SELECT id,code,quantity FROM products WHERE code='$barcode' LIMIT 1 OFFSET 1";

                            $updated_qty1 = $sales_quantity - $sales_quantity;
                            $this_prod_id1 = mysqli_query($conn, $get_product_id1);
                            if(mysqli_num_rows($this_prod_id1) > 0) {
                                while($uprod1 = mysqli_fetch_array($this_prod_id1)) {
                                    $product_id1 = $uprod1['id'];
                                    $sql_update_quantity1 = "UPDATE products SET quantity='$updated_qty1' WHERE id='$product_id1' ";
                                    if(mysqli_query($conn, $sql_update_quantity1)) {
                                        echo json_encode(array("statusCode"=>200));
                                    }
                                }
                            }
                            

                            $updated_qty2 = $sales_quantity - $existing_quantity;
                            $this_prod_id2 = mysqli_query($conn, $get_product_id2);
                            if(mysqli_num_rows($this_prod_id2) > 0) {
                                while($uprod2 = mysqli_fetch_array($this_prod_id2)) {
                                    $product_id2 = $uprod2['id'];
                                    $updated_qty3 = $uprod2['quantity'] - $updated_qty2;
                                    $sql_update_quantity2 = "UPDATE products SET quantity='$updated_qty3' WHERE id='$product_id2' ";
                                    if(mysqli_query($conn, $sql_update_quantity2)) {
                                        echo json_encode(array("statusCode"=>200));
                                    }
                                }
                            }
                        }
                    }
                }   
            }
        }

        // echo json_encode(array("statusCode"=>200));
    } else {
        echo json_encode(array("statusCode"=>201));
    }
    
?>