<?php require_once('header.php'); ?>
    <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
   
<style>
  
@import url("https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap");
h3{
    margin: 15px 0 25px;
    text-align: center;
    font-size: 25px;
}
h4{
    margin: 15px 0 25px;
    text-align: center;
    font-size: 20px;
}


input{
    color:#022255 !important;
}
input[type=email]:focus,
input[type=password]:focus,
input[type=text]:focus{
    box-shadow: 0 0 5px rgba(246, 190, 8 ,0.8);
    border: 1px solid rgba(246, 190, 8 ,0.8);
}


.form-horizontal {
    width: 520px;
    background-color: #ffffff;
    padding: 25px 38px;
    border-radius: 12px;
    box-shadow: 2px 2px 15px rgba(0,0,0,0.5);
}
.control-label {
    text-align: left !important;
    padding-bottom: 4px;
}
.progress {
    height: 3px !important;
}
.form-group {
    margin-bottom: 10px;
}
.show-pass{
    position: absolute;
    top:5%;
    right: 8%;
}
.progress-bar-danger {
    background-color: #e90f10;
}
.progress-bar-warning{
    background-color: #ffad00;
}
.progress-bar-success{
    background-color: #02b502;
}
.login-btn{
    width: 180px !important;
    background-image: linear-gradient(to right, #f6086e , #ff133a) !important;
    font-size: 18px;
    color: #fff;
    margin: 0 auto 5px;
    padding: 8px 0; 
}
.login-btn:hover{
    background-image: linear-gradient(to right, rgba(255, 0, 111, 0.8) , rgba(247, 2, 43, 0.8)) !important;
    color: #fff !important;
}
.fa-eye{
    color: #022255;
    cursor: pointer;
}
.ex-account p a{
    color: #f6086e;
    text-decoration: underline;
}
.fa-circle{
    font-size: 6px;  
}
.fa-check{
    color: #02b502;
}
</style>

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
<!-- <script src="assets/country/country.js"></script> -->
<?php
if (isset($_POST['form1'])) {

    $valid = 1;
if (empty($_POST['cust_name'])) {
    $valid = 0;
    $error_message .='First Name Cannot be empty.';
}
if (empty($_POST['cust_lname'])) {
    $valid = 0;
    $error_message .= 'Last Name Cannot be empty.' ;
}

if (empty($_POST['policy'])) {
    $valid = 0;
    $error_message .= 'You must agree to our Shipping and Return Policy. Check the box to agree.';
}




if (empty($_POST['cust_email'])) {
    $valid = 0;
    $error_message .= LANG_VALUE_131 . "<br>";
} else {
    if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
        $valid = 0;
        $error_message .= LANG_VALUE_134 . "<br>";
    } else {
        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
        $statement->execute(array($_POST['cust_email']));
        $total = $statement->rowCount();
        if ($total) {
            $valid = 0;
            $error_message .= LANG_VALUE_147 . "";
        }
    }
}

    

   if (empty($_POST['cust_phone'])) {
    $valid = 0;
    $error_message .= LANG_VALUE_124;
} else {
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_phone=?");
    $statement->execute(array($_POST['cust_phone']));
    $total = $statement->rowCount();
    if ($total) {
        $valid = 0;
        $error_message .= 'Phone Number already exists' . " ";
    }
}


    if( empty($_POST['cust_password']) || empty($_POST['cust_re_password']) ) {
        $valid = 0;
        $error_message .= LANG_VALUE_138;

 
    }

    if( !empty($_POST['cust_password']) && !empty($_POST['cust_re_password']) ) {
        if($_POST['cust_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message .= LANG_VALUE_139;
}
    }

 if( $_POST['cust_password'] == $_POST['cust_re_password'] ) {
            // Validate password strength
           $password = $_POST['cust_password'];

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);


if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
    $valid = 0;

    $error_message = 'Password should be at least 8 characters in length and should include at least one Upper Case letter and One Numeral .';
} else {
    
} 
     
    }


    if($valid == 1) { 

        $token = md5(time());
        $cust_datetime = date('Y-m-d h:i:s');
        $cust_timestamp = time();

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_customer (
                                        cust_name,
                                        cust_lname,
                                        cust_cname,
                                        cust_email,
                                        cust_phone,
                                        
                                      
                                        cust_password,
                                        cust_token,
                                        cust_datetime,
                                        cust_timestamp,
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['cust_name']),
                                        strip_tags($_POST['cust_lname']),
                                         strip_tags($_POST['cust_cname']),
                                        strip_tags($_POST['cust_email']),
                                        strip_tags($_POST['cust_phone']),
                                        
                                       
                                        
                                        md5($_POST['cust_password']),
                                        $token,
                                        $cust_datetime,
                                        $cust_timestamp,
                                        0
                                    ));
                                //     $link_address = 'login.php';
                                //    // $success_message = header("location: " . "login.php");
                                //     $success_message=  'Registration was successful . You can now login! <br><a href=' . $link_address . '>Go Login Page</a></div>';

      
      
                                     // Send email for confirmation of the account
        $to = $_POST['cust_email'];
        
        $subject = LANG_VALUE_150;
        $contact_email1 = 'newonlineoptics@gmail.com';
        $verify_link = BASE_URL.'verify.php?email='.$to.'&token='.$token;
        $message = '
