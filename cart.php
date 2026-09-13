<?php require_once 'header.php';?>
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
  margin-top: -.10rem;
  font-size: 2rem;
}

.modal-content {
  max-height: 600px;
  overflow: auto;
  padding:20px
}
  </style>

<?php
if (isset($_POST['see_frame_form'])) {

$valid = 1;
if (empty($_POST['cust_name'])) {
    $valid = 0;
    $error_message = 'Name cannot be empty!';
}
if (empty($_POST['cust_phone'])) {
    $valid = 0;
    $error_message = 'Phone cannot be empty!';
}
if (empty($_POST['cust_address'])) {
    $valid = 0;
    $error_message .= 'Address cannot be empty!';
}
if ($_POST['frame_name'] == 'empty'){
    $valid = 0;
    $error_message .= 'No Frame selected! Please go back and select a Frame to see !';
}




 if($valid == 1) {
$cust_datetime = date('Y-m-d h:i:s');
$cust_timestamp = time();

$statement = $pdo->prepare("INSERT INTO tbl_see_frame (
                                        cust_name,
                                        cust_phone,
                                        cust_address,
                                        frame_name,

                                        cust_datetime,
                                        cust_timestamp
                                    ) VALUES (?,?,?,?,?,?)");
$statement->execute(array(
    strip_tags($_POST['cust_name']),
    strip_tags($_POST['cust_phone']),
    strip_tags($_POST['cust_address']),
    strip_tags($_POST['frame_name']),

    $cust_datetime,
    $cust_timestamp,
));
$success_message .= 'We will call and show you your frame!';

}
}
?>


<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_cart = $row['banner_cart'];
}
?>

<?php

if (isset($_POST['form1'])) {

    $i = 0;
    $statement = $pdo->prepare("SELECT * FROM tbl_product");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $i++;
        $table_product_id[$i] = $row['p_id'];
        $table_quantity[$i] = $row['p_qty'];
        $prod_id = $row['p_id'];

    }

    $i = 0;
    foreach ($_POST['product_id'] as $val) {
        $i++;
        $arr1[$i] = $val;
    }
    $i = 0;
    foreach ($_POST['quantity'] as $val) {
        $i++;
        $arr2[$i] = $val;
    }
    $i = 0;
    foreach ($_POST['product_name'] as $val) {
        $i++;
        $arr3[$i] = $val;
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

//additional detail
    $i = 0;
    foreach ($_SESSION['cart_add_detail'] as $key => $value) {
        $i++;
        $arr_add_detail[$i] = $value;
    }

 //progressive lens type remark
$i = 0;
foreach ($_SESSION['p_photosolar_check'] as $key => $value) {
    $i++;
    $arr_p_photosolar_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_photochromic_check'] as $key => $value) {
    $i++;
    $arr_p_photochromc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_white_check'] as $key => $value) {
    $i++;
    $arr_p_white_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_plasctic_check'] as $key => $value) {
    $i++;
    $arr_p_plascitlens_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['p_sunsensor_check'] as $key => $value) {
    $i++;
    $arr_p_sunsensor_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_glarefree_check'] as $key => $value) {
    $i++;
    $arr_p_glarefree_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_antiglare_check'] as $key => $value) {
    $i++;
    $arr_p_antiglare_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_arc_check'] as $key => $value) {
    $i++;
    $arr_p_arc_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['p_hmc_check'] as $key => $value) {
    $i++;
    $arr_p_hmc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_progressivelens_check'] as $key => $value) {
    $i++;
    $arr_p_progressivelens_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_bicfocal_check'] as $key => $value) {
    $i++;
    $arr_p_bicfocal_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_scratchresistant_check'] as $key => $value) {
    $i++;
    $arr_p_scratchresistant_check[$i] = $value;

}
//single lens type remark
$i = 0;
foreach ($_SESSION['s_photosolar_check'] as $key => $value) {
    $i++;
    $arr_s_photosolar_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_photochromic_check'] as $key => $value) {
    $i++;
    $arr_s_photochromc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_white_check'] as $key => $value) {
    $i++;
    $arr_s_white_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_plasctic_check'] as $key => $value) {
    $i++;
    $arr_s_plascitlens_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['s_sunsensor_check'] as $key => $value) {
    $i++;
    $arr_s_sunsensor_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_glarefree_check'] as $key => $value) {
    $i++;
    $arr_s_glarefree_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_antiglare_check'] as $key => $value) {
    $i++;
    $arr_s_antiglare_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_arc_check'] as $key => $value) {
    $i++;
    $arr_s_arc_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['s_hmc_check'] as $key => $value) {
    $i++;
    $arr_s_hmc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_progressivelens_check'] as $key => $value) {
    $i++;
    $arr_s_progressivelens_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_bicfocal_check'] as $key => $value) {
    $i++;
    $arr_s_bicfocal_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_scratchresistant_check'] as $key => $value) {
    $i++;
    $arr_s_scratchresistant_check[$i] = $value;
}




    //prescription_photo
     $i = 0;
    foreach ($_SESSION['pres_photo_upload'] as $key => $value) {
        $i++;
        $arr_pres_photo[$i] = $value;
    }

  

///
    $allow_update = 1;
    for ($i = 1; $i <= count($arr1); $i++) {
        for ($j = 1; $j <= count($table_product_id); $j++) {
            if ($arr1[$i] == $table_product_id[$j]) {
                $temp_index = $j;
                break;
            }
        }
        if ($table_quantity[$temp_index] < $arr2[$i]) {
            $allow_update = 0;
            $error_message .= '"' . $arr2[$i] . '" items are not available for "' . $arr3[$i] . '"\n';
        } else {
            $_SESSION['cart_p_qty'][$i] = $arr2[$i];
        }
    }
    $error_message .= '\nOther items quantity are updated successfully!';
    ?>

    <?php if ($allow_update == 0): ?>
    	<script>alert('<?php echo $error_message; ?>');</script>
	<?php else: ?>
		<script>alert('All Items Quantity Update is Successful!');</script>
	<?php endif;?>
    <?php

}
?>

         <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}
