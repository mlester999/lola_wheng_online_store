
<?php
    require_once ('../../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    if(isset($_POST["query"]))
    {
        $search = mysqli_real_escape_string($conn, $_POST["query"]);

        $query = "SELECT 
        `id`, `name`, `retail_price`, `product_image`, SUM(`quantity`) AS `quantity`, `code`, `date_added`,
        GROUP_CONCAT(`id`) AS `product_ids`, 
        MAX(`date_added`) AS `latest_date_added`
        FROM `products`
        WHERE `quantity` > 0  AND (`name` LIKE '%" . $search."%' OR `code` LIKE '%" . $search . "%')
        GROUP BY `code`, `retail_price`
        ORDER BY `name` ASC
        ";
    }
    else
    {
        $query = "SELECT 
        `id`, `name`, `retail_price`, `product_image`, SUM(`quantity`) AS `quantity`, `code`, `date_added`,
        GROUP_CONCAT(`id`) AS `product_ids`, 
        MAX(`date_added`) AS `latest_date_added`
    FROM `products`
    WHERE `quantity` > 0
    GROUP BY `code`, `retail_price`
    ORDER BY `name` ASC";
    }

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $return .= '
                <div class="col-md-6 col-lg-4 col-xl-3">
                        <div data-product_barcode="'. $row['code'] .'" data-product_id="'. $row['id'] .'" data-product_total_quantity="'. $row['quantity'] .'" class="single-product" style="cursor: pointer;">
                            <img src="' . base_url . $row['product_image'] .'" class="w-100 rounded border" >
                            <div class="p-2">
                                <div class="text-muted product_name">' . $row['name'] . '</div>
                                <div class="text-muted">' . $row['quantity'] .  ' stocks left</div>
                                <div class="retail_price" style="font-size:20px"><b>₱ ' . number_format($row['retail_price'], 2) . '</b></div>
                            </div>
                        </div>
                </div>
            ';
        }
        echo $return;
    }
    else
    {
        echo 'No products found.';
    }
?>

