<?php
if(isset($_GET['transaction_id']) && $_GET['transaction_id'] > 0){
    $qry = $conn->query("SELECT * FROM `sales_transactions_2` WHERE transaction_id = '{$_GET['transaction_id']}' ");
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
	<h2><b>Print Receipt</b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0">
			<div class="card-body" id="print_order_data">
				<input type="hidden" name ="transaction_id" value="<?php echo isset($transaction_id) ? $transaction_id : '' ?>">

				<div class="wrapper d-flex justify-content-center p-5">
					<div class="container">
						<div class="row justify-content-center">
							<div class="col-lg-8 col-md-8 col-sm-12">
								<div class="container border border-dark px-5" id="print_receipt">
									<div class="row text-center">
										<div class="col-lg-12 col-md-12 col-sm-12">
                                        <h2 class="text-bold" style="text-align: center;"><?=$_SESSION['system_info']['name'] ?></h2>
										<p style="text-align: center;"><?=$_SESSION['system_info']['address'] ?></p>
                                        <p style="text-align: center;"><?=$_SESSION['system_info']['email'] ?></p>
                                        <p style="text-align: center;"><?=$_SESSION['system_info']['mobile'] ?></p>
										</div>
									</div>

                                    <hr style="border-top: dotted 2px;"  />

									<div class="row text-left mt-5">
                                    <div class="col-lg-12 col-md-9 col-sm-9">
                                        <div style="margin-bottom: 14px;">
                                            <span class="text-bold">Customer Name: </span> <br><?=$customer_name;?>
                                        </div>
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">Home Address: </span> <br><?=$customer_home_address;?>
                                        </div>
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">Contact Number: </span> <br><?=$customer_contact_number;?>
                                        </div>
                                    </div>  

                                    <div class="col-lg-12 col-md-3 col-sm-3">
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">DATE: </span> <br><?=date('Y-m-d H:i:s');?>
                                        </div>
                                        <div class="mt-2" style="margin-bottom: 14px;">
                                            <span class="text-bold">TRANSACTION #: </span> <br><?=$transaction_number;?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row text-center mt-5 text-bold">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left" style="margin-bottom: 6px;">
                                        ITEM/S:
                                    </div>
                                    <!-- <div class="col-lg-4 col-md-4 col-sm-4 text-center">
                                        QUANTITY
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                        AMOUNT
                                    </div> -->
                                </div>

                                <?php 
										$sql_sti = "SELECT  st2i.session_id, st2i.product_id, p.name, p.retail_price, st2i.quantity, st.transaction_id
													FROM sales_transactions_2_items st2i 
														JOIN products p ON st2i.product_id = p.id 
														JOIN sales_transactions_2 st ON st2i.session_id = st.session_id
													WHERE st.transaction_id = '$transaction_id'";

										$result_i = mysqli_query($conn, $sql_sti);
							
										if(mysqli_num_rows($result_i) > 0)
										{
											while($items = mysqli_fetch_array($result_i))
											{
									?>
												<div class="row text-center mt-1" style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                                                <div class="col-lg-6 col-md-6 col-sm-6 text-left">
                                                    <?=$items['name'];?> x <?=$items['quantity'];?> 
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 text-right">
                                                ₱<?=number_format($items['retail_price'] * $items['quantity'],2);?>
                                                </div>
                                            </div>      
									<?php
											}
										}
									?>

                                <hr style="border-top: dotted 2px;" />

                                <div class="row text-center mt-5" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        SUBTOTAL
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            echo number_format($total_amount, 2);
                                        ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        VAT (<?=$vat_pct;?>%)
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                        if($vat_pct > 0) {
                                            $vat_amount = $total_amount * ($vat_pct / 100);
                                        } else {
                                            $vat_amount = 0;
                                        }

                                        echo number_format($vat_amount,2);
                                    ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        DISCOUNT (<?=$discount_pct;?>%)
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            if($discount_pct > 0) {
                                                $discount_amount = $total_amount * ($discount_pct / 100);
                                            } else {
                                                $discount_amount = 0;
                                            }

                                            echo number_format($discount_amount,2);
                                        ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        DELIVERY FEE
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            if($delivery_fee > 0) {
                                                $delivery_fee_amount = $delivery_fee;
                                            } else {
                                                $delivery_fee_amount = 0;
                                            }

                                            echo number_format($delivery_fee_amount,2);
                                        ?>
                                    </div>
                                </div>
                                <div class="row text-center mt-5" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        TOTAL
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?php
                                            $total_with_vat = $overall_total;
                                            echo number_format($total_with_vat, 2);
                                        ?>
                                    </div>
                                </div>
        
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        CASH
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?=number_format($amount_rendered,2);?>
                                    </div>
                                </div>
                                <div class="row text-center mt-3" style="text-align: right;">
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-left">
                                        &nbsp;
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right" style="margin-bottom: 2px;">
                                        CHANGE
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                    ₱<?=number_format($changed,2);?>
                                    </div>
                                </div>
                                <br>
                                <br>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-danger btn-sm bg-gradient-danger rounded-0" type="button" id="print_data"><i class="fa fa-print"></i> Print</button>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=deliveries"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>


<script>
    var printBtn = $('#print_data');

    printBtn.on('click', function() {
        printPageArea('print_receipt');
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