if ($success_message != '') {
    echo "<script>alert('We will call and show you your frame!');
l</script>";
    //  header('location: product.php?id=' . $_REQUEST['id']);
}
?>

<div class="page-banner" style="height:50px; background-image: url(assets/uploads/<?php echo $banner_cart; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?php echo LANG_VALUE_18; ?></h1>
    </div>
</div>

<div class="page" >
	<div class="container">
		<div class="row">
			<div class="col-md-12">

                <?php if (!isset($_SESSION['cart_p_id'])): ?>
                    <?php echo 'Cart is empty'; ?>
                <?php else: ?>
                <form action="" method="post">
                    <?php $csrf->echoInputField();?>
				<div class="cart">
                    <div class="table-responsive">
                       <table class="table table-bordered">

                        <tr>
                            <th><?php echo 'Id'; ?></th>
                            <th><?php echo LANG_VALUE_8; ?></th>
                            <th><?php echo LANG_VALUE_47; ?></th>
                            <th><?php echo LANG_VALUE_157; ?></th>
                            <th><?php echo LANG_VALUE_158; ?></th>
                            <th><?php echo 'Frame price'; ?></th>
                            <th><?php echo 'Quantity'; ?></th>
                           
                            <th>Lens Type </th>
                            <th>Prescription Type</th>
                            <!-- <th>Prescription price</th> -->
                            <th class="text-right"><?php echo 'Row Total'; ?></th>
                            <th class="text-center" style="width: 100px;"><?php echo LANG_VALUE_83; ?></th>
                        </tr>
                        <?php
$table_total_price = 0;

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
    

    
    //progressive lens type remark
