<?php
    session_start();
    require_once ('../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = "";
    
    $query = "SELECT YEAR(`datetime_added`) AS `year` , 
    DATE_FORMAT(`datetime_added`, '%b') AS `month_name` , 
    s.`name` as `supplier_name`,
    SUM(`product_quantity`) AS `total_quantity` 
    FROM returned_orders ro
    JOIN products p ON ro.product_id = p.id
    JOIN suppliers s ON p.supplier_id = s.id
    WHERE YEAR(`datetime_added`) = 2023
    GROUP BY `supplier_name`, YEAR(`datetime_added`), MONTH(`datetime_added`) 
    ORDER BY MONTH(`datetime_added`), `datetime_added` ASC;";

    $result = mysqli_query($conn, $query);

    $returnToSuppliers = array();
    $colorCodes = array('#FF6969', '#FFD3B0', '#7C9070', '#F97B22', '#088395', 
    '#917FB3', '#F7D060', '#BFCCB5', '#3C486B', '#F0F0F0', '#B9EDDD', '#87CBB9', 
    '#263A29', '#D14D72', '#D21312', '#FFABAB', '#F9E2AF', '#009FBD', '#210062', 
    '#E8A0BF');

    if(mysqli_num_rows($result) > 0)
    {
        $count = 0;

        while($row = mysqli_fetch_array($result))
            {
                $returnObject = new stdClass();
                $returnObjectArr = array();
                
                array_push($returnObjectArr, intval($row['total_quantity']));

                $returnObject->label = $row['supplier_name'];
                $returnObject->backgroundColor = $colorCodes[$count];
                $returnObject->borderColor = $colorCodes[$count];
                $returnObject->pointBorderColor = $colorCodes[$count];
                $returnObject->pointBackgroundColor = $colorCodes[$count];
                $returnObject->data = $returnObjectArr;

                array_push($returnToSuppliers, $returnObject);

                $count++;
            }

            echo json_encode(array("returnToSupplier"=>$returnToSuppliers, "count"=>$count));
    } else {
        echo "No Return to Suppliers";
    }
    
?>