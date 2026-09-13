
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php require_once 'header.php';

?>

 <style>
 
 table {
  table-layout: fixed;
  width: 100%;
}

.small-cell {
  width: max-content;
}

td {
 
}

/* --- */

.display-table {
  display: table;
  table-layout: fixed;
  width: 100%;
}

.display-table > * {
  display: table-row;
}

.display-table > * > * {
  display: table-cell;

}
     
        /* Adding some basic styling to button */
          
        .btn {
          /* '  text-decoration: none;
            border: none;
            padding: 12px 40px;
            font-size: 16px;
            background-color: green;
            color: #fff;' */        
            /* box-shadow: 7px 6px 28px 1px rgba(0, 0, 0, 0.24); */
            /* cursor: pointer;
            outline: none; */
            transition: 0.2s all;
        }
        /* Adding transformation when the button is active */
          
        .btn:active {
            transform: scale(0.98);
          
            /* Scaling button to 0.98 to its original size */
            /* box-shadow: 3px  1px rgba(0, 0, 0, 0.24); */
            /* Lowering the shadow */
        }


        .new_btn
        {
            border-width: 2px !important;
            border-color: #FBBF24 !important;
           
           
        }

        .cancel_btn
        {
            border-width: 2px !important;
            border-color: #DC2626 !important;
           
           
        }
    </style>

<?php

if (!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if ($total == 0) {
        header('location: index.php');
        exit;
    }
}

foreach ($result as $row) {
    $p_name = $row['p_name'];
    //  $p_old_price = $row['p_old_price'];
    $p_current_price = $row['p_current_price'];
    $p_qty = $row['p_qty'];
    $p_featured_photo = $row['p_featured_photo'];
    $p_description = $row['p_description'];
    //  $p_short_description = $row['p_short_description'];
    $p_feature = $row['p_feature'];
    $p_condition = $row['p_condition'];
    $p_return_policy = $row['p_return_policy'];
    $p_total_view = $row['p_total_view'];
    $p_is_featured = $row['p_is_featured'];
    $p_is_active = $row['p_is_active'];
    $ecat_id = $row['ecat_id'];
}

// Getting all categories name for breadcrumb
$statement = $pdo->prepare("SELECT
                        t1.ecat_id,
                        t1.ecat_name,
                        t1.mcat_id,

                        t2.mcat_id,
                        t2.mcat_name,
                        t2.tcat_id,

                        t3.tcat_id,
                        t3.tcat_name
                
                        FROM tbl_end_category t1
                        JOIN tbl_mid_category t2
                        ON t1.mcat_id = t2.mcat_id
                        JOIN tbl_top_category t3
                        ON t2.tcat_id = t3.tcat_id
                        WHERE t1.ecat_id=?");
$statement->execute(array($ecat_id));
$total = $statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $mcat_name = $row['mcat_name'];
    $tcat_id = $row['tcat_id'];
    $tcat_name = $row['tcat_name'];
}

$p_total_view = $p_total_view + 1;
$statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
$statement->execute(array($p_total_view, $_REQUEST['id']));

$statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $size[] = $row['size_id'];
}

//AND color_qty > 0
$statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=? AND color_qty > 0 ");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $color[] = $row['color_id'];
    $color_req = $row['color_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_frames WHERE p_id=?  ");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $frame[] = $row['frame_id'];
}

if (isset($_POST['form_review'])) {

    $statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=? AND cust_id=?");
    $statement->execute(array($_REQUEST['id'], $_SESSION['customer']['cust_id']));
    $total = $statement->rowCount();

    if ($total) {
        $error_message = LANG_VALUE_68;
    } else {
        $statement = $pdo->prepare("INSERT INTO tbl_rating (p_id,cust_id,comment,rating) VALUES (?,?,?,?)");
        $statement->execute(array($_REQUEST['id'], $_SESSION['customer']['cust_id'], $_POST['comment'], $_POST['rating']));
        $success_message = LANG_VALUE_163;
    }
}

// Getting the average rating for this product
$t_rating = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$tot_rating = $statement->rowCount();
if ($tot_rating == 0) {
    $avg_rating = 0;
} else {
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $t_rating = $t_rating + $row['rating'];
    }
    $avg_rating = $t_rating / $tot_rating;
}

if (isset($_POST['form_add_to_cart'])) {





//upload prescription photo

$valid = 1;
global $ext;
$path = $_FILES['prescr_photo']['name'];
$path_tmp = $_FILES['prescr_photo']['tmp_name'];
if ($path != '') {
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $file_name = basename($path, '.' . $ext);
    if ($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'webp') {
        $valid = 0;
        $error_message .= 'You must have to upload jpg, jpeg, webp, gif or png file<br>';
    }
}

$ai_id = $row = $_POST['upload_time'];

    	
		$final_name = 'prescript'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, 'assets/uploads/prescription_photos/'.$final_name );
       

        ////end prescription photo upload

    $prescription_name = $final_name;


    //get final price//////////////////////////////////////////////////////////////////////////////////////
    $final_price = '0';
    $additional_price = $_POST['disp_vision_price'];
    $additional_detail = $_POST['additional_detail'];
    $calc_price = $_POST['calculated_price'];
    $original_price = $_POST['p_current_price2'];   
    $final_price = (int)$calc_price + (int)$original_price;
   
//get final price//////////////////////////////////////////////////////////////////////////////////////


//get value for Single vision and price
//ssph-right
    //$ssph_left_two = '';
    $result2 = $_POST['ssph-right'];
    $result_explode = explode('|', $result2);
    $ssph_right_one = $result_explode[0];
  //  $ssph_right_two = $result_explode[1];

    //ssph-left
    $result3 = $_POST['ssph-left'];
    $result_explode = explode('|', $result3);
    $ssph_left_one = $result_explode[0];
  //  $ssph_left_two = $result_explode[1];

    //scyl-right
    $result4 = $_POST['scyl-right'];
    $result_explode = explode('|', $result4);
    $scyl_right_one = $result_explode[0];
  //  $scyl_right_two = $result_explode[1];

    //scyl-left
    $result5 = $_POST['scyl-left'];
    $result_explode = explode('|', $result5);
    $scyl_left_one = $result_explode[0];
  //  $scyl_left_two = $result_explode[1];

    //scyl-right
    $result6 = $_POST['saxis-right'];
    $result_explode = explode('|', $result6);
    $saxis_right_one = $result_explode[0];
   // $saxis_right_two = $result_explode[1];
    
    //saxis-left
    $result7 = $_POST['saxis-left'];
    $result_explode = explode('|', $result7);
    $saxis_left_one = $result_explode[0];
   // $saxis_left_two = $result_explode[1];

//get value for Progressive vision 
    //psph-right
    $result9 = $_POST['psph-right'];
    $result_explode = explode('|', $result9);
    $psph_right_one = $result_explode[0];
  //  $psph_right_two = $result_explode[1];
 
    //psph-left
    $result10 = $_POST['psph-left'];
    $result_explode = explode('|', $result10);
    $psph_left_one = $result_explode[0];
  //  $psph_left_two = $result_explode[1];

    //pcyl-right
    $result11 = $_POST['pcyl-right'];
    $result_explode = explode('|', $result11);
    $pcyl_right_one = $result_explode[0];
   // $pcyl_right_two = $result_explode[1];

    //pcyl-left
    $result12 = $_POST['pcyl-left'];
    $result_explode = explode('|', $result12);
    $pcyl_left_one = $result_explode[0];
  //  $pcyl_left_two = $result_explode[1];

    //paxis-right
    $result14 = $_POST['paxis-right'];
    $result_explode = explode('|', $result14);
    $paxis_right_one = $result_explode[0];
   // $paxis_right_two = $result_explode[1];

    //paxis-left
    $result15 = $_POST['paxis-left'];
    $result_explode = explode('|', $result15);
    $paxis_left_one = $result_explode[0];
 //   $paxis_left_two = $result_explode[1];

    //padd-right
    $result16 = $_POST['padd-right'];
    $result_explode = explode('|', $result16);
    $padd_right_one = $result_explode[0];
  //  $padd_right_two = $result_explode[1];

    //padd-left
    $result17 = $_POST['padd-left'];
    $result_explode = explode('|', $result17);
    $padd_left_one = $result_explode[0];
  //  $padd_left_two = $result_explode[1];

//get value for Progressive vision and price
    $psph_left_two = '';
    $result = $_POST['psph-left'];
    $result_explode = explode('|', $result);
    $psph_left_one = $result_explode[0];
  //  $psph_left_two = $result_explode[1];
//Get lens type
    $lens_type_price = $_POST['cost_lens'];
    $lens_type_name = $_POST['name_lens'];
    // echo $lens_type_price;
    // echo $lens_type_name;



//proggressive_lens_remark

if (isset($_POST['s_photosolar_check'])) {
    $s_photosolar_check = $_POST['s_photosolar_check'];
} else {
    $s_photosolar_check = "";
}

if (isset($_POST['s_photochromic_check'])) {
    $s_photochromic_check = $_POST['s_photochromic_check'];
} else {
    $s_photochromic_check = "";
}

if (isset($_POST['s_white_check'])) {
    $s_white_check = $_POST['s_white_check'];

} else {
    $s_white_check = "";
}

if (isset($_POST['s_plascitlens_check'])) {
    $s_plascitlens_check = $_POST['s_plascitlens_check'];
} else {
    $s_plascitlens_check = "";
}

if (isset($_POST['s_sunsensor_check'])) {
    $s_sunsensor_check = $_POST['s_sunsensor_check'];
} else {
    $s_sunsensor_check = "";
}

if (isset($_POST['s_glarefree_check'])) {
    $s_glarefree_check = $_POST['s_glarefree_check'];
} else {
    $s_glarefree_check = "";
}

if (isset($_POST['s_antiglare_check'])) {
    $s_antiglare_check = $_POST['s_antiglare_check'];

} else {
    $s_antiglare_check = "";
}

if (isset($_POST['s_arc_check'])) {
    $s_arc_check = $_POST['s_arc_check'];
} else {
    $s_arc_check = "";
}

if (isset($_POST['s_hmc_check'])) {
    $s_hmc_check = $_POST['s_hmc_check'];
} else {
    $s_hmc_check = "";
}

if (isset($_POST['s_progressivelens_check'])) {
    $s_progressivelens_check = $_POST['s_progressivelens_check'];

} else {
    $s_progressivelens_check = "";
}

if (isset($_POST['s_bicfocal_check'])) {
    $s_bicfocal_check = $_POST['s_bicfocal_check'];
} else {
    $s_bicfocal_check = "";
}

if (isset($_POST['s_scratchresistant_check'])) {
    $s_scratchresistant_check = $_POST['s_scratchresistant_check'];
} else {
    $s_scratchresistant_check = "";
}
////// ------------------- ///////////////

    
//proggressive_lens_remark 
if(isset($_POST['p_photosolar_check'])){
      $p_photosolar_check = $_POST['p_photosolar_check'];
 }else{
     $p_photosolar_check = "";
}

if(isset($_POST['p_photochromic_check'])){
     $p_photochromic_check = $_POST['p_photochromic_check'];
}else{
      $p_photochromic_check ="";
}

if(isset($_POST['p_white_check'])){
     $p_white_check = $_POST['p_white_check'];

}else{
     $p_white_check = "";
}

if(isset($_POST['p_plascitlens_check'])){
     $p_plascitlens_check = $_POST['p_plascitlens_check'];
}else{
     $p_plascitlens_check = "";
}

if (isset($_POST['p_sunsensor_check'])) {
    $p_sunsensor_check = $_POST['p_sunsensor_check'];
} else {
    $p_sunsensor_check = "";
}

if (isset($_POST['p_glarefree_check'])) {
    $p_glarefree_check = $_POST['p_glarefree_check'];
} else {
    $p_glarefree_check = "";
}

if (isset($_POST['p_antiglare_check'])) {
    $p_antiglare_check = $_POST['p_antiglare_check'];

} else {
    $p_antiglare_check = "";
}

if (isset($_POST['p_arc_check'])) {
    $p_arc_check = $_POST['p_arc_check'];
} else {
    $p_arc_check = "";
}

if (isset($_POST['p_hmc_check'])) {
    $p_hmc_check = $_POST['p_hmc_check'];
} else {
    $p_hmc_check = "";
}

if (isset($_POST['p_progressivelens_check'])) {
    $p_progressivelens_check = $_POST['p_progressivelens_check'];

} else {
    $p_progressivelens_check = "";
}

if (isset($_POST['p_bicfocal_check'])) {
    $p_bicfocal_check = $_POST['p_bicfocal_check'];
} else {
    $p_bicfocal_check = "";
}