$i = 0;
foreach ($_SESSION['p_photosolar_check'] as $key => $value) {
    $i++;
    $arr_p_photosolar_check[$i] = $value;  

}
$i = 0;
foreach ($_SESSION['p_photochromic_check'] as $key => $value) {
    $i++;
    $arr_p_photochromc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_white_check'] as $key => $value) {
    $i++;
    $arr_p_white_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_plasctic_check'] as $key => $value) {
    $i++;
    $arr_p_plascitlens_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['p_sunsensor_check'] as $key => $value) {
    $i++;
    $arr_p_sunsensor_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_glarefree_check'] as $key => $value) {
    $i++;
    $arr_p_glarefree_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_antiglare_check'] as $key => $value) {
    $i++;
    $arr_p_antiglare_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_arc_check'] as $key => $value) {
    $i++;
    $arr_p_arc_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['p_hmc_check'] as $key => $value) {
    $i++;
    $arr_p_hmc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_progressivelens_check'] as $key => $value) {
    $i++;
    $arr_p_progressivelens_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_bicfocal_check'] as $key => $value) {
    $i++;
    $arr_p_bicfocal_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['p_scratchresistant_check'] as $key => $value) {
    $i++;
    $arr_p_scratchresistant_check[$i] = $value;

}

//single lens type remark
$i = 0;
foreach ($_SESSION['s_photosolar_check'] as $key => $value) {
    $i++;
    $arr_s_photosolar_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_photochromic_check'] as $key => $value) {
    $i++;
    $arr_s_photochromc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_white_check'] as $key => $value) {
    $i++;
    $arr_s_white_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_plasctic_check'] as $key => $value) {
    $i++;
    $arr_s_plascitlens_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['s_sunsensor_check'] as $key => $value) {
    $i++;
    $arr_s_sunsensor_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_glarefree_check'] as $key => $value) {
    $i++;
    $arr_s_glarefree_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_antiglare_check'] as $key => $value) {
    $i++;
    $arr_s_antiglare_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_arc_check'] as $key => $value) {
    $i++;
    $arr_s_arc_check[$i] = $value;

}

$i = 0;
foreach ($_SESSION['s_hmc_check'] as $key => $value) {
    $i++;
    $arr_s_hmc_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_progressivelens_check'] as $key => $value) {
    $i++;
    $arr_s_progressivelens_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_bicfocal_check'] as $key => $value) {
    $i++;
    $arr_s_bicfocal_check[$i] = $value;

}
$i = 0;
foreach ($_SESSION['s_scratchresistant_check'] as $key => $value) {
    $i++;
    $arr_s_scratchresistant_check[$i] = $value;
}
////////////////////
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

