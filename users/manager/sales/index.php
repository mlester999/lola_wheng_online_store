
<br>
<div class="card card-outline rounded-0 card-navy ">
	<div class="card-header">
		<h3 class="card-title">List of Sales</h3>
		<div class="card-tools">
			<a href="./?page=pos" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span> Add </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped table-bordered" id="list">
					<colgroup>
						<col width="5%">
						<col width="10%">
                        <col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="20%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Transaction Number</th>
                            <th>Transaction Date</th>
							<th>Overall Total</th>
							<th>Total Orders</th>
							<th>Customer Name</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
                            $i = 1;
                            $qry = $conn->query("SELECT s.transaction_id
                                                        , s.customer_id
                                                        , s.customer_name
                                                        , s.transaction_number
                                                        , s.session_id
                                                        , s.total_amount
                                                        , s.amount_rendered
                                                        , s.changed
                                                        , s.discount_pct
                                                        , s.overall_total
                                                        , s.datetime_checkedout
                                                        , SUM(s2.quantity) AS total_orders
                                                        , s.status
                                                FROM `sales_transactions_2` s
                                                    JOIN sales_transactions_2_items s2 ON s.session_id = s2.session_id
                                                WHERE s.status IN (0,1,2,5)
                                                GROUP BY s.transaction_id
                                                        , s.customer_id
                                                        , s.customer_name
                                                        , s.transaction_number
                                                        , s.session_id
                                                        , s.total_amount
                                                        , s.amount_rendered
                                                        , s.changed
                                                        , s.discount_pct
                                                        , s.overall_total
                                                        , s.datetime_checkedout
                                                        , s.status
                                                ORDER BY s.datetime_checkedout DESC
                                            ");
                            if($qry->num_rows > 0) {
                                while($row = $qry->fetch_assoc()) {
						?>
                                    <tr>
                                        <td class="align-items-center text-center"><?php echo $row['transaction_id']; ?></td>
                                        <td class="align-items-center"><?php echo $row['transaction_number']; ?></td>
                                        <td class="align-items-center text-center"><?php echo date('Y-m-d', strtotime($row['datetime_checkedout'])); ?></td>
                                        <td class="align-items-center">₱<?php echo number_format($row['overall_total'], 2); ?></td>
                                        <td class="align-items-center"><?php echo $row['total_orders'];?>  <?php echo $row['total_orders'] > 0 ? 'items':'item'; ?></td>
                                        <td class="align-items-center"><?php echo $row['customer_name']; ?></td>
                                        <td class="align-items-center truncate-1">
                                            <?php if($row['status'] == 0): ?>
                                                <span class="badge badge-primary px-3 rounded-pill">Not Delivered</span>
                                            <?php elseif($row['status'] == 1): ?>  
                                                <span class="badge badge-success px-3 rounded-pill">Delivered</span>
                                            <?php elseif($row['status'] == 2): ?>  
                                                <span class="badge badge-danger px-3 rounded-pill">Cancelled</span>
                                            <?php elseif($row['status'] == 3): ?>  
                                                <span class="badge badge-info px-3 rounded-pill">For Delivery</span>
                                            <?php elseif($row['status'] == 4): ?>  
                                                <span class="badge badge-dark px-3 rounded-pill">For Pick-up</span>
                                            <?php elseif($row['status'] == 5): ?>  
                                            <span class="badge badge-success px-3 rounded-pill">Picked Up</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-items-center" align="center">
                                            <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item " href="./?page=sales/view_sale&transaction_id=<?php echo $row['transaction_id']; ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                <?php if($row['status'] != 1 && $row['status'] < 4): ?>  
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item edit_data" href="javascript:void(0)" data-transaction_id="<?php echo $row['transaction_id']; ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
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
</div>



<!-- Modal - Update Selected List Sale  -->
<div class="modal fade" id="UpdateSelectedRowModal" tabindex="-1" role="dialog" aria-labelledby="UpdateSelectedRowModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="UpdateSelectedRowModalLabel">Update Selected Sale</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="" id="frm-modal-update-selected">
            <div class="modal-body">
                <input type="text" name="rows_selected" id="rows_selected" value="">

                <label for="status" class="control-label">Status</label>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group d-flex">
                                <div class="custom-control custom-radio pr-2">
                                    <input class="custom-control-input custom-control-input-primary" type="radio" id="customRadio0" name="status" value="0" >
                                    <label for="customRadio0" class="custom-control-label">Not Delivered</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group d-flex">
                                <div class="custom-control custom-radio pr-2">
                                    <input class="custom-control-input custom-control-input-primary " type="radio" id="customRadio1" name="status" value="1" >
                                    <label for="customRadio1" class="custom-control-label">Delivered</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group d-flex">
                                <div class="custom-control custom-radio pr-2">
                                    <input class="custom-control-input custom-control-input-danger " type="radio" id="customRadio2" name="status" value="2" >
                                    <label for="customRadio2" class="custom-control-label">Cancelled</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group d-flex">
                                <div class="custom-control custom-radio pr-2">
                                    <input class="custom-control-input custom-control-input-warning " type="radio" id="customRadio3" name="status" value="3" >
                                    <label for="customRadio3" class="custom-control-label">For Delivery</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group d-flex">
                                <div class="custom-control custom-radio pr-2">
                                    <input class="custom-control-input custom-control-input-dark " type="radio" id="customRadio4" name="status" value="4" >
                                    <label for="customRadio4" class="custom-control-label">For Pick-up</label>
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
    });

    $('#list tbody tr td a.edit_data').on('click', function() {
        $('#UpdateSelectedRowModal').modal({
            show: true,
        });

        $('#UpdateSelectedRowModal input#rows_selected').val($(this).data('transaction_id'));
        $('#UpdateSelectedRowModal input#rows_selected').hide();

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

        console.log($(this)[0]);

        $.ajax({
            url:_base_url_+"classes/Sales.php?f=update_sale_selected",
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
                    location.replace('./?page=sales');
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