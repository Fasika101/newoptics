<?php require_once 'header.php';?>
<script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"><link rel="stylesheet" href="./style.css">
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
    box-shadow: 0 0 5px rgba(246, 8, 110,0.8);
    border: 1px solid rgba(246, 8, 110,0.8);
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
    $banner_forget_password = $row['banner_forget_password'];
}
?>

<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if (empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_131 . "\\n";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= LANG_VALUE_134 . "\\n";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            $total = $statement->rowCount();
            if (!$total) {
                $valid = 0;
                $error_message .= LANG_VALUE_135 . "\\n";
            }
        }
    }

    if ($valid == 1) {

        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $forget_password_message = $row['forget_password_message'];
        }

        $token = md5(rand());
        $now = time();

        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_token=?,cust_timestamp=? WHERE cust_email=?");
        $statement->execute(array($token, $now, strip_tags($_POST['cust_email'])));
require "assets/mail/PHPMailerAutoload.php";

$mail = new PHPMailer;

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';

// h-hotel account
$mail->Username = 'newonlineoptics@gmail.com';
$mail->Password = 'hucbigesmvfsewcq';

// send by h-hotel email
$mail->setFrom('newonlineoptics@gmail.com', 'New Online Optics');
// get email from input
$mail->addAddress($_POST["cust_email"]);
//$mail->addReplyTo('lamkaizhe16@gmail.com');

$to = $_POST['cust_email'];

        $message = '<p>' . LANG_VALUE_142 . '<br> <a href="' . BASE_URL . 'reset-password.php?email=' . $_POST['cust_email'] . '&token=' . $token . '">Click here</a>';
$mail->isHTML(true);
$mail->Subject = "Recover your password";
$mail->Body = $message;
       
    

       $mail->send();


        $success_message = $forget_password_message;
    }
}
?>


<div class="page" style="padding-top: 50px;">
    <div class="container" style="display: flex;
    justify-content: center;
    align-items: center;
    width: 100&;">
        <form class="form-horizontal" id="validateForm" action="" method="post">
                            <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}
if ($success_message != '') {
    echo "<script>alert('" . $success_message . "')</script>";
}
?>
         <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";

}
if ($success_message != '') {
    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
}
?>
             <h3>New Online Optics </h3>
            <h4>Password Reset Form</h4>          
            <fieldset>
                <div class="form-group">
                     <label class="col-md-12 control-label" for="textinput">Email</label>
                     <div class="col-md-12">
                        <input  name="cust_email" type="email" autocomplete="off" placeholder="Enter your email" class="form-control input-md">
                    </div>
                </div>              
               
                <div class="form-group ">
                    <label for=""></label>
                    <input type="submit" class="btn login-btn btn-block" style="color: #fff !important;" value="Get Reset Link" name="form1">
                </div>
            </fieldset>
        </form>   
    </div>
</div>



<?php require_once 'footer.php';?>