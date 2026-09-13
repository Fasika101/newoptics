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
    $banner_reset_password = $row['banner_reset_password'];
}
?>

<?php
if( !isset($_GET['email']) || !isset($_GET['token']) )
{
    header('location: '.BASE_URL.'login.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=? AND cust_token=?");
$statement->execute(array($_GET['email'],$_GET['token']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$tot = $statement->rowCount();
if($tot == 0)
{
    header('location: '.BASE_URL.'login.php');
    exit;
}
foreach ($result as $row) {
    $saved_time = $row['cust_timestamp'];
}

$error_message2 = '';
if(time() - $saved_time > 86400)
{
    $error_message2 = LANG_VALUE_144;
}

if(isset($_POST['form1'])) {

    $valid = 1;
    
    // if( empty($_POST['cust_new_password']) || empty($_POST['cust_re_password']) )
    // {
    //     $valid = 0;
    //     $error_message .= LANG_VALUE_140.'\\n';
    // }
    // else
    // {
    //     if($_POST['cust_new_password'] != $_POST['cust_re_password'])
    //     {
    //         $valid = 0;
    //         $error_message .= LANG_VALUE_139.'\\n';
    //     }
    // }  
    
    
if (empty($_POST['cust_new_password']) || empty($_POST['cust_re_password'])) {
    $valid = 0;
    $error_message .= LANG_VALUE_138;

}
if (!empty($_POST['cust_new_password']) && !empty($_POST['cust_re_password'])) {
    if ($_POST['cust_new_password'] != $_POST['cust_re_password']) {
        $valid = 0;
        $error_message .= LANG_VALUE_139;
    }
}
if ($_POST['cust_new_password'] == $_POST['cust_re_password']) {
    // Validate password strength
    $password = $_POST['cust_new_password'];

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);

    if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
        $valid = 0;

        $error_message = 'Password should be at least 8 characters in length and should include at least one Upper Case letter and One Numeral .';
    } else {
        $success_message = 'Strong Password.';
    }

}


    if($valid == 1) {

        $cust_new_password = strip_tags($_POST['cust_new_password']);
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_password=?, cust_token=?, cust_timestamp=? WHERE cust_email=?");
        $statement->execute(array(md5($cust_new_password),'','',$_GET['email']));
        
        header('location: '.BASE_URL.'reset-password-success.php');
    }

    
}
?>


<div class="page" style="padding-top: 50px;">
    <div class="container" style="display: flex;
    justify-content: center;
    align-items: center;
    width: 100&;">

         <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";

}
if ($success_message != '') {
    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
}
?>
       
           <form class="form-horizontal" id="validateForm"  action="" method="post">
                            <?php $csrf->echoInputField(); ?>
            <h3>New Online Optics </h3>
            <h4>Password Reset Form</h4>
            
            <fieldset>                
                <!-- Password input-->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="passwordinput">
                        Password
                    </label>
                    <div class="col-md-12">
                        <input id="password" class="form-control input-md" name="cust_new_password" type="password" placeholder="Enter your new password" >
                        <br>
                        <label for=""><?php echo 'Re-type Password'; ?> </label>
                        <input type="password" placeholder="Re-enter your new password " class="form-control" name="cust_re_password">
                     
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
               
                <!-- Button -->
                <div class="form-group ">
                    <label for=""></label>
                    <input type="submit" class="btn login-btn btn-block" style="color: #fff !important;" value="Reset Password" name="form1">
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