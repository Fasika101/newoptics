<?php
ob_start();
session_start();
include("../../admin/inc/config.php");
include("../../admin/inc/functions.php");
// Getting all language variables into array as global variable
$i=1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	define('LANG_VALUE_'.$i,$row['lang_value']);
	$i++;
}
?>
<?php
if( !isset($_REQUEST['msg']) ) {
	if(empty($_POST['transaction_info'])) {
		header('location: ../../checkout.php');
	} else {
		$payment_date = date('Y-m-d H:i:s');
	    $payment_id = time();

	    $statement = $pdo->prepare("INSERT INTO tbl_payment (   
	                            customer_id,
	                            customer_name,
	                            customer_email,
								customer_drop,
								customer_phone,
	                            payment_date,
	                            txnid, 
	                            paid_amount,
	                            card_number,
	                            card_cvv,
	                            card_month,
	                            card_year,
	                            bank_transaction_info,
	                            payment_method,
	                            payment_status,
	                            shipping_status,
	                            payment_id,
								cust_type,
								company_drop_address,
								company_pickup_name,
								company_pickup_number,
								partner_drop_address,
								partner_pickup_name,
								partner_pickup_number,
								promo_code,
								promo_amount,
								discount
	                        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	    $statement->execute(array(
	                            $_SESSION['customer']['cust_id'],
	                            $_SESSION['customer']['cust_name'],
	                            $_SESSION['customer']['cust_email'],
								$_SESSION['customer']['cust_drop_address'],
								$_SESSION['customer']['cust_phone'],
	                            $payment_date,
	                            '',
	                            $_POST['amount'],
	                            '', 
	                            '',
	                            '', 
	                            '',
	                            $_POST['transaction_info'],
	                            'Bank Deposit',
	                            'Pending',
	                            'Pending',
	                            $payment_id,
								$_SESSION['customer']['cust_cname'],
								$_SESSION['customer']['company_drop_address'],
								$_SESSION['customer']['company_pickup_name'],
								$_SESSION['customer']['company_pickup_number'],
								$_SESSION['customer']['partner_drop_address'],
								$_SESSION['customer']['partner_pickup_name'],
								$_SESSION['customer']['partner_pickup_number'],
								$_POST['promo_c'],
								$_POST['promo_a'],
								$_POST['discount_price']
	                        ));

							//additional detail
$i = 0;
foreach ($_SESSION['cart_add_detail'] as $key => $value) {
    $i++;
    $arr_add_detail[$i] = $value;
}


//prescription_photo
$i = 0;
foreach ($_SESSION['pres_photo_upload'] as $key => $value) {
    $i++;
    $arr_pres_photo[$i] = $value;
}


////////////////////////////////////////////////////
	    $i=0;
	    foreach($_SESSION['cart_p_id'] as $key => $value) 
	    {
	        $i++;
	        $arr_cart_p_id[$i] = $value;
	    }

	    $i=0;
	    foreach($_SESSION['cart_p_name'] as $key => $value) 
	    {
	        $i++;
	        $arr_cart_p_name[$i] = $value;
	    }

	    $i=0;
	    foreach($_SESSION['cart_size_name'] as $key => $value) 
	    {
	        $i++;
	        $arr_cart_size_name[$i] = $value;
	    }

	    $i=0;
	    foreach($_SESSION['cart_color_name'] as $key => $value) 
	    {
	        $i++;
	        $arr_cart_color_name[$i] = $value;
	    }

	    $i=0;
	    foreach($_SESSION['cart_p_qty'] as $key => $value) 
	    {
	        $i++;
	        $arr_cart_p_qty[$i] = $value;
	    }

	    $i=0;
	    foreach($_SESSION['cart_p_current_price'] as $key => $value) 
	    {
	        $i++;
	        $arr_cart_p_current_price[$i] = $value;
	    }

		$i = 0;
		foreach ($_SESSION['cart_lens_type'] as $key => $value) {
			$i++;
			$arr_lens_type[$i] = $value;
		}

		//progressive vision

$i = 0;
foreach ($_SESSION['cart_progressive_sph_right'] as $key => $value) {
    $i++;
    $arr_progressive_sph_right[$i] = $value;

}
$i = 0;
foreach ($_SESSION['cart_progressive_cyl_right'] as $key => $value) {
    $i++;
    $arr_progressive_cyl_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_progressive_axis_right'] as $key => $value) {
    $i++;
    $arr_progressive_axis_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_progressive_add_right'] as $key => $value) {
    $i++;
    $arr_progressive_add_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_progressive_sph_left'] as $key => $value) {
    $i++;
    $arr_progressive_sph_left[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_progressive_cyl_left'] as $key => $value) {
    $i++;
    $arr_progressive_cyl_left[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_progressive_axis_left'] as $key => $value) {
    $i++;
    $arr_progressive_axis_left[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_progressive_add_left'] as $key => $value) {
    $i++;
    $arr_progressive_add_left[$i] = $value;
}
//single vision
$i = 0;
foreach ($_SESSION['cart_single_sph_right'] as $key => $value) {
    $i++;
    $arr_single_sph_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_single_cyl_right'] as $key => $value) {
    $i++;
    $arr_single_cyl_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_single_axis_right'] as $key => $value) {
    $i++;
    $arr_single_axis_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_single_sph_left'] as $key => $value) {
    $i++;
    $arr_single_sph_left[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_single_cyl_left'] as $key => $value) {
    $i++;
    $arr_single_cyl_left[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_single_axis_left'] as $key => $value) {
    $i++;
    $arr_single_axis_left[$i] = $value;
}

//fetch pd numbers
$i = 0;
foreach ($_SESSION['cart_s_one_pd'] as $key => $value) {
    $i++;
    $arr_s_one_pd[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_s_two_pd_right'] as $key => $value) {
    $i++;
    $arr_s_two_pd_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_s_two_pd_left'] as $key => $value) {
    $i++;
    $arr_s_two_pd_left[$i] = $value;
}

$i = 0;
foreach ($_SESSION['cart_one_pd'] as $key => $value) {
    $i++;
    $arr_one_pd[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_two_pd_right'] as $key => $value) {
    $i++;
    $arr_two_pd_right[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_two_pd_left'] as $key => $value) {
    $i++;
    $arr_two_pd_left[$i] = $value;
}

	    $i=0;
	    $statement = $pdo->prepare("SELECT * FROM tbl_product");
	    $statement->execute();
	    $result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	    foreach ($result as $row) {
	    	$i++;
	    	$arr_p_id[$i] = $row['p_id'];
	    	$arr_p_qty[$i] = $row['p_qty'];
	    }

	    for($i=1;$i<=count($arr_cart_p_name);$i++) {
	        $statement = $pdo->prepare("INSERT INTO tbl_order (
	                        product_id,
	                        product_name,
	                        size, 
	                        color,
							lens_type,
	                        quantity,
							add_detail,
							s_sph_right,
							s_sph_left,
							s_cyl_right,
							s_cyl_left,
							s_axis_right,
							s_axis_left, 
							p_sph_right,
							p_sph_left,
							p_cyl_right,
							p_cyl_left,
							p_axis_right,
							p_axis_left,
							p_add_right,
							p_add_left,
							s_one_pd,
							s_two_pd_right,
							s_two_pd_left,
							p_one_pd,
							p_two_pd_right,
							p_two_pd_left,
							
	                        unit_price, 
	                        payment_id,
							photo
	                        ) 
	                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	        $sql = $statement->execute(array(
	                        $arr_cart_p_id[$i],
	                        $arr_cart_p_name[$i],
	                        $arr_cart_size_name[$i],
	                        $arr_cart_color_name[$i],
							$arr_lens_type[$i], 
	                        $arr_cart_p_qty[$i],
							$arr_add_detail[$i],
							$arr_single_sph_right[$i],
							$arr_single_sph_left[$i],
							$arr_single_cyl_right[$i],
							$arr_single_cyl_left[$i],
							$arr_single_axis_right[$i],
							$arr_single_axis_left[$i],
							$arr_progressive_sph_right[$i],
							$arr_progressive_sph_left[$i],
							$arr_progressive_cyl_right[$i],
							$arr_progressive_cyl_left[$i],
							$arr_progressive_axis_right[$i],
							$arr_progressive_axis_left[$i],
							$arr_progressive_add_right[$i],
							$arr_progressive_add_left[$i],
							$arr_s_one_pd[$i],
							$arr_s_two_pd_right[$i],
							$arr_s_two_pd_left[$i],
							$arr_one_pd[$i],
							$arr_two_pd_right[$i],
							$arr_two_pd_left[$i],
	                        $arr_cart_p_current_price[$i],
	                        $payment_id,
							$arr_pres_photo[$i]
	                    ));

	        // Update the stock
            for($j=1;$j<=count($arr_p_id);$j++)
            {
                if($arr_p_id[$j] == $arr_cart_p_id[$i]) 
                {
                    $current_qty = $arr_p_qty[$j];
                    break;
                }
            }
            $final_quantity = $current_qty - $arr_cart_p_qty[$i];
            $statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
            $statement->execute(array($final_quantity,$arr_cart_p_id[$i]));
            
	    }
	    unset($_SESSION['cart_p_id']);
	    unset($_SESSION['cart_size_id']);
	    unset($_SESSION['cart_size_name']);
	    unset($_SESSION['cart_color_id']);
	    unset($_SESSION['cart_color_name']);
		unset($_SESSION['cart_p_qty']);
	    unset($_SESSION['cart_p_current_price']);
	    unset($_SESSION['cart_p_name']);
	    unset($_SESSION['cart_p_featured_photo']);

	    header('location: ../../payment_success.php');
	}
}
?>