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

    $query = "SELECT ROW_NUMBER() OVER(ORDER BY ro.id) AS `id`, YEAR(`datetime_added`) AS `year` , DATE_FORMAT(`datetime_added`, '%b') AS `month_name` , s.`name` as `supplier_name`, SUM(`product_quantity`) AS `total_quantity` FROM returned_orders ro JOIN products p ON ro.product_id = p.id JOIN suppliers s ON p.supplier_id = s.id WHERE DATE(datetime_added) BETWEEN '" . $minDate . "' AND '" . $maxDate . "' GROUP BY `supplier_name`, YEAR(`datetime_added`), MONTH(`datetime_added`) ORDER BY MONTH(`datetime_added`), `datetime_added` ASC;";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
            {
                $return .= '
                <tr>
                    <td class="align-items-center text-center">'.$row['id'].'</td>
                    <td class="align-items-center">'.$row['supplier_name'].'</td>
                    <td class="align-items-center">'.$row['total_quantity'].'</td>
                </tr>
                ';
            }
            echo $return;
    } else {
        // echo "No records found";
    }

?>