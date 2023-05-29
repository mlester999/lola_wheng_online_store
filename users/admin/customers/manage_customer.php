<?php 
if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM `customers` where id ='{$_GET['id']}' ");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>

<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b><?= isset($meta['id']) ? "Update User Details" : "New Customer" ?></b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
		<div class="card card-outline rounded-0 card-navy">
			<div class="card-body">
				<div class="container-fluid">
					<div id="msg"></div>
					<form action="" id="manage-customer">	
						<input type="hidden" name="id" value="<?=isset($meta['id']) ? $meta['id'] : '' ?>">

						<?php 
							if(!isset($meta['id'])) {
						?>
							<input type="number" name="is_active" id="is_active" class="form-control d-none" value="1">
						<?php 
							}
						?>

						<div class="form-group">
							<label for="name">Full Name</label>
							<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
						</div>
						<div class="form-group">
							<label for="home_address">Home Address</label>
							<input type="text" name="home_address" id="home_address" class="form-control" value="<?php echo isset($meta['home_address']) ? $meta['home_address']: '' ?>" required  autocomplete="off">
						</div>				
						<div class="form-group">
							<label for="contact_number">Contact Number</label>
							<input type="text" name="contact_number" id="contact_number" class="form-control" value="<?php echo isset($meta['contact_number']) ? $meta['contact_number']: '' ?>" required  autocomplete="off">
						</div>	

						<?php 
							if(isset($meta['id'])) {
						?>
								<div class="row">
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="is_active" class="control-label">Status</label>
										<select name="is_active" id="is_active" class="form-control form-control-sm rounded-0" required="required">
											<option value="1" <?= isset($meta['id']) && $meta['is_active'] == 1 ? 'selected' : '' ?>>Active</option>
											<option value="0" <?= isset($meta['id']) && $meta['is_active'] == 0 ? 'selected' : '' ?>>Inactive</option>
										</select>
									</div>
								</div>
						<?php 
							}
						?>
					</form>
				</div>
			</div>
			<div class="card-footer">
					<div class="col-md-12">
						<div class="row">
							<button class="btn btn-sm btn-primary bg-gradient-primary border-0 rounded-0 mr-3" form="manage-customer"><?php echo isset($meta['id']) ? 'Update Customer Details':'Save Customer Details';?></button>
							<a href="./?page=customers" class="btn btn-sm btn-default border rounded-0" form="manage-customer"><i class="fa fa-angle-left"></i> Cancel</a>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>

<style>
	img#cimage_path{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	$('#manage-customer').submit(function(e){
		e.preventDefault();
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Customers.php?f=save',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					// location.href='./?page=customers';
					location.replace('./?page=customers');
					// alert_toast("An error occured.",'error');
				} else{
					$('#msg').html(`<div class="alert alert-danger">${resp}</div>`)
					end_loader();
				}
			}
		})
	})

</script>
