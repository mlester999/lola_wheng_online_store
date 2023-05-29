
<?php
    session_start();

    require_once ('../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $vat = $_SESSION['system_info']['vat'];
    $discount = $_SESSION['system_info']['discount'];
    $delivery_fee = $_SESSION['system_info']['delivery_fee'];

    if(isset($_POST["query"]))
    {
        $search = mysqli_real_escape_string($conn, $_POST["query"]);

        $query = "SELECT 
                `id`, `category_name`, `name`, `retail_price`, `product_image`, SUM(`quantity`) AS `quantity`, `unit_id`, `unit_size`, `measurement_code`, `code`, `date_added`,
                GROUP_CONCAT(`id`) AS `product_ids`, 
                MAX(`date_added`) AS `latest_date_added`
                FROM `products` a
                JOIN categories ctg ON ctg.category_id = a.category_id
                JOIN measurements msr ON msr.measurement_id = a.unit_id
                WHERE `quantity` > 0 AND (`name` LIKE '%" . $search."%' OR `description` LIKE '%" . $search."%');
                GROUP BY `code`, `retail_price`
                ORDER BY `name` ASC";
    }
    else if (isset($_POST["category"]))
    {
        $category = mysqli_real_escape_string($conn, $_POST["category"]);
        
        if($_POST["category"] == "All Category") {
            $query = "SELECT 
                    `id`, `category_name`, `name`, `retail_price`, `product_image`, SUM(`quantity`) AS `quantity`, `unit_id`, `unit_size`, `measurement_code`, `code`, `date_added`,
                    GROUP_CONCAT(`id`) AS `product_ids`, 
                    MAX(`date_added`) AS `latest_date_added`
                    FROM `products` a
                    JOIN categories ctg ON ctg.category_id = a.category_id
                    JOIN measurements msr ON msr.measurement_id = a.unit_id
                    WHERE `quantity` > 0
                    GROUP BY `code`, `retail_price`
                    ORDER BY `name` ASC";
        } else {

            $query = "SELECT 
                    `id`, `category_name`, `name`, `retail_price`, `product_image`, SUM(`quantity`) AS `quantity`, `unit_id`, `unit_size`, `measurement_code`, `code`, `date_added`,
                    GROUP_CONCAT(`id`) AS `product_ids`, 
                    MAX(`date_added`) AS `latest_date_added`
                    FROM `products` a
                    JOIN categories ctg ON ctg.category_id = a.category_id
                    JOIN measurements msr ON msr.measurement_id = a.unit_id
                    WHERE `quantity` > 0  AND (`category_name` LIKE '%" . $category . "%')
                    GROUP BY `code`, `retail_price`
                    ORDER BY `name` ASC";
        
        }

    }
    else if(isset($_POST["query"]) && isset($_POST["category"]))
    {
        $search = mysqli_real_escape_string($conn, $_POST["query"]);

        $category = mysqli_real_escape_string($conn, $_POST["category"]);

        $query = "SELECT 
                `id`, `category_name`, `name`, `retail_price`, `product_image`, SUM(`quantity`) AS `quantity`, `unit_id`, `unit_size`, `measurement_code`, `code`, `date_added`,
                GROUP_CONCAT(`id`) AS `product_ids`, 
                MAX(`date_added`) AS `latest_date_added`
                FROM `products` a
                JOIN categories ctg ON ctg.category_id = a.category_id
                JOIN measurements msr ON msr.measurement_id = a.unit_id
                WHERE `quantity` > 0  AND (`name` LIKE '%" . $search . "%' OR `description` LIKE '%" . $search . "%') AND (`category_name` LIKE '%" . $category . "%')
                GROUP BY `code`, `retail_price`
                ORDER BY `name` ASC";

    }
    else
    {
        $query = "SELECT 
                `id`, `category_name`, `name`, `retail_price`, `product_image`, SUM(`quantity`) AS `quantity`, `unit_id`, `unit_size`, `measurement_code`, `code`, `date_added`,
                GROUP_CONCAT(`id`) AS `product_ids`, 
                MAX(`date_added`) AS `latest_date_added`
                FROM `products` a
                JOIN categories ctg ON ctg.category_id = a.category_id
                JOIN measurements msr ON msr.measurement_id = a.unit_id
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
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                    <div class="single-product">
                        <div class="product-img" style="cursor: auto;">
                                <img class="default-img w-100 rounded border" src="' . base_url . $row['product_image'] .'" alt="#">
                            <div class="button-head">
                                <div class="product-action">
                                    <button data-product_vat="'. $vat .'" data-product_discount="'. $discount .'" data-product_delivery_fee="'. $delivery_fee .'" data-product_barcode="'. $row['code'] .'" data-product_id="'. $row['id'] .'" data-product_total_quantity="'. $row['quantity'] .'"  class="add-to-cart-btn" style="padding: 10px 20px 10px 20px; margin: 0 auto;">Add to cart</button>
                                </div>
                            </div>
                        </div>
                        <div class="product-content">
                            <h3 class="product_name"><a href="product-details.html">' . $row['name'] . '</a></h3>
                            <div class="text-muted">
                            <small class="badge badge-warning">' . $row['category_name'] . '</small>
                            <small class="badge badge-warning">' . $row['unit_size'] .' '  . $row['measurement_code'] . '</small>
                            </div>
                            <div class="text-muted">
                                <span>' . $row['quantity'] . ' stocks left</span>
                            </div>
                            <div class="product-price retail_price">
                                ₱ ' . number_format($row['retail_price'], 2) . '
                            </div>
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

    clear_all();

    $('.add-to-cart-btn').click(function() {
        let product_id = $(this).data('product_id');
        let product_barcode = $(this).data('product_barcode').toString();
        let product_vat = $(this).data('product_vat');
        let product_discount = $(this).data('product_discount');
        let product_delivery_fee = $(this).data('product_delivery_fee');
        let product_total_quantity = $(this).data('product_total_quantity');
        let quantity =  $('.js-items li div.product_name input.quantity' + product_id);

        if(quantity.val()) {
            let currentQuantityProduct = parseFloat(quantity.val()) + 1;
            
            if(currentQuantityProduct <= product_total_quantity) {

        if(cart_items(product_id)) {
            $('.js-items li div.product_name input.quantity' + product_id).val(parseFloat(quantity.val()) + 1);
            calculate_item_quantity(product_discount, product_vat, product_delivery_fee, product_barcode, product_id);
            add_to_cart(product_id, product_barcode);
        } else {
            var data = `
            <li onclick="cart_items(`+product_id+`)">
                <span class="d-none product_id" id="`+ product_id +`" data-product_id="` + product_id + `"> `+product_id+` </span>
                
                <div class="product_name">
                    <span onclick="remove_item(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+product_id+`)" class="remove" style="cursor: pointer;" title="Remove this item"><i class="fa fa-remove"></i></span>
                    <span onclick="change_quantity_down(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+ product_id +`)" class="minus" style="cursor: pointer;" title="Remove this item"><i class="fa fa-minus text-primary"></i></span>
                    <input onblur="calculate_item_quantity(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+product_id+`, `+ product_total_quantity +`)" onchange="calculate_item_quantity(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, `+ product_barcode +`, `+product_id+`, `+ product_total_quantity +`)" onkeyup="calculate_item_quantity(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, `+ product_barcode +`, `+product_id+`, `+ product_total_quantity +`)" type="text" class="form-control text-primary quantity`+product_id+`" placeholder="1" value="1" min="1" id="quantity">
                    <span onclick="change_quantity_up(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+ product_id +`, `+ product_total_quantity +`)" class="add" style="cursor: pointer;" title="Remove this item"><i class="fa fa-plus text-primary"></i></span>
                </div>

                <img src="` + $(this).parent().parent().parent().find('img').attr('src') + `" class="cart-img rounded border" style="width:70px;height:70px">
                
                <div class="retail_price`+product_id+` cart-row">
                    <h4><a href="#"><span class="item_product_name">` + $(this).parent().parent().parent().parent().children('.product-content').find('.product_name').text() + `</span></a></h4>
                    <p class="item-quantity`+product_id+`">₱<span class="amount retail_price" id="retail_price`+product_id+`">` + $(this).parent().parent().parent().parent().children('.product-content').find('.retail_price').text().replace('₱ ','') + `</span></p>
                    <input type="hidden" value="`+$(this).parent().parent().parent().parent().children('.product-content').find('.retail_price').text().replace('₱ ','')+`" step="0.01" id="retail_price`+product_id+`" class="total_retail_price">
                </div>
            </li>
            `;

            $('.js-items').append(data);
            calculate_item_quantity(product_discount, product_vat, product_delivery_fee, product_barcode, product_id);
            
            toastr.options = {
                "closeButton": true
            }
            
            toastr.success('Product added successfully');
            add_to_cart(product_id, product_barcode);
        }
    }
    } else {
        var data = `
            <li onclick="cart_items(`+product_id+`)">
                <span class="d-none product_id" id="`+ product_id +`" data-product_id="` + product_id + `"> `+product_id+` </span>
                
                <div class="product_name">
                    <span onclick="remove_item(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+product_id+`)" class="remove" style="cursor: pointer;" title="Remove this item"><i class="fa fa-remove"></i></span>
                    <span onclick="change_quantity_down(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+ product_id +`)" class="minus" style="cursor: pointer;" title="Remove this item"><i class="fa fa-minus text-primary"></i></span>
                    <input onblur="calculate_item_quantity(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+product_id+`, `+ product_total_quantity +`)" onchange="calculate_item_quantity(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, `+ product_barcode +`, `+product_id+`, `+ product_total_quantity +`)" onkeyup="calculate_item_quantity(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, `+ product_barcode +`, `+product_id+`, `+ product_total_quantity +`)" type="text" class="form-control text-primary quantity`+product_id+`" placeholder="1" value="1" min="1" id="quantity">
                    <span onclick="change_quantity_up(`+ product_discount +`, `+ product_vat +`, `+ product_delivery_fee +`, '`+product_barcode+`', `+ product_id +`, `+ product_total_quantity +`)" class="add" style="cursor: pointer;" title="Remove this item"><i class="fa fa-plus text-primary"></i></span>
                </div>

                <img src="` + $(this).parent().parent().parent().find('img').attr('src') + `" class="cart-img rounded border" style="width:70px;height:70px">
                
                <div class="retail_price`+product_id+` cart-row">
                    <h4><a href="#"><span class="item_product_name">` + $(this).parent().parent().parent().parent().children('.product-content').find('.product_name').text() + `</span></a></h4>
                    <p class="item-quantity`+product_id+`">₱<span class="amount retail_price" id="retail_price`+product_id+`">` + $(this).parent().parent().parent().parent().children('.product-content').find('.retail_price').text().replace('₱ ','') + `</span></p>
                    <input type="hidden" value="`+$(this).parent().parent().parent().parent().children('.product-content').find('.retail_price').text().replace('₱ ','')+`" step="0.01" id="retail_price`+product_id+`" class="total_retail_price">
                </div>
            </li>
            `;

            $('.js-items').append(data);
            calculate_item_quantity(product_discount, product_vat, product_delivery_fee, product_barcode, product_id);
            
            toastr.options = {
                "closeButton": true
            }
            
            toastr.success('Product added successfully');
            add_to_cart(product_id, product_barcode);
    }
    });


    function add_to_cart(prod_id, prod_barcode) {
        var product_id = prod_id;
        var product_barcode = prod_barcode;
        var quantity = $('.js-items li div.product_name input.quantity'+prod_id).val();

        $.ajax({
            url: "./add_to_cart.php",
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
                    
                } else {
                    // alert('Something went wrong');
                }
            }
        });
    }

    function delete_to_cart(barcode, prod_id) {
        var product_id = prod_id;
        var session_id = "<?php echo isset($_SESSION['cart_unique_key']) ? $_SESSION['cart_unique_key'] : '0'?>";

        $.ajax({
            url: "./delete_to_cart.php",
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

        $.ajax({
            url: "./empty_cart.php",
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
        var item_count = $('.js-items li span[id="'+id+'"]').length;
        
        if(item_count > 0) {    
                return true;
        }
    }

    function calculate_item_quantity(discount, vat, deliveryFee, barcode, id, totalQty) {
        let quantity =  $('.js-items li div.product_name input.quantity' + id);
        let new_quantity = quantity.val();
        let retail_price = $('.js-items li div input#retail_price'+id);
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
        $('.js-items li div span#retail_price'+id).text(item_total.toFixed(2));

        getTotal(discount, vat, deliveryFee);
        getTotalQuantity();
        add_to_cart(id, barcode);
    }

    function change_quantity_down(discount, vat, deliveryFee, barcode, id) {
        let old_quantity =  $('.js-items li div.product_name input.quantity' + id);
        let new_quantity = old_quantity.val() - 1;

        if(new_quantity == 0) {
            new_quantity = 1;
        } else {
            old_quantity.val(new_quantity);
        }

        calculate_item_quantity(discount, vat, deliveryFee, barcode, id);
        add_to_cart(id, barcode);
    }

    function change_quantity_up(discount, vat, deliveryFee, barcode, id, totalQty) {
        let old_quantity =  $('.js-items li div.product_name input.quantity' + id);
        let new_quantity = parseFloat(old_quantity.val()) + 1;

        if(new_quantity == 0) {
            new_quantity = 1;
        } else if(new_quantity > totalQty) {
            new_quantity = totalQty;
        } else {
            old_quantity.val(new_quantity);
        }

        calculate_item_quantity(discount, vat, deliveryFee, barcode, id);
        add_to_cart(id, barcode);
    }

    function getTotal(prod_discount, prod_vat, prod_delivery_fee) {
        var st = 0;
        var vatPercentage = prod_vat;
        var discountPercentage = prod_discount;
        var df = prod_delivery_fee;
        var subTotalWithDiscount;
        
        $('.cart-row').each(function(k) {
            var i           = $('.cart-row p span.retail_price')[k];
            var q           = parseFloat($(i).text());
            st          += (q);
        });
        
        var totalVat = parseFloat(vatPercentage / 100).toFixed(2) * st;
        var subTotalVat = Number(st + totalVat);

        if(discountPercentage) {
            subTotalWithDiscount = parseFloat(discountPercentage / 100).toFixed(2) * subTotalVat;
        } else {
            subTotalWithDiscount = 0;
        }

        // alert($('.cart-row span.retail_price').text());

        $('span#TotalAmount').text(st.toFixed(2));
        $('span#TotalAmountVAT').text(vatPercentage);
        $('span#TotalAmountDiscount').text(discountPercentage);
        $('span#TotalAmountShipping').text(df.toFixed(2));
        $('input#TotalAmountShippingInput').val(df.toFixed(2));
        $('span#TotalAmountCheckout').text(Number(subTotalVat - subTotalWithDiscount + df).toFixed(2));
        // $('span#TotalAmount').hide();
    }

    function getTotalQuantity() {
        tq = 0;
        $('div.product_name').each(function(k) {
            var i           = $('div.product_name input#quantity')[k];
            var q           = parseFloat($(i).val());
            tq          += (q);
        });

        $('span.js-item-count').text(tq);
    }


    function remove_item(discount, vat, deliveryFee, barcode, product_id) {
        $(".retail_price"+product_id).parent().remove();

        calculate_item_quantity(discount, vat, deliveryFee, barcode, product_id);
        delete_to_cart(barcode, product_id);
    }
    
</script>
