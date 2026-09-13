<?php
ob_start();
session_start();
include "admin/inc/config.php";
include "admin/inc/functions.php";
include "admin/inc/CSRF_Protect.php";

if (!isset($_REQUEST['payment_id'])) {
    header('location: customer-order.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=?");
    $statement->execute(array($_REQUEST['payment_id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if ($total == 0) {
        header('location: customer-order.php');
        exit;
    }
}

foreach ($result as $row) {
    $p_name = $row['customer_name'];
    $p_id = $row['payment_id'];
    $p_date = $row['payment_date'];
    $cust_type = $row['cust_type'];
    $paid = $row['paid_amount'];
    $c_drop = $row['customer_drop'];
    $c_phone = $row['customer_phone'];


    //  $p_old_price = $row['p_old_price'];

}

// $statement1 = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_name= $p_name");
// $statement1->execute(array($row['cust_name']));
// $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
// foreach ($result1 as $row1) {
//    $cust_name = $row1['cust_name'];

// }


?>
<HTML>
    <head>
        <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
         <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>

@media print {
  #printPageButton {
    display: none;
  }
}

   body{
    background:#eee;
    margin-top:20px;
    }
    .text-danger strong {
        	color: #9f181c;
		}
		.receipt-main {
			background: #ffffff none repeat scroll 0 0;
			border-bottom: 12px solid #333333;
			border-top: 12px solid #9f181c;
			margin-top: 50px;
			margin-bottom: 50px;
			padding: 40px 30px !important;
			position: relative;
			box-shadow: 0 1px 21px #acacac;
			color: #333333;
			font-family: open sans;
		}
		.receipt-main p {
			color: #333333;
			font-family: open sans;
			line-height: 1.42857;
		}
		.receipt-footer h1 {
			font-size: 15px;
			font-weight: 400 !important;
			margin: 0 !important;
		}
		.receipt-main::after {
			background: #414143 none repeat scroll 0 0;
			content: "";
			height: 5px;
			left: 0;
			position: absolute;
			right: 0;
			top: -13px;
		}
		.receipt-main thead {
			background: #414143 none repeat scroll 0 0;
		}
		.receipt-main thead th {
			color:#fff;
		}
		.receipt-right h5 {
			font-size: 16px;
			font-weight: bold;
			margin: 0 0 7px 0;
		}
		.receipt-right p {
			font-size: 12px;
			margin: 0px;
		}
		.receipt-right p i {
			text-align: center;
			width: 18px;
		}
		.receipt-main td {
			padding: 9px 20px !important;
		}
		.receipt-main th {
			padding: 13px 20px !important;
		}
		.receipt-main td {
			font-size: 13px;
			font-weight: initial !important;
		}
		.receipt-main td p:last-child {
			margin: 0;
			padding: 0;
		}	
		.receipt-main td h2 {
			font-size: 20px;
			font-weight: 900;
			margin: 0;
			text-transform: uppercase;
		}
		.receipt-header-mid .receipt-left h1 {
			font-weight: 100;
			margin: 34px 0 0;
			text-align: right;
			text-transform: uppercase;
		}
		.receipt-header-mid {
			margin: 24px 0;
			overflow: hidden;
		}
		
		#container {
			background-color: #dcdcdc;
		}
</style>
    </head>
        <?php
$nn = $p_name;
$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_name='$nn'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $customer_name = $row['cust_name'];
	$customer_lname = $row['cust_lname'];
    $customer_phone = $row['cust_phone'];
    $drop_address = $row['cust_drop_address'];

}


?>

          
<body>