if (isset($_POST['p_scratchresistant_check'])) {
    $p_scratchresistant_check = $_POST['p_scratchresistant_check'];
} else {
    $p_scratchresistant_check = "";
}
////// ------------------- ///////////////

    // getting the currect stock of this product
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $current_p_qty = $row['p_qty'];
    }
    //$SESSION['color_id']
    $statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=? AND color_id=?");
    $statement->execute(array($_REQUEST['id'], $_POST['color_id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $current_color_qty = $row['color_qty'];
    }
    
     if ($_POST['p_qty'] > $current_p_qty || $_POST['p_qty'] > $current_color_qty ):
        $temp_msg = 'Sorry! There are only ' . $current_p_qty . ' item(s) in stock & '. $current_color_qty .' available frame(s) for this color in our stock.';

        
        
        
        ?>
	<script type="text/javascript">alert('<?php echo $temp_msg; ?>');</script>
		<?php
else:
        if (isset($_SESSION['cart_p_id'])) {
            $arr_cart_p_id = array();
            $arr_cart_size_id = array();
            $arr_cart_color_id = array();
            $arr_cart_p_qty = array();
            $arr_cart_p_current_price = array();
            $arr_cart_frame_id = array();
            $arr_cart_frame_price = array();

            //Single vision
            $arr_single_sph_right = array();
            $arr_single_cyl_right = array();
            $arr_single_axis_right = array();
            $arr_single_sph_left = array();
            $arr_single_cyl_left = array();
            $arr_single_axis_left = array();
            //Progressive vision
            $arr_progressive_cyl_right = array();
            $arr_progressive_sph_right = array();
            $arr_progressive_axis_right = array();
            $arr_progressive_add_right = array();
            $arr_progressive_sph_left = array();
            $arr_progressive_cyl_left = array();
            $arr_progressive_axis_left = array();
            $arr_progressive_add_left = array();

            //single price
             $arr_single_cyl_right_price = array();
             $arr_single_axis_right_price = array();
             $arr_single_sph_left_price = array();
             $arr_single_cyl_left_price = array();
             $arr_single_axis_left_price = array();
    
             //pd numbers
               $arr_one_pd = array();
               $arr_two_pd_right = array();
               $arr_two_pd_left = array();

               $arr_s_one_pd = array();
               $arr_s_two_pd_right = array();
               $arr_s_two_pd_left = array();
               
               $arr_s_two_pd_right_type = array();
               $arr_s_two_pd_left_type = array();
                
            //   $arr_p_two_pd_right = array();
            //   $arr_p_two_pd_left_type = array();
                
               $arr_p_two_pd_right_type = array();
               $arr_p_two_pd_left_type = array();

            // progressive price
            $arr_progressive_sph_left_price = array();

            //Lens type and price
            $arr_lens_price = array();
            $arr_lens_type = array();

            // prescription remark
            $arr_p_photosolar_check = array();
            $arr_p_photochromc_check = array();
            $arr_p_white_check = array();
            $arr_p_plascitlens_check= array();

            $arr_p_sunsensor_check = array();
            $arr_p_glarefree_check = array();
            $arr_p_antiglare_check = array();
            $arr_p_arc_check= array();

            $arr_p_hmc_check = array();
            $arr_p_progressivelens_check = array();
            $arr_p_bicfocal_check = array();
            $arr_p_scratchresistant_check= array();

             // prescription remark
            $arr_s_photosolar_check = array();
            $arr_s_photochromc_check = array();
            $arr_s_white_check = array();
            $arr_s_plascitlens_check= array();

            $arr_s_sunsensor_check = array();
            $arr_s_glarefree_check = array();
            $arr_s_antiglare_check = array();
            $arr_s_arc_check= array();

            $arr_s_hmc_check = array();
            $arr_s_progressivelens_check = array();
            $arr_s_bicfocal_check = array();
            $arr_s_scratchresistant_check= array();

             //additional detail
            $arr_add_detail = array();

            //prescription_photo
            $arr_pres_photo = array();


            //final price
            $arr_final_price = array();
            

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
            foreach ($_SESSION['cart_color_id'] as $key => $value) {
                $i++;
                $arr_cart_color_id[$i] = $value;
            }
        
            $added = 0;
            if (!isset($_POST['size_id'])) {
                $size_id = 0;
            } else {
                $size_id = $_POST['size_id'];
            }

            if (!isset($_POST['color_id'])) {
                $color_id = 0;
            } else {
                $color_id = $_POST['color_id'];
            }

            for ($i = 1; $i <= count($arr_cart_p_id); $i++) {
                if (($arr_cart_p_id[$i] == $_REQUEST['id']) && ($arr_cart_size_id[$i] == $size_id) && ($arr_cart_color_id[$i] == $color_id) ) {
                    $added = 1;
                    break;
                }
            }
            if ($added == 2) {
                $error_message1 = 'This product is already added to the shopping cart.';
            } 
            else 
           
            {
                $i = 0;
                foreach ($_SESSION['cart_p_id'] as $key => $res) {
                    $i++;
                }
                $new_key = $i + 1;

                if (isset($_POST['size_id'])) {

                    $size_id = $_POST['size_id'];

                    $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
                    $statement->execute(array($size_id));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        $size_name = $row['size_name'];
                    }
                } else {
                    $size_id = 0;
                    $size_name = '';
                }

                if (isset($_POST['color_id'])) {
                    $color_id = $_POST['color_id'];
                    $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
                    $statement->execute(array($color_id));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        $color_name = $row['color_name'];
                    }
                } else {
                    $color_id = 0;
                    $color_name = '';
                }

                $_SESSION['cart_p_id'][$new_key] = $_REQUEST['id'];
                $_SESSION['cart_size_id'][$new_key] = $size_id;
                $_SESSION['cart_size_name'][$new_key] = $size_name;
                $_SESSION['cart_color_id'][$new_key] = $color_id;
                $_SESSION['cart_color_name'][$new_key] = $color_name;
                $_SESSION['cart_p_qty'][$new_key] = $_POST['p_qty'];
              
                // single_sph_right
                $_SESSION['cart_single_sph_right'][$new_key] = $ssph_right_one;
                $_SESSION['cart_single_cyl_right'][$new_key] = $scyl_right_one;
                $_SESSION['cart_single_axis_right'][$new_key] = $saxis_right_one;
                $_SESSION['cart_single_sph_left'][$new_key] = $ssph_left_one;
                $_SESSION['cart_single_cyl_left'][$new_key] = $scyl_left_one;
                $_SESSION['cart_single_axis_left'][$new_key] = $saxis_left_one;
                // price

                //pd numbers
                 $_SESSION['cart_one_pd'][$new_key] = $_POST['p_one_pd'];
                 $_SESSION['cart_two_pd_right'][$new_key] = $_POST['p_two_pd_right'];
                 $_SESSION['cart_two_pd_left'][$new_key] = $_POST['p_two_pd_left'];

                 $_SESSION['cart_s_one_pd'][$new_key] = $_POST['s_one_pd'];
                 $_SESSION['cart_s_two_pd_right'][$new_key] = $_POST['s_two_pd_right'];
                 $_SESSION['cart_s_two_pd_left'][$new_key] = $_POST['s_two_pd_left'];
                 
                 
                 $_SESSION['cart_s_two_pd_left_type'][$new_key] = $_POST['s_two_pd_left_type'];
                 $_SESSION['cart_s_two_pd_right_type'][$new_key] = $_POST['s_two_pd_right_type'];
                 $_SESSION['cart_p_two_pd_left_type'][$new_key] = $_POST['p_two_pd_left_type'];
                 $_SESSION['cart_p_two_pd_right_type'][$new_key] = $_POST['p_two_pd_right_type'];

                 //progressive
                $_SESSION['cart_progressive_sph_right'][$new_key] = $psph_right_one;
                $_SESSION['cart_progressive_cyl_right'][$new_key] = $pcyl_right_one;
                $_SESSION['cart_progressive_axis_right'][$new_key] = $paxis_right_one;
                $_SESSION['cart_progressive_add_right'][$new_key] = $padd_right_one;

                //$_SESSION['cart_progressive_sph_left'][$new_key] = $psph_left_one;
                $_SESSION['cart_progressive_sph_left'][$new_key] = $psph_left_one;
                $_SESSION['cart_progressive_cyl_left'][$new_key] = $pcyl_left_one;
                $_SESSION['cart_progressive_axis_left'][$new_key] = $paxis_left_one;
                $_SESSION['cart_progressive_add_left'][$new_key] = $padd_left_one;

                //price
                $_SESSION['cart_final_price'][$new_key] = $final_price;
                $_SESSION['cart_frame_price'][$new_key] = $original_price;

                //Lens type and price
                $_SESSION['cart_lens_price'][$new_key] = $lens_type_price;
                $_SESSION['cart_lens_type'][$new_key] = $lens_type_name;
                $_SESSION['cart_p_current_price'][$new_key] = $final_price; 
                $_SESSION['cart_p_name'][$new_key] = $_POST['p_name'];
                $_SESSION['cart_p_featured_photo'][$new_key] = $_POST['p_featured_photo'];

                $_SESSION['cart_add_detail'][$new_key] = $additional_detail;
                $_SESSION['pres_photo_upload'][$new_key] = $prescription_name;

                //progressive lens type remark
                $_SESSION['p_photosolar_check'][$new_key] = $p_photosolar_check;
                $_SESSION['p_photochromic_check'][$new_key] = $p_photochromic_check;
                $_SESSION['p_white_check'][$new_key] = $p_white_check;
                $_SESSION['p_plasctic_check'][$new_key] = $p_plascitlens_check;

                $_SESSION['p_sunsensor_check'][$new_key] = $p_sunsensor_check;
                $_SESSION['p_glarefree_check'][$new_key] = $p_glarefree_check;
                $_SESSION['p_antiglare_check'][$new_key] = $p_antiglare_check;
                $_SESSION['p_arc_check'][$new_key] = $p_arc_check;

                $_SESSION['p_hmc_check'][$new_key] = $p_hmc_check;
                $_SESSION['p_progressivelens_check'][$new_key] = $p_progressivelens_check;
                $_SESSION['p_bicfocal_check'][$new_key] = $p_bicfocal_check;
                $_SESSION['p_scratchresistant_check'][$new_key] = $p_scratchresistant_check;  
                
                //single lens type remark
             $_SESSION['s_photosolar_check'][$new_key] = $s_photosolar_check;
             $_SESSION['s_photochromic_check'][$new_key] = $s_photochromic_check;
             $_SESSION['s_white_check'][$new_key] = $s_white_check;
             $_SESSION['s_plasctic_check'][$new_key] = $s_plascitlens_check;

             $_SESSION['s_sunsensor_check'][$new_key] = $s_sunsensor_check;
             $_SESSION['s_glarefree_check'][$new_key] = $s_glarefree_check;
             $_SESSION['s_antiglare_check'][$new_key] = $s_antiglare_check;
             $_SESSION['s_arc_check'][$new_key] = $s_arc_check;

             $_SESSION['s_hmc_check'][$new_key] = $s_hmc_check;
             $_SESSION['s_progressivelens_check'][$new_key] = $s_progressivelens_check;
             $_SESSION['s_bicfocal_check'][$new_key] = $s_bicfocal_check;
             $_SESSION['s_scratchresistant_check'][$new_key] = $s_scratchresistant_check;  


                $success_message1 = 'Product is added to the cart successfully!';
            }

            } 
        else 
        {
               if (isset($_POST['size_id'])) {

                $size_id = $_POST['size_id'];

                $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
                $statement->execute(array($size_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $size_name = $row['size_name'];
                }
            } else {
                $size_id = 0;
                $size_name = '';
            }

            if (isset($_POST['color_id'])) {
                $color_id = $_POST['color_id'];
                $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
                $statement->execute(array($color_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $color_name = $row['color_name'];
                }
            } else {
                $color_id = 0;
                $color_name = '';
            }

            if (isset($_POST['frame_id'])) {
                $frame_id = $_POST['frame_id'];
                $statement = $pdo->prepare("SELECT * FROM tbl_frames WHERE frame_id=?");
                $statement->execute(array($frame_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $frame_name = $row['frame_name'];
                }
            } else {
                $frame_id = 0;
                $frame_name = '';
            }
            if (isset($_POST['pres_id'])) {
                $pres_id = $_POST['pres_id'];
                $statement = $pdo->prepare("SELECT * FROM single_prescription WHERE pres_id=?");
                $statement->execute(array($pres_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $ssph_right = $row['right_sph'];
                }
            } else {
                $pres_id = 0;
                $ssph_right = '';
            }




            $_SESSION['cart_p_id'][1] = $_REQUEST['id'];
            $_SESSION['cart_size_id'][1] = $size_id;
            $_SESSION['cart_size_name'][1] = $size_name;
            $_SESSION['cart_color_id'][1] = $color_id;
            $_SESSION['cart_color_name'][1] = $color_name;
            $_SESSION['cart_p_qty'][1] = $_POST['p_qty'];
            $_SESSION['cart_frame_id'][1] = $frame_id;

             // single_sph_right
                $_SESSION['cart_single_sph_right'][1] = $ssph_right_one;
                $_SESSION['cart_single_cyl_right'][1] = $scyl_right_one;
                $_SESSION['cart_single_axis_right'][1] = $saxis_right_one;
                $_SESSION['cart_single_sph_left'][1] = $ssph_left_one;
                $_SESSION['cart_single_cyl_left'][1] = $scyl_left_one;
                $_SESSION['cart_single_axis_left'][1] = $saxis_left_one;
        
            //progressive
            $_SESSION['cart_progressive_sph_right'][1] = $psph_right_one;
            $_SESSION['cart_progressive_cyl_right'][1] = $pcyl_right_one;
            $_SESSION['cart_progressive_axis_right'][1] = $paxis_right_one;
            $_SESSION['cart_progressive_add_right'][1] = $padd_right_one;

            //$_SESSION['cart_progressive_sph_left'][1] = $psph_left_one;
            $_SESSION['cart_progressive_sph_left'][1] = $psph_left_one;
            $_SESSION['cart_progressive_cyl_left'][1] = $pcyl_left_one;
            $_SESSION['cart_progressive_axis_left'][1] = $paxis_left_one;
            $_SESSION['cart_progressive_add_left'][1] = $padd_left_one;
            
            //price
           $_SESSION['cart_final_price'][1] = $final_price;
           $_SESSION['cart_frame_price'][1] = $original_price;

           //pd numbers
            $_SESSION['cart_one_pd'][1] = $_POST['p_one_pd'];
            $_SESSION['cart_two_pd_right'][1] = $_POST['p_two_pd_right'];
            $_SESSION['cart_two_pd_left'][1] = $_POST['p_two_pd_left'];

            $_SESSION['cart_s_one_pd'][1] = $_POST['s_one_pd'];
            $_SESSION['cart_s_two_pd_right'][1] = $_POST['s_two_pd_right'];
            $_SESSION['cart_s_two_pd_left'][1] = $_POST['s_two_pd_left'];
            
            //type two pd
            $_SESSION['cart_s_two_pd_left_type'][1] = $_POST['s_two_pd_left_type'];
            $_SESSION['cart_s_two_pd_right_type'][1] = $_POST['s_two_pd_right_type'];
            $_SESSION['cart_p_two_pd_left_type'][1] = $_POST['p_two_pd_left_type'];
            $_SESSION['cart_p_two_pd_right_type'][1] = $_POST['p_two_pd_right_type'];

            //Lens type and price
            $_SESSION['cart_lens_price'][1] = $lens_type_price;
            $_SESSION['cart_lens_type'][1] = $lens_type_name;
            $_SESSION['cart_p_current_price'][1] = $final_price;
            $_SESSION['cart_p_name'][1] = $_POST['p_name'];
            $_SESSION['cart_p_featured_photo'][1] = $_POST['p_featured_photo'];
            $_SESSION['cart_add_detail'][1] = $additional_detail;
            $_SESSION['pres_photo_upload'][1] = $prescription_name;

            //progressive lens type remark
             $_SESSION['p_photosolar_check'][1] = $p_photosolar_check;
             $_SESSION['p_photochromic_check'][1] = $p_photochromic_check;
             $_SESSION['p_white_check'][1] = $p_white_check;
             $_SESSION['p_plasctic_check'][1] = $p_plascitlens_check;

             $_SESSION['p_sunsensor_check'][1] = $p_sunsensor_check;
             $_SESSION['p_glarefree_check'][1] = $p_glarefree_check;
             $_SESSION['p_antiglare_check'][1] = $p_antiglare_check;
             $_SESSION['p_arc_check'][1] = $p_arc_check;

             $_SESSION['p_hmc_check'][1] = $p_hmc_check;
             $_SESSION['p_progressivelens_check'][1] = $p_progressivelens_check;
             $_SESSION['p_bicfocal_check'][1] = $p_bicfocal_check;
             $_SESSION['p_scratchresistant_check'][1] = $p_scratchresistant_check;   

             //single lens type remark
             $_SESSION['s_photosolar_check'][1] = $s_photosolar_check;
             $_SESSION['s_photochromic_check'][1] = $s_photochromic_check;
             $_SESSION['s_white_check'][1] = $s_white_check;
             $_SESSION['s_plasctic_check'][1] = $s_plascitlens_check;

             $_SESSION['s_sunsensor_check'][1] = $s_sunsensor_check;
             $_SESSION['s_glarefree_check'][1] = $s_glarefree_check;
             $_SESSION['s_antiglare_check'][1] = $s_antiglare_check;
             $_SESSION['s_arc_check'][1] = $s_arc_check;

             $_SESSION['s_hmc_check'][1] = $s_hmc_check;
             $_SESSION['s_progressivelens_check'][1] = $s_progressivelens_check;
             $_SESSION['s_bicfocal_check'][1] = $s_bicfocal_check;
             $_SESSION['s_scratchresistant_check'][1] = $s_scratchresistant_check;  

            $success_message1 = 'Product is added to the cart successfully!';
           

        }
    endif;
}

?>
            
					<script type="text/javascript">
						if (getQueryString("errmsg") != '' && getQueryString("errmsg") != null) {
							alert(getQueryString("errmsg"))
						}
						var interval;
						$(function () {
							runIntervalInstance();
						});

						function runIntervalInstance() {
							clearIntervalInstance();
							interval = setInterval(function () {
								var timestamp = new Date().getTime();
								$("#timestamp").val(timestamp);
								$("#nonce").val(uuid());

								var nowDate = new Date();
								var year = nowDate.getFullYear();
								var month = nowDate.getMonth() + 1 < 10 ? "0" + (nowDate.getMonth() + 1)
									: nowDate.getMonth() + 1;
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
							}, function (res) {
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
           
<div class="page">
    <?php
if ($error_message1 != '') {
    echo "<script>alert('" . $error_message1 . "')</script>";
}
if ($success_message1 != '') {
    //echo "<script>alert('" . $success_message1 . "')</script>";
      echo "<script>
if(confirm('Product is added to the cart successfully!')) {
     window.location.href = '';
}
          </script>"; 

//    // header('location: product.php?id=' . $_REQUEST['id']);
//     echo " <script>
//                 alert('Product is added to the cart successfully!');

//                  window.location.href = 'product.php?id=';
//             </script>";

//     exit;

}

?>





	<div class="container" padding-bottom: 0px;>
		<div class="row">
			<div class="col-md-12">
                <div class="breadcrumb mb_30">
                    <ul>
                        <!-- <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL . 'product-category.php?id=' . $tcat_id . '&type=top-category' ?>"><?php echo $tcat_name; ?></a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL . 'product-category.php?id=' . $mcat_id . '&type=mid-category' ?>"><?php echo $mcat_name; ?></a></li>
                        <li>></li>
                        <li><a href="<?php echo BASE_URL . 'product-category.php?id=' . $ecat_id . '&type=end-category' ?>"><?php echo $ecat_name; ?></a></li>
                        <li>></li>
                        <li><?php echo $p_name; ?></li> -->
                    </ul>
                </div>

				<div class="product">
					<div class="row">
						<div class="col-md-5">
							<ul class="prod-slider">

								<li style="background-image: url(assets/uploads/<?php echo $p_featured_photo; ?>);">
                                    <a class="popup" href="assets/uploads/<?php echo $p_featured_photo; ?>"></a>
								</li>
                                <?php
$statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    ?>
                                    <li style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>);">
                                        <a class="popup" href="assets/uploads/product_photos/<?php echo $row['photo']; ?>"></a>
                                    </li>
                                    <?php
}
?>
							</ul>
							<div id="prod-pager">
								<a data-slide-index="0" href=""><div class="prod-pager-thumb" style="background-image: url(assets/uploads/<?php echo $p_featured_photo; ?>"></div></a>
                                <?php
$i = 1;
$statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    ?>
                                    <a data-slide-index="<?php echo $i; ?>" href=""><div class="prod-pager-thumb" style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>"></div></a>
                                    <?php
$i++;
}
?>
							</div>
							                            <br> <br> <br> <br> <br> <h6 class=" text-red-700 " style=" padding: 3px; border-radius: 5px; "><b>DISCLAIMER: </b> <span style="color: gray !important;">Product Color May Slightly Vary Due to Photographic Lighting Sources or Your Monitor Settings.</span></h6>

						</div>
						<div class="col-md-7">
							<div class="p-title"><h2><?php echo $p_name; ?></h2></div>
							<div class="p-review">
								<div class="rating" style="color: #FFD700 !important;">
                                    <?php
if ($avg_rating == 0) {
    echo '';
} elseif ($avg_rating == 1.5) {
    echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
} elseif ($avg_rating == 2.5) {
    echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
} elseif ($avg_rating == 3.5) {
    echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
} elseif ($avg_rating == 4.5) {
    echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
} else {
    for ($i = 1; $i <= 5; $i++) {
        ?>
                                            <?php if ($i > $avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif;?>
                                            <?php
}
}
?>
                                </div>
							</div>
						
                            <form action="" method="post" enctype="multipart/form-data">
                            <div class="p-quantity">
                                <div class="row">
                                    <?php if (isset($size)): ?>
                                    <div class="col-md-12 mb_20">
                                        <?php echo LANG_VALUE_52; ?> <br>
                                        <select name="size_id" class="form-control select2" style="width: 50px; !important;">
                                            <?php
$statement = $pdo->prepare("SELECT * FROM tbl_size");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    if (in_array($row['size_id'], $size)) {
        ?>
                                                    <option value="<?php echo $row['size_id']; ?>"><?php echo $row['size_name']; ?></option>
                                                    <?php
}
}
?>
                                        </select>
                                    </div>
                                    <?php endif;?>

                                    <?php if (isset($color)): ?>
                                    <div class="col-md-12">
                                        <?php echo LANG_VALUE_53; ?> <br>
                                        <select name="color_id" class="form-control select2" style="width: 20%; !important;
">
                                            <?php
$statement = $pdo->prepare("SELECT * FROM tbl_color");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    if (in_array($row['color_id'], $color)) {
        ?>
                                                    <option value="<?php echo $row['color_id']; ?>"><?php echo $row['color_name']; ?></option>
                                                    <?php
}
}
?>
                                        </select>
                                    </div>
                                    <?php endif;?>

                                     <!-- <?php if (isset($frame)): ?>
                                        <div class="col-md-12">
                                        Lens Type <br>
                                        <select name="frame_id" class="form-control select2" style="width:auto;">
                                            <?php
                                                $statement = $pdo->prepare("SELECT * FROM tbl_frames");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result as $row) {
                                                    if (in_array($row['frame_id'], $frame)) {
                                                        ?>
                                                    <option value="<?php echo $row['frame_id']; ?>"><?php echo $row['frame_name']; ?></option>
                                                    <?php
                                                }
                                                }
                                                ?>
                                        </select>
                                        </div>
                                    <?php endif;?> -->
                <!-- vision -->

    <div class="col-md-12" style="padding-top: 10px;">

    <div class="perscription_main_menu">
        <span >
           <p id="disp_lens_name"  class="text-green-500" style="font-size: 15px;  font-weight: bold;">
        </span>
   
    <!--<div class="btn" style="background-color: #373737 !important; margin:5px;" >-->
    <!--        <a style="cursor: pointer; color: white;" onclick="myFunction()" name="frame_type" id="cancel_some">Frame only</a>-->
    <!--    </div>-->
        
        <a role="button" style="cursor: pointer; color: white;" >
             <div > 
            </div></a>
            <button onclick="myFunction()" name="frame_type" id="cancel_some"  type="button" class="new_btn focus:outline-blue text-white bg-yellow-300 hover:bg-yellow-500  focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xl  px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900 dark:hover:text-white">Frame Only</button>

           <!-- <a role="button" style="cursor: pointer; color: white;" onclick="myFunction()" name="frame_type" id="cancel_some">
                 <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >Frame Only  
                 </div>
            </a> -->
        
      <!--  <div class="btn" style="background-color: #373737 !important; margin:5px;" >-->
		    <!--<a style="cursor: pointer; color: white;"  class="main_btn" main_target="8">Select Lens(no prescription)</a>-->
      <!--  </div>-->
      <!--  <div class="btn" style="background-color: #373737 !important; margin:5px;" >-->
		    <!--<a style="cursor: pointer; color: white;" class="main_btn" main_target="9">Select Lens(with prescription)</a>-->
      <!--  </div>-->
      <button   main_target="8"  type="button" class="new_btn main_btn focus:outline-none text-white bg-yellow-300 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xl  px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Select Lens(no prescription)</button>
    
      <button   main_target="9" type="button" class="new_btn main_btn focus:outline-none text-white bg-yellow-300 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xl  px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Select Lens(with prescription)</button>

      <button   onclick="refreshPage()" id="cancel" type="button" class="new_btn focus:outline-none text-white bg-yellow-300 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xl  px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900 dark:hover:text-white">Cancel</button>

      <!-- <button  type="button" main_target="8" class="main_btn text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-large rounded-lg text-lg px-5 py-3.5 text-center me-2 mb-2">Select Lens(no prescription)  </button> -->
        
         <!-- <a role="button" style="cursor: pointer; color: white;" class="main_btn" main_target="8">
                 <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >Select Lens(no prescription)  
                 </div>
            </a> -->

            <!-- <button  main_target="9" type="button" class="main_btn text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-large rounded-lg text-lg px-5 py-3.5 text-center me-2 mb-2">Select Lens(with prescription)  </button> -->
		    <!-- <a role="button" style="cursor: pointer; color: white;" class="main_btn" main_target="9">
                 <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >Select Lens(with prescription)  
                 </div>
            </a> -->
            
            
            <!-- <a role="button" style="cursor: pointer; color: white;" onclick="refreshPage()" id="cancel">
                 <div class="btn" style="background-color: #800000 !important; margin:5px; color: white;" >Cancel 
                 </div>
            </a> -->
            
      <!--  <div class="btn" style="background-color: #800000 !important; margin:5px;" >-->
		    <!--<a style="cursor: pointer; color: white;" onclick="refreshPage()" id="cancel">Cancel</a>-->
      <!--  </div>-->
	</div> 
   
<section class="pres_main_menu">
<div id="div9" style="display: none;" class="main_target">
     
         <h6 class=" text-red-700 " style="padding: 3px; border-radius: 5px;   "><b>Prescription Notice:</b> <span style="color: gray !important;"> Please fill in the prescription information completely. If the prescription is blank, please select the data as (-) this symbol in the list.</span></h6>

     Select Lens(with prescription)
    <div class="menu_vision">
  <!--      <div class="btn" style="background-color: #373737 !important; margin:5px;" >-->
	 <!--        <a style="cursor: pointer; color: white;" onclick="single_vision()" class="Single" target="1">Single Vision</a>-->
		<!--</div>-->
        <button  onclick="single_vision()"  target="1" type="button" class="Single btn new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">Single Vision</button>
        <button onclick="progressive_vision()" target="2" type="button" class="Single btn new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">Progressive Vision </button>

		 <!-- <a role="button" style="cursor: pointer; color: white;" onclick="single_vision()" class="Single" target="1">
            <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >Single Vision 
            </div>
        </a>
        <a role="button" style="cursor: pointer; color: white;" onclick="progressive_vision()" class="Single" target="2">
            <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >Progressive Vision 
            </div>
        </a> -->
        <!--<div class="btn" style="background-color: #373737 !important; margin:5px;" >-->
        <!--    <a style="cursor: pointer; color: white;" onclick="progressive_vision()" class="Single" target="2">Progressive / Bifocal Vision</a>-->
        <!--</div>-->
        
         <a role="button" style="cursor: pointer; color: white;" onclick="refreshPage2()" id="hideall">
            <div class="btn" style="background-color: #800000 !important; margin:5px; color: white;" >Cancel 
            </div>
        </a>

        <!--<div class="btn" style="background-color: #800000 !important;" >-->
	       <!-- <a style="cursor: pointer; color: white;" onclick="pres_reset()" id="hideall">Cancel</a>-->
        <!--</div>-->
        
    </div>
     <div class="menu_vision">
           <div class="table table-borderless">
         
                       <div class="mb-5">
                                  <label for="Image" class="form-label">Prescription Photo Upload:</label>
                                  <label for="Image" class="form-label" style="color: red;">(Note:) Prescription must be uploaded.</label>
                                  <input class="form-control" type="file" id="formFile" name="prescr_photo">
                                  <button type="button" id="scan-prescription-btn" class="new_btn focus:outline-none text-white bg-yellow-300 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 me-2 mb-2" style="margin-top:8px;">Scan &amp; Auto-fill from photo</button>
                                  <div id="scan-prescription-status" style="margin-top:6px; font-size:14px;"></div>
                             </div>
                            
                 
                   
<!-- //////////// -->
				<div><input type="hidden" id="timestamp" name="upload_time" value="" onfocus="clearIntervalInstance()"
								ondblclick="runIntervalInstance()">
                </div>                          
                  </div>
           
    </div>
	    <section class="target_box" >
	   <div id="div1" style="display: none;" class="target">
	      Single Vision
               <table class="table table-bordered">
				            <thead>
						        <th>Eye</th>
						        <br>
						        <th>Sph</th>
						        <th>Cyl</th>
						        <th>Axis</th>
				        	</thead>
				            <tr>
                                <td>OD (Right)</td>
					        	<td><select id="ssph-right" class="form-select" name="ssph-right"  style="width: 100%;">
						    	    <option value="" selected>-</option>
                                        <option value="- Right SPH +6.00,">+6.00</option>
                                        <option value="- Right SPH +5.75,">+5.75</option>
                                        <option value="- Right SPH +5.50,">+5.50</option>
                                        <option value="- Right SPH +5.25,">+5.25</option>
                                        <option value="- Right SPH +5.00,">+5.00</option>
                                        <option value="- Right SPH +4.75,">+4.75</option>
                                        <option value="- Right SPH +4.50,">+4.50</option>
                                        <option value="- Right SPH +4.25,">+4.25</option>
                                        <option value="- Right SPH +4.00,">+4.00</option>
                                        <option value="- Right SPH +3.75,">+3.75</option>
                                        <option value="- Right SPH +3.50,">+3.50</option>
                                        <option value="- Right SPH +3.25,">+3.25</option>
                                        <option value="- Right SPH +3.00,">+3.00</option>
                                        <option value="- Right SPH +2.75,">+2.75</option>
                                        <option value="- Right SPH +2.50,">+2.50</option>
                                        <option value="- Right SPH +2.25,">+2.25</option>
                                        <option value="- Right SPH +2.00,">+2.00</option>
                                        <option value="- Right SPH +1.75,">+1.75</option>
                                        <option value="- Right SPH +1.50,">+1.50</option>
                                        <option value="- Right SPH +1.25,">+1.25</option>
                                        <option value="- Right SPH +1.00,">+1.00</option>
                                        <option value="- Right SPH +0.75,">+0.75</option>
                                        <option value="- Right SPH +0.50,">+0.50</option>
                                        <option value="- Right SPH +0.25,">+0.25</option>
                                        <option value="- Right SPH Plano (0.00),">Plano (0.00)</option>
                                        <option value="- Right SPH -0.25,">-0.25</option>
                                        <option value="- Right SPH -0.50,">-0.50</option>
                                        <option value="- Right SPH -0.75,">-0.75</option>
                                        <option value="- Right SPH -1.00,">-1.00</option>
                                        <option value="- Right SPH -1.25,">-1.25</option>
                                        <option value="- Right SPH -1.50,">-1.50</option>
                                        <option value="- Right SPH -1.75,">-1.75</option>
                                        <option value="- Right SPH -2.00,">-2.00</option>
                                        <option value="- Right SPH -2.25,">-2.25</option>
                                        <option value="- Right SPH -2.50,">-2.50</option>
                                        <option value="- Right SPH -2.75,">-2.75</option>
                                        <option value="- Right SPH -3.00,">-3.00</option>
                                        <option value="- Right SPH -3.25,">-3.25</option>
                                        <option value="- Right SPH -3.50,">-3.50</option>
                                        <option value="- Right SPH -3.75,">-3.75</option>
                                        <option value="- Right SPH -4.00,">-4.00</option>
                                        <option value="- Right SPH -4.25,">-4.25</option>
                                        <option value="- Right SPH -4.50,">-4.50</option>
                                        <option value="- Right SPH -4.75,">-4.75</option>
                                        <option value="- Right SPH -5.00,">-5.00</option>
                                        <option value="- Right SPH -5.25,">-5.25</option>
                                        <option value="- Right SPH -5.50,">-5.50</option>
                                        <option value="- Right SPH -5.75,">-5.75</option>
                                        <option value="- Right SPH -6.00,">-6.00</option>

						        </td>
					        	<td><select id="scyl-right" class="form-select" name="scyl-right"  style="width: 100%;">
                                    <option value="" selected>-</option>
                                    <option value="- Right CYL +3.00 ">+3.00</option>
                                    <option value="- Right CYL +2.75 ">+2.75</option>
                                    <option value="- Right CYL +2.50 ">+2.50</option>
                                    <option value="- Right CYL +2.25 ">+2.25</option>
                                    <option value="- Right CYL +2.00 ">+2.00</option>
                                    <option value="- Right CYL +1.75 ">+1.75</option>
                                    <option value="- Right CYL +1.50 ">+1.50</option>
                                    <option value="- Right CYL +1.25 ">+1.25</option>
                                    <option value="- Right CYL +1.00 ">+1.00</option>
                                    <option value="- Right CYL +0.75 ">+0.75</option>
                                    <option value="- Right CYL +0.50 ">+0.50</option>
                                    <option value="- Right CYL +0.25 ">+0.25</option>
                                        
                                    <option value="- Right Cyl Plan" >Plano (0.00)</option>

                                    <option value="- Right CYL -0.25, ">-0.25</option>
                                    <option value="- Right CYL -0.50, ">-0.50</option>
                                    <option value="- Right CYL -0.75, ">-0.75</option>
                                    <option value="- Right CYL -1.00, ">-1.00</option>
                                    <option value="- Right CYL -1.25, ">-1.25</option>
                                    <option value="- Right CYL -1.50, ">-1.50</option>
                                    <option value="- Right CYL -1.75, ">-1.75</option>
                                    <option value="- Right CYL -2.00, ">-2.00</option>
                                    <option value="- Right CYL -2.25, ">-2.25</option>
                                    <option value="- Right CYL -2.50, ">-2.50</option>
                                    <option value="- Right CYL -2.75, ">-2.75</option>
                                    <option value="- Right CYL -3.00, ">-3.00</option>
						        </td>
					        	<td><select id="saxis-right" class="form-select" name="saxis-right"  style="width: 100%;">
						        	<option value="" selected>-</option>
                                 
						        	<option value="- Right Axis 0 ">0</option>
						        	<option value="- Right Axis 1 ">1</option>
						        	<option value="- Right Axis 2 ">2</option>
						        	<option value="- Right Axis 3 ">3</option>
						        	<option value="- Right Axis 4 ">4</option>
						        	<option value="- Right Axis 5 ">5</option>
						        	<option value="- Right Axis 6 ">6</option>
						        	<option value="- Right Axis 7 ">7</option>
						        	<option value="- Right Axis 8 ">8</option>
						        	<option value="- Right Axis 9 ">9</option>
						        	<option value="- Right Axis 10 ">10</option>
						        	<option value="- Right Axis 11 ">11</option>
						        	<option value="- Right Axis 12 ">12</option>
						        	<option value="- Right Axis 13 ">13</option>
						        	<option value="- Right Axis 14 ">14</option>
						        	<option value="- Right Axis 15 ">15</option>
						        	<option value="- Right Axis 16 ">16</option>
						        	<option value="- Right Axis 17 ">17</option>
						        	<option value="- Right Axis 18 ">18</option>
						        	<option value="- Right Axis 19 ">19</option>
						        	<option value="- Right Axis 20 ">20</option>
						        	<option value="- Right Axis 21 ">21</option>
						        	<option value="- Right Axis 22 ">22</option>
						        	<option value="- Right Axis 23 ">23</option>
						        	<option value="- Right Axis 24 ">24</option>
						        	<option value="- Right Axis 25 ">25</option>
						        	<option value="- Right Axis 26 ">26</option>
						        	<option value="- Right Axis 27 ">27</option>
						        	<option value="- Right Axis 28 ">28</option>
						        	<option value="- Right Axis 29 ">29</option>
						        	<option value="- Right Axis 30 ">30</option>
						        	<option value="- Right Axis 31 ">31</option>
						        	<option value="- Right Axis 32 ">32</option>
						        	<option value="- Right Axis 33 ">33</option>
						        	<option value="- Right Axis 34 ">34</option>
						        	<option value="- Right Axis 35 ">35</option>
						        	<option value="- Right Axis 36 ">36</option>
						        	<option value="- Right Axis 37 ">37</option>
						        	<option value="- Right Axis 38 ">38</option>
						        	<option value="- Right Axis 39 ">39</option>
						        	<option value="- Right Axis 40 ">40</option>
						        	<option value="- Right Axis 41 ">41</option>
						        	<option value="- Right Axis 42 ">42</option>
						        	<option value="- Right Axis 43 ">43</option>
						        	<option value="- Right Axis 44 ">44</option>
						        	<option value="- Right Axis 45 ">45</option>
						        	<option value="- Right Axis 46 ">46</option>
						        	<option value="- Right Axis 47 ">47</option>
						        	<option value="- Right Axis 48 ">48</option>
						        	<option value="- Right Axis 49 ">49</option>
						        	<option value="- Right Axis 50 ">50</option>
						        	<option value="- Right Axis 51 ">51</option>
						        	<option value="- Right Axis 52 ">52</option>
						        	<option value="- Right Axis 53 ">53</option>
						        	<option value="- Right Axis 54 ">54</option>
						        	<option value="- Right Axis 55 ">55</option>
						        	<option value="- Right Axis 56 ">56</option>
						        	<option value="- Right Axis 57 ">57</option>
						        	<option value="- Right Axis 58 ">58</option>
						        	<option value="- Right Axis 59 ">59</option>
						        	<option value="- Right Axis 60 ">60</option>
						        	<option value="- Right Axis 61 ">61</option>
						        	<option value="- Right Axis 62 ">62</option>
						        	<option value="- Right Axis 63 ">63</option>
						        	<option value="- Right Axis 64 ">64</option>
						        	<option value="- Right Axis 65 ">65</option>
						        	<option value="- Right Axis 66 ">66</option>
						        	<option value="- Right Axis 67 ">67</option>
						        	<option value="- Right Axis 68 ">68</option>
						        	<option value="- Right Axis 69 ">69</option>
						        	<option value="- Right Axis 70 ">70</option>
						        	<option value="- Right Axis 71 ">71</option>
						        	<option value="- Right Axis 72 ">72</option>
						        	<option value="- Right Axis 73 ">73</option>
						        	<option value="- Right Axis 74 ">74</option>
						        	<option value="- Right Axis 75 ">75</option>
						        	<option value="- Right Axis 76 ">76</option>
						        	<option value="- Right Axis 77 ">77</option>
						        	<option value="- Right Axis 78 ">78</option>
						        	<option value="- Right Axis 79 ">79</option>
						        	<option value="- Right Axis 80 ">80</option>
						        	<option value="- Right Axis 81 ">81</option>
						        	<option value="- Right Axis 82 ">82</option>
						        	<option value="- Right Axis 83 ">83</option>
						        	<option value="- Right Axis 84 ">84</option>
						        	<option value="- Right Axis 85 ">85</option>
						        	<option value="- Right Axis 86 ">86</option>
						        	<option value="- Right Axis 87 ">87</option>
						        	<option value="- Right Axis 88 ">88</option>
						        	<option value="- Right Axis 89 ">89</option>
						        	<option value="- Right Axis 90 ">90</option>
						        	<option value="- Right Axis 91 ">91</option>
						        	<option value="- Right Axis 92 ">92</option>
						        	<option value="- Right Axis 93 ">93</option>
						        	<option value="- Right Axis 94 ">94</option>
						        	<option value="- Right Axis 95 ">95</option>
						        	<option value="- Right Axis 96 ">96</option>
						        	<option value="- Right Axis 97 ">97</option>
						        	<option value="- Right Axis 98 ">98</option>
						        	<option value="- Right Axis 99 ">99</option>
						        	<option value="- Right Axis 100 ">100</option>
						        	<option value="- Right Axis 101 ">101</option>
						        	<option value="- Right Axis 102 ">102</option>
						        	<option value="- Right Axis 103 ">103</option>
						        	<option value="- Right Axis 104 ">104</option>
						        	<option value="- Right Axis 105 ">105</option>
						        	<option value="- Right Axis 106 ">106</option>
						        	<option value="- Right Axis 107 ">107</option>
						        	<option value="- Right Axis 108 ">108</option>
						        	<option value="- Right Axis 109 ">109</option>
						        	<option value="- Right Axis 110 ">110</option>
						        	<option value="- Right Axis 111 ">111</option>
						        	<option value="- Right Axis 112 ">112</option>
						        	<option value="- Right Axis 113 ">113</option>
						        	<option value="- Right Axis 114 ">114</option>
						        	<option value="- Right Axis 115 ">115</option>
						        	<option value="- Right Axis 116 ">116</option>
						        	<option value="- Right Axis 117 ">117</option>
						        	<option value="- Right Axis 118 ">118</option>
						        	<option value="- Right Axis 119 ">119</option>
						        	<option value="- Right Axis 120 ">120</option>
						        	<option value="- Right Axis 121 ">121</option>
						        	<option value="- Right Axis 122 ">122</option>
						        	<option value="- Right Axis 123 ">123</option>
						        	<option value="- Right Axis 124 ">124</option>
						        	<option value="- Right Axis 125 ">125</option>
						        	<option value="- Right Axis 126 ">126</option>
						        	<option value="- Right Axis 127 ">127</option>
						        	<option value="- Right Axis 128 ">128</option>
						        	<option value="- Right Axis 129 ">129</option>
						        	<option value="- Right Axis 130 ">130</option>
						        	<option value="- Right Axis 131 ">131</option>
						        	<option value="- Right Axis 132 ">132</option>
						        	<option value="- Right Axis 133 ">133</option>
						        	<option value="- Right Axis 134 ">134</option>
						        	<option value="- Right Axis 135 ">135</option>
						        	<option value="- Right Axis 136 ">136</option>
						        	<option value="- Right Axis 137 ">137</option>
						        	<option value="- Right Axis 138 ">138</option>
						        	<option value="- Right Axis 139 ">139</option>
						        	<option value="- Right Axis 140 ">140</option>
						        	<option value="- Right Axis 141 ">141</option>
						        	<option value="- Right Axis 142 ">142</option>
						        	<option value="- Right Axis 143 ">143</option>
						        	<option value="- Right Axis 144 ">144</option>
						        	<option value="- Right Axis 145 ">145</option>
						        	<option value="- Right Axis 146 ">146</option>
						        	<option value="- Right Axis 147 ">147</option>
						        	<option value="- Right Axis 148 ">148</option>
						        	<option value="- Right Axis 149 ">149</option>
						        	<option value="- Right Axis 150 ">150</option>
						        	<option value="- Right Axis 151 ">151</option>
						        	<option value="- Right Axis 152 ">152</option>
						        	<option value="- Right Axis 153 ">153</option>
						        	<option value="- Right Axis 154 ">154</option>
						        	<option value="- Right Axis 155 ">155</option>
						        	<option value="- Right Axis 156 ">156</option>
						        	<option value="- Right Axis 157 ">157</option>
						        	<option value="- Right Axis 158 ">158</option>
						        	<option value="- Right Axis 159 ">159</option>
						        	<option value="- Right Axis 160 ">160</option>
						        	<option value="- Right Axis 161 ">161</option>
						        	<option value="- Right Axis 162 ">162</option>
						        	<option value="- Right Axis 163 ">163</option>
						        	<option value="- Right Axis 164 ">164</option>
						        	<option value="- Right Axis 165 ">165</option>
						        	<option value="- Right Axis 166 ">166</option>
						        	<option value="- Right Axis 167 ">167</option>
						        	<option value="- Right Axis 168 ">168</option>
						        	<option value="- Right Axis 169 ">169</option>
						        	<option value="- Right Axis 170 ">170</option>
						        	<option value="- Right Axis 171 ">171</option>
						        	<option value="- Right Axis 172 ">172</option>
						        	<option value="- Right Axis 173 ">173</option>
						        	<option value="- Right Axis 174 ">174</option>
						        	<option value="- Right Axis 175 ">175</option>
						        	<option value="- Right Axis 176 ">176</option>
						        	<option value="- Right Axis 177 ">177</option>
						        	<option value="- Right Axis 178 ">178</option>
						        	<option value="- Right Axis 179 ">179</option>
						        	<option value="- Right Axis 180 ">180</option>
						        	
						        </td>
				        	</tr>
				        	<tr>
				        		<td>OS (Left)</td>
				        		<td><select id="ssph-left" class="form-select" name="ssph-left"  style="width: 100%;">
						    		<option value="" selected>-</option>
                                        <option value="- Left SPH +6.00,">+6.00</option>
                                        <option value="- Left SPH +5.75,">+5.75</option>
                                        <option value="- Left SPH +5.50,">+5.50</option>
                                        <option value="- Left SPH +5.25,">+5.25</option>
                                        <option value="- Left SPH +5.00,">+5.00</option>
                                        <option value="- Left SPH +4.75,">+4.75</option>
                                        <option value="- Left SPH +4.50,">+4.50</option>
                                        <option value="- Left SPH +4.25,">+4.25</option>
                                        <option value="- Left SPH +4.00,">+4.00</option>
                                        <option value="- Left SPH +3.75,">+3.75</option>
                                        <option value="- Left SPH +3.50,">+3.50</option>
                                        <option value="- Left SPH +3.25,">+3.25</option>
                                        <option value="- Left SPH +3.00,">+3.00</option>
                                        <option value="- Left SPH +2.75,">+2.75</option>
                                        <option value="- Left SPH +2.50,">+2.50</option>
                                        <option value="- Left SPH +2.25,">+2.25</option>
                                        <option value="- Left SPH +2.00,">+2.00</option>
                                        <option value="- Left SPH +1.75,">+1.75</option>
                                        <option value="- Left SPH +1.50,">+1.50</option>
                                        <option value="- Left SPH +1.25,">+1.25</option>
                                        <option value="- Left SPH +1.00,">+1.00</option>
                                        <option value="- Left SPH +0.75,">+0.75</option>
                                        <option value="- Left SPH +0.50,">+0.50</option>
                                        <option value="- Left SPH +0.25,">+0.25</option>
                                        <option value="- Left SPH Plano (0.00),">Plano (0.00)</option>
                                        <option value="- Left SPH -0.25,">-0.25</option>
                                        <option value="- Left SPH -0.50,">-0.50</option>
                                        <option value="- Left SPH -0.75,">-0.75</option>
                                        <option value="- Left SPH -1.00,">-1.00</option>
                                        <option value="- Left SPH -1.25,">-1.25</option>
                                        <option value="- Left SPH -1.50,">-1.50</option>
                                        <option value="- Left SPH -1.75,">-1.75</option>
                                        <option value="- Left SPH -2.00,">-2.00</option>
                                        <option value="- Left SPH -2.25,">-2.25</option>
                                        <option value="- Left SPH -2.50,">-2.50</option>
                                        <option value="- Left SPH -2.75,">-2.75</option>
                                        <option value="- Left SPH -3.00,">-3.00</option>
                                        <option value="- Left SPH -3.25,">-3.25</option>
                                        <option value="- Left SPH -3.50,">-3.50</option>
                                        <option value="- Left SPH -3.75,">-3.75</option>
                                        <option value="- Left SPH -4.00,">-4.00</option>
                                        <option value="- Left SPH -4.25,">-4.25</option>
                                        <option value="- Left SPH -4.50,">-4.50</option>
                                        <option value="- Left SPH -4.75,">-4.75</option>
                                        <option value="- Left SPH -5.00,">-5.00</option>
                                        <option value="- Left SPH -5.25,">-5.25</option>
                                        <option value="- Left SPH -5.50,">-5.50</option>
                                        <option value="- Left SPH -5.75,">-5.75</option>
                                        <option value="- Left SPH -6.00,">-6.00</option>
				        		</td>
                                <td><select id="scyl-left" class="form-select" name="scyl-left"   style="width: 100%;">
                                    <option value="" selected>-</option>
                                    <option value="- Left CYL +3.00 ">+3.00</option>
                                    <option value="- Left CYL +2.75 ">+2.75</option>
                                    <option value="- Left CYL +2.50 ">+2.50</option>
                                    <option value="- Left CYL +2.25 ">+2.25</option>
                                    <option value="- Left CYL +2.00 ">+2.00</option>
                                    <option value="- Left CYL +1.75 ">+1.75</option>
                                    <option value="- Left CYL +1.50 ">+1.50</option>
                                    <option value="- Left CYL +1.25 ">+1.25</option>
                                    <option value="- Left CYL +1.00 ">+1.00</option>
                                    <option value="- Left CYL +0.75 ">+0.75</option>
                                    <option value="- Left CYL +0.50 ">+0.50</option>
                                    <option value="- Left CYL +0.25 ">+0.25</option>
                                        
                                    <option value="- Left Cyl Plan" >Plano (0.00)</option>

                                    <option value="- Left CYL -0.25, ">-0.25</option>
                                    <option value="- Left CYL -0.50, ">-0.50</option>
                                    <option value="- Left CYL -0.75, ">-0.75</option>
                                    <option value="- Left CYL -1.00, ">-1.00</option>
                                    <option value="- Left CYL -1.25, ">-1.25</option>
                                    <option value="- Left CYL -1.50, ">-1.50</option>
                                    <option value="- Left CYL -1.75, ">-1.75</option>
                                    <option value="- Left CYL -2.00, ">-2.00</option>
                                    <option value="- Left CYL -2.25, ">-2.25</option>
                                    <option value="- Left CYL -2.50, ">-2.50</option>
                                    <option value="- Left CYL -2.75, ">-2.75</option>
                                    <option value="- Left CYL -3.00, ">-3.00</option>
                                </td>
                                <td><select id="saxis-left"  class="form-select" name="saxis-left" style="width: 100%;">
                                        <option value="" selected>-</option>
                                      
                                        <option value="- Left Axis 0 ">0</option>
                                        <option value="- Left Axis 1 ">1</option>
						        	<option value="- Left Axis 2 ">2</option>
						        	<option value="- Left Axis 3 ">3</option>
						        	<option value="- Left Axis 4 ">4</option>
						        	<option value="- Left Axis 5 ">5</option>
						        	<option value="- Left Axis 6 ">6</option>
						        	<option value="- Left Axis 7 ">7</option>
						        	<option value="- Left Axis 8 ">8</option>
						        	<option value="- Left Axis 9 ">9</option>
						        	<option value="- Left Axis 10 ">10</option>
						        	<option value="- Left Axis 11 ">11</option>
						        	<option value="- Left Axis 12 ">12</option>
						        	<option value="- Left Axis 13 ">13</option>
						        	<option value="- Left Axis 14 ">14</option>
						        	<option value="- Left Axis 15 ">15</option>
						        	<option value="- Left Axis 16 ">16</option>
						        	<option value="- Left Axis 17 ">17</option>
						        	<option value="- Left Axis 18 ">18</option>
						        	<option value="- Left Axis 19 ">19</option>
						        	<option value="- Left Axis 20 ">20</option>
						        	<option value="- Left Axis 21 ">21</option>
						        	<option value="- Left Axis 22 ">22</option>
						        	<option value="- Left Axis 23 ">23</option>
						        	<option value="- Left Axis 24 ">24</option>
						        	<option value="- Left Axis 25 ">25</option>
						        	<option value="- Left Axis 26 ">26</option>
						        	<option value="- Left Axis 27 ">27</option>
						        	<option value="- Left Axis 28 ">28</option>
						        	<option value="- Left Axis 29 ">29</option>
						        	<option value="- Left Axis 30 ">30</option>
						        	<option value="- Left Axis 31 ">31</option>
						        	<option value="- Left Axis 32 ">32</option>
						        	<option value="- Left Axis 33 ">33</option>
						        	<option value="- Left Axis 34 ">34</option>
						        	<option value="- Left Axis 35 ">35</option>
						        	<option value="- Left Axis 36 ">36</option>
						        	<option value="- Left Axis 37 ">37</option>
						        	<option value="- Left Axis 38 ">38</option>
						        	<option value="- Left Axis 39 ">39</option>
						        	<option value="- Left Axis 40 ">40</option>
						        	<option value="- Left Axis 41 ">41</option>
						        	<option value="- Left Axis 42 ">42</option>
						        	<option value="- Left Axis 43 ">43</option>
						        	<option value="- Left Axis 44 ">44</option>
						        	<option value="- Left Axis 45 ">45</option>
						        	<option value="- Left Axis 46 ">46</option>
						        	<option value="- Left Axis 47 ">47</option>
						        	<option value="- Left Axis 48 ">48</option>
						        	<option value="- Left Axis 49 ">49</option>
						        	<option value="- Left Axis 50 ">50</option>
						        	<option value="- Left Axis 51 ">51</option>
						        	<option value="- Left Axis 52 ">52</option>
						        	<option value="- Left Axis 53 ">53</option>
						        	<option value="- Left Axis 54 ">54</option>
						        	<option value="- Left Axis 55 ">55</option>
						        	<option value="- Left Axis 56 ">56</option>
						        	<option value="- Left Axis 57 ">57</option>
						        	<option value="- Left Axis 58 ">58</option>
						        	<option value="- Left Axis 59 ">59</option>
						        	<option value="- Left Axis 60 ">60</option>
						        	<option value="- Left Axis 61 ">61</option>
						        	<option value="- Left Axis 62 ">62</option>
						        	<option value="- Left Axis 63 ">63</option>
						        	<option value="- Left Axis 64 ">64</option>
						        	<option value="- Left Axis 65 ">65</option>
						        	<option value="- Left Axis 66 ">66</option>
						        	<option value="- Left Axis 67 ">67</option>
						        	<option value="- Left Axis 68 ">68</option>
						        	<option value="- Left Axis 69 ">69</option>
						        	<option value="- Left Axis 70 ">70</option>
						        	<option value="- Left Axis 71 ">71</option>
						        	<option value="- Left Axis 72 ">72</option>
						        	<option value="- Left Axis 73 ">73</option>
						        	<option value="- Left Axis 74 ">74</option>
						        	<option value="- Left Axis 75 ">75</option>
						        	<option value="- Left Axis 76 ">76</option>
						        	<option value="- Left Axis 77 ">77</option>
						        	<option value="- Left Axis 78 ">78</option>
						        	<option value="- Left Axis 79 ">79</option>
						        	<option value="- Left Axis 80 ">80</option>
						        	<option value="- Left Axis 81 ">81</option>
						        	<option value="- Left Axis 82 ">82</option>
						        	<option value="- Left Axis 83 ">83</option>
						        	<option value="- Left Axis 84 ">84</option>
						        	<option value="- Left Axis 85 ">85</option>
						        	<option value="- Left Axis 86 ">86</option>
						        	<option value="- Left Axis 87 ">87</option>
						        	<option value="- Left Axis 88 ">88</option>
						        	<option value="- Left Axis 89 ">89</option>
						        	<option value="- Left Axis 90 ">90</option>
						        	<option value="- Left Axis 91 ">91</option>
						        	<option value="- Left Axis 92 ">92</option>
						        	<option value="- Left Axis 93 ">93</option>
						        	<option value="- Left Axis 94 ">94</option>
						        	<option value="- Left Axis 95 ">95</option>
						        	<option value="- Left Axis 96 ">96</option>
						        	<option value="- Left Axis 97 ">97</option>
						        	<option value="- Left Axis 98 ">98</option>
						        	<option value="- Left Axis 99 ">99</option>
						        	<option value="- Left Axis 100 ">100</option>
						        	<option value="- Left Axis 101 ">101</option>
						        	<option value="- Left Axis 102 ">102</option>
						        	<option value="- Left Axis 103 ">103</option>
						        	<option value="- Left Axis 104 ">104</option>
						        	<option value="- Left Axis 105 ">105</option>
						        	<option value="- Left Axis 106 ">106</option>
						        	<option value="- Left Axis 107 ">107</option>
						        	<option value="- Left Axis 108 ">108</option>
						        	<option value="- Left Axis 109 ">109</option>
						        	<option value="- Left Axis 110 ">110</option>
						        	<option value="- Left Axis 111 ">111</option>
						        	<option value="- Left Axis 112 ">112</option>
						        	<option value="- Left Axis 113 ">113</option>
						        	<option value="- Left Axis 114 ">114</option>
						        	<option value="- Left Axis 115 ">115</option>
						        	<option value="- Left Axis 116 ">116</option>
						        	<option value="- Left Axis 117 ">117</option>
						        	<option value="- Left Axis 118 ">118</option>
						        	<option value="- Left Axis 119 ">119</option>
						        	<option value="- Left Axis 120 ">120</option>
						        	<option value="- Left Axis 121 ">121</option>
						        	<option value="- Left Axis 122 ">122</option>
						        	<option value="- Left Axis 123 ">123</option>
						        	<option value="- Left Axis 124 ">124</option>
						        	<option value="- Left Axis 125 ">125</option>
						        	<option value="- Left Axis 126 ">126</option>
						        	<option value="- Left Axis 127 ">127</option>
						        	<option value="- Left Axis 128 ">128</option>
						        	<option value="- Left Axis 129 ">129</option>
						        	<option value="- Left Axis 130 ">130</option>
						        	<option value="- Left Axis 131 ">131</option>
						        	<option value="- Left Axis 132 ">132</option>
						        	<option value="- Left Axis 133 ">133</option>
						        	<option value="- Left Axis 134 ">134</option>
						        	<option value="- Left Axis 135 ">135</option>
						        	<option value="- Left Axis 136 ">136</option>
						        	<option value="- Left Axis 137 ">137</option>
						        	<option value="- Left Axis 138 ">138</option>
						        	<option value="- Left Axis 139 ">139</option>
						        	<option value="- Left Axis 140 ">140</option>
						        	<option value="- Left Axis 141 ">141</option>
						        	<option value="- Left Axis 142 ">142</option>
						        	<option value="- Left Axis 143 ">143</option>
						        	<option value="- Left Axis 144 ">144</option>
						        	<option value="- Left Axis 145 ">145</option>
						        	<option value="- Left Axis 146 ">146</option>
						        	<option value="- Left Axis 147 ">147</option>
						        	<option value="- Left Axis 148 ">148</option>
						        	<option value="- Left Axis 149 ">149</option>
						        	<option value="- Left Axis 150 ">150</option>
						        	<option value="- Left Axis 151 ">151</option>
						        	<option value="- Left Axis 152 ">152</option>
						        	<option value="- Left Axis 153 ">153</option>
						        	<option value="- Left Axis 154 ">154</option>
						        	<option value="- Left Axis 155 ">155</option>
						        	<option value="- Left Axis 156 ">156</option>
						        	<option value="- Left Axis 157 ">157</option>
						        	<option value="- Left Axis 158 ">158</option>
						        	<option value="- Left Axis 159 ">159</option>
						        	<option value="- Left Axis 160 ">160</option>
						        	<option value="- Left Axis 161 ">161</option>
						        	<option value="- Left Axis 162 ">162</option>
						        	<option value="- Left Axis 163 ">163</option>
						        	<option value="- Left Axis 164 ">164</option>
						        	<option value="- Left Axis 165 ">165</option>
						        	<option value="- Left Axis 166 ">166</option>
						        	<option value="- Left Axis 167 ">167</option>
						        	<option value="- Left Axis 168 ">168</option>
						        	<option value="- Left Axis 169 ">169</option>
						        	<option value="- Left Axis 170 ">170</option>
						        	<option value="- Left Axis 171 ">171</option>
						        	<option value="- Left Axis 172 ">172</option>
						        	<option value="- Left Axis 173 ">173</option>
						        	<option value="- Left Axis 174 ">174</option>
						        	<option value="- Left Axis 175 ">175</option>
						        	<option value="- Left Axis 176 ">176</option>
						        	<option value="- Left Axis 177 ">177</option>
						        	<option value="- Left Axis 178 ">178</option>
						        	<option value="- Left Axis 179 ">179</option>
						        	<option value="- Left Axis 180 ">180</option>
                                </td>
				        	</tr>
			    </table>
			   
            
                <!-- single vision pd -->
					<div class="pd_menu_vision"><br>
						PD:
       <!--                  <div class="btn" style="background-color: #373737 !important;" >-->
       <!--                     <a style="cursor: pointer; color: white;" class="pd" pdtarget="3">&nbsp; One PD</a>-->
       <!--                  </div>-->
       <!--                  <div class="btn" style="background-color: #373737 !important;" >-->
							<!--<a style="cursor: pointer; color: white;" class="pd" pdtarget="4">&nbsp; Two PD</a>-->
       <!--                  </div>-->

                        <button pdtarget="3"  type="button" class="pd new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">One PD</button>

                        <button pdtarget="4"  type="button" class="pd new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">Two PD</button>
                         
                          <!-- <a role="button" style="cursor: pointer; color: white;" class="pd" pdtarget="3">
                               <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >&nbsp; One PD</div>
                          </a> -->
                          <!-- <a role="button" style="cursor: pointer; color: white;" class="pd" pdtarget="4">
                               <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >&nbsp; Two PD</div>
                          </a> -->
                                
       <!--                  <div class="btn" style="background-color: #A52A2A !important;" >-->
							<!--<a style="cursor: pointer; color: white;" onclick="s_pd_reset()"id="hideallpd">&nbsp; Cancel</a>-->
       <!--                   </div>-->
                        <button onclick="s_pd_reset()"id="hideallpd" type="button" class=" cancel_btn text-gray hover:text-white border border-blue-400 hover:bg-red-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">Cancel</button>

                          <!-- <a role="button" style="cursor: pointer; color: white;" onclick="s_pd_reset()"id="hideallpd">
                             <div class="btn" style="background-color: #800000 !important; margin:5px; color: white;" >&nbsp; Cancel</div>
                          </a> -->
					</div>
					<section class="pd_target_box">
						<div id="div3" style="display: none;" class="pdtarget">
								<table class="table table-bordered">
                                    <thead><br>	
										</thead>
                                        <tr>
                                        <td>PD Numbers:</td><td>
							<select id="s_one_pd" name="s_one_pd" class="form-select" style="width: 120px;">
											<option value="" selected>Choose One</option>
									
											<option value="- One Pd: 40">40</option>
											<option value="- One Pd: 41">41</option>
											<option value="- One Pd: 42">42</option>
											<option value="- One Pd: 43">43</option>
											<option value="- One Pd: 44">44</option>
											<option value="- One Pd: 45">45</option>
											<option value="- One Pd: 46">46</option>
											<option value="- One Pd: 47">47</option>
											<option value="- One Pd: 48">48</option>
											<option value="- One Pd: 49">49</option>
											<option value="- One Pd: 50">50</option>
											<option value="- One Pd: 51">51</option>
											<option value="- One Pd: 52">52</option>
											<option value="- One Pd: 53">53</option>
											<option value="- One Pd: 54">54</option>
											<option value="- One Pd: 55">55</option>
											<option value="- One Pd: 56">56</option>
											<option value="- One Pd: 57">57</option>
											<option value="- One Pd: 58">58</option>
											<option value="- One Pd: 59">59</option>
											<option value="- One Pd: 60">60</option>
											<option value="- One Pd: 61">61</option>
											<option value="- One Pd: 62">62</option>
											<option value="- One Pd: 63">63</option>
											<option value="- One Pd: 64">64</option>
											<option value="- One Pd: 65">65</option>
											<option value="- One Pd: 66">66</option>
											<option value="- One Pd: 67">67</option>
											<option value="- One Pd: 68">68</option>
											<option value="- One Pd: 69">69</option>
											<option value="- One Pd: 70">70</option>
											<option value="- One Pd: 71">71</option>
											<option value="- One Pd: 72">72</option>
											<option value="- One Pd: 73">73</option>
											<option value="- One Pd: 74">74</option>
											<option value="- One Pd: 75">75</option>
											<option value="- One Pd: 76">76</option>
											<option value="- One Pd: 77">77</option>
											<option value="- One Pd: 78">78</option>
											<option value="- One Pd: 79">79</option>
											<option value="- One Pd: 80">80</option>
							</select></td>
                                            </tr>
                                            </table>
						</div>
						<div id="div4" style="display: none;" class="pdtarget">PD Numbers:
										<table class="table table-bordered">
								<thead>
									<th></th>
									<br>
									<th>Right</th>
									<th>Left</th>
								</thead>
								<tr>
									<td>PD</td>
									<td><select id="s_two_pd_right" name="s_two_pd_right" class="form-select" style="width: 100%">
											<option value="" selected>-</option>
											<option value="- Two PD Right: 20.0">20.0</option>
											<option value="- Two PD Right: 20.5">20.5</option>
											<option value="- Two PD Right: 21.0">21.0</option>
											<option value="- Two PD Right: 21.5">21.5</option>
											<option value="- Two PD Right: 22.0">22.0</option>
											<option value="- Two PD Right: 22.5">22.5</option>
                                            <option value="- Two PD Right: 23.0">23.0</option>
											<option value="- Two PD Right: 23.5">23.5</option>
											<option value="- Two PD Right: 24.0">24.0</option>
											<option value="- Two PD Right: 24.5">24.5</option>
											<option value="- Two PD Right: 25.0">25.0</option>
											<option value="- Two PD Right: 25.5">25.5</option>
        
											<option value="- Two PD Right: 26.0">26.0</option>
											<option value="- Two PD Right: 26.5">26.5</option>
											<option value="- Two PD Right: 27.0">27.0</option>
											<option value="- Two PD Right: 27.5">27.5</option>
											<option value="- Two PD Right: 28.0">28.0</option>
											<option value="- Two PD Right: 28.5">28.5</option>
                                            <option value="- Two PD Right: 29.0">29.0</option>
											<option value="- Two PD Right: 29.5">29.5</option>
											<option value="- Two PD Right: 30.0">30.0</option>
											<option value="- Two PD Right: 30.5">30.5</option>
											<option value="- Two PD Right: 31.0">31.0</option>
											<option value="- Two PD Right: 31.5">31.5</option>
                                            <option value="- Two PD Right: 32.0">32.0</option>
											<option value="- Two PD Right: 32.5">32.5</option>
											<option value="- Two PD Right: 33.0">33.0</option>
											<option value="- Two PD Right: 33.5">33.5</option>
                                            <option value="- Two PD Right: 34.0">34.0</option>
											<option value="- Two PD Right: 34.5">34.5</option>
                                            <option value="- Two PD Right: 35.0">35.0</option>
											<option value="- Two PD Right: 35.5">35.5</option>
											<option value="- Two PD Right: 36.0">36.0</option>
											<option value="- Two PD Right: 36.5">36.5</option>
                                            <option value="- Two PD Right: 37.0">37.0</option>
                                            <option value="- Two PD Right: 37.5">37.5</option>
											<option value="- Two PD Right: 38.0">38.0</option>
											<option value="- Two PD Right: 38.5">38.5</option>
											<option value="- Two PD Right: 39.0">39.0</option>
                                            <option value="- Two PD Right: 39.5">39.5</option>
                                            <option value="- Two PD Right: 40.0">40.0</option>										
										
									</td>
									<td><select id="s_two_pd_left" name="s_two_pd_left" class="form-select" style="width: 100%;">
											<option value="" selected>-</option>
											<option value="- Two PD Left: 20.0">20.0</option>
											<option value="- Two PD Left: 20.5">20.5</option>
											<option value="- Two PD Left: 21.0">21.0</option>
											<option value="- Two PD Left: 21.5">21.5</option>
											<option value="- Two PD Left: 22.0">22.0</option>
											<option value="- Two PD Left: 22.5">22.5</option>
                                            <option value="- Two PD Left: 23.0">23.0</option>
											<option value="- Two PD Left: 23.5">23.5</option>
											<option value="- Two PD Left: 24.0">24.0</option>
											<option value="- Two PD Left: 24.5">24.5</option>
											<option value="- Two PD Left: 25.0">25.0</option>
											<option value="- Two PD Left: 25.5">25.5</option>
											<option value="- Two PD Left: 26.0">26.0</option>
											<option value="- Two PD Left: 26.5">26.5</option>
											<option value="- Two PD Left: 27.0">27.0</option>
											<option value="- Two PD Left: 27.5">27.5</option>
											<option value="- Two PD Left: 28.0">28.0</option>
											<option value="- Two PD Left: 28.5">28.5</option>
                                            <option value="- Two PD Left: 29.0">29.0</option>
											<option value="- Two PD Left: 29.5">29.5</option>
											<option value="- Two PD Left: 30.0">30.0</option>
											<option value="- Two PD Left: 30.5">30.5</option>
											<option value="- Two PD Left: 31.0">31.0</option>
											<option value="- Two PD Left: 31.5">31.5</option>
                                            <option value="- Two PD Left: 32.0">32.0</option>
											<option value="- Two PD Left: 32.5">32.5</option>
											<option value="- Two PD Left: 33.0">33.0</option>
											<option value="- Two PD Left: 33.5">33.5</option>
                                            <option value="- Two PD Left: 34.0">34.0</option>
											<option value="- Two PD Left: 34.5">34.5</option>
                                            <option value="- Two PD Left: 35.0">35.0</option>
											<option value="- Two PD Left: 35.5">35.5</option>
											<option value="- Two PD Left: 36.0">36.0</option>
											<option value="- Two PD Left: 36.5">36.5</option>
                                            <option value="- Two PD Left: 37.0">37.0</option>
                                            <option value="- Two PD Left: 37.5">37.5</option>
											<option value="- Two PD Left: 38.0">38.0</option>
											<option value="- Two PD Left: 38.5">38.5</option>
											<option value="- Two PD Left: 39.0">39.0</option>
                                            <option value="- Two PD Left: 39.5">39.5</option>
                                            <option value="- Two PD Left: 40.0">40.0</option>
										
									</td>

								</tr>

							</table>
							  PD type two:
                            	<table class="table table-bordered">
								<thead>
									<th></th>
									<br>
									<th>Far</th>
									<th>Near / Close</th>
								</thead>
								<tr>
									<td>PD</td>
									<td><select id="s_two_pd_right_type" name="s_two_pd_right_type" class="form-select" style="width: 100%">
											<option value="" selected>-</option>
											<option value="- Far: 40">40</option>
											<option value="- Far: 41">41</option>
											<option value="- Far: 42">42</option>
											<option value="- Far: 43">43</option>
											<option value="- Far: 44">44</option>
											<option value="- Far: 45">45</option>
											<option value="- Far: 46">46</option>
											<option value="- Far: 47">47</option>
											<option value="- Far: 48">48</option>
											<option value="- Far: 49">49</option>
											<option value="- Far: 50">50</option>
											<option value="- Far: 51">51</option>
											<option value="- Far: 52">52</option>
											<option value="- Far: 53">53</option>
											<option value="- Far: 54">54</option>
											<option value="- Far: 55">55</option>
											<option value="- Far: 56">56</option>
											<option value="- Far: 57">57</option>
											<option value="- Far: 58">58</option>
											<option value="- Far: 59">59</option>
											<option value="- Far: 60">60</option>
											<option value="- Far: 61">61</option>
											<option value="- Far: 62">62</option>
											<option value="- Far: 63">63</option>
											<option value="- Far: 64">64</option>
											<option value="- Far: 65">65</option>
											<option value="- Far: 66">66</option>
											<option value="- Far: 67">67</option>
											<option value="- Far: 68">68</option>
											<option value="- Far: 69">69</option>
											<option value="- Far: 70">70</option>
											<option value="- Far: 71">71</option>
											<option value="- Far: 72">72</option>
											<option value="- Far: 73">73</option>
											<option value="- Far: 74">74</option>
											<option value="- Far: 75">75</option>
											<option value="- Far: 76">76</option>
											<option value="- Far: 77">77</option>
											<option value="- Far: 78">78</option>
											<option value="- Far: 79">79</option>
											<option value="- Far: 80">80</option>										
										
									</td>
									<td><select id="s_two_pd_left_type" name="s_two_pd_left_type" class="form-select" style="width: 100%">
											<option value="" selected>-</option>
											<option value=" - Near/Close: 40">40</option>
											<option value=" - Near/Close: 41">41</option>
											<option value=" - Near/Close: 42">42</option>
											<option value=" - Near/Close: 43">43</option>
											<option value=" - Near/Close: 44">44</option>
											<option value=" - Near/Close: 45">45</option>
											<option value=" - Near/Close: 46">46</option>
											<option value=" - Near/Close: 47">47</option>
											<option value=" - Near/Close: 48">48</option>
											<option value=" - Near/Close: 49">49</option>
											<option value=" - Near/Close: 50">50</option>
											<option value=" - Near/Close: 51">51</option>
											<option value=" - Near/Close: 52">52</option>
											<option value=" - Near/Close: 53">53</option>
											<option value=" - Near/Close: 54">54</option>
											<option value=" - Near/Close: 55">55</option>
											<option value=" - Near/Close: 56">56</option>
											<option value=" - Near/Close: 57">57</option>
											<option value=" - Near/Close: 58">58</option>
											<option value=" - Near/Close: 59">59</option>
											<option value=" - Near/Close: 60">60</option>
											<option value=" - Near/Close: 61">61</option>
											<option value=" - Near/Close: 62">62</option>
											<option value=" - Near/Close: 63">63</option>
											<option value=" - Near/Close: 64">64</option>
											<option value=" - Near/Close: 65">65</option>
											<option value=" - Near/Close: 66">66</option>
											<option value=" - Near/Close: 67">67</option>
											<option value=" - Near/Close: 68">68</option>
											<option value=" - Near/Close: 69">69</option>
											<option value=" - Near/Close: 70">70</option>
											<option value=" - Near/Close: 71">71</option>
											<option value=" - Near/Close: 72">72</option>
											<option value=" - Near/Close: 73">73</option>
											<option value=" - Near/Close: 74">74</option>
											<option value=" - Near/Close: 75">75</option>
											<option value=" - Near/Close: 76">76</option>
											<option value=" - Near/Close: 77">77</option>
											<option value=" - Near/Close: 78">78</option>
											<option value=" - Near/Close: 79">79</option>
											<option value=" - Near/Close: 80">80</option>
										
									</td>

								</tr>

							</table>
						</div>
					</section>

                          <!-- single vision lens -->
                           <table class="table table-borderless">
										<thead>
											
											
											<th style ="font-size: 15px;"> LENS TYPE REMARKS:</th>
										
										</thead>
										<tr style ="font-size: 13px;">
											<td>  <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=" PHOTO SOLAR, " name="s_photosolar_check" id="1" >
                                                        <label class="form-check-label" for="1">
                                                            PHOTO SOLAR
                                                        </label>
                                                    </div>
                                             </td>
                                            
                                             <td> 
                                                   <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=" PHOTO CHROMIC, " name="s_photochromic_check" id="2">
                                                        <label class="form-check-label" for="2">
                                                            PHOTO CHROMIC
                                                        </label>
                                                    </div>
                                             </td>
										</tr>
                                        <tr style ="font-size: 13px;">
											<td> 
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=" WHITE, " name="s_white_check" id="3">
                                                        <label class="form-check-label" for="3">
                                                            WHITE
                                                        </label>
                                                    </div>
                                                    
                                             </td>
                                            
                                             <td> 
                                                  <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" PLASTIC LENS, " name="s_plascitlens_check" id="4">
                                                        <label class="form-check-label" for="4">
                                                            PLASTIC LENS
                                                        </label>
                                                    </div>
                                             </td>
										</tr>
                                        <tr style ="font-size: 13px;">
											<td> 
                                                <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" SUN SENSOR, " name="s_sunsensor_check" id="5">
                                                        <label class="form-check-label" for="5">
                                                            SUN SENSOR
                                                        </label>
                                                    </div> 
                                             </td>                                           
                                             <td> 
                                             <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" GLARE FREE, " name="s_glarefree_check" id="6">
                                                        <label class="form-check-label" for="6">
                                                            GLARE FREE
                                                        </label>
                                                    </div>     
                                           </td>
										</tr>
                                         <tr style ="font-size: 13px;">
											<td>
                                                <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" ANTI-GLARE, " name="s_antiglare_check" id="7">
                                                        <label class="form-check-label" for="7">
                                                            ANTI-GLARE
                                                        </label>
                                                    </div>  
                                            </td>
                                           
                                             <td> 
                                              <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" ARC / AR, " name="s_arc_check" id="8">
                                                        <label class="form-check-label" for="8">
                                                            ARC / AR
                                                        </label>
                                                    </div>     
                                             </td>
										</tr>
                                         <tr style ="font-size: 13px;">
											<td> 
                                             <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" HMC, " name="s_hmc_check" id="9">
                                                        <label class="form-check-label" for="9">
                                                           HMC
                                                        </label>
                                                    </div>    
                                            </td>
                                            
                                             <td>
                                                  <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" PROGRESSIVE LENS, " name="s_progressivelens_check" id="10">
                                                        <label class="form-check-label" for="10">
                                                           PROGRESSIVE LENS
                                                        </label>
                                                    </div> 
                                             </td>
										</tr>
                                        <tr style ="font-size: 13px;">
											<td>
                                                 <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" BI-FOCAL LENS, " name="s_bicfocal_check" id="11">
                                                        <label class="form-check-label" for="11">
                                                           BI-FOCAL LENS
                                                        </label>
                                                    </div>  
                                            </td>
                                          
                                             <td>
                                                 <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" SCRATCH RESISTANT, " name="s_scratchresistant_check" id="12">
                                                        <label class="form-check-label" for="12">
                                                           SCRATCH RESISTANT
                                                        </label>
                                                    </div>  
                                                  
                                             </td>
										</tr>
									</table>
                        
                        <!-- ///single vision lens -->
            </div>
            <!-- Progressive vision -->
      	    <div id="div2" style="display: none;" class="target">Progressive Vision
             
					   	    <table  class="table table-bordered">
                            <thead>
                                <th>Eye</th>
                                <br>
                                <th>Sph</th>
                                <th>Cyl</th>
                                <th>Axis</th>
                                <th>Add</th>
                            </thead>
                            <tr style="">
                                <td>OD (Right)</td>
                                <td><select id="psph-right" class="form-select" name="psph-right" onchange="p_set_sph_right(this.value)" style="width: 100%;">
                                        <option value=" || 0" selected>-</option>
                                        <option value="- Right SPH +3.00, || 0">+3.00</option>
                                        <option value="- Right SPH +2.75, || 0">+2.75</option>
                                        <option value="- Right SPH +2.50, || 0">+2.50</option>
                                        <option value="- Right SPH +2.25, || 0">+2.25</option>
                                        <option value="- Right SPH +2.00, || 0">+2.00</option>
                                        <option value="- Right SPH +1.75, || 0">+1.75</option>
                                        <option value="- Right SPH +1.50, || 0">+1.50</option>
                                        <option value="- Right SPH +1.25, || 0">+1.25</option>
                                        <option value="- Right SPH +1.00, || 0">+1.00</option>
                                        <option value="- Right SPH +0.75, || 0">+0.75</option>
                                        <option value="- Right SPH +0.50, || 0">+0.50</option>
                                        <option value="- Right SPH +0.25, || 0">+0.25</option>
                                        <option value="- Right SPH Plano (0.00), || 0">Plano (0.00)</option>
                                        <option value="- Right SPH -0.25, || 0">-0.25</option>
                                        <option value="- Right SPH -0.50, || 0">-0.50</option>
                                        <option value="- Right SPH -0.75, || 0">-0.75</option>
                                        <option value="- Right SPH -1.00, || 0">-1.00</option>
                                        <option value="- Right SPH -1.25, || 0">-1.25</option>
                                        <option value="- Right SPH -1.50, || 0">-1.50</option>
                                        <option value="- Right SPH -1.75, || 0">-1.75</option>
                                        <option value="- Right SPH -2.00, || 0">-2.00</option>
                                        <option value="- Right SPH -2.25, || 0">-2.25</option>
                                        <option value="- Right SPH -2.50, || 0">-2.50</option>
                                        <option value="- Right SPH -2.75, || 0">-2.75</option>
                                        <option value="- Right SPH -3.00, || 0">-3.00</option>

                                </td>
                                <td><select id="pcyl-right" class="form-select" name="pcyl-right" onchange="p_set_cyl_right(this.value)" style="width: 100%;">
                                        <option value=" || 0" selected>-</option>
                                        <option value="- Right CYL +3.00 || 3000 ">+3.00</option>
                                        <option value="- Right CYL +2.75 || 3000 ">+2.75</option>
                                        <option value="- Right CYL +2.50 || 3000 ">+2.50</option>
                                        <option value="- Right CYL +2.25 || 3000 ">+2.25</option>
                                        <option value="- Right CYL +2.00 || 3000 ">+2.00</option>
                                        <option value="- Right CYL +1.75 || 3000 ">+1.75</option>
                                        <option value="- Right CYL +1.50 || 3000 ">+1.50</option>
                                        <option value="- Right CYL +1.25 || 3000 ">+1.25</option>
                                        <option value="- Right CYL +1.00 || 3000 ">+1.00</option>
                                        <option value="- Right CYL +0.75 || 3000 ">+0.75</option>
                                        <option value="- Right CYL +0.50 || 3000 ">+0.50</option>
                                        <option value="- Right CYL +0.25 || 3000 ">+0.25</option>
                                        
                                        <option value="- Right Cyl Plano|| 0" >Plano (0.00)</option>

                                        <option value="- Right CYL -0.25, || 3000">-0.25</option>
                                        <option value="- Right CYL -0.50, || 3000">-0.50</option>
                                        <option value="- Right CYL -0.75, || 3000">-0.75</option>
                                        <option value="- Right CYL -1.00, || 3000">-1.00</option>
                                        <option value="- Right CYL -1.25, || 3000">-1.25</option>
                                        <option value="- Right CYL -1.50, || 3000">-1.50</option>
                                        <option value="- Right CYL -1.75, || 3000">-1.75</option>
                                        <option value="- Right CYL -2.00, || 3000">-2.00</option>
                                        <option value="- Right CYL -2.25, || 3000">-2.25</option>
                                        <option value="- Right CYL -2.50, || 3000">-2.50</option>
                                        <option value="- Right CYL -2.75, || 3000">-2.75</option>
                                        <option value="- Right CYL -3.00, || 3000">-3.00</option>
                                </td>
                                <td><select id="paxis-right" class="form-select" name="paxis-right" onchange="p_set_axis_right(this.value)" style="width: 100%;">
                                        <option value=" || 0" selected>-</option>
                                    <option value="- Right Axis 0, || 0">0</option>
                                    <option value="- Right Axis 1 || ">1</option>
						        	<option value="- Right Axis 2 || ">2</option>
						        	<option value="- Right Axis 3 || ">3</option>
						        	<option value="- Right Axis 4 || ">4</option>
						        	<option value="- Right Axis 5 || ">5</option>
						        	<option value="- Right Axis 6 || ">6</option>
						        	<option value="- Right Axis 7 || ">7</option>
						        	<option value="- Right Axis 8 || ">8</option>
						        	<option value="- Right Axis 9 || ">9</option>
						        	<option value="- Right Axis 10 || ">10</option>
						        	<option value="- Right Axis 11 || ">11</option>
						        	<option value="- Right Axis 12 || ">12</option>
						        	<option value="- Right Axis 13 || ">13</option>
						        	<option value="- Right Axis 14 || ">14</option>
						        	<option value="- Right Axis 15 || ">15</option>
						        	<option value="- Right Axis 16 || ">16</option>
						        	<option value="- Right Axis 17 || ">17</option>
						        	<option value="- Right Axis 18 || ">18</option>
						        	<option value="- Right Axis 19 || ">19</option>
						        	<option value="- Right Axis 20 || ">20</option>
						        	<option value="- Right Axis 21 || ">21</option>
						        	<option value="- Right Axis 22 || ">22</option>
						        	<option value="- Right Axis 23 || ">23</option>
						        	<option value="- Right Axis 24 || ">24</option>
						        	<option value="- Right Axis 25 || ">25</option>
						        	<option value="- Right Axis 26 || ">26</option>
						        	<option value="- Right Axis 27 || ">27</option>
						        	<option value="- Right Axis 28 || ">28</option>
						        	<option value="- Right Axis 29 || ">29</option>
						        	<option value="- Right Axis 30 || ">30</option>
						        	<option value="- Right Axis 31 || ">31</option>
						        	<option value="- Right Axis 32 || ">32</option>
						        	<option value="- Right Axis 33 || ">33</option>
						        	<option value="- Right Axis 34 || ">34</option>
						        	<option value="- Right Axis 35 || ">35</option>
						        	<option value="- Right Axis 36 || ">36</option>
						        	<option value="- Right Axis 37 || ">37</option>
						        	<option value="- Right Axis 38 || ">38</option>
						        	<option value="- Right Axis 39 || ">39</option>
						        	<option value="- Right Axis 40 || ">40</option>
						        	<option value="- Right Axis 41 || ">41</option>
						        	<option value="- Right Axis 42 || ">42</option>
						        	<option value="- Right Axis 43 || ">43</option>
						        	<option value="- Right Axis 44 || ">44</option>
						        	<option value="- Right Axis 45 || ">45</option>
						        	<option value="- Right Axis 46 || ">46</option>
						        	<option value="- Right Axis 47 || ">47</option>
						        	<option value="- Right Axis 48 || ">48</option>
						        	<option value="- Right Axis 49 || ">49</option>
						        	<option value="- Right Axis 50 || ">50</option>
						        	<option value="- Right Axis 51 || ">51</option>
						        	<option value="- Right Axis 52 || ">52</option>
						        	<option value="- Right Axis 53 || ">53</option>
						        	<option value="- Right Axis 54 || ">54</option>
						        	<option value="- Right Axis 55 || ">55</option>
						        	<option value="- Right Axis 56 || ">56</option>
						        	<option value="- Right Axis 57 || ">57</option>
						        	<option value="- Right Axis 58 || ">58</option>
						        	<option value="- Right Axis 59 || ">59</option>
						        	<option value="- Right Axis 60 || ">60</option>
						        	<option value="- Right Axis 61 || ">61</option>
						        	<option value="- Right Axis 62 || ">62</option>
						        	<option value="- Right Axis 63 || ">63</option>
						        	<option value="- Right Axis 64 || ">64</option>
						        	<option value="- Right Axis 65 || ">65</option>
						        	<option value="- Right Axis 66 || ">66</option>
						        	<option value="- Right Axis 67 || ">67</option>
						        	<option value="- Right Axis 68 || ">68</option>
						        	<option value="- Right Axis 69 || ">69</option>
						        	<option value="- Right Axis 70 || ">70</option>
						        	<option value="- Right Axis 71 || ">71</option>
						        	<option value="- Right Axis 72 || ">72</option>
						        	<option value="- Right Axis 73 || ">73</option>
						        	<option value="- Right Axis 74 || ">74</option>
						        	<option value="- Right Axis 75 || ">75</option>
						        	<option value="- Right Axis 76 || ">76</option>
						        	<option value="- Right Axis 77 || ">77</option>
						        	<option value="- Right Axis 78 || ">78</option>
						        	<option value="- Right Axis 79 || ">79</option>
						        	<option value="- Right Axis 80 || ">80</option>
						        	<option value="- Right Axis 81 || ">81</option>
						        	<option value="- Right Axis 82 || ">82</option>
						        	<option value="- Right Axis 83 || ">83</option>
						        	<option value="- Right Axis 84 || ">84</option>
						        	<option value="- Right Axis 85 || ">85</option>
						        	<option value="- Right Axis 86 || ">86</option>
						        	<option value="- Right Axis 87 || ">87</option>
						        	<option value="- Right Axis 88 || ">88</option>
						        	<option value="- Right Axis 89 || ">89</option>
						        	<option value="- Right Axis 90 || ">90</option>
						        	<option value="- Right Axis 91 || ">91</option>
						        	<option value="- Right Axis 92 || ">92</option>
						        	<option value="- Right Axis 93 || ">93</option>
						        	<option value="- Right Axis 94 || ">94</option>
						        	<option value="- Right Axis 95 || ">95</option>
						        	<option value="- Right Axis 96 || ">96</option>
						        	<option value="- Right Axis 97 || ">97</option>
						        	<option value="- Right Axis 98 || ">98</option>
						        	<option value="- Right Axis 99 || ">99</option>
						        	<option value="- Right Axis 100 || ">100</option>
						        	<option value="- Right Axis 101 || ">101</option>
						        	<option value="- Right Axis 102 || ">102</option>
						        	<option value="- Right Axis 103 || ">103</option>
						        	<option value="- Right Axis 104 || ">104</option>
						        	<option value="- Right Axis 105 || ">105</option>
						        	<option value="- Right Axis 106 || ">106</option>
						        	<option value="- Right Axis 107 || ">107</option>
						        	<option value="- Right Axis 108 || ">108</option>
						        	<option value="- Right Axis 109 || ">109</option>
						        	<option value="- Right Axis 110 || ">110</option>
						        	<option value="- Right Axis 111 || ">111</option>
						        	<option value="- Right Axis 112 || ">112</option>
						        	<option value="- Right Axis 113 || ">113</option>
						        	<option value="- Right Axis 114 || ">114</option>
						        	<option value="- Right Axis 115 || ">115</option>
						        	<option value="- Right Axis 116 || ">116</option>
						        	<option value="- Right Axis 117 || ">117</option>
						        	<option value="- Right Axis 118 || ">118</option>
						        	<option value="- Right Axis 119 || ">119</option>
						        	<option value="- Right Axis 120 || ">120</option>
						        	<option value="- Right Axis 121 || ">121</option>
						        	<option value="- Right Axis 122 || ">122</option>
						        	<option value="- Right Axis 123 || ">123</option>
						        	<option value="- Right Axis 124 || ">124</option>
						        	<option value="- Right Axis 125 || ">125</option>
						        	<option value="- Right Axis 126 || ">126</option>
						        	<option value="- Right Axis 127 || ">127</option>
						        	<option value="- Right Axis 128 || ">128</option>
						        	<option value="- Right Axis 129 || ">129</option>
						        	<option value="- Right Axis 130 || ">130</option>
						        	<option value="- Right Axis 131 || ">131</option>
						        	<option value="- Right Axis 132 || ">132</option>
						        	<option value="- Right Axis 133 || ">133</option>
						        	<option value="- Right Axis 134 || ">134</option>
						        	<option value="- Right Axis 135 || ">135</option>
						        	<option value="- Right Axis 136 || ">136</option>
						        	<option value="- Right Axis 137 || ">137</option>
						        	<option value="- Right Axis 138 || ">138</option>
						        	<option value="- Right Axis 139 || ">139</option>
						        	<option value="- Right Axis 140 || ">140</option>
						        	<option value="- Right Axis 141 || ">141</option>
						        	<option value="- Right Axis 142 || ">142</option>
						        	<option value="- Right Axis 143 || ">143</option>
						        	<option value="- Right Axis 144 || ">144</option>
						        	<option value="- Right Axis 145 || ">145</option>
						        	<option value="- Right Axis 146 || ">146</option>
						        	<option value="- Right Axis 147 || ">147</option>
						        	<option value="- Right Axis 148 || ">148</option>
						        	<option value="- Right Axis 149 || ">149</option>
						        	<option value="- Right Axis 150 || ">150</option>
						        	<option value="- Right Axis 151 || ">151</option>
						        	<option value="- Right Axis 152 || ">152</option>
						        	<option value="- Right Axis 153 || ">153</option>
						        	<option value="- Right Axis 154 || ">154</option>
						        	<option value="- Right Axis 155 || ">155</option>
						        	<option value="- Right Axis 156 || ">156</option>
						        	<option value="- Right Axis 157 || ">157</option>
						        	<option value="- Right Axis 158 || ">158</option>
						        	<option value="- Right Axis 159 || ">159</option>
						        	<option value="- Right Axis 160 || ">160</option>
						        	<option value="- Right Axis 161 || ">161</option>
						        	<option value="- Right Axis 162 || ">162</option>
						        	<option value="- Right Axis 163 || ">163</option>
						        	<option value="- Right Axis 164 || ">164</option>
						        	<option value="- Right Axis 165 || ">165</option>
						        	<option value="- Right Axis 166 || ">166</option>
						        	<option value="- Right Axis 167 || ">167</option>
						        	<option value="- Right Axis 168 || ">168</option>
						        	<option value="- Right Axis 169 || ">169</option>
						        	<option value="- Right Axis 170 || ">170</option>
						        	<option value="- Right Axis 171 || ">171</option>
						        	<option value="- Right Axis 172 || ">172</option>
						        	<option value="- Right Axis 173 || ">173</option>
						        	<option value="- Right Axis 174 || ">174</option>
						        	<option value="- Right Axis 175 || ">175</option>
						        	<option value="- Right Axis 176 || ">176</option>
						        	<option value="- Right Axis 177 || ">177</option>
						        	<option value="- Right Axis 178 || ">178</option>
						        	<option value="- Right Axis 179 || ">179</option>
						        	<option value="- Right Axis 180 || ">180</option>

                                </td>
                                <td><select id="padd-right" class="form-select" name="padd-right" onchange="p_set_add_right(this.value)" style="width: 100%;">
                                        <option value=" || 0" selected>-</option>
                                        <option value="- Right ADD +3.00 || 0 ">+3.00</option>
                                        <option value="- Right ADD +2.75 || 0 ">+2.75</option>
                                        <option value="- Right ADD +2.50 || 0 ">+2.50</option>
                                        <option value="- Right ADD +2.25 || 0 ">+2.25</option>
                                        <option value="- Right ADD +2.00 || 0 ">+2.00</option>
                                        <option value="- Right ADD +1.75 || 0 ">+1.75</option>
                                        <option value="- Right ADD +1.50 || 0 ">+1.50</option>
                                        <option value="- Right ADD +1.25 || 0 ">+1.25</option>
                                        <option value="- Right ADD +1.00 || 0 ">+1.00</option>
                                        <option value="- Right ADD +0.75 || 0 ">+0.75</option>
                                        <option value="- Right ADD +0.50 || 0 ">+0.50</option>
                                        <option value="- Right ADD +0.25 || 0 ">+0.25</option>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>OS (Left)</td>
                                <td><select id="psph-left" class="form-select" name="psph-left" onchange="p_set_sph_left(this.value)" style="width: 100%;">
                                        <option value=" || 0" selected>-</option>
                                        <option value="- Left SPH +3.00, || 0">+3.00</option>
                                        <option value="- Left SPH +2.75, || 0">+2.75</option>
                                        <option value="- Left SPH +2.50, || 0">+2.50</option>
                                        <option value="- Left SPH +2.25, || 0">+2.25</option>
                                        <option value="- Left SPH +2.00, || 0">+2.00</option>
                                        <option value="- Left SPH +1.75, || 0">+1.75</option>
                                        <option value="- Left SPH +1.50, || 0">+1.50</option>
                                        <option value="- Left SPH +1.25, || 0">+1.25</option>
                                        <option value="- Left SPH +1.00, || 0">+1.00</option>
                                        <option value="- Left SPH +0.75, || 0">+0.75</option>
                                        <option value="- Left SPH +0.50, || 0">+0.50</option>
                                        <option value="- Left SPH +0.25, || 0">+0.25</option>
                                        <option value="- Left SPH Plano (0.00), || 0">Plano (0.00)</option>
                                        <option value="- Left SPH -0.25, || 0">-0.25</option>
                                        <option value="- Left SPH -0.50, || 0">-0.50</option>
                                        <option value="- Left SPH -0.75, || 0">-0.75</option>
                                        <option value="- Left SPH -1.00, || 0">-1.00</option>
                                        <option value="- Left SPH -1.25, || 0">-1.25</option>
                                        <option value="- Left SPH -1.50, || 0">-1.50</option>
                                        <option value="- Left SPH -1.75, || 0">-1.75</option>
                                        <option value="- Left SPH -2.00, || 0">-2.00</option>
                                        <option value="- Left SPH -2.25, || 0">-2.25</option>
                                        <option value="- Left SPH -2.50, || 0">-2.50</option>
                                        <option value="- Left SPH -2.75, || 0">-2.75</option>
                                        <option value="- Left SPH -3.00, || 0">-3.00</option>
                                </td>
                                <td><select id="pcyl-left" class="form-select" name="pcyl-left" onchange="p_set_cyl_left(this.value)" style="width: 100%;">
                                        <option value=" || 0" selected>-</option>
                                        <option value="- Left CYL +3.00 || 3000 ">+3.00</option>
                                        <option value="- Left CYL +2.75 || 3000 ">+2.75</option>
                                        <option value="- Left CYL +2.50 || 3000 ">+2.50</option>
                                        <option value="- Left CYL +2.25 || 3000 ">+2.25</option>
                                        <option value="- Left CYL +2.00 || 3000 ">+2.00</option>
                                        <option value="- Left CYL +1.75 || 3000 ">+1.75</option>
                                        <option value="- Left CYL +1.50 || 3000 ">+1.50</option>
                                        <option value="- Left CYL +1.25 || 3000 ">+1.25</option>
                                        <option value="- Left CYL +1.00 || 3000 ">+1.00</option>
                                        <option value="- Left CYL +0.75 || 3000 ">+0.75</option>
                                        <option value="- Left CYL +0.50 || 3000 ">+0.50</option>
                                        <option value="- Left CYL +0.25 || 3000 ">+0.25</option>
                                        
                                        <option value="- Left Cyl Plano|| 0" >Plano (0.00)</option>

                                        <option value="- Left CYL -0.25, || 3000">-0.25</option>
                                        <option value="- Left CYL -0.50, || 3000">-0.50</option>
                                        <option value="- Left CYL -0.75, || 3000">-0.75</option>
                                        <option value="- Left CYL -1.00, || 3000">-1.00</option>
                                        <option value="- Left CYL -1.25, || 3000">-1.25</option>
                                        <option value="- Left CYL -1.50, || 3000">-1.50</option>
                                        <option value="- Left CYL -1.75, || 3000">-1.75</option>
                                        <option value="- Left CYL -2.00, || 3000">-2.00</option>
                                        <option value="- Left CYL -2.25, || 3000">-2.25</option>
                                        <option value="- Left CYL -2.50, || 3000">-2.50</option>
                                        <option value="- Left CYL -2.75, || 3000">-2.50</option>
                                        <option value="- Left CYL -3.00, || 3000">-3.00</option>
                                </td>
                                <td><select id="paxis-left" class="form-select" name="paxis-left" onchange="p_set_axis_left(this.value)" style="width: 100%;">
                                    <option value=" || 0" selected>-</option>
                                    <option value="- Left Axis 0, || ">0</option>
                                    <option value="- Left Axis 1 || ">1</option>
						        	<option value="- Left Axis 2 || ">2</option>
						        	<option value="- Left Axis 3 || ">3</option>
						        	<option value="- Left Axis 4 || ">4</option>
						        	<option value="- Left Axis 5 || ">5</option>
						        	<option value="- Left Axis 6 || ">6</option>
						        	<option value="- Left Axis 7 || ">7</option>
						        	<option value="- Left Axis 8 || ">8</option>
						        	<option value="- Left Axis 9 || ">9</option>
						        	<option value="- Left Axis 10 || ">10</option>
						        	<option value="- Left Axis 11 || ">11</option>
						        	<option value="- Left Axis 12 || ">12</option>
						        	<option value="- Left Axis 13 || ">13</option>
						        	<option value="- Left Axis 14 || ">14</option>
						        	<option value="- Left Axis 15 || ">15</option>
						        	<option value="- Left Axis 16 || ">16</option>
						        	<option value="- Left Axis 17 || ">17</option>
						        	<option value="- Left Axis 18 || ">18</option>
						        	<option value="- Left Axis 19 || ">19</option>
						        	<option value="- Left Axis 20 || ">20</option>
						        	<option value="- Left Axis 21 || ">21</option>
						        	<option value="- Left Axis 22 || ">22</option>
						        	<option value="- Left Axis 23 || ">23</option>
						        	<option value="- Left Axis 24 || ">24</option>
						        	<option value="- Left Axis 25 || ">25</option>
						        	<option value="- Left Axis 26 || ">26</option>
						        	<option value="- Left Axis 27 || ">27</option>
						        	<option value="- Left Axis 28 || ">28</option>
						        	<option value="- Left Axis 29 || ">29</option>
						        	<option value="- Left Axis 30 || ">30</option>
						        	<option value="- Left Axis 31 || ">31</option>
						        	<option value="- Left Axis 32 || ">32</option>
						        	<option value="- Left Axis 33 || ">33</option>
						        	<option value="- Left Axis 34 || ">34</option>
						        	<option value="- Left Axis 35 || ">35</option>
						        	<option value="- Left Axis 36 || ">36</option>
						        	<option value="- Left Axis 37 || ">37</option>
						        	<option value="- Left Axis 38 || ">38</option>
						        	<option value="- Left Axis 39 || ">39</option>
						        	<option value="- Left Axis 40 || ">40</option>
						        	<option value="- Left Axis 41 || ">41</option>
						        	<option value="- Left Axis 42 || ">42</option>
						        	<option value="- Left Axis 43 || ">43</option>
						        	<option value="- Left Axis 44 || ">44</option>
						        	<option value="- Left Axis 45 || ">45</option>
						        	<option value="- Left Axis 46 || ">46</option>
						        	<option value="- Left Axis 47 || ">47</option>
						        	<option value="- Left Axis 48 || ">48</option>
						        	<option value="- Left Axis 49 || ">49</option>
						        	<option value="- Left Axis 50 || ">50</option>
						        	<option value="- Left Axis 51 || ">51</option>
						        	<option value="- Left Axis 52 || ">52</option>
						        	<option value="- Left Axis 53 || ">53</option>
						        	<option value="- Left Axis 54 || ">54</option>
						        	<option value="- Left Axis 55 || ">55</option>
						        	<option value="- Left Axis 56 || ">56</option>
						        	<option value="- Left Axis 57 || ">57</option>
						        	<option value="- Left Axis 58 || ">58</option>
						        	<option value="- Left Axis 59 || ">59</option>
						        	<option value="- Left Axis 60 || ">60</option>
						        	<option value="- Left Axis 61 || ">61</option>
						        	<option value="- Left Axis 62 || ">62</option>
						        	<option value="- Left Axis 63 || ">63</option>
						        	<option value="- Left Axis 64 || ">64</option>
						        	<option value="- Left Axis 65 || ">65</option>
						        	<option value="- Left Axis 66 || ">66</option>
						        	<option value="- Left Axis 67 || ">67</option>
						        	<option value="- Left Axis 68 || ">68</option>
						        	<option value="- Left Axis 69 || ">69</option>
						        	<option value="- Left Axis 70 || ">70</option>
						        	<option value="- Left Axis 71 || ">71</option>
						        	<option value="- Left Axis 72 || ">72</option>
						        	<option value="- Left Axis 73 || ">73</option>
						        	<option value="- Left Axis 74 || ">74</option>
						        	<option value="- Left Axis 75 || ">75</option>
						        	<option value="- Left Axis 76 || ">76</option>
						        	<option value="- Left Axis 77 || ">77</option>
						        	<option value="- Left Axis 78 || ">78</option>
						        	<option value="- Left Axis 79 || ">79</option>
						        	<option value="- Left Axis 80 || ">80</option>
						        	<option value="- Left Axis 81 || ">81</option>
						        	<option value="- Left Axis 82 || ">82</option>
						        	<option value="- Left Axis 83 || ">83</option>
						        	<option value="- Left Axis 84 || ">84</option>
						        	<option value="- Left Axis 85 || ">85</option>
						        	<option value="- Left Axis 86 || ">86</option>
						        	<option value="- Left Axis 87 || ">87</option>
						        	<option value="- Left Axis 88 || ">88</option>
						        	<option value="- Left Axis 89 || ">89</option>
						        	<option value="- Left Axis 90 || ">90</option>
						        	<option value="- Left Axis 91 || ">91</option>
						        	<option value="- Left Axis 92 || ">92</option>
						        	<option value="- Left Axis 93 || ">93</option>
						        	<option value="- Left Axis 94 || ">94</option>
						        	<option value="- Left Axis 95 || ">95</option>
						        	<option value="- Left Axis 96 || ">96</option>
						        	<option value="- Left Axis 97 || ">97</option>
						        	<option value="- Left Axis 98 || ">98</option>
						        	<option value="- Left Axis 99 || ">99</option>
						        	<option value="- Left Axis 100 || ">100</option>
						        	<option value="- Left Axis 101 || ">101</option>
						        	<option value="- Left Axis 102 || ">102</option>
						        	<option value="- Left Axis 103 || ">103</option>
						        	<option value="- Left Axis 104 || ">104</option>
						        	<option value="- Left Axis 105 || ">105</option>
						        	<option value="- Left Axis 106 || ">106</option>
						        	<option value="- Left Axis 107 || ">107</option>
						        	<option value="- Left Axis 108 || ">108</option>
						        	<option value="- Left Axis 109 || ">109</option>
						        	<option value="- Left Axis 110 || ">110</option>
						        	<option value="- Left Axis 111 || ">111</option>
						        	<option value="- Left Axis 112 || ">112</option>
						        	<option value="- Left Axis 113 || ">113</option>
						        	<option value="- Left Axis 114 || ">114</option>
						        	<option value="- Left Axis 115 || ">115</option>
						        	<option value="- Left Axis 116 || ">116</option>
						        	<option value="- Left Axis 117 || ">117</option>
						        	<option value="- Left Axis 118 || ">118</option>
						        	<option value="- Left Axis 119 || ">119</option>
						        	<option value="- Left Axis 120 || ">120</option>
						        	<option value="- Left Axis 121 || ">121</option>
						        	<option value="- Left Axis 122 || ">122</option>
						        	<option value="- Left Axis 123 || ">123</option>
						        	<option value="- Left Axis 124 || ">124</option>
						        	<option value="- Left Axis 125 || ">125</option>
						        	<option value="- Left Axis 126 || ">126</option>
						        	<option value="- Left Axis 127 || ">127</option>
						        	<option value="- Left Axis 128 || ">128</option>
						        	<option value="- Left Axis 129 || ">129</option>
						        	<option value="- Left Axis 130 || ">130</option>
						        	<option value="- Left Axis 131 || ">131</option>
						        	<option value="- Left Axis 132 || ">132</option>
						        	<option value="- Left Axis 133 || ">133</option>
						        	<option value="- Left Axis 134 || ">134</option>
						        	<option value="- Left Axis 135 || ">135</option>
						        	<option value="- Left Axis 136 || ">136</option>
						        	<option value="- Left Axis 137 || ">137</option>
						        	<option value="- Left Axis 138 || ">138</option>
						        	<option value="- Left Axis 139 || ">139</option>
						        	<option value="- Left Axis 140 || ">140</option>
						        	<option value="- Left Axis 141 || ">141</option>
						        	<option value="- Left Axis 142 || ">142</option>
						        	<option value="- Left Axis 143 || ">143</option>
						        	<option value="- Left Axis 144 || ">144</option>
						        	<option value="- Left Axis 145 || ">145</option>
						        	<option value="- Left Axis 146 || ">146</option>
						        	<option value="- Left Axis 147 || ">147</option>
						        	<option value="- Left Axis 148 || ">148</option>
						        	<option value="- Left Axis 149 || ">149</option>
						        	<option value="- Left Axis 150 || ">150</option>
						        	<option value="- Left Axis 151 || ">151</option>
						        	<option value="- Left Axis 152 || ">152</option>
						        	<option value="- Left Axis 153 || ">153</option>
						        	<option value="- Left Axis 154 || ">154</option>
						        	<option value="- Left Axis 155 || ">155</option>
						        	<option value="- Left Axis 156 || ">156</option>
						        	<option value="- Left Axis 157 || ">157</option>
						        	<option value="- Left Axis 158 || ">158</option>
						        	<option value="- Left Axis 159 || ">159</option>
						        	<option value="- Left Axis 160 || ">160</option>
						        	<option value="- Left Axis 161 || ">161</option>
						        	<option value="- Left Axis 162 || ">162</option>
						        	<option value="- Left Axis 163 || ">163</option>
						        	<option value="- Left Axis 164 || ">164</option>
						        	<option value="- Left Axis 165 || ">165</option>
						        	<option value="- Left Axis 166 || ">166</option>
						        	<option value="- Left Axis 167 || ">167</option>
						        	<option value="- Left Axis 168 || ">168</option>
						        	<option value="- Left Axis 169 || ">169</option>
						        	<option value="- Left Axis 170 || ">170</option>
						        	<option value="- Left Axis 171 || ">171</option>
						        	<option value="- Left Axis 172 || ">172</option>
						        	<option value="- Left Axis 173 || ">173</option>
						        	<option value="- Left Axis 174 || ">174</option>
						        	<option value="- Left Axis 175 || ">175</option>
						        	<option value="- Left Axis 176 || ">176</option>
						        	<option value="- Left Axis 177 || ">177</option>
						        	<option value="- Left Axis 178 || ">178</option>
						        	<option value="- Left Axis 179 || ">179</option>
						        	<option value="- Left Axis 180 || ">180</option>
                                </td>
                                <td><select id="padd-left" class="form-select" name="padd-left" onchange="p_set_add_left(this.value)" style="width: 100%;">
                                        <option value=" || 0" selected>-</option>
                                        <option value="- Left ADD +3.00 || 0 ">+3.00</option>
                                        <option value="- Left ADD +2.75 || 0 ">+2.75</option>
                                        <option value="- Left ADD +2.50 || 0 ">+2.50</option>
                                        <option value="- Left ADD +2.25 || 0 ">+2.25</option>
                                        <option value="- Left ADD +2.00 || 0 ">+2.00</option>
                                        <option value="- Left ADD +1.75 || 0 ">+1.75</option>
                                        <option value="- Left ADD +1.50 || 0 ">+1.50</option>
                                        <option value="- Left ADD +1.25 || 0 ">+1.25</option>
                                        <option value="- Left ADD +1.00 || 0 ">+1.00</option>
                                        <option value="- Left ADD +0.75 || 0 ">+0.75</option>
                                        <option value="- Left ADD +0.50 || 0 ">+0.50</option>
                                        <option value="- Left ADD +0.25 || 0 ">+0.25</option>
                                        

                                </td>
                            </tr>
				    	</table>
                        <!-- progressive vision pd -->
                        <h6 class="text-red-700" style="padding: 3px; border-radius: 5px; "><b>Prescription Notice 2:</b> <span style="color: gray !important;">Please choose a medium(M) or large(L) size frame for Progressive Vision prescription.</span></h6>

						<div class="p_pd_menu_vision">
                                PD:
                               


                              <button prog_pd_target="5"  type="button" class="progressive_pd new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">One PD</button>
                              <button prog_pd_target="6"  type="button" class="progressive_pd new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">Two PD</button>
                                
                                <!-- <a role="button" style="cursor: pointer; color: white;" class="progressive_pd" prog_pd_target="5">
                                     <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >&nbsp; One PD</div>
                                </a>
                                <a role="button" style="cursor: pointer; color: white;" class="progressive_pd" prog_pd_target="6">
                                     <div class="btn" style="background-color: #373737 !important; margin:5px; color: white;" >&nbsp; Two PD</div>
                                </a> -->
                                
                                
                                <!--<div class="btn" style="background-color: #373737 !important;" >-->
                                <!--    <a style="cursor: pointer; color: white;" class="progressive_pd" prog_pd_target="6">&nbsp; Two PD</a>-->
                                <!-- </div>-->
                                 
                                 <a role="button" style="cursor: pointer; color: white;" onclick="p_pd_reset()" id="hideall_p_pd">
                                     <div class="btn" style="background-color: #800000 !important; margin:5px; color: white;" >&nbsp; Cancel</div>
                                 </a>
                                <!--<div class="btn" style="background-color: #A52A2A !important;" >-->
                                <!--    <a style="cursor: pointer; color: white;" onclick="p_pd_reset()" id="hideall_p_pd">&nbsp; Cancel</a>-->
                                <!--</div>-->
                                
						</div>                       
							<section class="pro_pd_target_box">
								<div id="div5" style="display: none;" class="prog_pd_target">
									<table class="table table-bordered">
                                    <thead>
										</thead>
                                        <tr>
                                        <td>PD Numbers:</td><td>
									<select id="p_one_pd" name="p_one_pd" class="form-select" onchange="one_pd_value(this.value)" style="width: 100px;"">
										<option value="" selected>Choose One</option>
											<option value="- One Pd: 40">40</option>
											<option value="- One Pd: 41">41</option>
											<option value="- One Pd: 42">42</option>
											<option value="- One Pd: 43">43</option>
											<option value="- One Pd: 44">44</option>
											<option value="- One Pd: 45">45</option>
											<option value="- One Pd: 46">46</option>
											<option value="- One Pd: 47">47</option>
											<option value="- One Pd: 48">48</option>
											<option value="- One Pd: 49">49</option>
											<option value="- One Pd: 50">50</option>
											<option value="- One Pd: 51">51</option>
											<option value="- One Pd: 52">52</option>
											<option value="- One Pd: 53">53</option>
											<option value="- One Pd: 54">54</option>
											<option value="- One Pd: 55">55</option>
											<option value="- One Pd: 56">56</option>
											<option value="- One Pd: 57">57</option>
											<option value="- One Pd: 58">58</option>
											<option value="- One Pd: 59">59</option>
											<option value="- One Pd: 60">60</option>
											<option value="- One Pd: 61">61</option>
											<option value="- One Pd: 62">62</option>
											<option value="- One Pd: 63">63</option>
											<option value="- One Pd: 64">64</option>
											<option value="- One Pd: 65">65</option>
											<option value="- One Pd: 66">66</option>
											<option value="- One Pd: 67">67</option>
											<option value="- One Pd: 68">68</option>
											<option value="- One Pd: 69">69</option>
											<option value="- One Pd: 70">70</option>
											<option value="- One Pd: 71">71</option>
											<option value="- One Pd: 72">72</option>
											<option value="- One Pd: 73">73</option>
											<option value="- One Pd: 74">74</option>
											<option value="- One Pd: 75">75</option>
											<option value="- One Pd: 76">76</option>
											<option value="- One Pd: 77">77</option>
											<option value="- One Pd: 78">78</option>
											<option value="- One Pd: 79">79</option>
											<option value="- One Pd: 80">80</option>
									</select>
									</td>
                                            </tr>
                                            </table>
								</div>
								<div id="div6" style="display: none;" class="prog_pd_target">PD Numbers:
								<table class="table table-bordered">
										<thead>
											<th></th>
											<br>
											<th>Right</th>
											<th>Left</th>
										</thead>
										<tr>
											<td>PD</td>
											<td><select id="p_two_pd_right" name="p_two_pd_right" class="form-select"  style="width: 100%;">
													<option value="" selected>-</option>
											<option value="- Two PD Right: 20.0">20.0</option>
											<option value="- Two PD Right: 20.5">20.5</option>
											<option value="- Two PD Right: 21.0">21.0</option>
											<option value="- Two PD Right: 21.5">21.5</option>
											<option value="- Two PD Right: 22.0">22.0</option>
											<option value="- Two PD Right: 22.5">22.5</option>
                                            <option value="- Two PD Right: 23.0">23.0</option>
											<option value="- Two PD Right: 23.5">23.5</option>
											<option value="- Two PD Right: 24.0">24.0</option>
											<option value="- Two PD Right: 24.5">24.5</option>
											<option value="- Two PD Right: 25.0">25.0</option>
											<option value="- Two PD Right: 25.5">25.5</option>
                                            <option value="- Two PD Right: 26.0">26.0</option>
											<option value="- Two PD Right: 26.5">26.5</option>
											<option value="- Two PD Right: 27.0">27.0</option>
											<option value="- Two PD Right: 27.5">27.5</option>
											<option value="- Two PD Right: 28.0">28.0</option>
											<option value="- Two PD Right: 28.5">28.5</option>
                                            <option value="- Two PD Right: 29.0">29.0</option>
											<option value="- Two PD Right: 29.5">29.5</option>
											<option value="- Two PD Right: 30.0">30.0</option>
											<option value="- Two PD Right: 30.5">30.5</option>
											<option value="- Two PD Right: 31.0">31.0</option>
											<option value="- Two PD Right: 31.5">31.5</option>
                                            <option value="- Two PD Right: 32.0">32.0</option>
											<option value="- Two PD Right: 32.5">32.5</option>
											<option value="- Two PD Right: 33.0">33.0</option>
											<option value="- Two PD Right: 33.5">33.5</option>
                                            <option value="- Two PD Right: 34.0">34.0</option>
											<option value="- Two PD Right: 34.5">34.5</option>
                                            <option value="- Two PD Right: 35.0">35.0</option>
											<option value="- Two PD Right: 35.5">35.5</option>
											<option value="- Two PD Right: 36.0">36.0</option>
											<option value="- Two PD Right: 36.5">36.5</option>
                                            <option value="- Two PD Right: 37.0">37.0</option>
                                            <option value="- Two PD Right: 37.5">37.5</option>
											<option value="- Two PD Right: 38.0">38.0</option>
											<option value="- Two PD Right: 38.5">38.5</option>
											<option value="- Two PD Right: 39.0">39.0</option>
                                            <option value="- Two PD Right: 39.5">39.5</option>
                                            <option value="- Two PD Right: 40.0">40.0</option>		

											</td>
											<td><select id="p_two_pd_left" name="p_two_pd_left" class="form-select" style="width: 100%;">
													<option value="" selected>-</option>
											<option value="- Two PD Left: 20.0">20.0</option>
											<option value="- Two PD Left: 20.5">20.5</option>
											<option value="- Two PD Left: 21.0">21.0</option>
											<option value="- Two PD Left: 21.5">21.5</option>
											<option value="- Two PD Left: 22.0">22.0</option>
											<option value="- Two PD Left: 22.5">22.5</option>
                                            <option value="- Two PD Left: 23.0">23.0</option>
											<option value="- Two PD Left: 23.5">23.5</option>
											<option value="- Two PD Left: 24.0">24.0</option>
											<option value="- Two PD Left: 24.5">24.5</option>
											<option value="- Two PD Left: 25.0">25.0</option>
											<option value="- Two PD Left: 25.5">25.5</option>		
											<option value="- Two PD Left: 26.0">26.0</option>
											<option value="- Two PD Left: 26.5">26.5</option>
											<option value="- Two PD Left: 27.0">27.0</option>
											<option value="- Two PD Left: 27.5">27.5</option>
											<option value="- Two PD Left: 28.0">28.0</option>
											<option value="- Two PD Left: 28.5">28.5</option>
                                            <option value="- Two PD Left: 29.0">29.0</option>
											<option value="- Two PD Left: 29.5">29.5</option>
											<option value="- Two PD Left: 30.0">30.0</option>
											<option value="- Two PD Left: 30.5">30.5</option>
											<option value="- Two PD Left: 31.0">31.0</option>
											<option value="- Two PD Left: 31.5">31.5</option>
                                            <option value="- Two PD Left: 32.0">32.0</option>
											<option value="- Two PD Left: 32.5">32.5</option>
											<option value="- Two PD Left: 33.0">33.0</option>
											<option value="- Two PD Left: 33.5">33.5</option>
                                            <option value="- Two PD Left: 34.0">34.0</option>
											<option value="- Two PD Left: 34.5">34.5</option>
                                            <option value="- Two PD Left: 35.0">35.0</option>
											<option value="- Two PD Left: 35.5">35.5</option>
											<option value="- Two PD Left: 36.0">36.0</option>
											<option value="- Two PD Left: 36.5">36.5</option>
                                            <option value="- Two PD Left: 37.0">37.0</option>
                                            <option value="- Two PD Left: 37.5">37.5</option>
											<option value="- Two PD Left: 38.0">38.0</option>
											<option value="- Two PD Left: 38.5">38.5</option>
											<option value="- Two PD Left: 39.0">39.0</option>
                                            <option value="- Two PD Left: 39.5">39.5</option>
                                            <option value="- Two PD Left: 40.0">40.0</option>		
											</td>

										</tr>

									</table>
									 PD type two:
                                    <table class="table table-bordered">
										<thead>
											<th></th>
											<br>
											<th>Far</th>
											<th>Near / Close</th>
										</thead>
										<tr>
											<td>PD</td>
											<td><select id="p_two_pd_right_type" name="p_two_pd_right_type" class="form-select" style="width: 100%;">
													<option value="" selected>-</option>
                                                   
											<option value="- Far: 40">40</option>
											<option value="- Far: 41">41</option>
											<option value="- Far: 42">42</option>
											<option value="- Far: 43">43</option>
											<option value="- Far: 44">44</option>
											<option value="- Far: 45">45</option>
											<option value="- Far: 46">46</option>
											<option value="- Far: 47">47</option>
											<option value="- Far: 48">48</option>
											<option value="- Far: 49">49</option>
											<option value="- Far: 50">50</option>
											<option value="- Far: 51">51</option>
											<option value="- Far: 52">52</option>
											<option value="- Far: 53">53</option>
											<option value="- Far: 54">54</option>
											<option value="- Far: 55">55</option>
											<option value="- Far: 56">56</option>
											<option value="- Far: 57">57</option>
											<option value="- Far: 58">58</option>
											<option value="- Far: 59">59</option>
											<option value="- Far: 60">60</option>
											<option value="- Far: 61">61</option>
											<option value="- Far: 62">62</option>
											<option value="- Far: 63">63</option>
											<option value="- Far: 64">64</option>
											<option value="- Far: 65">65</option>
											<option value="- Far: 66">66</option>
											<option value="- Far: 67">67</option>
											<option value="- Far: 68">68</option>
											<option value="- Far: 69">69</option>
											<option value="- Far: 70">70</option>
											<option value="- Far: 71">71</option>
											<option value="- Far: 72">72</option>
											<option value="- Far: 73">73</option>
											<option value="- Far: 74">74</option>
											<option value="- Far: 75">75</option>
											<option value="- Far: 76">76</option>
											<option value="- Far: 77">77</option>
											<option value="- Far: 78">78</option>
											<option value="- Far: 79">79</option>
											<option value="- Far: 80">80</option>		
	

											</td>
											<td><select id="p_two_pd_left_type" name="p_two_pd_left_type" class="form-select" style="width: 100%;">
													<option value="" selected>-</option>
												
									    	<option value=" - Near/Close: 40">40</option>
											<option value=" - Near/Close: 41">41</option>
											<option value=" - Near/Close: 42">42</option>
											<option value=" - Near/Close: 43">43</option>
											<option value=" - Near/Close: 44">44</option>
											<option value=" - Near/Close: 45">45</option>
											<option value=" - Near/Close: 46">46</option>
											<option value=" - Near/Close: 47">47</option>
											<option value=" - Near/Close: 48">48</option>
											<option value=" - Near/Close: 49">49</option>
											<option value=" - Near/Close: 50">50</option>
											<option value=" - Near/Close: 51">51</option>
											<option value=" - Near/Close: 52">52</option>
											<option value=" - Near/Close: 53">53</option>
											<option value=" - Near/Close: 54">54</option>
											<option value=" - Near/Close: 55">55</option>
											<option value=" - Near/Close: 56">56</option>
											<option value=" - Near/Close: 57">57</option>
											<option value=" - Near/Close: 58">58</option>
											<option value=" - Near/Close: 59">59</option>
											<option value=" - Near/Close: 60">60</option>
											<option value=" - Near/Close: 61">61</option>
											<option value=" - Near/Close: 62">62</option>
											<option value=" - Near/Close: 63">63</option>
											<option value=" - Near/Close: 64">64</option>
											<option value=" - Near/Close: 65">65</option>
											<option value=" - Near/Close: 66">66</option>
											<option value=" - Near/Close: 67">67</option>
											<option value=" - Near/Close: 68">68</option>
											<option value=" - Near/Close: 69">69</option>
											<option value=" - Near/Close: 70">70</option>
											<option value=" - Near/Close: 71">71</option>
											<option value=" - Near/Close: 72">72</option>
											<option value=" - Near/Close: 73">73</option>
											<option value=" - Near/Close: 74">74</option>
											<option value=" - Near/Close: 75">75</option>
											<option value=" - Near/Close: 76">76</option>
											<option value=" - Near/Close: 77">77</option>
											<option value=" - Near/Close: 78">78</option>
											<option value=" - Near/Close: 79">79</option>
											<option value=" - Near/Close: 80">80</option>		
											</td>

										</tr>

									</table>
								</div>
							</section>
                        <!-- progressive vision lens -->
                                 <table class="table table-borderless">
										<thead>
											
											
											<th style ="font-size: 15px;"> LENS TYPE REMARKS:</th>
										
										</thead>
										<tr style ="font-size: 13px;">
											<td>  <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=" PHOTO SOLAR, " name="p_photosolar_check" id="13" >
                                                        <label class="form-check-label" for="13">
                                                            PHOTO SOLAR
                                                        </label>
                                                    </div>
                                             </td>
                                            
                                             <td> 
                                                   <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=" PHOTO CHROMIC, " name="p_photochromic_check" id="14">
                                                        <label class="form-check-label" for="14">
                                                            PHOTO CHROMIC
                                                        </label>
                                                    </div>
                                             </td>
										</tr>
                                        <tr style ="font-size: 13px;">
											<td> 
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=" WHITE, " name="p_white_check" id="15">
                                                        <label class="form-check-label" for="15">
                                                            WHITE
                                                        </label>
                                                    </div>
                                                    
                                             </td>
                                            
                                             <td> 
                                                  <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" PLASTIC LENS, " name="p_plascitlens_check" id="16">
                                                        <label class="form-check-label" for="16">
                                                            PLASTIC LENS
                                                        </label>
                                                    </div>
                                             </td>
										</tr>
                                        <tr style ="font-size: 13px;">
											<td> 
                                                <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" SUN SENSOR, " name="p_sunsensor_check" id="17">
                                                        <label class="form-check-label" for="17">
                                                            SUN SENSOR
                                                        </label>
                                                    </div> 
                                             </td>                                           
                                             <td> 
                                             <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" GLARE FREE, " name="p_glarefree_check" id="18">
                                                        <label class="form-check-label" for="18">
                                                            GLARE FREE
                                                        </label>
                                                    </div>     
                                           </td>
										</tr>
                                         <tr style ="font-size: 13px;">
											<td>
                                                <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" ANTI-GLARE, " name="p_antiglare_check" id="19">
                                                        <label class="form-check-label" for="19">
                                                            ANTI-GLARE
                                                        </label>
                                                    </div>  
                                            </td>
                                           
                                             <td> 
                                              <div class="form-check">
                                                      
                                                        <input class="form-check-input" type="checkbox" value=" ARC / AR, " name="p_arc_check" id="20">
                                                        <label class="form-check-label" for="20">
                                                            ARC / AR
                                                        </label>
                                                    </div>     
                                             </td>
										</tr>
                                         <tr style ="font-size: 13px;">
											<td> 
                                             <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" HMC, " name="p_hmc_check" id="21">
                                                        <label class="form-check-label" for="21">
                                                           HMC
                                                        </label>
                                                    </div>    
                                            </td>
                                            
                                             <td>
                                                  <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" PROGRESSIVE LENS, " name="p_progressivelens_check" id="22">
                                                        <label class="form-check-label" for="22">
                                                           PROGRESSIVE LENS
                                                        </label>
                                                    </div> 
                                             </td>
										</tr>
                                        <tr style ="font-size: 13px;">
											<td>
                                                 <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" BI-FOCAL LENS, " name="p_bicfocal_check" id="23">
                                                        <label class="form-check-label" for="23">
                                                           BI-FOCAL LENS
                                                        </label>
                                                    </div>  
                                            </td>
                                          
                                             <td>
                                                 <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" value=" SCRATCH RESISTANT, " name="p_scratchresistant_check" id="24">
                                                        <label class="form-check-label" for="24">
                                                           SCRATCH RESISTANT
                                                        </label>
                                                    </div>  
                                                  
                                             </td>
										</tr>
									</table>  
	        </div>       
	    </section>
</div>
<div id="div8" style="display: none; " class="main_target"> LENS TYPE REMARKS
		<br>
        <button  onclick="antiblue()" type="button" class="new_btn text-black hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">PHOTO SOLAR LENS WITH ANTI-GLARE FOR PC</button>
        <button  onclick="tinted()" type="button" class="new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">WHITE LENS WITH ANTIGLARE FOR PC</button>
        <button  onclick="polarized()" type="button" class="new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">PHOTO SOLAR LENS WITH ANTI-GLARE AND BLUE LIGHT FILTER FOR PC</button>
        <button  onclick="photochromic()" type="button" class="new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">WHITE LENS WITH ANTI-GLARE AND BLUE LIGHT FILTER FOR PC</button>
        <button  onclick="nightvision()" type="button" class="new_btn text-gray hover:text-white border border-blue-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">POLARIZED LENS</button>
                                                
      


        <!-- <button type="button" class="new_btn text-white bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-full text- px-5 py-3.5 text-center me-2 mb-2 dark:focus:ring-yellow-900">Yellow</button> -->
		<!-- <button type="button" class="btn btn-secondary" style="text-color: #fff !important; margin:5px;"  onclick="antiblue()">PHOTO SOLAR LENS WITH ANTI-GLARE FOR PC</button> -->
        <!-- <button type="button" class="btn btn-secondary" style="margin:5px;"   onclick="tinted()">WHITE LENS WITH ANTIGLARE FOR PC</button> -->
        <!-- <button type="button" class="btn btn-secondary" style="margin:5px;"   onclick="polarized()">PHOTO SOLAR LENS WITH ANTI-GLARE AND BLUE LIGHT FILTER FOR PC</button> -->
        <!-- <button type="button" class="btn btn-secondary" style="margin:5px;"   onclick="photochromic()">WHITE LENS WITH ANTI-GLARE AND BLUE LIGHT FILTER FOR PC</button> -->
        <!-- <button type="button" class="btn btn-secondary" style="margin:5px;"   onclick="nightvision()">POLARIZED LENS</button> -->
        
        
	</div>
</section>
    </div>

            <!-- end vision -->


                                </div>

                            </div>
                                    

							<div class="p-price">
                                <span style="font-size:14px;"><?php echo "Frame Price"; ?></span><br>
                                <span>
                                    <!-- <?php //if($p_old_price!=''): ?>
                                        <del><?php //echo LANG_VALUE_1; ?><?php //echo $p_old_price; ?></del> -->
                               
                                        <?php echo ""; ?><?php 

                                        $price_of_lens = 0;

                                        $total_price = '';
                                        $total_price = $p_current_price ;

                                        echo '<span style="font-size:25px; color:#228B22;">ETB </span>';

                                        echo  $total_price?>                          
                                </span>
                                    <br>
                                <span style="font-size:14px;">
                                    Total Price <br>
                                  <p style="font-size:25px; color: #228B22; display: inline;" id="etb" name="etb" value="">
                                 <p style="font-size:30px; display: inline;" id="new_new" name="new_new" value="<?php echo $total_price; ?>">

                                </span>

                            </div>
                         
                            <input type="hidden" name="calculated_price" id="calculated_price" >
                                                   
                            <input type="hidden" name="p_current_price2" id="p_current_price2" value="<?php echo $p_current_price; ?>">

                            <input type="hidden" name="p_current_price" id="p_current_price" value="<?php echo $total_price; ?>">

                            <input type="hidden" name="p_name" value="<?php echo $p_name; ?>">
                            <input type="hidden" name="p_featured_photo" value="<?php echo $p_featured_photo; ?>">
					
                            <div class="p-quantity">
                                <?php echo LANG_VALUE_55; ?> <br>
								<input type="number" class="input-text qty" step="1" min="1" max="" name="p_qty" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
							</div>
                             
                            <div class="form-group">
                              
                                 <label for=""><?php echo '<b>Additional Detail:</b>  (*optional)'; ?></label><br>
                                 <input type="text" placeholder="Enter Additional Detail" class="form-control text-xl" name="additional_detail" >
							</div>
							 <div class="form-check" style="padding-left: 20px; padding-bottom: 10px;"> 
                                    <input class="form-check-input" type="checkbox"  value="free glass box"  id="91">
                                      <label style="padding: 2px; color: white; border-radius: 3px;  font-size: 12px !important; background-color: #f1b70a
; display: inline;"class="form-check-label" for="91" >
                                            &nbsp;  Free eyeglass box   &  Free eyeglass cleaning cloth &nbsp;
                                     </label>
                             </div> 
                                   
							<div class="btn-cart btn-cart1" >
                                <input type="submit" id="show_cart" value="<?php echo "Add To Cart"; ?>" name="form_add_to_cart">
							</div>

                    

                              <!-- Lanes Name --> <input type='hidden' name='name_lens' id='name_lens' />
     <!-- Lense Price --><input type='hidden'  name='cost_lens' id='cost_lens' /> <br>


     <!-- price values single -->
           <input type='hidden' name='single_sph_right'  id='single_sph_right' />
           <input type='hidden' name='single_sph_left' id='single_sph_left' />
           <input type='hidden' name='single_cyl_right'  id='single_cyl_right' />
           <input type='hidden' name='single_cyl_left' id='single_cyl_left' />
           <input type='hidden' name='single_axis_right' id='single_axis_right' />
           <input type='hidden' name='single_axis_left' id='single_axis_left' />
     <!-- //price values single -->
     
      <!-- price values progressive -->
           <input type='hidden' name='progressive_sph_right'  id='progressive_sph_right' />
           <input type='hidden' name='progressive_sph_left' id='progressive_sph_left' />
           <input type='hidden' name='progressive_cyl_right'  id='progressive_cyl_right' />
           <input type='hidden' name='progressive_cyl_left' id='progressive_cyl_left' />
           <input type='hidden' name='progressive_axis_right' id='progressive_axis_right' />
           <input type='hidden' name='progressive_axis_left' id='progressive_axis_left' />
           <input type='hidden' name='progressive_add_right' id='progressive_add_right' />
           <input type='hidden' name='progressive_add_left' id='progressive_add_left' />

       <!-- Pd numbers -->
           <input type='hidden' name='one_pd_data' id='one_pd_data' />
           <input type='hidden' name='two_pd_data_right' id='two_pd_data_right' />
           <input type='hidden' name='two_pd_data_left' id='two_pd_data_left' />

           
     <!-- // price values progressive -->

 
     <input type="hidden" id="disp_vision_price" name="disp_vision_price"  style="color:green; font-size: 15px;  font-weight: bold;">
    
                            </form>
							<!-- <div class="share">
                                <?php echo LANG_VALUE_58; ?> <br>
								<div class="sharethis-inline-share-buttons"></div>
							</div> -->
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?php echo LANG_VALUE_59; ?></a></li>
								
                               
                                <li role="presentation"><a href="#return_policy" aria-controls="return_policy" role="tab" data-toggle="tab"><?php echo LANG_VALUE_62; ?></a></li>
                                <li role="presentation"><a href="#review" aria-controls="review" role="tab" data-toggle="tab"><?php echo LANG_VALUE_63; ?></a></li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
									<p>
                                        <?php
if ($p_description == '') {
    echo LANG_VALUE_70;
} else {
    echo $p_description;
}
?>
									</p>
								</div>
                               
                               
                                <div role="tabpanel" class="tab-pane" id="return_policy" style="margin-top: -30px;">
                                    <p>
                                        <?php
if ($p_return_policy == '') {
   $i = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_post WHERE post_id ='11' ");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $i++;
    if ($i > 7) {
        break;
    }
    ?>
				<li>Click to read our <a href="blog-single.php?slug=<?php echo $row['post_slug']; ?>"><?php echo $row['post_title']; ?></a></li>
				                <?php
}


} else {
    echo $p_return_policy;
}
?>
                                    </p>
                                </div>
								<div role="tabpanel" class="tab-pane" id="review" style="margin-top: -30px;">

                                    <div class="review-form">
                                        <?php
