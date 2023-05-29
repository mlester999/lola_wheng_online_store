<?php
    session_start();
    require_once ('../../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    if(isset($_SESSION['cart_unique_key']) == FALSE || empty($_SESSION['cart_unique_key'])) {
        $_SESSION['cart_unique_key'] = get_random_string_max(50);
    }

    $session_id = $_SESSION['cart_unique_key'];
    $product_id = $_POST['product_id'];
    $product_barcode = $_POST['product_barcode'];
    $quantity = $_POST['quantity'];
    $unique_key = $session_id . '-' . $product_id;
    $user_id = $_SESSION['userdata']['id'];
    $datetime_added = date('Y-m-d H:i:s');

    $sql_AddToCart = "INSERT INTO `cart` (session_id, product_id, product_barcode, quantity, unique_key, user_id) VALUES ('$session_id','$product_id','$product_barcode','$quantity', '$unique_key', '$user_id') ON DUPLICATE KEY UPDATE session_id='$session_id', product_id='$product_id', product_barcode='$product_barcode', quantity='$quantity', datetime_added='$datetime_added'";
    if(mysqli_query($conn,$sql_AddToCart)) {
        echo json_encode(array("statusCode"=>200));
    } else {
        echo json_encode(array("statusCode"=>201));
    }



    function get_random_string_max($length) {

        $array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $text = "";
    
        $length = rand(4,$length);
    
        for($i=0;$i<$length;$i++) {
    
            $random = rand(0,61);
            
            $text .= $array[$random];
    
        }

        return $text;
    }
    
?>