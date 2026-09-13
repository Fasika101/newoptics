<?php
	header('Content-Type:application/json; charset=utf-8');
	$api = 'http://196.188.120.3:11443/service-openup/toTradeWebPay';
	$appkey = '8f1946d52dca42d28768e61109a02377';
	$publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk+bRj+6A0Qsx3vOlJVj1yTx4xQXla4Af9v3LFdj7/19/TTr4FQmTQMAFscrq659C9uwnN4IoMK1VivuCr3dC5kGC2HKwOIYZjFgjBm/mUWzYFjqzbtXJlowyMhwswm0vobadEb4B+osq+8kDtAUrrmDDmCRnVdXPNtSNDZ7sTfOaH+PEGHQ97xHVe/LfBGBFU179PF5p2ULNaP2shtwZ3yCzvnBrnt3dMImF5g5DL4+SsI8AfmS4YX9n5QGBzcZdJwrIKavVQxcARG8JvVfYdcT/pArGwdVuZ1C5Nc5V+4QzjrpVBDz1/l9iHEysgyOyRBIO88nshzfGFWpRdB4UbwIDAQAB';

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
	
	$result = [
		'encode' => json_encode($encode),
		'sign' => $sign['values'],
		'ussd' => json_encode($ussd)
	];
	echo json_encode($result);
	
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
?>