<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `products` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}

$suppliers = $conn->query("SELECT * FROM `suppliers` WHERE is_archived = 0 AND is_active = 1");
$categories = $conn->query("SELECT * FROM `categories` WHERE is_active = 1");
$measurements = $conn->query("SELECT * FROM `measurements` WHERE is_active = 1");
?>
<style>
	#cimg{
		max-width:100%;
		max-height:25em;
		object-fit:scale-down;
		object-position:center center;
	}

	select, input, textarea {
		pointer-events: none;
	}

	select:disabled, input:disabled, textarea:disabled {
		background-color: white !important;
  		color: black !important;
		opacity: 0.9 !important;
		font-size: 16px !important;
	}
</style>

<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b>Product Details</b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0">
			<div class="card-body">
				<div class="container-fluid">
						<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="supplier_id" class="control-label">Supplier</label>
									<select name="supplier_id" class="custom-select rounded-0" id="supplier_id" required="required" disabled>
										<option value="">Select Supplier</option>
										<?php foreach($suppliers->fetch_all(MYSQLI_ASSOC) as $sup): ?>
											<option value="<?=$sup['id'] ?>" <?= isset($id) && $supplier_id == $sup['id'] ? "selected" : "" ?>><?= $sup['name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="category_id" class="control-label">Category</label>
									<select name="category_id" class="custom-select rounded-0" id="category_id" required="required" disabled>
										<option value="">Select Category</option>
										<?php foreach($categories->fetch_all(MYSQLI_ASSOC) as $cat): ?>
											<option value="<?= $cat['category_id'] ?>" <?= isset($id) && $category_id == $cat['category_id'] ? "selected" : "" ?>><?= $cat['category_name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group ">
									<label for="unit_id" class="control-label">Unit</label>
									<select name="unit_id" class="custom-select rounded-0" id="unit_id" required="required" disabled>
										<option value="">Select Unit/Measure</option>
										<?php foreach($measurements->fetch_all(MYSQLI_ASSOC) as $mes): ?>
											<option value="<?= $mes['measurement_id'] ?>" <?= isset($id) && $unit_id == $mes['measurement_id'] ? "selected" : "" ?>><?= $mes['measurement_name'] . ' (' . $mes['measurement_code']. ')'?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="code" class="control-label">Barcode</label>
											<input type="text" name="code" id="code" class="form-control form-control-sm rounded-0" value="<?php echo isset($code) ? $code : ''; ?>"  required disabled />
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="name" class="control-label">Product Name</label>
											<input type="text" name="name" id="name" class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : ''; ?>"  required disabled/>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="description" class="control-label">Description</label>
											<textarea rows="7" name="description" id="description" class="form-control form-control-sm rounded-0" disabled><?php echo isset($description) ? $description : ''; ?></textarea>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="" class="control-label">Product Image</label>
											<div class="custom-file d-none">
												<input type="file" class="custom-file-input rounded-circle" id="product_image" name="product_image" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg" disabled>
												<label class="custom-file-label" for="product_image">Choose Image</label>
											</div>
										</div>
										<div class="form-group d-flex ">
											<!-- justify-content-center -->
											<img src="<?php echo validate_image(isset($product_image) ? $product_image :'') ?>" alt="" id="cproduct_image" class="img-fluid img-thumbnail">
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="unit_size" class="control-label">Unit Size</label>
											<input type="text" name="unit_size" id="unit_size" class="form-control form-control-sm rounded-0" value="<?php echo isset($unit_size) ? $unit_size : ''; ?>"  required disabled />
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="quantity" class="control-label">Quantity</label>
											<input type="number" name="quantity" id="quantity" class="form-control form-control-sm rounded-0" value="<?php echo isset($quantity) ? $quantity : ''; ?>"  required disabled />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="original_price" class="control-label">Original Price</label>
											<input type="number" name="original_price" id="original_price" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($original_price) ? $original_price : ''; ?>"  required disabled />
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="retail_price" class="control-label">Retail Price</label>
											<input type="number" name="retail_price" id="retail_price" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($retail_price) ? $retail_price : ''; ?>"  required disabled />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="expiration_date" class="control-label">Expiration Date</label>
											<input type="date" name="expiration_date" id="expiration_date" class="form-control form-control-sm rounded-0" value="<?php echo isset($expiration_date) ? $expiration_date : ''; ?>"  required disabled />
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="expiration_gap" class="control-label">Expiration Gap</label>
											<input type="number" name="expiration_gap" id="expiration_gap" class="form-control form-control-sm rounded-0" value="<?php echo isset($expiration_gap) ? $expiration_gap : ''; ?>"  required disabled />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="reordering_point" class="control-label">Reordering Point <span class="badge bg-warning"> % </span></label>
											<input type="number" name="reordering_point" id="reordering_point" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($reordering_point) ? $reordering_point : ''; ?>"  required disabled/>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="critical_point" class="control-label">Critical Point <span class="badge bg-warning"> % </span></label>
											<input type="number" name="critical_point" id="critical_point" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($critical_point) ? $critical_point : ''; ?>"  required disabled />
										</div>
									</div>
								</div>
							</div>
						</div>
				</div>
            </div>
			<div class="card-footer py-1 text-center">
				<a class="btn btn-primary btn-sm bg-gradient-teal rounded-0" href="./?page=products/manage_product&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
				<a class="btn btn-light btn-sm bg-gradient-light border rounded-0" href="./?page=products"><i class="fa fa-angle-left"></i> Back to List</a>
			</div>
		</div>
	</div>
</div>
<script>
    $(function(){
		$('#delete_data').click(function(){
			_conf("Are you sure to delete this product permanently?","delete_product", ["<?= isset($id) ? $id :'' ?>"])
		});

    });

    function delete_product($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_product",
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
					location.replace("./?page=products");
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>