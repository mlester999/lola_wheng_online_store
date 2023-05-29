<?php
    session_start();

    require_once ('../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $sessionId = $_POST["session_id"];

    $query = "SELECT p.name, p.product_image, p.retail_price, product_id, product_barcode, session_id, sti.quantity AS sold_quantity FROM `sales_transactions_2_items` sti JOIN products p ON sti.product_id = p.id WHERE session_id = '".$sessionId."'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        $itemsCount = 0;

        while($row = mysqli_fetch_array($result))
        {
            $return .= '
            <li>
                <div class="product_name">
                    <span class="d-none product-data-info" id="'. $row['product_id'] .'">'. $row['product_id'] .'</span>

                    <span id="minusQty" onclick="change_quantity_down('. $row['product_id'] .', '. $row['sold_quantity'] .')" class="minus" data-product_id="'. $row['product_id'] .'" style="cursor: pointer;" title="Remove quantity"><i class="fa fa-minus text-primary"></i></span>
                    <input type="text" data-product_id="'. $row['product_id'] .'" data-product_name="'. $row['name'] .'" data-product_barcode="'. $row['product_barcode'] .'" data-product_quantity="'. $row['sold_quantity'] .'" data-product_session_id="'. $row['session_id'] .'" class="form-control text-primary quantity'. $row['product_id'] .'" value="0" min="0" id="quantity">
                    <span id="addQty" onclick="change_quantity_up('. $row['product_id'] .', '. $row['sold_quantity'] .')" class="add" data-product_id="'. $row['product_id'] .'" style="cursor: pointer;" title="Add quantity"><i class="fa fa-plus text-primary"></i></span>
                </div>

                <img src="' . base_url . $row['product_image'] .'" class="cart-img rounded border" style="width:100px;height:100px">
                
                <div class="retail_price'. $row['product_id'] .' cart-row">
                    <h4><span class="item_product_name">'. $row['name'] .'</span></h4>
                    <p class="text-success" style="font-size: 12px; margin: 0;">'. $row['sold_quantity'] .' QTY</p>
                    <p class="item-quantity'. $row['product_id'] .'">₱<span class="amount retail_price" id="retail_price'. $row['product_id'] .'">'. $row['retail_price'] .'</span></p>
                </div>
            </li>
            ';

            $itemsCount++;
        }
            // echo $return;
        echo json_encode(array("returnedItems"=>$return, "itemsCount"=>$itemsCount));
    } else
    {
        echo 'No products found.';
    }

?>
