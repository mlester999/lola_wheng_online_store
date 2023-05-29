<?php
    session_start();

    require_once ('../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $sessionId = $_POST["session_id"];

    $query = "SELECT p.name, p.product_image, p.retail_price, product_id, product_barcode, session_id, sti.quantity AS sold_quantity FROM `sales_transactions_2_items` sti JOIN products p ON sti.product_id = p.id WHERE session_id = '".$sessionId."' ORDER BY p.name";

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
