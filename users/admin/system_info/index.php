<!-- <?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?> -->

<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
	img#cimg2{
		height: 50vh;
		width: 100%;
		object-fit: contain;
		/* border-radius: 100% 100%; */
	}
</style>
<br>
<div class="col-lg-12">
	<div class="card card-outline rounded-0 card-navy">
		<div class="card-header">
			<h5 class="card-title">System Information</h5>
			<!-- <div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-navy new_department" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div> -->
		</div>
		<div class="card-body">
			<form action="" id="system-frm">
				<div id="msg" class="form-group"></div>
				<div class="form-group">
					<label for="name" class="control-label">System Name</label>
					<input type="text" class="form-control form-control-sm" name="name" id="name" value="<?php echo $_settings->info('name') ?>">
				</div>
				<div class="form-group">
					<label for="short_name" class="control-label">System Short Name</label>
					<input type="text" class="form-control form-control-sm" name="short_name" id="short_name" value="<?php echo  $_settings->info('short_name') ?>">
				</div>
			<!-- <div class="form-group">
				<label for="" class="control-label">Welcome Content</label>
	             <textarea name="content[welcome]" id="" cols="30" rows="2" class="form-control summernote">< ?php echo  is_file(base_app.'welcome.html') ? file_get_contents(base_app.'welcome.html') : "" ?></textarea>
			</div>
			<div class="form-group">
				<label for="" class="control-label">About Us</label>
	             <textarea name="content[about]" id="" cols="30" rows="2" class="form-control summernote">< ?php echo  is_file(base_app.'about.html') ? file_get_contents(base_app.'about.html') : "" ?></textarea>
			</div> -->
			<div class="form-group">
				<label for="" class="control-label">System Logo</label>
				<div class="custom-file">
	              <input type="file" class="custom-file-input rounded-circle" id="customFile1" name="img" onchange="displayImg(this,$(this))">
	              <label class="custom-file-label" for="customFile1">Choose file</label>
	            </div>
			</div>
			<div class="form-group d-flex justify-content-center">
				<img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
			</div>

			<!-- <div class="form-group d-none">
				<label for="" class="control-label">Website Cover</label>
				<div class="custom-file">
	              <input type="file" class="custom-file-input rounded-circle" id="customFile2" name="cover" onchange="displayImg2(this,$(this))">
	              <label class="custom-file-label" for="customFile2">Choose file</label>
	            </div>
			</div>
			<div class="form-group d-flex justify-content-center d-none">
				<img src="<?php echo validate_image($_settings->info('cover')) ?>" alt="" id="cimg2" class="img-fluid img-thumbnail">
			</div>
			<div class="form-group d-none">
				<label for="" class="control-label">Banner Images</label>
				<div class="custom-file">
	              <input type="file" class="custom-file-input rounded-circle" id="customFile3" name="banners[]" multiple accept=".png,.jpg,.jpeg" onchange="displayImg3(this,$(this))">
	              <label class="custom-file-label" for="customFile3">Choose file</label>
	            </div>
				<small><i>Choose to upload new banner immages</i></small>
			</div>
				<?php 
				$upload_path = "uploads/banner";
				if(is_dir(base_app.$upload_path)): 
				$file= scandir(base_app.$upload_path);
					foreach($file as $img):
						if(in_array($img,array('.','..')))
							continue;
						
					
				?>
					<div class="d-flex w-100 align-items-center img-item d-none">
						<span><img src="<?php echo base_url.$upload_path.'/'.$img."?v=".(time()) ?>" width="150px" height="100px" style="object-fit:cover;" class="img-thumbnail" alt=""></span>
						<span class="ml-4"><button class="btn btn-sm btn-default text-danger rem_img" type="button" data-path="<?php echo base_app.$upload_path.'/'.$img ?>"><i class="fa fa-trash"></i></button></span>
					</div>
				<?php endforeach; ?>
				<?php endif; ?> -->

				<div class="form-group">
					<label for="address" class="control-label">Address</label>
					<input type="text" class="form-control form-control-sm" name="address" id="address" value="<?php echo  $_settings->info('address') ?>">
				</div>

				<div class="form-group">
					<label for="email" class="control-label">Email</label>
					<input type="text" class="form-control form-control-sm" name="email" id="email" value="<?php echo  $_settings->info('email') ?>">
				</div>

				<div class="form-group">
					<label for="mobile" class="control-label">Mobile</label>
					<input type="text" class="form-control form-control-sm" name="mobile" id="mobile" value="<?php echo  $_settings->info('mobile') ?>">
				</div>

				<div class="form-group">
					<label for="phone" class="control-label">Phone</label>
					<input type="text" class="form-control form-control-sm" name="phone" id="phone" value="<?php echo  $_settings->info('phone') ?>">
				</div>
		</div>

		<div class="card-header">
			<h5 class="card-title">Store Pricing Factors</h5>
		</div>
		<div class="card-body">
			<div class="form-group">
				<label for="vat" class="control-label">VAT (%)</label>
				<input type="text" class="form-control form-control-sm" name="vat" id="vat" value="<?php echo  $_settings->info('vat') ?>">
			</div>
			<div class="form-group">
				<label for="discount" class="control-label">Discount (%)</label>
				<input type="text" class="form-control form-control-sm" name="discount" id="discount" value="<?php echo  $_settings->info('discount') ?>">
			</div>
			<div class="form-group">
				<label for="delivery_fee" class="control-label">Shipping Fee (₱)</label>
				<input type="text" class="form-control form-control-sm" name="delivery_fee" id="delivery_fee" value="<?php echo  $_settings->info('delivery_fee') ?>">
			</div>
		</div>

		<div class="card-header">
			<h5 class="card-title">Economic Order Quantity</h5>
		</div>
		<div class="card-body">
			<div class="form-group">
				<label for="handling_cost" class="control-label">Handling Cost</label>
				<input type="text" class="form-control form-control-sm" name="handling_cost" id="handling_cost" value="<?php echo  $_settings->info('handling_cost') ?>">
			</div>
		</div>
		</form>
		<div class="card-footer">
			<div class="col-md-12">
				<div class="row">
					<button class="btn btn-sm btn-primary bg-gradient-teal border-0" form="system-frm">Update</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        	_this.siblings('.custom-file-label').html(input.files[0].name)
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}

	function displayImg2(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	_this.siblings('.custom-file-label').html(input.files[0].name)
	        	$('#cimg2').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}

	function displayImg3(input,_this) {
		var fnames = [];
		Object.keys(input.files).map(function(k){
			fnames.push(input.files[k].name)

		})
		_this.siblings('.custom-file-label').html(fnames.join(", "))
	}

	function delete_img($path){
        start_loader()
        
        $.ajax({
            url: _base_url_+'classes/Master.php?f=delete_img',
            data:{path:$path},
            method:'POST',
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("An error occured while deleting an Image","error");
                end_loader()
            },
            success:function(resp){
                $('.modal').modal('hide')
                if(typeof resp =='object' && resp.status == 'success'){
                    $('[data-path="'+$path+'"]').closest('.img-item').hide('slow',function(){
                        $('[data-path="'+$path+'"]').closest('.img-item').remove()
                    })
                    alert_toast("Image Successfully Deleted","success");
                }else{
                    console.log(resp)
                    alert_toast("An error occured while deleting an Image","error");
                }
                end_loader()
            }
        })
    }

	$(document).ready(function(){

		if(sessionStorage.getItem("showmsg")=='1'){
        alert_toast("The settings has been updated successfully",'success');
        sessionStorage.removeItem("showmsg");
        }

		$('.rem_img').click(function(){
            _conf("Are sure to delete this image permanently?",'delete_img',["'"+$(this).attr('data-path')+"'"])
        });

		// $('.summernote').summernote({
		// 	height: 200,
		// 	toolbar: [
		// 		[ 'style', [ 'style' ] ],
		// 		[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		// 		[ 'fontname', [ 'fontname' ] ],
		// 		[ 'fontsize', [ 'fontsize' ] ],
		// 		[ 'color', [ 'color' ] ],
		// 		[ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		// 		[ 'table', [ 'table' ] ],
		// 		[ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
		// 	]
		// });

		$('#system-frm').submit(function(e){
			e.preventDefault();
			
            var _this = $(this);

			$('.err-msg').remove();
			
			start_loader();
			
			$.ajax({
				url:_base_url_+"classes/SystemSettings.php?f=update_settings",
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
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						sessionStorage.setItem("showmsg", "1");
						location.replace('./?page=system_info');
						e.preventDefault();
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
			})
		})
	});
</script>

