
<?php
    require_once ('../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $query = "SELECT a.* 
                FROM (
                    SELECT `id`, `name`, `retail_price`, `product_image`, `quantity`, `code` , ROW_NUMBER()OVER(PARTITION BY `code`) AS row_id
                    FROM `products`
                    WHERE quantity > 0
                    ORDER BY id ASC 
                ) a
                WHERE a.row_id = 1";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $return .= '
                    <tr>
                        <td class="image" data-title="No">
                        <p class="product-des text-center">
                        1
                        </p>
                    </td>
                    <td class="product-des" data-title="Description">
                        <p class="product-des text-center">
                        1681722691
                        </p>
                    </td>
                    <td class="price" data-title="Price">
                        <p class="product-des text-center">
                        2023-04-17 17:11:31
                        </p>
                    </td>
                    <td class="qty" data-title="Qty">
                        <p class="product-des text-center">
                        ₱115.5
                        </p>
                    </td>
                    <td class="total-amount" data-title="Total">
                        <p class="product-des text-center">
                        5 items
                        </p>
                    </td>
                    <td class="action" data-title="Remove">
                        <span class="badge badge-primary">Not Delivered</span>
                    </td>
                </tr>
            ';

            // $return .= '
            //         <div class="col-xl-3 col-lg-4 col-md-4 col-12">
            //         <div class="single-product">
            //             <div class="product-img" style="cursor: auto;">
            //                     <img class="default-img w-100 rounded border" src="' . base_url . $row['product_image'] .'" alt="#">
            //                 <div class="button-head">
            //                     <div class="product-action">
            //                         <button data-product_id="'. $row['id'] .'" data-product_total_quantity="'. $row['quantity'] .'"  class="add-to-cart-btn" style="padding: 10px 20px 10px 20px; margin: 0 auto;">Add to cart</button>
            //                     </div>
            //                 </div>
            //             </div>
            //             <div class="product-content">
            //                 <h3 class="product_name"><a href="product-details.html">' . $row['name'] . '</a></h3>
            //                 <div class="text-muted">
            //                     <span>' . $row['quantity'] . ' stocks left</span>
            //                 </div>
            //                 <div class="product-price retail_price">
            //                     ₱ ' . $row['retail_price'] . '
            //                 </div>
            //             </div>
            //         </div>
            //     </div>
            // ';
        }
        echo $return;
    }
    else
    {
        echo 'No products found.';
    }
?>
    
</script>
