<?php 
if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM `accounts` where id ='{$_GET['id']}' ");
    foreach($user->fetch_array() as $k =>$v){
        $meta[$k] = $v;
    }
}
?>

<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b><?= isset($meta['id']) ? "Update User Details" : "New User" ?></b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
		<div class="card card-outline rounded-0 card-navy">
			<div class="card-body">
				<div class="container-fluid">
					<div id="msg"></div>
					<form action="" id="manage-account">	
						<input type="hidden" name="id" value="<?=isset($meta['id']) ? $meta['id'] : '' ?>">
						<div class="form-group">
							<label for="full_name">Full Name</label>
							<input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo isset($meta['full_name']) ? $meta['full_name']: '' ?>" required>
						</div>
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
						</div>				
						<div class="form-group">
							<label for="password"><?= isset($meta['id']) ? "New" : "" ?> Password</label>
							<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
							<?php if(isset($meta['id'])): ?>
							<small><i>Leave this blank if you dont want to change the password.</i></small>
							<?php endif; ?>
						</div>
						<div class="form-group">
							<label for="type" class="control-label">Type</label>
							<select name="type" id="type" class="form-control form-control-sm rounded-0" required>
							<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Administrator</option>
							<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Manager</option>
							<option value="3" <?php echo isset($meta['type']) && $meta['type'] == 3 ? 'selected' : '' ?>>Cashier</option>
							<option value="4" <?php echo isset($meta['type']) && $meta['type'] == 4 ? 'selected' : '' ?>>Customer</option>
							</select>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Avatar</label>
							<div class="custom-file">
							<input type="file" class="custom-file-input rounded-circle" id="image_path" name="image_path" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
							<label class="custom-file-label" for="image_path">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center">
							<img src="<?php echo validate_image(isset($meta['image_path']) ? $meta['image_path'] :'') ?>" alt="" id="cimage_path" class="img-fluid img-thumbnail">
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
							<button class="btn btn-sm btn-primary bg-gradient-primary border-0 rounded-0 mr-3" form="manage-account"><?php echo isset($meta['id']) ? 'Update User Details':'Save User Details';?></button>
							<a href="./?page=accounts" class="btn btn-sm btn-default border rounded-0" form="manage-account"><i class="fa fa-angle-left"></i> Cancel</a>
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
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimage_path').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
			$('#cimage_path').attr('src', "<?php echo validate_image(isset($meta['image_path']) ? $meta['image_path'] :'') ?>");
		}
	}
	$('#manage-account').submit(function(e){
		e.preventDefault();
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1  ){
					// location.href='./?page=accounts';
					location.replace('./?page=accounts');
					// alert_toast("An error occured.",'error');
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_loader();
				}
			}
		})
	})

</script>

<?php
	if(isset($meta['id'])) {
?>
		<script>
			$('#username').attr('readonly', true);
		</script>
<?php
	}
?>