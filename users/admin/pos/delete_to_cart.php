<?php
    session_start();
    require_once ('../../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $session_id = $_SESSION['cart_unique_key'];
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['userdata']['id'];

    $sql_AddToCart = "DELETE FROM `cart` WHERE session_id = '$session_id' AND product_id='$product_id' AND user_id='$user_id' LIMIT 1";
    
    if(mysqli_query($conn,$sql_AddToCart)) {
        echo json_encode(array("statusCode"=>200));
    } else {
        echo json_encode(array("statusCode"=>201));
    }
    
?>