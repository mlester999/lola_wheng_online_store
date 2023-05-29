<?php
    require_once ('../../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $customerName = $_POST["customer_name"];

    $return = '';

    $query = "SELECT `transaction_number` from `sales_transactions_2` WHERE `customer_name` = '". $customerName ."' AND `for_return` = 0 ";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $return .= '
                <option id="'.$row['transaction_number'].'" value="'.$row['transaction_number'].'">'. $row['transaction_number'] .'</option>
            ';
        }

        echo $return;
    } else {
        echo "No transactions found";
    }
?>