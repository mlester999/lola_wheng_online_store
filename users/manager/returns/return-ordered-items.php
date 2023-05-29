<?php
    session_start();

    require_once ('../../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $return = '';

    $fullName = $_POST["full_name"];
    $contactNumber = $_POST["contact_number"];
    $homeAddress = $_POST["home_address"];
    $sessionId = $_POST["session_id"];
    $productId = $_POST["product_id"];
    $productQty = $_POST["product_qty"];
    $allReturnedItems = $_POST["all_returned_items"];
    $status = 7;
    $forReturn = 1;

    $query = "INSERT INTO returned_orders (customer_name, customer_contact_number, customer_home_address, product_id, product_name, product_quantity, reason_for_return, `status`, session_id) VALUES".$allReturnedItems.";";

    $updateQuery = "UPDATE `sales_transactions_2` SET `status`='" . $status . "', `for_return`='" . $forReturn . "' WHERE session_id = '".$sessionId."'";

    mysqli_query($conn, $query);
    mysqli_query($conn, $updateQuery);

?>