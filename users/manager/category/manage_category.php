<?php
if(isset($_GET['category_id']) && $_GET['category_id'] > 0){
    $qry = $conn->query("SELECT * from `categories` where category_id = '{$_GET['category_id']}' ");
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
	<h2><b><?= isset($category_id) ? "Update Category Details" : "New Category" ?></b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
		<div class="card rounded-0">
			<div class="card-body">

				<div class="container-fluid">
					<form action="" id="category-form">
						<input type="hidden" name ="category_id" value="<?php echo isset($category_id) ? $category_id : '' ?>">
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="category_name" class="control-label">Category Name</label>
								<input type="text" name="category_name" id="category_name" class="form-control form-control-sm rounded-0" value="<?php echo isset($category_name) ? $category_name : ''; ?>"  required/>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="description" class="control-label">Description</label>
								<textarea rows="3" name="description" id="description" class="form-control form-control-sm rounded-0"  ><?php echo isset($description) ? $description : ''; ?></textarea>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label for="is_active" class="control-label">Status</label>
								<select name="is_active" id="is_active" class="form-control form-control-sm rounded-0" required="required">
									<option value="1" <?= isset($is_active) && $is_active == 1 ? 'selected' : '' ?>>Active</option>
									<option value="0" <?= isset($is_active) && $is_active == 0 ? 'selected' : '' ?>>Inactive</option>
								</select>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-primary btn-sm bg-gradient-primary btn-flat border-0" form="category-form"><i class="fa fa-save"></i> Save</button>
				<a class="btn btn-light btn-sm bg-gradient-danger border btn-flat" href="./?page=category"><i class="fa fa-times"></i> Cancel</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#category-form').submit(function(e){
			e.preventDefault();
			
            var _this = $(this);

			$('.err-msg').remove();
			
			start_loader();
			
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_category",
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
						location.replace('./?page=category/view_category&category_id='+resp.category_id)
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