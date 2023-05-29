<?php
    ob_start();
    ini_set('date.timezone','Asia/Manila');
    date_default_timezone_set('Asia/Manila');
    session_start();

    require_once('initialize.php');
    require_once('classes/DBConnection.php');
    require_once('classes/SystemSettings.php');

    $db = new DBConnection;
    $conn = $db->conn;
    function redirect($url=''){
        if(!empty($url))
        echo '<script>location.href="'.base_url .$url.'"</script>';
    }
    function validate_image($file){
        global $_settings;
        if(!empty($file)){
                // exit;
            $ex = explode("?",$file);
            $file = $ex[0];
            $ts = isset($ex[1]) ? "?".$ex[1] : '';
            if(is_file(base_app.$file)){
                return base_url.$file.$ts;
            }else{
                return base_url.($_settings->info('logo'));
            }
        }else{
            return base_url . 'uploads/product_placeholder.png';
        }
    }
    function format_num($number = '' , $decimal = ''){
        if(is_numeric($number)){
            $ex = explode(".",$number);
            $decLen = isset($ex[1]) ? strlen($ex[1]) : 0;
            if(is_numeric($decimal)){
                return number_format($number,$decimal);
            }else{
                return number_format($number,$decLen);
            }
        }else{
            return "Invalid Input";
        }
    }
    
    function isMobileDevice(){
        $aMobileUA = array(
            '/iphone/i' => 'iPhone', 
            '/ipod/i' => 'iPod', 
            '/ipad/i' => 'iPad', 
            '/android/i' => 'Android', 
            '/blackberry/i' => 'BlackBerry', 
            '/webos/i' => 'Mobile'
        );

        //Return true if Mobile User Agent is detected
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
                return true;
            }
    }
    //Otherwise return false..  
    return false;
    }

    function validate_page_access($user_login_type, $page_access) {
        if($user_login_type == 1 && $page_access != 1) {
            redirect('users/admin');
        }

        if($user_login_type == 2 && $page_access != 2) {
            redirect('users/manager');
        }

        if($user_login_type == 3 && $page_access != 3) {
            redirect('users/cashier');
        }

        if($user_login_type == 4 && $page_access != 4) {
            redirect('eshop/');
        }
    }
ob_end_flush();
?>