<div class="col-md-12-responsive">   
 <div class="row">
		 
        <div class="receipt-main col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="row">
    			<div class="receipt-header">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="receipt-left">
							<img class="img-responsive" alt="iamgurdeeposahan" src="assets/img/avatar5.png" style="width: 71px; border-radius: 2px;">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 text-right">
						<div class="receipt-right">
							<h5>NewOnlineOptics.</h5>
							<p>+251 919 48 51 09 <i class="fa fa-phone"></i></p>
							<p>info@newonlineoptics.com <i class="fa fa-envelope-o"></i></p>
							<p>Ethiopia, Addis Ababa <i class="fa fa-location-arrow"></i></p>
						</div>
					</div>
				</div>
            </div>
			
			<div class="row">
				<div class="receipt-header receipt-header-mid">
					<div class="col-xs-8 col-sm-8 col-md-8 text-left">
						<div class="receipt-right">
							<h5>Customer Information </h5>
                            <p><b>Name :</b> <?php echo $p_name;?></p>
							<p><b>Mobile :</b> <?php echo $c_phone;?></p>
							<p><b>Address :</b>  <?php echo $c_drop; ?></p>
                            <p><b>Customer Type :</b>  <?php echo $cust_type; ?></p>
                            <p><b>Date :</b> <?php echo $p_date;?></p>
						</div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-4">
						<div class="receipt-left" style="word-wrap: break-word;">
							<h5>NewOnlineOptics Issued Receipt</h5>
						</div>
					</div>
				</div>
            </div>

            <div>
				<div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Total Quantity</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
	
                    <tbody>
                        <tr>	
                            <td class="col-md-9">
<?php		
$payment_id = $p_id;
$statement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id='$p_id'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {

?>
								
								<?php echo '<b>Product Name</b>: '.$row['product_name'];
                                                echo '<br><b>Size</b>: '.$row['size'];
                                                echo '<br><b>Color</b>: '.$row['color'];
                                                echo '<br><b>Lens Type</b>: ' . $row['lens_type'];
												echo '<br><b>Quantity</b>: ' . $row['quantity'];
												echo '<br><b>Unit Price</b>: ' . $row['unit_price'];
                                                echo '<br><b>Single Vision</b>: ' . $row['s_sph_right'].$row['s_sph_left'].$row['s_cyl_right'].$row['s_cyl_left'].$row['s_axis_right'].$row['s_axis_left'];
                                                echo '<br><b>Progressive Vision</b>: ' . $row['p_sph_right'] . $row['p_sph_left'] . $row['p_cyl_right'] . $row['p_cyl_left'] . $row['p_axis_right'] . $row['p_axis_left'] . $row['p_add_right'] . $row['p_add_left'] ;
                                                echo '<br><b>PD Numbers</b>: ' . $row['s_one_pd'] . $row['s_two_pd_right'] . $row['s_two_pd_left'] . $row['p_one_pd'] . $row['p_two_pd_right'] . $row['p_two_pd_left']  ;
                                                                                               

                                                echo '<br><br>';
                                                ?><br>

	<?php	}
?>
	
                            </td>

 <td class="col-md-3"><i class="fa fa-inr"></i>
							<?php
$statement7 = $pdo->prepare("SELECT SUM(quantity) AS 'count_col' FROM tbl_order WHERE payment_id=?  ");
$statement7->execute(array($p_id));

$result7 = $statement7->fetchAll(PDO::FETCH_ASSOC);
foreach ($result7 as $row7) {?>
<?php

    $sum = $row7['count_col'];
    echo '<b>' . $sum . ' </b>';?>
<?php
}?> </td>

	
                      
                            <td class="col-md-3"><i class="fa fa-inr"></i> <?php echo $paid;?> ETB <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                        </tr>
                   
                        <tr>
                           
                            <td class="text-right"><h2><strong> </strong></h2></td>
                            <td class="text-right"><h2><strong>Total Amount: </strong></h2></td>
                            <td class="text-left text-success"><h2><strong><?php echo $paid; echo ' ETB';?></strong></h2></td>
                        </tr>
                    </tbody>
                </table>
</div>
            </div>
	
			<div class="row" >
				<div class="receipt-header receipt-header-mid receipt-footer">
					<div class="col-xs-8 col-sm-8 col-md-8 text-left">
						<div class="receipt-right">
							<p><b>Date :</b> <?php echo $p_date;?></p>
							<h5 style="color: rgb(140, 140, 140);">Thanks for shopping with NewOnlineOptics.!</h5>
								<button  class="btn btn-info "  id="printPageButton" onclick="printFunction()">Print Receipt!</button>
							
						</div>
					</div>
					<!--<div class="col-xs-4 col-sm-4 col-md-4" style="white-space: nowrap;"  >-->
					<!--	<div class="receipt-left" style="padding: 1px 2px;">-->
					<!--		<button  class="btn btn-info "  id="printPageButton" onclick="printFunction()">Print Receipt!</button>-->
					<!--	</div>-->
					<!--</div>-->
				</div>
            </div>
			
        </div>    
	</div>
</div>

 <script>
      function printFunction() { 
        window.print(); 
      }
    </script>
</body>

</html>