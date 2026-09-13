
<?php

ob_start();
session_start();
require_once '../../admin/inc/config.php';

// Getting all language variables into array as global variable




	header('Content-Type:application/json; charset=utf-8');



	$api = 'https://app.ethiomobilemoney.et:2121/ammapi/payment/service-openup/toTradeWebPay';
	$appkey = 'bbc116ded5d44faaa8e5f772ff6ca502';
	$publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAin6vaUQve7IIXPBD1/gP7a0HMNsddCRiv2zVV4A9o4i9bStP/DcpkIHYaUlzNFcPkvBpckUMPobPCTkE2fD/KH6zPgTaEofnu8ozyGPU6doAzdjqbIkwPANdnfFqKnflqYU/ebW687ja3BrD0x22mFA5zW+DcBZNLdOdKU9UiA9qjr2aHvtyN43udCx4FalYIVaZEBAzmWYKgHFBA0TzFxycusIjQTVZ+906GknMOfm6t5qUmPeZGX5hxg+/xGKUhrjB2LqUeimFWAL392vzG8wb9R5VnEJPp5c5huQwv4UA3+9c1e7Lv2FYCx8EDuJWi6HbLq4nPsOFZeTE1lymQwIDAQAB';

	$data=[
		'outTradeNo' => $_POST['outTradeNo'],
		'subject' => $_POST['subject'],
		'totalAmount' => $_POST['totalAmount'],
		'shortCode' => $_POST['shortCode'],
		'notifyUrl' => $_POST['notifyUrl'],
		'returnUrl' => $_POST['returnUrl'],
		'receiveName' => $_POST['receiveName'],
		'appId' => $_POST['appid'],
		'timeoutExpress' => $_POST['timeoutExpress'],
		'nonce' => $_POST['nonce'],
		'timestamp' => $_POST['timestamp']
    ];
	ksort($data);
	$ussd = $data;
	$data['appKey'] = $appkey;
	ksort($data);
	$sign = sign($data);
	$encode = [
		'appid' => $data['appId'],
		'sign' => $sign['sha256'],
		'ussd' => encryptRSA(json_encode($ussd),$publicKey)
	];
	
	list($returnCode, $returnContent) = http_post_json($api, json_encode($encode));
	if($returnCode == 200){
		$rsp = json_decode($returnContent,true);
		echo 'xxxxxxx'.$returnContent .'  \n'.$sign['values'];
		header('location:'.$rsp['data']['toPayUrl']);
$payment_date = date('Y-m-d H:i:s');

	//------   For Kidane  -----//
	  //here is how am storing payment information to my database
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
		
						$_SESSION['customer']['cust_id'],
						$_SESSION['customer']['cust_name'],
						$_SESSION['customer']['cust_email'],
						$_SESSION['customer']['cust_phone'],
						$_SESSION['customer']['cust_drop_address'],
						$payment_date,
						'',
						$_POST['totalAmount'],
						'',
						'', 
						'',
						'',
						'',
						'Telebirr',
						'Pending',
						'Pending',
						$_POST['outTradeNo'],
						$_SESSION['customer']['cust_cname'],
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
        $payment_id,

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


$i = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_product");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $i++;
    $arr_p_id[$i] = $row['p_id'];
    $arr_p_qty[$i] = $row['p_qty'];
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
	                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
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
		$arr_s_two_pd_left_type[$i],
        $arr_s_two_pd_right_type[$i],
        $arr_p_two_pd_left_type[$i],
        $arr_p_two_pd_right_type[$i],

        $arr_cart_p_current_price[$i],
        $_POST['outTradeNo'],
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
    for ($j = 1; $j <= count($arr_p_id); $j++) {
        if ($arr_p_id[$j] == $arr_cart_p_id[$i]) {
            $current_qty = $arr_p_qty[$j];
            break;
        }
    }
    $final_quantity = $current_qty - $arr_cart_p_qty[$i];
    $statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
    $statement->execute(array($final_quantity, $arr_cart_p_id[$i]));
    
     for ($k = 1; $k <= count($arr_product_color_id); $k++) {
        if ($arr_product_color_id[$k] == $arr_cart_p_id[$i] && $arr_color_id[$k] == $arr_cart_color_id[$i] ) {
            $current_color_qty = $arr_color_qty[$k];
            break;
        }
    }
    
    $final_color_quantity = $current_color_qty - $arr_cart_p_qty[$i];
    $statement = $pdo->prepare("UPDATE tbl_product_color SET color_qty=? WHERE p_id=? AND color_id=?");
    $statement->execute(array($final_color_quantity, $arr_cart_p_id[$i], $arr_cart_color_id[$i]));

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



	}else{
		echo 'Fail:'.$returnCode . '   '.$sign['values'];
	}
	
	function sign($params){
		$signPars = '';
		foreach($params as $k => $v){
			if($signPars == ''){
				$signPars = $k.'='.$v;
			}else{
				$signPars = $signPars.'&'.$k.'='.$v;
			}
		}
		$sign = [
			'sha256' => hash("sha256", $signPars),
			'values' => $signPars
		];
		return $sign;
	}
	
	function encryptRSA($data, $public){
		$pubPem = chunk_split($public, 64, "\n");
		$pubPem = "-----BEGIN PUBLIC KEY-----\n" . $pubPem . "-----END PUBLIC KEY-----\n";
		$public_key = openssl_pkey_get_public($pubPem); 
		if(!$public_key){
			die('invalid public key');
		}
		$crypto = '';
		foreach(str_split($data, 117) as $chunk){
			$return = openssl_public_encrypt($chunk, $cryptoItem, $public_key);
			if(!$return){
				return('fail');
			}
			$crypto .= $cryptoItem;
		}
		$ussd = base64_encode($crypto);
		return $ussd;
	}
	
	function http_post_json($url, $jsonStr){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Content-Length: ' . strlen($jsonStr)
			)
		);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return array($httpCode, $response);
	}


	
?>