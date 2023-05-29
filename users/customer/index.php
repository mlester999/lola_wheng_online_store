<?php 
	$page_access_no = 5;

	require_once('../../config.php'); 
	require_once('../../includes/sess_auth.php');

	validate_page_access($_settings->userdata('login_type'), $page_access_no);
?>

