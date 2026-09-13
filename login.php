<?php require_once('header.php'); ?>
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
    $banner_login = $row['banner_login'];
}
?>

<?php
if(isset($_POST['form1'])) {
        
    if(empty($_POST['logininfo']) || empty($_POST['cust_password'])) {
        $error_message = "Please enter your Phone or Email and password to login to your account".'';
    } else {
        
        $cust_email = strip_tags($_POST['logininfo']);
        $cust_password = strip_tags($_POST['cust_password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE (cust_phone=? OR cust_email=?)");
        $statement->execute(array($cust_email, $cust_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $cust_status = $row['cust_status'];
            $row_password = $row['cust_password'];
        }

        if($total==0) {
            $error_message .= LANG_VALUE_133;
        } else {

            if( $row_password != md5($cust_password) ) {
                $error_message .= LANG_VALUE_139;
            } else {
                if($cust_status == 0) {
                    $error_message .= LANG_VALUE_148;
                } else {
                    $_SESSION['customer'] = $row;
                    header("location: "."dashboard.php");
                }
            }
            
        }
    }
    
}
?>

<div class="col-xs-1 text-center">
    
</div>
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
    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
}
?>
            <h3>Login to your account </h3>            
            <fieldset>
                <div class="form-group">
                     <label class="col-md-12 control-label" for="textinput">Phone Number/Email</label>
                     <div class="col-md-12">
                        <input id="phone" name="logininfo" type="text" autocomplete="off" placeholder="Enter your Phone Number" class="form-control input-md">
                    </div>
                </div>              
                <!-- Password input-->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="passwordinput">
                        Password
                    </label>
                    <div class="col-md-12">
                        <input id="password" class="form-control input-md" name="cust_password" type="password" placeholder="Enter your password" >    
                         <span class="show-pass" onclick="toggle()">
                            <i class="far fa-eye" onclick="myFunction(this)" style="vertical-align: -moz-middle-with-baseline;"></i>
                          
                        </span> 
                    </div>                 
                </div> 
                <div class="form-group ">
                    <label for=""></label>
                    <input type="submit" class="btn login-btn btn-block" style="color: #fff !important;" value="Sign in" name="form1">
                </div>
                <div class="ex-account text-center">
                    <p>Don't have an account? Signup 
                        <a href="registration.php">here</a>
                    </p>
                    <div class="divider"></div>
                                                    <a href="forget-password.php" style="color:#e4144d;"><?php echo LANG_VALUE_97; ?></a>

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