<script>
    var ITEMS 		= [];

    clear_all_cart_items();

    $('.single-product').click(function() {
        let product_id = $(this).data('product_id');
        let product_barcode = $(this).data('product_barcode');
        let product_total_quantity = $(this).data('product_total_quantity');
        let quantity =  $('.js-items tr td.product_name div input.quantity' + product_id);

        if(quantity.val()) {
            let currentQuantityProduct = parseFloat(quantity.val()) + 1;
            
            if(currentQuantityProduct <= product_total_quantity) {
                if(cart_items(product_id)) {
                    $('.js-items tr td.product_name div input.quantity' + product_id).val(parseFloat(quantity.val()) + 1);
                    calculate_item_quantity(product_barcode, product_id);
                    add_to_cart(product_id, product_barcode);
                } else {

                    var data = `
                            <tr onclick="cart_items(`+product_id+`)">
                                <td class="d-none product_id" id="`+ product_id +`" data-product_id="` + product_id + `"> `+product_id+` </td>
                                <td style="width:110px"><img src="` + $(this).find('img').attr('src') + `" class="rounded border" style="width:100px;height:100px"></td>
                                <td class="text-primary product_name">
                                    ` + $(this).find('.product_name').text() + `

                                    <div class="input-group my-3" style="max-width:150px">
                                        <span onclick="authenticate_before_decrease_item(`+product_barcode+`, `+product_id+`)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-minus text-primary"></i></span>
                                        <input onblur="calculate_item_quantity('`+product_barcode+`', `+product_id+`, `+ product_total_quantity +`)" onchange="calculate_item_quantity(`+product_id+`, `+ product_total_quantity +`)" onkeyup="calculate_item_quantity(`+product_id+`, `+ product_total_quantity +`)" type="text" class="form-control text-primary quantity`+product_id+`" placeholder="1" value="1" min="1" id="quantity">
                                        <span onclick="change_quantity_up('`+product_barcode+`', `+ product_id +`, `+ product_total_quantity +`)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-plus text-primary"></i></span>
                                    </div>

                                </td>
                                <td style="font-size:20px" class="retail_price`+product_id+` cart-row">
                                    <b>₱ <span id="retail_price`+product_id+`" class="retail_price">` + $(this).find('.retail_price').text().replace('₱ ','') + `</span> </b>
                                    <input type="hidden" value="`+$(this).find('.retail_price').text().replace('₱ ','')+`" step="0.01" id="retail_price`+product_id+`" class="total_retail_price">
                                    <button onclick="authenticate_before_remove_item(`+product_barcode+`, `+product_id+`)" class="float-end btn btn-danger btn-sm remove-item"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `;

                    $('.js-items').append(data);
                    calculate_item_quantity(product_barcode, product_id);
                    add_to_cart(product_id, product_barcode);
                    }
            }
        } else {
            var data = `
                            <tr onclick="cart_items(`+product_id+`)">
                                <td class="d-none product_id" id="`+ product_id +`" data-product_id="` + product_id + `"> `+product_id+` </td>
                                <td style="width:110px"><img src="` + $(this).find('img').attr('src') + `" class="rounded border" style="width:100px;height:100px"></td>
                                <td class="text-primary product_name">
                                    ` + $(this).find('.product_name').text() + `

                                    <div class="input-group my-3" style="max-width:150px">
                                        <span onclick="authenticate_before_decrease_item('`+product_barcode+`', `+product_id+`)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-minus text-primary"></i></span>
                                        <input onblur="calculate_item_quantity('`+product_barcode+`', `+product_id+`, `+ product_total_quantity +`)" onchange="calculate_item_quantity(`+product_id+`, `+ product_total_quantity +`)" onkeyup="calculate_item_quantity(`+product_id+`, `+ product_total_quantity +`)" type="text" class="form-control text-primary quantity`+product_id+`" placeholder="1" value="1" min="1" id="quantity" disabled>
                                        <span onclick="change_quantity_up('`+product_barcode+`', `+ product_id +`, `+ product_total_quantity +`)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-plus text-primary"></i></span>
                                    </div>

                                </td>
                                <td style="font-size:20px" class="retail_price`+product_id+` cart-row">
                                    <b>₱ <span id="retail_price`+product_id+`" class="retail_price">` + $(this).find('.retail_price').text().replace('₱ ','') + `</span> </b>
                                    <input type="hidden" value="`+$(this).find('.retail_price').text().replace('₱ ','')+`" step="0.01" id="retail_price`+product_id+`" class="total_retail_price">
                                    <button onclick="authenticate_before_remove_item(`+product_barcode+`, `+product_id+`)" class="float-end btn btn-danger btn-sm remove-item"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        `;

                    $('.js-items').append(data);
                    calculate_item_quantity(product_barcode, product_id);
                    add_to_cart(product_id, product_barcode);
        }
    });


    function add_to_cart(prod_id, prod_barcode) {
        var product_id = prod_id;
        var product_barcode = prod_barcode;
        var quantity = $('.js-items td input.quantity'+prod_id).val();

        $.ajax({
            url: "pos/add_to_cart.php",
            type: "POST",
            data: {
                product_id: product_id,
                product_barcode: product_barcode,
                quantity: quantity,
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
    }

    function delete_to_cart(prod_id) {
        var product_id = prod_id;
        var session_id = "<?php echo isset($_SESSION['cart_unique_key']) ? $_SESSION['cart_unique_key'] : '0'?>";

        $.ajax({
            url: "pos/delete_to_cart.php",
            type: "POST",
            data: {
                product_id: product_id,
                session_id: session_id,
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
    }

    function refresh_items_display()
	{

		var item_count = document.querySelector(".js-item-count");
		item_count.innerHTML = ITEMS.length;

		var items_div = document.querySelector(".js-items");
		items_div.innerHTML = "";
		var grand_total = 0;

		for (var i = ITEMS.length - 1; i >= 0; i--) {

			items_div.innerHTML += item_html(ITEMS[i],i);
			grand_total += (ITEMS[i].qty * ITEMS[i].amount);
		}
		
		var gtotal_div = document.querySelector(".js-gtotal");
		gtotal_div.innerHTML = "Total: ₱ " + grand_total.toFixed(2);
		GTOTAL = grand_total;
	}



    function clear_all()
	{

		ITEMS = [];
		refresh_items_display();

        $.ajax({
            url: "pos/empty_cart.php",
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

        location.reload();
	}

    function clear_all_cart_items()
	{
		ITEMS = [];

        $.ajax({
            url: "pos/empty_cart.php",
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
	}

    function reset_all()
	{
        ITEMS = [];
		refresh_items_display();

        $.ajax({
            url: "pos/empty_cart.php",
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

        location.reload();
    }

    function cart_items(id) {
        var item_count = $('.js-items tr td[id="'+id+'"]').length;
        
        if(item_count > 0) {    
                return true;
        }
    }

    function calculate_item_quantity(barcode, id, totalQty) {
        let quantity =  $('.js-items tr td.product_name div input.quantity' + id);
        let new_quantity = quantity.val();
        let retail_price = $('.js-items tr td input#retail_price'+id);
        let item_total = 0;

        if(new_quantity <= 0 || !Number(quantity.val())) {
            new_quantity = 1;
            quantity.val(1);
        } else if(new_quantity > totalQty) {
            new_quantity = totalQty;
            quantity.val(totalQty);
        } else {
            quantity.val(new_quantity);
        }

        item_total = parseFloat(new_quantity) * parseFloat(retail_price.val());
        $('.js-items tr td span#retail_price'+id).text(item_total.toFixed(2));

        getTotal();
        getTotalQuantity();
        add_to_cart(id, barcode);
    }

    function change_quantity_down(barcode, id) {
        let old_quantity =  $('.js-items tr td.product_name div input.quantity' + id);
        let new_quantity = old_quantity.val() - 1;

        if(new_quantity == 0) {
            new_quantity = 1;
        } else {
            old_quantity.val(new_quantity);
        }

        calculate_item_quantity(barcode, id);
        add_to_cart(id, barcode);
    }

    function change_quantity_up(barcode, id, totalQty) {
        let old_quantity =  $('.js-items tr td.product_name div input.quantity' + id);
        let new_quantity = parseFloat(old_quantity.val()) + 1;

        if(new_quantity == 0) {
            new_quantity = 1;
        } else if(new_quantity > totalQty) {
            new_quantity = totalQty;
        } else {
            old_quantity.val(new_quantity);
        }

        calculate_item_quantity(barcode, id);
        add_to_cart(id, barcode);
    }

    function getTotal() {
        var st = 0;
        $('.cart-row').each(function(k) {
            var i           = $('.cart-row span.retail_price')[k];
            var q           = parseFloat($(i).text());
            st          += (q);
        });

        // alert($('.cart-row span.retail_price').text());

        $('span#TotalAmount').text(st.toFixed(2));
        // $('span#TotalAmount').hide();
    }

    function getTotalQuantity() {
        tq = 0;
        $('td.product_name').each(function(k) {
            var i           = $('td.product_name input#quantity')[k];
            var q           = parseFloat($(i).val());
            tq          += (q);
        });

        $('div.js-item-count').text(tq);

        return tq;
    }


    function remove_item(barcode, product_id) {
        $(".retail_price"+product_id).parent().remove();

        calculate_item_quantity(barcode, product_id);
        delete_to_cart(product_id);
    }
    
</script>
