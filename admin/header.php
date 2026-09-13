<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

// Check if the user is logged in or not
if(!isset($_SESSION['user'])) {
	header('location: login.php');
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Admin Panel</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/ionicons.min.css">
	<link rel="stylesheet" href="css/datepicker3.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<link rel="stylesheet" href="css/_all-skins.min.css">
	<link rel="stylesheet" href="css/on-off-switch.css"/>
	<link rel="stylesheet" href="css/summernote.css">
	<link rel="stylesheet" href="style.css">

</head>

<body class="hold-transition fixed skin-blue sidebar-mini">

	<div class="wrapper">

		<header class="main-header">

			<a href="index.php" class="logo">
				<span class="logo-lg">NewOnlineOptics</span>
			</a>

			<nav class="navbar navbar-static-top">
				
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>

				<span style="float:left;line-height:50px;color:#fff;padding-left:15px;font-size:18px;">Admin Panel</span>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" class="user-image" alt="User Image">
								<span class="hidden-xs"><?php echo $_SESSION['user']['full_name']; ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-footer">
									<div>
										<a href="profile-edit.php" class="btn btn-default btn-flat">Edit Profile</a>
									</div>
									<div>
										<a href="logout.php" class="btn btn-default btn-flat">Log out</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>

			</nav>
		</header>

  		<?php $cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); ?>

  		<aside class="main-sidebar">
    		<section class="sidebar">
      
      			<ul class="sidebar-menu">

			        <li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
			          <a href="index.php">
			            <i class="fa fa-hand-o-right"></i> <span>Dashboard</span>
			          </a>
			        </li>

					
			        <!-- <li class="treeview <?php if( ($cur_page == 'settings.php') ) {echo 'active';} ?>">-->
			        <!--  <a href="settings.php">-->
			        <!--    <i class="fa fa-hand-o-right"></i> <span>Settings</span>-->
			        <!--  </a>-->
			        <!--</li> -->

			        <li class="treeview <?php if( ($cur_page == 'slider.php') ) {echo 'active';} ?>">
			          <a href="slider.php">
			            <i class="fa fa-hand-o-right"></i> <span>Home page</span>
			          </a>
			        </li>
			        
			      

			        <!--<li class="treeview <?php if( ($cur_page == 'service.php') ) {echo 'active';} ?>">-->
			        <!--  <a href="service.php">-->
			        <!--    <i class="fa fa-hand-o-right"></i> <span>Service</span>-->
			        <!--  </a>-->
			        <!--</li> -->

			         <!-- <li class="treeview <?php if( ($cur_page == 'testimonial.php') ) {echo 'active';} ?>"> -->
			          <!-- <a href="testimonial.php">
			            <i class="fa fa-hand-o-right"></i> <span>Testimonial</span>
			          </a>
			        </li>  -->

			        <!-- <li class="treeview <?php if( ($cur_page == 'faq.php') ) {echo 'active';} ?>"> -->
			          <!-- <a href="faq.php">
			            <i class="fa fa-hand-o-right"></i> <span>FAQ</span>
			          </a>
			        </li> -->

			         <li class="treeview <?php if( ($cur_page == 'photo.php')){echo 'active';} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Gallery</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="photo.php"><i class="fa fa-circle-o"></i> Photo Gallery</a></li>
							 <!--<li><a href="video.php"><i class="fa fa-circle-o"></i> Video Gallery</a></li> -->
						</ul>
					</li> 

					<!-- <li class="treeview <?php if( ($cur_page == 'post.php') ||($cur_page == 'post-add.php') ||($cur_page == 'post-edit.php') || ($cur_page == 'category.php') || ($cur_page == 'category-add.php') || ($cur_page == 'category-edit.php') ) {echo 'active';} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Footer info posts</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="category.php"><i class="fa fa-circle-o"></i> Category</a></li>
							<li><a href="post.php"><i class="fa fa-circle-o"></i> Posts</a></li>
						</ul>
					</li> -->

					<!-- <li class="treeview <?php if( ($cur_page == 'size.php') || ($cur_page == 'size-add.php') || ($cur_page == 'size-edit.php') || ($cur_page == 'color.php') || ($cur_page == 'color-add.php') || ($cur_page == 'color-edit.php') || ($cur_page == 'country.php') || ($cur_page == 'country-add.php') || ($cur_page == 'country-edit.php') || ($cur_page == 'shipping-cost.php') || ($cur_page == 'shipping-cost-edit.php') || ($cur_page == 'top-category.php') || ($cur_page == 'top-category-add.php') || ($cur_page == 'top-category-edit.php') || ($cur_page == 'mid-category.php') || ($cur_page == 'mid-category-add.php') || ($cur_page == 'mid-category-edit.php') || ($cur_page == 'end-category.php') || ($cur_page == 'end-category-add.php') || ($cur_page == 'end-category-edit.php') ) {echo 'active';} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Shop Section</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="size.php"><i class="fa fa-circle-o"></i> Size</a></li>
							<li><a href="color.php"><i class="fa fa-circle-o"></i> Color</a></li>
							<li><a href="country.php"><i class="fa fa-circle-o"></i> Country</a></li>
							<li><a href="shipping-cost.php"><i class="fa fa-circle-o"></i> Shipping Cost</a></li>
							 <li><a href="top-category.php"><i class="fa fa-circle-o"></i> Gender</a></li> 
							<li><a href="mid-category.php"><i class="fa fa-circle-o"></i> Add Brand</a></li> 
							<li><a href="end-category.php"><i class="fa fa-circle-o"></i> Add Brand</a></li>
						</ul>
					</li> -->
					 <li class="treeview <?php if( ($cur_page == 'color.php')){echo 'active';} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Size & Color</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="color.php"><i class="fa fa-circle-o"></i>Add Color</a></li>
							 <!--<li><a href="video.php"><i class="fa fa-circle-o"></i> Video Gallery</a></li> -->
						</ul>
						<ul class="treeview-menu">
								<li><a href="size.php"><i class="fa fa-circle-o"></i> Size</a></li>
							 <!--<li><a href="video.php"><i class="fa fa-circle-o"></i> Video Gallery</a></li> -->
						</ul>
						<ul class="treeview-menu">
								<li><a href="end-category.php"><i class="fa fa-circle-o"></i> Add Brand</a></li>
							 <!--<li><a href="video.php"><i class="fa fa-circle-o"></i> Video Gallery</a></li> -->
						</ul>
					
					</li> 

                    
			        
                    
					<li class="treeview <?php if( ($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php') ) {echo 'active';} ?>">
			          <a href="product.php">
			            <i class="fa fa-hand-o-right"></i> <span>Product</span>
			          </a>
			        </li>
			        <li class="treeview <?php if( ($cur_page == 'lens_price.php')   ) {echo 'active';} ?>">
			          <a href="lens_price.php">
			            <i class="fa fa-hand-o-right"></i> <span>Change Lens Price</span>
			          </a>
			        </li>
				

