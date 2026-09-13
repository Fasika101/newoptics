<?php require_once('header.php'); ?>

	<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
    $contact_email = $row['contact_email'];
    $receive_email_thank_you_message = $row['receive_email_thank_you_message'];
}
?>
<style type="text/css">

    body
    {
        background:#f2f2f2;
    }

    .payment
	{
		border:1px solid #f2f2f2;
		height:280px;
        border-radius:20px;
        background:#fff;
	}
   .payment_header
   {
	   background:rgba(50,205,50);
	   padding:20px;
       border-radius:20px 20px 0px 0px;
	   
   }
   
   .check
   {
	   margin:0px auto;
	   width:50px;
	   height:50px;
	   border-radius:100%;
	   background:#fff;
	   text-align:center;
   }
   
   .check i
   {
	   vertical-align:middle;
	   line-height:50px;
	   font-size:30px;
   }

    .content 
    {
        text-align:center;
        padding-right: 20px;
        padding-left: 20px;
    }

    .content  h1
    {
        font-size:25px;
        padding-top:25px;
    }

    .content a
    {
        width:200px;
        height:35px;
        color:#fff;
        border-radius:30px;
        padding:5px 10px;
        background:rgba(0,255,0);
        transition:all ease-in-out 0.3s;
    }

    .content a:hover
    {
        text-decoration:none;
        background:#000;
    }
    .ff{
        display: flex;
  justify-content: center;
  align-items: center;
    }
    
   
</style>

<?php


					if(isset($_SESSION['customer'])) {
					    
					    $tx_ref = ""; 
    if(isset($_SESSION['tx_ref'])){
        $tx_ref = $_SESSION['tx_ref'];
    }
      $email = ""; // set var to avoid errors
    if(isset($_SESSION['email'])){
        $email = $_SESSION['email'];
    }
    

$curl = curl_init();

//$url = 'https://api.chapa.co/v1/transaction/verify/' . $tx_ref;
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.chapa.co/v1/transaction/verify/' . $tx_ref ,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . CHAPA_SECRET_KEY
    ),
));

// Use the project CA bundle when the server has none (local WAMP)
$ca_bundle = __DIR__ . '/admin/inc/cacert.pem';
if (file_exists($ca_bundle)) {
    curl_setopt($curl, CURLOPT_CAINFO, $ca_bundle);
}

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {

    // Only mark the payment Completed when Chapa confirms it succeeded.
    // Before this check, simply landing on this page marked any payment
    // as Completed, even failed or cancelled ones.
    $verify = json_decode($response, true);
    $payment_verified = isset($verify['status'], $verify['data']['status'])
        && $verify['status'] === 'success'
        && $verify['data']['status'] === 'success';

    if ($payment_verified) {

   $statement = $pdo->prepare("UPDATE tbl_payment SET payment_status=? WHERE payment_id=?");
   $statement->execute(array('Completed', $tx_ref));
 
                                   // Send email for confirmation of the account
$email = $_SESSION['customer']['cust_email'];
$name = $_SESSION['customer']['cust_name'];





if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}


$to = $email;
//$tx_ref = $_SESSION['tx_ref'];


//$pay_id = $row['payment_id'];


$subject = 'Receipt';
$contact_email1 = 'newonlineoptics@gmail.com';
$verify_link = BASE_URL . 'customer_receipt.php?payment_id=' . $tx_ref;

//$verify_link = BASE_URL . 'verify.php?email=' . $to . '&token=' . $token;
//$message = 'Payment success';

$message = 'Hey' . $name . '!<br>Heres your payment receipt.<br><a href="' . $verify_link . '">' . $verify_link . '</a>';

require "assets/mail/PHPMailerAutoload.php";

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';

$mail->Username = 'newonlineoptics@gmail.com';
$mail->Password = 'hucbigesmvfsewcq';

//ufjuxtkjzbumbcyy

$to = $email;

try {

    $mail->setFrom('newonlineoptics@gmail.com', 'New Online Optics');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = 'Payment Detail Invoice';

//$subject = 'LANG_VALUE_150';


    $message = 'Hey '. $name.',<br>Here is your payment receipt. Click on the link below to view.<br><a href="' . $verify_link . '">' . $verify_link . '</a>';

    $message_sms = 'Payment Success';
    //$phone_sms = $_POST['cust_phone'];

    $mail->Body = $message;

    $mail->send();

    $success_message = $receive_email_thank_you_message;
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}


    } // end $payment_verified

