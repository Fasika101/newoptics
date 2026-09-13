 


 <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
 <script src="tailwind/tail.js" crossorigin="anonymous"></script>
   
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
 
<style>
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active {
  

 border-color: transparent !important;

}

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
.form-horizontal {
  
  
    padding: 25px 38px;
    border-radius: 12px;
    
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
.show-pass{
    position: absolute;
    top:5%;
    right: 8%;
}


@media screen and (max-width: 768px) {
  .hh {
    top: 14.5rem !important; right: 0.5rem; bottom: 0.5rem; left: 0.5rem;
  }
}


    </style>
<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>


<?php
if(!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}


?>



<?php
if(!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}

if (isset($_POST['guest_pass'])) {
// $valid = 1;

 
$first_name = $_POST['cust_name'];
$last_name = $_POST['cust_fname'];
$phone_no = $_POST['cust_phone'];
$email_acc = $_POST['cust_jemail'];
$drop_add = $_POST['cust_drop'];
$acc_type = $_POST['cust_guest'];

$new_key =  1;
 
$_SESSION['guest_first_name'][$new_key] = $first_name;
$_SESSION['guest_last_name'][$new_key] = $last_name;
$_SESSION['guest_phone'][$new_key] = $phone_no;
$_SESSION['guest_email'][$new_key] = $email_acc;
$_SESSION['guest_drop'][$new_key] = $drop_add;
$_SESSION['guest_type'][$new_key] = $acc_type;



header("Location: guest_checkout.php");

}
?>

<?php
if (isset($_POST['form1'])) {

    if (empty($_POST['logininfo']) || empty($_POST['cust_password'])) {
        $error_message = "Please enter your Phone or Email and password to login to your account" . '';
    } else {

        $cust_email = strip_tags($_POST['logininfo']);
        $cust_password = strip_tags($_POST['cust_password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE (cust_phone='$cust_email' OR cust_email='$cust_email') ");
        $statement->execute();
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $cust_status = $row['cust_status'];
            $row_password = $row['cust_password'];
        }

        if ($total == 0) {
            $error_message .= LANG_VALUE_133;
        } else {

            if ($row_password != md5($cust_password)) {
                $error_message .= LANG_VALUE_139;
            } else {
                if ($cust_status == 0) {
                    $error_message .= LANG_VALUE_148;
                } else {
                    $_SESSION['customer'] = $row;
                    header("location: " . "checkout.php");
                }
            }

        }
    }

}



if (isset($_POST['payment_form'])) {
    
    $valid = 1;


    if( empty($_POST['cust_drop_address']) || empty($_POST['cust_drop_address']) ) {
      
            if( empty($_POST['partner_drop_address']) || empty($_POST['partner_drop_address']) ) {

               if( empty($_POST['partner_pickup_name']) || empty($_POST['partner_pickup_name']) ) {
                  if (empty($_POST['partner_pickup_number']) || empty($_POST['partner_pickup_number'])) {
                        $valid = 0;
                         $error_message .= 'Please fill out all the necessary shipping info.';
                }

    
    }
    }
    }
   global $full_ship_price ;
//    $full_ship_price = 67;

//    $full_ship_price = $_POST['current_price'];
    
//    $full_final = $table_total_price + $full_ship_price;

//    //additional detail
//     $arr_full_final_p = array();



    if (empty($_POST['cost_shipment']) ) {

    
                $valid = 0;
                $error_message .= 'Please let us know where you are curently living. With the YES or NO provided.';
            

    }

    



    

   
 if($valid == 1) {
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
   
    // $success_message = LANG_VALUE_122;
      $success_message = header("location: " . "payment_page.php");
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

    $_SESSION['customer']['full_final_p'] = strip_tags($_POST['cost_shipment']);

   // final2

   // $_SESSION['full_final_p'][$new_key] = $full_final ;
   



   // $_SESSION['customer']['cust_zip'] = strip_tags($_POST['cust_zip']);

}
}
?>

                  <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}

?>

<?php if(!isset($_SESSION['customer'])): ?>
                   
<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?php echo LANG_VALUE_22; ?></h1>
    </div>
</div>

<div class="page" style="padding-top: 20px;">
    <div class="container">

<!-- first option -->
    <!-- <section class="bg-white dark:bg-gray-900">
    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-7">
            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">Payments tool for software companies</h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">From checkout to global sales tax compliance, companies around the world use Flowbite to simplify their payment stack.</p>
            <a href="#" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                Get started
                <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </a>
            <a href="#" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                Speak to Sales
            </a> 
        </div>
        <div class="lg:mt-0 lg:col-span-5 lg:flex">
            <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/phone-mockup.png" alt="mockup">
        </div>                
    </div>
</section> -->

<!-- first option -->

    <section class="bg-white dark:bg-gray-900">
    <div class="py-9 px-6 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
        <button id="readProductButton" data-modal-target="readProductModal" data-modal-toggle="readProductModal"  class="inline-flex justify-between items-center py-1 px-1 pr-4 mb-7 text-lg text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700" role="alert">
            <span class="text-2xl bg-yellow-300 rounded-full text-white px-5 py-2.5 mr-3">Login</span> <span class="text-2xl font-large">Already have account</span> 
            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
</button>
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Don't have an account?</h1>
        <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">New Online Optics stands for providing accessible, high-quality, and convenient eyewear solutions.</p>
        <div class="flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
            <!-- <a href="#" class=" bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400">
                Register Now
                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </a>
            <a href="#" class=" bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400">
                Guest Checkout
                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </a> -->


            <!-- Modal toggle -->
<div class="flex justify-center m-5">
    <button id="reg_btn" class=" bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400" type="button">
    Register Now
    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>

    </button>
</div>

<div class="flex justify-center m-5">
    <button id="guest_checkoutc" data-modal-target="guest_c" data-modal-toggle="guest_c" class=" bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400" type="button">
    Guest Checkout
    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>

    </button>
</div>
            <!-- <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                Watch video
            </a>   -->
        </div>
        <!-- <div class="px-4 mx-auto text-center md:max-w-screen-md lg:max-w-screen-lg lg:px-36">
            <span class="font-semibold text-gray-400 uppercase">Pay with </span>
            <div class="flex flex-wrap justify-center items-center mt-8 text-gray-500 sm:justify-between">
                <a href="#" class="mr-5 mb-5 lg:mb-0 hover:text-gray-800 dark:hover:text-gray-400">
                            <img class="rounded-full" src="assets/payment_buttons/Chapa Logo.webp"/>             
                </a>
                <a href="#" class="mr-5 mb-5 lg:mb-0 hover:text-gray-800 dark:hover:text-gray-400">
                    <svg class="h-11" viewBox="0 0 208 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M42.7714 20.729C42.7714 31.9343 33.6867 41.019 22.4814 41.019C11.2747 41.019 2.19141 31.9343 2.19141 20.729C2.19141 9.52228 11.2754 0.438965 22.4814 0.438965C33.6867 0.438965 42.7714 9.52297 42.7714 20.729Z" fill="currentColor"/>
                        <path d="M25.1775 21.3312H20.1389V15.9959H25.1775C25.5278 15.9959 25.8747 16.0649 26.1983 16.1989C26.522 16.333 26.8161 16.5295 27.0638 16.7772C27.3115 17.0249 27.508 17.319 27.6421 17.6427C27.7761 17.9663 27.8451 18.3132 27.8451 18.6635C27.8451 19.0139 27.7761 19.3608 27.6421 19.6844C27.508 20.0081 27.3115 20.3021 27.0638 20.5499C26.8161 20.7976 26.522 20.9941 26.1983 21.1281C25.8747 21.2622 25.5278 21.3312 25.1775 21.3312ZM25.1775 12.439H16.582V30.2234H20.1389V24.8881H25.1775C28.6151 24.8881 31.402 22.1012 31.402 18.6635C31.402 15.2258 28.6151 12.439 25.1775 12.439Z" fill="white"/>
                        <path d="M74.9361 17.4611C74.9361 16.1521 73.9305 15.3588 72.6239 15.3588H69.1216V19.5389H72.6248C73.9313 19.5389 74.9369 18.7457 74.9369 17.4611H74.9361ZM65.8047 28.2977V12.439H73.0901C76.4778 12.439 78.3213 14.7283 78.3213 17.4611C78.3213 20.1702 76.4542 22.4588 73.0901 22.4588H69.1216V28.2977H65.8055H65.8047ZM80.3406 28.2977V16.7362H83.3044V18.2543C84.122 17.2731 85.501 16.4563 86.9027 16.4563V19.3518C86.6912 19.3054 86.4349 19.2826 86.0851 19.2826C85.1039 19.2826 83.7949 19.8424 83.3044 20.5681V28.2977H80.3397H80.3406ZM96.8802 22.3652C96.8802 20.6136 95.8503 19.0955 93.9823 19.0955C92.1364 19.0955 91.1105 20.6136 91.1105 22.366C91.1105 24.1404 92.1364 25.6585 93.9823 25.6585C95.8503 25.6585 96.8794 24.1404 96.8794 22.3652H96.8802ZM88.0263 22.3652C88.0263 19.1663 90.2684 16.4563 93.9823 16.4563C97.7198 16.4563 99.962 19.1655 99.962 22.3652C99.962 25.5649 97.7198 28.2977 93.9823 28.2977C90.2684 28.2977 88.0263 25.5649 88.0263 22.3652ZM109.943 24.3739V20.3801C109.452 19.6316 108.378 19.0955 107.396 19.0955C105.693 19.0955 104.524 20.4265 104.524 22.366C104.524 24.3267 105.693 25.6585 107.396 25.6585C108.378 25.6585 109.452 25.1215 109.943 24.3731V24.3739ZM109.943 28.2977V26.5697C109.054 27.6899 107.841 28.2977 106.462 28.2977C103.637 28.2977 101.465 26.1499 101.465 22.3652C101.465 18.6993 103.59 16.4563 106.462 16.4563C107.793 16.4563 109.054 17.0177 109.943 18.1843V12.439H112.932V28.2977H109.943ZM123.497 28.2977V26.5925C122.727 27.4337 121.372 28.2977 119.526 28.2977C117.052 28.2977 115.884 26.9431 115.884 24.7473V16.7362H118.849V23.5798C118.849 25.1451 119.666 25.6585 120.927 25.6585C122.071 25.6585 122.983 25.028 123.497 24.3731V16.7362H126.463V28.2977H123.497ZM128.69 22.3652C128.69 18.9092 131.212 16.4563 134.67 16.4563C136.982 16.4563 138.383 17.4611 139.131 18.4886L137.191 20.3093C136.655 19.5153 135.838 19.0955 134.81 19.0955C133.011 19.0955 131.751 20.4037 131.751 22.366C131.751 24.3267 133.011 25.6585 134.81 25.6585C135.838 25.6585 136.655 25.1915 137.191 24.4203L139.131 26.2426C138.383 27.2702 136.982 28.2977 134.67 28.2977C131.212 28.2977 128.69 25.8456 128.69 22.3652ZM141.681 25.1915V19.329H139.813V16.7362H141.681V13.6528H144.648V16.7362H146.935V19.329H144.648V24.3975C144.648 25.1215 145.02 25.6585 145.675 25.6585C146.118 25.6585 146.541 25.495 146.702 25.3087L147.334 27.5728C146.891 27.9714 146.096 28.2977 144.857 28.2977C142.779 28.2977 141.681 27.2238 141.681 25.1915ZM165.935 28.2977V21.454H158.577V28.2977H155.263V12.439H158.577V18.5577H165.935V12.4398H169.275V28.2977H165.935ZM179.889 28.2977V26.5925C179.119 27.4337 177.764 28.2977 175.919 28.2977C173.443 28.2977 172.276 26.9431 172.276 24.7473V16.7362H175.241V23.5798C175.241 25.1451 176.058 25.6585 177.32 25.6585C178.464 25.6585 179.376 25.028 179.889 24.3731V16.7362H182.856V28.2977H179.889ZM193.417 28.2977V21.1986C193.417 19.6333 192.602 19.0963 191.339 19.0963C190.172 19.0963 189.285 19.7504 188.77 20.4045V28.2985H185.806V16.7362H188.77V18.1843C189.495 17.3439 190.896 16.4563 192.718 16.4563C195.217 16.4563 196.408 17.8573 196.408 20.0523V28.2977H193.418H193.417ZM199.942 25.1915V19.329H198.076V16.7362H199.943V13.6528H202.91V16.7362H205.198V19.329H202.91V24.3975C202.91 25.1215 203.282 25.6585 203.936 25.6585C204.38 25.6585 204.802 25.495 204.965 25.3087L205.595 27.5728C205.152 27.9714 204.356 28.2977 203.119 28.2977C201.04 28.2977 199.943 27.2238 199.943 25.1915" fill="currentColor"/>
                    </svg>                       
                </a>
                <a href="#" class="mr-5 mb-5 lg:mb-0 hover:text-gray-800 dark:hover:text-gray-400">
                    <svg class="h-11" viewBox="0 0 120 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.058 40.5994C31.0322 40.5994 39.9286 31.7031 39.9286 20.7289C39.9286 9.75473 31.0322 0.858398 20.058 0.858398C9.08385 0.858398 0.1875 9.75473 0.1875 20.7289C0.1875 31.7031 9.08385 40.5994 20.058 40.5994Z" fill="currentColor"/>
                        <path d="M33.3139 20.729C33.3139 19.1166 32.0101 17.8362 30.4211 17.8362C29.6388 17.8362 28.9272 18.1442 28.4056 18.6424C26.414 17.2196 23.687 16.2949 20.6518 16.1765L21.9796 9.96387L26.2951 10.8885C26.3429 11.9793 27.2437 12.8567 28.3584 12.8567C29.4965 12.8567 30.4211 11.9321 30.4211 10.7935C30.4211 9.65536 29.4965 8.73071 28.3584 8.73071C27.5522 8.73071 26.8406 9.20497 26.5086 9.89271L21.6954 8.87303C21.553 8.84917 21.4107 8.87303 21.3157 8.94419C21.1972 9.01535 21.1261 9.13381 21.1026 9.27613L19.6321 16.1999C16.5497 16.2949 13.7753 17.2196 11.7599 18.6662C11.2171 18.1478 10.495 17.8589 9.74439 17.86C8.13201 17.86 6.85156 19.1639 6.85156 20.7529C6.85156 21.9383 7.56272 22.9341 8.55897 23.3849C8.51123 23.6691 8.48781 23.9538 8.48781 24.2623C8.48781 28.7197 13.6807 32.348 20.083 32.348C26.4852 32.348 31.6781 28.7436 31.6781 24.2623C31.6781 23.9776 31.6543 23.6691 31.607 23.3849C32.6028 22.9341 33.3139 21.9144 33.3139 20.729ZM13.4434 22.7918C13.4434 21.6536 14.368 20.729 15.5066 20.729C16.6447 20.729 17.5694 21.6536 17.5694 22.7918C17.5694 23.9299 16.6447 24.855 15.5066 24.855C14.368 24.8784 13.4434 23.9299 13.4434 22.7918ZM24.9913 28.2694C23.5685 29.6921 20.8653 29.7872 20.083 29.7872C19.2768 29.7872 16.5736 29.6683 15.1742 28.2694C14.9612 28.0559 14.9612 27.7239 15.1742 27.5105C15.3877 27.2974 15.7196 27.2974 15.9331 27.5105C16.8343 28.4117 18.7314 28.7197 20.083 28.7197C21.4346 28.7197 23.355 28.4117 24.2324 27.5105C24.4459 27.2974 24.7778 27.2974 24.9913 27.5105C25.1809 27.7239 25.1809 28.0559 24.9913 28.2694ZM24.6116 24.8784C23.4735 24.8784 22.5488 23.9538 22.5488 22.8156C22.5488 21.6775 23.4735 20.7529 24.6116 20.7529C25.7502 20.7529 26.6748 21.6775 26.6748 22.8156C26.6748 23.9299 25.7502 24.8784 24.6116 24.8784Z" fill="white"/>
                        <path d="M108.412 16.6268C109.8 16.6268 110.926 15.5014 110.926 14.1132C110.926 12.725 109.8 11.5996 108.412 11.5996C107.024 11.5996 105.898 12.725 105.898 14.1132C105.898 15.5014 107.024 16.6268 108.412 16.6268Z" fill="currentColor"/>
                        <path d="M72.5114 24.8309C73.7446 24.8309 74.4557 23.9063 74.4084 23.0051C74.385 22.5308 74.3373 22.2223 74.29 21.9854C73.5311 18.7133 70.8756 16.2943 67.7216 16.2943C63.9753 16.2943 60.9401 19.6853 60.9401 23.8586C60.9401 28.0318 63.9753 31.4228 67.7216 31.4228C70.0694 31.4228 71.753 30.5693 72.9622 29.2177C73.5549 28.5538 73.4365 27.5341 72.7249 27.036C72.1322 26.6329 71.3972 26.7752 70.8517 27.2256C70.3302 27.6765 69.3344 28.5772 67.7216 28.5772C65.825 28.5772 64.2126 26.941 63.8568 24.7832H72.5114V24.8309ZM67.6981 19.1637C69.4051 19.1637 70.8756 20.4915 71.421 22.3173H63.9752C64.5207 20.468 65.9907 19.1637 67.6981 19.1637ZM61.0824 17.7883C61.0824 17.0771 60.5609 16.5078 59.897 16.3894C57.8338 16.0813 55.8895 16.8397 54.7752 18.2391V18.049C54.7752 17.1717 54.0636 16.6267 53.3525 16.6267C52.5697 16.6267 51.9297 17.2667 51.9297 18.049V29.6681C51.9297 30.427 52.4985 31.0908 53.2574 31.1381C54.0875 31.1854 54.7752 30.5454 54.7752 29.7154V23.7162C54.7752 21.0608 56.7668 18.8791 59.5173 19.1876H59.802C60.5131 19.1399 61.0824 18.5233 61.0824 17.7883ZM109.834 19.306C109.834 18.5233 109.194 17.8833 108.412 17.8833C107.629 17.8833 106.989 18.5233 106.989 19.306V29.7154C106.989 30.4981 107.629 31.1381 108.412 31.1381C109.194 31.1381 109.834 30.4981 109.834 29.7154V19.306ZM88.6829 11.4338C88.6829 10.651 88.0429 10.011 87.2602 10.011C86.4779 10.011 85.8379 10.651 85.8379 11.4338V17.7648C84.8655 16.7924 83.6562 16.3182 82.2096 16.3182C78.4632 16.3182 75.4281 19.7091 75.4281 23.8824C75.4281 28.0557 78.4632 31.4466 82.2096 31.4466C83.6562 31.4466 84.8893 30.9485 85.8613 29.9761C85.9797 30.6405 86.5729 31.1381 87.2602 31.1381C88.0429 31.1381 88.6829 30.4981 88.6829 29.7154V11.4338ZM82.2334 28.6245C80.0518 28.6245 78.2971 26.5145 78.2971 23.8824C78.2971 21.2742 80.0518 19.1399 82.2334 19.1399C84.4151 19.1399 86.1698 21.2504 86.1698 23.8824C86.1698 26.5145 84.3912 28.6245 82.2334 28.6245ZM103.527 11.4338C103.527 10.651 102.887 10.011 102.104 10.011C101.322 10.011 100.681 10.651 100.681 11.4338V17.7648C99.7093 16.7924 98.5 16.3182 97.0534 16.3182C93.307 16.3182 90.2719 19.7091 90.2719 23.8824C90.2719 28.0557 93.307 31.4466 97.0534 31.4466C98.5 31.4466 99.7327 30.9485 100.705 29.9761C100.824 30.6405 101.416 31.1381 102.104 31.1381C102.887 31.1381 103.527 30.4981 103.527 29.7154V11.4338ZM97.0534 28.6245C94.8717 28.6245 93.1174 26.5145 93.1174 23.8824C93.1174 21.2742 94.8717 19.1399 97.0534 19.1399C99.235 19.1399 100.99 21.2504 100.99 23.8824C100.99 26.5145 99.235 28.6245 97.0534 28.6245ZM117.042 29.7392V19.1637H118.299C118.963 19.1637 119.556 18.6656 119.603 17.9779C119.651 17.2428 119.058 16.6267 118.347 16.6267H117.042V14.6347C117.042 13.8758 116.474 13.2119 115.715 13.1646C114.885 13.1173 114.197 13.7573 114.197 14.5874V16.6501H113.011C112.348 16.6501 111.755 17.1483 111.708 17.836C111.66 18.571 112.253 19.1876 112.964 19.1876H114.173V29.7631C114.173 30.5454 114.814 31.1854 115.596 31.1854C116.426 31.1381 117.042 30.5216 117.042 29.7392Z" fill="currentColor"/>
                    </svg>                                                   
                </a>         
            </div>
        </div>  -->
    </div>
</section>
    

<!-- first option -->
    <!-- <section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
      <div class="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
          <h2 class="mb-4 text-3xl lg:text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Our Blog</h2>
          <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">We use an agile approach to test assumptions and connect with the needs of your audience early and often.</p>
      </div> 
      <div class="grid gap-8 lg:grid-cols-2">
          <article class="p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
              <div class="flex justify-between items-center mb-5 text-gray-500">
                  
                
              </div>
              <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><a href="#">Login</a></h2>
              <p class="mb-5 font-light text-gray-500 dark:text-gray-400">Static websites are now used to bootstrap lots of websites and are becoming the basis for a variety of tools that even influence both web designers and developers influence both web designers and developers.</p>
              <div class="flex justify-between items-center">
                  <div class="flex items-center space-x-4">
                     
                  </div>
                  <a href="#" class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline">
                      Read more
                      <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                  </a>
              </div>
          </article> 
          <article class="p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
              <div class="flex justify-between items-center mb-5 text-gray-500">
                  <span class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                     
                  </span>
                 
              </div>
              <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><a href="#">Our first project with React</a></h2>
              <p class="mb-5 font-light text-gray-500 dark:text-gray-400">Static websites are now used to bootstrap lots of websites and are becoming the basis for a variety of tools that even influence both web designers and developers influence both web designers and developers.</p>
              <div class="flex justify-between items-center">
                  <div class="flex items-center space-x-4">
                     
                  </div>
                  <a href="#" class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline">
                      Read more
                      <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                  </a>
              </div>
          </article>                  
      </div>  
  </div>
</section> -->


<!-- second option -->

        <div class="row">
            <div class="col-md-12">
                <div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
       

               
      </div>
      
    </div>
  </div>
  </div>
</div>
               
               
                <?php else: ?>
                    <div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
    <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?php echo LANG_VALUE_22; ?></h1>

    </div>
</div>

<div class="page" style="padding-top: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">


                <h3 class="special"><?php echo LANG_VALUE_26; ?></h3>
            <div class="cart">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th><?php echo LANG_VALUE_7; ?></th>
                            <th><?php echo LANG_VALUE_8; ?></th>
                            <th><?php echo LANG_VALUE_47; ?></th>
                            <th><?php echo LANG_VALUE_157; ?></th>
                            <th><?php echo LANG_VALUE_158; ?></th>
                            <th>Lens Type</th>
                            <th>Prescription</th>
                            <th><?php echo LANG_VALUE_159; ?></th>
                            <th><?php echo LANG_VALUE_55; ?></th>
                            <th class="text-right"><?php echo LANG_VALUE_82; ?></th>
                        </tr>
                         <?php
                        $table_total_price = 0;

                        $i=0;
                        foreach($_SESSION['cart_p_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_qty'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_qty[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_current_price'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_current_price[$i] = $value;
                        }

                        //  $i=0;
                        // foreach($_SESSION['current_price'] as $key => $value) 
                        // {
                        //     $i++;
                        //     $arr_cart_p_current_price[$i] = $value;
                        // }

                        $i=0;
                        foreach($_SESSION['cart_p_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_featured_photo'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_featured_photo[$i] = $value;
                        }
                        //single vision
                        $i = 0;
                        foreach ($_SESSION['cart_single_sph_right'] as $key => $value) {
                            $i++;
                            $arr_single_sph_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_single_cyl_right'] as $key => $value) {
                            $i++;
                            $arr_single_cyl_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_single_axis_right'] as $key => $value) {
                            $i++;
                            $arr_single_axis_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_single_sph_left'] as $key => $value) {
                            $i++;
                            $arr_single_sph_left[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_single_cyl_left'] as $key => $value) {
                            $i++;
                            $arr_single_cyl_left[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_single_axis_left'] as $key => $value) {
                            $i++;
                            $arr_single_axis_left[$i] = $value;
                        }

                        //progressive vision
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_sph_right'] as $key => $value) {
                            $i++;
                            $arr_progressive_sph_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_cyl_right'] as $key => $value) {
                            $i++;
                            $arr_progressive_cyl_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_axis_right'] as $key => $value) {
                            $i++;
                            $arr_progressive_axis_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_add_right'] as $key => $value) {
                            $i++;
                            $arr_progressive_add_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_sph_left'] as $key => $value) {
                            $i++;
                            $arr_progressive_sph_left[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_cyl_left'] as $key => $value) {
                            $i++;
                            $arr_progressive_cyl_left[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_axis_left'] as $key => $value) {
                            $i++;
                            $arr_progressive_axis_left[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_progressive_add_left'] as $key => $value) {
                            $i++;
                            $arr_progressive_add_left[$i] = $value;
                        }
                        
                        //fetch pd numbers
                        $i = 0;
                        foreach ($_SESSION['cart_s_one_pd'] as $key => $value) {
                            $i++;
                            $arr_s_one_pd[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_s_two_pd_right'] as $key => $value) {
                            $i++;
                            $arr_s_two_pd_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_s_two_pd_left'] as $key => $value) {
                            $i++;
                            $arr_s_two_pd_left[$i] = $value;
                        }

                        $i = 0;
                        foreach ($_SESSION['cart_one_pd'] as $key => $value) {
                            $i++;
                            $arr_one_pd[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_two_pd_right'] as $key => $value) {
                            $i++;
                            $arr_two_pd_right[$i] = $value;
                        }
                        $i = 0;
                        foreach ($_SESSION['cart_two_pd_left'] as $key => $value) {
                            $i++;
                            $arr_two_pd_left[$i] = $value;
                        }

                        //additional detail
                         $i = 0;
                         foreach ($_SESSION['cart_add_detail'] as $key => $value) {
                            $i++;
                            $arr_add_detail[$i] = $value;
                         }
                         //prescription_photo
                        $i = 0;
                        foreach ($_SESSION['pres_photo_upload'] as $key => $value) {
                              $i++;
                              $arr_pres_photo[$i] = $value;
                        }
                                                // type two pd
$i = 0;
foreach ($_SESSION['cart_s_two_pd_right_type'] as $key => $value) {
    $i++;
    $arr_s_two_pd_right_type[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_s_two_pd_left_type'] as $key => $value) {
    $i++;
    $arr_s_two_pd_left_type[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_p_two_pd_right_type'] as $key => $value) {
    $i++;
    $arr_p_two_pd_right_type[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_p_two_pd_left_type'] as $key => $value) {
    $i++;
    $arr_p_two_pd_left_type[$i] = $value;
}
// type two pd

            
                        $i = 0;
foreach ($_SESSION['cart_lens_price'] as $key => $value) {
    $i++;
    $arr_lens_price[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_lens_type'] as $key => $value) {
    $i++;
    $arr_lens_type[$i] = $value;
}

                        ?>
                        <?php for($i=1;$i<=count($arr_cart_p_id);$i++): ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="">
                            </td>
                            <td><?php echo $arr_cart_p_name[$i]; ?></td>
                            <td><?php echo $arr_cart_size_name[$i]; ?></td>
                            <td><?php echo $arr_cart_color_name[$i]; ?></td>
                            <td><?php echo $arr_lens_type[$i]; ?></td>
                            <td><b>Single Vision </b>  <br> 
                                  
                                    <?php 
                                      echo $arr_single_sph_right[$i];   
                                      echo $arr_single_cyl_right[$i];
                                      echo $arr_single_axis_right[$i];
                                    ?> <br>
                                     
                                    <?php 
                                      echo $arr_single_sph_left[$i];   
                                      echo $arr_single_cyl_left[$i];
                                      echo $arr_single_axis_left[$i];
                                    ?><br>
                                    <br>
                                    <?php
                                       echo $arr_s_one_pd[$i];echo '<br>';
                                       echo $arr_s_two_pd_right[$i]; 
                                       echo $arr_s_two_pd_left[$i];
                                       echo $arr_s_two_pd_right_type[$i];
                                       echo $arr_s_two_pd_left_type[$i];
                                    ?> 
                                    <br>
                               <b> Progressive Vision</b> <br> 
                                    
                                    <?php 
                                      echo $arr_progressive_sph_right[$i];   
                                      echo $arr_progressive_cyl_right[$i];
                                      echo $arr_progressive_axis_right[$i];
                                      echo $arr_progressive_add_right[$i];


                                    ?> <br>
                                     
                                    <?php 
                                       echo $arr_progressive_sph_left[$i];   
                                       echo $arr_progressive_cyl_left[$i];
                                       echo $arr_progressive_axis_left[$i];
                                       echo $arr_progressive_add_left[$i];
                                    ?>
                                    <br>
                                    <?php
                                       echo $arr_one_pd[$i];echo '<br>';
                                       echo $arr_two_pd_right[$i]; 
                                       echo $arr_two_pd_left[$i];
                                       echo $arr_p_two_pd_right_type[$i];
                                       echo $arr_p_two_pd_left_type[$i];
                                    ?> 
                                
                            </td>
                            
                            

                            <td><?php echo 'ETB '; ?><?php echo $arr_cart_p_current_price[$i]; ?></td>
                            <td><?php echo $arr_cart_p_qty[$i]; ?></td>
                            <td class="text-right">
                                <?php
                                $row_total_price = $arr_cart_p_current_price[$i]*$arr_cart_p_qty[$i];
                                $table_total_price = $table_total_price + $row_total_price;
                                ?>
                                <?php echo 'ETB '; ?><?php echo $row_total_price; ?>
                            </td>
                        </tr>
                         <tr>
                             <th colspan="" class="total-text">Additional Information</th>
                            
                                  <td colspan="10" >
                                  <?php echo $arr_add_detail[$i]; ?>
                                  </td>
                             
                        </tr>
                        <?php endfor; ?>           
                        <tr>
                            <th colspan="7" class="total-text"><?php echo LANG_VALUE_81; ?></th>
                            <th class="total-amount"><?php echo 'ETB '; ?><?php echo $table_total_price; ?></th>
                        </tr>
                      
                        <!-- <tr>
                            <td colspan="7" class="total-text"><?php echo LANG_VALUE_84; ?></td>
                            <td class="total-amount">
                                <?php 
                                 $shipping_price = 0;
                                ?>
                                
                                <?php echo LANG_VALUE_1; ?><p style="font-size:15px;" id="shipping_price" name="shipping_price" value="<?php echo $shipping_price; ?>"></td>
                        </tr> -->
                       
                        <tr>
                            <th colspan="7" class="total-text"><?php echo LANG_VALUE_82; ?></th>
                            <th class="total-amount">
                                <?php
                                $final_total = $table_total_price  ;
                                ?>
                                <?php echo 'ETB '; ?><?php echo $final_total; ?>
                                <p style="font-size:30px;" id="final2" name="final2" value="<?php echo $table_total_price; ?>">
                                 <!-- <input type="hidden" name="current_price" id="current_price" value="<?php echo $final_total; ?>"> -->
                                
                            </th>
                        </tr>
                    </table> 
                </div>
            </div>
                

                <div class="billing-address">
                    <div class="row">
                        
                        <div class="col-md-6">
                            <h3 class="special"><?php echo 'Customer Info'; ?></h3>
                            <table class="table table-responsive table-bordered bill-address">
                                <tr>
                                    <td><?php echo 'Name'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_name']; echo ' '; echo $_SESSION['customer']['cust_lname']; ?></p></td>
                                </tr>
                               
                               
                            </table>
                        </div>
                        <div class="col-md-6">
                            <form action="" method="post">
                            <?php $csrf->echoInputField(); ?>
                            <h3 class="special"><?php echo 'Customer Location Info'; ?></h3>
                            <table class="table table-responsive table-bordered bill-address">
                                <tr>
                                    <td><?php echo '<span style="font-size:20px;">Are you currently located out of Addis Ababa?</span> <br> <span style="color: #808080;"> Shipping charges will apply to our customers out of Addis Ababa. </span>'; ?><br><br>
                                      
                                    <button type="button" class="btn btn-primary bg-blue-800" id='yes_button' onclick="yes_shipping()"> Yes</button>
	                                <button type="button" class="btn btn-primary bg-blue-800" id='no_button' onclick="no_shipping()"> NO </button>


                                     <input  type="hidden" name="cost_shipment" id="cost_shipment" > <br>
                                     <input  type="hidden" name="ship_plus" id="ship_plus" ><br>
                                     <input type="hidden" id="final" name="final" value="<?php echo $table_total_price; ?>">
                                     <!-- <p id="final2" name="final2" > -->
                                     <!-- <p style="font-size:30px;" id="final3" name="final3" >     -->
                                      <span >
                                        <p id="disp_lens_name" name="disp_lens_name"  style="font-size: 15px;  font-weight: bold;">
                                      </span>
                                     

                                        
                                </td>
                                        
                                </tr>
                               
                                                          
                            </table>
                           
                            <h3 class="special"><?php echo 'Shipping Address Info'; ?></h3>
                            <table class="table table-responsive table-bordered bill-address">
                                  
                                <!-- <tr>
                                    <td><?php echo 'Name'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_name']; ?></p></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Customer Type'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_cname']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Phone Number'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_phone']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Country'; ?></td>
                                    <td>
                                        <?php echo nl2br($_SESSION['customer']['cust_country']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Region'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_region']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'City'; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_city']; ?></td>
                                </tr> -->
                                 <!-- partner -->
                                        <?php if ($_SESSION['customer']['cust_cname'] == 'Individual'): ?>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Name'; ?></label>
                                            <input type="text" class="form-control text-2xl" name="cust_s_name" value="<?php echo $_SESSION['customer']['cust_name']; echo ' '; echo $_SESSION['customer']['cust_lname']; ?>" >
                                        </div>
                                         <div class="form-group">
                                            <label for=""><?php echo 'Phone Number'; ?></label>
                                            <input type="text" class="form-control text-2xl" name="cust_s_phone" value="<?php echo $_SESSION['customer']['cust_phone']; ?>" >
                                         </div>
                                         <div class="form-group">
                                            <label for=""><?php echo 'Drop Off Location'; ?></label>
                                            <input type="text"  placeholder="Enter Drop Address." class="form-control text-2xl" name="cust_drop_address" >
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
                                            <label for=""><?php echo 'Pick-up person Full Name'; ?> </label>
                                            <input type="text"  placeholder="Enter Full Name" class="form-control" name="partner_pickup_name">
                                            
                                        </div>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Pick-up person Phone number'; ?></label>
                                            <input type="text"  placeholder="Enter Pick-up person Phone number" class="form-control" name="partner_pickup_number" >
                                        </div>
                                        <div class="form-group">
                                            <label for=""><?php echo 'Drop Off Location'; ?></label>
                                            <input type="text" placeholder="Enter Drop Off Location" class="form-control" name="partner_drop_address" >
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
                                            <input type="text"  class="form-control" name="company_pickup_name" >
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

                               
                            </table>
                              <!-- To change and fill in  drop off location <a href="customer-billing-shipping-update.php">click here!</a> -->
                                <input type="submit" class="btn btn-primary" style="margin:5px; background-color: #008000 !important;"value="<?php echo 'Finish and go to payment page'; ?>" name="payment_form">
                                <a href="cart.php" class="btn btn-primary" style="background-color:  #373737 !important;"><?php echo LANG_VALUE_21; ?></a>
                            </form>
                            </div>
                      
                    </div>                    
                </div>

                

                <!-- <div class="cart-buttons">
                    <ul>
                        <li><a href="cart.php" class="btn btn-primary"><?php echo LANG_VALUE_21; ?></a></li>
                    </ul>
                </div> -->

				
                        
                </div>
                

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>



<!-- Main modal -->
 
<div id="guest_c" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0  right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal lg:h-full hh">
    <div class="relative p-4 w-full max-w-2xl h-full lg:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 lg:p-5">
                <!-- Modal header -->
                         
            <div class="flex justify-between   pb-4 mb-4 rounded-t border-b lg:mb-5 dark:border-gray-600">
                <h3 class="text-4xl font-semibold text-center text-gray-900 dark:text-white" >
                    Guest Checkout
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="guest_c">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="" method="post" >
                <div class="grid gap-4  mb-4 lg:grid-cols-1">
                    <div>
                        <label for="name" class="block mb-2 text-2xl font-medium text-gray-900 dark:text-white">First Name</label>
                        <input type="text" name="cust_name" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="First Name" required="">

                    </div>
                    <div>
                        <label for="brand" class="block mb-2 text-2xl font-medium text-gray-900 dark:text-white">Fathers Name</label>
                        <input type="text" name="cust_fname" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Fathers Name" required="">
                    </div>
                    <div>
                        <label for="brand" class="block mb-2 text-2xl font-medium text-gray-900 dark:text-white">Phone</label>
                        <input type="tel" id="phone" name="cust_phone" placeholder="0911223344" pattern="[0-9]{10}" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Phone" required="">
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-2xl font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="cust_jemail" id="cust_jemail" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Email" required="">
                    </div>
                    <div>
                        <label for="Address" class="block mb-2 text-2xl font-medium text-gray-900 dark:text-white">Drop Address</label>
                        <input type="text" name="cust_drop" id="cust_drop" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Drop Location" required="">
                        <input type="hidden" class="form-control" name="cust_guest" value="Guest" required>
                    </div>

                   
                </div>
                <button type="submit" name="guest_pass" class="text-white inline-flex items-center bg-yellow-300 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-2xl px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Continue as Guest
                </button>
            </form>
        </div>
    </div>
</div>

<div id="readProductModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0  right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div class="flex justify-between mb-4 rounded-t ">
                    <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                        <h3 class="font-semibold text-3xl ">
                        <!-- Login to your account  -->
                        </h3>
                        
                    </div>
                    <div>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="readProductModal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <form class="content-start" id="validateForm" action="" method="post">
         <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";

}
if ($success_message != '') {
    echo "<div class='success' style='padding: 10px;background:#f1f1f1;margin-bottom:20px;'>" . $success_message . "</div>";
}
?>
            <h3 class="font-semibold text-4xl">Login to your account</h3>            
            <div class="grid  sm:grid-cols-1" style="padding-top: 20px;">
                <div class="form-group">
                     <label class="col-md-12 control-label" for="textinput">Phone Number/Email</label>
                     <div class="col-md-12">
                        <input id="phone" name="logininfo" type="text" autocomplete="off" placeholder="Enter your Phone Number" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    </div>
                </div>              
                <!-- Password input-->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="passwordinput">
                     Password
                    </label>
                    <div class="col-md-12">
                        <input id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="cust_password" type="password" placeholder="Enter your password" >    
                  <span class="show-pass" onclick="toggle()">
                            <i class="far fa-eye" onclick="myFunction(this)" style="padding-top: 10px;"></i>
                          
                        </span> 
                    </div>                 
                </div> 
                <div class="form-group ">
                    <label for=""></label>
                    <input type="submit" class="btn login-btn btn-block" style="color: #fff !important;" value="Signin" name="form1">
                </div>
                <div class="ex-account text-center">
                    <!-- <p>Don't have an account? Signup 
                        <a href="registration.php">here</a>
                    </p> -->
                                <a href="forget-password.php" style="color:#e4144d;"><?php echo LANG_VALUE_97; ?></a>

                    <div class="divider"></div>
                </div>
            </div>
        </form>   
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>

<script>
window.onload = function() {
    if(/iP(hone|ad)/.test(window.navigator.userAgent)) {
        document.body.addEventListener('touchstart', function() {}, false);
    }
}

    var global_p = document.getElementById("disp_vision_price");
    //var global_d = document.getElementById("disp_vision_price2");
    //var inputG = document.getElementById("ship_plus");
    var inputF = document.getElementById("cost_shipment");
    var show_lens_name = document.getElementById("disp_lens_name");
    function yes_shipping() {
  inputF.value = "300";
   document.getElementById("yes_button").style.backgroundColor='#f1cf0a';
  document.getElementById("no_button").style.backgroundColor='';
 // inputG.value = "+ 300 ETB";
  //show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
 // type_price2 = Number(inputG.value);
  show_lens_name.innerHTML = "+ 300 ETB " ;
  sum_price();
}

    function no_shipping() {
  inputF.value = "00";
  document.getElementById("no_button").style.backgroundColor='#f1cf0a';
  document.getElementById("yes_button").style.backgroundColor='';
//  inputG.value = "+ 0 ETB";
 // type_price2 = Number(inputG.value);
  show_lens_name.innerHTML = "+ 0 ETB";
 type_price = Number(inputF.value);
  sum_price();
}

var global_p = 0;
var gg = 0;
var dd = 0;
var type_price = 0;
var type_price2 = 0;
var main_price = 0;
var main_price1 = 0;
//var global_c = document.getElementById("final");
var global_d = 0;
//global_d =  document.getElementById("cost_shipment");
function sum_price() {

main_price = Number(document.getElementById("final").value);
 var local_main_price = main_price;

//  main_price1 = Number(document.getElementById("ship_plus").value);
//  var local_main_price1 = main_price1;
 //type_price2 = Number(document.getElementById("ship_plus").value);
 var local_type_price = type_price;
 var local_type_price1 = type_price2;

  //type_price = ship_price.value;
  //var pp = ship_price;

  gg =  local_type_price1;
  dd = global_c;
  main_price = gg + dd;

  global_p = type_price + local_main_price;
  global_d = local_type_price1;
  document.getElementById("final2").innerHTML = global_p ;
 // document.getElementById("final3").innerHTML = global_d ;
//  document.getElementById("shipping_price").innerHTML = type_price.value ;
 //document.getElementById("etb").innerHTML = "ETB ";

}
</script>



<script>

document.addEventListener("DOMContentLoaded", function(event) {
  document.getElementById('readProductButton').click();
});

document.addEventListener("DOMContentLoaded", function(event) {
  document.getElementById('guest_checkoutc').click();
});

</script>



<script type="text/javascript">
    document.getElementById("reg_btn").onclick = function () {
        location.href = "registration.php";
    };
   
</script>

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