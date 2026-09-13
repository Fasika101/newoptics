 
<?php ini_set('display_errors', 0);?>

 <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
   
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

if (isset($_POST['guest_pass'])) {
 $valid = 1;
global $full_ship_price;
//    $full_ship_price = 67;

//    $full_ship_price = $_POST['current_price'];

//    $full_final = $table_total_price + $full_ship_price;

//    //additional detail
//     $arr_full_final_p = array();

if (empty($_POST['cost_shipment'])) {

    $valid = 0;
    $error_message .= 'Please let us know where you are curently living. With the YES or NO provided.';

}
if (empty($_POST['cust_s_name'])) {

    $valid = 0;
    $error_message .= 'NAME nnot provided.';

}


 $new_key = 1;
 
$first_name = $_POST['cust_name'];
$last_name = $_POST['cust_fname'];
$phone_no = $_POST['cust_phone'];
$email_acc = $_POST['cust_jemail'];
$drop_add = $_POST['cust_drop'];
$acc_type = $_POST['cust_guest'];

$cost_shipment = $_POST['cost_shipment'];
$_SESSION['final_cost_price'][$new_key] = $cost_shipment;

$_SESSION['guest_first_name'][$new_key] = $first_name;
$_SESSION['guest_last_name'][$new_key] = $last_name;
$_SESSION['guest_phone'][$new_key] = $phone_no;
$_SESSION['guest_email'][$new_key] = $email_acc;
$_SESSION['guest_drop'][$new_key] = $drop_add;
$_SESSION['guest_type'][$new_key] = $acc_type;

 if($valid == 1) {

header("Location: payment_guest.php");
 }

}
?>



                  <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}

?>
 

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


                        $table_total_price = 0;

