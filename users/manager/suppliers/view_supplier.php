
<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `suppliers` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }else{
		echo '<script>alert("Supplier is not valid."); location.replace("./?page=suppliers")</script>';
	}
}else{
	echo '<script>alert("Supplier is Required."); location.replace("./?page=suppliers")</script>';
}
?>
<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b>Supplier Details</b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0">
			<div class="card-body">
                <div class="container-fluid">
                    <dl>
                        <dt class="text-muted">Supplier</dt>
                        <dd class="pl-4"><?= isset($name) ? $name : "" ?></dd>

						<dt class="text-muted">Contact Name</dt>
                        <dd class="pl-4"><?= isset($contact_name) ? $contact_name : "" ?></dd>

                        <dt class="text-muted">Contact Number</dt>
                        <dd class="pl-4"><?= isset($contact_number) ? $contact_number : "" ?></dd>

						<dt class="text-muted">Email Address</dt>
                        <dd class="pl-4"><?= isset($email_address) ? $email_address : "" ?></dd>

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
				<a class="btn btn-primary btn-sm bg-gradient-teal rounded-0" href="./?page=suppliers/manage_supplier&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=suppliers"><i class="fa fa-angle-left"></i> Back to List</a>
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
    function delete_supplier($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_supplier",
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
					location.replace("./?page=suppliers");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