$statement = $pdo->prepare("SELECT *
                                                            FROM tbl_rating t1
                                                            JOIN tbl_customer t2
                                                            ON t1.cust_id = t2.cust_id
                                                            WHERE t1.p_id=?");
$statement->execute(array($_REQUEST['id']));
$total = $statement->rowCount();
?>
                                        <h2><?php echo LANG_VALUE_63; ?> (<?php echo $total; ?>)</h2>
                                        <?php
if ($total) {
    $j = 0;
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $j++;
        ?>
                                                <div class="mb_10"><b><u><?php echo LANG_VALUE_64; ?> <?php echo $j; ?></u></b></div>
                                                <table class="">
                                                    <tr>
                                                        <th style="width:170px;"><?php echo LANG_VALUE_75; ?></th>
                                                        <td><?php echo $row['cust_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_76; ?></th>
                                                        <td><?php echo $row['comment']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_78; ?></th>
                                                        <td>
                                                            <div class="rating" style="color: #FFD700 !important;">
                                                                <?php
for ($i = 1; $i <= 5; $i++) {
            ?>
                                                                    <?php if ($i > $row['rating']): ?>
                                                                        <i class="fa fa-star-o"></i>
                                                                    <?php else: ?>
                                                                        <i class="fa fa-star"></i>
                                                                    <?php endif;?>
                                                                    <?php
}
        ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php
}
} else {
    echo LANG_VALUE_74;
}
?>

                                        <h2><?php echo LANG_VALUE_65; ?></h2>
                                        <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}
