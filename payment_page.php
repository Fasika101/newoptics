<?php require_once 'header.php';
$cust_timestamp = time();
?>

<style>
    .modal-overlay {
        display: none;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, .5);
        opacity: 0;
        transition: opacity .2s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-overlay.visible {
        opacity: 1;
    }

    .modal-container {
        flex-basis: 50%;
        padding: 1rem;
        background-color: #fff;
        border-radius: 3px;
    }

    .modal-header {
        display: flex;
        font-weight: bold;
    }

    .modal-close {
        margin-left: auto;
        color: inherit;
        text-decoration: none;
        margin-top: -.5rem;
        font-size: 2rem;
    }

    .modal-content {
        max-height: 600px;
        overflow: auto;
        padding: 20px
    }

    .coupon {
        border-radius: 1px
    }
</style>

<?php

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>





<?php
if (!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}
?>
<?php

$tx_ref = "";
if (isset($_POST['tx_ref'])) {
    $_SESSION['tx_ref'] = $_POST['tx_ref'];
}

$email = "";
if (isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
}


?>

<?php
if (isset($_POST['payment_form'])) {

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
    // $success_message = header("location: " . "login.php");
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

<div class="page-banner" style="height: 50px;  padding-top: 30px;">
    <div class="overlay" style="padding-top: 0px; !important background:white; !important"></div>
    <div class="page-banner-inner">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?php echo 'Payment Section'; ?></h1>

    </div>
</div>

<div class="page" style="padding-top: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php if (!isset($_SESSION['customer'])): ?>
                    <p>
                    <p><?php echo 'Please Register or Login to your account to continue.'; ?></p>
                    <a href="login.php" class="btn btn-primary"><?php echo 'Login Here.'; ?></a>
                    <a href="register_as.php" class="btn btn-primary"><?php echo 'Register Here.'; ?></a>

                    </p>
                <?php else: ?>
                    <div class="cart">
                        <div class="table-responsive">


                            <?php
                            $table_total_price = 0;


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

                            $i = 0;
                            foreach ($_SESSION['cart_p_id'] as $key => $value) {
                                $i++;
                                $arr_cart_p_id[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_size_id'] as $key => $value) {
                                $i++;
                                $arr_cart_size_id[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_size_name'] as $key => $value) {
                                $i++;
                                $arr_cart_size_name[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_color_id'] as $key => $value) {
                                $i++;
                                $arr_cart_color_id[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_color_name'] as $key => $value) {
                                $i++;
                                $arr_cart_color_name[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_p_qty'] as $key => $value) {
                                $i++;
                                $arr_cart_p_qty[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_p_current_price'] as $key => $value) {
                                $i++;
                                $arr_cart_p_current_price[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_p_name'] as $key => $value) {
                                $i++;
                                $arr_cart_p_name[$i] = $value;
                            }

                            $i = 0;
                            foreach ($_SESSION['cart_p_featured_photo'] as $key => $value) {
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
                            ?>
                            <?php for ($i = 1; $i <= count($arr_cart_p_id); $i++): ?>

                                <?php
                                $row_total_price = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i];
                                $table_total_price = $table_total_price + $row_total_price;
                                ?>
                            <?php endfor; ?>
                            <?php
                            $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE country_id=?");
                            $statement->execute(array($_SESSION['customer']['cust_country']));
                            $total = $statement->rowCount();
                            if ($total) {
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $shipping_cost = $row['amount'];
                                }
                            } else {
                                $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost_all WHERE sca_id=1");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $shipping_cost = $row['amount'];
                                }
                            }
                            ?>

                            <?php
                            $ship_total = $_SESSION['customer']['full_final_p'];

                            $final_total = $table_total_price + $shipping_cost + $ship_total;

                            ?>

                        </div>
                    </div>


                    <div class="row" style="padding-left: 20px;">

                        <h3 class="special"><?php echo 'Customer Information'; ?></h3>
                        <div class="col-sm-6" style="box-shadow: none;">
                            <div class="card" style="">
                                <div class="card-body">

                                    <table class="table table-responsive table-bordered bill-address">
                                        <!--<tr>-->
                                        <!--    <td><?php echo 'Customer Type'; ?></td>-->
                                        <!--    <td><?php echo $_SESSION['customer']['cust_cname']; ?></td>-->
                                        <!--</tr>-->
                                        <?php if ($_SESSION['customer']['cust_cname'] == 'partner'): ?>
                                            <tr>
                                                <td><?php echo 'Ordered By'; ?></td>
                                                <td><?php echo $_SESSION['customer']['cust_name']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo 'Partner Phone Number'; ?></td>
                                                <td><?php echo $_SESSION['customer']['cust_phone']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo 'Pick-up person Full Name'; ?></td>
                                                <td><?php echo $_SESSION['customer']['partner_pickup_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo 'Pick-up person Phone number'; ?></td>
                                                <td><?php echo $_SESSION['customer']['partner_pickup_number']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo 'Drop Off Location'; ?></td>
                                                <td><?php echo $_SESSION['customer']['partner_drop_address']; ?></td>
                                            </tr>
                                        <?php elseif ($_SESSION['customer']['cust_cname'] == 'individual'): ?>
                                            <tr>
                                                <td><?php echo 'Customer Name'; ?></td>
                                                <td><?php echo $_SESSION['customer']['cust_name']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo 'Phone Number'; ?></td>
                                                <td><?php echo $_SESSION['customer']['cust_phone']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo 'Drop Off Location'; ?></td>
                                                <td><?php echo $_SESSION['customer']['cust_drop_address']; ?></td>
                                            </tr>

                                        <?php endif; ?>

                                        <tr>
                                            <td><?php echo 'Product Name'; ?></td>

                                            <td>
                                                <?php for ($i = 1; $i <= count($arr_cart_p_id); $i++): ?>
                                                    <?php
                                                    $produt_name = $arr_cart_p_name[$i];

                                                    echo $produt_name;
                                                    echo ', ';
                                                    ?>
                                                <?php endfor; ?>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td><?php echo 'Total Price'; ?></td>
                                            <td> <?php  ?>
                                                <input type="hidden" name="total_price" style="font-size:14px;" value="<?php
                                                                                                                        $ship_final =  $final_total;
                                                                                                                        $table_total_price = $ship_final;
                                                                                                                        echo $table_total_price; ?>"><?php echo '<b> ETB </b>';
                                                                                                                                                        echo $table_total_price;




                                                                                                                                                        ?></input><br>

                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                            <!-- end card one -->
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-6" style="background-color: #FFEA00; !important; border-radius: 5px;">


                            <?php
                            if (isset($_POST['fetch_promo'])) {
                                $promo1 = $_POST['promo1'];
                                global $vlll;
                                global $promo_c;
                                global $promo_price;
                                global $discount_price;

                                //= echo 'Apply';
                                $vlll = 0;
                                $promo_price = 0;
                                $promo_c = 0;
                                $discount_price = 0;


                                $statement = $pdo->prepare("SELECT * FROM promo WHERE promo_code=? AND status='1'");
                                $statement->execute(array($_POST['promo1']));
                                $total = $statement->rowCount();

                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $vll = $row['promo_amount'];
                                    $promo_c = $row['promo_code'];



                                    // echo $vll;
                                }
                                if ($total == 0) {
                                    $error_message .= LANG_VALUE_133;
                                } else {
                                    $vlll =  $vll;
                                }


                                if (empty($vlll)) {
                                    $y = 0;
                                } else {
                                    $y = $vlll;
                                }

                                $x = $y / 100;
                                $discount_price = $table_total_price * $x;

                                $promo_price = $table_total_price - $discount_price;
                            }

                            ?>
                            <form action="" method="post"> <br>
                                <label>Have a Promo Code?</label>
                                <div class="input-group"> <input type="text" class="form-control coupon text-2xl" name="promo1" id="promo1" placeholder="Promo code"> <span class="input-group-append">
                                <button onclick="showDiv()" id="show_btn" type="submit" name="fetch_promo" class="btn btn-primary btn-apply coupon bg-green-400 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-green-500 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400">Apply Promo Code</button> </span> </div>
                                      
                            </form>
                            <br>
                            <div id="show_promo" style="display:block;">
                                <label>Promo Code discount in %</label><br>
                                <input id="promo_disp" type="text" class="form-control coupon text-2xl" name="promo_disp" value="<?php
                                                                                                                        if ($error_message != '') {
                                                                                                                            echo "Promo Code doesn't exist.";
                                                                                                                        } else {
                                                                                                                            $prod_name = 'No Promo Code Entered';
                                                                                                                            if (empty($vlll)) {
                                                                                                                                echo $prod_name;
                                                                                                                            } else {
                                                                                                                                echo $vlll;
                                                                                                                            }
                                                                                                                        }   ?>" readonly>
                                <br> <label>New Discount Price.</label><br>
                                <input id="promo_disp" type="text" class="form-control coupon text-2xl" name="new_price" value="<?php

                                                                                                                        $prod_name1 = '';
                                                                                                                        if (empty($promo_price)) {
                                                                                                                            echo $prod_name1;
                                                                                                                        } else {
                                                                                                                            echo $promo_price;
                                                                                                                        }
                                                                                                                        ?>" readonly>
                                <br>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                        


                    </div>
                    <div class="clear"></div>
                    <h3 class="special"><?php echo LANG_VALUE_33; ?></h3>
                    <div class="row">

                        <?php
                        $checkout_access = 1;
                        if (
                            ($_SESSION['customer']['cust_name'] == '') ||
                            ($_SESSION['customer']['cust_cname'] == '') ||
                            ($_SESSION['customer']['cust_phone'] == '') ||
                            ($_SESSION['customer']['cust_country'] == '') ||
                            ($_SESSION['customer']['cust_region'] == '') ||
                            ($_SESSION['customer']['cust_city'] == '')
                            // ($_SESSION['customer']['cust_b_state']=='') ||
                            // ($_SESSION['customer']['cust_b_zip']=='') ||
                            // ($_SESSION['customer']['cust_s_name']=='') ||
                            // ($_SESSION['customer']['cust_s_cname']=='') ||
                            // ($_SESSION['customer']['cust_s_phone']=='') ||
                            // ($_SESSION['customer']['cust_s_country']=='') ||
                            // ($_SESSION['customer']['cust_s_address']=='') ||
                            // ($_SESSION['customer']['cust_s_city']=='') ||
                            // ($_SESSION['customer']['cust_s_state']=='') ||
                            // ($_SESSION['customer']['cust_s_zip']=='')
                        ) {
                            $checkout_access = 0;
                        }
                        ?>
                        <?php if ($checkout_access == 1): ?>
                            <div class="col-md-12">
                                <div style="color:red;font-size:22px;margin-bottom:50px;">
                                    You must have to fill up all the billing and shipping information from your dashboard panel in order to checkout the order. Please fill up the information going to <a href="customer-billing-shipping-update.php" style="color:red;text-decoration:underline;">this link</a>.
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="container">

                                <div class="row">


                                    <!--<div class="col-sm-6 col-md-4">-->
                                    <!--    <div class="thumbnail">-->
                                    <!--        <div class="caption">-->
                                    <!--             <img src="assets/payment_buttons/cbe.png" alt="CBE Bank" width="300px" height="80px">-->
                                    <!--            <h3>Pay with Direct Bank Deposit</h3>-->

                                    <!--           <p><button class="btn" style="background-color: #008000 !important; !important margin:5px; color: white !important;" id="cbe_direct">CBE Direct Deposit</button></p>-->
                                    <!--        </div>-->
                                    <!--     </div>-->
                                    <!--</div>-->

                                    <div class="label-panel">

                                        <form action="https://newonlineoptics.com/payment/telebirr/submit.php" method="post">

                                            <div class="label-rows">

                                                <div><input type="hidden" id="appid" name="appid" value="d751bdc774c542049d1f1613635d7555"></div>

                                                <div><input type="hidden" id="timeoutExpress" name="timeoutExpress" value="30"></div>

                                                <div><input type="hidden" id="nonce" name="nonce" value="ffcc1cea6038447c90cba1ff3866728b"></div>
                                            </div>

                                            <tr>


                                                <td>
                                                    <?php $promo_price ?>

                                                </td>

                                            </tr>
                                            <div class="label-rows">

                                                <div><input type="hidden" id="outTradeNo" name="outTradeNo" value="2021062421280000011"></div>

                                                <div><input type="hidden" id="subject" name="subject" value="<?php echo $produt_name; ?>"></div>

                                                <div><input type="hidden" id="promo_c" name="promo_c" value="<?php

                                                                                                                if (empty($promo_c)) {
                                                                                                                    echo "No Promo Used";
                                                                                                                } else {
                                                                                                                    echo $promo_c;
                                                                                                                }

                                                                                                                ?>"></div>
                                                <div><input type="hidden" id="promo_a" name="promo_a" value="<?php

                                                                                                                if (empty($vlll)) {
                                                                                                                    echo "";
                                                                                                                } else {
                                                                                                                    echo $vlll;
                                                                                                                }

                                                                                                                ?>"></div>
                                                <div><input type="hidden" id="discount_price" name="discount_price" value="<?php

                                                                                                                            if (empty($discount_price)) {
                                                                                                                                echo "";
                                                                                                                            } else {

                                                                                                                                $tobe_paid = $discount_price / 2;
                                                                                                                                echo $tobe_paid;
                                                                                                                            }

                                                                                                                            ?>"></div>



                                                <div><input type="hidden" id="totalAmount" name="totalAmount" value="<?php

                                                                                                                        if (empty($promo_price)) {
                                                                                                                            echo $table_total_price;
                                                                                                                        } else {
                                                                                                                            echo $promo_price;
                                                                                                                        }

                                                                                                                        ?>"></div>
                                            </div>
                                            <div class="label-rows">

                                                <div><input type="hidden" id="shortCode" name="shortCode" value="500480"></div>

                                                <div><input type="hidden" id="receiveName" name="receiveName" value="New Online Optics"></div>

                                                <div><input type="hidden" id="timestamp" name="timestamp" value="" onfocus="clearIntervalInstance()"
                                                        ondblclick="runIntervalInstance()"></div>
                                            </div>
                                            <div class="label-rows">

                                                <div><input type="hidden" id="notifyUrl" name="notifyUrl" value="https://newonlineoptics.com/payment/telebirr/notify.php" style="width: 500px;">
                                                </div>
                                                <div class="label-title">&nbsp;</div>
                                                <div style="width: 190px">&nbsp;</div>
                                            </div>
                                            <div class="label-rows">

                                                <div><input type="hidden" id="returnUrl" name="returnUrl" value="https://newonlineoptics.com/payment_success.php" style="width: 500px;">
                                                </div>
                                                <div class="label-title">&nbsp;</div>
                                                <div style="width: 190px">&nbsp;</div>
                                            </div>
                                            <div>


                                                <!--<div class="col-sm-6 col-md-4">-->
                                                <!--                            <div class="thumbnail">-->
                                                <!--                                <div class="caption">-->
                                                <!--                                    <img src="assets/payment_buttons/telebirr.png" alt="Telebirr" width="300px" height="80px">-->
                                                <!--                                    <h3>Pay with telebirr</h3>-->
                                                <!--                                    <p><input class="btn" type="submit" value="Pay with Telebirr" style="background-color: #008000 !important; margin:5px; color: white !important;" id="telebirr"></p>-->
                                                <!--                                </div>-->
                                                <!--                             </div>-->
                                                <!--                        </div>-->



                                                <script type="text/javascript">
                                                    if (getQueryString("errmsg") != '' && getQueryString("errmsg") != null) {
                                                        alert(getQueryString("errmsg"))
                                                    }
                                                    var interval;
                                                    $(function() {
                                                        runIntervalInstance();
                                                    });

                                                    function runIntervalInstance() {
                                                        clearIntervalInstance();
                                                        interval = setInterval(function() {
                                                            var timestamp = new Date().getTime();
                                                            $("#timestamp").val(timestamp);
                                                            $("#nonce").val(uuid());

                                                            var nowDate = new Date();
                                                            var year = nowDate.getFullYear();
                                                            var month = nowDate.getMonth() + 1 < 10 ? "0" + (nowDate.getMonth() + 1) :
                                                                nowDate.getMonth() + 1;
                                                            var day = nowDate.getDate() < 10 ? "0" + nowDate.getDate() : nowDate
                                                                .getDate();
                                                            var dateStr = year + "" + month + "" + day;

                                                            $("#outTradeNo").val(dateStr + timestamp);
                                                        }, 1000);
                                                    }

                                                    function clearIntervalInstance() {
                                                        clearInterval(interval);
                                                    }

                                                    function getData() {
                                                        var outTradeNo = $('#outTradeNo').val();
                                                        var subject = $('#subject').val();
                                                        var totalAmount = $('#totalAmount').val();
                                                        var shortCode = $('#shortCode').val();
                                                        var notifyUrl = $('#notifyUrl').val();
                                                        var returnUrl = $('#returnUrl').val();
                                                        var receiveName = $('#receiveName').val();
                                                        var appid = $('#appid').val();
                                                        var timeoutExpress = $('#timeoutExpress').val();
                                                        var nonce = $('#nonce').val();
                                                        var timestamp = $('#timestamp').val();
                                                        $.post("payment/telebirr/getData.php", {
                                                            outTradeNo: outTradeNo,
                                                            subject: subject,
                                                            totalAmount: totalAmount,
                                                            shortCode: shortCode,
                                                            notifyUrl: notifyUrl,
                                                            returnUrl: returnUrl,
                                                            receiveName: receiveName,
                                                            appid: appid,
                                                            timeoutExpress: timeoutExpress,
                                                            nonce: nonce,
                                                            timestamp: timestamp
                                                        }, function(res) {
                                                            $("#sgin").val(res.sign);
                                                            $("#ussd").val(res.ussd);
                                                            $("#encode").val(res.encode);
                                                        });
                                                    }

                                                    function clearData() {
                                                        $("#sgin").val("");
                                                        $("#ussd").val("");
                                                        $("#encode").val("");
                                                    }

                                                    function uuid() {
                                                        var s = [];
                                                        var hexDigits = "0123456789abcdef";
                                                        for (var i = 0; i < 36; i++) {
                                                            s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
                                                        }
                                                        s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
                                                        s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
                                                        s[8] = s[13] = s[18] = s[23] = "-";

                                                        var uuid = s.join("");
                                                        return uuid.replace("-", "").replace("-", "").replace("-", "").replace("-", "");
                                                    }

                                                    function getQueryString(name) {
                                                        var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
                                                        var r = window.location.search.substr(1).match(reg);
                                                        if (r != null) {
                                                            return unescape(r[2]);
                                                        }
                                                        return null;
                                                    }
                                                </script>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="label-panel">

                                        <form action="https://newonlineoptics.com/payment/chapa/submit.php" method="post">
                                            <input type="hidden" name="public_key" value="CHAPUBK-Rm5JbfvNDmST9D4kiWIkQ5sxPHCfqHhA" />
                                            <?php $ref_no = $_SESSION['customer']['cust_lname'] . $cust_timestamp;


                                            ?>
                                            <input type="hidden" id="tx_ref" name="tx_ref" value="<?php echo $ref_no; ?>" />

                                            <input type="hidden" id="currency" name="currency" value="ETB" />

                                            <!--cust data-->
                                            <!--               $_SESSION['customer']['cust_id'],-->
                                            <!--$_SESSION['customer']['cust_name'],-->
                                            <!--$_SESSION['customer']['cust_email'],-->
                                            <!--$_SESSION['customer']['cust_phone'],-->
                                            <!--$_SESSION['customer']['cust_drop_address'],-->
                                            <input type="hidden" id="cust_id_2" name="cust_id_2" value="<?php echo $_SESSION['customer']['cust_id']; ?>" />
                                            <input type="hidden" id="cust_name_2" name="cust_name_2" value="<?php echo $_SESSION['customer']['cust_name'] . ' ' . $_SESSION['customer']['cust_lname']; ?>" />
                                            <input type="hidden" id="cust_email_2" name="cust_email_2" value="<?php echo $_SESSION['customer']['cust_email']; ?>" />
                                            <input type="hidden" id="cust_phone_2" name="cust_phone_2" value="<?php echo $_SESSION['customer']['cust_phone']; ?>" />
                                            <input type="hidden" id="cust_drop_2" name="cust_drop_2" value="<?php echo $_SESSION['customer']['cust_drop_address']; ?>" />
                                            <input type="hidden" id="cust_type" name="cust_type" value="Individual" />

                                            <!--end cust data-->

                                            <input type="hidden" id="phone" name="phone" value="<?php echo $_SESSION['customer']['cust_phone']; ?>" />
                                            <input type="hidden" id="email" name="email" value="<?php echo $_SESSION['customer']['cust_email']; ?>" />
                                            <input type="hidden" id="first_name" name="first_name" value="<?php echo $_SESSION['customer']['cust_name']; ?>" />
                                            <input type="hidden" id="last_name" name="last_name" value="<?php echo $_SESSION['customer']['cust_lname']; ?>" />
                                            <input type="hidden" id="title" name="title" value="<?php echo $produt_name; ?>" />
                                            <input type="hidden" id="description" name="description" value="Payment for New Online Optics." />
                                            <input type="hidden" id="logo" name="logo" value="null" />
                                            <input type="hidden" id="callback_url" name="callback_url" value="https://newonlineoptics.com/payment/chapa/callbackurl.php" />
                                            <input type="hidden" id="return_url" name="return_url" value="https://newonlineoptics.com/payment_success.php" />
                                            <input type="hidden" id="_custtitle" name="_custtitle" value="<?php echo $produt_name; ?>" />




                                            <tr>


                                                <td>
                                                    <?php $promo_price ?>

                                                </td>

                                            </tr>
                                            <div class="label-rows">


                                                <div><input type="hidden" id="subject" name="subject" value="<?php echo $produt_name; ?>"></div>

                                                <div><input type="hidden" id="promo_c" name="promo_c" value="<?php

                                                                                                                if (empty($promo_c)) {
                                                                                                                    echo "No Promo Used";
                                                                                                                } else {
                                                                                                                    echo $promo_c;
                                                                                                                }

                                                                                                                ?>"></div>

                                                <div><input type="hidden" id="promo_a" name="promo_a" value="<?php

                                                                                                                if (empty($vlll)) {
                                                                                                                    echo "";
                                                                                                                } else {
                                                                                                                    echo $vlll;
                                                                                                                }

                                                                                                                ?>"></div>

                                                <div><input type="hidden" id="discount_price" name="discount_price" value="<?php

                                                                                                                            if (empty($discount_price)) {
                                                                                                                                echo "";
                                                                                                                            } else {
                                                                                                                                $tobe_paid = $discount_price / 2;
                                                                                                                                echo $tobe_paid;
                                                                                                                            }

                                                                                                                            ?>"></div>



                                                <div><input type="hidden" id="amount" name="amount" value="<?php

                                                                                                            if (empty($promo_price)) {
                                                                                                                echo $table_total_price;
                                                                                                            } else {
                                                                                                                echo $promo_price;
                                                                                                            }

                                                                                                            ?>"></div>
                                            </div>



                                            <div class="col-sm-6 col-md-4">
                                                <div class="thumbnail">
                                                    <div class="caption">
                                                        <img src="assets/payment_buttons/Chapa Logo.webp" alt="Chapa Button" width="300px" height="60px">
                                                        <h3>Pay with Chapa</h3>
                                                        <p><input class="btn" type="submit" value="Pay with Chapa" style="background-color: #008000 !important; margin:5px; color: white !important;" id="chapa" name="chapa_button"></p>
                                                    </div>
                                                </div>

                                            </div>


                                    </div>
                                    </form>
                                </div>






                                <div id="myModal" class="modal">
                                    <!-- Info content -->
                                    <div class="modal-content" style="  flex-basis: 50%;
                                                                                padding: 1rem;
                                                                                background-color: #fff;
                                                                                border-radius: 3px;
                                                                                width: 400px;">
                                        <span class="close">&times;</span>
                                        <form action="payment/bank/init.php" method="post">
                                            <input type="hidden" name="amount" value="<?php if (empty($promo_price)) {
                                                                                            echo $table_total_price;
                                                                                        } else {
                                                                                            echo $promo_price;
                                                                                        } ?>">
                                            <input type="hidden" name="discount_price" value="<?php $count = 0;
                                                                                                if (empty($discount_price)) {
                                                                                                    echo "No Promo Used";
                                                                                                } else {
                                                                                                    $tobe_paid = $discount_price / 2;
                                                                                                    echo $tobe_paid;
                                                                                                } ?>">

                                            <div><input type="hidden" id="promo_c" name="promo_c" value="<?php

                                                                                                            if (empty($promo_c)) {
                                                                                                                echo "No Promo Used";
                                                                                                            } else {
                                                                                                                echo $promo_c;
                                                                                                            }

                                                                                                            ?>"></div>
                                            <div><input type="hidden" id="promo_a" name="promo_a" value="<?php

                                                                                                            if (empty($vlll)) {
                                                                                                                echo "No Promo Used";
                                                                                                            } else {
                                                                                                                echo $vlll;
                                                                                                            }

                                                                                                            ?>"></div>

                                            <div class="col-md-12 form-group">
                                                <label for=""><?php echo LANG_VALUE_43; ?></span></label><br>
                                                <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    echo nl2br($row['bank_detail']);
                                                }
                                                ?>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label for=""><?php echo LANG_VALUE_44; ?> <br><span style="font-size:12px;font-weight:normal;">(<?php echo LANG_VALUE_45; ?>)</span></label>
                                                <input name="transaction_info" class="form-control" cols="30" rows="10"></input>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_46; ?>" name="form3">
                                            </div>
                                        </form>
                                    </div>
                                </div>





                            </div>
                        <?php endif; ?>

                    </div>


                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("cbe_direct");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    //show/ hide promo

    function showDiv() {
        document.getElementById('show_promo').style.display = "block";
    }
</script>


<?php require_once 'footer.php'; ?>