<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
        $link = "https"; 
    else
        $link = "http"; 

    $link .= "://"; 
    $link .= $_SERVER['HTTP_HOST']; 
    $link .= $_SERVER['REQUEST_URI'];

    if(!isset($_SESSION['userdata']) && !strpos($link, 'login.php')){
        redirect('login.php');
    }

    if(isset($_SESSION['userdata']) && strpos($link, 'login.php') && $_settings->userdata('login_type') == 1){
        redirect('users/admin');
    }
    if(isset($_SESSION['userdata']) && strpos($link, 'login.php') && $_settings->userdata('login_type') == 2){
        redirect('users/manager');
    }

    if(isset($_SESSION['userdata']) && strpos($link, 'login.php') && $_settings->userdata('login_type') == 3){
        redirect('users/cashier');
    }

    if(isset($_SESSION['userdata']) && strpos($link, 'login.php') && $_settings->userdata('login_type') == 4){
        redirect('eshop/index.php');
    } 
