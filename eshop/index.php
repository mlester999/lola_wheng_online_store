<?php 
	$page_access_no = 4;

	require_once('../config.php'); 
	require_once('../includes/sess_auth.php');

	$user = $conn->query("SELECT * FROM `accounts` where id ='".$_settings->userdata('id')."'");
	foreach($user->fetch_array() as $k =>$v){
		$meta[$k] = $v;
	}

	validate_page_access($_settings->userdata('login_type'), $page_access_no);
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
	<!-- Meta Tag -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name='copyright' content=''>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Title Tag  -->
    <title>Eshop - <?=$_SESSION['system_info']['name'];?></title>
	
	<!-- Favicon -->
	<!-- <link rel="icon" type="image/png" href="images/favicon.png"> -->
	<link rel="icon" href="<?php echo validate_image($_settings->info('logo')) ?>" />

	<!-- Web Font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
	
	<!-- StyleSheet -->
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Magnific Popup -->
    <link rel="stylesheet" href="css/magnific-popup.min.css">
	<!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.css">
	<!-- Fancybox -->
	<link rel="stylesheet" href="css/jquery.fancybox.min.css">
	<!-- Themify Icons -->
    <link rel="stylesheet" href="css/themify-icons.css">
	<!-- Nice Select CSS -->
    <link rel="stylesheet" href="css/niceselect.css">
	<!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
	<!-- Flex Slider CSS -->
    <link rel="stylesheet" href="css/flex-slider.min.css">
	<!-- Owl Carousel -->
    <link rel="stylesheet" href="css/owl-carousel.css">
	<!-- Slicknav -->
    <link rel="stylesheet" href="css/slicknav.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
	
	<!-- Eshop StyleSheet -->
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">

	
	