<hr class="my-4">
					<li class="treeview <?php if( ($cur_page == 'customer.php') || ($cur_page == 'customer-add.php') || ($cur_page == 'customer-edit.php') ) {echo 'active';} ?>">
			          <a href="customer.php">
			            <i class="fa fa-hand-o-right"></i> <span>Customers</span>
			          </a>
			        </li>
					<!--<li class="treeview <?php if( ($cur_page == 'order.php') ) {echo 'active';} ?>">-->
			  <!--        <a href="order.php">-->
			  <!--          <i class="fa fa-hand-o-right"></i> <span>Individual Order</span>-->
			  <!--        </a>-->
			  <!--      </li>-->
			  <!--      <li class="treeview <?php if( ($cur_page == 'order_telebirr.php') || ($cur_page == 'payment_done.php') || ($cur_page == 'shipping_done.php') ) {echo 'active';} ?>">-->
					<!--	<a href="#">-->
					<!--		<i class="fa fa-hand-o-right"></i>-->
					<!--		<span>Telebirr Orders</span>-->
					<!--		<span class="pull-right-container">-->
					<!--			<i class="fa fa-angle-left pull-right"></i>-->
					<!--		</span>-->
					<!--	</a>-->
					<!--	<ul class="treeview-menu">-->
					<!--		<li><a href="order_telebirr.php"><i class="fa fa-circle-o"></i> New Orders</a></li>-->
					<!--		<li><a href="payment_done.php"><i class="fa fa-circle-o"></i> Payment Confirmed Order</a></li>-->
					<!--		<li><a href="shipping_done.php"><i class="fa fa-circle-o"></i> Shipped and delivered orders</a></li>-->
							
					<!--	</ul>-->
					<!--</li>-->
			        
			        <li class="treeview <?php if( ($cur_page == 'chapa_new_order.php') || ($cur_page == 'chapa_confirmed_order.php') || ($cur_page == 'chapa_shipped.php') ) {echo 'active';} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Chapa Orders</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="chapa_new_order.php"><i class="fa fa-circle-o"></i> Pending Orders</a></li>
							<li><a href="chapa_confirmed_order.php"><i class="fa fa-circle-o"></i> Payment Confirmed Order</a></li>
							<li><a href="chapa_shipped.php"><i class="fa fa-circle-o"></i> Shipped and delivered orders</a></li>
							
						</ul>
					</li>
				<li class="treeview <?php if( ($cur_page == 'guest_new.php') || ($cur_page == 'guest_confirmed.php') || ($cur_page == 'guest_shipped.php') ) {echo 'active';} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Guest Chapa Orders</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="guest_new.php"><i class="fa fa-circle-o"></i> Pending Orders</a></li>
							<li><a href="guest_confirmed.php"><i class="fa fa-circle-o"></i> Payment Confirmed Order</a></li>
							<li><a href="guest_shipped.php"><i class="fa fa-circle-o"></i> Shipped and delivered orders</a></li>
							
						</ul>
					</li>
					