//final price
$i = 0;
foreach ($_SESSION['cart_final_price'] as $key => $value) {
    $i++;
    $arr_final_price[$i] = $value;
}
$i = 0;
foreach ($_SESSION['cart_frame_price'] as $key => $value) {
    $i++;
    $arr_cart_frame_price[$i] = $value;
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

$i = 0;
foreach ($_SESSION['cart_p_current_price'] as $key => $value) {
    $i++;
    $arr_cart_p_current_price[$i] = $value;
}

$i = 0;
foreach ($_SESSION['cart_p_name'] as $key => $value) {
    $i++;
    $arr_cart_p_name[$i] = $value;
    $name_pr =  $arr_cart_p_name[$i];

}

$i = 0;
foreach ($_SESSION['cart_p_featured_photo'] as $key => $value) {
    $i++;
    $arr_cart_p_featured_photo[$i] = $value;
}
?>
                        <?php for ($i = 1; $i <= count($arr_cart_p_id); $i++): ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="">
                            </td>
                            <td><?php echo $arr_cart_p_name[$i];?></td>
                            <td><?php echo $arr_cart_size_name[$i]; ?></td>
                            <td><?php echo $arr_cart_color_name[$i]; ?></td>

                            <td><?php echo 'ETB '; ?><?php echo $arr_cart_frame_price[$i]; ?></td>
                            <td>
                                <input type="hidden" name="product_id[]" value="<?php echo $arr_cart_p_id[$i]; ?>">
                                <input type="hidden" name="product_name[]" value="<?php echo $arr_cart_p_name[$i]; ?>">
                                <input type="number" class="input-text qty text" step="1" min="1" max="" name="quantity[]" value="<?php echo $arr_cart_p_qty[$i]; ?>" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                            </td>
                           
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
?>
                                    <br>
                                    <br>
                                    <?php
echo $arr_s_one_pd[$i];
echo '<br>';
echo $arr_s_two_pd_right[$i];
echo $arr_s_two_pd_left[$i];
echo $arr_s_two_pd_right_type[$i];
echo $arr_s_two_pd_left_type[$i];
?>
     <br>                             <?php
echo $arr_s_photosolar_check[$i];
echo $arr_s_photochromc_check[$i];
echo $arr_s_white_check[$i];
echo $arr_s_plascitlens_check[$i];

echo $arr_s_sunsensor_check[$i];
echo $arr_s_glarefree_check[$i];
echo $arr_s_antiglare_check[$i];
echo $arr_s_arc_check[$i];

echo $arr_s_hmc_check[$i];
echo $arr_s_progressivelens_check[$i];
echo $arr_s_bicfocal_check[$i];
echo $arr_s_scratchresistant_check[$i];


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

?><br>
                                    <?php
echo $arr_one_pd[$i];
echo '<br>';
echo $arr_two_pd_right[$i];
echo $arr_two_pd_left[$i];
echo $arr_p_two_pd_right_type[$i];
echo $arr_p_two_pd_left_type[$i];
?>
       <br>                             <?php

echo $arr_p_photosolar_check[$i];
echo $arr_p_photochromc_check[$i];
echo $arr_p_white_check[$i];
echo $arr_p_plascitlens_check[$i];

echo $arr_p_sunsensor_check [$i];
echo $arr_p_glarefree_check [$i];
echo $arr_p_antiglare_check [$i];
echo $arr_p_arc_check [$i];

echo $arr_p_hmc_check [$i];
echo $arr_p_progressivelens_check [$i];
echo $arr_p_bicfocal_check [$i];
echo $arr_p_scratchresistant_check [$i];

?>


                            </td>
                             <!-- <td><?php 
                            //  $vision_price = $arr_single_sph_right_price[$i] +
    // $arr_single_sph_left_price[$i] +
    // $arr_single_cyl_right_price[$i] +
    // $arr_single_cyl_left_price[$i] +
    // $arr_single_axis_right_price[$i] +
    // $arr_single_axis_left_price[$i] +
    // $arr_progressive_sph_left_price[$i];

// echo $arr_final_price[$i];

?></td> -->
                            <td class="text-right">
                                <?php
$row_total_price = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i];