if ($success_message != '') {
    echo "<script>alert('" . $success_message . "')</script>";
}
?>
                                        <?php if (isset($_SESSION['customer'])): ?>

                                            <?php
$statement = $pdo->prepare("SELECT *
                                                                FROM tbl_rating
                                                                WHERE p_id=? AND cust_id=?");
$statement->execute(array($_REQUEST['id'], $_SESSION['customer']['cust_id']));
$total = $statement->rowCount();
?>
                                            <?php if ($total == 0): ?>
                                            <form action="" method="post">
                                            <div class="rating-section">
                                                <input type="radio" name="rating" class="rating" value="1" checked>
                                                <input type="radio" name="rating" class="rating" value="2" checked>
                                                <input type="radio" name="rating" class="rating" value="3" checked>
                                                <input type="radio" name="rating" class="rating" value="4" checked>
                                                <input type="radio" name="rating" class="rating" value="5" checked>
                                            </div>
                                            <div class="form-group">
                                                <textarea name="comment" class="form-control" cols="30" rows="10" placeholder="Write your comment (optional)" style="height:100px;"></textarea>
                                            </div>
                                            <input type="submit" class="btn btn-default" name="form_review" value="<?php echo LANG_VALUE_67; ?>">
                                            </form>
                                            <?php else: ?>
                                                <span style="color:red;"><?php echo LANG_VALUE_68; ?></span>
                                            <?php endif;?>


                                        <?php else: ?>
                                            <p class="error">
												<?php echo LANG_VALUE_69; ?> <br>
												<a href="login.php" style="color:red;text-decoration: underline;"><?php echo LANG_VALUE_9; ?></a>
											</p>
                                        <?php endif;?>
                                    </div>

								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<div class="product bg-gray pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo LANG_VALUE_155; ?></h2>
                    <h3><?php echo LANG_VALUE_156; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">

                    <?php
                    $statement3 = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id=? AND ecat_id!=?");
$statement3->execute(array($mcat_id, $_REQUEST['id']));
$result3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
foreach ($result3 as $row3) {
    $mid_cat = $row3['ecat_id'];

$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ecat_id=? AND p_id!=? AND p_qty>0");
$statement->execute(array($mid_cat, $_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    ?>
                        <div class="item">
                        <div class="thumb">
                                <a href="product.php?id=<?php echo $row['p_id']; ?>">
                                                    <div class="photo"  style="background-image:url(assets/uploads/<?php echo $row['p_featured_photo']; ?>);"></div>
                                                </a>
                                               
                            </div>
                           
                            <div class="text">
                                <h3><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a></h3>
                                <h4>
                                     <?php echo '<span style="color:#228B22;">ETB </span>'; echo $row['p_current_price']; ?> 
                                    <!-- <?php //if($row['p_old_price'] != ''): ?>
                                    <del>
                                        <?php //echo LANG_VALUE_1; ?><?php //echo $row['p_old_price']; ?>
                                    </del>
                                    <?php //endif; ?> -->
                                </h4>
                                <div class="rating" style="color: #FFD700 !important;">
                                    <?php
$t_rating = 0;
    $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
    $statement1->execute(array($row['p_id']));
    $tot_rating = $statement1->rowCount();
    if ($tot_rating == 0) {
        $avg_rating = 0;
    } else {
        $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result1 as $row1) {
            $t_rating = $t_rating + $row1['rating'];
        }
        $avg_rating = $t_rating / $tot_rating;
    }
    ?>
                                    <?php
if ($avg_rating == 0) {
        echo '';
    } elseif ($avg_rating == 1.5) {
        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
    } elseif ($avg_rating == 2.5) {
        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
    } elseif ($avg_rating == 3.5) {
        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
    } elseif ($avg_rating == 4.5) {
        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
    } else {
        for ($i = 1; $i <= 5; $i++) {
            ?>
                                            <?php if ($i > $avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif;?>
                                            <?php
}
    }
    ?>
                                </div>
                                <p><a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo 'View Product'; ?></a></p>
                            </div>
                        </div>
                        <?php
}
}
?>

                </div>

            </div>
        </div>
    </div>
</div>
<?php
$statement = $pdo->prepare("SELECT * FROM lens_type_price WHERE lens_abbrv='pslwa'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $pslwa = $row['lens_price'];
    // $wlwa = $row['lens_price'];
    // $pslwablf = $row['pslwablf'];
    // $wlwablf = $row['wlwablf'];
    // $pl = $row['pl'];
    // $slp = $row['slp'];
    // $plp = $row['plp'];
  
}
?>
	<?php
$statement = $pdo->prepare("SELECT * FROM lens_type_price WHERE lens_abbrv='wlwa'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
     $wlwa = $row['lens_price'];  
}
?>
	<?php
$statement = $pdo->prepare("SELECT * FROM lens_type_price WHERE lens_abbrv='wlwablf'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
   $wlwablf = $row['lens_price'];
}
?>
	<?php
$statement = $pdo->prepare("SELECT * FROM lens_type_price WHERE lens_abbrv='pslwablf'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
  $pslwablf = $row['lens_price'];
}
?>
	<?php
$statement = $pdo->prepare("SELECT * FROM lens_type_price WHERE lens_abbrv='pl'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $pl = $row['lens_price'];
}
?>
	<?php
$statement = $pdo->prepare("SELECT * FROM lens_type_price WHERE lens_abbrv='slp'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $slp = $row['lens_price'];
}
?>
	<?php
$statement = $pdo->prepare("SELECT * FROM lens_type_price WHERE lens_abbrv='plp'");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $plp = $row['lens_price'];
}
?>
 <input type="hidden" name="pslwa" id="pslwa" value="<?php echo $pslwa; ?>">
  <input type="hidden" name="wlwa" id="wlwa" value="<?php echo $wlwa; ?>">
  <input type="hidden" name="pslwablf" id="pslwablf" value="<?php echo $pslwablf; ?>">
 <input type="hidden" name="wlwablf" id="wlwablf" value="<?php echo $wlwablf; ?>">
 <input type="hidden" name="pl" id="pl" value="<?php echo $pl; ?>">
 <input type="hidden" name="slp" id="slp" value="<?php echo $slp; ?>">
 <input type="hidden" name="plp" id="plp" value="<?php echo $plp; ?>"> 
 

<?php require_once 'footer.php';?>

<script src="assets/vision/vision.js"></script>
<script src="assets/vision/prescription-scanner.js"></script>
<script src="hide_show/show_cart.js"></script>

	<script type="text/javascript">
						if (getQueryString("errmsg") != '' && getQueryString("errmsg") != null) {
							alert(getQueryString("errmsg"))
						}
						var interval;
						$(function () {
							runIntervalInstance();
						});

						function runIntervalInstance() {
							clearIntervalInstance();
							interval = setInterval(function () {
								var timestamp = new Date().getTime();
								$("#timestamp").val(timestamp);
								$("#nonce").val(uuid());

								var nowDate = new Date();
								var year = nowDate.getFullYear();
								var month = nowDate.getMonth() + 1 < 10 ? "0" + (nowDate.getMonth() + 1)
									: nowDate.getMonth() + 1;
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
							}, function (res) {
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
<script>

 function pres_reset() {

                                
                                
     document.getElementById("ssph-right").selectedIndex = 0;
     document.getElementById("scyl-right").selectedIndex = 0;
     document.getElementById("saxis-right").selectedIndex = 0;
     document.getElementById("ssph-left").selectedIndex = 0;
     document.getElementById("scyl-left").selectedIndex = 0;
     document.getElementById("saxis-left").selectedIndex = 0;
     
     document.getElementById("psph-right").selectedIndex = 0;
     document.getElementById("pcyl-right").selectedIndex = 0;
     document.getElementById("paxis-right").selectedIndex = 0;
     document.getElementById("padd-right").selectedIndex = 0;
     document.getElementById("psph-left").selectedIndex = 0;
     document.getElementById("pcyl-left").selectedIndex = 0;
     document.getElementById("paxis-left").selectedIndex = 0;
     document.getElementById("padd-left").selectedIndex = 0;
     
    
     
     
}
      function p_pd_reset() {
     document.getElementById("p_one_pd").selectedIndex = 0;
     document.getElementById("p_two_pd_right").selectedIndex = 0;
     document.getElementById("p_two_pd_left").selectedIndex = 0;
     document.getElementById("p_two_pd_right_type").selectedIndex = 0;
     document.getElementById("p_two_pd_left_type").selectedIndex = 0;
}
   function s_pd_reset() {
     document.getElementById("s_one_pd").selectedIndex = 0;
     document.getElementById("s_two_pd_right").selectedIndex = 0;
     document.getElementById("s_two_pd_left").selectedIndex = 0;
      document.getElementById("s_two_pd_right_type").selectedIndex = 0;
     document.getElementById("s_two_pd_left_type").selectedIndex = 0;
}
    jQuery(function () {
  //main_prescription
  //progressive_pd
  jQuery("#cancel_some").click(function () {
    jQuery(".main_target").hide();
  });
  jQuery("#cancel").click(function () {
    jQuery(".main_target").hide();
  });
  jQuery(".main_btn").click(function () {
    jQuery(".main_target").hide();
    jQuery("#div" + $(this).attr("main_target")).show();
  });

  jQuery("#hideall").click(function () {
    jQuery(".target").hide();
  });

  jQuery(".Single").click(function () {
    jQuery(".target").hide();
    jQuery("#div" + $(this).attr("target")).show();
  });
  //single_pd
  jQuery("#hideallpd").click(function () {
    jQuery(".pdtarget").hide();
  });
  jQuery(".pd").click(function () {
    jQuery(".pdtarget").hide();
    jQuery("#div" + $(this).attr("pdtarget")).show();
  });
  //progressive_pd
  jQuery("#hideall_p_pd").click(function () {
    jQuery(".prog_pd_target").hide();
  });
  jQuery(".progressive_pd").click(function () {
    jQuery(".prog_pd_target").hide();
    jQuery("#div" + $(this).attr("prog_pd_target")).show();
  });
});

//name and price value passing method
var inputF = document.getElementById("cost_lens");
var inputG = document.getElementById("name_lens");
var show_lens_name = document.getElementById("disp_lens_name");
var fucking = 0;

function myFunction() {
  //   document.getElementById("selected_type").innerHTML = "Frame Only";
  inputF.value = "0";
  global_c.value = "0";
  inputG.value = "Frame Only";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}

// var el_down = document.getElementById("GFG_DOWN");

function antiblue() {
  // document.getElementById("selected_type").innerHTML = "Frame with Anti Blue Light Lens";
  // document.getElementById("type_price").innerHTML = "150";
   inputF.value = Number(document.getElementById("pslwa").value);
  inputG.value = "PHOTO SOLAR LENS WITH ANTI-GLARE FOR PC";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}
function tinted() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Tinted Lens";
  //   document.getElementById("type_price").innerHTML = "250";
 inputF.value = Number(document.getElementById("wlwa").value);
  inputG.value = "WHITE LENS WITH ANTIGLARE FOR PC";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;

  type_price = Number(inputF.value);
  sum_price();
}
function polarized() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Polarized Lens";
  //   document.getElementById("type_price").innerHTML = "350";
  inputF.value = Number(document.getElementById("pslwablf").value);
  inputG.value = "PHOTO SOLAR LENS WITH ANTI-GLARE AND BLUE LIGHT FILTER FOR PC";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}
function photochromic() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Photochromic Lens";
  //   document.getElementById("type_price").innerHTML = "450";
   inputF.value = Number(document.getElementById("wlwablf").value);
  inputG.value = "WHITE LENS WITH ANTI-GLARE AND BLUE LIGHT FILTER FOR PC";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}
function nightvision() {
  //   document.getElementById("selected_type").innerHTML = "Frame with Night Vision(Yellow) Lens";
  //   document.getElementById("type_price").innerHTML = "500";
  inputF.value = Number(document.getElementById("pl").value);
  inputG.value = "POLARIZED LENS";
  show_lens_name.innerHTML = "Selected Type: " + inputG.value;
  type_price = Number(inputF.value);
  sum_price();
}


function single_vision() {
   inputF.value = Number(document.getElementById("slp").value);
     type_price = Number(inputF.value);
  sum_price();
    // document.getElementById("vision_type").innerHTML = "Single  Vision";
}
function progressive_vision() {
   inputF.value = Number(document.getElementById("plp").value);
     type_price = Number(inputF.value);
  sum_price();
  //document.getElementById("vision_type").innerHTML = "Progressive Vision";
}

// function cart_button() {
  
//   var popup = document.getElementById("Item added to cart successfully.");
//   popup.classList.toggle("show");
// }

//single vision price out put box
var ssphright = document.getElementById("single_sph_right");
var ssphleft = document.getElementById("single_sph_left");
var scylright = document.getElementById("single_cyl_right");
var scylleft = document.getElementById("single_cyl_left");
var saxisright = document.getElementById("single_axis_right");
var saxisleft = document.getElementById("single_axis_left");
//progressive vision price out put box
var psphright = document.getElementById("progressive_sph_right");
var psphleft = document.getElementById("progressive_sph_left");
var pcylright = document.getElementById("progressive_cyl_right");
var pcylleft = document.getElementById("progressive_cyl_left");
var paxisright = document.getElementById("progressive_axis_right");
var paxisleft = document.getElementById("progressive_axis_left");
var paddright = document.getElementById("progressive_add_right");
var paddleft = document.getElementById("progressive_add_left");

//pd number out put boxes

var pd_one = document.getElementById("one_pd_data");
var pd_two_right = document.getElementById("two_pd_data_right");
var pd_two_left = document.getElementById("two_pd_data_left");

//Lens type price

//  var price_of_lens = 0;
//   //  price_of_lens = document.getElementById("cost_lens").value;

//     function one(){
//           var bb = document.getElementById("cost_lens").value;
//             price_of_lens = bb;
//             sum_price();
//     }

//price variables
var global_p = document.getElementById("disp_vision_price");
var ssr = 0; //single sph right
var ssl = 0; //single sph left
var scr = 0; //single cyl right
var scl = 0; //single cyl left
var sal = 0; //single axis left
var sar = 0; //single axis right

var psr = 0; //progressive sph right
var psl = 0; //progressive sph left
var pcr = 0; //progressive cyl right
var pcl = 0; //progressive cyl left
var pal = 0; //progressive axis left
var par = 0; //progressive axis right
var padl = 0; //progressive add left
var padr = 0; //progressive add right

<!--// PD Numbers-->
<!--function one_pd() {-->
<!--  var details = document.getElementById("ssph-right").value;-->
<!--  var allData = details.split("||");-->
<!--  //inputF.value = allData[0];-->
<!--  pd_one.value = details;-->
<!--  //ssr = Number(ssphright.value);-->
<!--  //sum_price();-->
<!--}-->
<!--function two_pd_right() {-->
<!--  var details = document.getElementById("ssph-right").value;-->
<!--  var allData = details.split("||");-->
<!--  //inputF.value = allData[0];-->
<!--  ssphright.value = allData[1];-->
<!--  ssr = Number(ssphright.value);-->
<!--  sum_price();-->
<!--}-->
<!--function two_pd_left() {-->
<!--  var details = document.getElementById("ssph-right").value;-->
<!--  var allData = details.split("||");-->
<!--  //inputF.value = allData[0];-->
<!--  ssphright.value = allData[1];-->
<!--  ssr = Number(ssphright.value);-->
<!--  sum_price();-->
<!--}-->

//single vision
function set_sph_right() {
  var details = document.getElementById("ssph-right").value;
  var allData = details.split("||");
  //inputF.value = allData[0];
  ssphright.value = allData[1];
  ssr = Number(ssphright.value);
  sum_price();
}
function set_sph_left() {
  var details = document.getElementById("ssph-left").value;
  var allData = details.split("||");
  //ssphright.value = allData[0];
  ssphleft.value = allData[1];
  ssl = Number(ssphleft.value);
  sum_price();
}
function set_cyl_right() {
  var details = document.getElementById("scyl-right").value;
  var allData = details.split("||");
  //inputF.value = allData[0];
  scylright.value = allData[1];
  scr = Number(scylright.value);
  sum_price();
}
function set_cyl_left() {
  var details = document.getElementById("scyl-left").value;
  var allData = details.split("||");
  //  inputF.value = allData[0];
  scylleft.value = allData[1];
  scl = Number(scylleft.value);
  sum_price();
}

function set_axis_right() {
  var details = document.getElementById("saxis-right").value;
  var allData = details.split("||");
  //inputF.value = allData[0];
  saxisright.value = allData[1];
  sar = Number(saxisright.value);
  sum_price();
}

function set_axis_left() {
  var details = document.getElementById("saxis-left").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  saxisleft.value = allData[1];
  sal = Number(saxisleft.value);

  sum_price();
}

//progressive vision
function p_set_sph_right() {
  var details = document.getElementById("psph-right").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  psphright.value = allData[1];
  psr = Number(psphright.value);

  sum_price();
}
function p_set_sph_left() {
  var details = document.getElementById("psph-left").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  psphleft.value = allData[1];
  psl = Number(psphleft.value);

  sum_price();
}
function p_set_cyl_right() {
  var details = document.getElementById("pcyl-right").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  pcylright.value = allData[1];
  pcr = Number(pcylright.value);

  sum_price();
}
function p_set_cyl_left() {
  var details = document.getElementById("pcyl-left").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  pcylleft.value = allData[1];
  pcr = Number(pcylleft.value);

  sum_price();
}
function p_set_axis_right() {
  var details = document.getElementById("paxis-right").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  paxisright.value = allData[1];
  par = Number(paxisright.value);

  sum_price();
}
function p_set_axis_left() {
  var details = document.getElementById("paxis-left").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  paxisleft.value = allData[1];
  pal = Number(paxisleft.value);

  sum_price();
}
function p_set_add_right() {
  var details = document.getElementById("padd-right").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  paddright.value = allData[1];
  padr = Number(paddright.value);

  sum_price();
}
function p_set_add_left() {
  var details = document.getElementById("padd-left").value;
  var allData = details.split("||");
  // inputF.value = allData[0];
  paddleft.value = allData[1];
  padl = Number(paddleft.value);

  sum_price();
}

var main_price = 0;
var type_price = 0;
var cart_price;
var global_c = document.getElementById("calculated_price");
// var cancel_id_hoo = document.getElementById("calculated_price");

function cart_total_price (){

}

function sum_price() {
  //main price value
  main_price = Number(document.getElementById("p_current_price").value);
  var local_main_price = main_price;
  //lens type price
  type_price = Number(document.getElementById("cost_lens").value);
  var local_type_price = type_price;

  //single vision                 //progressive vision
  var local_ssr = ssr;
  var local_psr = psr;
  var local_ssl = ssl;
  var local_psl = psl;
  var local_scr = scr;
  var local_pcr = pcr;
  var local_scl = scl;
  var local_pcl = pcr;
  var local_sar = sar;
  var local_par = par;
  var local_sal = sal;
  var local_pal = pal;
  var local_padr = padr;
  var local_padl = padl;

  var single_sph = local_ssr + local_ssl;
  var single_cyl = local_scr + local_scl;

  var progressive_sph = local_psr + local_psl;
  var progressive_cyl = local_pcr ;
  var progressive_axis = local_par + local_pal;
  var progressive_add = local_padr + local_padl;

  var single_vision_price = single_sph + single_cyl + local_sar + local_sal;
  var progressive_vision_price =
    progressive_sph + progressive_cyl + progressive_axis + progressive_add;

  // global_p.value = local_price_of_lens;
  global_p.value =
    single_vision_price +
    local_main_price +
    progressive_vision_price +
    local_type_price;
    global_c.value =
    single_vision_price + progressive_vision_price + local_type_price;

    document.getElementById("new_new").innerHTML = global_p.value ;
    document.getElementById("etb").innerHTML = "ETB ";
}

//refresh page
function refreshPage() {
  window.location.reload();
}
function refreshPage2() {
  window.location.reload();
}
</script>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>


