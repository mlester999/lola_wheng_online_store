
<br>

<?php 
    $reorder = 0;
    $critical = 0;

    $sql_reorder = "SELECT COUNT(*) AS reorder_cnt 
                        FROM products p
                        JOIN (
                            SELECT code, MAX(id) AS id, SUM(quantity) as `quantity`
                            FROM products
                            GROUP BY code
                        ) latest_p ON p.id = latest_p.id
                        WHERE latest_p.quantity BETWEEN (p.critical_q + 1) AND p.reordering_q AND
                            p.id NOT IN (
                                SELECT DISTINCT product_id FROM orders_2 WHERE status IN (0) ORDER BY datetime_ordered DESC
                            )
                        LIMIT 1";
    $qry_reorder = $conn->query($sql_reorder);
    $row1 = $qry_reorder->fetch_assoc();
    $reorder =  $row1['reorder_cnt'];

    $sql_critical = "SELECT COUNT(*) AS critical_cnt 
                        FROM products p 
                        JOIN (
                            SELECT code, MAX(id) AS id, SUM(quantity) as `quantity`
                            FROM products
                            GROUP BY code
                        ) latest_p ON p.id = latest_p.id
                        WHERE latest_p.quantity <= p.critical_q AND 
                            p.id NOT IN (
                                SELECT DISTINCT product_id FROM orders_2 WHERE status IN (0) ORDER BY datetime_ordered DESC
                            )
                        LIMIT 1";
    $qry_critical = $conn->query($sql_critical);
    $row2 = $qry_critical->fetch_assoc();
    $critical = $row2['critical_cnt'];
    
?>

