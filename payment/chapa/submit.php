 <?php
ob_start();
session_start();
require_once '../../admin/inc/config.php';

$curl = curl_init();

$amountgg =  $_POST['amount'];
$amount =  $_POST['amount'];
$first = $_POST['first_name'];
$currecy = $_POST['currency'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$tx_ref = $_POST['tx_ref'];
$callback = $_POST['callback_url'];
$returnurl = $_POST['return_url'];
$_custtitle = $_POST['_custtitle'];
$description = $_POST['description'];


$cust_id_2 = $_POST['cust_id_2'];
$cust_name_2 = $_POST['cust_name_2'];
$cust_email_2 = $_POST['cust_email_2'];
$cust_phone_2 = $_POST['cust_phone_2'];
$cust_drop_2 = $_POST['cust_drop_2'];
$cust_type = $_POST['cust_type'];

 





 $_SESSION['amount'] = $_POST['amount'];
 
 
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.chapa.co/v1/transaction/initialize',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => "{
      \"amount\":\"" .$amount . "\",
       \"currency\":\"" .$currecy . "\",
       \"email\":\"" .$email . "\",
      \"first_name\":\"" .$first . "\",
      \"last_name\":\"" .$last . "\",
       \"phone_number\":\"" .$phone . "\",
      \"tx_ref\":\"" .$tx_ref . "\",
      \"callback_url\":\"" .$callback . "\",
      \"return_url\":\"" .$returnurl . "\",

       \"customization[title]\":\"" .$_custtitle . "\",
      \"customization[description]\":\"" .$description . "\"

  

  }",
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . CHAPA_SECRET_KEY,
        'Content-Type: application/json'
        
    ),
));

// Use the project CA bundle when the server has none (local WAMP)
$ca_bundle = __DIR__ . '/../../admin/inc/cacert.pem';
if (file_exists($ca_bundle)) {
    curl_setopt($curl, CURLOPT_CAINFO, $ca_bundle);
}

$response = curl_exec($curl);


$err = curl_error($curl);


curl_close($curl);


// Dawit1680389978

if ($err) {
    echo "cURL Error #:" . $err;
} else {
     $data = json_decode($response, true);

// If Chapa did not accept the transaction, stop here: no payment row,
// no order rows, no stock change. Otherwise phantom "orders" get created
// for payments that never even started.
if (!isset($data['status'], $data['data']['checkout_url']) || $data['status'] !== 'success') {
    $chapa_msg = isset($data['message']) ? (is_array($data['message']) ? json_encode($data['message']) : $data['message']) : 'Unknown error';
    echo 'Could not start the Chapa payment: ' . htmlspecialchars($chapa_msg) . '. Please go back and try again.';
    exit;
}

$url = $data['data']['checkout_url'];
header("location:" . $url);

 $_SESSION['tx_ref'] = $tx_ref;
 $_SESSION['amount'] = $amount;
 
 
$payment_date = date('Y-m-d H:i:s');

$statement = $pdo->prepare("INSERT INTO tbl_payment (
						customer_id,
						customer_name,
						customer_email,
						customer_phone,
						customer_drop,
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
						promo_code,
						promo_amount,
						discount
						)
						VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$sql = $statement->execute(array(
    
    
$cust_id_2,
$cust_name_2,
$cust_email_2,
$cust_phone_2, 
$cust_drop_2,
    $payment_date,
    '',
    $amount,
    '',
    '',
    '',
    '',
    '',
    'Chapa',
    'Pending',
    'Pending',
    $tx_ref,
    $cust_type,
    $_POST['promo_c'],
    $_POST['promo_a'],
    $_POST['discount_price']
));

global $pr;
$statement = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE cust_promo=?");
$statement->execute(array($_POST['promo_c']));
$result9 = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result9 as $row9) {
    $pr = $row9['cust_promo'];
}

if (!empty($_POST['promo_c']) != $pr) {

    $statement = $pdo->prepare("INSERT INTO tbl_withdraw (
                                        cust_promo,
                                        payment_date,
                                        payment_time

                                    ) VALUES (?,?,?)");
    $statement->execute(array(
        $_POST['promo_c'],
        $payment_date,
        $tx_ref

    ));
}

//additional detail
$i = 0;
foreach ($_SESSION['cart_add_detail'] as $key => $value) {
    $i++;
    $arr_add_detail[$i] = $value;
}

