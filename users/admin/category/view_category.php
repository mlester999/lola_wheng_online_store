<?php
if(isset($_GET['category_id']) && $_GET['category_id'] > 0){
    $qry = $conn->query("SELECT * from `categories` where category_id = '{$_GET['category_id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }else{
		echo '<script>alert("Category is not valid."); location.replace("./?page=category")</script>';
	}
}else{
	echo '<script>alert("Category is Required."); location.replace("./?page=category")</script>';
}
?>
<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b>Category Details</b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0">
			<div class="card-body">
                <div class="container-fluid">
                    <dl>
                        <dt class="text-muted">Category Name</dt>
                        <dd class="pl-4"><?= isset($category_name) ? $category_name : "" ?></dd>
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
				<a class="btn btn-primary btn-sm bg-gradient-teal rounded-0" href="./?page=category/manage_category&category_id=<?= isset($category_id) ? $category_id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=category"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>
<script>
    $(function(){
		$('#delete_data').click(function(){
			_conf("Are you sure to delete this category permanently?","delete_category", ["<?= isset($category_id) ? $category_id :'' ?>"])
		})
    })
    function delete_category($category_id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_category",
			method:"POST",
			data:{category_id: $category_id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace("./?page=category");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
