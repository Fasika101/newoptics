<?php
	header('Content-Type:application/json; charset=utf-8');
	$content = file_get_contents('php://input');
	

	$api = 'http://196.188.120.3:11443/service-openup/toTradeWebPay';
	$appkey = '8f1946d52dca42d28768e61109a02377';
	$publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk+bRj+6A0Qsx3vOlJVj1yTx4xQXla4Af9v3LFdj7/19/TTr4FQmTQMAFscrq659C9uwnN4IoMK1VivuCr3dC5kGC2HKwOIYZjFgjBm/mUWzYFjqzbtXJlowyMhwswm0vobadEb4B+osq+8kDtAUrrmDDmCRnVdXPNtSNDZ7sTfOaH+PEGHQ97xHVe/LfBGBFU179PF5p2ULNaP2shtwZ3yCzvnBrnt3dMImF5g5DL4+SsI8AfmS4YX9n5QGBzcZdJwrIKavVQxcARG8JvVfYdcT/pArGwdVuZ1C5Nc5V+4QzjrpVBDz1/l9iHEysgyOyRBIO88nshzfGFWpRdB4UbwIDAQAB';
	$nofityData = decryptRSA($content, $publicKey);
	
	echo '{"code":0,"msg":"success"}';
	
	function decryptRSA($source, $key) {
		$pubPem = chunk_split($key, 64, "\n");
		$pubPem = "-----BEGIN PUBLIC KEY-----\n" . $pubPem . "-----END PUBLIC KEY-----\n";
		$public_key = openssl_pkey_get_public($pubPem); 
		if(!$public_key){
			die('invalid public key');
		}
		$decrypted='';//decode must be done before spliting for getting the binary String
		$data=str_split(base64_decode($source),256);
		foreach($data as $chunk){
			$partial = '';//be sure to match padding
			$decryptionOK = openssl_public_decrypt($chunk,$partial,$public_key,OPENSSL_PKCS1_PADDING);
			if($decryptionOK===false){die('fail');}
				$decrypted.=$partial;
			}
		return $decrypted;
	}
?>