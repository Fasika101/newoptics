<div class="user-sidebar">
    <ul>
        <li><a href="index.php"><?php echo "Home"; ?></a></li>
         <!--<li><a href="cart.php" class="fa fa-shopping-cart><?php echo 'Go to cart'; ?></a></li> -->
        <!-- <li><a href="customer-password-update.php"><?php echo LANG_VALUE_99; ?></a></li> -->
        <li><a href="customer-order.php"><?php echo LANG_VALUE_24; ?>
           
         <span style=".badge {
  padding-left: 9px;
  padding-right: 9px;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}

.label-warning[href],
.badge-warning[href] {
  background-color: #c67605;
}
#lblCartCount {
    font-size: 12px;
    background: #ff0000;
    color: #fff;
    padding: 0 5px;
    vertical-align: top;
    margin-left: -10px; 
}"class='badge badge-warning' id='lblCartCount'> <?php

                $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_phone=?  AND payment_status='Completed' ORDER BY id DESC");
                $statement->execute(array($_SESSION['customer']['cust_phone']));
                $countposts = $statement->rowCount();
              ?>
                <?php echo htmlentities($countposts); ?> 

</span>
					
					</a></li>  

        <!-- <li><a href="logout.php"><?php echo LANG_VALUE_14; ?></a></li> -->
        <!--<li><a href="customer-profile-update.php"><?php echo LANG_VALUE_117; ?></a></li>-->
        
        <li><a href="cart.php"><i data-count="2" class="fa fa-shopping-cart"></i> <?php echo 'cart'; ?> 
					
					<span style=".badge {
  padding-left: 9px;
  padding-right: 9px;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}

.label-warning[href],
.badge-warning[href] {
  background-color: #c67605;
}
#lblCartCount {
    font-size: 12px;
    background: #ff0000;
    color: #fff;
    padding: 0 5px;
    vertical-align: top;
    margin-left: -10px; 
}"class='badge badge-warning' id='lblCartCount'><?php
if (isset($_SESSION['cart_p_id'])) {
    $table_total_price = 0;
    $i = 0;
    foreach ($_SESSION['cart_p_qty'] as $key => $value) {
        $i++;
        $arr_cart_p_qty[$i] = $value;
        //$row_total_price = $arr_cart_p_qty[$i] ;
    }
    for ($i = 1; $i <= count($arr_cart_p_qty); $i++) {
        $row_total_price = $arr_cart_p_qty[$i];
        $table_total_price = $table_total_price + $row_total_price;
    }
    echo $table_total_price;
} else {
    echo '0';
}?>
 </span>
					
					</a></li>  
           <li><a href="promo_page.php"><?php echo 'Promo Page'; ?></a></li>
    </ul>
</div>



