<?php
if(isset($_GET['return_id']) && $_GET['return_id'] > 0){
    $qry = $conn->query("SELECT ro.id
    , s2.customer_name
    , s2.customer_contact_number
    , s2.customer_home_address
    , s2.transaction_number
    , ro.product_id
    , ro.product_name
    , ro.product_quantity
    , ro.reason_for_return
    , ro.datetime_added
    , ro.datetime_returned
    , ro.session_id
    , ro.status
FROM `returned_orders` ro
JOIN sales_transactions_2 s2 ON ro.session_id = s2.session_id
WHERE id = '{$_GET['return_id']}'
GROUP BY ro.id
    , s2.customer_name
    , s2.customer_contact_number
    , s2.customer_home_address
    , s2.transaction_number
    , ro.product_id
    , ro.product_name
    , ro.product_quantity
    , ro.reason_for_return
    , ro.datetime_added
    , ro.datetime_returned
    , ro.session_id
    , ro.status");
    
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}

// $suppliers = $conn->query("SELECT * FROM `suppliers` WHERE is_archived = 0 AND is_active = 1 AND id = $supplier_id");
// if($suppliers->num_rows > 0){
//     foreach($suppliers->fetch_assoc() as $sk => $sv){
//         $$sk=$sv;
//     }
// }

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
	<h2><b>Print Product Return Details</b></h2>
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
									<label for="" class="control-label" style="margin-bottom: 14px;">Customer Name:</label>
									<br>
                                    <?=$customer_name?>
								</div>
							</div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-lg-right text-md-right text-sm-left ">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Transaction Date:</label>
									<br>
                                    <?=$datetime_added?>
								</div>
							</div>
                        </div>

                        <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Contact Number:</label>
									<br>
                                    <?=$customer_contact_number?>
								</div>
							</div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-lg-right text-md-right text-sm-left">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Transaction No.:</label>
									<br>
                                    <?=$transaction_number?>
								</div>
							</div>
                        </div>

                        <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="" class="control-label" style="margin-bottom: 14px;">Home Address:</label>
									<br>
                                    <?=$customer_home_address?>
								</div>
							</div>
                        </div>

                        <hr style="border-top: dotted 2px;"  />

                        <div class="row my-3">
                            <div class="col-lg-12 col-md-12-col-sm-12 text-primary">
                                <h3> Returned Item </h3>
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
                                                <th>Quantity</th>
                                                <th>Reason for Return</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?=$product_id?></td>
                                                <td><?=$product_name?></td>
                                                <td><?=$product_quantity?></td>
                                                <td><?=$reason_for_return?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
				</div>
            </div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-danger btn-sm bg-gradient-danger rounded-0" type="button" id="print_data"><i class="fa fa-print"></i> Print</button>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=returns"><i class="fa fa-angle-left"></i> Back to List</a>
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