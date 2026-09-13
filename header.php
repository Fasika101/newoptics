<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");
include("admin/inc/CSRF_Protect.php");
// include "chat.php";

$csrf = new CSRF_Protect();

// require 'assets/mail/PHPMailer.php';
// require 'assets/mail/Exception.php';
// $mail = new PHPMailer\PHPMailer\PHPMailer();

$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

// Getting all language variables into array as global variable
$i = 1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	define('LANG_VALUE_' . $i, $row['lang_value']);
	$i++;
}

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$logo = $row['logo'];
	$favicon = $row['favicon'];
	$contact_email = $row['contact_email'];
	$contact_phone = $row['contact_phone'];
	$meta_title_home = $row['meta_title_home'];
	$meta_keyword_home = $row['meta_keyword_home'];
	$meta_description_home = $row['meta_description_home'];
	$before_head = $row['before_head'];
	$after_body = $row['after_body'];
	$theme_color = $row['color'];
}

// Checking the order table and removing the pending transaction that are 24 hours+ old
$current_date_time = date('Y-m-d H:i:s');
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
$statement->execute(array('Pending'));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$ts1 = strtotime($row['payment_date']);
	$ts2 = strtotime($current_date_time);
	$diff = $ts2 - $ts1;
	$time = $diff / (3600);
	if ($time > 24) {

		// Return back the stock amount
		$statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
		$statement1->execute(array($row['payment_id']));
		$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result1 as $row1) {
			$statement2 = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
			$statement2->execute(array($row1['product_id']));
			$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result2 as $row2) {
				$p_qty = $row2['p_qty'];
			}
			$final = $p_qty + $row1['quantity'];

			$statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
			$statement->execute(array($final, $row1['product_id']));
		}

		// Deleting data from table
		$statement1 = $pdo->prepare("DELETE FROM tbl_order WHERE payment_id=?");
		$statement1->execute(array($row['payment_id']));

		$statement1 = $pdo->prepare("DELETE FROM tbl_payment WHERE id=?");
		$statement1->execute(array($row['id']));
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<!-- Meta Tags -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<script src="payment/telebirr/jquery.min.js"></script>
	<!-- Favicon -->
	<link rel="icon" type="image/png" href="assets/uploads/<?php echo $favicon; ?>">
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
	<script src="assets/vision/vision.js"></script>


	<!-- Stylesheets -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.min.css">
	<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
	<link rel="stylesheet" href="assets/css/jquery.bxslider.min.css">
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<link rel="stylesheet" href="assets/css/rating.css">
	<link rel="stylesheet" href="assets/css/spacing.css">
	<link rel="stylesheet" href="assets/css/bootstrap-touch-slider.css">
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<link rel="stylesheet" href="assets/css/tree-menu.css">
	<link rel="stylesheet" href="assets/css/select2.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/responsive.css">
	<link rel="stylesheet" href="hide_show/style.css">

	<!-- flowbite -->
	<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
	
	<!-- Google tag (gtag.js) --> 
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-10867043837"></script> 
	<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-10867043837'); </script> 




	<?php

	$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($result as $row) {
		$about_meta_title = $row['about_meta_title'];
		$about_meta_keyword = $row['about_meta_keyword'];
		$about_meta_description = $row['about_meta_description'];
		$faq_meta_title = $row['faq_meta_title'];
		$faq_meta_keyword = $row['faq_meta_keyword'];
		$faq_meta_description = $row['faq_meta_description'];
		$blog_meta_title = $row['blog_meta_title'];
		$blog_meta_keyword = $row['blog_meta_keyword'];
		$blog_meta_description = $row['blog_meta_description'];
		$contact_meta_title = $row['contact_meta_title'];
		$contact_meta_keyword = $row['contact_meta_keyword'];
		$contact_meta_description = $row['contact_meta_description'];
		$pgallery_meta_title = $row['pgallery_meta_title'];
		$pgallery_meta_keyword = $row['pgallery_meta_keyword'];
		$pgallery_meta_description = $row['pgallery_meta_description'];
		$vgallery_meta_title = $row['vgallery_meta_title'];
		$vgallery_meta_keyword = $row['vgallery_meta_keyword'];
		$vgallery_meta_description = $row['vgallery_meta_description'];
	}

	$cur_page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);

	if ($cur_page == 'index.php' || $cur_page == 'login.php' || $cur_page == 'registration.php' || $cur_page == 'cart.php' || $cur_page == 'checkout.php' || $cur_page == 'forget-password.php' || $cur_page == 'reset-password.php' || $cur_page == 'product-category.php' || $cur_page == 'product.php') {
	?>
		<title><?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
	<?php
	}

	if ($cur_page == 'about.php') {
	?>
		<title><?php echo $about_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $about_meta_keyword; ?>">
		<meta name="description" content="<?php echo $about_meta_description; ?>">
	<?php
	}
	if ($cur_page == 'faq.php') {
	?>
		<title><?php echo $faq_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $faq_meta_keyword; ?>">
		<meta name="description" content="<?php echo $faq_meta_description; ?>">
	<?php
	}

	if ($cur_page == 'contact.php') {
	?>
		<title><?php echo $contact_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $contact_meta_keyword; ?>">
		<meta name="description" content="<?php echo $contact_meta_description; ?>">
	<?php
	}
	if ($cur_page == 'charity.php') {
	?>
		<title><?php echo $pgallery_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $pgallery_meta_keyword; ?>">
		<meta name="description" content="<?php echo $pgallery_meta_description; ?>">
	<?php
	}
	if ($cur_page == 'video-gallery.php') {
	?>
		<title><?php echo $vgallery_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $vgallery_meta_keyword; ?>">
		<meta name="description" content="<?php echo $vgallery_meta_description; ?>">
	<?php
	}

	if ($cur_page == 'blog-single.php') {
		$statement = $pdo->prepare("SELECT * FROM tbl_post WHERE post_slug=?");
		$statement->execute(array($_REQUEST['slug']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $row) {
			$og_photo = $row['photo'];
			$og_title = $row['post_title'];
			$og_slug = $row['post_slug'];
			$og_description = substr(strip_tags($row['post_content']), 0, 200) . '...';
			echo '<meta name="description" content="' . $row['meta_description'] . '">';
			echo '<meta name="keywords" content="' . $row['meta_keyword'] . '">';
			echo '<title>' . $row['meta_title'] . '</title>';
		}
	}

	if ($cur_page == 'product.php') {
		$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $row) {
			$og_photo = $row['p_featured_photo'];
			$og_title = $row['p_name'];
			$og_slug = 'product.php?id=' . $_REQUEST['id'];
			$og_description = substr(strip_tags($row['p_description']), 0, 200) . '...';
		}
	}

	if ($cur_page == 'dashboard.php') {
	?>
		<title>Dashboard - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
	<?php
	}
	if ($cur_page == 'customer-profile-update.php') {
	?>
		<title>Update Profile - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
	<?php
	}
	if ($cur_page == 'customer-billing-shipping-update.php') {
	?>
		<title>Update Billing and Shipping Info - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
	<?php
	}
	if ($cur_page == 'customer-password-update.php') {
	?>
		<title>Update Password - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
	<?php
	}
	if ($cur_page == 'customer-order.php') {
	?>
		<title>Orders - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
	<?php
	}
	?>

	<?php if ($cur_page == 'blog-single.php'): ?>
		<meta property="og:title" content="<?php echo $og_title; ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="<?php echo BASE_URL . $og_slug; ?>">
		<meta property="og:description" content="<?php echo $og_description; ?>">
		<meta property="og:image" content="assets/uploads/<?php echo $og_photo; ?>">
	<?php endif; ?>

	<?php if ($cur_page == 'product.php'): ?>
		<meta property="og:title" content="<?php echo $og_title; ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="<?php echo BASE_URL . $og_slug; ?>">
		<meta property="og:description" content="<?php echo $og_description; ?>">
		<meta property="og:image" content="assets/uploads/<?php echo $og_photo; ?>">
	<?php endif; ?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

	<!-- <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=5993ef01e2587a001253a261&product=inline-share-buttons"></script> -->

	<style>
		.top .right ul li a:hover,
		.nav,
		.menu-container,
		.slide-text>a.btn-primary,
		.welcome p.button a,
		.product .owl-controls .owl-prev:hover,
		.product .owl-controls .owl-next:hover,
		.product .text p a,
		.home-blog .text p.button a,
		.home-newsletter,
		.footer-main h3:after,
		.scrollup i,
		.cform input[type="submit"],
		.blog p.button a,
		div.pagination a,
		#left ul.nav>li.cat-level-1.parent>a,
		.product .btn-cart1 input[type="submit"],
		.review-form .btn-default {
			background: #<?php echo $theme_color; ?> !important;
		}

		#left ul.nav>li.cat-level-1.parent>a>.sign,
		#left ul.nav>li.cat-level-1 li.parent>a>.sign {
			background-color: #<?php echo $theme_color; ?> !important;
		}

		.top .left ul li,
		.top .left ul li i,
		.top .right ul li a,
		.header .right ul li,
		.header .right ul li a,
		.welcome p.button a:hover,
		.product .text h4,
		.cform address span,
		.blog h3 a:hover,
		.blog .text ul.status li a,
		.blog .text ul.status li,
		.widget ul li a:hover,
		.breadcrumb ul li,
		.breadcrumb ul li a,
		.product .p-title h2 {
			color: #<?php echo $theme_color; ?> !important;
		}

		.scrollup i,
		div.pagination a,
		#left ul.nav>li.cat-level-1.parent>a {
			border-color: #<?php echo $theme_color; ?> !important;
		}

		.widget h4 {
			border-bottom-color: #<?php echo $theme_color; ?> !important;
		}


		.top .right ul li a:hover,
		#left ul.nav>li.cat-level-1 .lbl1 {
			color: #fff !important;
		}

		.welcome p.button a:hover {
			background: #fff !important;
		}

		.slide-text>a:hover,
		.slide-text>a:active {
			background-color: #333333 !important;
		}

		.product .text p a:hover,
		.home-blog .text p.button a:hover,
		.blog p.button a:hover {
			background: #333 !important;
		}

		div.pagination span.current {
			border-color: #777 !important;
			background: #777 !important;
		}

		div.pagination a:hover,
		div.pagination a:active {
			border-color: #777 !important;
			background: #777 !important;
		}

		.product .nav {
			background: transparent !important;
		}





		.menu-mobile:after {
			content: "\f0c9";
			font-family: "FontAwesome";
			font-size: 2.5rem;
			padding: 0;
			float: right;
			position: relative;
			top: 50%;
			-webkit-transform: translateY(-25%);
			-ms-transform: translateY(-25%);
			transform: translateY(-25%);
		}

		.menu-dropdown-icon:before {
			content: "\f107";
			font-family: "FontAwesome";


			float: right;
			padding: 1.5em 2em;

		}

		.popup .show {
			visibility: visible;
			-webkit-animation: fadeIn 1s;
			animation: fadeIn 1s
		}




		/* The Modal (background) */
		.modal {
			display: none;
			/* Hidden by default */
			position: fixed;
			/* Stay in place */
			z-index: 1;
			/* Sit on top */
			padding-top: 100px;
			/* Location of the box */
			left: 0;
			top: 0;
			width: 100%;
			/* Full width */
			height: 100%;
			/* Full height */
			overflow: auto;
			/* Enable scroll if needed */
			background-color: rgb(0, 0, 0);
			/* Fallback color */
			background-color: rgba(0, 0, 0, 0.4);
			/* Black w/ opacity */
		}

		/* Modal Content */
		.modal-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 1px solid #888;
			width: 80%;
		}

		/* The Close Button */
		.close {
			color: #aaaaaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}

		.close:hover,
		.close:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}

		.badge {
			padding-left: 9px;
			padding-right: 9px;
			-webkit-border-radius: 9px;
			-moz-border-radius: 9px;
			border-radius: 9px;
		}

		.badge:empty {
			display: none;
		}

		.label-warning[href],
		.badge-warning[href] {
			background-color: #c67605;
		}

		#lblCartCount {
			font-size: 12px;
			background: #f7c852;
			color: white;
			padding: 0 5px;
			vertical-align: top;
			margin-left: -10px;
		}



		.mobile-nav {
			background: #f7c852;
			position: fixed;
			bottom: 0;
			height: 65px;
			width: 100%;
			display: flex;
			justify-content: space-around;
			z-index: 2;
		}

		.bloc-icon {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.bloc-icon img {
			width: 30px;
		}

		@media screen and (min-width: 600px) {
			.mobile-nav {
				display: none;
			}
		}

		@media screen and (min-width: 800px){
  .newoptics_menu{
    display: none;
  }
}


@media only screen and (max-width: 991px){
.heade{
    height: auto !important;
}
}
	</style>




	<?php echo $before_head; ?>

</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-18231082197"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-18231082197');
</script>

<body>

	<?php echo $after_body; ?>

	<!-- <div id="preloader">
	<div id="status"></div>
</div> -->


	<!-- <div class="top">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="left">
					<ul>
						<li><i class="fa fa-phone"></i> <?php //echo $contact_phone; 
														?></li>
						<li><i class="fa fa-envelope-o"></i> <?php //echo $contact_email; 
																?></li>
					</ul>
				</div>
			</div>
			
		</div>
	</div>
</div> -->


	<div class="header" style="background-color: #A9A9A9;">
		<div class="container">
			<div class="row inner">
				<div class="col-md-4 logo">
					<a href="index.php" style="text-align: -moz-center;" ><img  class="heade"  src="assets/uploads/<?php echo $logo; ?>" alt="logo image"></a>
				</div>

				<div class="col-md-5 right">
					<ul>

						<?php
						if (isset($_SESSION['customer'])) {
						?>
							<li><i class="fa fa-user"></i> <?php echo LANG_VALUE_13; ?> <?php echo $_SESSION['customer']['cust_name']; ?></li>
							<li><a href="dashboard.php"><i class="fa fa-home"></i> <?php echo LANG_VALUE_89; ?></a></li>
							<li><a href="logout.php" class="fa fa-sign-out"><?php echo LANG_VALUE_14; ?></a></li>

						<?php
						} else {
						?>
							<li><a href="login.php"><i class="fa fa-sign-in"></i> <?php echo LANG_VALUE_9; ?></a></li>
							<li><a href="registration.php"><i class="fa fa-user-plus"></i> <?php echo LANG_VALUE_15; ?></a></li>
						<?php
						}
						?>

						<li><a href="cart.php"><i data-count="2" class="fa fa-shopping-cart fa-lg"></i> <?php echo ''; ?>

								<span class='badge badge-warning' id='lblCartCount'><?php
																					if (isset($_SESSION['cart_p_id'])) {
																						$table_total_price = 0;
																						$i = 0;
																						foreach ($_SESSION['cart_p_qty'] as $key => $value) {
																							$i++;
																							$arr_cart_p_qty[$i] = $value;
																							//$row_total_price = $arr_cart_p_qty[$i] ;
																						}
																						for ($i = 1; $i <= count($arr_cart_p_qty); $i++) {
																							$row_total_price = $arr_cart_p_qty[$i];
																							$table_total_price = $table_total_price + $row_total_price;
																						}
																						echo $table_total_price;
																					} else {
																						echo '';
																					} ?>
								</span>

							</a></li>
					</ul>
				</div>
				<div class="col-md-3 search-area">
					<form class="navbar-form navbar-left" role="search" action="search-result.php" method="get">
						<?php $csrf->echoInputField(); ?>
						<div class="form-group">
							<input type="text" class="form-control search-top text-2xl" placeholder="<?php echo LANG_VALUE_2; ?>" name="search_text">
						</div>
						<button type="submit" class="btn btn-default"><?php echo LANG_VALUE_3; ?></button>
					</form>
				</div>

			</div>
		</div>
	</div>


	<div class="fixed z-40 w-full h-20 max-w-lg -translate-x-1/2 bg-white border border-green-200 rounded-full bottom-4 left-1/2 dark:bg-green-700 dark:border-green-600 newoptics_menu">
		<div class="grid h-full max-w-lg grid-cols-4 mx-auto">
			<a href="index.php" data-tooltip-target="tooltip-home" type="button" class="inline-flex flex-col items-center justify-center px-5 rounded-s-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
				<svg class="w-8 h-8 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
					<path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
				</svg>
				<span class="sr-only">Home</span>
			</a>
			<div id="tooltip-home" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
				Home
				<div class="tooltip-arrow" data-popper-arrow></div>
			</div>
			<button id="multiLevelDropdownButton" data-dropdown-toggle="multi-dropdown" data-tooltip-target="tooltip-wallet" type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
				<svg class="w-9 h-9 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
					<path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14" />
				</svg>

				<span class="sr-only">Menu</span>
			</button>
			<!-- Dropdown menu -->
			<div id="multi-dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
				<ul class="py-2 text-lg text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">

					

						<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach ($result as $row) {
					?>

					<li>
						<a style="color: black !important;" id="doubleDropdownButton" data-dropdown-toggle="doubleDropdown" data-dropdown-placement="right-start" type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"  href="product-category.php?id=<?php echo $row['tcat_id']; ?>&type=top-category"><?php echo $row['tcat_name']; ?>
							<svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
								<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
							</svg></a>
						<div id="doubleDropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
							<ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="doubleDropdownButton">
							<?php
											$statement1 = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id=?");
											$statement1->execute(array($row['tcat_id']));
											$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
											foreach ($result1 as $row1) {
											?>
												
											<?php
											}
											?>

								
							</ul>
						</div>
					</li>
					<?php
					}
					?>
		
				</ul>
			</div>
	
		
			<a href="cart.php" data-tooltip-target="tooltip-settings" type="button" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
			<span class='badge badge-warning' id='lblCartCount'><?php
																					if (isset($_SESSION['cart_p_id'])) {
																						$table_total_price = 0;
																						$i = 0;
																						foreach ($_SESSION['cart_p_qty'] as $key => $value) {
																							$i++;
																							$arr_cart_p_qty[$i] = $value;
																							//$row_total_price = $arr_cart_p_qty[$i] ;
																						}
																						for ($i = 1; $i <= count($arr_cart_p_qty); $i++) {
																							$row_total_price = $arr_cart_p_qty[$i];
																							$table_total_price = $table_total_price + $row_total_price;
																						}
																						echo $table_total_price;
																					} else {
																						echo '';
																					} ?>
								</span>
				<svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
					  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
</svg>

				



			</a>
			<div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
				Cart
				<div class="tooltip-arrow" data-popper-arrow></div>
			</div>
			<?php
			if (isset($_SESSION['customer'])) {

				
			?>

<button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group" type="button">
<svg class="w-8 h-8 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
						<path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
					</svg>
</button>


<!-- Dropdown menu -->
<div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
    <ul class="py-2  text-lg text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconButton">
	 <li>
        <a href="promo_page.php"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Promo Page</a>
      </li>
	  <li>
        <a href="dashboard.php"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
      </li>
      <li>
        <a href="customer-profile-update.php"class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a>
      </li>
      <li>
        <a href="logout.php"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Signout</a>
      </li>
      
    </ul>
    
</div>
			
			<?php

			} else {
			?>
				<a href="login.php" data-tooltip-target="tooltip-profile" type="button" class="inline-flex flex-col items-center justify-center px-5 rounded-e-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
					
					<svg class="w-8 h-8 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
						  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
					</svg>

					<span class="sr-only">Signin</span>
				</a>

				<div id="tooltip-profile" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
					Signin
					<div class="tooltip-arrow" data-popper-arrow></div>
				</div>
			<?php
			}

			?>
		</div>
	</div>






	<div class="nav">
		<div class="container">
			<div class="row">
				<div class="col-md-12 pl_0 pr_0">
					<div class="menu-container">
						<div class="menu">

							<ul>
								<!-- <li><a href="index.php" ><img src="assets/uploads/<?php echo $logo; ?>" alt="logo image" style="max-width: 70px;"></a></li> -->

								<li><a href="index.php">Home</a></li>

								<?php
								$statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
								$statement->execute();
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
								?>
									<li><a href="product-category.php?id=<?php echo $row['tcat_id']; ?>&type=top-category"><?php echo $row['tcat_name']; ?></a>
										<ul>
											<?php
											$statement1 = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id=?");
											$statement1->execute(array($row['tcat_id']));
											$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
											foreach ($result1 as $row1) {
											?>
												<li><a href="product-category.php?id=<?php echo $row1['mcat_id']; ?>&type=mid-category"><?php echo $row1['mcat_name']; ?></a>
													<!--  -->
												</li>
											<?php
											}
											?>
										</ul>
									</li>
								<?php
								}
								?>

								<?php
								$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
								$statement->execute();
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result as $row) {
									$about_title = $row['about_title'];
									$faq_title = $row['faq_title'];
									$blog_title = $row['blog_title'];
									$contact_title = $row['contact_title'];
									$pgallery_title = $row['pgallery_title'];
									$vgallery_title = $row['vgallery_title'];
								}
								?>
								<li><a href="charity.php">Charity</a>
									<!-- <ul>
									<li><a href="photo-gallery.php"><?php echo $pgallery_title; ?></a></li>
									 <li><a href="video-gallery.php"><?php echo $vgallery_title; ?></a></li> 
								</ul> -->
								</li>
								<!-- <li><a href="about.php"><?php echo $about_title; ?></a></li> -->
								<!-- <li><a href="faq.php"><?php echo $faq_title; ?></a></li> -->

								<li><a href="contact.php"><?php echo $contact_title; ?></a></li>

								<?php
								if (isset($_SESSION['customer'])) {
								?>
									<li><a href="customer-profile-update.php"><?php echo LANG_VALUE_117; ?></a></li>
									<!--<li><a href="customer-password-update.php"><?php echo LANG_VALUE_99; ?></a></li>-->
								<?php
								} ?>


								<!-- <?php
										if (isset($_SESSION['customer'])) {
										?>
						<li><i class="fa fa-user"></i> <?php echo LANG_VALUE_13; ?> <?php echo $_SESSION['customer']['cust_name']; ?></li>
						<li><a href="dashboard.php"><i class="fa fa-home"></i> <?php echo LANG_VALUE_89; ?></a></li>
						<?php
										} else {
						?>
						<li><a href="login.php"><i class="fa fa-sign-in"></i> <?php echo LANG_VALUE_9; ?></a></li>
						<li><a href="registration.php"><i class="fa fa-user-plus"></i> <?php echo LANG_VALUE_15; ?></a></li>
						<?php
										}
						?> -->

								<!-- <li><a href="cart.php"><i class="fa fa-shopping-cart"></i> <?php echo LANG_VALUE_19; ?> (<?php echo LANG_VALUE_1; ?><?php
																																							if (isset($_SESSION['cart_p_id'])) {
																																								$table_total_price = 0;
																																								$i = 0;
																																								foreach ($_SESSION['cart_p_qty'] as $key => $value) {
																																									$i++;
																																									$arr_cart_p_qty[$i] = $value;
																																								}
																																								$i = 0;
																																								foreach ($_SESSION['cart_p_current_price'] as $key => $value) {
																																									$i++;
																																									$arr_cart_p_current_price[$i] = $value;
																																								}
																																								for ($i = 1; $i <= count($arr_cart_p_qty); $i++) {
																																									$row_total_price = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i];
																																									$table_total_price = $table_total_price + $row_total_price;
																																								}
																																								echo $table_total_price;
																																							} else {
																																								echo '0.00';
																																							}
																																							?>)</a></li> -->
							</ul>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>