</head>
<body class="js">
	
	<!-- Preloader -->
	<div class="preloader">
		<div class="preloader-inner">
			<div class="preloader-icon">
				<span></span>
				<span></span>
			</div>
		</div>
	</div>
	<!-- End Preloader -->
	
	
	<!-- Header -->
	<header class="header shop">
		<!-- Topbar -->
		<?php if($_SESSION['system_info']['discount']): ?>
		<div class="topbar" style="background: #f6931d;">
			<p class="text-center" style="color: #fff; font-size: 16px;">Get <?php echo $_SESSION['system_info']['discount']; ?>% off everything! Shop now and save big!</p>
		</div>
		<?php endif; ?>
		<!-- End Topbar -->

		<div class="middle-inner">
			<div class="container">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-12">
						<!-- Logo -->
						<div class="logo">
							<a href="index.php">
								<!-- <img src="images/logo.png" alt="logo"> -->
								<img src="<?php echo base_url; ?>dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 60px;">
							</a>
						</div>
						<!--/ End Logo -->
						<!-- Search Form -->
						<div class="search-top">
							<div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
							<!-- Search Form -->
							<div class="search-top">
								<form class="search-form">
									<input type="text" placeholder="Search here..." name="search">
									<button value="search" type="submit"><i class="ti-search"></i></button>
								</form>
							</div>
							<!--/ End Search Form -->
						</div>
						<!--/ End Search Form -->
						<div class="mobile-nav"></div>
					</div>
					<div class="col-lg-8 col-md-7 col-12">
						<div class="search-bar-top">
							<div class="search-bar">
									<select name="category" id="category">
										<option selected="selected">All Category</option>
										<?php
											$qry = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY category_id ASC");
											
											if($qry->num_rows > 0) {
												while($row = $qry->fetch_assoc()) {
										?>
												<option><?php echo $row["category_name"]  ?></option>
										<?php 
												}
										?>
										<?php
											}
										?>
									</select>
								<form>
									<input name="search" id="search" placeholder="Search Products Here....." type="search">
									<button class="btnn"><i class="ti-search"></i></button>
								</form>
							</div>
						</div>
					</div>

					<div class="col-lg-2 col-md-3 col-12">
						<div class="right-bar">
							<!-- Search Form -->
							<div class="sinlge-bar d-none">
								<a href="#" class="single-icon"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
							</div>

							<div class="sinlge-bar account">
								<a href="#" class="single-icon"><i class="fa fa-user-circle-o" aria-hidden="true"></i></a>
								<!-- Account Details -->
								<div class="account-items">
									<div class="dropdown-account-header">
										<span>Hello, <?php echo $meta['full_name'] ?></span>
									</div>
									<ul class="account-list">
										<a href="my-orders.php" class="btn animate">My Orders</a>
										<a href="<?php echo base_url.'classes/Login.php?f=logout';?>" class="btn animate">Logout</a>
									</ul>
								</div>
								<!--/ End Account Details -->
							</div>

							<div class="sinlge-bar shopping">
								<a href="#" class="single-icon"><i class="ti-bag"></i> <span class="total-count js-item-count">0</span></a>
								<!-- Shopping Item -->
								<div class="shopping-item">
									<div class="dropdown-cart-header">
										<span class="js-item-count">0</span> <span>Items</span>
										<a href="#">Your Cart</a>
									</div>
									<ul class="shopping-list js-items">
										
									</ul>
									<div class="bottom">
										<div class="total js-gtotal">
											<span>Total</span>
											<span id="TotalAmount" class="total-amount">0.00</span>
											<span class="total-amount">₱</span>
										</div>
										<button id="checkoutCart" class="btn animate" style="margin: 0 auto;">Checkout</button>
									</div>
								</div>
								<!--/ End Shopping Item -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Header Inner -->
		<div class="header-inner">
			<div class="container">
				<div class="cat-nav-head">
					<div class="row">
						
						<div class="col-lg-9 col-12">
							<div class="menu-area">
								<!-- Main Menu -->
								<nav class="navbar navbar-expand-lg">
									<div class="navbar-collapse">	
										<div class="nav-inner">	
											<ul class="nav main-menu menu navbar-nav">
													<li><a href="#home">Home</a></li>
													<li><a href="#product">Product</a></li>											
													<li><a href="#contact">Contact Us</a></li>
												</ul>
										</div>
									</div>
								</nav>
								<!--/ End Main Menu -->	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/ End Header Inner -->
	</header>
	<!--/ End Header -->
	
	<!-- Start Product Area -->
    <div class="product-area section" id="home">
            <div class="container">
				<div class="row">
					<div class="col-12">
						<div class="section-title" id="product">
							<h2>Lola Wheng's Products</h2>
						</div>
						<div id="msgSuccess"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="product-info">
							<div class="tab-content" id="myTabContent">
								<!-- Start Single Tab -->
								<div class="tab-pane fade show active" id="man" role="tabpanel">
									<div class="tab-single">
										<div class="row ajax-res-items">
											
										</div>
									</div>
								</div>
								<!--/ End Single Tab -->
							</div>
						</div>
					</div>
				</div>
            </div>
    </div>
	<!-- End Product Area -->
	
	<!-- Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
                    </div>
                    <div class="modal-body shop checkout section">
                        <div class="row no-gutters">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <!-- Product Slider -->
								<div class="checkout-form py-2 px-4">
							<h2>Make Your Checkout Here</h2>
							<p style="margin-bottom: 16px;">Please put your information in order to checkout your orders.</p>
							<div id="msgError"></div>
							<!-- Form -->
							<form class="form" method="post" action="#">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-12">
										<div class="form-group">
											<label>First Name<span>*</span></label>
											<input type="text" name="first_name" id="first_name" value="<?php echo $meta['first_name'] ?>" required="required" style="pointer-events: none;">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-12">
										<div class="form-group">
											<label>Last Name<span>*</span></label>
											<input type="text" name="last_name" id="last_name" value="<?php echo $meta['last_name'] ?>" required="required" style="pointer-events: none;">
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-12">
										<div class="form-group">
											<label>Phone Number<span>*</span></label>
											<input type="number" name="phone_number" id="phone_number" value="<?php echo $meta['contact_number'] ?>" required="required" style="pointer-events: none;">
										</div>
									</div>
								
									<div class="col-lg-12 col-md-12 col-12">
										<div class="form-group">
											<label>Home Address<span>*</span></label>
											<input type="text" name="home_address" id="home_address" value="<?php echo $meta['home_address'] ?>" required="required">
										</div>
									</div>
								</div>
							</form>
							<!--/ End Form -->
						</div>
								<!-- End Product slider -->
                            </div>
                            <div class="col-lg-6 col-12 px-4 py-2">
							<div class="order-details">
							<!-- Order Widget -->
							<div class="single-widget">
								<h2>CART TOTALS</h2>
								<div class="content js-gtotal">
									<ul>
										<li>Sub Total<span id="TotalAmount" class="total-amount">0.00</span><span>₱</span></li>
										<li>(+) VAT<span>%</span><span id="TotalAmountVAT">0</span></li>
										<li>(+) Discount<span>%</span><span id="TotalAmountDiscount">0</span></li>
										<input type="hidden" id="TotalAmountShippingInput">
										<li>(+) Shipping<span id="TotalAmountShipping">0.00</span><span>₱</span></li>
										<li>Mode of Delivery<span id="ModeOfDeliveryText">
											<input type="radio" name="modeOfDelivery" id="forDelivery" value="3" checked="checked">
											<label for="forDelivery" style="margin-right: 6px;">For Delivery</label>
											
											<input type="radio" name="modeOfDelivery" id="forPickup" value="4">
											<label for="forPickup">For Pickup</label>
										</span></li>
										<li class="last">Total<span id="TotalAmountCheckout" class="total-amount">0.00</span><span>₱</span></li>
									</ul>
								</div>
							</div>
							<!--/ End Order Widget -->

							<!-- Button Widget -->
							<div class="single-widget get-button">
								<div class="content">
									<div class="button">
										<button onclick="submit_checkout()" class="btn">proceed to checkout</button>
									</div>
								</div>
							</div>
							<!--/ End Button Widget -->
						</div>
					</div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- Modal end -->
	
	<!-- Start Footer Area -->
	<footer class="footer" id="contact">
		<!-- Footer Top -->
		<div class="footer-top section">
			<div class="container">
				<div class="row">
					<div class="col-lg-5 col-md-6 col-12">
						<!-- Single Widget -->
						<div class="single-footer about">
							<div class="logo">
								<a href="#home"><img src="<?php echo base_url; ?>dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 60px;"></a>
							</div>
							<p class="text">Our commitment to excellence means that every item we sell is carefully selected to meet our rigorous standards for quality, taste, and freshness. We understand that food is an essential part of your daily life, and we take great care to ensure that every product we offer meets our high standards.</p>
							<p class="call">Got Question? Call us 24/7<span><a href="tel:123456789">+63 915 139 2944</a></span></p>
						</div>
						<!-- End Single Widget -->
					</div>
					<div class="col-lg-2 col-md-6 col-12">
						<!-- Single Widget -->
						<div class="single-footer links">
							<h4>Information</h4>
							<ul>
								<li><a href="#">About Us</a></li>
								<li><a href="#">Faq</a></li>
								<li><a href="#">Terms & Conditions</a></li>
								<li><a href="#">Contact Us</a></li>
								<li><a href="#">Help</a></li>
							</ul>
						</div>
						<!-- End Single Widget -->
					</div>
					<div class="col-lg-2 col-md-6 col-12">
						<!-- Single Widget -->
						<div class="single-footer links">
							<h4>Customer Service</h4>
							<ul>
								<li><a href="#">Payment Methods</a></li>
								<li><a href="#">Money-back</a></li>
								<li><a href="#">Returns</a></li>
								<li><a href="#">Shipping</a></li>
								<li><a href="#">Privacy Policy</a></li>
							</ul>
						</div>
						<!-- End Single Widget -->
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Single Widget -->
						<div class="single-footer social">
							<h4>Get In Tuch</h4>
							<!-- Single Widget -->
							<div class="contact">
								<ul>
									<li>Blk 16 Lot 74 Phase 1 Extension, Mamatid, Cabuyao.</li>
									<li>012 Philippines.</li>
									<li>lolawhengtransfi@gmail.com</li>
									<li>+032 3456 7890</li>
								</ul>
							</div>
							<!-- End Single Widget -->
							<ul>
								<li><a href="#"><i class="ti-facebook"></i></a></li>
								<li><a href="#"><i class="ti-twitter"></i></a></li>
								<li><a href="#"><i class="ti-flickr"></i></a></li>
								<li><a href="#"><i class="ti-instagram"></i></a></li>
							</ul>
						</div>
						<!-- End Single Widget -->
					</div>
				</div>
			</div>
		</div>
		<!-- End Footer Top -->
		<div class="copyright">
			<div class="container">
				<div class="inner">
					<div class="row">
						<div class="col-lg-6 col-12">
							<div class="left">
								<p>Copyright © 2023 Lola Wheng's E-Shop -  All Rights Reserved.</p>
							</div>
						</div>
						<div class="col-lg-6 col-12">
							<div class="right">
								<img src="images/payments.png" alt="#">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- /End Footer Area -->
 
	<!-- Jquery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.0.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<!-- Popper JS -->
	<script src="js/popper.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Color JS -->
	<script src="js/colors.js"></script>
	<!-- Slicknav JS -->
	<script src="js/slicknav.min.js"></script>
	<!-- Owl Carousel JS -->
	<script src="js/owl-carousel.js"></script>
	<!-- Magnific Popup JS -->
	<script src="js/magnific-popup.js"></script>
	<!-- Waypoints JS -->
	<script src="js/waypoints.min.js"></script>
	<!-- Countdown JS -->
	<script src="js/finalcountdown.min.js"></script>
	<!-- Nice Select JS -->
	<script src="js/nicesellect.js"></script>
	<!-- Flex Slider JS -->
	<script src="js/flex-slider.js"></script>
	<!-- ScrollUp JS -->
	<script src="js/scrollup.js"></script>
	<!-- Onepage Nav JS -->
	<script src="js/onepage-nav.min.js"></script>
	<!-- Easing JS -->
	<script src="js/easing.js"></script>
	<!-- Active JS -->
	<script src="js/active.js"></script>
	<!-- Toastr -->
	<script src="https:////cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

	
	<script>
	$(document).ready(function(){

		let searchParams = new URLSearchParams(window.location.search)

		if(searchParams.has('search'))
		{
			load_data(searchParams.get('search'));
		}
		else
		{
			load_data();
		}

		$('#msgError').html(`<div class="alert alert-danger">Please fill out all the fields.</div>`)
		$('#msgSuccess').html(`<div class="alert alert-success">Your order is now on process. Thank you for ordering!</div>`)
        $('#msgError').hide();
        $('#msgSuccess').hide();
		
		function load_data(query)
		{
			$.ajax({
				url:"./eshop-search-item.php",
                method:"POST",
                data:{query:query},
                success:function(data)
                {
					$('.ajax-res-items').html(data);
                }
			});

		}

	}); 

	$("#category").on('change', function () {
		var value = $(this).val();

		$.ajax({
			url:"./eshop-search-item.php",
			method:"POST",
			data:{category:value},
			success:function(data)
			{
				$('.ajax-res-items').html(data);
			}
		});
	});
	
		$('#checkoutCart').click(function() {
			if ($('.js-item-count').html() != 0) {
				$('#checkoutModal').modal('show');
			}
    	});	

		$("#checkoutModal input[name='modeOfDelivery']").change(function() {
			var chk_mode_of_delivery = $("#checkoutModal input[name='modeOfDelivery']:checked").val();

			if(chk_mode_of_delivery == 3) {
				var shippingValue = $('#checkoutModal input#TotalAmountShippingInput').val();
				var totalValue = Number($('#checkoutModal span#TotalAmountCheckout').html()) + Number(shippingValue);

				$('#checkoutModal span#TotalAmountShipping').text(shippingValue);
			
				$('#checkoutModal span#TotalAmountCheckout').text(totalValue.toFixed(2));
				
			} else {
				var shippingValue = $('#checkoutModal input#TotalAmountShippingInput').val();
				var totalValue = Number($('#checkoutModal span#TotalAmountCheckout').html()) - Number(shippingValue);

				$('#checkoutModal span#TotalAmountShipping').text('0.00');

				$('#checkoutModal span#TotalAmountCheckout').text(totalValue.toFixed(2));
			}
		});

		function submit_checkout() {
        // btn-submit-checkout
        var chk_customer_first_name = $('#checkoutModal input#first_name').val();
        var chk_customer_last_name = $('#checkoutModal input#last_name').val();
        var chk_customer_full_name = chk_customer_first_name + ' ' + chk_customer_last_name;
        var chk_customer_contact_number = $('#checkoutModal input#phone_number').val();
        var chk_customer_home_address = $('#checkoutModal input#home_address').val();
        var chk_total_amount = Number($('#checkoutModal span#TotalAmount').html());
        var chk_vat = Number($('#checkoutModal span#TotalAmountVAT').html());
        var chk_discount = Number($('#checkoutModal span#TotalAmountDiscount').html());
        var chk_delivery_fee = Number($('#checkoutModal span#TotalAmountShipping').html());
		var chk_total_rendered = Number($('#checkoutModal span#TotalAmountCheckout').html());
        var chk_change = Number(chk_total_rendered - chk_total_rendered);
		var chk_overall_total = Number($('#checkoutModal span#TotalAmountCheckout').html());
		var chk_mode_of_delivery = $("#checkoutModal input[name='modeOfDelivery']:checked").val();

        if(chk_customer_first_name && chk_customer_last_name && chk_customer_contact_number && chk_customer_home_address) {
			$.ajax({
            url: "./submit_checkout.php",
            type: "POST",
            data: {
                customer_id: 0,
                customer_name: chk_customer_full_name,
                customer_contact_number: chk_customer_contact_number,
                customer_home_address: chk_customer_home_address,
                total_amount: chk_total_amount,
                amount_rendered: chk_total_rendered,
                changed: chk_change,
                discount_pct: chk_discount,
                vat_pct: chk_vat,
                delivery_fee: chk_delivery_fee,
                overall_total: chk_overall_total,
				status: chk_mode_of_delivery
            },
            cache: false,
            success: function(dataResult) {
				empty_cart();
				
                // var dataResult = JSON.parse(dataResult);
                // if(dataResult.statusCode == 200) {
                //     // $('#msgSuccess').show();
                // } else {
                //     // alert('Something went wrong');
                // }
            }
        });

        $('#checkoutModal').hide();

		$('.modal-backdrop').remove()

        } else {
			$('#msgError').show();
		}
    }

	function empty_cart() {
        $.ajax({
            url: "./empty_cart.php",
            cache: false,
            success: function(dataResult1) {
                var dataResult1 = JSON.parse(dataResult1);
                if(dataResult1.statusCode == 200) {
                    // alert('Items/s added successfully.');
                    window.location.href = "my-orders.php";
                } else {
                    // alert('Something went wrong');
                }
            }
        });
    }

	// function search_product() {
	// 	var search = $(this).val();
	// 	if(search != '')
	// 	{
	// 		load_data(search);
	// 	}
	// 	else
	// 	{
	// 		load_data();
	// 	}
	// }
	</script>
</body>
</html>