<?php require_once('header.php'); ?>

<?php
// Check if the customer is logged in or not
if(!isset($_SESSION['customer'])) {
    header('location: '.BASE_URL.'logout.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'],0));
    $total = $statement->rowCount();
    if($total) {
        header('location: '.BASE_URL.'logout.php');
        exit;
    }
}
?>

<?php
if (isset($_POST['form1'])) {


    // update data into the database
    $statement = $pdo->prepare("UPDATE tbl_customer SET 
                            -- cust_name=?, 
                            -- cust_cname=?, 
                            -- cust_phone=?, 
                            -- cust_country=?, 
                            -- cust_address=?, 
                            -- cust_city=?, 
                            -- cust_state=?, 
                            -- cust_zip=?,
                            cust_s_name=?, 
                            -- cust_cname=?, 
                            cust_s_phone=?, 
                            -- cust_s_country=?, 
                            -- cust_s_region=?, 
                            -- cust_s_city=?, 
                            cust_drop_address=?,
                            company_drop_address=?,
                            partner_drop_address=?,
                            partner_pickup_name=?,
                            company_pickup_name=?,
                            partner_pickup_number=?,
                            company_pickup_number=?


                            WHERE cust_id=?");
    $statement->execute(array(
                            // strip_tags($_POST['cust_name']),
                            // strip_tags($_POST['cust_cname']),
                            // strip_tags($_POST['cust_phone']),
                            // strip_tags($_POST['cust_country']),
                            // strip_tags($_POST['cust_address']),
                            // strip_tags($_POST['cust_city']),
                            // strip_tags($_POST['cust_state']),
                            // strip_tags($_POST['cust_zip']),
                            strip_tags($_POST['cust_s_name']),
                          //  strip_tags($_POST['cust_cname']),
                            strip_tags($_POST['cust_s_phone']),
                            // strip_tags($_POST['cust_s_country']),
                            // strip_tags($_POST['cust_s_region']),
                            // strip_tags($_POST['cust_s_city']),
                            strip_tags($_POST['cust_drop_address']),
                            strip_tags($_POST['company_drop_address']),
                            strip_tags($_POST['partner_drop_address']),
                            strip_tags($_POST['partner_pickup_name']),
                            strip_tags($_POST['company_pickup_name']),
                            strip_tags($_POST['partner_pickup_number']),
                            strip_tags($_POST['company_pickup_number']),
                            $_SESSION['customer']['cust_id'],
                        ));  
   
    $success_message = LANG_VALUE_122;
    // $_SESSION['customer']['cust_name'] = strip_tags($_POST['cust_b_name']);
    // $_SESSION['customer']['cust_name'] = strip_tags($_POST['cust_b_cname']);
    // $_SESSION['customer']['cust_phone'] = strip_tags($_POST['cust_b_phone']);
    // $_SESSION['customer']['cust_country'] = strip_tags($_POST['cust_country']);
    // $_SESSION['customer']['cust_address'] = strip_tags($_POST['cust_address']);
    // $_SESSION['customer']['cust_city'] = strip_tags($_POST['cust_city']);
    // $_SESSION['customer']['cust_state'] = strip_tags($_POST['cust_state']);
   // $_SESSION['customer']['cust_b_zip'] = strip_tags($_POST['cust_b_zip']);
    $_SESSION['customer']['cust_s_name'] = strip_tags($_POST['cust_s_name']);
  //  $_SESSION['customer']['cust_cname'] = strip_tags($_POST['cust_cname']);
    $_SESSION['customer']['cust_s_phone'] = strip_tags($_POST['cust_s_phone']);
    // $_SESSION['customer']['cust_s_country'] = strip_tags($_POST['cust_s_country']);
    // $_SESSION['customer']['cust_s_city'] = strip_tags($_POST['cust_s_city']);
    // $_SESSION['customer']['cust_s_region'] = strip_tags($_POST['cust_s_region']);
    $_SESSION['customer']['cust_drop_address'] = strip_tags($_POST['cust_drop_address']);
    $_SESSION['customer']['company_drop_address'] = strip_tags($_POST['company_drop_address']);
    $_SESSION['customer']['partner_drop_address'] = strip_tags($_POST['partner_drop_address']);
    $_SESSION['customer']['partner_pickup_name'] = strip_tags($_POST['partner_pickup_name']);
    $_SESSION['customer']['company_pickup_name'] = strip_tags($_POST['company_pickup_name']);
    $_SESSION['customer']['partner_pickup_number'] = strip_tags($_POST['partner_pickup_number']);
    $_SESSION['customer']['company_pickup_number'] = strip_tags($_POST['company_pickup_number']);


   // $_SESSION['customer']['cust_zip'] = strip_tags($_POST['cust_zip']);

}
?>

<div class="page">
    <div class="container">
        <div class="row">            
            <div class="col-md-12"> 
                <?php require_once('customer-sidebar.php'); ?>
            </div>
            <div class="col-md-12">
                <div class="user-content">
                    <?php
                    if($error_message != '') {
                        echo "<div class='error' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                    }
                    if($success_message != '') {
                      $link_address = 'checkout.php';
                      echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "<br><a href='" . $link_address . "'>Go to Check-Out page</a></div>"; 

                    }
                    ?>
                 <span><h5>NewonlineOptics will deliver following the info you give down below. Please fill correctly.</h5> </span>

                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <div class="row">
                            <!-- <div class="col-md-6">
                                <h3><?php echo LANG_VALUE_86; ?></h3>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_102; ?></label>
                                    <input type="text" class="form-control" name="cust_name" value="<?php echo $_SESSION['customer']['cust_b_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_103; ?></label>
                                    <input type="text" class="form-control" name="cust_cname" value="<?php echo $_SESSION['customer']['cust_b_cname']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_104; ?></label>
                                    <input type="text" class="form-control" name="cust_phone" value="<?php echo $_SESSION['customer']['cust_b_phone']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_106; ?></label>
                                    <select name="cust_country" class="form-control">
                                       
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_105; ?></label>
                                    <textarea name="cust_address" class="form-control" cols="30" rows="10" style="height:100px;"><?php echo $_SESSION['customer']['cust_address']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_107; ?></label>
                                    <input type="text" class="form-control" name="cust_city" value="<?php echo $_SESSION['customer']['cust_city']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_108; ?></label>
                                    <input type="text" class="form-control" name="cust_state" value="<?php echo $_SESSION['customer']['cust_state']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for=""><?php echo LANG_VALUE_109; ?></label>
                                    <input type="text" class="form-control" name="cust_b_zip" value="<?php echo $_SESSION['customer']['cust_b_zip']; ?>">
                                </div> 
                            </div> -->
                            <div class="col-md-8">
                                <!-- shipping info -->
                                <h3><?php echo LANG_VALUE_87; ?></h3>

                                                            
                               
                                    <?php if ($_SESSION['customer']['cust_cname'] == 'individual'): ?>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Name'; ?></label>
                                            <input type="text" class="form-control" name="cust_s_name" value="<?php echo $_SESSION['customer']['cust_name']; ?>" >
                                        </div>
                                         <div class="form-group">
                                            <label for=""><?php echo 'Phone Number'; ?></label>
                                            <input type="text" class="form-control" name="cust_s_phone" value="<?php echo $_SESSION['customer']['cust_phone']; ?>" >
                                         </div>
                                         <div class="form-group">
                                            <label for=""><?php echo 'Drop Off Location'; ?></label>
                                            <input type="text" class="form-control" name="cust_drop_address" >
                                            <input type="hidden" class="form-control" name="company_drop_address" value="0" >
                                            <input type="hidden" class="form-control" name="company_pickup_name" value="0">
                                            <input type="hidden" class="form-control" name="company_pickup_number" value="0">
                                            <input type="hidden" class="form-control" name="partner_drop_address" value="0">
                                            <input type="hidden" class="form-control" name="partner_pickup_number" value="0">
                                            <input type="hidden" class="form-control" name="partner_pickup_name" value="0">
                                         </div>
                                    <!-- partner -->
                                    <?php elseif ($_SESSION['customer']['cust_cname'] == 'partner'): ?>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Pick-up person Full Name'; ?></label>
                                            <input type="text" class="form-control" name="partner_pickup_name" >
                                        </div>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Pick-up person Phone number'; ?></label>
                                            <input type="text" class="form-control" name="partner_pickup_number" >
                                        </div>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Drop Off Location'; ?></label>
                                            <input type="text" class="form-control" name="partner_drop_address" >
                                            <input type="hidden" class="form-control" name="company_drop_address" value="0" >
                                            <input type="hidden" class="form-control" name="company_pickup_name" value="0">
                                            <input type="hidden" class="form-control" name="company_pickup_number" value="0">
                                            <input type="hidden" class="form-control" name="cust_s_name" value="0" >
                                            <input type="hidden" class="form-control" name="cust_s_phone" value="0" >
                                            <input type="hidden" class="form-control" name="cust_drop_address" value="0" >
                                        </div>
                                    <!-- end of partner -->
                                    <!-- company -->
                                    <?php elseif ($_SESSION['customer']['cust_cname'] == 'company'): ?>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Pick-up person Full Name'; ?></label>
                                            <input type="text" class="form-control" name="company_pickup_name" >
                                        </div>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Pick-up person Phone number'; ?></label>
                                            <input type="text" class="form-control" name="company_pickup_number" >
                                        </div>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Company Drop Off Location'; ?></label>
                                            <input type="text" class="form-control" name="company_drop_address" >
                                            <input type="hidden" class="form-control" name="partner_drop_address" value="0">
                                            <input type="hidden" class="form-control" name="partner_pickup_number" value="0">
                                            <input type="hidden" class="form-control" name="partner_pickup_name" value="0">
                                            <input type="hidden" class="form-control" name="cust_s_name" value="0" >
                                            <input type="hidden" class="form-control" name="cust_s_phone" value="0" >
                                            <input type="hidden" class="form-control" name="cust_drop_address" value="0" >



                                        </div>

                                    <?php endif;?>
                                     <!-- end of company -->

                              <input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_5; ?>" name="form1">

                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>