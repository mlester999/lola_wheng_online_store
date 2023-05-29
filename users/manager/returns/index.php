
<br>
<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-return-by-customer-tab" data-toggle="pill" href="#custom-tabs-return-by-customer" role="tab" aria-controls="custom-tabs-return-by-customer" aria-selected="true">Return by Customer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-return-to-supplier-tab" data-toggle="pill" href="#custom-tabs-return-to-supplier" role="tab" aria-controls="custom-tabs-return-to-supplier" aria-selected="false">
                    Return to Supplier
                </a>
            </li>
        </ul>
    </div>

	<div class="card-body">
    <div class="tab-content" id="custom-tabs-two-tabContent">
    <div class="tab-pane fade show active" id="custom-tabs-return-by-customer" role="tabpanel" aria-labelledby="custom-tabs-return-by-customer-tab">
            <div class="card-tools float-right mb-3">
                    <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span> Add </a>
            </div>
        <div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped table-bordered" id="list">
					<colgroup>
						<col width="5%">
                        <col width="10%">
						<col width="10%">
						<col width="5%">
						<col width="20%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Customer Name</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Reason for Return</th>
                            <th>Transaction Date</th>
                            <th>Date Returned</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
                            $i = 1;
                            $qry = $conn->query("SELECT ro.id
                                                        , ro.customer_name
                                                        , ro.product_id
                                                        , ro.product_name
                                                        , ro.product_quantity
                                                        , ro.session_id
                                                        , ro.reason_for_return
                                                        , ro.datetime_added
                                                        , ro.datetime_returned
                                                        , ro.status
                                                FROM `returned_orders` ro
                                                WHERE ro.status IN (0, 3, 4)
                                                GROUP BY ro.id
                                                        , ro.customer_name
                                                        , ro.product_id
                                                        , ro.product_name
                                                        , ro.product_quantity
                                                        , ro.session_id
                                                        , ro.reason_for_return
                                                        , ro.datetime_added
                                                        , ro.datetime_returned
                                                        , ro.status
                                                ORDER BY ro.datetime_added DESC
                                            ");
                            if($qry->num_rows > 0) {
                                while($row = $qry->fetch_assoc()) {
						?>
                                    <tr>
                                        <td class="align-items-center text-center"><?php echo $row['id']; ?></td>
                                        <td class="align-items-center"><?php echo $row['customer_name']; ?></td>
                                        <td class="align-items-center"><?php echo $row['product_name']; ?></td>
                                        <td class="align-items-center"><?php echo $row['product_quantity']; ?></td>
                                        <td class="align-items-center"><?php echo $row['reason_for_return']; ?></td>
                                        <td class="align-items-center text-center"><?php echo date('Y-m-d', strtotime($row['datetime_added'])); ?></td>
                                        <td class="align-items-center text-center">
                                        <?php if($row['datetime_returned'] == "" || $row['datetime_returned'] == "0000-00-00 00:00:00"): ?>
                                            <span class="badge badge-secondary px-3 rounded-pill">TBD</span>
                                        <?php else: ?>
                                            <?php echo  date('Y-m-d', strtotime($row['datetime_returned'])); ?></td>
                                        <?php endif; ?>
                                        </td>
                                        <td class="align-items-center truncate-1">
                                            <?php if($row['status'] == 0): ?>
                                                <span class="badge badge-warning px-3 rounded-pill">Pending (For Approval)</span>
                                            <?php elseif($row['status'] == 1): ?>  
                                                <span class="badge badge-info px-3 rounded-pill">In Progress (Approved)</span>
                                            <?php elseif($row['status'] == 2): ?>  
                                                <span class="badge badge-primary px-3 rounded-pill">For Delivery (Customer)</span>
                                            <?php elseif($row['status'] == 3): ?>  
                                                <span class="badge badge-success px-3 rounded-pill">Items Returned</span>
                                            <?php elseif($row['status'] == 4): ?>  
                                                <span class="badge badge-danger px-3 rounded-pill">Not Approved</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-items-center" align="center">
                                            <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item " href="./?page=returns/view_return&return_id=<?php echo $row['id']; ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                <?php if($row['status'] < 2): ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-product_id="<?php echo $row['product_id']; ?>" data-product_qty="<?php echo $row['product_quantity']; ?>" data-session_id="<?php echo $row['session_id']; ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
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
			</div>
		</div>
    </div>

    <div class="tab-pane fade" id="custom-tabs-return-to-supplier" role="tabpanel" aria-labelledby="custom-tabs-return-to-supplier-tab">
        <div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped table-bordered" id="supplierList">
					<colgroup>
						<col width="5%">
                        <col width="10%">
						<col width="15%">
						<col width="10%">
						<col width="5%">
						<col width="15%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Supplier Name</th>
							<th>Customer Name</th>
							<th>Product Name</th>
							<th>Quantity</th>
                            <th>Transaction Date</th>
                            <th>Date Returned</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
                            $i = 1;
                            $qry = $conn->query("SELECT ro.id
                                                        , s.name as supplier_name
                                                        , s.contact_number as supplier_contact_number
                                                        , s.email_address as supplier_email_address
                                                        , ro.customer_name
                                                        , ro.product_id
                                                        , ro.product_name
                                                        , ro.product_quantity
                                                        , ro.session_id
                                                        , ro.reason_for_return
                                                        , ro.datetime_added
                                                        , ro.datetime_returned
                                                        , ro.status
                                                FROM `returned_orders` ro
                                                JOIN products p ON ro.product_id = p.id
                                                JOIN suppliers s ON p.supplier_id = s.id
                                                WHERE ro.status IN (1, 2)
                                                GROUP BY ro.id
                                                        , supplier_name
                                                        , supplier_contact_number
                                                        , supplier_email_address
                                                        , ro.customer_name
                                                        , ro.product_id
                                                        , ro.product_name
                                                        , ro.product_quantity
                                                        , ro.session_id
                                                        , ro.reason_for_return
                                                        , ro.datetime_added
                                                        , ro.datetime_returned
                                                        , ro.status
                                                ORDER BY ro.datetime_added DESC
                                            ");
                            if($qry->num_rows > 0) {
                                while($row = $qry->fetch_assoc()) {
						?>
                                    <tr>
                                        <td class="align-items-center text-center"><?php echo $row['id']; ?></td>
                                        <td class="align-items-center"><?php echo $row['supplier_name']; ?></td>
                                        <td class="align-items-center"><?php echo $row['customer_name']; ?></td>
                                        <td class="align-items-center"><?php echo $row['product_name']; ?></td>
                                        <td class="align-items-center"><?php echo $row['product_quantity']; ?></td>
                                        <td class="align-items-center text-center"><?php echo date('Y-m-d', strtotime($row['datetime_added'])); ?></td>
                                        <td class="align-items-center text-center">
                                        <?php if($row['datetime_returned'] == "" || $row['datetime_returned'] == "0000-00-00 00:00:00"): ?>
                                            <span class="badge badge-secondary px-3 rounded-pill">TBD</span>
                                        <?php else: ?>
                                            <?php echo  date('Y-m-d', strtotime($row['datetime_returned'])); ?></td>
                                        <?php endif; ?>
                                        </td>
                                        <td class="align-items-center truncate-1">
                                            <?php if($row['status'] == 0): ?>
                                                <span class="badge badge-warning px-3 rounded-pill">Pending (For Approval)</span>
                                            <?php elseif($row['status'] == 1): ?>  
                                                <span class="badge badge-info px-3 rounded-pill">In Progress (Approved)</span>
                                            <?php elseif($row['status'] == 2): ?>  
                                                <span class="badge badge-primary px-3 rounded-pill">For Delivery (Customer)</span>
                                            <?php elseif($row['status'] == 3): ?>  
                                                <span class="badge badge-success px-3 rounded-pill">Items Returned</span>
                                            <?php elseif($row['status'] == 4): ?>  
                                                <span class="badge badge-danger px-3 rounded-pill">Not Approved</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-items-center" align="center">
                                            <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <?php if($row['status'] == 1): ?>
                                                <a class="dropdown-item " href="./?page=returns/view_return_to_supplier&return_id=<?php echo $row['id']; ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                <?php else: ?>
                                                <a class="dropdown-item " href="./?page=returns/view_return&return_id=<?php echo $row['id']; ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                <?php endif; ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item edit_data2" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-product_id="<?php echo $row['product_id']; ?>" data-product_qty="<?php echo $row['product_quantity']; ?>" data-session_id="<?php echo $row['session_id']; ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
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
			</div>
		</div>
    </div>
    </div>
	</div> 
</div>



<!-- Modal - Update Selected List Sale  -->
<div class="modal fade" id="UpdateSelectedRowModal" tabindex="-1" role="dialog" aria-labelledby="UpdateSelectedRowModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="UpdateSelectedRowModalLabel">Update Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="" id="frm-modal-update-selected">
            <div class="modal-body">
                <input type="text" name="rows_selected" id="rows_selected" value="">
                <input type="text" name="product_id" id="product_id" value="">
                <input type="text" name="product_qty" id="product_qty" value="">
                <input type="text" name="session_id" id="session_id" value="">

                <label for="status" class="control-label">Status</label>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group d-flex">
                                <div class="custom-control custom-radio pr-2">
                                    <input class="custom-control-input custom-control-input-primary " type="radio" id="customRadio1" name="status" value="1" >
                                    <label for="customRadio1" class="custom-control-label">In Progress (Approved)</label>
                                </div>
                            </div>
                        </div>
                            <div class="col-sm-6">
                                <div class="form-group d-flex">
                                    <div class="custom-control custom-radio pr-2">
                                        <input class="custom-control-input custom-control-input-danger " type="radio" id="customRadio4" name="status" value="4" >
                                        <label for="customRadio4" class="custom-control-label">Not Approved</label>
                                    </div>
                                </div>
                            </div>
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

<!-- Modal - Before Add Returned Product -->
<div class="modal fade" id="beforeAddReturnedProductModal" tabindex="-1" role="dialog" aria-labelledby="beforeAddReturnedProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <?php 
                        $customers = $conn->query("SELECT a.* FROM ( SELECT `customer_name`, `customer_contact_number`, `customer_home_address` , `for_return` , ROW_NUMBER()OVER(PARTITION BY `customer_name`) AS customer_id
													FROM `sales_transactions_2`) a WHERE a.customer_id = 1 AND `for_return` = 0 ORDER BY customer_name ASC
													");
                    	?>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="beforeAddReturnedProductModalLabel">Add Returned Product</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <div class="container-fluid">
                            <div class="table-responsive" style="display: flex; justify-content: center; align-items: center;">
                            <div>
                                <h5 class="text-center text-bold mb-4">Please select the customer name and its transaction number</h5>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <label for="customer_name" class="control-label">Customer Name</label>
                                    <select class="custom-select checkout-input" name="customer_name" id="customer_name" required="required">
                                        <option value="" hidden>Select Name</option>
                                        <?php foreach($customers->fetch_all(MYSQLI_ASSOC) as $cust): ?>
                                        <option id="<?= $cust['customer_name'] ?>" data-customer_name="<?= $cust['customer_name'] ?>" value="<?= $cust['customer_name'] ?>" ><?= $cust['customer_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <span id="returnCustomerNameError" class="text-center text-danger position-absolute"></span>
								
							    </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-4 mb-5">
								<label for="transaction_number" class="control-label">Transaction Number</label>
								<select class="custom-select checkout-input js-transaction-number" name="transaction_number" id="transaction_number" required="required" disabled>
									<option value="" hidden>Select Transaction Number</option>
								</select>

                                <span id="returnTransactionNumberError" class="text-center text-danger position-absolute"></span>
								
							</div>

                                <div class="mt-4" style="display: flex; justify-content: center; align-items: center;">
                                    <button id="afterAddReturnedProducts" class="btn btn-primary px-4">Enter</button>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!-- Modal - Update Selected Row Suppliers List Sale  -->
<div class="modal fade" id="UpdateSelectedRowSuppliersModal" tabindex="-1" role="dialog" aria-labelledby="UpdateSelectedRowSuppliersLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="UpdateSelectedRowSuppliersModalLabel">Update Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="" id="frm-modal-supplier-update-selected">
            <div class="modal-body">
                <input type="text" name="rows_selected" id="rows_selected" value="">
                <input type="text" name="product_id" id="product_id" value="">
                <input type="text" name="product_qty" id="product_qty" value="">
                <input type="text" name="session_id" id="session_id" value="">

                <label for="status" class="control-label">Status</label>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group d-flex">
                                <div class="custom-control custom-radio pr-2">
                                    <input class="custom-control-input custom-control-input-primary " type="radio" id="customRadio2" name="status" value="2" >
                                    <label for="customRadio2" class="custom-control-label">For Delivery (Customer)</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                                <div class="form-group d-flex">
                                    <div class="custom-control custom-radio pr-2">
                                        <input class="custom-control-input custom-control-input-danger " type="radio" id="customRadio3" name="status" value="3" >
                                        <label for="customRadio3" class="custom-control-label">Items Returned</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group d-flex">
                                    <div class="custom-control custom-radio pr-2">
                                        <input class="custom-control-input custom-control-input-danger " type="radio" id="customRadio5" name="status" value="4" >
                                        <label for="customRadio5" class="custom-control-label">Not Approved</label>
                                    </div>
                                </div>
                            </div>
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


<script>
    $(document).ready(function(){

        if(sessionStorage.getItem("showmsg")=='1'){
        alert_toast("The returned product has been added successfully",'success');
        sessionStorage.removeItem("showmsg");
        }


        $('.delete_data').click(function(){
            _conf("Are you sure to delete this product permanently?","delete_product",[$(this).attr('data-id')]);
            
        });
        $('.table').dataTable({
            columnDefs: [
                    { orderable: false, targets: [2,7] }
            ],
            order:[0,'desc'],
            "oLanguage": {
                "sEmptyTable": "No records available."
            }
        });
        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');

        $('#customer_name').select2({
            placeholder: 'Select Customer Name',
            dropdownParent: $('#beforeAddReturnedProductModal'),
			theme: 'bootstrap4',
			// placeholder: 'Select Category',
		});

        $('#transaction_number').select2({
            dropdownParent: $('#beforeAddReturnedProductModal'),
			theme: 'bootstrap4',
			// placeholder: 'Select Category',
		});

        $('#customer_name').change(function() {
            var customerName = $('select#customer_name option').filter(":selected").val();

				if ($(this).val() != '') {
					$('#transaction_number').prop('disabled', false);
				} else {
					$('#transaction_number').prop('disabled', true);
				}

                $.ajax({
                url:"returns/search_transaction_number.php",
                method:"POST",
                data:{customer_name: customerName},
                success:function(data)
                {
                    var withLabelData = '<option value="" hidden>Select Transaction Number</option>' + ' ' + data;
                    $('.js-transaction-number').html(withLabelData);
                }
            });
        });

    $('#create_new').on('click', function() {
        $('#beforeAddReturnedProductModal').modal('show');

    });

    $('#afterAddReturnedProducts').on('click', function() {
        var customerName = $('select#customer_name option').filter(":selected").val();
        var transactionNumber = $('select#transaction_number option').filter(":selected").val();

        if(!customerName) {
            $('#returnCustomerNameError').text('Please select a customer name');
        } else {
            $('#returnCustomerNameError').text('');
        }

        if(!transactionNumber) {
            $('#returnTransactionNumberError').text('Please select a transaction number');
        } else {
            $('#returnTransactionNumberError').text('');
        }

        if(customerName && transactionNumber) {
            $('#beforeAddReturnedProductModal').modal('hide');

            $('select#customer_name option').filter(":selected").remove();
            $('select#transaction_number option').filter(":selected").remove();
            $('#transaction_number').prop('disabled', true);

            location.replace('./?page=returns/manage_return&customer_name=' + customerName + '&transaction_number=' + transactionNumber);
        }
    });
    });

    $('#list tbody tr td a.edit_data').on('click', function() {
        $('#UpdateSelectedRowModal').modal({
            show: true,
        });

        $('#UpdateSelectedRowModal input#rows_selected').val($(this).data('id'));
        $('#UpdateSelectedRowModal input#product_id').val($(this).data('product_id'));
        $('#UpdateSelectedRowModal input#product_qty').val($(this).data('product_qty'));
        $('#UpdateSelectedRowModal input#session_id').val($(this).data('session_id'));
        $('#UpdateSelectedRowModal input#rows_selected').hide();
        $('#UpdateSelectedRowModal input#product_id').hide();
        $('#UpdateSelectedRowModal input#product_qty').hide();
        $('#UpdateSelectedRowModal input#session_id').hide();

    });

    $('#supplierList tbody tr td a.edit_data2').on('click', function() {
        $('#UpdateSelectedRowSuppliersModal').modal({
            show: true,
        });

        $('#UpdateSelectedRowSuppliersModal input#rows_selected').val($(this).data('id'));
        $('#UpdateSelectedRowSuppliersModal input#product_id').val($(this).data('product_id'));
        $('#UpdateSelectedRowSuppliersModal input#product_qty').val($(this).data('product_qty'));
        $('#UpdateSelectedRowSuppliersModal input#session_id').val($(this).data('session_id'));
        $('#UpdateSelectedRowSuppliersModal input#rows_selected').hide();
        $('#UpdateSelectedRowSuppliersModal input#product_id').hide();
        $('#UpdateSelectedRowSuppliersModal input#product_qty').hide();
        $('#UpdateSelectedRowSuppliersModal input#session_id').hide();

    });

    function delete_product($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_product",
            method:"POST",
            data:{id: $id},
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

        // var formData = new FormData($(this)[0]); // create a new FormData object

        // for (var pair of formData.entries()) {
        // console.log(pair[0]+ ', ' + pair[1]); // log each key/value pair to the console
        // }

        $.ajax({
            url:_base_url_+"classes/Deliveries.php?f=update_return_selected",
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
                    location.replace('./?page=returns');
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

    $('#UpdateSelectedRowSuppliersModal #frm-modal-supplier-update-selected').submit(function(e){
        e.preventDefault();
        
        var _this = $(this);

        $('.err-msg').remove();

        // var formData = new FormData($(this)[0]); // create a new FormData object

        // for (var pair of formData.entries()) {
        // console.log(pair[0]+ ', ' + pair[1]); // log each key/value pair to the console
        // }

        $.ajax({
            url:_base_url_+"classes/Deliveries.php?f=update_return_selected",
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
                    location.replace('./?page=returns');
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