$table_total_price = $table_total_price + $row_total_price;
?>
                                <?php echo 'ETB '; ?><?php echo $row_total_price; ?>
                            </td>
                            <td class="text-center">
                                <a onclick="return confirmDelete();" href="cart-item-delete.php?id=<?php echo $arr_cart_p_id[$i]; ?>&no=<?php echo $i;?>&size=<?php echo $arr_cart_size_id[$i]; ?>&color=<?php echo $arr_cart_color_id[$i]; ?>" class="trash"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                             <th colspan="" class="total-text">Additional Information</th>
                            
                                  <td colspan="10" >
                                  <?php  echo $arr_add_detail[$i]; ?>
                                  </td>
                             
                        </tr>

                        <?php endfor;?>
                        <tr>
                             <!-- <th colspan="7" class="total-text">Total</th> -->
                            <th colspan="10" class="total-text">Total</th>
                            <th class="total-amount"><?php echo 'ETB '; ?><?php echo $table_total_price; ?></th>

                        </tr>
                        
                    </table>
                </div>
            </div>

                   <div class="cart-buttons" style="background-color: transparent !important;">
                        <ul>
                           <!-- <li><div>
                            </div></li>
                            <li><div class="btn" style="background-color: #373737 !important; margin:5px;  color: white !important;" >
                            </div></li>
                         <li>   <div class="btn" style="background-color: #373737 !important; margin:5px;  " >
                            </div></li> -->


                        </ul>
                    </div>
                     <div class="col-md-8 col-sm-8 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <div class="feature-wrap">
                                <input type="submit" class="new_btn text-white hover:text-white bg-yellow-300  border border-yellow-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900" value="<?php echo LANG_VALUE_20; ?>" name="form1">

                        </div>
                    </div>

                </form>
                <?php endif;?>

                <div class="col-md-8 col-sm-8 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                        <div class="feature-wrap">
                             <button  id="shop" type="button" class="new_btn text-white hover:text-white bg-yellow-300 border border-yellow-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-300 dark:focus:ring-yellow-900"><?php echo LANG_VALUE_85; ?></button>
                             <button   id="check" type="button" class="new_btn text-white hover:text-white bg-yellow-300 border border-yellow-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-300 dark:focus:ring-yellow-900"><?php echo LANG_VALUE_23; ?></button>
                             <button   id="see" type="button" class="new_btn text-white hover:text-white bg-yellow-300  border border-yellow-400 hover:bg-yellow-500 focus:ring-6 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xl px-5 py-2.5 text-center me-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-300 dark:focus:ring-yellow-900">See Frame First</button>

                             <!-- <a class="btn"  href="index.php" style="background-color:  #373737 !important;  margin:5px; color: white !important;" ><?php echo LANG_VALUE_85; ?></a>
                             <a class="btn"  href="checkout.php" style="background-color:  #373737 !important;  margin:5px; color: white !important;"  ><?php echo LANG_VALUE_23; ?></a>
                             <a class="btn"  href="see_frame.php" style="background-color: #008000 !important;  margin:5px; color: white !important;"  >See Frame First</a> -->

                           
                        </div>
                    </div>
                <!-- The Info -->

                  
                <div id="myModal"  style="" class="modal">

                <!-- Info content -->
                  <div class="modal-content" style="  flex-basis: 50%;
                                                                                padding: 1rem;
                                                                                background-color: #fff;
                                                                                border-radius: 3px;
                                                                                width: 400px;">
                                <span class="close">&times;</span>
                                    <form action="" method="post" >
                                       
                                         <input type="hidden" name="frame_name" value="<?php 
                                           
                                           $prod_name = 'empty';
                                           if (empty($name_pr)) {
                                             echo $prod_name; 
                                            } else{
                                                echo $name_pr;
                                            }
                                                 
                                         ?>">
                                        <div class="col-md-12 form-group">
                                            <label style="font-size: 30px;" for=""><?php echo 'See Frame'; ?></span></label><br>
                                           
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label for=""><?php echo 'Full Name'; ?></label>
                                            <input name="cust_name" class="form-control" cols="30" rows="10"></input>
                                        </div>
                                         <div class="col-md-12 form-group">
                                            <label for=""><?php echo 'Phone Number'; ?></label>
                                            <input name="cust_phone" class="form-control" cols="30" rows="10"></input>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label for=""><?php echo 'Address'; ?></label>
                                            <input name="cust_address" class="form-control" cols="30" rows="10"></input>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <input type="submit" class="btn btn-primary" value="<?php echo 'Submit'; ?>" name="see_frame_form">
                                        </div>
                                    </form>                                                                  
                     </div>
                </div>              
			</div>
		</div>
	</div>
</div>


<?php require_once 'footer.php';?>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

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
</script>


<script type="text/javascript">
    document.getElementById("shop").onclick = function () {
        location.href = "index.php";
    };
    document.getElementById("check").onclick = function () {
        location.href = "checkout.php";
    };
    document.getElementById("see").onclick = function () {
        location.href = "see_frame.php";
    };
</script>