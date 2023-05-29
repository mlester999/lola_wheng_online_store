
<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `products` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }

			$barcode = $code;
			$total_demand = 0;
			$order_cost_per_item = number_format($_SESSION['system_info']['vat'], 2) / 100 * $original_price + number_format($_SESSION['system_info']['delivery_fee']);
			$holding_cost = $_SESSION['system_info']['handling_cost'];
			
			$queryEoq = "SELECT SUM(`quantity`) FROM `sales_transactions_2_items`
			WHERE product_barcode = '$barcode'
			AND datetime_added >= (
				SELECT MIN(datetime_added) FROM `sales_transactions_2_items`
				WHERE product_barcode = '$barcode'
				AND datetime_added <= DATE_SUB(NOW(), INTERVAL 1 YEAR)
			)
			AND datetime_added BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW()";

		$resultEoq = mysqli_query($conn, $queryEoq);

		$latestCeilingPointQty = 0;

		if(mysqli_num_rows($resultEoq) > 0)
		{

			$rows = $conn->query($queryEoq);

			if($rows)
			{
				foreach ($rows as $key => $row) {
					$total_demand = round($row['SUM(`quantity`)']);
				}
			}

			$eoq = round(sqrt((2 * $total_demand * number_format($order_cost_per_item)) / $holding_cost),0);
			
			if($eoq > 0) {
				$latestCeilingPointQty = $eoq;

				$latestReorderingPoint = round($latestCeilingPointQty * $reordering_point / 100);
				$latestCriticalPoint = round($latestCeilingPointQty * $critical_point / 100) ;

				$conn->query("UPDATE `products` SET `ceiling_q` = '$latestCeilingPointQty', `reordering_q` = '$latestReorderingPoint', `critical_q` = '$latestCriticalPoint' WHERE id = '{$id}'");
			} else {
				$latestCeilingPointQty = $ceiling_q;

				$latestReorderingPoint = $reordering_q;
				$latestCriticalPoint = $critical_q;
			}

		} else {
			$latestCeilingPointQty = $ceiling_q;
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
</style>
<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b><?= isset($id) ? "Update Product Details" : "New Product Entry" ?></b></h2>
</div>
<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 ">
		<div class="card rounded-0">
			<div class="card-body">

				<div class="container-fluid">
					<form action="" id="product-form">
						<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="supplier_id" class="control-label">Supplier</label>
									<select name="supplier_id" class="custom-select rounded-0" id="supplier_id" required="required">
										<option value="" hidden>Select Supplier</option>
										<?php foreach($suppliers->fetch_all(MYSQLI_ASSOC) as $sup): ?>
											<option value="<?=$sup['id'] ?>" <?= isset($id) && $supplier_id == $sup['id'] ? "selected" : "" ?>><?= $sup['name'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="category_id" class="control-label">Category</label>
									<select name="category_id" class="custom-select rounded-0" id="category_id" required="required">
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
									<select name="unit_id" class="custom-select rounded-0" id="unit_id" required="required">
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
											<input type="text" name="code" id="code" class="form-control form-control-sm rounded-0" value="<?php echo isset($code) ? $code : ''; ?>"  required/>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="name" class="control-label">Product Name</label>
											<input type="text" name="name" id="name" class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : ''; ?>"  required/>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="description" class="control-label">Description</label>
											<textarea rows="7" name="description" id="description" class="form-control form-control-sm rounded-0"  ><?php echo isset($description) ? $description : ''; ?></textarea>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="" class="control-label">Product Image</label>
											<div class="custom-file">
											<input type="file" class="custom-file-input rounded-circle" id="product_image" name="product_image" onchange="displayImg(this,$(this))" accept="image/png, image/jpeg">
											<label class="custom-file-label" for="product_image">Choose Image</label>
											</div>
										</div>
										<div class="form-group d-flex justify-content-center">
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
											<input type="text" name="unit_size" id="unit_size" class="form-control form-control-sm rounded-0" value="<?php echo isset($unit_size) ? $unit_size : ''; ?>"  required/>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="quantity" class="control-label">Quantity</label>
											<input type="number" name="quantity" id="quantity" class="form-control form-control-sm rounded-0" value="<?php echo isset($quantity) ? $quantity : ''; ?>"  required <?php echo isset($quantity) ? 'disabled' : ''; ?>/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="original_price" class="control-label">Original Price</label>
											<input type="number" name="original_price" id="original_price" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($original_price) ? $original_price : ''; ?>"  required/>
										</div>
									</div>
									<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="retail_pct" class="control-label">Retail %</label>
											<input type="number" name="retail_pct" id="retail_pct" step="0.01" min="5" max="25" class="form-control form-control-sm rounded-0" value="<?php echo isset($retail_pct) ? $retail_pct : '5'; ?>"  required/>
										</div>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="retail_price" class="control-label">Retail Price</label>
											<input type="number" name="retail_price" id="retail_price" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($retail_price) ? $retail_price : ''; ?>"  required/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="expiration_date" class="control-label">Expiration Date</label>
											<input type="date" name="expiration_date" id="expiration_date" class="form-control form-control-sm rounded-0" value="<?php echo isset($expiration_date) ? $expiration_date : ''; ?>"  required/>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="expiration_gap" class="control-label">Expiration Gap</label>
											<input type="number" name="expiration_gap" id="expiration_gap" class="form-control form-control-sm rounded-0" value="<?php echo isset($expiration_gap) ? $expiration_gap : ''; ?>"  required/>
										</div>
									</div>
								</div>
								<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
								<?php if(isset($latestCeilingPointQty)): ?>
										<div class="form-group">
											<label for="ceiling_q" class="control-label">Ceiling Point Qty </label>
											<input type="number" name="ceiling_q" id="ceiling_q" class="form-control form-control-sm rounded-0" value="<?php echo isset($ceiling_q) ? $latestCeilingPointQty : ''; ?>"  required/>
										</div>
								<?php else: ?>
									<div class="form-group">
											<label for="ceiling_q" class="control-label">Ceiling Point Qty </label>
											<input type="number" name="ceiling_q" id="ceiling_q" class="form-control form-control-sm rounded-0" value="<?php echo isset($ceiling_q) ? $ceiling_q : ''; ?>"  required/>
									</div>
								<?php endif; ?>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="reordering_point" class="control-label">Reordering Point <span class="badge bg-warning"> % </span></label>
											<input type="number" name="reordering_point" id="reordering_point" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($reordering_point) ? $reordering_point : ''; ?>"/>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="form-group">
											<label for="critical_point" class="control-label">Critical Point <span class="badge bg-warning"> % </span></label>
											<input type="number" name="critical_point" id="critical_point" step="0.01" class="form-control form-control-sm rounded-0" value="<?php echo isset($critical_point) ? $critical_point : ''; ?>"/>
											<div id="criticalError"></div>
										</div>
									</div>
								</div>

								<div class="row">
					
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<?php if(isset($latestReorderingPoint)): ?>
										<div class="form-group">
											<label for="reordering_q" class="control-label">Reordering Point Qty </label>
											<input type="number" name="reordering_q" id="reordering_q" class="form-control form-control-sm rounded-0" value="<?php echo isset($reordering_q) ? $latestReorderingPoint : ''; ?>"  required disabled/>
										</div>
									<?php else: ?>
										<div class="form-group">
											<label for="reordering_q" class="control-label">Reordering Point Qty </label>
											<input type="number" name="reordering_q" id="reordering_q" class="form-control form-control-sm rounded-0" value="<?php echo isset($reordering_q) ? $reordering_q : ''; ?>"  required disabled/>
										</div>
									<?php endif; ?>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<?php if(isset($latestCriticalPoint)): ?>
										<div class="form-group">
											<label for="critical_q" class="control-label">Critical Point Qty </label>
											<input type="number" name="critical_q" id="critical_q" class="form-control form-control-sm rounded-0" value="<?php echo isset($critical_q) ? $latestCriticalPoint : ''; ?>"  required disabled/>
										</div>
									<?php else: ?>
										<div class="form-group">
											<label for="critical_q" class="control-label">Critical Point Qty </label>
											<input type="number" name="critical_q" id="critical_q" class="form-control form-control-sm rounded-0" value="<?php echo isset($critical_q) ? $critical_q : ''; ?>"  required disabled/>
										</div>
									<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-primary btn-sm bg-gradient-primary btn-flat border-0" form="product-form"><i class="fa fa-save"></i> Save</button>
				<a class="btn btn-light btn-sm bg-gradient-danger border btn-flat" href="./?page=products"><i class="fa fa-times"></i> Cancel</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#criticalError').html(`<p class="absolute text-danger">The critical point must be lower than reordering point.</p>`)
        $('#criticalError').hide();

		$('#supplier_id').select2({
			theme: 'bootstrap4',
			// placeholder: 'Select Supplier',
		});

		$('#category_id').select2({
			theme: 'bootstrap4',
			// placeholder: 'Select Category',
		});
		
		$('#unit_id').select2({
			theme: 'bootstrap4',
			// placeholder: 'Select Unit/Measure',
		});
		
		$('#product-form').submit(function(e){
			e.preventDefault();
			
            var _this = $(this);

			$('.err-msg').remove();
			
			start_loader();

			var criticalPoint = $("#critical_point").val();
			var reorderPoint = $("#reordering_point").val();

			if(Number(criticalPoint) >= Number(reorderPoint)) {
				$('#criticalError').show();
			} else {
				$('#criticalError').hide();
				$("#reordering_q").prop("disabled", false );
				$("#critical_q").prop("disabled", false );

				$.ajax({
				url:_base_url_+"classes/Master.php?f=save_product",
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
					$("#quantity").prop("disabled", true );
					$("#reordering_q").prop("disabled", true );
					$("#critical_q").prop("disabled", true );

					if(typeof resp =='object' && resp.status == 'success'){
						location.replace('./?page=products')
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
			}
		})
	});

</script>

<style>
	img#cproduct_image{
		/* height: 25vh; */
		/* width: 25vh; */
		/* object-fit: cover; */
		/* border-radius: 100% 100%; */
		width: 30vh;
	}
</style>
<script>

	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cproduct_image').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
			$('#cproduct_image').attr('src', "<?php echo validate_image(isset($meta['product_image']) ? $meta['product_image'] :'') ?>");
		}
	}


	$('#retail_price').parent().css({'pointer-events':'none'});

	$('#original_price').on('change keyup', function() {
		// let retail_price = this.value * ($('#retail_pct').val() / 100);
        // $('#retail_price').val(parseFloat(this.value) + parseFloat(retail_price));
		getRetailPrice();
	}); 


	$('#retail_pct').on('change keyup', function() {
		getRetailPrice();
	});

	$('#ceiling_q').on('change keyup', function() {
		getQuantity_Point();
	});

	$('#reordering_point').on('change keyup', function() {
		getQuantity_Point();
	});

	$('#critical_point').on('change keyup', function() {
		getQuantity_Point();
	});

	function getRetailPrice() {
		let retail_price = $('#original_price').val() * ($('#retail_pct').val() / 100);
        $('#retail_price').val(round(parseFloat($('#original_price').val()) + parseFloat(retail_price),2));
	}

	function getQuantity_Point() {
		let ceiling_point_qty = $('#ceiling_q').val();
		let reordering_point = $('#reordering_point').val() / 100;
		let critical_point = $('#critical_point').val() / 100;
		
		// $('#ceiling_q').val(0);
		$('#reordering_q').val(round(ceiling_point_qty * reordering_point,0));
		$('#critical_q').val(round(ceiling_point_qty * critical_point,0));
	}

	function round_up(value, decPlaces) {
		return Math.ceil(value * Math.pow(10, decPlaces)) / Math.pow(10, decPlaces);
	}

	function round(num,dec){
		var d = 1;
		for (var i=0; i<dec; i++){
			d += "0";
		}
		return Math.round(num * d) / d;
	}
</script>