// $_SESSION['txref'] = $url;
	
}

					    
					    
						?>
<div class="page">
                
    <div class="container">
   <div class="row" >
    <div class="ff">
      <div class="col-md-6 mx-auto mt-5">
         <div class="payment">
            <div class="payment_header">
               <div class="check"><i class="fa fa-check" aria-hidden="true"></i></div>
            </div>
            <div class="content">
               <h1>Payment Successful!</h1>
               <p>
                   
                   Payment Reference code sent to New online optics Admin. 
                   Hold on to your Telebirr mobile text and wait untill we approve your order with in 24 hours.
                   <br>
                   Call +251 919 485109 to follow up
                   </p>
               <a href="dashboard.php">Go to Dashboard</a>
            </div>
            
         </div>
      </div>
      </div>
   </div>
</div>
         
</div>

						<?php
					} else {
					    
					   
$i = 0;
foreach ($_SESSION['guest_email'] as $key => $value) {
    $i++;
    $arr_add_email[$i] = $value;

    $jemail = $arr_add_email[$i];

}
$i = 0;
foreach ($_SESSION['guest_first_name'] as $key => $value) {
    $i++;
    $arr_add_name[$i] = $value;

    $fname =  $arr_add_name[$i];

}
	    
					    $tx_ref = ""; 
    if(isset($_SESSION['tx_ref'])){
        $tx_ref = $_SESSION['tx_ref'];
    }

$to = $jemail;
//$tx_ref = $_SESSION['tx_ref'];


//$pay_id = $row['payment_id'];


$subject = 'Receipt';
$contact_email1 = 'newonlineoptics@gmail.com';
$verify_link = BASE_URL . 'customer_receipt.php?payment_id=' . $tx_ref;

//$verify_link = BASE_URL . 'verify.php?email=' . $to . '&token=' . $token;
//$message = 'Payment success';

$message = 'Hey' . $fname . '!<br>Heres your payment receipt.<br><a href="' . $verify_link . '">' . $verify_link . '</a>';

require "assets/mail/PHPMailerAutoload.php";

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';

$mail->Username = 'newonlineoptics@gmail.com';
$mail->Password = 'hucbigesmvfsewcq';

//ufjuxtkjzbumbcyy

$to = $jemail;

try {

    $mail->setFrom('newonlineoptics@gmail.com', 'New Online Optics');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = 'Payment Detail Invoice';

//$subject = 'LANG_VALUE_150';


    $message = 'Hey '. $fname.',<br>Here is your payment receipt. Click on the link below to view.<br><a href="' . $verify_link . '">' . $verify_link . '</a>';

    $message_sms = 'Payment Success';
    //$phone_sms = $_POST['cust_phone'];

    $mail->Body = $message;

    $mail->send();

    $success_message = $receive_email_thank_you_message;
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
						?>
						
					<div class="page">
                
    <div class="container">
   <div class="row" >
    <div class="ff">
      <div class="col-md-6 mx-auto mt-5">
         <div class="payment">
            <div class="payment_header">
               <div class="check"><i class="fa fa-check" aria-hidden="true"></i></div>
            </div>
            <div class="content">
               <h1>Payment Successful!</h1>
               <p>
                   
                   Dear, <?php echo $fname;?> your Payment Reference code has been sent to New online optics Admin and a copy has been forwarded to your email.
                   We will approve your order with in 24 hours.
                   <br>
                   Call +251 919 485109 to follow up.
                   </p>
              
            </div>
            
         </div>
      </div>
      </div>
   </div>
</div>
         
</div>
					
					
					
						<?php	
					}
					?>


<?php require_once('footer.php'); ?>