<hr class="my-1">
<!--					<li class="treeview <?php if( ($cur_page == 'customer_company.php') || ($cur_page == '#') || ($cur_page == '#') ) {echo 'active';} ?>">-->
<!--			          <a href="customer_company.php">-->
<!--			            <i class="fa fa-hand-o-right"></i> <span>Registered Companys</span>-->
<!--			          </a>-->
<!--			        </li>-->
					
<!--					<li class="treeview <?php if( ($cur_page == 'order_company.php') || ($cur_page == '#') || ($cur_page == '#') ) {echo 'active';} ?>">-->
<!--			          <a href="order_company.php">-->
<!--			            <i class="fa fa-hand-o-right"></i> <span>Company Order</span>-->
<!--			          </a>-->
<!--			        </li>-->
<!--<hr class="my-1">-->
					<!--<li class="treeview <?php if( ($cur_page == 'customer_partner.php') || ($cur_page == '#') || ($cur_page == '#') ) {echo 'active';} ?>">-->
			  <!--        <a href="customer_partner.php">-->
			  <!--          <i class="fa fa-hand-o-right"></i> <span>Registered Partners</span>-->
			  <!--        </a>-->
			  <!--      </li>-->
					<!--<li class="treeview <?php if( ($cur_page == '#') || ($cur_page == 'order_partner.php') || ($cur_page == '#') ) {echo 'active';} ?>">-->
			  <!--        <a href="order_partner.php">-->
			  <!--          <i class="fa fa-hand-o-right"></i> <span>Partner Orders</span>-->
			  <!--        </a>-->
			  <!--      </li>-->
			     
					

					  <li class="treeview <?php if( ($cur_page == 'promo_page.php') || ($cur_page == 'promo_custom.php') ) {echo 'active';} ?>">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Promo Management</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="promo_page.php"><i class="fa fa-circle-o"></i> Customers Promo</a></li>
							 <li><a href="promo_custom.php"><i class="fa fa-circle-o"></i> New Online Optics Promo</a></li> 
						</ul>
					</li>
			        <hr class="my-1">
			        <li class="treeview">
						<a href="#">
							<i class="fa fa-hand-o-right"></i>
							<span>Over all menu</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="see_frame.php"><i class="fa fa-circle-o"></i> See Frame</a></li>
							<li><a href="free_maintenance.php"><i class="fa fa-circle-o"></i> Free Maintenance</a></li> 
							<li><a href="rating.php"><i class="fa fa-circle-o"></i> Rating</a></li> 
							<li><a href="generate_qr.php"><i class="fa fa-circle-o"></i> QR Code</a></li> 
						</ul>
					</li>
			        <!-- <li class="treeview <?php if( ($cur_page == 'see_frame.php') ) {echo 'active';} ?>">-->
			        <!--  <a href="see_frame.php">-->
			        <!--    <i class="fa fa-hand-o-right"></i> <span>See Frame</span>-->
			        <!--  </a>-->
			        <!--</li> -->
			        <!-- <li class="treeview <?php if( ($cur_page == 'free_maintenance.php') ) {echo 'active';} ?>">-->
			        <!--  <a href="free_maintenance.php">-->
			        <!--    <i class="fa fa-hand-o-right"></i> <span>Free Maintenance</span>-->
			        <!--  </a>-->
			        <!--</li> -->
			        <hr class="my-1">
					 <li class="treeview <?php if( ($cur_page == 'promo_earning.php') ) {echo 'active';} ?>">
			          <a href="promo_earning.php">
			            <i class="fa fa-hand-o-right"></i> <span>Customer Promo Earnings</span>
			          </a>
			        </li> 
					<li class="treeview <?php if( ($cur_page == 'withdraw_request.php') ) {echo 'active';} ?>">
			          <a href="withdraw_request.php">
			            <i class="fa fa-hand-o-right"></i> <span>Customer Withdraw Request</span>
			          </a>
			        </li> 
			        	<li class="treeview <?php if( ($cur_page == 'withdrawn.php') ) {echo 'active';} ?>">
			          <a href="withdrawn.php">
			            <i class="fa fa-hand-o-right"></i> <span>Withdrawn</span>
			          </a>
			        </li> 
			        

			     

			        

			        


      			</ul>
    		</section>
  		</aside>

  		<div class="content-wrapper">