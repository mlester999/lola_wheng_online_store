
<br>
<!-- <div class="card card-outline rounded-0 card-navy ">
	<div class="card-header">
		<h3 class="card-title">Point Of Sale</h3>
		<div class="card-tools">
			<a href="./?page=products/manage_product" id="create_new" class="btn btn-flat btn-primary bg-gradient-primary border-0"><span class="fas fa-plus"></span> Add </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<div class="table-responsive">
				
			</div>
		</div>
	</div>
</div> -->

<style>
    .checkout-input {
        border: solid thin #8888ff;
        height: 50px;
        border-radius: 1px;
        background-color: #e9ffc2;
        font-size: 30px;
        font-weight: bold;
    }
</style>

<div class="d-flex">
    <div style="min-height:600px;" class="shadow-sm col-7 p-4">
        <div id="msg"></div>
        <div class="input-group mb-3"><h3> Items </h3>
            <input type="text" class="ms-4 form-control js-search ml-4" placeholder="Search" aria-label="Search" aria-describedby="basic-search" autofocus>
            <!-- <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span> -->
        </div>

        <div class="js-products d-flex" style="flex-wrap: wrap;height: 90%;overflow-y: scroll;">
            <div class="container">
                <div class="row ajax-res-items">
                       
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-5 bg-light p-4 pt-2">
        
        <div>
            <center>
                <h3>Cart 
                    <div class="js-item-count badge bg-primary rounded-circle">0</div>
                </h3>
            </center>
        </div>
        
        <div class="table-responsive" style="height:400px;overflow-y: scroll;">
            <table class="table table-striped table-hover" id="tbl-cart-items">
                <tr>
                    <th>Image</th><th>Description</th><th>Amount</th>
                </tr>
                
                <tbody class="js-items">

                
                </tbody>
            </table>
        </div>

        <div class="js-gtotal alert alert-danger" style="font-size:30px">Total: ₱ <span id="TotalAmount" > 0.00</span></div>
        <div class="">
            <button class="btn btn-success my-2 w-100 py-4" id="btnCheckOut">Checkout</button>
            <button onclick="authenticate_before_clear()" class="btn btn-primary my-2 w-100">Clear All</button>
        </div>
    </div>
</div>	


