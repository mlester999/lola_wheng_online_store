<?php
    session_start();
    require_once ('../../../initialize.php');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql_find = "SELECT * FROM accounts WHERE username = '$username' AND (type = '1' OR type = '2') ";

    if(mysqli_num_rows(mysqli_query($conn, $sql_find))) {
        $row = mysqli_fetch_assoc(mysqli_query($conn, $sql_find));
        
        if (password_verify($password, $row['password'])) {
            echo json_encode(array("statusCode"=>200));
        } else {
            echo json_encode(array("statusCode"=>201));
        }
    } else {
        echo json_encode(array("statusCode"=>201));
    }
    
?>