'.LANG_VALUE_151.'<br><br>

<a href="'.$verify_link.'">'.$verify_link.'</a>';
	
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
$to = $_POST['cust_email'];
        
        try {
         
$mail->setFrom('newonlineoptics@gmail.com', 'New Online Optics');
$mail->addAddress($to);

$mail->isHTML(true);
$mail->Subject = LANG_VALUE_150;

//$subject = 'LANG_VALUE_150';

$message = '' . LANG_VALUE_151 . '<br><br> <a href="' . $verify_link . '">' . $verify_link . '</a>';
$message_sms = ''  . '<br><br> <a href="' . $verify_link . '">' . $verify_link . '</a>';
$phone_sms = $_POST['cust_phone'];



$mail->Body = $message;
 
    	    $mail->send();

    	    $success_message = $receive_email_thank_you_message;    
    	} catch (Exception $e) {
    	    echo 'Message could not be sent.';
    	    echo 'Mailer Error: ' . $mail->ErrorInfo;
    	}

      

        unset($_POST['cust_name']);
        unset($_POST['cust_cname']);
        unset($_POST['cust_email']);
        unset($_POST['cust_phone']);
       

        $success_message = LANG_VALUE_152;
    
                                }
}
?>
                           <?php
 if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}
if ($success_message != '') {
   echo "<script>alert('" . $success_message . "')</script>";


     header('location: registration_success.php?name=' . $_REQUEST['cust_name']);
}
?>


                             <!-- <?php
// if ($error_message != '') {
//     echo "<script>alert('" . $error_message . "')</script>";
// }
// if ($success_message != '') {
//     echo "<script>alert('" . $success_message . "')</script>";
// }
?> -->
 
<div class="page" style="padding-top: 50px;">
    <div class="container" style="display: flex;
    justify-content: center;
    align-items: center;
    width: 100&;">
     <form class="form-horizontal" id="validateForm" action="" method="post">
            <?php
// if ($error_message != '') {
//     echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $error_message . "</div>";
// }
// if ($success_message != '') {
//     echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
// }
?>

            <h3>New Online Optics </h3>
            <h4>Registration Form</h4>
            
            <fieldset>
                <!-- Email input-->
                <div class="form-group">
                     <label class="col-md-12 control-label" for="textinput">First Name</label>
                     <div class="col-md-12">
                        <input id="email" name="cust_name" type="text" autocomplete="off" placeholder="Enter your First Name" class="form-control input-md">
                    </div>
                </div>
                 <div class="form-group">
                     <label class="col-md-12 control-label" for="textinput">Father Name</label>
                     <div class="col-md-12">
                        <input id="email" name="cust_lname" type="text" autocomplete="off" placeholder="Enter your Father Name" class="form-control input-md">
                    </div>
                </div>
                 <div class="form-group">
                     <label class="col-md-12 control-label"  for="phone">Phone Number</label></label>
                       <div class="col-md-12">
                            <input style=" margin-bottom: 5px;" type="tel" id="phone" name="cust_phone" placeholder="0911223344" pattern="[0-9]{10}" class="form-control input-md" required >
                            <span style="padding: 2px; color: white; border-radius: 3px; background-color:#f1b70a !important;" >Format: <b>0911223344</b></span>
                            </div>
                </div>
               
                <div class="form-group">
                    <label class="col-md-12 control-label" for="textinput">
                        Email
                    </label>
                    <div class="col-md-12">
                        <input id="email" name="cust_email" type="email" autocomplete="off" placeholder="Enter your email address" class="form-control input-md">
                    </div>
                </div>
                
                <!-- Password input-->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="passwordinput">
                        Password
                    </label>
                    <div class="col-md-12">
                        <input id="password" class="form-control input-md" name="cust_password" type="password" placeholder="Enter your password" >
                        <br>
                        <label for=""><?php echo 'Re-type Password'; ?> </label>
                        <input type="password" placeholder="Re-enter your password " class="form-control" name="cust_re_password">
                     
                        <span class="show-pass" onclick="toggle()">
                            <i class="far fa-eye" onclick="myFunction(this)"></i>
                        </span>
                        <div id="popover-password">
                            <p><span id="result"></span></p>
                            <div class="progress">
                                <div id="password-strength" 
                                    class="progress-bar" 
                                    role="progressbar" 
                                    aria-valuenow="40" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100" 
                                    style="width:0%">
                                </div>
                            </div>
                            <ul class="list-unstyled">
                                <li class="">
                                    <span class="low-upper-case">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Lowercase &amp; Uppercase
                                    </span>
                                </li>
                                <li class="">
                                    <span class="one-number">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Number (0-9)
                                    </span> 
                                </li>
                                <!-- <li class="">
                                    <span class="one-special-char">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Special Character (!@#$%^&*)
                                    </span>
                                </li> -->
                                <li class="">
                                    <span class="eight-character">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Atleast 8 Character
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                                
                </div>
                <div class="form-group">
                    <input  type="hidden" class="form-control" name="cust_cname" value="Individual">
                </div>

                 <?php

$statement = $pdo->prepare("SELECT * FROM tbl_post WHERE post_id ='11' ");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
  
    ?>

     <div class="form-check">
                      
                                    <input name="policy" class="form-check-input" type="checkbox"  value="free glass box"  id="1">
                                      <span style="color: #525252 !important; font-size: 12px !important;" class="form-check-label" for="1" >
                                             <!-- I have read and agreed to <a>Terms of Service</a> and <a>Privacy Policy</a>. -->
			<b>	 I have read and agreed to <a href="blog-single.php?slug=<?php echo $row['post_slug']; ?>"><?php echo $row['post_title']; ?> </a> of New Online Optics.</b>

                                     </span>
                             </div> 
<?php
            }