//progressive lens type remark
$i = 0;
foreach ($_SESSION['p_photosolar_check'] as $key => $value) {
    $i++;
    $arr_p_photosolar_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_photochromic_check'] as $key => $value) {
    $i++;
    $arr_p_photochromc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_white_check'] as $key => $value) {
    $i++;
    $arr_p_white_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_plasctic_check'] as $key => $value) {
    $i++;
    $arr_p_plascitlens_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['p_sunsensor_check'] as $key => $value) {
    $i++;
    $arr_p_sunsensor_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_glarefree_check'] as $key => $value) {
    $i++;
    $arr_p_glarefree_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_antiglare_check'] as $key => $value) {
    $i++;
    $arr_p_antiglare_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_arc_check'] as $key => $value) {
    $i++;
    $arr_p_arc_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['p_hmc_check'] as $key => $value) {
    $i++;
    $arr_p_hmc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_progressivelens_check'] as $key => $value) {
    $i++;
    $arr_p_progressivelens_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_bicfocal_check'] as $key => $value) {
    $i++;
    $arr_p_bicfocal_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_scratchresistant_check'] as $key => $value) {
    $i++;
    $arr_p_scratchresistant_check[$i] = $value;

}
//single lens type remark
$i = 0;
foreach ($_SESSION['s_photosolar_check'] as $key => $value) {
    $i++;
    $arr_s_photosolar_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_photochromic_check'] as $key => $value) {
    $i++;
    $arr_s_photochromc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_white_check'] as $key => $value) {
    $i++;
    $arr_s_white_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_plasctic_check'] as $key => $value) {
    $i++;
    $arr_s_plascitlens_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['s_sunsensor_check'] as $key => $value) {
    $i++;
    $arr_s_sunsensor_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_glarefree_check'] as $key => $value) {
    $i++;
    $arr_s_glarefree_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_antiglare_check'] as $key => $value) {
    $i++;
    $arr_s_antiglare_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_arc_check'] as $key => $value) {
    $i++;
    $arr_s_arc_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['s_hmc_check'] as $key => $value) {
    $i++;
    $arr_s_hmc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_progressivelens_check'] as $key => $value) {
    $i++;
    $arr_s_progressivelens_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_bicfocal_check'] as $key => $value) {
    $i++;
    $arr_s_bicfocal_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_scratchresistant_check'] as $key => $value) {
    $i++;
    $arr_s_scratchresistant_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['cart_p_id'] as $key => $value) {
    $i++;
    $arr_cart_p_id[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_color_id'] as $key => $value) {
    $i++;
    $arr_cart_color_id[$i] = $value;
}

$i = 0;
foreach ($_SESSION['cart_p_name'] as $key => $value) {
    $i++;
    $arr_cart_p_name[$i] = $value;
}

$i = 0;
foreach ($_SESSION['cart_size_name'] as $key => $value) {
    $i++;
    $arr_cart_size_name[$i] = $value;
}

$i = 0;
foreach ($_SESSION['cart_color_name'] as $key => $value) {
    $i++;
    $arr_cart_color_name[$i] = $value;
}

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

//prescription_photo
$i = 0;
foreach ($_SESSION['pres_photo_upload'] as $key => $value) {
    $i++;
    $arr_pres_photo[$i] = $value;
}

// type two pd
$i = 0;
foreach ($_SESSION['cart_s_two_pd_right_type'] as $key => $value) {
    $i++;
    $arr_s_two_pd_right_type[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_s_two_pd_left_type'] as $key => $value) {
    $i++;
    $arr_s_two_pd_left_type[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_p_two_pd_right_type'] as $key => $value) {
    $i++;
    $arr_p_two_pd_right_type[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_p_two_pd_left_type'] as $key => $value) {
    $i++;
    $arr_p_two_pd_left_type[$i] = $value;
}
// type two pd

$i = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_product");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $i++;
    $arr_p_id[$i] = $row['p_id'];
    $arr_p_qty[$i] = $row['p_qty'];
}

$i = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_product_color");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $i++;
    $arr_product_color_id[$i] = $row['p_id'];
    $arr_color_qty[$i] = $row['color_qty'];
    $arr_color_id[$i] = $row['color_id'];

}

for ($i = 1; $i <= count($arr_cart_p_name); $i++) {
    $statement = $pdo->prepare("INSERT INTO tbl_order (
	                        product_id,
	                        product_name,
	                        size,
	                        color,
							lens_type,
	                        quantity,
	                        color_id,
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
							s_two_pd_left_type,
							s_two_pd_right_type,
							p_two_pd_left_type,
							p_two_pd_right_type,

	                        unit_price,
	                        payment_id,
							photo,
							s_photosolar_check,
							s_photochromic_check,
							s_white_check,
							s_plasctic_check,
							s_sunsensor_check,
							s_glarefree_check,
							s_antiglare_check,
							s_arc_check,
							s_hmc_check,
							s_progressivelens_check,
							s_bicfocal_check,
							s_scratchresistant_check,
							p_photosolar_check,
							p_photochromic_check,
							p_white_check,
							p_plasctic_check,
							p_sunsensor_check,
							p_glarefree_check,
							p_antiglare_check,
							p_arc_check,
							p_hmc_check,
							p_progressivelens_check,
							p_bicfocal_check,
							p_scratchresistant_check
	                        )
	                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $sql = $statement->execute(array(
        $arr_cart_p_id[$i],
        $arr_cart_p_name[$i],
        $arr_cart_size_name[$i],
        $arr_cart_color_name[$i],
        $arr_lens_type[$i],
        $arr_cart_p_qty[$i],
        $arr_cart_color_id[$i],
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
        $arr_s_two_pd_left_type[$i],
        $arr_s_two_pd_right_type[$i],
        $arr_p_two_pd_left_type[$i],
        $arr_p_two_pd_right_type[$i],

        $arr_cart_p_current_price[$i],
        $tx_ref,
        $arr_pres_photo[$i],

        $arr_s_photosolar_check[$i],
        $arr_s_photochromc_check[$i],
        $arr_s_white_check[$i],
        $arr_s_plascitlens_check[$i],
        $arr_s_sunsensor_check[$i],
        $arr_s_glarefree_check[$i],
        $arr_s_antiglare_check[$i],
        $arr_s_arc_check[$i],
        $arr_s_hmc_check[$i],
        $arr_s_progressivelens_check[$i],
        $arr_s_bicfocal_check[$i],
        $arr_s_scratchresistant_check[$i],
        $arr_p_photosolar_check[$i],
        $arr_p_photochromc_check[$i],
        $arr_p_white_check[$i],
        $arr_p_plascitlens_check[$i],
        $arr_p_sunsensor_check[$i],
        $arr_p_glarefree_check[$i],
        $arr_p_antiglare_check[$i],
        $arr_p_arc_check[$i],
        $arr_p_hmc_check[$i],
        $arr_p_progressivelens_check[$i],
        $arr_p_bicfocal_check[$i],
        $arr_p_scratchresistant_check[$i]
    ));

    // Update the stock
    // for ($j = 1; $j <= count($arr_p_id); $j++) {
    //     if ($arr_p_id[$j] == $arr_cart_p_id[$i]) {
    //         $current_qty = $arr_p_qty[$j];
    //         break;
    //     }
    // }
 
     for ($k = 1; $k <= count($arr_product_color_id); $k++) {
        if ($arr_product_color_id[$k] == $arr_cart_p_id[$i] && $arr_color_id[$k] == $arr_cart_color_id[$i] ) {
            $current_color_qty = $arr_color_qty[$k];
            break;
        }
    }
    // $final_quantity = $current_qty - $arr_cart_p_qty[$i];
    // $statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
    // $statement->execute(array($final_quantity, $arr_cart_p_id[$i]));

    
    $final_color_quantity = $current_color_qty - $arr_cart_p_qty[$i];
    $statement = $pdo->prepare("UPDATE tbl_product_color SET color_qty=? WHERE p_id=? AND color_id=?");
    $statement->execute(array($final_color_quantity, $arr_cart_p_id[$i], $arr_cart_color_id[$i]));
    
$statement7 = $pdo->prepare("SELECT SUM(color_qty) AS 'count_col' FROM tbl_product_color WHERE p_id=?  ");
$statement7->execute(array($arr_cart_p_id[$i]));
$result7 = $statement7->fetchAll(PDO::FETCH_ASSOC);
foreach ($result7 as $row7) {

    $sum = $row7['count_col'];

}
$statement7 = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
$statement7->execute(array($sum, $arr_cart_p_id[$i]));



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



 
}



?>