<!-- Modal - Check Out -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="checkoutModalLabel">Check Out</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <?php 
                        $customers = $conn->query("SELECT * FROM `customers` WHERE is_active = 1");
                    ?>
                <div class="form-group d-none">
                    <label for="chk_customer_id">Customer Id</label>
                    <input type="number" class="form-control checkout-input" name="chk_customer_id" id="chk_customer_id" placeholder="Customer Id" value="0">
                </div>
                <div class="form-group">
                    <label for="chk_customer_name">Customer Name</label>
                    <select class="form-control checkout-input" name="chk_customer_name" id="chk_customer_name" required="required">
                        <option value="" hidden>Select Name</option>
                        <?php foreach($customers->fetch_all(MYSQLI_ASSOC) as $cust): ?>
                        <option id="<?= $cust['id'] ?>" data-address="<?= $cust['home_address'] ?>" data-contact="<?= $cust['contact_number'] ?>" value="<?= $cust['name'] ?>" ><?= $cust['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p id="customerNameError" class="text-danger"></p>
                </div>
                <div class="form-group">
                    <label for="chk_total_amount">Subtotal</label>
                    <input type="number" step="0.01" class="form-control checkout-input" name="chk_total_amount" id="chk_total_amount" placeholder="0" value="">
                </div>

                <div class="form-group">
                    <label for="chk_vat">VAT (%)</label>
                    <input type="number" step="0.01" class="form-control checkout-input" name="chk_vat" id="chk_vat" placeholder="0" value="<?php echo $_SESSION['system_info']['vat'] ?>">
                </div>

                <div class="form-group">
                    <label for="chk_discount">Discount (%)</label>
                    <input type="number" step="0.01" class="form-control checkout-input" name="chk_discount" id="chk_discount" placeholder="0" value="<?php echo $_SESSION['system_info']['discount'] ?>">
                </div>

                <div class="form-group">
                    <label for="chk_delivery_fee">Delivery Fee</label>
                    <input type="number" class="form-control checkout-input" name="chk_delivery_fee" id="chk_delivery_fee" placeholder="0" value="0">
                </div>

                <div class="form-group">
                    <label for="chk_overall_total">Overall Total</label>
                    <input type="number" step="0.01" class="form-control checkout-input" name="chk_overall_total" id="chk_overall_total" placeholder="0" value="">
                    <small>Discounted: ₱ <span id="discounted">0.00</span></small>
                </div>

                <div class="form-group">
                    <label for="chk_total_rendered">Cash Rendered</label>
                    <input type="number" step="0.01" class="form-control checkout-input" name="chk_total_rendered" id="chk_total_rendered" placeholder="0" value="0" min="0">
                    <p id="cashRenderedError" class="text-danger"></p>
                </div>

                <div class="form-group">
                    <label for="chk_change">Change</label>
                    <input type="number" step="0.01" class="form-control checkout-input" name="chk_change" id="chk_change" placeholder="0" value="0">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-submit-checkout" onclick="submit_checkout()">Checkout</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Authenticate Check Out -->
<div class="modal fade" id="authenticateCheckOutModal" tabindex="1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="checkoutModalLabel">Please enter the Admin or Manager Credentials to proceed.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control checkout-input" id="username" autofocus placeholder="Username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control checkout-input" id="password" placeholder="Password">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-submit-checkout" onclick="authenticate_after_transaction()">Enter</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Authenticate Clear All -->
<div class="modal fade" id="authenticateClearAllModal" tabindex="2" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="checkoutModalLabel">Please enter the Admin or Manager Credentials to proceed.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control checkout-input" id="username" autofocus placeholder="Username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control checkout-input" id="password" placeholder="Password">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-submit-checkout" onclick="authenticate_after_clear()">Enter</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Before Decrease Item Authenticate Product -->
<div class="modal fade" id="authenticateDecreaseItemModal" tabindex="3" role="dialog" aria-labelledby="authenticateDecreaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="authenticateDecreaseModalLabel">Please enter the Admin or Manager Credentials to proceed.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control checkout-input" id="username" autofocus placeholder="Username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control checkout-input" id="password" placeholder="Password">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-submit-checkout" onclick="authenticate_after_decrease_item()">Enter</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal - Authenticate Product -->
<div class="modal fade" id="authenticateRemoveItemModal" tabindex="3" role="dialog" aria-labelledby="authenticateRemoveItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="authenticateRemoveItemModalLabel">Please enter the Admin or Manager Credentials to proceed.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control checkout-input" id="username" autofocus placeholder="Username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control checkout-input" id="password" placeholder="Password">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-submit-checkout" onclick="authenticate_after_remove_item()">Enter</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal - Print Receipt -->
<div class="modal fade" id="PrintReceiptModal" tabindex="-1" role="dialog" aria-labelledby="PrintReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="PrintReceiptModalLabel">Print Receipt</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3>Do you want to print receipt?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="no_print_receipt()">No</button>
                <button type="button" class="btn btn-primary btn-print-receipt" onclick="yes_print_receipt()">Yes! Print</button>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
		load_data();

        $('#msg').html(`<div class="alert alert-danger">The username and password does not match.</div>`)
        $('#msg').hide();

		function load_data(query)
		{
			$.ajax({
                url:"pos/pos-search-item.php",
                method:"POST",
                data:{query:query},
                success:function(data)
                {
                    $('.ajax-res-items').html(data);
                }
			});
		}

		$('.js-search').keyup(function(){
		    var search = $(this).val();
            if(search != '')
            {
                load_data(search);
            }
            else
            {
                load_data();
            }
		});

        $('#chk_customer_name').select2({
			placeholder: 'Select Customer Name',
            dropdownParent: $('#chk_customer_name').parent(),
			theme: 'bootstrap4',
		});
	});   
    
    $('#btnCheckOut').click(function() {
        let totalAmount = $('span#TotalAmount').text();

        if(parseFloat(totalAmount) > 0) {
            $('#checkoutModal').modal('show');
        }

        $('#checkoutModal').on('shown.bs.modal',function(e) {
            $('input#chk_total_amount').val(parseFloat(totalAmount).toFixed(2));
            $('input#chk_total_amount').parent().css({'pointer-events':'none'})

            var totalWithVat = Number(parseFloat(totalAmount) + (parseFloat(totalAmount) * ($('input#chk_vat').val() / 100)));

            var discountPercentage = $("#chk_discount").val();

            if(discountPercentage) {
                subTotalWithDiscount = parseFloat(discountPercentage / 100) * totalWithVat;
                $('span#discounted').text(parseFloat(subTotalWithDiscount).toFixed(2));
            } else {
                subTotalWithDiscount = 0;
            }

            var overallTotal = totalWithVat - subTotalWithDiscount + parseFloat($('input#chk_delivery_fee').val());

            $('input#chk_overall_total').val(overallTotal.toFixed(2));
            $('input#chk_overall_total').parent().css({'pointer-events':'none'})
            
            $('input#chk_vat').parent().css({'pointer-events':'none'})
        });
    
    });

    $('#checkoutModal input#chk_total_rendered').on('keyup change',function() {
        getChange();
    });


    $('#checkoutModal input#chk_discount').on('keyup change',function() {
        getChange();
    });

    $('#checkoutModal input#chk_vat').on('keyup change',function() {
        getChange();
    });

    $('#checkoutModal input#chk_delivery_fee').on('keyup change',function() {
        getChange();
    });

    function getChange() {
        let totalChkAmount = $('input#chk_total_amount').val();
        let chk_total_rendered = $('input#chk_total_rendered').val();
        let chk_discount = $('input#chk_discount').val();
        let chk_vat = $('input#chk_vat').val();
        let chk_delivery_fee = $('input#chk_delivery_fee').val();
        let chk_overall_total = $('input#chk_overall_total').val();
        let totalChange = 0;
        let discount = 0;
        let vat = 0;
        let delivery_fee = 0;

        if(chk_vat > 0) {
            vat = (parseFloat(chk_vat / 100).toFixed(2)) * totalChkAmount;
        
            $('input#chk_overall_total').val(Number(parseFloat(totalChkAmount) + vat - discount + delivery_fee ).toFixed(2));
        } else {
            vat = 0;

            $('input#chk_overall_total').val(totalChkAmount);
        }

        if(chk_delivery_fee > 0) {
            delivery_fee = parseFloat(chk_delivery_fee);
        
            $('input#chk_overall_total').val(Number(parseFloat(totalChkAmount) + vat - discount + delivery_fee ).toFixed(2));
        } else {
            delivery_fee = 0;

            $('input#chk_overall_total').val(Number(parseFloat(totalChkAmount) + vat - discount + delivery_fee ).toFixed(2));
        }

        if(chk_discount > 0) {
            var totalWithVat = Number(parseFloat(totalChkAmount) + vat);

            discount = (parseFloat(chk_discount / 100).toFixed(2)) * totalWithVat;
        
            $('input#chk_overall_total').val(Number(parseFloat(totalChkAmount) + vat - discount + delivery_fee ).toFixed(2));
        } else {
            discount = 0;

            $('input#chk_overall_total').val(Number(parseFloat(totalChkAmount) + vat - discount + delivery_fee ).toFixed(2));
            $('span#discounted').text(parseFloat(discount).toFixed(2));
        }

        totalChange = parseFloat((chk_total_rendered - totalChkAmount) + discount - vat - delivery_fee).toFixed(2);
        if(totalChange > 0) {
            $('input#chk_change').val(totalChange);

            $('span#discounted').text(parseFloat(discount).toFixed(2));
        } 

        if(totalChange < 0 || totalChange == 0) {
            $('input#chk_change').val(0);

            $('span#discounted').text(parseFloat(discount).toFixed(2));
        }
    }
    
    function authenticate_after_transaction() {
        var authenticate_username = $('#authenticateCheckOutModal input#username').val();
        var authenticate_password = $('#authenticateCheckOutModal input#password').val();

        $.ajax({
            url: "pos/authenticate_transaction.php",
            type: "POST",
            data: {
                username: authenticate_username,
                password: authenticate_password,
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200) {
                    $('#msg').hide();

                    $('#authenticateCheckOutModal input#username').val('');
                    $('#authenticateCheckOutModal input#password').val('');
                    
                    submit_checkout();
                } else {
                    $('#msg').show();

                    $('#authenticateCheckOutModal input#username').val('');
                    $('#authenticateCheckOutModal input#password').val('');

                    $('#authenticateClearAllModal').modal('hide');
                }
            }
        });
    }

    function authenticate_before_decrease_item(barcode, productId) {
        $('#authenticateDecreaseItemModal').data('data-productId', productId);
        $('#authenticateDecreaseItemModal').data('data-barcode', barcode);

        $('#authenticateDecreaseItemModal').modal({
            show:true,
        });
    }

    function authenticate_after_decrease_item() {
        var authenticate_username = $('#authenticateDecreaseItemModal input#username').val();
        var authenticate_password = $('#authenticateDecreaseItemModal input#password').val();
        var get_product_id = $('#authenticateDecreaseItemModal').data("data-productId");
        var get_barcode = $('#authenticateDecreaseItemModal').data("data-barcode");

        $.ajax({
            url: "pos/authenticate_transaction.php",
            type: "POST",
            data: {
                username: authenticate_username,
                password: authenticate_password,
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                console.log(dataResult);
                if(dataResult.statusCode == 200) {
                    change_quantity_down(get_barcode, get_product_id);

                    $('#authenticateDecreaseItemModal input#username').val('');
                    $('#authenticateDecreaseItemModal input#password').val('');
                    $('#msg').hide();
                } else {

                    $('#authenticateDecreaseItemModal input#username').val('');
                    $('#authenticateDecreaseItemModal input#password').val('');
                    $('#msg').show();
                }

                $('#authenticateDecreaseItemModal').modal('hide');
            }
        });
    }

    function authenticate_before_remove_item(barcode, productId) {
        $('#authenticateRemoveItemModal').data('data-productId', productId);
        $('#authenticateRemoveItemModal').data('data-barcode', barcode);

        $('#authenticateRemoveItemModal').modal({
            show:true,
        });
    }

    function authenticate_after_remove_item() {
        var authenticate_username = $('#authenticateRemoveItemModal input#username').val();
        var authenticate_password = $('#authenticateRemoveItemModal input#password').val();
        var get_product_id = $('#authenticateRemoveItemModal').data("data-productId");
        var get_barcode = $('#authenticateRemoveItemModal').data("data-barcode");

        $.ajax({
            url: "pos/authenticate_transaction.php",
            type: "POST",
            data: {
                username: authenticate_username,
                password: authenticate_password,
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                console.log(dataResult);
                if(dataResult.statusCode == 200) {
                    remove_item(get_barcode, get_product_id);

                    $('#authenticateRemoveItemModal input#username').val('');
                    $('#authenticateRemoveItemModal input#password').val('');
                    $('#msg').hide();
                } else {

                    $('#authenticateRemoveItemModal input#username').val('');
                    $('#authenticateRemoveItemModal input#password').val('');
                    $('#msg').show();
                }

                $('#authenticateRemoveItemModal').modal('hide');
            }
        });
    }

    function authenticate_before_clear() {
        if(getTotalQuantity()) {
            $('#authenticateClearAllModal').modal({
                show:true,
            });
        }
    }
    
    function authenticate_after_clear() {
        var authenticate_username = $('#authenticateClearAllModal input#username').val();
        var authenticate_password = $('#authenticateClearAllModal input#password').val();

        $.ajax({
            url: "pos/authenticate_transaction.php",
            type: "POST",
            data: {
                username: authenticate_username,
                password: authenticate_password,
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200) {
                    $('#msg').hide();

                    $('#authenticateClearAllModal input#username').val('');
                    $('#authenticateClearAllModal input#password').val('');

                    clear_all();
                } else {

                    $('#authenticateClearAllModal input#username').val('');
                    $('#authenticateClearAllModal input#password').val('');

                    $('#msg').show();
                }

                $('#authenticateClearAllModal').modal('hide');
            }
        });
    }

    function authenticate_before_transaction() {
        var chk_customer_id = $('#checkoutModal select#chk_customer_name option').filter(":selected").prop("id");
        var chk_customer_name = $('#checkoutModal select#chk_customer_name option').filter(":selected").val();
        var chk_customer_home_address = $('#checkoutModal select#chk_customer_name option').filter(":selected").data("address");
        var chk_customer_contact_number = $('#checkoutModal select#chk_customer_name option').filter(":selected").data("contact");
        var chk_total_amount = $('#checkoutModal input#chk_total_amount').val();
        var chk_total_rendered = $('#checkoutModal input#chk_total_rendered').val();
        var chk_change = $('#checkoutModal input#chk_change').val();
        var chk_discount = $('#checkoutModal input#chk_discount').val();
        var chk_vat = $('#checkoutModal input#chk_vat').val();

        if(!chk_customer_id) {
            $('#checkoutModal #customerNameError').text("This field is required");
        } else {
            $('#checkoutModal #customerNameError').text("");
        }
        
        if(chk_total_rendered == 0) {
            $('#checkoutModal #cashRenderedError').text("This field is required");
        } else {
            $('#checkoutModal #cashRenderedError').text("");
        }

        if(chk_total_rendered >= Number(chk_total_amount) + Number(parseFloat(chk_vat / 100).toFixed(2)) * chk_total_amount && chk_customer_name) {
            $('#checkoutModal').modal('hide');

            $('.modal-backdrop').remove();
            
            $('#authenticateCheckOutModal').modal({
                show:true,
            });
        }
    }

    function submit_checkout() {
        // btn-submit-checkout
        var chk_customer_id = $('#checkoutModal select#chk_customer_name option').filter(":selected").prop("id");
        var chk_customer_name = $('#checkoutModal select#chk_customer_name option').filter(":selected").val();
        var chk_customer_home_address = $('#checkoutModal select#chk_customer_name option').filter(":selected").data("address");
        var chk_customer_contact_number = $('#checkoutModal select#chk_customer_name option').filter(":selected").data("contact");
        var chk_total_amount = $('#checkoutModal input#chk_total_amount').val();
        var chk_total_rendered = $('#checkoutModal input#chk_total_rendered').val();
        var chk_change = $('#checkoutModal input#chk_change').val();
        var chk_discount = $('#checkoutModal input#chk_discount').val();
        var chk_vat = $('#checkoutModal input#chk_vat').val();
        var chk_delivery_fee = $('#checkoutModal input#chk_delivery_fee').val();
        var chk_overall_total = $('#checkoutModal input#chk_overall_total').val();

        var vatTotal = Number(parseFloat(chk_vat / 100).toFixed(2)) * chk_total_amount;
        var totalWithVat = Number(parseFloat(chk_total_amount) + vatTotal);

        var totalWithDiscount = (parseFloat(chk_discount / 100).toFixed(2)) * totalWithVat;

        if(!chk_customer_id) {
            $('#checkoutModal #customerNameError').text("This field is required");
        } else {
            $('#checkoutModal #customerNameError').text("");
        }
        
        if(chk_total_rendered == 0) {
            $('#checkoutModal #cashRenderedError').text("This field is required");
        } else if(chk_total_rendered < chk_overall_total) {
            $('#checkoutModal #cashRenderedError').text("Cash rendered must be higher than overall total");
        } else {
            $('#checkoutModal #cashRenderedError').text("");
        }
        
        if(chk_total_rendered >= parseFloat(totalWithVat - totalWithDiscount + Number(chk_delivery_fee)).toFixed(2) && chk_customer_name) {
            $.ajax({
            url: "pos/submit_checkout.php",
            type: "POST",
            data: {
                customer_id: chk_customer_id,
                customer_name: chk_customer_name,
                customer_contact_number: chk_customer_contact_number,
                customer_home_address: chk_customer_home_address,
                total_amount: chk_total_amount,
                amount_rendered: chk_total_rendered,
                changed: chk_change,
                discount_pct: chk_discount,
                vat_pct: chk_vat,
                delivery_fee: chk_delivery_fee,
                overall_total: chk_overall_total,
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200) {
                    // alert('Items/s added successfully.');
                } else {
                    // alert('Something went wrong');
                }
            }
        });

       $('#checkoutModal').hide();

        $('#authenticateCheckOutModal').modal('hide');
        
        $('.modal-backdrop').remove()

        $('#PrintReceiptModal').modal({
            show:true,
        });
        }
    }


    function no_print_receipt() {
        empty_cart();
    }

    function yes_print_receipt() {
        window.open('pos/print_receipt.php','_blank');
        empty_cart();
    }

    function empty_cart() {
        $.ajax({
            url: "pos/empty_cart.php",
            cache: false,
            success: function(dataResult1) {
                var dataResult1 = JSON.parse(dataResult1);
                if(dataResult1.statusCode == 200) {
                    // alert('Items/s added successfully.');
                    location.reload();
                } else {
                    // alert('Something went wrong');
                }
            }
        });

        location.reload();
    }
</script>

