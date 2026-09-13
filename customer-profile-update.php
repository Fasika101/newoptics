
<?php require_once 'header.php';?>
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
.proif ($_POST['cust_new_password'] == $_POST['cust_re_password']) {
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
// Check if the customer is logged in or not
if (!isset($_SESSION['customer'])) {
    header('location: ' . BASE_URL . 'logout.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'], 0));
    $total = $statement->rowCount();
    if ($total) {
        header('location: ' . BASE_URL . 'logout.php');
        exit;
    }
}
?>

<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if (empty($_POST['cust_name'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_123 . "<br>";
    }
    if (empty($_POST['cust_lname'])) {
    $valid = 0;
    $error_message .= LANG_VALUE_123 . "<br>";
    }

    if (empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_124 . "<br>";
    }

    if (empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_125 . "<br>";
    }

     if( empty($_POST['cust_new_password']) || empty($_POST['cust_re_password']) ) {
        $valid = 0;
        $error_message .= LANG_VALUE_138."<br>";
    }

    if( !empty($_POST['cust_new_password']) && !empty($_POST['cust_re_password']) ) {
        if($_POST['cust_new_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message .= LANG_VALUE_139."<br>";
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

   

    if ($valid == 1) {
$password = strip_tags($_POST['cust_new_password']);

        // update data into the database
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_name=?,  cust_lname=?,  cust_phone=?, cust_password=? , cust_email=? WHERE cust_id=?");
        $statement->execute(array(
            strip_tags($_POST['cust_name']),
             strip_tags($_POST['cust_lname']),
            // strip_tags($_POST['cust_cname']),
            strip_tags($_POST['cust_phone']),
            md5($password),
            strip_tags($_POST['cust_email']),
            // strip_tags($_POST['cust_zip']),
            $_SESSION['customer']['cust_id'],
        ));

        $success_message = LANG_VALUE_130;

        $_SESSION['customer']['cust_name'] = $_POST['cust_name'];
         $_SESSION['customer']['cust_lname'] = $_POST['cust_lname'];
        //$_SESSION['customer']['cust_cname'] = $_POST['cust_cname'];
        $_SESSION['customer']['cust_phone'] = $_POST['cust_phone'];
      //  $_SESSION['customer']['cust_country'] = $_POST['cust_country'];
        $_SESSION['customer']['cust_email'] = $_POST['cust_email'];
        $_SESSION['customer']['cust_new_password'] = md5($password);

        // $_SESSION['customer']['cust_city'] = $_POST['cust_city'];
       // $_SESSION['customer']['cust_region'] = $_POST['cust_region'];
      //  $_SESSION['customer']['cust_drop_address'] = $_POST['cust_drop_address'];
    }
}
?>


                   
                   <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";

}
if ($success_message != '') {
   echo "<script>alert('" . $success_message . "')</script>";

}
?>
               <div class="page" style="padding-top: 50px;">
    <div class="container" style="display: flex;
    justify-content: center;
    align-items: center;
    width: 100&;">
               <form class="form-horizontal" id="validateForm" action="" method="post">
                        <?php $csrf->echoInputField();?>
                         
                    
                                <h3>
                        <?php echo LANG_VALUE_117; ?>
                         
                    </h3>
                    <fieldset>
                            <div class="form-group">
                                <label for=""><?php echo 'First Name'; ?> </label>                               
                                <input type="text" class="form-control" name="cust_name" value="<?php echo $_SESSION['customer']['cust_name']; ?>">
                            </div>
                             <div class="form-group">
                                <label for=""><?php echo 'Last Name'; ?> </label>                               
                                <input type="text" class="form-control" name="cust_lname" value="<?php echo $_SESSION['customer']['cust_lname']; ?>">
                            </div>
                        
                            <div class=" form-group">
                                <label for=""><?php echo 'Email Address '; ?> </label>
                                <input type="text" class="form-control" name="cust_email" value="<?php echo $_SESSION['customer']['cust_email']; ?>" >
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo 'Phone Number'; ?> </label>
                                <input type="text" class="form-control" name="cust_phone" value="<?php echo $_SESSION['customer']['cust_phone']; ?>">
                            </div>
                           
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
               
                            
                        <input type="submit" style="color: white !important;" class="bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400" value="<?php echo LANG_VALUE_5; ?>" name="form1">
                            <!-- <a href="customer-password-update.php"  >Change Password</a>
                             -->
                        </div>
                       
                    </div>
</fieldset>
                </form>
                       

               
    </div>
</div>


<?php require_once 'footer.php';?>
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