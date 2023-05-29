
<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `suppliers` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
	#cimg{
		max-width:100%;
		max-height:25em;
		object-fit:scale-down;
		object-position:center center;
	}
</style>
<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b><?= isset($id) ? "Update Supplier Details" : "New Supplier" ?></b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
		<div class="card rounded-0">
			<div class="card-body">

				<div class="container-fluid">
					<form action="" id="supplier-form">
						<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="name" class="control-label">Supplier</label>
								<input type="text" name="name" id="name" class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : ''; ?>"  required/>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="contact_name" class="control-label">Contact Name</label>
								<input type="text" name="contact_name" id="contact_name" class="form-control form-control-sm rounded-0" value="<?php echo isset($contact_name) ? $contact_name : ''; ?>"  required/>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="contact_number" class="control-label">Contact Number</label>
								<input type="text" name="contact_number" id="contact_number" class="form-control form-control-sm rounded-0" value="<?php echo isset($contact_number) ? $contact_number : ''; ?>"  required/>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="email_address" class="control-label">Email Address</label>
								<input type="email" name="email_address" id="email_address" class="form-control form-control-sm rounded-0" value="<?php echo isset($email_address) ? $email_address : ''; ?>"  required/>
							</div>
						</div>

						<?php 
							if(isset($id)) {
						?>
								<div class="row">
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label for="is_active" class="control-label">Status</label>
										<select name="is_active" id="is_active" class="form-control form-control-sm rounded-0" required="required">
											<option value="1" <?= isset($is_active) && $is_active == 1 ? 'selected' : '' ?>>Active</option>
											<option value="0" <?= isset($is_active) && $is_active == 0 ? 'selected' : '' ?>>Inactive</option>
										</select>
									</div>
								</div>
						<?php 
							}
						?>
					</form>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-primary btn-sm bg-gradient-primary btn-flat border-0" form="supplier-form"><i class="fa fa-save"></i> Save</button>
				<a class="btn btn-light btn-sm bg-gradient-danger border btn-flat" href="./?page=suppliers"><i class="fa fa-times"></i> Cancel</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#supplier-form').submit(function(e){
			e.preventDefault();
			
            var _this = $(this);

			$('.err-msg').remove();
			
			start_loader();
			
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_supplier",
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
						location.replace('./?page=suppliers/view_supplier&id='+resp.id)
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

	})
</script>