
<?php
if(isset($_GET['measurement_id']) && $_GET['measurement_id'] > 0){
    $qry = $conn->query("SELECT * from `measurements` where measurement_id = '{$_GET['measurement_id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }else{
		echo '<script>alert("Unit of Measurement is not valid."); location.replace("./?page=measurements")</script>';
	}
}else{
	echo '<script>alert("Unit of Measurements is Required."); location.replace("./?page=measurements")</script>';
}
?>
<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b>Unit of Measurement Details</b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0">
			<div class="card-body">
                <div class="container-fluid">
                    <dl>
                        <dt class="text-muted">Unit of Measurement</dt>
                        <dd class="pl-4"><?= isset($measurement_name) ? $measurement_name : "" ?></dd>

						<dt class="text-muted">Code</dt>
                        <dd class="pl-4"><?= isset($measurement_code) ? $measurement_code : "" ?></dd>

                        <dt class="text-muted">Description</dt>
                        <dd class="pl-4"><?= isset($description) ? $description : "" ?></dd>

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
				<a class="btn btn-primary btn-sm bg-gradient-teal rounded-0" href="./?page=measurements/manage_measurement&measurement_id=<?= isset($measurement_id) ? $measurement_id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=measurements"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>
<script>
    $(function(){
		$('#delete_data').click(function(){
			_conf("Are you sure to delete this unit of measurement permanently?","delete_measurement", ["<?= isset($measurement_id) ? $measurement_id :'' ?>"])
		})
    })
    function delete_measurement($measurement_id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_measurement",
			method:"POST",
			data:{measurement_id: $measurement_id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace("./?page=measurements");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