<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-list-orders-tab" data-toggle="pill" href="#custom-tabs-list-orders" role="tab" aria-controls="custom-tabs-list-orders" aria-selected="true">List of Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-request-order-tab" data-toggle="pill" href="#custom-tabs-request-order" role="tab" aria-controls="custom-tabs-request-order" aria-selected="false">
                    Purchase Orders
                    <span class="badge badge-warning"><?= $reorder + $critical;?></span>
                </a>

            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-four-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-list-orders" role="tabpanel" aria-labelledby="custom-tabs-list-orders-tab">
                <div class="card-tools float-right mb-3 d-none">
                    <a href="./?page=orders/manage_order" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span> Add </a>
                </div>
                <div class="container-fluid">
                    <form id="frm-selected" class="frm-selected" action="" method="POST">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped table-bordered tbl-list-order" id="list" >
                                <colgroup>
                                    <col width="5%">
                                    <col width="15%">
                                    <col width="20%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th>Supplier</th>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th>Transaction Code</th>
                                        <th>Date Ordered</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        $qry = $conn->query("SELECT o.order_id
                                                                    , s.name AS supplier_name
                                                                    , c.category_name
                                                                    , p.name AS product_name
                                                                    , m.measurement_name
                                                                    , m.measurement_code
                                                                    , o.quantity
                                                                    , o.transaction_code
                                                                    , o.status
                                                                    , o.datetime_ordered
                                                            FROM `orders_2` o
                                                            JOIN `suppliers` s ON o.supplier_id = s.id
                                                            JOIN `categories` c ON o.category_id = c.category_id
                                                            JOIN `products` p ON o.product_id = p.id
                                                            JOIN `measurements` m ON o.unit_id = m.measurement_id
                                                            ORDER BY o.datetime_ordered DESC;
                                                        ");
                                        if($qry->num_rows > 0) {
                                            while($row = $qry->fetch_assoc()) {
                                    ?>
                                                <tr>
                                                    <td class="text-center" data-order_id="<?php echo $row['order_id']; ?>"><?php echo $row['order_id']; ?></td>
                                                    <td class="align-items-center"><?php echo $row['supplier_name']; ?></td>
                                                    <td class="align-items-center "><?php echo $row['product_name']; ?>
                                                        <br>
                                                        <small class="badge badge-primary"> 
                                                            <?php echo $row['category_name']; ?>
                                                        </small>
                                                    </td>
                                                    <td class="align-items-cente text-center"><?php echo $row['quantity']; ?>
                                                        <br>
                                                        <small class="badge badge-dark"> 
                                                            <?php echo $row['measurement_name'] . ' (' . $row['measurement_code'] . ')'; ?>
                                                        </small>
                                                    </td>
                                                    <td class="align-items-center"><?php echo $row['transaction_code']; ?></td>
                                                    <td class="align-items-center truncate-1"><?php echo date('Y-m-d',strtotime($row['datetime_ordered'])); ?></td>
                                                    <td class="align-items-center">
                                                        <?php if($row['status'] == 0): ?>
                                                            <span class="badge badge-warning px-3 rounded-pill">Not Delivered</span>
                                                        <?php elseif($row['status'] == 1): ?>  
                                                            <span class="badge badge-primary px-3 rounded-pill">Delivered</span>
                                                        <?php elseif($row['status'] == 2): ?>  
                                                            <span class="badge badge-danger px-3 rounded-pill">Cancelled</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="align-items-center" align="center">
                                                        <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                Action
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            <!-- <a class="dropdown-item " href="./?page=orders/view_order&order_id=<?php echo $row['order_id'] ?>"><span class="fa fa-eye text-dark"></span> View</a> -->
                                                            <a class="dropdown-item " href="./?page=orders/print_order&order_id=<?php echo $row['order_id'] ?>"><span class="fa fa-print text-dark"></span> Print</a>
                                                            <?php if($row['status'] == 0): ?>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="./?page=orders/manage_order&order_id=<?php echo $row['order_id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                                            <?php endif; ?>
                                                        </div>
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

                            <br>
                            <button type="button" name="btn_update_selected" class="btn btn-sm btn-primary btn_update_selected"> 
                                <i class="fa fa-edit" aria-hidden="true"></i> Update Selected
                            </button>
                            <button type="button" name="btn_delete_selected" class="btn btn-sm btn-danger btn_delete_selected"> 
                                <i class="fa fa-trash" aria-hidden="true"></i> Delete Selected
                            </button>

                            <input type="hidden" id="row_selected" value="" placeholder="row selected">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Request Order -->
            <div class="tab-pane fade" id="custom-tabs-request-order" role="tabpanel" aria-labelledby="custom-tabs-request-order-tab">
                <div class="container-fluid">
                    <form id="frm-request-order" class="frm-request-order" action="" method="POST">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped table-bordered " id="list-request-order" >
                                <colgroup>
                                    <col width="5%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th>Supplier Name</th>
                                        <th>Product Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Stock Left</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        $qry = $conn->query("SELECT p.id AS product_id, s.name, m.measurement_name, m.measurement_code, c.category_name
                                        , p.code AS product_code, p.name AS product_name
                                        , SUM(latest_p.quantity) as `quantity`
                                        , p.reordering_point
                                        , p.critical_point
                                        , p.expiration_date
                                        , p.expiration_gap
                                        , p.original_price
                                        , p.retail_price
                                        , p.retail_pct
                                        , p.ceiling_q
                                        , p.reordering_q
                                        , p.critical_q
                                        , CASE WHEN SUM(latest_p.quantity) <= p.critical_q THEN 'critical'
                                                WHEN SUM(latest_p.quantity) <= p.reordering_q THEN 'reorder'
                                                ELSE 'good'
                                            END AS end_point
                                        , s.id AS supplier_id
                                        , c.category_id
                                        , m.measurement_id
                                        , o2.transaction_code
                                    FROM products p
                                    JOIN (
                                        SELECT code, MAX(id) AS id, SUM(quantity) as `quantity`
                                        FROM products
                                        GROUP BY code
                                    ) latest_p ON p.id = latest_p.id
                                    JOIN suppliers s ON p.supplier_id = s.id
                                    JOIN measurements m ON p.unit_id = m.measurement_id
                                    JOIN categories c ON p.category_id = c.category_id
                                    LEFT JOIN orders_2 o2 ON p.id = o2.product_id
                                    WHERE 
                                    (latest_p.quantity <= p.reordering_q OR 
                                    latest_p.quantity <= p.critical_q) AND 
                                    p.id NOT IN (
                                            SELECT DISTINCT product_id FROM orders_2 WHERE status IN (0) ORDER by datetime_ordered DESC
                                        )
                                GROUP BY latest_p.code
                                                        ");
                                        if($qry->num_rows > 0) {
                                            while($rowro = $qry->fetch_assoc()) {
                                                $barcode = $rowro['product_code'];
                                                $total_demand = 0;
                                                $order_cost_per_item = number_format($_SESSION['system_info']['vat'], 2) / 100 * $rowro['original_price'] + number_format($_SESSION['system_info']['delivery_fee']);
                                                $holding_cost = $_SESSION['system_info']['handling_cost'];
                                                
                                                $queryEoq = "SELECT SUM(`quantity`) FROM `sales_transactions_2_items`
                                                WHERE product_barcode = '$barcode'
                                                AND datetime_added >= (
                                                    SELECT MIN(datetime_added) FROM `sales_transactions_2_items`
                                                    WHERE product_barcode = '$barcode'
                                                    AND datetime_added <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
                                                )
                                                AND datetime_added BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW()";

                                            $resultEoq = mysqli_query($conn, $queryEoq);

                                            $latestCeilingPointQty = 0;

                                            if(mysqli_num_rows($resultEoq) > 0)
                                            {
                                            
                                                $rows = $conn->query($queryEoq);

                                                if($rows)
                                                {
                                                    foreach ($rows as $key => $row) {
                                                        $total_demand = round($row['SUM(`quantity`)']);
                                                    }
                                                }

                                                $eoq = round(sqrt((2 * $total_demand * number_format($order_cost_per_item)) / $holding_cost),0);
                                                
                                                if($eoq > 0) {
                                                    $latestCeilingPointQty = $eoq;
                                                    
                                                } else {
                                                    $latestCeilingPointQty = $rowro['ceiling_q'];
                                                }
                                            } else {
                                                $latestCeilingPointQty = $rowro['ceiling_q'];
                                            }
                                    ?>
                                                <tr>
                                                    <td class="align-items-center text-center"><?php echo $rowro['product_id']; ?></td>
                                                    <td class="align-items-center"><?php echo $rowro['name']; ?></td>
                                                    <td class="align-items-center"><?php echo $rowro['product_code']; ?></td>
                                                    <td class="align-items-center td-product_name"><?php echo $rowro['product_name']; ?></td>
                                                    <td><?php echo $rowro['category_name']; ?></td>
                                                    <td class="align-items-cente text-center"><?php echo $rowro['quantity']; ?></td>
                                                    <td class="align-items-center text-center">₱ <?php echo $rowro['original_price']; ?></td>
                                                    <td class="align-items-center">
                                                        <?php if($rowro['end_point'] == 'reorder'): ?>
                                                            <span class="badge badge-warning px-3 py-1 rounded-pill"><i class="fas fa-info-circle"></i> Reorder Stocks</span>
                                                        <?php elseif($rowro['end_point'] == 'critical'): ?>  
                                                            <span class="badge badge-danger px-3 py-1 rounded-pill"><i class="fas fa-exclamation-triangle"></i> Critical Stocks</span>
                                                        <?php elseif($rowro['end_point'] == 'good'): ?>  
                                                            <span class="badge badge-primary px-3 py-1 rounded-pill">Good</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="align-items-center" align="center">
                                                        <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                Action
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu">
                                                            <a class="dropdown-item btn-ReorderRequest" id="btn-ReorderRequest" href="javascript:void(0)" data-product_id="<?php echo $rowro['product_id'] ?>" data-toggle="modal" data-target="#ReorderRequestModal"><span class="fa fa-edit text-dark"></span> Request Order </a>
                                                            <!-- <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="./?page=orders/manage_order&order_id=<?php echo $rowro['product_id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item delete_data" href="javascript:void(0)" data-order_id="<?php echo $rowro['product_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a> -->
                                                        </div>

                                                        <span class="d-none td-barcode"><?php echo $rowro['product_code']; ?></span>
                                                        <span class="d-none td-original_price"><?php echo $rowro['original_price']; ?></span>
                                                        <span class="d-none td-retail_pct"><?php echo $rowro['retail_pct']; ?></span>
                                                        <span class="d-none td-retail_price"><?php echo $rowro['retail_price']; ?></span>
                                                        <?php if(isset($latestCeilingPointQty)): ?>
                                                        <span class="d-none td-ceiling_q"><?php echo $latestCeilingPointQty ?></span>
                                                        <?php else: ?>
                                                        <span class="d-none td-ceiling_q"><?php echo $rowro['ceiling_q'] ?></span>
                                                        <?php endif; ?>
                                                        <span class="d-none td-reordering_point"><?php echo $rowro['reordering_point']; ?></span>
                                                        <span class="d-none td-critical_point"><?php echo $rowro['critical_point']; ?></span>
                                                        <span class="d-none td-expiration_date"><?php echo $rowro['expiration_date']; ?></span>
                                                        <span class="d-none td-expiration_gap"><?php echo $rowro['expiration_gap']; ?></span>
                                                        <span class="d-none td-supplier_id"><?php echo $rowro['supplier_id']; ?></span>
                                                        <span class="d-none td-category_id"><?php echo $rowro['category_id']; ?></span>
                                                        <span class="d-none td-measurement_id"><?php echo $rowro['measurement_id']; ?></span>
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

                            <br>
                            <button type="button" name="btn_update_selected_ro" class="btn btn-sm btn-primary btn_update_selected_ro"> 
                                <i class="fa fa-edit" aria-hidden="true"></i> Update Selected
                            </button>
                            <button type="button" name="btn_delete_selected_ro" class="btn btn-sm btn-danger btn_delete_selected_ro"> 
                                <i class="fa fa-trash" aria-hidden="true"></i> Delete Selected
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline rounded-0 card-navy d-none">
	<div class="card-header">
		<h3 class="card-title">List of Orders</h3>
		<div class="card-tools">
			<a href="./?page=orders/manage_order" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span> Add </a>
		</div>
	</div>
	<div class="card-body">
        
	</div>
</div>




<!-- Modal - Update Selected List Order  -->
<div class="modal fade" id="UpdateSelectedRowModal" tabindex="-1" role="dialog" aria-labelledby="UpdateSelectedRowModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="UpdateSelectedRowModalLabel">Update Selected List Orders</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="" id="frm-modal-update-selected">
            <div class="modal-body">
                <input type="text" name="rows_selected" id="rows_selected" value="">

                <label for="status" class="control-label">Status</label>
                <div class="form-group d-flex">
                    <div class="custom-control custom-radio pr-2">
                        <input class="custom-control-input custom-control-input-warning" type="radio" id="customRadio2" name="status" value="0" >
                        <label for="customRadio2" class="custom-control-label">Not Delivered</label>
                    </div>
                    <div class="custom-control custom-radio pr-2">
                        <input class="custom-control-input custom-control-input-primary " type="radio" id="customRadio4" name="status" value="1" >
                        <label for="customRadio4" class="custom-control-label">Delivered</label>
                    </div>
                    <div class="custom-control custom-radio pr-2">
                        <input class="custom-control-input custom-control-input-danger " type="radio" id="customRadio5" name="status" value="2" >
                        <label for="customRadio5" class="custom-control-label">Cancelled</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
  </div>
</div>



<!-- Modal - Reorder Request -->
<div class="modal fade" id="ReorderRequestModal" tabindex="-1" role="dialog" aria-labelledby="ReorderRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h3 class="modal-title" id="ReorderRequestModalLabel">Request Order</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" id="request-order-form">
                <div class="modal-body">
                    <?php 
                        $suppliers = $conn->query("SELECT * FROM `suppliers` WHERE is_archived = 0 AND is_active = 1");
                        $categories = $conn->query("SELECT * FROM `categories` WHERE is_active = 1");
                        $measurements = $conn->query("SELECT * FROM `measurements` WHERE is_active = 1");
                    ?>
                    <input type="hidden" value="" name="product_id" id="product_id">
                    <div class="row ">
                        <div class="col-sm-6">
                            <div class="form-group mb-2 pb-2">
                                <label for="" class="control-label">Supplier</label>
                                <select name="supplier_id" class="custom-select rounded-0" id="supplier_id" disabled>
                                    <option value="">Select Supplier</option>
                                    <?php foreach($suppliers->fetch_all(MYSQLI_ASSOC) as $sup): ?>
                                        <option value="<?=$sup['id'] ?>"><?= $sup['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-2 pb-2">
                                <label for="barcode" class="control-label">Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control form-control-sm rounded-0" value="" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-2 pb-2">
                                <label for="category_id" class="control-label">Category</label>
                                <select name="category_id" class="custom-select rounded-0" id="category_id" required="required">
                                    <option value="">Select Category</option>
                                    <?php foreach($categories->fetch_all(MYSQLI_ASSOC) as $cat): ?>
                                        <option value="<?= $cat['category_id'] ?>" ><?= $cat['category_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-2 pb-2">
                                <label for="unit_id" class="control-label">Unit</label>
                                <select name="unit_id" class="custom-select rounded-0" id="unit_id" required="required">
                                    <option value="">Select Unit/Measure</option>
                                    <?php foreach($measurements->fetch_all(MYSQLI_ASSOC) as $mes): ?>
                                        <option value="<?= $mes['measurement_id'] ?>" <?= isset($order_id) && $unit_id == $mes['measurement_id'] ? "selected" : "" ?>><?= $mes['measurement_name'] . ' (' . $mes['measurement_code']. ')'?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="quantity" class="control-label">Order Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control form-control-sm rounded-0 bg-input" value=""  required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="original_price" class="control-label">Original Price</label>
                                <input type="number" name="original_price" id="original_price" step="0.01" class="form-control form-control-sm rounded-0" value=""  required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="retail_pct" class="control-label">Retail %</label>
                                <input type="number" name="retail_pct" id="retail_pct" step="0.01" class="form-control form-control-sm rounded-0" value=""  required/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="retail_price" class="control-label">Retail Price</label>
                                <input type="number" name="retail_price" id="retail_price" step="0.01" class="form-control form-control-sm rounded-0" value=""  required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="ceiling_q" class="control-label">Ceiling Point Qty</label>
                                <input type="number" name="ceiling_q" id="ceiling_q" step="0.01" class="form-control form-control-sm rounded-0" value=""  required disabled/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="reordering_point" class="control-label">Reordering Point %</label>
                                <input type="number" name="reordering_point" id="reordering_point" step="0.01" class="form-control form-control-sm rounded-0" value=""  required/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="critical_point" class="control-label">Critical Point %</label>
                                <input type="number" name="critical_point" id="critical_point" step="0.01" class="form-control form-control-sm rounded-0" value=""  required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="reordering_q" class="control-label">Reordering Point Quantity </label>
                                <input type="number" name="reordering_q" id="reordering_q" class="form-control form-control-sm rounded-0" value="" disabled />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="critical_q" class="control-label">Critical Point Quantity </label>
                                <input type="number" name="critical_q" id="critical_q" class="form-control form-control-sm rounded-0" value="" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="expiration_date" class="control-label">Expiry Date</label>
                                <input type="date" name="expiration_date" id="expiration_date" class="form-control form-control-sm rounded-0" value=""  required/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="expiration_gap" class="control-label">Expiry Gap</label>
                                <input type="number" name="expiration_gap" id="expiration_gap" class="form-control form-control-sm rounded-0" value=""  required/>
                            </div>  
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" form="request-order-form" class="btn btn-primary ">Order Now</button>
                </div>
            </form>
        </div>
    </div>
</div>






<script>
    $('.btn_update_selected').hide();
    $('.btn_delete_selected').hide();

    $('.btn_update_selected_ro').hide();
    $('.btn_delete_selected_ro').hide();

    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this order permanently?","delete_order",[$(this).attr('data-order_id')]);
            
        });

        // $('.table').dataTable({
        //     columnDefs: [
        //             { 
        //                 orderable: false, 
        //                 targets: [2,7],
        //             }
        //     ],
        //     order:[0,'asc'],
        //     "oLanguage": {
        //         "sEmptyTable": "No records available."
        //     },
        //     "ordering": false,
        // });

        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');

        var table = $('.tbl-list-order').DataTable({
            'columnDefs': [
                {
                    orderable: false,
                    targets: 0,
                    checkboxes: {
                        selectRow: true,
                    },
                }
            ],
            'select': {
                'style': 'multi',
            },
            'order': [[1, 'asc']],
                "oLanguage": {
                "sEmptyTable": "No records available."
            },
            'ordering': false,
        });


        $('#ReorderRequestModal #supplier_id').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('#ReorderRequestModal'),
        });

        $('#ReorderRequestModal #category_id').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('#ReorderRequestModal'),
        });
        
        $('#ReorderRequestModal #unit_id').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('#ReorderRequestModal'),
        });
        
        // Handle form submission event
        // $('#frm-selected').on('submit', function(e){
        //     var form = this;

        //     var rows_selected = table.column(0).checkboxes.selected();

        //     // Iterate over all selected checkboxes
        //     $.each(rows_selected, function(index, rowId){
        //         // Create a hidden element
        //         $(form).append(
        //             $('<input>')
        //                 .attr('type', 'hidden')
        //                 .attr('name', 'order_id[]')
        //                 .val(rowId)
        //         );
        //     });

        //     e.preventDefault();
        // });



        // $('#list-request-order').DataTable(
        //     columnDefs: [ {
        //                     orderable: false,
        //                     className: 'select-checkbox',
        //                     targets:   0
        //                 } ],
        //     select: {
        //         style:    'os',
        //         selector: 'td:first-child'
        //     },
        //     order: [[ 1, 'asc' ]]
        // );
        
        var table = $('#list-request-order').DataTable({
            'columnDefs': [
                {
                    orderable: false,
                    targets: 0,
                    checkboxes: {
                        selectRow: true,
                    },
                }
            ],
            'select': {
                'style': 'multi',
            },
            'order': [[1, 'asc']],
                "oLanguage": {
                "sEmptyTable": "No records available."
            },
            'ordering': false,
        });

        $('.tbl-list-order input[type="checkbox"]').click(function() {

            if($(this).prop("checked") == true) {
                if($(this).parent().hasClass('dt-checkboxes-select-all')) {
                    $('.tbl-list-order tbody tr').addClass('bg-row-selected');
                    $('.btn_update_selected').show();
                    $('.btn_delete_selected').hide();
                } else {
                    $(this).parent().parent().addClass('bg-row-selected');
                    $('.btn_update_selected').show();
                    $('.btn_delete_selected').hide();
                }

                GetSelectedRows();
            }
            else if($(this).prop("checked") == false) {
                if($(this).parent().parent().hasClass('bg-row-selected')) {
                    $(this).parent().parent().removeClass('bg-row-selected');
                }

                if($(this).parent().hasClass('dt-checkboxes-select-all')) {
                    $('.tbl-list-order tbody tr').removeClass('bg-row-selected');
                    $('.btn_update_selected').hide();
                    $('.btn_delete_selected').hide();
                }

                GetSelectedRows();
            }

            var row_selected_count = $('tr.bg-row-selected').length;

            if(row_selected_count > 0) {
                $('.btn_update_selected').show();
                $('.btn_delete_selected').hide();
            } else {
                $('.btn_update_selected').hide();
                $('.btn_delete_selected').hide();
            }
        });

        function GetSelectedRows() {
            var data = Array();
            $(".tbl-list-order tr.bg-row-selected").each(function(i, v){
                data[i] = Array();
                $(this).children('td').each(function(ii, vv){
                    if($(this).attr("data-order_id")) {
                        data[i][ii] = $(this).data("order_id");
                    }
                }); 
            });

            $('#row_selected').val(data);
            $('#UpdateSelectedRowModal input#rows_selected').val(data);
        }

        $(".btn_update_selected").click(function(){
            $('#UpdateSelectedRowModal').modal({
                show: true
            })

            GetSelectedRows();     
            
            $('#UpdateSelectedRowModal input#rows_selected').hide();
        });


        // $('#btn-ReorderRequest').click(function(){
        //     var currentRow = $(this).closest("tr");
        //     var thisrow = currentRow.find(".btn-ReorderRequest").data("product_id");
        //     alert(thisrow)
        //     $('#ReorderRequestModal').on('show.bs.modal', function (event) {
        //         $('#ReorderRequestModal input#product_id').val(thisrow);
        //     });
        // });

        $("#frm-request-order").on('click', '.btn-ReorderRequest', function(e) {
            e.preventDefault();
            var currentRow = $(this).closest("tr");
            var thisrow = currentRow.find("#btn-ReorderRequest").data("product_id");
            var product_name = currentRow.find(".td-product_name").text();
            var barcode = currentRow.find(".td-barcode").text();
            var original_price = currentRow.find(".td-original_price").text();
            var retail_pct = currentRow.find(".td-retail_pct").text();    
            var retail_price = currentRow.find(".td-retail_price").text();
            var ceiling_q = currentRow.find(".td-ceiling_q").text();
            var reordering_point = currentRow.find(".td-reordering_point").text();
            var critical_point = currentRow.find(".td-critical_point").text();
            var expiration_date = currentRow.find(".td-expiration_date").text();
            var expiration_gap = currentRow.find(".td-expiration_gap").text();
            var supplier_id = currentRow.find(".td-supplier_id").text();
            var category_id = currentRow.find(".td-category_id").text();
            var measurement_id = currentRow.find(".td-measurement_id").text();

            var reordering_point_quantity = reordering_point / 100;
            var critical_point_quantity = critical_point / 100;
            
            $("#ReorderRequestModal").modal();
            $("#ReorderRequestModal input#product_id").val(thisrow);
            $("#ReorderRequestModal input#product_name").val(product_name);
            $("#ReorderRequestModal input#barcode").val(barcode);
            $("#ReorderRequestModal input#original_price").val(original_price);
            $("#ReorderRequestModal input#retail_pct").val(retail_pct);
            $("#ReorderRequestModal input#retail_price").val(retail_price);
            $("#ReorderRequestModal input#ceiling_q").val(ceiling_q);
            $("#ReorderRequestModal input#reordering_q").val(round(ceiling_q * reordering_point_quantity,0));
            $("#ReorderRequestModal input#critical_q").val(round(ceiling_q * critical_point_quantity,0));
            $("#ReorderRequestModal input#reordering_point").val(reordering_point);
            $("#ReorderRequestModal input#critical_point").val(critical_point);
            $("#ReorderRequestModal input#expiration_date").val(expiration_date);
            $("#ReorderRequestModal input#expiration_gap").val(expiration_gap);

            $("#ReorderRequestModal select#supplier_id").val(supplier_id).change();
            $("#ReorderRequestModal select#category_id").val(category_id).change();
            $("#ReorderRequestModal select#unit_id").val(measurement_id).change();            

        });

        // $("#ReorderRequestModal input#reordering_q").parent().parent().parent().hide();

        $('#ReorderRequestModal').on('hidden.bs.modal', function(e) {
            e.preventDefault();
            document.getElementById("frm-request-order").reset(); 
            //or $('#form2')[0].reset();
        });

        $('#ReorderRequestModal #retail_price').parent().css({'pointer-events':'none'});

        $('#ReorderRequestModal #original_price').on('change keyup', function() {
            // let retail_price = this.value * ($('#retail_pct').val() / 100);
            // $('#retail_price').val(parseFloat(this.value) + parseFloat(retail_price));
            getRetailPrice();
        }); 


        $('#ReorderRequestModal #retail_pct').on('change keyup', function() {
            getRetailPrice();
        });

        $('#ReorderRequestModal #ceiling_q').on('change keyup', function() {
            getQuantity_Point();
        });

        $('#ReorderRequestModal #quantity').on('change keyup', function() {
            getQuantity_Point();
        });

        $('#ReorderRequestModal #reordering_point').on('change keyup', function() {
            getQuantity_Point();
        });

        $('#ReorderRequestModal #critical_point').on('change keyup', function() {
            getQuantity_Point();
        });

        function getRetailPrice() {
            let retail_price = $('#ReorderRequestModal #original_price').val() * ($('#ReorderRequestModal #retail_pct').val() / 100);
            $('#ReorderRequestModal #retail_price').val(round(parseFloat($('#ReorderRequestModal #original_price').val()) + parseFloat(retail_price),2));
        }

        function getQuantity_Point() {
            let ceiling_point_quantity = $('#ReorderRequestModal #ceiling_q').val();
            let reordering_point = $('#ReorderRequestModal #reordering_point').val() / 100;
            let critical_point = $('#ReorderRequestModal #critical_point').val() / 100;
            
            // $('#ReorderRequestModal #ceiling_q').val(0);
            $('#ReorderRequestModal #reordering_q').val(round(ceiling_point_quantity * reordering_point,0));
            $('#ReorderRequestModal #critical_q').val(round(ceiling_point_quantity * critical_point,0));
        }

        function round_up(value, decPlaces) {
            return Math.ceil(value * Math.pow(10, decPlaces)) / Math.pow(10, decPlaces);
        }

        function round(num,dec){
            var d = 1;
            for (var i=0; i<dec; i++){
                d += "0";
            }
            return Math.round(num * d) / d;
        }
    });

   

    $('#request-order-form').submit(function(e){
        e.preventDefault();
        
        var _this = $(this);

        $('.err-msg').remove();

        $("#supplier_id").prop("disabled", false );
        $("#barcode").prop("disabled", false );
        $("#ceiling_q").prop("disabled", false );
        $("#reordering_q").prop("disabled", false );
        $("#critical_q").prop("disabled", false );

        $.ajax({
            url:_base_url_+"classes/Orders.php?f=save_order_modal",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error:err=>{
                console.log(err)
                alert_toast("An error occured",'error');
            },

            success:function(resp){
                if(typeof resp =='object' && resp.status == 'success'){
                    location.replace('./?page=orders');
                }else if(resp.status == 'failed' && !!resp.msg){
                    var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body").scrollTop(0);
                        end_loader()
                }else{
                    alert_toast("An error occured",'error');
                    end_loader();
                    console.log(resp)
                }
            }
        });
    });

    function delete_order($order_id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Orders.php?f=delete_order",
            method:"POST",
            data:{order_id: $order_id},
            dataType:"json",
            error:err=>{
                // console.log(err)
                alert_toast("An error occured.",'error');
                end_loader();
            },
            success:function(resp){
                if(typeof resp== 'object' && resp.status == 'success'){
                    location.reload();
                }else{
                    alert_toast("An error occured.",'error');
                    end_loader();
                }
            }
        })
    }


    $('#UpdateSelectedRowModal #frm-modal-update-selected').submit(function(e){
        e.preventDefault();
        
        var _this = $(this);

        $('.err-msg').remove();

        $.ajax({
            url:_base_url_+"classes/Orders.php?f=update_order_selected",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error:err=>{
                console.log(err)
                alert_toast("An error occured",'error');
            },

            success:function(resp){
                if(typeof resp =='object' && resp.status == 'success'){
                    location.replace('./?page=orders');
                }else if(resp.status == 'failed' && !!resp.msg){
                    var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body").scrollTop(0);
                        end_loader()
                }else{
                    alert_toast("An error occured",'error');
                    end_loader();
                    console.log(resp)
                }
            }
        });
    });
</script>

<style>
    .bg-row-selected {
        background-color: #01ff70 !important;
    }

    .bg-input {
        background-color: #00ff7e !important;
    }
</style>