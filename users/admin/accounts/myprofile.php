<?php 
	$user = $conn->query("SELECT * FROM `accounts` where id ='".$_settings->userdata('id')."'");
	foreach($user->fetch_array() as $k =>$v){
		$meta[$k] = $v;
	}
?>

<br>
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
							<label for="" class="control-label">Avatar</label>
							<div class="custom-file">
							<input type="file" class="custom-file-input rounded-circle" id="image_path" name="image_path" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
							<label class="custom-file-label" for="image_path">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center">
							<img src="<?php echo validate_image(isset($meta['image_path']) ? $meta['image_path'] :'') ?>" alt="" id="cimage_path" class="img-fluid img-thumbnail">
						</div>

					</form>
				</div>
			</div>
			<div class="card-footer">
					<div class="col-md-12">
						<div class="row">
							<button class="btn btn-sm btn-primary bg-gradient-primary border-0 rounded-0 mr-3" form="manage-account">Update Profile</button>
							<a href="./?page=accounts" class="btn btn-sm btn-default border rounded-0" form="manage-account"><i class="fa fa-angle-left"></i> Cancel</a>
						</div>
					</div>
				</div>
		</div>

		<br><br>


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
					location.href='./?page=myprofile';
					// location.reload();
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_loader();
				}
			}
		})
	});

	$('#username').attr('readonly', true);
</script>

