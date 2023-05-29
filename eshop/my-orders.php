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
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="copyright" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <!-- Title Tag  -->
    <title>Eshop - <?=$_SESSION['system_info']['name'];?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo validate_image($_settings->info('logo')) ?>" />
    
    <!-- Web Font -->
    <link
      href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
      rel="stylesheet"
    />

    <!-- StyleSheet -->

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.css" />
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="css/magnific-popup.min.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.css" />
    <!-- Fancybox -->
    <link rel="stylesheet" href="css/jquery.fancybox.min.css" />
    <!-- Themify Icons -->
    <link rel="stylesheet" href="css/themify-icons.css" />
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="css/niceselect.css" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css/animate.css" />
    <!-- Flex Slider CSS -->
    <link rel="stylesheet" href="css/flex-slider.min.css" />
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="css/owl-carousel.css" />
    <!-- Slicknav -->
    <link rel="stylesheet" href="css/slicknav.min.css" />
    <!-- Toastr -->
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="<?php echo base_url; ?>plugins/sweetalert2/sweetalert2.min.css">

    <!-- Eshop StyleSheet -->
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="css/responsive.css" />
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

        <p class="d-none" id="userFullName"><?php echo $meta['full_name'] ?></p>
        <p class="d-none" id="userContactNumber"><?php echo $meta['contact_number'] ?></p>
        <p class="d-none" id="userHomeAddress"><?php echo $meta['home_address'] ?></p>

      <div class="middle-inner">
        <div class="container">
          <div class="row">
            <div class="col-lg-2 col-md-2 col-12">
              <!-- Logo -->
              <div class="logo">
                <a href="index.php"
                  ><img src="<?php echo base_url; ?>dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 60px;"></a>
              </div>
              <!--/ End Logo -->
              <!-- Search Form -->
              <div class="search-top">
                <div class="top-search">
                  <a href="#0"><i class="ti-search"></i></a>
                </div>
                <!-- Search Form -->
                <div class="search-top">
                  <form class="search-form">
                    <input
                      type="text"
                      placeholder="Search here..."
                      name="search"
                    />
                    <button value="search" type="submit">
                      <i class="ti-search"></i>
                    </button>
                  </form>
                </div>
                <!--/ End Search Form -->
              </div>
              <!--/ End Search Form -->
              <div class="mobile-nav"></div>
            </div>
            <div class="col-lg-8 col-md-7 col-12">
              <div class="search-bar-top"></div>
            </div>
            <div class="col-lg-2 col-md-3 col-12">
              <div class="right-bar">
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!--/ End Header -->

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="bread-inner">
              <ul class="bread-list">
                <li>
                  <a href="index.php">Home<i class="ti-arrow-right"></i></a>
                </li>
                <li class="active"><a href="#">My Orders</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Shopping Cart -->
    <div class="shopping-cart section">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <!-- Shopping Summery -->
            <table class="table shopping-summery">
              <thead>
                <tr class="main-hading">
                  <th>#</th>
                  <th style="width: 250px;">TRANSACTION NUMBER</th>
                  <th class="text-center">TRANSACTION DATE</th>
                  <th class="text-center">OVERALL TOTAL</th>
                  <th class="text-center">TOTAL ORDERS</th>
                  <th class="text-center">
                    STATUS
                  </th>
                  <th class="text-center">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody>
              <?php 
                  $i = 0;
                  $qry = $conn->query("SELECT s.transaction_id
                                              , s.customer_id
                                              , s.customer_name
                                              , s.transaction_number
                                              , s.session_id
                                              , s.total_amount
                                              , s.amount_rendered
                                              , s.changed
                                              , s.discount_pct
                                              , s.discount_pct
                                              , s.datetime_checkedout
                                              , s.order_received
                                              , s.for_return
                                              , SUM(s2.quantity) AS total_orders
                                              , s.status
                                      FROM `sales_transactions_2` s
                                          JOIN sales_transactions_2_items s2 ON s.session_id = s2.session_id
                                      WHERE s.status IN (0,1,2,3,4,5,6,7,8,9) AND s2.user_id IN (".$meta['id'].")
                                      GROUP BY s.transaction_id
                                              , s.customer_id
                                              , s.customer_name
                                              , s.transaction_number
                                              , s.session_id
                                              , s.total_amount
                                              , s.amount_rendered
                                              , s.changed
                                              , s.discount_pct
                                              , s.discount_pct
                                              , s.datetime_checkedout
                                              , s.status
                                      ORDER BY s.datetime_checkedout DESC
                                  ");
                  if($qry->num_rows > 0) {
                      while($row = $qry->fetch_assoc()) {
                        $i++;
                  ?>
                  <tr>
                        <td class="image" data-title="No">
                        <p class="product-des text-center">
                        <?php echo $i; ?>
                        </p>
                    </td>
                    <td class="d-none" data-title="Session">
                        <p class="product-des text-center" id="productSessionId">
                        <?php echo $row['session_id']; ?>
                        </p>
                    </td>
                    <td class="product-des" data-title="Description">
                        <p class="product-des text-center" id="productTransactionNumber">
                        <?php echo $row['transaction_number']; ?>
                        </p>
                    </td>
                    <td class="price" data-title="Price">
                        <p class="product-des text-center">
                        <?php echo date('Y-m-d', strtotime($row['datetime_checkedout'])); ?>
                        </p>
                    </td>
                    <td class="qty" data-title="Qty">
                        <p class="product-des text-center">
                        ₱<?php echo $row['total_amount']; ?>
                        </p>
                    </td>
                    <td class="total-amount" data-title="Total">
                        <p class="product-des text-center">
                        <?php echo $row['total_orders'];?>  <?php echo $row['total_orders'] > 0 ? 'items':'item'; ?>
                        </p>
                    </td>
                    <td class="action" data-title="Status">
                    <?php if($row['status'] == 0): ?>
                        <span class="badge badge-primary px-3 rounded-pill">Not Delivered</span>
                    <?php elseif($row['status'] == 1): ?>  
                        <span class="badge badge-success px-3 rounded-pill">Delivered</span>
                    <?php elseif($row['status'] == 2): ?>  
                        <span class="badge badge-danger px-3 rounded-pill">Cancelled</span>
                    <?php elseif($row['status'] == 3): ?>  
                        <span class="badge badge-info px-3 rounded-pill">For Delivery</span>
                    <?php elseif($row['status'] == 4): ?>  
                        <span class="badge badge-dark px-3 rounded-pill">For Pick-up</span>
                    <?php elseif($row['status'] == 5): ?>  
                        <span class="badge badge-success px-3 rounded-pill">Picked Up</span>
                    <?php elseif($row['status'] == 6): ?>  
                    <span class="badge badge-secondary px-3 rounded-pill">For Return (Pending)</span>
                    <?php elseif($row['status'] == 7): ?>  
                    <span class="badge badge-primary px-3 rounded-pill">For Return (In Progress)</span>
                    <?php elseif($row['status'] == 8): ?>  
                    <span class="badge badge-primary px-3 rounded-pill">For Return (Out For Delivery)</span>
                    <?php elseif($row['status'] == 9): ?>  
                    <span class="badge badge-danger px-3 rounded-pill">For Return (Not Approved)</span>
                    <?php endif; ?>
                    </td>
                    <td align="center" class="action" data-title="Action">
                    <button type="button" class="px-3 btn btn-flat rounded btn-default btn-xs dropdown-toggle dropdown-icon bg-secondary" data-toggle="dropdown">
                        Action
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <?php

                    $datetime_added = $row['datetime_checkedout']; // example datetime_added

                    $datetime_now = new DateTime(); // current date and time
                    $datetime_added = new DateTime($datetime_added); // convert datetime_added to DateTime object

                    $interval = $datetime_now->diff($datetime_added); // calculate the difference between datetime_now and datetime_added
                    ?>
                    <div class="dropdown-menu p-0" role="menu">
                    <?php if($row['status'] == 1 && $row['order_received'] == 0): ?>
                      <button class="dropdown-item py-3 viewOrder" id="viewOrder" data-session_id="<?php echo $row['session_id']; ?>" data-transaction_number="<?php echo $row['transaction_number']; ?>"><span class="fa fa-edit text-dark"></span> View Order</button>
                      <button class="dropdown-item py-3 orderReceived" id="orderReceived" data-transaction_number="<?php echo $row['transaction_number']; ?>" style="cursor: <?php echo ($interval->days >= 7 ? ' not-allowed' : 'cursor'); ?>;" <?php echo ($interval->days >= 7 ? 'disabled' : ''); ?>><span class="fa fa-edit text-dark"></span> Order Received</button>
                      <button class="dropdown-item py-3 forReturn" id="forReturn" data-session_id="<?php echo $row['session_id']; ?>" style="cursor: <?php echo ($interval->days >= 7 ? ' not-allowed' : 'cursor'); ?>;" <?php echo ($interval->days >= 7 ? 'disabled' : ''); ?>><span class="fa fa-edit text-dark"></span> Return</button>
                    <?php elseif($row['status'] == 5 && $row['order_received'] == 0): ?>
                      <button class="dropdown-item py-3 viewOrder" id="viewOrder" data-session_id="<?php echo $row['session_id']; ?>" data-transaction_number="<?php echo $row['transaction_number']; ?>"><span class="fa fa-edit text-dark"></span> View Order</button>
                      <button class="dropdown-item py-3 orderReceived" id="orderReceived" data-transaction_number="<?php echo $row['transaction_number']; ?>" style="cursor: <?php echo ($interval->days >= 7 ? ' not-allowed' : 'cursor'); ?>;" <?php echo ($interval->days >= 7 ? 'disabled' : ''); ?>><span class="fa fa-edit text-dark"></span> Order Received</button>
                      <button class="dropdown-item py-3 forReturn" id="forReturn" data-session_id="<?php echo $row['session_id']; ?>" style="cursor: <?php echo ($interval->days >= 7 ? ' not-allowed' : 'cursor'); ?>;" <?php echo ($interval->days >= 7 ? 'disabled' : ''); ?>><span class="fa fa-edit text-dark"></span> Return</button>
                    <?php elseif($row['status'] == 1 && $row['order_received'] == 1): ?>
                      <button class="dropdown-item py-3 viewOrder" id="viewOrder" data-session_id="<?php echo $row['session_id']; ?>" data-transaction_number="<?php echo $row['transaction_number']; ?>"><span class="fa fa-edit text-dark"></span> View Order</button>
                      <button class="dropdown-item py-3 orderReceived" id="orderReceived" data-transaction_number="<?php echo $row['transaction_number']; ?>" style="cursor: not-allowed;" disabled><span class="fa fa-edit text-dark"></span> Order Received</button>
                      <button class="dropdown-item py-3 forReturn" id="forReturn" data-session_id="<?php echo $row['session_id']; ?>" style="cursor: <?php echo ($interval->days >= 7 ? ' not-allowed' : 'cursor'); ?>;" <?php echo ($interval->days >= 7 ? 'disabled' : ''); ?>><span class="fa fa-edit text-dark"></span> Return</button>
                    <?php elseif($row['status'] == 5 && $row['order_received'] == 1): ?>
                      <button class="dropdown-item py-3 viewOrder" id="viewOrder" data-session_id="<?php echo $row['session_id']; ?>" data-transaction_number="<?php echo $row['transaction_number']; ?>"><span class="fa fa-edit text-dark"></span> View Order</button>
                      <button class="dropdown-item py-3 orderReceived" id="orderReceived" data-transaction_number="<?php echo $row['transaction_number']; ?>" style="cursor: not-allowed;" disabled><span class="fa fa-edit text-dark"></span> Order Received</button>
                      <button class="dropdown-item py-3 forReturn" id="forReturn" data-session_id="<?php echo $row['session_id']; ?>" style="cursor: <?php echo ($interval->days >= 7 ? ' not-allowed' : 'cursor'); ?>;" <?php echo ($interval->days >= 7 ? 'disabled' : ''); ?>><span class="fa fa-edit text-dark"></span> Return</button>
                    <?php else: ?>
                      <button class="dropdown-item py-3 viewOrder" id="viewOrder" data-session_id="<?php echo $row['session_id']; ?>" data-transaction_number="<?php echo $row['transaction_number']; ?>"><span class="fa fa-edit text-dark"></span> View Order</button>
                      <button class="dropdown-item py-3" style="cursor: not-allowed;" disabled><span class="fa fa-edit text-dark"></span> Order Received</button>
                      <button class="dropdown-item py-3" style="cursor: not-allowed;" disabled><span class="fa fa-edit text-dark"></span> Return</button>
                    <?php endif; ?>
                    </div>
								</td>
                </tr>
                <?php 
                    }
            ?>
            <?php
                } else {
            ?>
                <tr>
                  <td class="text-center" colspan="7">No orders found</td>
                </tr>
            <?php
              }
            ?>
              </tbody>
            </table>
            <!--/ End Shopping Summery -->
          </div>
        </div>
      </div>
    </div>
    <!--/ End Shopping Cart -->

    <!-- View Order Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" style="width: 600px;" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
                    </div>
                    <div class="modal-body shop checkout section">
                        <div class="row no-gutters">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <!-- Product Slider -->
                                  <div class="checkout-form py-2 px-4">
                                <h2>View Ordered Items</h2>
                                <p style="margin-bottom: 16px;">Here are all the items that you ordered.</p>
                                
                        <header class="header shop">
                          <div>
                            <div class="container">
                              <div class="row">
                                <div class="col-lg-12 col-md-12 col-12" style="padding: 15px 0;">
                                  <div>
                                    <div class="sinlge-bar shopping" style="width: 100%;">
                                      <div class="dropdown-cart-header">
                                        <span>You ordered <span class="js-ordered-item-count">2</span> product/s in this transaction</span>
                                      </div>

                                        <ul class="shopping-list js-view-ordered-items">
                                            

                                        </ul>
                                    </div>
                                  </div>
                              </div>
                              </div>
                              </div>
                            </div>
                          </header>
                          </div>
								              <!-- End Product slider -->
                        </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- Modal end -->

    <!-- Modal -->
    <div class="modal fade" id="returnItemsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" style="width: 600px;" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
                    </div>
                    <div class="modal-body shop checkout section">
                        <div class="row no-gutters">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <!-- Product Slider -->
                                  <div class="checkout-form py-2 px-4">
                                <h2>Return Ordered Items</h2>
                                <p style="margin-bottom: 0;">Please select the product and quantity that you want to return.</p>
                                <p style="margin-bottom: 16px;">Only products that has a damage will be considered.</p>
                                
                        <header class="header shop">
                          <div>
                            <div class="container">
                              <div class="row">
                                <div class="col-lg-12 col-md-12 col-12" style="padding: 15px 0;">
                                  <div>
                                    <div class="sinlge-bar shopping" style="width: 100%;">
                                      <div class="dropdown-cart-header">
                                        <span>You ordered <span class="js-item-count">2</span> product/s in this transaction</span>
                                      </div>

                                        <ul class="shopping-list js-ordered-items">
                                            

                                        </ul>
                                        <ul>
                                          <li class="shopping-list" style="text-align: left;">
                                            <label for="reasonText" style="font-weight: 700;">Reason of returning item/s:</label>
                                            <textarea id="reasonText" name="reasonText" rows="4" cols="50">
                                            </textarea>
                                            <p class="text-danger" id="errorFields" style="font-size: 14px; margin: 0;"></p>
                                          </li>
                                        </ul>
                                        <div class="bottom my-3"  style="display: flex; justify-content: center; align-items: center;">
                                          <button id="submitReturnItems" class="btn animate submitReturnItems">SUBMIT</button>
                                        </div>
                                    </div>
                                  </div>
                              </div>
                              </div>
                              </div>
                            </div>
                          </header>
                          </div>
								              <!-- End Product slider -->
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
								<a href="index.php"><img src="<?php echo base_url; ?>dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 60px;"></a>
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
							<h4>Get In Touch</h4>
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
    <!-- Fancybox JS -->
    <script src="js/facnybox.min.js"></script>
    <!-- Waypoints JS -->
    <script src="js/waypoints.min.js"></script>
    <!-- Countdown JS -->
    <script src="js/finalcountdown.min.js"></script>
    <!-- Nice Select JS -->
    <script src="js/nicesellect.js"></script>
    <!-- Ytplayer JS -->
    <script src="js/ytplayer.min.js"></script>
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

    <!-- Sweet Alert -->
    <script src="<?php echo base_url; ?>plugins/sweetalert2/sweetalert2.min.js"></script>

    <!-- Toastr -->
	  <script src="https:////cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
      $(document).ready(function(){
        if(sessionStorage.getItem("showmsg")=='1'){
            toastr.success('Order Received Successfully');
            sessionStorage.removeItem("showmsg");
        }

        if(sessionStorage.getItem("showmsg")=='2'){
            toastr.success('Your returned items are now in process.');
            sessionStorage.removeItem("showmsg");
        }

        $('.orderReceived').click(function(){
            var transactionNumber = $(this).data('transaction_number');

            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, I received the order!'
          }).then((result) => {
            if (result.isConfirmed) {
              var value = 1;

              $.ajax({
              url:"./order-received.php",
              method:"POST",
              data:{order_received:value, transaction_number:transactionNumber},
              success:function(data)
              {
                sessionStorage.setItem("showmsg", "1");
                location.reload();
              }
            });
            }
          })
        });

        $('.viewOrder').click(function(){
          $('#viewOrderModal').modal('show');

          var sessionId = $(this).data('session_id');

          $.ajax({
              url:"./view-orders.php",
              method:"POST",
              data:{session_id: sessionId},
              success:function(data)
              {
                var dataResult = JSON.parse(data);

                $('.js-ordered-item-count').text(dataResult.itemsCount);
                $('.js-view-ordered-items').html(dataResult.returnedItems);
              }
            });
        });

        $('.forReturn').click(function(){
          let reasonTextField = $('#reasonText').val('');
          $('#errorFields').text('');

          $('#returnItemsModal').modal('show');

          var sessionId = $(this).data('session_id');

          $.ajax({
              url:"./return-orders.php",
              method:"POST",
              data:{session_id: sessionId},
              success:function(data)
              {
                var dataResult = JSON.parse(data);

                $('.js-item-count').text(dataResult.itemsCount);
                $('.js-ordered-items').html(dataResult.returnedItems);
              }
            });
        });

        $('.submitReturnItems').click(function() {
          let reasonTextField = $.trim($('#reasonText').val());

          let inputs = $('.js-ordered-items').find('input');
          let checkInputValues = inputs.map((i, el) => {
              return {
                product_id: el.dataset.product_id,
                product_name: el.dataset.product_name,
                product_barcode: el.dataset.product_barcode,
                product_quantity: el.value,
                product_session_id: el.dataset.product_session_id,
                reason_for_return: reasonTextField
              };
          });

          let checkIfHasQty = checkInputValues.get().filter(el => {
            return el.product_quantity > 0;
          });

          if(checkIfHasQty.length == 0) {
            $('#errorFields').text('Please check if you put a quantity or a valid reason.');
          } else {
            $('#errorFields').text('');

            let sessionId = "";
            let productId = "";
            let productQty = "";

            let userFullName = $('#userFullName').text();
            let userContactNumber = $('#userContactNumber').text();
            let userHomeAddress = $('#userHomeAddress').text();

            let productsData = checkIfHasQty.map(el => {
              sessionId = el.product_session_id;
              productId = el.product_id;
              productQty = el.product_quantity;

              return ` ('${userFullName}', '${userContactNumber}', '${userHomeAddress}', ${el.product_id}, '${el.product_name}', '${el.product_barcode}', ${el.product_quantity}, '${el.reason_for_return}', 0, '${el.product_session_id}')`;
            })

            $.ajax({
              url:"./return-ordered-items.php",
              method:"POST",
              data:{full_name: userFullName, contact_number: userContactNumber, home_address: userHomeAddress, product_id: productId, product_qty: productQty, session_id: sessionId, all_returned_items: productsData.toString()},
              success:function(data)
              {
                $('#returnItemsModal').hide();

                sessionStorage.setItem("showmsg", "2");
                location.reload();
              }
            });
          }
        });
      });

      // function forReturn() {
      //   $('#returnItemsModal').modal('show');

      //     var sessionId = $('#productSessionId').text().replace(/\s/g, "");

      //     console.log(sessionId);

      //     $.ajax({
      //         url:"./return-orders.php",
      //         method:"POST",
      //         data:{session_id: sessionId},
      //         success:function(data)
      //         {
      //           var dataResult = JSON.parse(data);

      //           $('.js-item-count').text(dataResult.itemsCount);
      //           $('.js-ordered-items').html(dataResult.returnedItems);
      //         }
      //       });
      // }

    function change_quantity_up(prod_id, total_qty) {
        let old_quantity =  $('.js-ordered-items li div.product_name input.quantity' + prod_id);
        let new_quantity = parseFloat(old_quantity.val()) + 1;

        if(new_quantity <= 0) {
            new_quantity = 0;
            old_quantity.val(0);
        } else if(new_quantity > total_qty) {
            new_quantity = total_qty;
        } else {
            old_quantity.val(new_quantity);
        }
        
    }

    function change_quantity_down(prod_id, total_qty) {
        let old_quantity =  $('.js-ordered-items li div.product_name input.quantity' + prod_id);
        let new_quantity = parseFloat(old_quantity.val()) - 1;

        
        if(new_quantity <= 0) {
            new_quantity = 0;
            old_quantity.val(0);
        } else if(new_quantity > total_qty) {
            new_quantity = total_qty;
        } else {
            old_quantity.val(new_quantity);
        }

    }

    </script>

  </body>
</html>