$i = 0;
foreach ($_SESSION['guest_first_name'] as $key => $value) {
    $i++;
    $arr_add_name[$i] = $value;

    $name = $arr_add_name[$i];

}
$i = 0;
foreach ($_SESSION['guest_last_name'] as $key => $value) {
    $i++;
    $arr_add_fname[$i] = $value;

    $fname = $arr_add_fname[$i];

}
$i = 0;
foreach ($_SESSION['guest_phone'] as $key => $value) {
    $i++;
    $arr_add_gphone[$i] = $value;

    $phone = $arr_add_gphone[$i];

}
$i = 0;
foreach ($_SESSION['guest_email'] as $key => $value) {
    $i++;
    $arr_add_email[$i] = $value;

    $jemail = $arr_add_email[$i];

}
$i = 0;
foreach ($_SESSION['guest_drop'] as $key => $value) {
    $i++;
    $arr_add_gdrop[$i] = $value;

    $drop = $arr_add_gdrop[$i];

}
$i = 0;
foreach ($_SESSION['guest_type'] as $key => $value) {
    $i++;
    $arr_add_gtype[$i] = $value;

    $guest_cust = $arr_add_gtype[$i];

}


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
                        //Lens type and price
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
                        <tr>
                            <th colspan="7" class="total-text"><?php echo LANG_VALUE_82; ?></th>
                            <th class="total-amount">
                                <?php
                                $final_total = $table_total_price  ;
                                ?>
                                <?php echo 'ETB '; ?><?php echo $final_total; ?>
                                <p style="font-size:30px;" id="final2" name="final2" value="<?php echo $table_total_price; ?>">
                                
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
                                    <td><?php echo $name . ' ' . $fname; ?></p></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Customer Type'; ?></td>
                                    <td><?php echo $guest_cust; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo 'Phone Number'; ?></td>
                                    <td><?php echo $phone; ?></td>
                                </tr>
                               
                                  
                            </table>
                        </div>
                        <div class="col-md-6">
                            <form action="" method="post">
                            <?php $csrf->echoInputField(); ?>
                            <h3 class="special"><?php echo 'Customer Info'; ?></h3>
                            <table class="table table-responsive table-bordered bill-address">
                                <tr>
                                    <td><?php echo '<span style="font-size:20px;">Are you currently located out of Addis Ababa?</span> <br> <span style="color: #808080;"> Shipping charges will apply to our customers out of Addis Ababa. </span>'; ?><br><br>
                                   
                                    <button type="button" class="btn btn-primary bg-blue-800" id='yes_button' onclick="yes_shipping()"> Yes</button>
	                                <button type="button" class="btn btn-primary bg-blue-800" id='no_button' onclick="no_shipping()"> NO </button>

                                     <input  type="hidden" name="cost_shipment" id="cost_shipment" > <br>
                                     <input  type="hidden" name="ship_plus" id="ship_plus" ><br>
                                     <input type="hidden" id="final" name="final" value="<?php echo $table_total_price; ?>">
                                  
                                      <span >
                                        <p id="disp_lens_name" name="disp_lens_name"  style="font-size: 15px;  font-weight: bold;">
                                      </span>
                                      
                                </td>
                                        
                                </tr>
                               
                                                          
                            </table>
                           
                            <h3 class="special"><?php echo 'Shipping Address Info'; ?></h3>
                            <table class="table table-responsive table-bordered bill-address">
                                  
                          
                                       
                                        <div class="form-group">
                                            <label for=""><?php echo 'Name'; ?></label>
                                            <input type="text" class="form-control text-2xl" name="cust_s_name" value="<?php echo $name . ' ' . $fname; ?>" >
                                        </div>
                                         <div class="form-group">
                                            <label for=""><?php echo 'Phone Number'; ?></label>
                                            <input type="text" class="form-control text-2xl" name="cust_s_phone" value="<?php echo $phone; ?>" >
                                         </div>
                                         <div class="form-group">
                                            <label for=""><?php echo 'Drop Off Location'; ?></label>
                                            <input type="text"  value="<?php echo $drop; ?>"class="form-control text-2xl" name="cust_drop_address" >
                                            <input type="hidden" class="form-control" name="company_drop_address" value="0" >
                                            <input type="hidden" class="form-control" name="company_pickup_name" value="0">
                                            <input type="hidden" class="form-control" name="company_pickup_number" value="0">
                                            <input type="hidden" class="form-control" name="partner_drop_address" value="0">
                                            <input type="hidden" class="form-control" name="partner_pickup_number" value="0">
                                            <input type="hidden" class="form-control" name="partner_pickup_name" value="0">
                                         </div>
                                         <input type="hidden" class="form-control" name="cust_name" value="<?php echo $name; ?>" required>
                                         <input type="hidden" class="form-control" name="cust_fname" value="<?php echo $fname; ?>" required>
                                         <input type="hidden" class="form-control" name="cust_phone" value="<?php echo $phone;?>" required>
                                         <input type="hidden" class="form-control" name="cust_jemail" value="<?php echo $jemail; ?>" required>
                                         <input type="hidden" class="form-control" name="cust_drop" value="<?php echo $drop; ?>" required>
                                         <input type="hidden" class="form-control" name="cust_guest" value="Guest" required>
                           
                            </table>
                              <!-- To change and fill in  drop off location <a href="customer-billing-shipping-update.php">click here!</a> -->
                                <input type="submit" class="btn btn-primary" style="margin:5px; background-color: #008000 !important;"value="<?php echo 'Finish and go to payment page'; ?>" name="guest_pass">
                                <a href="cart.php" class="btn btn-primary" style="background-color:  #373737 !important;"><?php echo LANG_VALUE_21; ?></a>
                            </form>
                            </div>
                      
                    </div>                    
                </div>
         
                </div>
      
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>

<script>
$("button").click(function() {
   $("button").removeClass("active");
   $(this).addClass("active");
});
    var global_p = document.getElementById("disp_vision_price");
    //var global_d = document.getElementById("disp_vision_price2");
    //var inputG = document.getElementById("ship_plus");
    var inputF = document.getElementById("cost_shipment");
    var show_lens_name = document.getElementById("disp_lens_name");
    function yes_shipping() {
  inputF.value = "300";
 // inputG.value = "+ 300 ETB";
  //show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  document.getElementById("yes_button").style.backgroundColor='#f1cf0a';
  document.getElementById("no_button").style.backgroundColor='';
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