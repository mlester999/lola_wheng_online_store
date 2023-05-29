<?php
    $current_date = date('Y-m-d');
    $current_year = date('Y');
    // $current_year = 2022;
    $qry_sales = "SELECT SUM(total_amount) AS total_amount FROM `sales_transactions_2` WHERE YEAR(datetime_checkedout) = '$current_year' AND `status` IN (1,5);";
    $qry = $conn->query($qry_sales);
    if($qry->num_rows > 0){
        foreach($qry->fetch_all(MYSQLI_ASSOC) as $s):
            $sales = number_format($s['total_amount'],2);
        endforeach;
    } else {
        $sales = number_format(0,2);
    }


    // Orders
    $qry_orders = "SELECT COUNT(*) AS orders_count FROM `orders_2` WHERE YEAR(datetime_ordered) = '$current_year' AND `status` IN (0,1);";
    $qry_o = $conn->query($qry_orders);
    if($qry_o->num_rows > 0){
        foreach($qry_o->fetch_all(MYSQLI_ASSOC) as $o):
            $orders = number_format($o['orders_count'],0);
        endforeach;
    } else {
        $orders = number_format(0,0);
    }


    // Request Orders 
    $sql_reqor = "SELECT count(*) request_orders
                    FROM products p
                    JOIN suppliers s ON p.supplier_id = s.id
                    JOIN measurements m ON p.unit_id = m.measurement_id
                    JOIN categories c ON p.category_id = c.category_id
                    LEFT JOIN orders_2 o2 ON p.id = o2.product_id
                    WHERE 
                    (p.quantity <= p.reordering_q OR 
                    p.quantity <= p.critical_q) AND 
                    p.id NOT IN (
                            SELECT DISTINCT product_id FROM orders_2 ORDER by datetime_ordered DESC
                        )";
    $qry_reqor = $conn->query($sql_reqor);
    $reorder = $qry_reqor->fetch_assoc();
    $request_orders = $reorder['request_orders'];

    // Sales - For Delivery
    $sql_sales_4delivery = "SELECT count(*) AS for_delivery_cnt FROM sales_transactions_2 WHERE `status` = 3";
    $qry_4delivery = $conn->query($sql_sales_4delivery);
    $fd = $qry_4delivery->fetch_assoc();
    $for_delivery = $fd['for_delivery_cnt'];

    // Sales - For Pick-Up
    $sql_sales_4pickup = "SELECT count(*) AS for_pickup_cnt FROM sales_transactions_2 WHERE `status` = 4";
    $qry_4pickup = $conn->query($sql_sales_4pickup);
    $fp = $qry_4pickup->fetch_assoc();
    $for_pickup = $fp['for_pickup_cnt'];


    // Expiring Products
    $sql_exp_prod = "SELECT COUNT(*) AS expiring_products_count
    FROM products p
    WHERE p.expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL p.expiration_gap DAY) GROUP BY p.id";
    $qry_exp_prod = $conn->query($sql_exp_prod);
    $xp = $qry_exp_prod->fetch_assoc();
    $expiring_products = $xp['expiring_products_count'];


    // Customers
    $sql_customers = "SELECT COUNT(*) AS customers_count FROM customers ";
    $qry_customers = $conn->query($sql_customers);
    $c = $qry_customers->fetch_assoc();
    $customers = $c['customers_count'];

    // Online Users
    $sql_users = "SELECT COUNT(*) AS users_count FROM accounts WHERE type = 4 ";
    $qry_users = $conn->query($sql_users);
    $u = $qry_users->fetch_assoc();
    $users = $u['users_count'];
