<?php
    session_start();
    require_once ('../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $category = $_POST['category'];

    $query = "SELECT a.*
                        FROM (
                            SELECT `id`, `name`, `original_price`, `retail_price`, `product_image`, `quantity`, `code`, ROW_NUMBER()OVER(PARTITION BY `code` ORDER BY date_added DESC) AS row_id
                            FROM `products`
                            WHERE name = '$category'
                            ORDER BY id ASC 
                        ) a
                        WHERE a.row_id = 1
                    ";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $barcode = $row['code'];
            $total_demand = 0;
            $order_cost_per_item = number_format($_SESSION['system_info']['vat'], 2) / 100 * $row['original_price'] + number_format($_SESSION['system_info']['delivery_fee']);
            $holding_cost = $_SESSION['system_info']['handling_cost'];
            $query = "SELECT SUM(`quantity`) FROM `sales_transactions_2_items`
            WHERE product_barcode = '$barcode'
            AND datetime_added >= (
                SELECT MIN(datetime_added) FROM `sales_transactions_2_items`
                WHERE product_barcode = '$barcode'
                AND datetime_added <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
            )
            AND datetime_added BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW()";

            $rows = $conn->query($query);
                if($rows)
                {
                    foreach ($rows as $key => $row) {
                        $total_demand = round($row['SUM(`quantity`)']);
                    }
                }

            $eoq = round(sqrt((2 * $total_demand * number_format($order_cost_per_item)) / $holding_cost),0);

            $x_number_order = $total_demand / $eoq;

            $x_when_to_order = round(365 / $x_number_order,0);
           
            $return = '
            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                <p class="text-success text-xl">
                    <i class="ion ion-ios-refresh-empty"></i> 
                    <!-- Demand  -->
                </p>
                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold h4">
                    <!-- <i class="ion ion-android-arrow-up text-success"></i>  -->
                    '.number_format($total_demand).'
                    </span>
                    <span class="text-muted">Demand</span>
                </p>
            </div>

            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                <p class="text-warning text-xl">
                    <i class="ion ion-ios-cart-outline"></i> 
                    <!-- Order Cost -->
                </p>
                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold h4">
                    <!-- <i class="ion ion-android-arrow-up text-warning"></i>  -->
                    ₱'.number_format($order_cost_per_item).'
                    </span>
                    <span class="text-muted">Order Cost</span>
                </p>
            </div>
            
            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                <p class="text-danger text-xl">
                    <i class="ion ion-ios-folder-outline"></i> 
                    <!-- Holding Cost -->
                </p>
                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold h4">
                    <!-- <i class="ion ion-android-arrow-down text-danger"></i>  -->
                    ₱'.number_format($holding_cost).'
                    </span>
                    <span class="text-muted">Holding Cost</span>
                </p>
            </div>
        
            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                <p class="text-primary text-xl">
                    <i class="ion ion-ios-compose-outline"></i> 
                    <!-- EOQ -->
                </p>
                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold h4">
                    <!-- <i class="ion ion-android-arrow-down text-danger"></i>  -->
                    '.number_format($eoq).'
                    </span>
                    <span class="text-muted">EOQ</span>
                </p>
            </div>

            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                <p class="text-secondary text-xl">
                    <i class="ion ion-ios-information-outline"></i> 
                    <!-- # to Order -->
                </p>
                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold h4">
                    <!-- <i class="ion ion-android-arrow-down text-danger"></i>  -->
                    '.number_format($x_number_order).'
                    </span>
                    <span class="text-muted"> Order Count</span>
                </p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-0">
                <p class="text-success text-xl">
                    <i class="ion ion-ios-help-outline"></i> 
                    <!-- When to Order -->
                </p>
                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold h4">
                    <!-- <i class="ion ion-android-arrow-down text-danger"></i>  -->
                    '.number_format($x_when_to_order).'
                    </span>
                    <span class="text-muted"> When to Order (day/s)</span>
                </p>
            </div>
            ';
        }
        echo $return;
    }
    else
    {
        echo 'No products found.';
    }
?>