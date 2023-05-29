
<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `customers` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }else{
		echo '<script>alert("Customer is not valid."); location.replace("./?page=customers")</script>';
	}
}else{
	echo '<script>alert("Customer is Required."); location.replace("./?page=customers")</script>';
}
?>
<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b>Customer Details</b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0">
			<div class="card-body">
                <div class="container-fluid">
                    <dl>
						<dt class="text-muted">Customer Name</dt>
                        <dd class="pl-4"><?= isset($name) ? $name : "" ?></dd>

                        <dt class="text-muted">Contact Number</dt>
                        <dd class="pl-4"><?= isset($contact_number) ? $contact_number : "" ?></dd>

						<dt class="text-muted">Home Address</dt>
                        <dd class="pl-4"><?= isset($home_address) ? $home_address : "" ?></dd>

						<dt class="text-muted">Date Added</dt>
                        <dd class="pl-4"><?= isset($date_added) ? $date_added : "" ?></dd>

                      	<dt class="text-muted">Status</dt>
                        <dd class="pl-4">
                            <?php if($is_active == 1): ?>
                                <span class="badge badge-success px-3 rounded-pill">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger px-3 rounded-pill">Inactive</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>
			<div class="card-footer py-1 text-center">
				<a class="btn btn-primary btn-sm bg-gradient-teal rounded-0" href="./?page=customers/manage_customer&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=customers"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>
<script>
    $(function(){
		$('#delete_data').click(function(){
			_conf("Are you sure to delete this supplier permanently?","delete_supplier", ["<?= isset($id) ? $id :'' ?>"])
		})
    })
    function delete_customer($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Customers.php?f=delete_customer",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace("./?page=customers");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
