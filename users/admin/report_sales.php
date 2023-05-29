<?php
    session_start();
    require_once ('../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $minDate = $_POST["minDate"];
    $maxDate = $_POST["maxDate"];

    $query = "SELECT 
    ROW_NUMBER() OVER(ORDER BY sti.product_id) AS id,
    name, SUM(sti.quantity) as total_quantity,
    ROUND(SUM((p.retail_price) * sti.quantity), 2) as total_revenue,
    ROUND(SUM((p.original_price) * sti.quantity), 2) as cost_of_goods,
    ROUND(SUM((p.retail_price) * sti.quantity) - SUM((p.original_price) * sti.quantity), 2) as gross_profit
    FROM products p
    JOIN sales_transactions_2_items sti ON p.id = sti.product_id
    JOIN sales_transactions_2 st ON st.session_id = sti.session_id
    WHERE DATE(sti.datetime_added) BETWEEN '".$minDate."' AND '".$maxDate."'
    GROUP BY p.code;";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
            {
                $return .= '
                <tr>
                    <td class="align-items-center text-center">'.$row['id'].'</td>
                    <td class="align-items-center">'.$row['name'].'</td>
                    <td class="align-items-center">'.$row['total_quantity'].'</td>
                    <td class="align-items-center">₱'.$row['total_revenue'].'</td>
                    <td class="align-items-center">₱'.$row['cost_of_goods'].'</td>
                    <td class="align-items-center">₱'.$row['gross_profit'].'</td>
                </tr>
                ';
            }
            echo $return;
    } else {
        // echo "No records found";
    }
?>