?>		             
                 
                <!-- Button -->
                <div class="form-group ">
                    <label for=""></label>
                    <input type="submit" class="btn login-btn btn-block" style="color: #fff !important;" value="Create Account" name="form1">
                </div>
                <div class="ex-account text-center">
                    <p>Already have an account? Signin 
                        <a href="login.php">here</a>
                    </p>
                    <div class="divider"></div>
                </div>
            </fieldset>
        </form>   
    </div>
</div>

<?php require_once('footer.php'); ?>

<script>
let state = false;
let password = document.getElementById("password");
let passwordStrength = document.getElementById("password-strength");
let lowUpperCase = document.querySelector(".low-upper-case i");
let number = document.querySelector(".one-number i");
// let specialChar = document.querySelector(".one-special-char i");
let eightChar = document.querySelector(".eight-character i");

password.addEventListener("keyup", function(){
    let pass = document.getElementById("password").value;
    checkStrength(pass);
});

function toggle(){
    if(state){
        document.getElementById("password").setAttribute("type","password");
        state = false;
    }else{
        document.getElementById("password").setAttribute("type","text")
        state = true;
    }
}

function myFunction(show){
    show.classList.toggle("fa-eye-slash");
}

function checkStrength(password) {
    let strength = 0;

    //If password contains both lower and uppercase characters
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
        strength += 1;
        lowUpperCase.classList.remove('fa-circle');
        lowUpperCase.classList.add('fa-check');
    } else {
        lowUpperCase.classList.add('fa-circle');
        lowUpperCase.classList.remove('fa-check');
    }
    //If it has numbers and characters
    if (password.match(/([0-9])/)) {
        strength += 1;
        number.classList.remove('fa-circle');
        number.classList.add('fa-check');
    } else {
        number.classList.add('fa-circle');
        number.classList.remove('fa-check');
    }
    //If it has one special character
    // if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
    //     strength += 1;
    //     specialChar.classList.remove('fa-circle');
    //     specialChar.classList.add('fa-check');
    // } else {
    //     specialChar.classList.add('fa-circle');
    //     specialChar.classList.remove('fa-check');
    // }
    //If password is greater than 7
    if (password.length > 7) {
        strength += 1;
        eightChar.classList.remove('fa-circle');
        eightChar.classList.add('fa-check');
    } else {
        eightChar.classList.add('fa-circle');
        eightChar.classList.remove('fa-check');   
    }

    // If value is less than 2
    if (strength < 2) {
        passwordStrength.classList.remove('progress-bar-warning');
        passwordStrength.classList.remove('progress-bar-success');
        passwordStrength.classList.add('progress-bar-danger');
        passwordStrength.style = 'width: 10%';
    } else if (strength == 3) {
        passwordStrength.classList.remove('progress-bar-success');
        passwordStrength.classList.remove('progress-bar-danger');
        passwordStrength.classList.add('progress-bar-warning');
        passwordStrength.style = 'width: 60%';
    } else if (strength == 4) {
        passwordStrength.classList.remove('progress-bar-warning');
        passwordStrength.classList.remove('progress-bar-danger');
        passwordStrength.classList.add('progress-bar-success');
        passwordStrength.style = 'width: 100%';
    }
}
</script>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>