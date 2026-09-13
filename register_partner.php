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
<script src="assets/country/country.js"></script>
<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if(empty($_POST['cust_name'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_123;
    }


    if(empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_124;
    }
    else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_phone=?");
            $statement->execute(array($_POST['cust_phone']));
            $total = $statement->rowCount();                            
            if($total) {
                $valid = 0;
                $error_message .= 'Phone Number already exists'." ";
            }
        }

    // if(empty($_POST['cust_address'])) {
    //     $valid = 0;
    //     $error_message .= LANG_VALUE_125."<br>";
    // }

    
    // if(empty($_POST['dob'])) {
    //     $valid = 0;
    //     $error_message .= 'Date of birth can not be empty'."<br>";
    // }
    // if (empty($_POST['house_no'])) {
    // $valid = 0;
    // $error_message .= 'House no can not be empty' . "<br>";
    // }
    // if (empty($_POST['cust_bank'])) {
    // $valid = 0;
    // $error_message .= 'Bank name can not be empty' . "<br>";
    // }
    // if (empty($_POST['cust_bank_account'])) {
    //     $valid = 0;
    //     $error_message .= 'Bank account number cannot be empty no can not be empty' . "<br>";
    // }



    

    // if(empty($_POST['cust_zip'])) {
    //     $valid = 0;
    //     $error_message .= LANG_VALUE_129."<br>";
    // }

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

    if($valid == 1) {

        $token = md5(time());
        $cust_datetime = date('Y-m-d h:i:s');
        $cust_timestamp = time();

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_customer (
                                        cust_name,
                                        cust_cname,
                                        cust_email,
                                        cust_phone,
                                                                             
                                        cust_password,
                                        cust_token,
                                        cust_datetime,
                                        cust_timestamp,
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['cust_name']),
                                        strip_tags($_POST['cust_cname']),
                                        strip_tags($_POST['cust_email']),
                                        strip_tags($_POST['cust_phone']),
                                                                             
                                        
                                        md5($_POST['cust_password']),
                                        $token,
                                        $cust_datetime,
                                        $cust_timestamp,
                                        1
                                    ));
                                    $link_address = 'login.php';
                                   // $success_message = header("location: " . "login.php");

                                   $success_message = 'Registration was successful . You can now login! <br><a href=' . $link_address . '>Go Login Page</a></div>';

       }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="row d-flex justify-content-center text-center">
        <div class="col-2">
            <span><h4>Partner Registration Page </h4></span>
        </div>   
    </div>
</div>
<div class="col-xs-1 text-center">
    
</div>
<div class="page" style="padding-top: 0px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content"> 


                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                             <div class="col-md-4"></div>
                            <div class="col-md-4">
                                
                                                         <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')
                   
    </script>";
}
if ($success_message != '') {
    echo "<script>alert('Registration was successful!');
location.href = 'login.php';
          </script>";
    //  header('location: product.php?id=' . $_REQUEST['id']);
}
?>

                                <div class="form-group">
                                    <label for=""><?php echo 'Full Name'; ?> </label>
                                    <input placeholder="Enter Full Name" type="text" class="form-control" name="cust_name" value="<?php if(isset($_POST['cust_name'])){echo $_POST['cust_name'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Phone Number'; ?> </label>
                                    <input type="text" placeholder="Enter phone number here!" class="form-control" name="cust_phone" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo 'Email Address'; ?><span class="text-muted">(Optional)</span></label>
                                    <input type="email" placeholder="Enter is optional!" class="form-control" name="cust_email" value="<?php if(isset($_POST['cust_email'])){echo $_POST['cust_email'];} ?>">
                                </div>
                                
                                <!-- <div class="col-md-12 form-group">
                                    <label for=""><?php //echo LANG_VALUE_105; ?> *</label>
                                    <textarea name="cust_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php //if(isset($_POST['cust_address'])){echo $_POST['cust_address'];} ?></textarea>
                                </div> -->
                              
                               <div class="form-group">
                                    <!-- <label for=""><?php echo 'Password'; ?> </label> -->
                                    <!-- <input type="password" placeholder="Enter a password" class="form-control" name="cust_password"> -->
                                </div>
                                
                                
                                    
                                    <!-- <div class="form-group">
                                        <label for=""><?php echo 'House Number'; ?> </label>
                                        <input type="text" placeholder="Enter house number here!" class="form-control" name="house_no" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>">
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label for=""><?php echo 'Date of Birth'; ?> </label>
                                        <input type="date" placeholder="Enter house number here!" class="form-control" name="dob" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>">
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label for=""> Choose Bank you use :  </label><br>
                                            <select name="cust_bank" id="cust_bank"  class="form-control select2">
                                                <option value="Commercial Bank of Ethiopia" >Commercial Bank of Ethiopia</option>
                                                <option value="Awash Bank" >Awash Bank</option>
                                                <option value="Abyssinia Bank" >Abyssinia Bank</option>
                                                <option value="Oromia Bank" >Oromia Bank</option>
                                                <option value="Hibret Bank" >Hibret Bank</option>
                                            </select>
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label for=""><?php echo 'Account Number'; ?> </label>
                                        <input type="text" placeholder="Enter your banks account number here!" class="form-control" name="cust_bank_account" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>">
                                    </div> -->
                                    
                                                                
                               
                                    <hr class="my-4">
                                    <div class="form-group">
                                        <label for=""><?php echo 'Password'; ?> </label>
                                        <input type="password" placeholder="Enter a password" class="form-control" name="cust_password">
                                    </div>
                                    <div class="form-group">
                                        <label for=""><?php echo 'Re-type Password'; ?> </label>
                                        <input type="password" placeholder="Re-enter your password " class="form-control" name="cust_re_password">
                                    </div>
                                    
                                        
                                        <input  type="hidden" class="form-control" name="cust_cname" value="Partner">
                                    
                                
                                    
                                    <div class="form-group ">
                                        <label for=""></label>
                                        <input type="submit" class="btn btn-primary" value="<?php echo 'Register as Partner'; ?>" name="form1">
                                    </div>                             
                                
                            </div>
                           
                        </div>                        
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>

