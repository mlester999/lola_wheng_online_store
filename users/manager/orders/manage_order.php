
<?php
if(isset($_GET['order_id']) && $_GET['order_id'] > 0){
    $qry = $conn->query("SELECT * FROM `orders_2` WHERE order_id = '{$_GET['order_id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}

$suppliers = $conn->query("SELECT * FROM `suppliers` WHERE is_archived = 0 AND is_active = 1");
$categories = $conn->query("SELECT * FROM `categories` WHERE is_active = 1");
$measurements = $conn->query("SELECT * FROM `measurements` WHERE is_active = 1");
$products = $conn->query("SELECT a.* 
                            FROM (
                                SELECT `id`, `name`, `retail_price`, `product_image`, `quantity`, `code`, `date_added` , ROW_NUMBER()OVER(PARTITION BY `code` ORDER BY date_added ASC) AS row_id
                                FROM `products`
                                WHERE quantity > 0
                                ORDER BY id ASC
                            ) a
                            WHERE a.row_id = 1");
?>

<br>
<div class="content py-5 px-3 bg-gradient-primary">
	<h2><b><?= isset($order_id) ? "Update Order Details" : "New Order Entry" ?></b></h2>
</div>

<?php 
    if(!isset($order_id)) {
?>
        <div class="row mt-lg-n4 mt-md-n4 justify-content-center">
            <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 ">
                <div class="card rounded-0 ">
                    <div class="card-body">
                        <form action="" id="order-form">  
                            <div class="container-fluid">
                                <div class="main-form mt-3 border-bottom">
                                    <div class="row main-form mt-3 border-bottom">
                                        <div class="col-lg-1 col-md-1 d-none">
                                            <div class="form-group mb-2">
                                                <label for="row_id" class="control-label">Id</label>
                                                <input type="number" class="form-control form-control-sm rounded-0 row_num" name="row_num[]" id="row_id" value="1">   
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <div class="form-group mb-2">
                                                <label for="" class="control-label">Supplier</label>
                                                <select name="supplier_id[]" class="custom-select rounded-0" id="supplier_id-1" required="required">
                                                    <option value="">Select Supplier</option>
                                                    <?php foreach($suppliers->fetch_all(MYSQLI_ASSOC) as $sup): ?>
                                                        <option value="<?=$sup['id'] ?>" <?= isset($id) && $supplier_id == $sup['id'] ? "selected" : "" ?>><?= $sup['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <div class="form-group mb-2">
                                                <label for="category_id" class="control-label">Category</label>
                                                <select name="category_id[]" class="custom-select rounded-0" id="category_id-1" required="required">
                                                    <option value="">Select Category</option>
                                                    <?php foreach($categories->fetch_all(MYSQLI_ASSOC) as $cat): ?>
                                                        <option value="<?= $cat['category_id'] ?>" <?= isset($id) && $category_id == $cat['category_id'] ? "selected" : "" ?>><?= $cat['category_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group mb-2">
                                                <label for="product_id" class="control-label">Product</label>
                                                <select name="product_id[]" class="custom-select rounded-0" id="product_id-1" required="required">
                                                    <option value="">Select Product</option>
                                                    <?php foreach($products->fetch_all(MYSQLI_ASSOC) as $prod): ?>
                                                        <option value="<?= $prod['id'] ?>" <?= isset($id) && $product_id == $prod['id'] ? "selected" : "" ?>><?= $prod['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <div class="form-group mb-2">
                                                <label for="unit_id" class="control-label">Unit</label>
                                                <select name="unit_id[]" class="custom-select rounded-0" id="unit_id-1" required="required">
                                                    <option value="">Select Unit/Measure</option>
                                                    <?php foreach($measurements->fetch_all(MYSQLI_ASSOC) as $mes): ?>
                                                        <option value="<?= $mes['measurement_id'] ?>" <?= isset($id) && $unit_id == $mes['measurement_id'] ? "selected" : "" ?>><?= $mes['measurement_name'] . ' (' . $mes['measurement_code']. ')'?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 d-flex align-items-center">
                                            <div class="form-group mb-2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="quantity" class="control-label">Quantity</label>
                                                <input type="number" class="form-control form-control-sm rounded-0" id="quantity" name="quantity[]" value="" required>   
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="paste-new-forms"></div>

                                <a href="javascript:void(0)" class="add-more-form float-end btn btn-sm btn-primary mt-3"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add More</a>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer py-1 text-center">
                        <button class="btn btn-primary btn-sm bg-gradient-primary btn-flat border-0" form="order-form"><i class="fa fa-cart-plus"></i> Submit Order</button>
                        <a class="btn btn-light btn-sm bg-gradient-danger border btn-flat" href="./?page=orders"><i class="fa fa-times"></i> Cancel Order</a>
                    </div>
                </div>
            </div>
        </div>
<?php   
    } else {
?>
        <div class="row mt-lg-n4 mt-md-n4 justify-content-center">
            <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 ">
                <div class="card rounded-0 ">
                    <div class="card-body">
                        <form action="" id="order-form-update">  
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group mb-2 d-none">
                                            <label for="order_id" class="control-label">Id</label>
                                            <input type="number" class="form-control form-control-sm rounded-0" name="order_id" id="order_id" value="<?=$order_id;?>">   
                                        </div>

                                        <div class="form-group mb-2 d-none">
                                            <label for="transaction_code" class="control-label">Transaction Code</label>
                                            <input type="text" class="form-control form-control-sm rounded-0" name="transaction_code" id="transaction_code" value="<?=$transaction_code;?>">   
                                        </div>

                                        <div class="form-group mb-2 d-none">
                                            <label for="barcode" class="control-label">Barcode</label>
                                            <input type="text" class="form-control form-control-sm rounded-0" name="barcode" id="barcode" value="<?=$barcode;?>">   
                                        </div>

                                        <div class="form-group mb-2 pb-2">
                                            <label for="" class="control-label">Supplier</label>
                                            <select disabled name="supplier_id" class="custom-select rounded-0" id="supplier_id" required="required">
                                                <option value="">Select Supplier</option>
                                                <?php foreach($suppliers->fetch_all(MYSQLI_ASSOC) as $sup): ?>
                                                    <option value="<?=$sup['id'] ?>" <?= isset($order_id) && $supplier_id == $sup['id'] ? "selected" : "" ?>><?= $sup['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group mb-2 pb-2">
                                            <label for="category_id" class="control-label">Category</label>
                                            <select disabled name="category_id" class="custom-select rounded-0" id="category_id" required="required">
                                                <option value="">Select Category</option>
                                                <?php foreach($categories->fetch_all(MYSQLI_ASSOC) as $cat): ?>
                                                    <option value="<?= $cat['category_id'] ?>" <?= isset($order_id) && $category_id == $cat['category_id'] ? "selected" : "" ?>><?= $cat['category_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group mb-2 pb-2">
                                            <label for="product_id" class="control-label">Product</label>
                                            <select disabled name="product_id" class="custom-select rounded-0" id="product_id" required="required">
                                                <option value="">Select Product</option>
                                                <?php foreach($products->fetch_all(MYSQLI_ASSOC) as $prod): ?>
                                                    <option value="<?= $prod['id'] ?>" <?= isset($order_id) && $product_id == $prod['id'] ? "selected" : "" ?>><?= $prod['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group mb-2 pb-2">
                                        <label for="unit_id" class="control-label">Unit</label>
                                        <select disabled name="unit_id" class="custom-select rounded-0" id="unit_id" required="required">
                                            <option value="">Select Unit/Measure</option>
                                            <?php foreach($measurements->fetch_all(MYSQLI_ASSOC) as $mes): ?>
                                                <option value="<?= $mes['measurement_id'] ?>" <?= isset($order_id) && $unit_id == $mes['measurement_id'] ? "selected" : "" ?>><?= $mes['measurement_name'] . ' (' . $mes['measurement_code']. ')'?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group mb-2 pb-2">
                                        <label for="quantity" class="control-label">Quantity</label>
                                        <input type="number" class="form-control form-control-sm rounded-0" id="quantity" name="quantity" value="<?=$quantity?>" required style="height: 38px;">   
                                    </div>

                                    <div class="form-group mb-2 pb-2">
                                        <label for="status" class="control-label">Status</label>
                                        <div class="custom-control custom-radio d-none">
                                            <input class="custom-control-input custom-control-input-warning" type="radio" id="customRadio2" name="status" value="0" <?php if($status == 0) { echo 'checked'; };?> >
                                            <label for="customRadio2" class="custom-control-label">Not Delivered</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-primary " type="radio" id="customRadio4" name="status" value="1" <?php if($status == 1) { echo 'checked'; };?> >
                                            <label for="customRadio4" class="custom-control-label">Delivered</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input custom-control-input-danger " type="radio" id="customRadio5" name="status" value="2" <?php if($status == 2) { echo 'checked'; };?> >
                                            <label for="customRadio5" class="custom-control-label">Cancelled</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>  
                    <div class="card-footer py-1 text-center">
                        <button class="btn btn-primary btn-sm bg-gradient-primary btn-flat border-0" form="order-form-update"><i class="fa fa-save"></i> Update</button>
                        <a class="btn btn-light btn-sm bg-gradient-danger border btn-flat" href="./?page=orders"><i class="fa fa-times"></i> Cancel</a>
                    </div>
                </div>  
            </div>  
        </div>  
<?php   
    } 
?>
<script>    
    $(document).ready(function () {
        var originalQty = $('#quantity').val();

        $('#supplier_id-1').select2({
            theme: 'bootstrap4',
        });

        $('#category_id-1').select2({
            theme: 'bootstrap4',
        });

        $('#product_id-1').select2({
            theme: 'bootstrap4',
        });

        $('#unit_id-1').select2({
            theme: 'bootstrap4',
        });
        

        $('#supplier_id').select2({
            theme: 'bootstrap4',
        });

        $('#category_id').select2({
            theme: 'bootstrap4',
        });

        $('#product_id').select2({
            theme: 'bootstrap4',
        });

        $('#unit_id').select2({
            theme: 'bootstrap4',
        });

        $(document).on('click', '.remove-btn', function () {
            $(this).closest('.main-form').remove();
        });
        
        $(document).on('click', '.add-more-form', function () {
            var length = $('.row_num').length;
            var i = parseInt(length) + parseInt(1);
            $('.paste-new-forms').append(`
                        <?php 
                            $suppliers = $conn->query("SELECT * FROM `suppliers` WHERE is_archived = 0 AND is_active = 1");
                            $categories = $conn->query("SELECT * FROM `categories` WHERE is_active = 1");
                            $measurements = $conn->query("SELECT * FROM `measurements` WHERE is_active = 1");
                            $products = $conn->query("SELECT a.* 
                            FROM (
                                SELECT `id`, `name`, `retail_price`, `product_image`, `quantity`, `code`, `date_added` , ROW_NUMBER()OVER(PARTITION BY `code` ORDER BY date_added ASC) AS row_id
                                FROM `products`
                                WHERE quantity > 0
                                ORDER BY id ASC
                            ) a
                            WHERE a.row_id = 1");
                        ?>
                        <div class="main-form mt-4 border-bottom">
                            <div class="row">
                                <div class="col-md-1 d-none">
                                    <div class="form-group mb-2">
                                        <label for="row_id" class="control-label">Id</label>
                                        <input type="number" class="form-control form-control-sm rounded-0 row_num" name="row_num[]" id="row_id" value="${i}">   
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <div class="form-group mb-2">
                                        <label for="">Supplier</label>
                                        <select name="supplier_id[]" class="custom-select rounded-0 " id="supplier_id-${i}" required="required">
                                            <option value="">Select Supplier</option>
                                            <?php foreach($suppliers->fetch_all(MYSQLI_ASSOC) as $sup): ?>
                                                <option value="<?=$sup['id'] ?>"><?= $sup['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <div class="form-group mb-2">
                                        <label for="category_id" class="control-label">Category</label>
                                        <select name="category_id[]" class="custom-select rounded-0" id="category_id-${i}" required="required">
                                            <option value="">Select Category</option>
                                            <?php foreach($categories->fetch_all(MYSQLI_ASSOC) as $cat): ?>
                                                <option value="<?= $cat['category_id'] ?>"><?= $cat['category_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group mb-2">
                                        <label for="product_id" class="control-label">Product</label>
                                        <select name="product_id[]" class="custom-select rounded-0" id="product_id-${i}" required="required">
                                            <option value="">Select Product</option>
                                            <?php foreach($products->fetch_all(MYSQLI_ASSOC) as $prod): ?>
                                                <option value="<?= $prod['id'] ?>" <?= isset($id) && $product_id == $prod['id'] ? "selected" : "" ?>><?= $prod['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <div class="form-group mb-2">
                                        <label for="unit_id" class="control-label">Unit</label>
                                        <select name="unit_id[]" class="custom-select rounded-0" id="unit_id-${i}" required="required">
                                            <option value="">Select Unit/Measure</option>
                                            <?php foreach($measurements->fetch_all(MYSQLI_ASSOC) as $mes): ?>
                                                <option value="<?= $mes['measurement_id'] ?>" <?= isset($id) && $unit_id == $mes['measurement_id'] ? "selected" : "" ?>><?= $mes['measurement_name'] . ' (' . $mes['measurement_code']. ')'?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 d-flex align-items-center">
                                    <div class="form-group mb-2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="quantity" class="control-label">Quantity</label>
                                        <input type="number" class="form-control form-control-sm rounded-0" id="quantity" name="quantity[]" value="" required>   
                                    </div>
                                </div>

                                <div class="col-md-1 d-flex align-items-end">
                                    <div class="form-group mb-2 pb-1">
                                        <button type="button" class="remove-btn btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>`);
                        
            $('#supplier_id-' + i).select2({
                theme: 'bootstrap4',
                width: '100%',
            });

            $('#category_id-' + i).select2({
                theme: 'bootstrap4',
                width: '100%',
            });

            $('#product_id-' + i).select2({
                theme: 'bootstrap4',
                width: '100%',
            });

            $('#unit_id-' + i).select2({
                theme: 'bootstrap4',
                width: '100%',
            });
        });

        $('#quantity').on('input', function () {
            if(Number($('#quantity').val()) > Number(originalQty)) {
                $('#quantity').val(originalQty);
            }

            if($('#quantity').val() == "" || Number($('#quantity').val()) <= 0 ) {
                $('#quantity').val(Number(1));
            }
        });

        $('#quantity').keypress(function () {
            if(Number($('#quantity').val()) > Number(originalQty)) {
                $('#quantity').val(originalQty);

            }

            if(Number($('#quantity').val()) <= 0) {
                $('#quantity').val(Number(1));
            }
        });

        $('#order-form').submit(function(e){
			e.preventDefault();
			
            var _this = $(this);

			$('.err-msg').remove();
			
			$.ajax({
				url:_base_url_+"classes/Orders.php?f=save_order",
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
				},

				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.replace('./?page=orders');
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
                        // console.log(resp)
					}
				}
			});
		});

        $('#order-form-update').submit(function(e){
			e.preventDefault();
			
            var _this = $(this);

			$('.err-msg').remove();
			
            start_loader();

            var formData = new FormData($(this)[0]);
            formData.append('originalQuantity', originalQty);

			$.ajax({
				url:_base_url_+"classes/Orders.php?f=update_order",
				data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				success:function(resp){
                    console.log(resp);
					if(typeof resp =='object' && resp.status == 'success'){
						location.replace('./?page=orders');
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.err)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").scrollTop(0);
                            end_loader()
                    }
                    else{
						alert_toast("An error occured edit",'error');
						end_loader();
                        // console.log(resp)
					}
				},  
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr, textStatus, errorThrown);
                    alert_toast("An error occured",'error');
				},
			});
		});
    });

    $(window).resize(function () {
        $('#supplier_id-1').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        $('#category_id-1').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        $('#product_id-1').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        $('#unit_id-1').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        $('#supplier_id').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        $('#category_id').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        $('#product_id').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        $('#unit_id').select2({
            theme: 'bootstrap4',
            width: '100%',
        });
    });
</script>
