<?php
    session_start();

    require_once ('../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $transactionNumber = $_POST["transaction_number"];
    $status = $_POST["order_received"];

    $query = "UPDATE `sales_transactions_2` SET `order_received`='" . $status . "' WHERE transaction_number = '".$transactionNumber."'";

    $result = mysqli_query($conn, $query);

?>