?>
            <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Dashboard</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Sales</span>
                                        <span class="info-box-number">₱ <?=$sales?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cart-arrow-down"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Orders</span>
                                        <span class="info-box-number">
                                            <?=$orders;?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Purchase Orders</span>
                                        <span class="info-box-number"><?=number_format($request_orders,0);?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-truck"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">For Delivery</span>
                                        <span class="info-box-number"><?=number_format($for_delivery,0);?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-truck"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">For Pick-Up</span>
                                        <span class="info-box-number"><?=number_format($for_pickup,0);?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Expiring Products</span>
                                        <span class="info-box-number"><?=number_format($expiring_products,0);?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="clearfix hidden-md-up"></div> -->
                        
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Customers</span>
                                        <span class="info-box-number"><?=number_format($customers,0);?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Online Users</span>
                                        <span class="info-box-number"><?=number_format($users,0);?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                        <div class="card">
                                <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title text-bold">Forecast</h3>
                                            <button id="viewReportForecast" class="text-primary text-bold bg-transparent" style="border: none;">View Report</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-center">Trend Forecasting for a Product</p>
                                        <div class="d-flex mb-3" style="justify-content: center; gap: 24px;">
                                            <div class="card-tools">
                                                <div class="input-group" style="width: 400px;">
                                                    <select class="form-select checkout-input" id="productsCategoryForecast" name="productsCategoryForecast">
                                                    
                                                                    <?php
                                                        $qry = $conn->query("SELECT a.*
                                                        FROM (
                                                            SELECT p.`id`, p.`name`, p.`retail_price`, p.`product_image`, p.`quantity`, p.`code`, p.`date_added`, 
                                                                ROW_NUMBER() OVER (PARTITION BY p.`code` ORDER BY sti.`datetime_added`) AS row_id
                                                            FROM `products` p
                                                            JOIN sales_transactions_2_items sti ON p.`code` = sti.`product_barcode`
                                                            GROUP BY p.`name`, p.`id`
                                                            HAVING MIN(sti.`datetime_added`) <= DATE_SUB(CURDATE(), INTERVAL 2 WEEK)
                                                        ) a
                                                        WHERE a.row_id = 1;");

                                                        if($qry->num_rows > 0) {
                                                            while($row = $qry->fetch_assoc()) {
                                                        ?>
                                                            <option id="<?php echo $row["name"]  ?>" value="<?php echo $row["name"]  ?>"><?php echo $row["name"]  ?></option>
                                                        <?php 
                                                                }
                                                        ?>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card-tools">
                                                <div class="input-group" style="width: 120px;">
                                                    <select class="form-select checkout-input" id="filterDate" name="filterDate">
                                                        <option selected value="Weekly">Weekly</option>
                                                        <option value="Monthly">Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="position-relative mb-4">
                                            <canvas id="trendForecastingChart" height="550"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                        </div>  
                        
                        <div class="col-lg-12">
                        <div class="card" style="height: auto;">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title text-bold">Inventory Report</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-striped table-bordered" id="inventory-reports" >
                                            <colgroup>
                                                <col width="10%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="20%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="15%">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Product Name</th>
                                                    <th>Deliveries</th>
                                                    <th>Sales</th>
                                                    <th>Return</th>
                                                    <th>Back Order</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $i = 1;
                                                    $qry = $conn->query("SELECT p.name, p.code AS product_barcode, 
                                                    COALESCE(deliveries.total_deliveries, 0) AS total_deliveries, 
                                                    COALESCE(sales.total_sales, 0) AS total_sales, 
                                                    COALESCE(returns.total_returns, 0) AS total_returns, 
                                                    COALESCE(back_orders.back_orders, 0) AS back_orders, 
                                                    COALESCE(deliveries.total_deliveries, 0) - COALESCE(sales.total_sales, 0) + COALESCE(returns.total_returns, 0) - COALESCE(back_orders.back_orders, 0) AS balance
                                             FROM products p 
                                             LEFT JOIN (SELECT product_barcode, SUM(quantity) AS total_sales FROM sales_transactions_2_items sti JOIN sales_transactions_2 st ON sti.session_id = st.session_id WHERE st.status != 2 GROUP BY product_barcode) AS sales ON p.code = sales.product_barcode
                                             LEFT JOIN (SELECT barcode, SUM(quantity) AS total_deliveries FROM orders_2 GROUP BY barcode) AS deliveries ON p.code = deliveries.barcode
                                             LEFT JOIN (SELECT product_barcode, SUM(product_quantity) AS total_returns FROM returned_orders WHERE status != 0 AND status != 4 GROUP BY product_barcode) AS returns ON p.code = returns.product_barcode
                                             LEFT JOIN (SELECT product_barcode, SUM(product_quantity) AS back_orders FROM returned_orders WHERE status != 0 AND status != 4 GROUP BY product_barcode) AS back_orders ON p.code = back_orders.product_barcode
                                             GROUP by p.code;;");
                                                    if($qry->num_rows > 0) {
                                                        while($rowro = $qry->fetch_assoc()) {
                                                ?>
                                                            <tr>
                                                                <td class="align-items-center text-center"><?php echo $i; ?></td>
                                                                <td class="align-items-center"><?php echo $rowro['name']; ?></td>
                                                                <td class="align-items-center"><?php echo $rowro['total_deliveries']; ?></td>
                                                                <td class="align-items-center td-product_name"><?php echo $rowro['total_sales']; ?></td>
                                                                <td><?php echo $rowro['total_returns']; ?></td>
                                                                <td><?php echo $rowro['back_orders']; ?></td>
                                                                <td><?php echo $rowro['balance']; ?></td>
                                                            </tr>
                                                <?php 
                                                $i++;
                                                        }
                                                ?>
                                                <?php
                                                    }
                                                ?>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="2" style="text-align:right">Total:</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title text-bold">Returned Products</h3>
                                            <button id="viewReturnProducts" class="text-primary text-bold bg-transparent" style="border: none;">View Report</button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="position-relative mb-4">
                                            <canvas id="sales-demand-chart" height="295"></canvas>
                                        </div>

                                        <div class="d-none flex-row justify-content-end">
                                            <span class="mr-2">
                                                <i class="fas fa-square text-plato"></i> Returned &nbsp;
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card" style="height: auto;">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title text-bold">Expiring Products</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-striped table-bordered" id="list-expiring-products" >
                                            <colgroup>
                                                <col width="10%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="20%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="15%">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Supplier Name</th>
                                                    <th>Product Name</th>
                                                    <th>Product Code</th>
                                                    <th>Category</th>
                                                    <th>Expiration Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $i = 1;
                                                    $qry = $conn->query("SELECT ROW_NUMBER() OVER (ORDER BY expiration_date) as id_count, sup.name as supplier_name, p.name, p.code, ctg.category_name, p.expiration_date, p.expiration_gap
                                                    FROM products p
                                                    JOIN suppliers sup ON p.supplier_id = sup.id
                                                    JOIN categories ctg ON p.category_id = ctg.category_id
                                                    WHERE p.expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL p.expiration_gap DAY)
                                                    GROUP BY p.code");
                                                    if($qry->num_rows > 0) {
                                                        while($rowro = $qry->fetch_assoc()) {
                                                ?>
                                                            <tr>
                                                                <td class="align-items-center text-center"><?php echo $rowro['id_count']; ?></td>
                                                                <td class="align-items-center"><?php echo $rowro['supplier_name']; ?></td>
                                                                <td class="align-items-center"><?php echo $rowro['name']; ?></td>
                                                                <td class="align-items-center td-product_name"><?php echo $rowro['code']; ?></td>
                                                                <td><?php echo $rowro['category_name']; ?></td>
                                                                <td><?php echo $rowro['expiration_date']; ?></td>
                                                                <td class="align-items-center"> 
                                                                        <span class="badge badge-danger px-3 py-1 rounded-pill"><i class="fas fa-exclamation-triangle"></i> Expiring Product</span>
                                                                </td>
                                                            </tr>
                                                <?php 
                                                        }
                                                ?>
                                                <?php
                                                    }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title text-bold">Sales</h3>
                                            <button id="viewReportSales" class="text-primary text-bold bg-transparent" style="border: none;">View Report</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <p class="d-flex flex-column">
                                                <?php
                                                $total_amount = 0;
                                                $query = "SELECT YEAR(`datetime_checkedout`) AS `year`, DATE_FORMAT(`datetime_checkedout`, '%b') AS `month_name`
                                                                , SUM(`total_amount`) AS total_amount
                                                            FROM sales_transactions_2
                                                            WHERE `status` IN (1,5) AND YEAR(`datetime_checkedout`) = $current_year
                                                            GROUP BY YEAR(`datetime_checkedout`), DATE_FORMAT(`datetime_checkedout`, '%b')
                                                            ORDER BY `datetime_checkedout` ASC";
                                                $rows = query($query);
                                                if($rows)
                                                {
                                                    foreach ($rows as $key => $row) {
                                                        $total_amount = round($row['total_amount'],2);
                                                    }
                                                }

                                                    $sales_last_month = 0;
                                                    $sql_sales_last_month = "SELECT YEAR(`datetime_checkedout`) AS `year`, DATE_FORMAT(`datetime_checkedout`, '%b') AS `month_name`
                                                                    , SUM(`total_amount`) AS total_amount
                                                                FROM sales_transactions_2
                                                                WHERE `status` IN (1,5) AND YEAR(`datetime_checkedout`) = $current_year AND MONTH(`datetime_checkedout`) = MONTH($current_date) - 1
                                                                GROUP BY YEAR(`datetime_checkedout`), DATE_FORMAT(`datetime_checkedout`, '%b')
                                                                ORDER BY `datetime_checkedout` ASC";
                                                    $rows_slm = query($sql_sales_last_month);
                                                    if($rows_slm)
                                                    {
                                                        foreach ($rows_slm as $key => $slm) {
                                                            $sales_last_month = round($slm['total_amount'],2);
                                                        }
                                                    }
                                                ?>
                                                <span class="text-bold text-lg">P <?php echo number_format($total_amount,2); ?></span>
                                                <span>Sales Over Time</span>
                                            </p>
                                            <p class="ml-auto d-flex flex-column text-right">
                                                <?php 

                                                ?>
                                                <span class="text-success">
                                                    <?php
                                                        if($sales_last_month < $total_amount) {
                                                    ?>
                                                            <i class="fas fa-arrow-up"></i> 
                                                    <?php
                                                        } else {
                                                    ?>
                                                            <i class="fas fa-arrow-down"></i> 
                                                    <?php 
                                                        }
                                                    ?>
                                                
                                                    <?php 
                                                        $slm_diff = $total_amount - $sales_last_month; 
                                                        $slm_pct = ($slm_diff / $total_amount) * 100;
                                                        echo number_format($slm_pct,2);
                                                    ?>%
                                                </span>
                                                <span class="text-muted">Since last month</span>
                                            </p>
                                        </div>

                                        <div class="position-relative mb-4">
                                            <canvas id="sales-amount-chart" height="200"></canvas>
                                        </div>

                                        <div class="d-flex flex-row justify-content-end d-none">
                                            <span class="mr-2">
                                                <i class="fas fa-square text-primary"></i> Sales YTD
                                            </span>

                                            <!-- <span>
                                                <i class="fas fa-square text-gray"></i> Last year1
                                            </span> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title text-bold">Economic Order Quantity </h3>

                                        <div class="card-tools">
                                        <div class="input-group mb-3" style="width: 240px;">
                                        <select id="productsCategoryEoq" name="productsCategoryEoq" class="form-select checkout-input">
                                            <option value="" hidden>Select Product</option>
                                            <?php
											$qry = $conn->query("SELECT a.* 
                                            FROM (
                                                SELECT `id`, `name`, `retail_price`, `product_image`, `quantity`, `code`, `date_added` , ROW_NUMBER()OVER(PARTITION BY `code`) AS row_id
                                                FROM `products`
                                                ORDER BY id ASC
                                            ) a
                                            WHERE a.row_id = 1");

											if($qry->num_rows > 0) {
												while($row = $qry->fetch_assoc()) {
                                                    $barcode = $row["code"];

                                                    $qryEoq = $conn->query("SELECT * FROM `sales_transactions_2_items`
                                                    WHERE product_barcode = '$barcode'
                                                    AND datetime_added >= (
                                                        SELECT MIN(datetime_added) FROM `sales_transactions_2_items`
                                                        WHERE product_barcode = '$barcode'
                                                        AND datetime_added <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
                                                    )
                                                    AND datetime_added BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW()");

                                                    if($qryEoq->num_rows > 0) { 
                                            ?>
                                                    <option><?php echo $row["name"]  ?></option>
                                            <?php 
                                                        }
                                                    }
                                            ?>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body js-eoq">
                                        <?php
                                            $total_demand = 0;
                                            // $order_cost_per_item = $_SESSION['system_info']['order_cost'];
                                            $holding_cost = $_SESSION['system_info']['handling_cost'];
                                            $query = "SELECT SUM(`order_quantity`) AS `order_quantity`, SUM(`order_amount`) AS `order_amount` FROM `demo_sales_eoq` WHERE YEAR(`order_date`) = 2022 AND `product_id` = 1;";
                                            $rows = query($query);
                                            if($rows)
                                            {
                                                foreach ($rows as $key => $row) {
                                                    $total_demand = round($row['order_quantity']);
                                                }
                                            }
                                        ?>
                                        <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                            <p class="text-success text-xl">
                                                <i class="ion ion-ios-refresh-empty"></i> 
                                                <!-- Demand  -->
                                            </p>
                                            <p class="d-flex flex-column text-right">
                                                <span class="font-weight-bold h4">
                                                <!-- <i class="ion ion-android-arrow-up text-success"></i>  -->
                                                0
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
                                                ₱0
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
                                                ₱0
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
                                                0
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
                                                0
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
                                                0
                                                </span>
                                                <span class="text-muted"> When to Order (day/s)</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal - Before View Report Returns -->
                <div class="modal fade" id="beforeViewReturnProductsModal" tabindex="-1" role="dialog" aria-labelledby="beforeViewReturnProductsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="beforeViewReturnProductsModalLabel">Sales</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <div class="container-fluid">
                            <div class="table-responsive" style="display: flex; justify-content: center; align-items: center;">
                            <div>
                                <h5 class="text-center text-bold mb-4">Please select a date range to view the reports</h5>
                                <span class="mr-4"> Minimum date:
                                <input type="date" id="minDateReturn" name="minDateReturn"></span>
                                <span>Maximum date:
                                <input type="date" id="maxDateReturn" name="maxDateReturn"></span>
                                <p id="returnDateError" class="text-center text-danger mt-2"></p>
                                <div class="mt-4" style="display: flex; justify-content: center; align-items: center;">
                                <button id="viewReturnProductsTable" class="btn btn-primary px-4">Enter</button>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Modal - After View Report Returns -->
                <div class="modal fade" id="viewReturnProductsModal" tabindex="-1" role="dialog" aria-labelledby="viewReturnProductsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="viewReturnProductsModalLabel">Returns (<span id="jsReturnsDateRange"></span>)</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <div class="container-fluid">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped table-bordered" id="returnsTable">
                                    <colgroup>
                                        <col width="20%">
                                        <col width="40%">
                                        <col width="40%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Supplier Name</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody class="js-returns">
                                    <tr>
                                     
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align:right">Total:</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal - BeforeView Report Sales -->
                <div class="modal fade" id="beforeViewReportSalesModal" tabindex="-1" role="dialog" aria-labelledby="beforeViewReportSalesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="beforeViewReportSalesModalLabel">Sales</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <div class="container-fluid">
                            <div class="table-responsive" style="display: flex; justify-content: center; align-items: center;">
                            <div>
                                <h5 class="text-center text-bold mb-4">Please select a date range to view the reports</h5>
                                <span class="mr-4"> Minimum date:
                                <input type="date" id="min" name="min"></span>
                                <span>Maximum date:
                                <input type="date" id="max" name="max"></span>
                                <p id="salesDateError" class="text-center text-danger mt-2"></p>
                                <div class="mt-4" style="display: flex; justify-content: center; align-items: center;">
                                <button id="viewReportSalesTable" class="btn btn-primary px-4">Enter</button>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Modal - After View Report Sales -->
                <div class="modal fade" id="viewReportSalesModal" tabindex="-1" role="dialog" aria-labelledby="viewReportSalesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="viewReportSalesModalLabel">Sales (<span id="jsSalesDateRange"></span>)</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <div class="container-fluid">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped table-bordered" id="salesTable">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="20%">
                                        <col width="10%">
                                        <col width="20%">
                                        <col width="20%">
                                        <col width="20%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Items Sold</th>
                                            <th>Revenue</th>
                                            <th>Cost of Goods Sold</th>
                                            <th>Gross Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="js-sales">
                                    <tr>
                                     
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align:right">Total:</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal - Before View Report Forecast -->
                <div class="modal fade" id="beforeViewReportForecastModal" tabindex="-1" role="dialog" aria-labelledby="beforeViewReportForecastModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="beforeViewReportForecastModalLabel">Sales</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <div class="container-fluid">
                            <div class="table-responsive" style="display: flex; justify-content: center; align-items: center;">
                            <div>
                                <h5 class="text-center text-bold mb-4">Please select a date range to view the reports</h5>
                                <span class="mr-4"> Minimum date:
                                <input type="date" id="minDateForecast" name="minDateForecast"></span>
                                <span>Maximum date:
                                <input type="date" id="maxDateForecast" name="maxDateForecast"></span>
                                <p id="forecastDateError" class="text-center text-danger mt-2"></p>
                                <div class="mt-4" style="display: flex; justify-content: center; align-items: center;">
                                <button id="viewReportForecastTable" class="btn btn-primary px-4">Enter</button>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Modal - After View Report Forecast -->
                <div class="modal fade" id="viewReportForecastModal" tabindex="-1" role="dialog" aria-labelledby="viewReportForecastModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="viewReportForecastModalModalLabel">Forecast (<span id="jsForecastDateRange"></span>)</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <div class="container-fluid">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped table-bordered" id="forecastTable">
                                    <colgroup>
                                        <col width="10%">
                                        <col width="40%">
                                        <col width="20%">
                                        <col width="30%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Items Sold</th>
                                            <th>Forecast</th>
                                        </tr>
                                    </thead>
                                    <tbody class="js-forecast">
                                    <tr>
                                     
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="text-align:right">Total:</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

<script>
    $(document).ready(function(){

        $('#productsCategoryForecast').select2({
            theme: 'bootstrap4',
            width: 'resolve'
        });

        $('#filterDate').select2({
            theme: 'bootstrap4',
            width: 'resolve'
        });

        $('#productsCategoryEoq').select2({
            placeholder: 'Select Product',
            theme: 'bootstrap4',
            width: 'resolve'
        });

        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');

        var table = $('#list-expiring-products').DataTable({
            dom: '<"row"<"col-sm-6"B><"col-sm-3"><"col-sm-3"f>>tr<"bottom"<"row no-gutters"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
            paging: true,
            autoWidth: true,
            buttons: [
                { extend: 'excelHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            'columnDefs': [
                {
                    orderable: false,
                    targets: 0,
                }
            ],
            'order': [[0, 'asc']],
                "oLanguage": {
                "sEmptyTable": "No records available."
            },
            'ordering': true,
        });

        var table = $('#inventory-reports').DataTable({
            retrieve: true,
                            footerCallback: function (row, data, start, end, display) {
                            var api = this.api();

                            // Remove the formatting to get integer data for summation
                            var intVal = function (i) {
                                return typeof i === 'string' ? i.replace(/[\₱,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                            };

                            // Total over all pages
                            secondColumnTotal = api
                                .column(2)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                console.log(secondColumnTotal);

                            // Total over this page
                            secondPageTotal = api
                                .column(2, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over all pages
                            thirdColumnTotal = api
                                .column(3)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            thirdPageTotal = api
                                .column(3, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                // Total over all pages
                            fourthColumnTotal = api
                                .column(4)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            fourthPageTotal = api
                                .column(4, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                // Total over all pages
                            fifthColumnTotal = api
                                .column(5)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            fifthPageTotal = api
                                .column(5, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over all pages
                            sixthColumnTotal = api
                            .column(6)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                            // Total over this page
                            sixthPageTotal = api
                            .column(6, { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                            // Update footer
                            $(api.column(2).footer()).html(secondPageTotal);
                            $(api.column(3).footer()).html(thirdPageTotal.toLocaleString());
                            $(api.column(4).footer()).html(fourthPageTotal.toLocaleString());
                            $(api.column(5).footer()).html(fifthPageTotal.toLocaleString());
                            $(api.column(6).footer()).html(sixthPageTotal.toLocaleString());
                        },
            dom: '<"row"<"col-sm-5"B><"col-sm-3"><"col-sm-3"f>>tr<"bottom"<"row no-gutters"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
            paging: true,
            autoWidth: true,
            buttons: [
                { extend: 'excelHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            'columnDefs': [
                {
                    orderable: false,
                    targets: 0,
                }
            ],
            'order': [[0, 'asc']],
                "oLanguage": {
                "sEmptyTable": "No records available."
            },
            'ordering': true,
        });

    $("#viewReturnProducts").on('click', function () {
        $('#beforeViewReturnProductsModal').modal('show');

    });

    $("#viewReturnProductsTable").on('click', function () {
        var minDate = $('#minDateReturn').val();
        var maxDate = $('#maxDateReturn').val();

        if(minDate && maxDate) {

            if(minDate <= maxDate) {
                var value = $(this).val();

            $('#returnDateError').text('');

            var convertedMinDate = new Date(minDate).toLocaleDateString("en-CA", { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
            var convertedMaxDate = new Date(maxDate).toLocaleDateString("en-CA", { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');

            $('#jsReturnsDateRange').text(`${convertedMinDate} to ${convertedMaxDate}`);

            $.ajax({
                url:"./report_returns.php",
                method:"POST",
                data:{
                    minDate: convertedMinDate,
                    maxDate: convertedMaxDate,
                },
                success:function(data)
                {
                    $('.js-returns').html(data);

                    var table = $('#returnsTable').DataTable({
                        retrieve: true,
                        footerCallback: function (row, data, start, end, display) {
                        var api = this.api();

                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ? i.replace(/[\₱,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };

                        // Total over all pages
                        secondColumnTotal = api
                            .column(2)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                            console.log(secondColumnTotal);

                        // Total over this page
                        secondPageTotal = api
                            .column(2, { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(2).footer()).html(secondPageTotal);
                    },

                        dom: '<"row"<"col-sm-6"B><"col-sm-3"><"col-sm-3"f>>tr<"bottom"<"row no-gutters"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
                        paging: true,
                        autoWidth: true,
                        buttons: [
                            { extend: 'excelHtml5', footer: true },
                            { extend: 'pdfHtml5', footer: true }
                        ],
                        columnDefs: [{
                            targets: 0,
                            searchable: false
                        }],
                        order:[0,'asc'],
                        "oLanguage": {
                            "sEmptyTable": "No records available."
                        }
                    });

                    $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');
                }
            });

            $('#min').val('');
            $('#max').val('');

            $('#beforeViewReturnProductsModal').modal('hide');

            $('.modal-backdrop').remove()

            $('#viewReturnProductsModal').modal({
                show:true,
            });
            } else {
                $('#returnDateError').text('The minimum date should be lower than the maximum date');
            }

        } else {
            $('#returnDateError').text('Please select a date');
        }

    });

    $("#viewReportSales").on('click', function () {
        $('#beforeViewReportSalesModal').modal('show');
    });

    $("#viewReportSalesTable").on('click', function () {
        var minDate = $('#min').val();
        var maxDate = $('#max').val();

        if(minDate && maxDate) {

            if(minDate <= maxDate) {
                var value = $(this).val();

                $('#salesDateError').text('');

                var convertedMinDate = new Date(minDate).toLocaleDateString("en-CA", { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
                var convertedMaxDate = new Date(maxDate).toLocaleDateString("en-CA", { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');

                $('#jsSalesDateRange').text(`${convertedMinDate} to ${convertedMaxDate}`);

                $.ajax({
                    url:"./report_sales.php",
                    method:"POST",
                    data:{
                        minDate: convertedMinDate,
                        maxDate: convertedMaxDate,
                    },
                    success:function(data)
                    {
                        $('.js-sales').html(data);

                        var table = $('#salesTable').DataTable({
                            retrieve: true,
                            footerCallback: function (row, data, start, end, display) {
                            var api = this.api();

                            // Remove the formatting to get integer data for summation
                            var intVal = function (i) {
                                return typeof i === 'string' ? i.replace(/[\₱,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                            };

                            // Total over all pages
                            secondColumnTotal = api
                                .column(2)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                console.log(secondColumnTotal);

                            // Total over this page
                            secondPageTotal = api
                                .column(2, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over all pages
                            thirdColumnTotal = api
                                .column(3)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            thirdPageTotal = api
                                .column(3, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                // Total over all pages
                            fourthColumnTotal = api
                                .column(4)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            fourthPageTotal = api
                                .column(4, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                // Total over all pages
                            fifthColumnTotal = api
                                .column(5)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            fifthPageTotal = api
                                .column(5, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Update footer
                            $(api.column(2).footer()).html(secondPageTotal);
                            $(api.column(3).footer()).html('₱' + thirdPageTotal.toLocaleString());
                            $(api.column(4).footer()).html('₱' + fourthPageTotal.toLocaleString());
                            $(api.column(5).footer()).html('₱' + fifthPageTotal.toLocaleString());
                        },

            dom: '<"row"<"col-sm-6"B><"col-sm-3"><"col-sm-3"f>>tr<"bottom"<"row no-gutters"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
            paging: true,
            autoWidth: true,
            buttons: [
                { extend: 'excelHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            columnDefs: [{
                targets: 0,
                searchable: false
            }],
            order:[0,'asc'],
            "oLanguage": {
                "sEmptyTable": "No records available."
            }
        });

                $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');
            }
        });

        $('#min').val('');
        $('#max').val('');

        $('#beforeViewReportSalesModal').modal('hide');

        $('.modal-backdrop').remove()

        $('#viewReportSalesModal').modal({
            show:true,
        });

            } else {
                $('#salesDateError').text('The minimum date should be lower than the maximum date');
            }
           
        } else {
            $('#salesDateError').text('Please select a date');
        }
    });

    $("#viewReportForecast").on('click', function () {
        $('#beforeViewReportForecastModal').modal('show');
    });

    $("#viewReportForecastTable").on('click', function () {

        var minDate = $('#minDateForecast').val();
        var maxDate = $('#maxDateForecast').val();

        if(minDate && maxDate) {

            if(minDate <= maxDate) {
                var value = $(this).val();

                $('#forecastDateError').text('');

                var convertedMinDate = new Date(minDate).toLocaleDateString("en-CA", { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
                var convertedMaxDate = new Date(maxDate).toLocaleDateString("en-CA", { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');

                $('#jsForecastDateRange').text(`${convertedMinDate} to ${convertedMaxDate}`);

                $.ajax({
                    url:"./report_forecast.php",
                    method:"POST",
                    data:{
                        minDate: convertedMinDate,
                        maxDate: convertedMaxDate,
                    },
                    success:function(data)
                    {
                        $('.js-forecast').html(data);

                        var table = $('#forecastTable').DataTable({
                            retrieve: true,
                            footerCallback: function (row, data, start, end, display) {
                            var api = this.api();

                            // Remove the formatting to get integer data for summation
                            var intVal = function (i) {
                                return typeof i === 'string' ? i.replace(/[\₱,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                            };

                            // Total over this page
                            secondPageTotal = api
                                .column(2, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            thirdPageTotal = api
                                .column(3, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Update footer
                            $(api.column(2).footer()).html(secondPageTotal);
                            $(api.column(3).footer()).html(thirdPageTotal);
                        },

                            dom: '<"row"<"col-sm-6"B><"col-sm-3"><"col-sm-3"f>>tr<"bottom"<"row no-gutters"<"col-sm-6"i><"col-sm-6"p>>><"clear">',
                            paging: true,
                            autoWidth: true,
                            buttons: [
                                { extend: 'excelHtml5', footer: true },
                                { extend: 'pdfHtml5', footer: true }
                            ],
                            columnDefs: [{
                                targets: 0,
                                searchable: false
                            }],
                            order:[0,'asc'],
                            "oLanguage": {
                                "sEmptyTable": "No records available."
                            }
                        });

                        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');
                    }
                });

                $('#minDateForecast').val('');
                $('#maxDateForecast').val('');

                $('#beforeViewReportForecastModal').modal('hide');

                $('.modal-backdrop').remove()

                $('#viewReportForecastModal').modal({
                        show:true,
                });
            } else {
                $('#forecastDateError').text('The minimum date should be lower than the maximum date');
            }

        } else {
            $('#forecastDateError').text('Please select a date');
        }
    });

    $("#productsCategoryEoq").on('change', function () {
		var value = $(this).val();

		$.ajax({
			url:"./eoq.php",
			method:"POST",
			data:{
                category: value,
            },
			success:function(data)
			{
				$('.js-eoq').html(data);
			}
		});
	});
    });

    // $("#productsCategoryForecast").on('change', function () {
	// 	var value = $(this).val();

	// 	$.ajax({
	// 		url:"./forecast.php",
	// 		method:"POST",
	// 		data:{
    //             category: value,
    //         },
	// 		success:function(data)
	// 		{
    //             trendForecastingChart.data.datasets[0].data = data;
	// 		}
	// 	});
	// });
</script>
