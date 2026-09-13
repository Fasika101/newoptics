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
        $error_message .= LANG_VALUE_123."<br>";
    }

    if(empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_131."<br>";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= LANG_VALUE_134."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            $total = $statement->rowCount();                            
            if($total) {
                $valid = 0;
                $error_message .= LANG_VALUE_147."<br>";
            }
        }
    }

    if(empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_124."<br>";
    }

    // if(empty($_POST['cust_address'])) {
    //     $valid = 0;
    //     $error_message .= LANG_VALUE_125."<br>";
    // }

    if(empty($_POST['cust_country'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_126."<br>";
    }

    if (empty($_POST['cust_region'])) {
    $valid = 0;
    $error_message .= 'Region can not be empty' . "<br>";
    }

    if(empty($_POST['cust_city'])) {
        $valid = 0;
        $error_message .= 'City can not be empty'."<br>";
    }

    

    // if(empty($_POST['cust_zip'])) {
    //     $valid = 0;
    //     $error_message .= LANG_VALUE_129."<br>";
    // }

    if( empty($_POST['cust_password']) || empty($_POST['cust_re_password']) ) {
        $valid = 0;
        $error_message .= LANG_VALUE_138."<br>";
    }

    if( !empty($_POST['cust_password']) && !empty($_POST['cust_re_password']) ) {
        if($_POST['cust_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message .= LANG_VALUE_139."<br>";
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
                                        cust_country,
                                        cust_region,
                                        cust_city,
                                      
                                        cust_password,
                                        cust_token,
                                        cust_datetime,
                                        cust_timestamp,
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['cust_name']),
                                        strip_tags($_POST['cust_cname']),
                                        strip_tags($_POST['cust_email']),
                                        strip_tags($_POST['cust_phone']),
                                        strip_tags($_POST['cust_country']),
                                        strip_tags($_POST['cust_region']),
                                        strip_tags($_POST['cust_city']),
                                       
                                        
                                        md5($_POST['cust_password']),
                                        $token,
                                        $cust_datetime,
                                        $cust_timestamp,
                                        1
                                    ));
                                    $link_address = 'login.php';
                                    $success_message = 'Registration was successful . You can now login! <br><a href=' . $link_address . '>Go Login Page</a></div>';




       }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="row d-flex justify-content-center text-center">
        <div class="col-2">
            <span><h4>Company Registration Page</h4></span>
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
                            <div class="col-md-2"></div>
                          <div class="col-md-8">
                                
                                <?php
                                if($error_message != '') {
                                    echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                                }
                                if($success_message != '') {
                                    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$success_message."</div>";
                                }
                                ?>

                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Company Name'; ?> </label>
                                    <input placeholder="Enter Company Name" type="text" class="form-control" name="cust_name" value="<?php if(isset($_POST['cust_name'])){echo $_POST['cust_name'];} ?>">
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Email Address'; ?> </label>
                                    <input type="email" placeholder="Enter a valid email please!" class="form-control" name="cust_email" value="<?php if(isset($_POST['cust_email'])){echo $_POST['cust_email'];} ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for=""><?php echo 'Phone Number'; ?> </label>
                                    <input type="text" placeholder="Enter phone number here!" class="form-control" name="cust_phone" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>">
                                </div>
                                <!-- <div class="col-md-12 form-group">
                                    <label for=""><?php //echo LANG_VALUE_105; ?> *</label>
                                    <textarea name="cust_address" class="form-control" cols="30" rows="10" style="height:70px;"><?php //if(isset($_POST['cust_address'])){echo $_POST['cust_address'];} ?></textarea>
                                </div> -->
                              
                               <div class="col-md-6 form-group">
                                    <!-- <label for=""><?php echo 'Password'; ?> </label> -->
                                    <!-- <input type="password" placeholder="Enter a password" class="form-control" name="cust_password"> -->
                                </div>
                                
                                
                                    <div class="col-md-6 form-group">
                                        <label for=""> Choose Country: </label><br>    
                                            <select name="cust_country" id="selectCountry" size="1"  class="form-control select2">
                                                <option value="" class="form-control select2" selected="selected">Choose Country</option>
                                            </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Choose Region:  </label><br>
                                            <select name="cust_region" id="selectState" size="1" class="form-control select2">
                                                <option value="" selected="selected">Please Choose Country First</option>
                                            </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""> Choose City:  </label><br>
                                            <select name="cust_city" id="countrystatecitySelect" size="1" class="form-control select2">
                                                <option value="" selected="selected">Please Choose Region First</option>
                                            </select>
                                    </div>
                                    
                                                                
                               
                                    <hr class="my-4">
                                    <div class="col-md-6 form-group">
                                        <label for=""><?php echo 'Password'; ?> </label>
                                        <input type="password" placeholder="Enter a password" class="form-control" name="cust_password">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for=""><?php echo 'Re-type Password'; ?> </label>
                                        <input type="password" placeholder="Re-enter your password " class="form-control" name="cust_re_password">
                                    </div>
                                    
                                
                                    
                                    <div class="col-md-6 form-group ">
                                        <label for=""></label>
                                        <input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_15; ?>" name="form1">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        
                                        <input  type="hidden" class="form-control" name="cust_cname" value="company">
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

