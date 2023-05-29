<?php
if(isset($_GET['order_id']) && $_GET['order_id'] > 0){
    $qry = $conn->query("SELECT * from `orders_2` where order_id = '{$_GET['order_id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}

$suppliers = $conn->query("SELECT * FROM `suppliers` WHERE is_archived = 0 AND is_active = 1 AND id = $supplier_id");
if($suppliers->num_rows > 0){
    foreach($suppliers->fetch_assoc() as $sk => $sv){
        $$sk=$sv;
    }
}

?>

<style>
    .border-primary {
        border-width: 2px !important;
    }

    .form-group {
        color: #666666 !important;
        font-size: 16px;
    }

    .form-group > label {
        color: #000000;
        font-size: 13px;
    }
</style>
<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b>Print Order Details</b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0">
			<div class="card-body" id="print_order_data">
				<div class="container-fluid">
						<input type="hidden" name ="order_id" value="<?php echo isset($order_id) ? $order_id : '' ?>">
                        <div class="row mb-3">
                            <div class="col-lg-12 col-md-12 col-sm-12 bg-primary p-2 text-center">
                            <h2 class="text-bold" style="text-align: center;"><?=$_SESSION['system_info']['name'] ?></h2>
                            </div>
                        </div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Supplier Name:</label>
									<br>
                                    <?=$name?>
								</div>
							</div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-lg-right text-md-right text-sm-left ">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Transaction Date:</label>
									<br>
                                    <?=$datetime_ordered?>
								</div>
							</div>
                        </div>

                        <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Contact Name:</label>
									<br>
                                    <?=$contact_name?>
								</div>
							</div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-lg-right text-md-right text-sm-left">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Transaction No.:</label>
									<br>
                                    <?=$transaction_code?>
								</div>
							</div>
                        </div>

                        <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Contact Number:</label>
									<br>
                                    <?=$contact_number?>
								</div>
							</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Email Address:</label>
									<br>
                                    <?=$email_address?>
								</div>
							</div>
                        </div>

                        <hr style="border-top: dotted 2px;"  />

                        <div class="row my-3">
                            <div class="col-lg-12 col-md-12-col-sm-12 text-primary">
                                <h3> Purchase Order </h3>
                                <hr style="border-top: dotted 2px; color: #000;"  />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product Code</th>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $qry_prods = $conn->query("SELECT p.id AS product_id, s.name, m.measurement_name, m.measurement_code, c.category_name
                                                                            , p.code AS product_code, p.name AS product_name
                                                                            , p.quantity
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
                                                                            , CASE WHEN p.quantity <= p.critical_q THEN 'critical'
                                                                                    WHEN p.quantity <= p.reordering_q THEN 'reorder'
                                                                                    ELSE 'good'
                                                                                END AS end_point
                                                                            , s.id AS supplier_id
                                                                            , c.category_id
                                                                            , m.measurement_id
                                                                            , o2.transaction_code
                                                                    FROM products p
                                                                        JOIN suppliers s ON p.supplier_id = s.id
                                                                        JOIN measurements m ON p.unit_id = m.measurement_id
                                                                        JOIN categories c ON p.category_id = c.category_id
                                                                        LEFT JOIN orders_2 o2 ON p.id = o2.product_id
                                                                    WHERE 
                                                                        o2.order_id = $order_id
                                                                ");
                                                if($qry_prods->num_rows > 0) {
                                                    while($row = $qry_prods->fetch_assoc()) {
                                            ?>
                                                        <tr>
                                                            <td><?=$row['product_id']?></td>
                                                            <td><?=$row['product_name']?></td>
                                                            <td>₱<?=number_format($original_price, 2)?></td>
                                                            <td><?=$quantity?></td>
                                                            <td class="text-right">₱ <?=number_format($quantity * $original_price,2)?></td>
                                                        </tr>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
				</div>
            </div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-danger btn-sm bg-gradient-danger rounded-0" type="button" id="print_data"><i class="fa fa-print"></i> Print</button>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=orders"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>


<script>
    var printBtn = $('#print_data');

    printBtn.on('click', function() {
        printPageArea('print_order_data');
    });

    function printPageArea(areaID) {
        var printContent = document.getElementById(areaID).innerHTML;
        var originalContent = document.body.innerHTML;
        var originalContentWithStyles = document.documentElement.innerHTML;

        window.document.write(printContent);
        // document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContentWithStyles;
        window.close();
    }

</script>