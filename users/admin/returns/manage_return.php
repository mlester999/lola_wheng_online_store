
<?php

if(isset($_GET['customer_name']) && isset($_GET['transaction_number']) && $_GET['transaction_number'] > 0){
    $qry = $conn->query("SELECT * FROM `sales_transactions_2` WHERE customer_name = '{$_GET['customer_name']}' AND transaction_number = '{$_GET['transaction_number']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    } else {
		$url = "./?page=returns";
		echo '<script>location.replace("'.$url.'")</script>';
	}
}
 else {
	$url = "./?page=returns";
	echo '<script>location.replace("'.$url.'")</script>';
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
	<h2><b><?= isset($transaction_number) ? "Manage Returned Products" : "Add Product Returned" ?></b></h2>
</div>

		<p class="d-none" id="userFullName"><?php echo $customer_name ?></p>
        <p class="d-none" id="userContactNumber"><?php echo $customer_contact_number ?></p>
        <p class="d-none" id="userHomeAddress"><?php echo $customer_home_address ?></p>

<div class="row mt-lg-n4 mt-md-n4 justify-content-center">
	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
		<div class="card rounded-0">
			<div class="card-body">
				<div class="table-responsive" style="overflow-y: scroll;">
					<table class="table table-striped table-hover" id="tbl-cart-items" style="margin-bottom: 0;">
						<tr>
							<th>Image</th><th>Description</th><th>Reason for Return</th>
						</tr>
						
						<tbody class="js-items">
							<?php
								$items = $conn->query("SELECT customer_name, customer_contact_number, customer_home_address, sti.product_id, p.name, sti.product_barcode, sti.quantity, p.product_image, sti.session_id FROM `sales_transactions_2` st JOIN sales_transactions_2_items sti ON st.session_id = sti.session_id JOIN products p ON sti.product_id = p.id WHERE customer_name = '{$_GET['customer_name']}' AND transaction_number = '{$_GET['transaction_number']}' ");
							
								if($items->num_rows > 0) {
									while($row = $items->fetch_assoc()) {
							?>
						<tr>
                        <td class="d-none product_id" id="`+ product_id +`" data-product_id="` + product_id + `"> `+product_id+` </td>
                        <td style="width:110px"><img src="<?php echo base_url ?><?php echo $row['product_image'] ?>" class="rounded border" style="width:100px;height:100px"></td>
                        <td class="text-primary product_name">
                            <p style="margin-bottom: 0;"><?php echo $row['name'] ?></p>
                            <p style="color: gray; margin-bottom: 0;"><?php echo $row['quantity'] ?> ordered qty</p>

                            <div class="input-group my-3" style="max-width:150px">
                                <span onclick="change_quantity_down('<?php echo $row['product_id'] ?>', `<?php echo $row['quantity'] ?>`)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-minus text-primary"></i></span>
                                <input onblur="calculate_item_quantity('<?php echo $row['product_id'] ?>', `<?php echo $row['quantity'] ?>`)" onchange="calculate_item_quantity('<?php echo $row['product_id'] ?>', `<?php echo $row['quantity'] ?>`)" onkeyup="calculate_item_quantity('<?php echo $row['product_id'] ?>', `<?php echo $row['quantity'] ?>`)" type="text" data-product_id="<?php echo $row['product_id'] ?>" data-product_name="<?php echo $row['name'] ?>" data-product_barcode="<?php echo $row['product_barcode'] ?>" data-product_quantity="<?php echo $row['quantity'] ?>" data-product_session_id="<?php echo $row['session_id'] ?>" class="form-control text-primary quantity<?php echo $row['product_id'] ?>" value="0" min="0" id="quantity" />
                                <span onclick="change_quantity_up('<?php echo $row['product_id'] ?>', `<?php echo $row['quantity'] ?>`)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-plus text-primary"></i></span>
                            </div>

                        </td>
                        <td style="font-size:20px" class="reason-for-return cart-row">
						<div class="row">
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<textarea id="reason_for_return" class="form-control form-control-sm rounded-0" name="reason_for_return" rows="4" cols="50">
								</textarea>
							</div>
						</div>
                        </td>
                    </tr>
					<?php 
                                }
                        ?>
					<?php
						}
					?>
						</tbody>
					</table>
					<p class="text-danger text-center" id="errorFields" style="font-size: 14px; margin: 0;"></p>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-primary btn-sm bg-gradient-primary btn-flat border-0" id="submit-return"><i class="fa fa-save"></i> Save</button>
				<a class="btn btn-light btn-sm bg-gradient-danger border btn-flat" href="./?page=returns"><i class="fa fa-times"></i> Cancel</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#submit-return').click(function(){
			var _this = $(this);

			$('.err-msg').remove();
			
			start_loader();

			let reasons = $('.js-items').find('textarea');
			let checkReasons = reasons.map((i, el) => {
				return el.value;
			})

			// let reasonTextField = $.trim($('#reason_for_return').val());

          	let inputs = $('.js-items').find('input');
			let checkInputValues = inputs.map((i, el) => {
				return {
				product_id: el.dataset.product_id,
				product_name: el.dataset.product_name,
				product_barcode: el.dataset.product_barcode,
				product_quantity: el.value,
				product_session_id: el.dataset.product_session_id,
				reason_for_return: checkReasons[i]
				};
			});

			let checkIfHasQty = checkInputValues.get().filter(el => {
            	return el.product_quantity > 0;
          	});

			
			  if(checkIfHasQty.length == 0) {
            	$('#errorFields').text('Please check if you put a quantity or a valid reason.');
			} else {
				$('#errorFields').text('');
			}

			let sessionId = "";
            let productId = "";
            let productQty = "";

            let userFullName = $('#userFullName').text();
            let userContactNumber = $('#userContactNumber').text();
            let userHomeAddress = $('#userHomeAddress').text();

			let productsData = checkIfHasQty.map((el, i) => {
              sessionId = el.product_session_id;
              productId = el.product_id;
              productQty = el.product_quantity;

              return ` ('${userFullName}', '${userContactNumber}', '${userHomeAddress}', ${el.product_id}, '${el.product_name}', '${el.product_barcode}', ${el.product_quantity}, '${el.reason_for_return}', 1, '${el.product_session_id}')`;
            })

			$.ajax({
              url:"returns/return-ordered-items.php",
              method:"POST",
              data:{full_name: userFullName, contact_number: userContactNumber, home_address: userHomeAddress, product_id: productId, product_qty: productQty, session_id: sessionId, all_returned_items: productsData.toString()},
              success:function(data)
              {
				sessionStorage.setItem("showmsg", "1");
                location.replace("./?page=returns");
              }
            });

			// const date = new Date(); // Create a new Date object with the current date and time
			// const year = date.getFullYear();
			// const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Add leading zero if needed
			// const day = date.getDate().toString().padStart(2, '0');
			// const hours = date.getHours().toString().padStart(2, '0');
			// const minutes = date.getMinutes().toString().padStart(2, '0');
			// const seconds = date.getSeconds().toString().padStart(2, '0');
			// const formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

			// var productName = $('select#product_id option').filter(":selected").data("product_name");
			// var customerContactNumber = $('select#customer_name option').filter(":selected").data("contact");
			// var customerHomeAddress = $('select#customer_name option').filter(":selected").data("address");

			// $('input#product_name').val(productName);
			// $('input#customer_contact_number').val(customerContactNumber);
			// $('input#customer_home_address').val(customerHomeAddress);

			// $('input#session_id').val(generateRandomString(50));
			// $('input#datetime_added').val(formattedDate);
			
			// $.ajax({
			// 	url:_base_url_+"classes/Master.php?f=save_return",
			// 	data: new FormData($(this)[0]),
            //     cache: false,
            //     contentType: false,
            //     processData: false,
            //     method: 'POST',
            //     type: 'POST',
            //     dataType: 'json',
			// 	error:err=>{
			// 		console.log(err)
			// 		alert_toast("An error occured",'error');
			// 		end_loader();
			// 	},
			// 	success:function(resp){
			// 		console.log(resp);
			// 		if(typeof resp =='object' && resp.status == 'success'){
			// 			location.replace('./?page=returns')
			// 		}else if(resp.status == 'failed' && !!resp.msg){
            //             var el = $('<div>')
            //                 el.addClass("alert alert-danger err-msg").text(resp.msg)
            //                 _this.prepend(el)
            //                 el.show('slow')
            //                 $("html, body").scrollTop(0);
            //                 end_loader()
            //         }else{
			// 			alert_toast("An error occured",'error');
			// 			end_loader();
            //             console.log(resp)
			// 		}
			// 	}
			// })
		})
	})

	function change_quantity_up(prod_id, total_qty) {
        let old_quantity =  $('.js-items input.quantity' + prod_id);
        let new_quantity = parseFloat(old_quantity.val()) + 1;

        if(new_quantity <= 0) {
            new_quantity = 0;
            old_quantity.val(0);
        } else if(new_quantity > total_qty) {
            new_quantity = total_qty;
        } else {
            old_quantity.val(new_quantity);
        }
        
    }

    function change_quantity_down(prod_id, total_qty) {
        let old_quantity =  $('.js-items input.quantity' + prod_id);
        let new_quantity = parseFloat(old_quantity.val()) - 1;

        
        if(new_quantity <= 0) {
            new_quantity = 0;
            old_quantity.val(0);
        } else if(new_quantity > total_qty) {
            new_quantity = total_qty;
        } else {
            old_quantity.val(new_quantity);
        }

    }

	function calculate_item_quantity(prod_id, total_qty) {
		let old_quantity =  $('.js-items input.quantity' + prod_id);
		let new_quantity = parseFloat(old_quantity.val());

        
        if(new_quantity <= 0) {
			old_quantity.val(0);
        } else if(new_quantity > total_qty) {
			old_quantity.val(total_qty);
        } else if(isNaN(new_quantity)) {
            old_quantity.val(0);
		} else {
            old_quantity.val(new_quantity);
        }
	}
</script>