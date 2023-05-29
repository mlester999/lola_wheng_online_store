
<br>
<div class="card card-outline rounded-0 card-navy ">
	<div class="card-header">
		<h3 class="card-title">List of Products</h3>
		<div class="card-tools">
			<a href="./?page=products/manage_product" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span> Add </a>
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
						<col width="15%">
						<col width="20%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Supplier</th>
                            <th>Image</th>
							<th class="text-center">Code</th>
							<th>Product</th>
							<th>Unit Size</th>
							<th>Quantity</th>
                            <th>Date Added</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
                            $i = 1;
                            $qry = $conn->query("SELECT p.id as product_id
                                                        , s.id as supplier_id
                                                        , s.name as supplier_name
                                                        , m.measurement_id as unit_id
                                                        , m.measurement_code
                                                        , m.measurement_name
                                                        , c.category_id as category_id
                                                        , c.category_name
                                                        , p.code
                                                        , p.name as product_name
                                                        , p.description
                                                        , p.unit_size
                                                        , p.quantity
                                                        , p.ceiling_point
                                                        , p.reordering_point
                                                        , p.critical_point
                                                        , p.expiration_date
                                                        , p.expiration_gap
                                                        , p.original_price
                                                        , p.retail_price
                                                        , p.date_added
                                                        , p.unique_key
                                                        , p.is_archived
                                                        , p.product_image
                                                FROM `products` p
                                                JOIN `suppliers` s ON p.supplier_id = s.id
                                                JOIN `measurements` m on p.unit_id = m.measurement_id
                                                JOIN `categories` c on p.category_id = c.category_id               
                                                WHERE quantity > 0
                            ");
                            if($qry->num_rows > 0) {
                                while($row = $qry->fetch_assoc()) {
						?>
                                    <tr>
                                        <td class="align-items-center text-center"><?php echo $i++; ?></td>
                                        <td class="align-items-center"><?php echo $row['supplier_name']; ?></td>
                                        <td class="align-items-center text-center"><img src="<?=base_url . $row['product_image']; ?>" width="55px" alt=""/></td>
                                        <td class="align-items-center"><?php echo $row['code']; ?></td>
                                        <td class="align-items-center">
                                            <a class="" href="./?page=products/view_product&id=<?php echo $row['product_id'] ?>">
                                                <?php echo $row['product_name']; ?>
                                            </a>
                                            <br>
                                            <small class="badge badge-warning"><i class="fas fa-th"></i> <?php echo $row['category_name']; ?></small>
                                        </td>
                                        <td class="align-items-center"><?php echo $row['unit_size'] . " " . $row['measurement_code']; ?></td>
                                        <td class="align-items-center"><?php echo $row['quantity']; ?></td>
                                        <td class="align-items-center truncate-1"><?php echo date('Y-m-d',strtotime($row['date_added'])); ?></td>
                                        <td class="align-items-center" align="center">
                                            <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item " href="./?page=products/view_product&id=<?php echo $row['product_id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="./?page=products/manage_product&id=<?php echo $row['product_id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
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


<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this product permanently?","delete_product",[$(this).attr('data-id')]);
            
        });
        $('.table').dataTable({
            columnDefs: [
                    { orderable: false, targets: [2,7] }
            ],
            order:[0,'asc'],
            "oLanguage": {
                "sEmptyTable": "No records available."
            }
        });
        $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle');
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

</script>