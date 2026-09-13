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



<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php require_once('customer-sidebar.php'); ?>
            </div>
            <div class="col-md-12">
                <div class="user-content">
                    <h3 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?php echo LANG_VALUE_25; ?></h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo LANG_VALUE_7; ?></th>
                                    <th><?php echo LANG_VALUE_48; ?></th>
                                    <th><?php echo LANG_VALUE_27; ?></th>
                                    <th><?php echo LANG_VALUE_29; ?></th>
                                    <th><?php echo LANG_VALUE_30; ?></th>
                                    <th><?php echo 'Shipping Status'; ?></th>
                                    <th><?php echo LANG_VALUE_31; ?></th>
                                    <!-- <th><?php echo LANG_VALUE_32; ?></th> -->
                                     <th><?php echo 'Receipt'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                              <form action="" method="post">


            <?php
            /* ===================== Pagination Code Starts ================== */
            $adjacents = 5;

            $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_phone=? ORDER BY id DESC");
            $statement->execute(array($_SESSION['customer']['cust_phone']));
            $total_pages = $statement->rowCount();

            $targetpage = BASE_URL.'customer-order.php';
            $limit = 10;
            $page = @$_GET['page'];
            if($page) 
                $start = ($page - 1) * $limit;
            else
                $start = 0;
            
            
            $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_phone=? AND payment_status='Completed' ORDER BY id DESC LIMIT $start, $limit");
            $statement->execute(array($_SESSION['customer']['cust_phone']));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
           
            
            if ($page == 0) $page = 1;
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = ceil($total_pages/$limit);
            $lpm1 = $lastpage - 1;   
            $pagination = "";
            if($lastpage > 1)
            {   
                $pagination .= "<div class=\"pagination\">";
                if ($page > 1) 
                    $pagination.= "<a href=\"$targetpage?page=$prev\">&#171; previous</a>";
                else
                    $pagination.= "<span class=\"disabled\">&#171; previous</span>";    
                if ($lastpage < 7 + ($adjacents * 2))
                {   
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                    }
                }
                elseif($lastpage > 5 + ($adjacents * 2))
                {
                    if($page < 1 + ($adjacents * 2))        
                    {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
                    }
                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                    {
                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                        $pagination.= "...";
                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                        $pagination.= "...";
                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";       
                    }
                    else
                    {
                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                        $pagination.= "...";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $page)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";                 
                        }
                    }
                }
                if ($page < $counter - 1) 
                    $pagination.= "<a href=\"$targetpage?page=$next\">next &#187;</a>";
                else
                    $pagination.= "<span class=\"disabled\">next &#187;</span>";
                $pagination.= "</div>\n";       
            }
            /* ===================== Pagination Code Ends ================== */
            ?>


                                <?php
                                $tip = $page*10-10;
                                foreach ($result as $row) {
                                    $tip++;
                                    ?>
                                    <tr id="1">
                                        <td><?php echo $tip; ?></td>
                                        <td class="row-data">
                                            <?php
                                            $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                                            $statement1->execute(array($row['payment_id']));
                                            $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result1 as $row1) {
                                               echo '<b>Product Name:</b> ' . $row1['product_name'];
echo '<br>(<b>Size:</b> ' . $row1['size'];
echo ', <b>Color:</b> ' . $row1['color'] . ')';
echo '<br>(<b>Lens Type:</b> ' . $row1['lens_type'] . ')<br>';
echo '<br>(<b>Single Vision:</b> ' . $row1['s_sph_right'] . $row1['s_sph_left'] . $row1['s_cyl_right'] . $row1['s_cyl_left'] . $row1['s_axis_right'] . $row1['s_axis_left'];
echo '<br><b>Lens Type Remark:</b> ' . $row1['s_photosolar_check'] . $row1['s_photochromic_check'] . $row1['s_white_check'] . $row1['s_plasctic_check'] . $row1['s_sunsensor_check'] . $row1['s_glarefree_check'] . $row1['s_antiglare_check'] . $row1['s_arc_check'] . $row1['s_hmc_check'] . $row1['s_progressivelens_check'] . $row1['s_bicfocal_check'] . $row1['s_scratchresistant_check'] . ')<br>';

echo '<br>(<b>Progressive Vision:</b> ' . $row1['p_sph_right'] . $row1['p_sph_left'] . $row1['p_cyl_right'] . $row1['p_cyl_left'] . $row1['p_axis_right'] . $row1['p_axis_left'] . $row1['p_add_right'] . $row1['p_add_left'] . '';
echo '<br><b>Lens Type Remark:</b> ' . $row1['p_photosolar_check'] . $row1['p_photochromic_check'] . $row1['p_white_check'] . $row1['p_plasctic_check'] . $row1['p_sunsensor_check'] . $row1['p_glarefree_check'] . $row1['p_antiglare_check'] . $row1['p_arc_check'] . $row1['p_hmc_check'] . $row1['p_progressivelens_check'] . $row1['p_bicfocal_check'] . $row1['p_scratchresistant_check'] . ')<br>';

echo '<br>(<b>PD Numbers</b> ' . $row1['s_one_pd'] . $row1['s_two_pd_right'] . $row1['s_two_pd_left'] . $row1['p_one_pd'] . $row1['p_two_pd_right'] . $row1['p_two_pd_left'] . ')';
echo '<br>(<b>Quantity:</b> ' . $row1['quantity'] . ')';
echo '<br>(<b>Additional Detail:</b> ' . $row1['add_detail'] . ')';

echo '<br><br>';

                                            }
                                            ?>
                                        </td>
                                        <td class="row-data"><?php echo $row['payment_date']; ?></td>
                                        <td><?php echo $row['paid_amount']; ?></td>
                                        <td><?php echo $row['payment_status']; ?></td>
                                        <td><?php echo $row['shipping_status']; ?></td>
                                        <td><?php if($row['payment_method'] == 'Telebirr'): ?>
                                                <b>Payment Method:</b> <?php echo '<span style="color:green;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                                                <b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
                                                <b>Date:</b> <?php echo $row['payment_date']; ?><br>
                                            <?php endif; ?>
                                          </td>
                                        <!-- <td class="row-data"><?php echo $row['payment_id'];?></td> -->
                                        <td><a type="button" class="btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0 bg-yellow-400" href="customer_receipt.php?payment_id=<?php echo $row['payment_id']; ?>">View Receipt</a></td>
                                                                              
                                        
                                    </tr>
                                    <?php
                                }
                                ?>                               
                                </form>
                            </tbody>
                        </table>
                        <div class="pagination" style="overflow: hidden;">
                        <?php 
                            echo $pagination; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
