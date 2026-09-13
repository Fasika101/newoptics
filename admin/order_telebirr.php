<?php require_once('header.php'); ?>
<style>








</style>
<?php
$error_message = '';
if(isset($_POST['form1'])) {
    $valid = 1;
    if(empty($_POST['subject_text'])) {
        $valid = 0;
        $error_message .= 'Subject can not be empty\n';
    }
    if(empty($_POST['message_text'])) {
        $valid = 0;
        $error_message .= 'Subject can not be empty\n';
    }
    if($valid == 1) {

        $subject_text = strip_tags($_POST['subject_text']);
        $message_text = strip_tags($_POST['message_text']);

        // Getting Customer Email Address
        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=?");
        $statement->execute(array($_POST['cust_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $cust_email = $row['cust_email'];
            $cust_name = $row['cust_name'];
        }

        // Getting Admin Email Address
        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $admin_email = $row['contact_email'];
        }

        $order_detail = '';
        $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=?");
        $statement->execute(array($_POST['payment_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
        	
        	if($row['payment_method'] == 'PayPal'):
        		$payment_details = '
Transaction Id: '.$row['txnid'].'<br>
        		';
        	elseif($row['payment_method'] == 'Stripe'):
				$payment_details = '
Transaction Id: '.$row['txnid'].'<br>
Card number: '.$row['card_number'].'<br>
Card CVV: '.$row['card_cvv'].'<br>
Card Month: '.$row['card_month'].'<br>
Card Year: '.$row['card_year'].'<br>
        		';
        	elseif($row['payment_method'] == 'Bank Deposit'):
				$payment_details = '
Transaction Details: <br>'.$row['bank_transaction_info'];
        	endif;

            $order_detail .= '
Customer Name: '.$row['customer_name'].'<br>
Customer Email: '.$row['customer_email'].'<br>
Payment Method: '.$row['payment_method'].'<br>
Payment Date: '.$row['payment_date'].'<br>
Payment Details: <br>'.$payment_details.'<br>
Paid Amount: '.$row['paid_amount'].'<br>
Payment Status: '.$row['payment_status'].'<br>
Shipping Status: '.$row['shipping_status'].'<br>
Payment Id: '.$row['payment_id'].'<br>
            ';
        }

        $i=0;
        $statement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
        $statement->execute(array($_POST['payment_id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $i++;
            $order_detail .= '
<br><b><u>Product Item '.$i.'</u></b><br>
Product Name: '.$row['product_name'].'<br>
Size: '.$row['size'].'<br>
Color: '.$row['color'].'<br>
Quantity: '.$row['quantity'].'<br>
Unit Price: '.$row['unit_price'].'<br>
            ';
        }

        $statement = $pdo->prepare("INSERT INTO tbl_customer_message (subject,message,order_detail,cust_id) VALUES (?,?,?,?)");
        $statement->execute(array($subject_text,$message_text,$order_detail,$_POST['cust_id']));

        // sending email
        $to_customer = $cust_email;
        $message = '
<html><body>
<h3>Message: </h3>
'.$message_text.'
<h3>Order Details: </h3>
'.$order_detail.'
</body></html>
';
        

        try {
            
            $mail->setFrom($admin_email, 'Admin');
            $mail->addAddress($to_customer, $cust_name);
            $mail->addReplyTo($admin_email, 'Admin');
            
            $mail->isHTML(true);
            $mail->Subject = $subject;

            $mail->Body = $message;
            $mail->send();

            $success_message = 'Your email to customer is sent successfully.';   
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        
        

    }
}
?>
<?php
if($error_message != '') {
    echo "<script>alert('".$error_message."')</script>";
}
if($success_message != '') {
    echo "<script>alert('".$success_message."')</script>";
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>View Orders</h1>
	</div>
</section>


<section class="content">

  <div class="row">
    <div class="col-md-12">


      <div class="box box-info">
        
        <div class="box-body table-responsive">
          <table id="example1" class="table table-bordered table-striped">
			<thead>
			    <tr>
			        <th>SL</th>
                    <th>Prescription_photo</th>
                    <th>Customer Details</th>
			        <th>Product Details</th>
                    
                    <th>
                    	Payment Information
                    </th>
                    <th>Paid Amount</th>
                    <th>promo Status</th>
                    <th>Payment Status</th>
                  
			        <th>Action</th>
			    </tr>
			</thead>
            <tbody>
            	<?php
            	$i=0;
            	$statement = $pdo->prepare("SELECT * FROM tbl_payment where cust_type='Individual' AND payment_method='Telebirr' AND payment_status='Pending' ");
            	$statement->execute();
            	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
            	foreach ($result as $row) {
            		$i++;
            		?>
					<tr class="<?php if($row['payment_status']=='Pending'){echo 'bg-r';}else{echo 'bg-g';} ?>">
	                    <td><?php echo $i; ?></td>
                       
                           <?php
                           $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=? ");
                           $statement1->execute(array($row['payment_id']));
                           $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result1 as $row1) {
                               $imagelink = $row1['photo'];
                                  }
                               ?>
					<td ><img id="myImg" src="../assets/uploads/prescription_photos/<?php echo $row1['photo']; ?>" alt="<?php  ?>" style="width:100px; max-width:300px;"></td>
                    
                   
	                    <td>
                            <b>Id:</b> <?php echo $row['customer_id']; ?><br>
                            <b>Name:</b><br> <?php echo $row['customer_name']; ?><br>
                            <b>Email:</b><br> <?php echo $row['customer_email']; ?><br>
                            <b>Phone:</b><br> <?php echo $row['customer_phone']; ?><br>
                            <b>Drop Location:</b><br> <?php echo $row['customer_drop']; ?><br><br>
                            <!-- Messsage to customers -->
                            <!-- <a href="#" data-toggle="modal" data-target="#model-<?php echo $i; ?>"class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Send Message</a>
                            <div id="model-<?php echo $i; ?>" class="modal fade" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title" style="font-weight: bold;">Send Message</h4>
										</div>
										<div class="modal-body" style="font-size: 14px">
											<form action="" method="post">
                                                <input type="hidden" name="cust_id" value="<?php echo $row['customer_id']; ?>">
                                                <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
												<table class="table table-bordered">
													<tr>
														<td>Subject</td>
														<td>
                                                            <input type="text" name="subject_text" class="form-control" style="width: 100%;">
														</td>
													</tr>
                                                    <tr>
                                                        <td>Message</td>
                                                        <td>
                                                            <textarea name="message_text" class="form-control" cols="30" rows="10" style="width:100%;height: 200px;"></textarea>
                                                        </td>
                                                    </tr>
													<tr>
														<td></td>
														<td><input type="submit" value="Send Message" name="form1"></td>
													</tr>
												</table>
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div> -->
                        </td>
                        <td>
                           <?php
                           $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                           $statement1->execute(array($row['payment_id']));
                           $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result1 as $row1) {
                                echo '<b>Product Name:</b> '.$row1['product_name'];
                                echo '<br>(<b>Size:</b> '.$row1['size'];
                                echo ', <b>Color:</b> '.$row1['color'].')';
                                echo '<br>(<b>Lens Type:</b> ' . $row1['lens_type'].')<br>';
                                echo '<br>(<b>Single Vision:</b> ' . $row1['s_sph_right'].$row1['s_sph_left'].$row1['s_cyl_right'].$row1['s_cyl_left'].$row1['s_axis_right'].$row1['s_axis_left'];
                                echo '<br><b>Lens Type Remark:</b> ' . $row1['s_photosolar_check']. $row1['s_photochromic_check']. $row1['s_white_check']. $row1['s_plasctic_check']. $row1['s_sunsensor_check']. $row1['s_glarefree_check']. $row1['s_antiglare_check']. $row1['s_arc_check']. $row1['s_hmc_check']. $row1['s_progressivelens_check']. $row1['s_bicfocal_check']. $row1['s_scratchresistant_check'].')<br>';

                                echo '<br>(<b>Progressive Vision:</b> ' . $row1['p_sph_right'].$row1['p_sph_left'].$row1['p_cyl_right'].$row1['p_cyl_left'].$row1['p_axis_right'].$row1['p_axis_left'].$row1['p_add_right'].$row1['p_add_left'].'';
                                echo '<br><b>Lens Type Remark:</b> ' . $row1['p_photosolar_check'] . $row1['p_photochromic_check'] . $row1['p_white_check'] . $row1['p_plasctic_check'] . $row1['p_sunsensor_check'] . $row1['p_glarefree_check'] . $row1['p_antiglare_check'] . $row1['p_arc_check'] . $row1['p_hmc_check'] . $row1['p_progressivelens_check'] . $row1['p_bicfocal_check'] . $row1['p_scratchresistant_check'] . ')<br>';
                                echo '<br>(<b>PD Numbers</b> ' . $row1['s_one_pd'] . $row1['s_two_pd_right'] . $row1['s_two_pd_left'] . $row1['s_two_pd_right_type'] . $row1['s_two_pd_left_type'] . $row1['p_one_pd'] . $row1['p_two_pd_right'] . $row1['p_two_pd_left'] . $row1['p_two_pd_right_type'] . $row1['p_two_pd_left_type'] . ')';

                                echo '<br>(<b>Quantity:</b> '.$row1['quantity'] . ')';
                                echo '<br>(<b>Additional Detail:</b> ' . $row1['add_detail'] . ')';
                               
                                echo '<br><br>';
                           }
                           ?>
                        </td>
                        
                        <td>
                  
                        	<?php if($row['payment_method'] == 'Telebirr'): ?>
                        		<b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                        		<b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
                        		<b>Date:</b> <?php echo $row['payment_date']; ?><br><br>
                            
                                                        
                                
                        	<?php elseif($row['payment_method'] == 'Stripe'): ?>
                        		<b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                        		<b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
								<b>Date:</b> <?php echo $row['payment_date']; ?><br>
                        		<b>Transaction Id:</b> <?php echo $row['txnid']; ?><br>
                        		<b>Card Number:</b> <?php echo $row['card_number']; ?><br>
                        		<b>Card CVV:</b> <?php echo $row['card_cvv']; ?><br>
                        		<b>Expire Month:</b> <?php echo $row['card_month']; ?><br>
                        		<b>Expire Year:</b> <?php echo $row['card_year']; ?><br>
                        	<?php elseif($row['payment_method'] == 'Bank Deposit'): ?>
                        		<b>Payment Method:</b> <?php echo '<span style="color:red;"><b>'.$row['payment_method'].'</b></span>'; ?><br>
                        		<b>Payment Id:</b> <?php echo $row['payment_id']; ?><br>
								<b>Date:</b> <?php echo $row['payment_date']; ?><br>
                        		<b>Transaction Information:</b> <br><?php echo $row['bank_transaction_info']; ?><br>
                        	<?php endif; ?>
<b>Promo Code: </b> <?php echo $row['promo_code']; ?><br>
                                <b>Promo discount: </b> <?php echo $row['promo_amount'].'%'; ?>   
                                 <?php
$statement2 = $pdo->prepare("SELECT * FROM promo WHERE promo_code=?");
$statement2->execute(array($row['promo_code']));
$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
foreach ($result2 as $row2) {

    echo '<br><b>------ Promo Holder Info ------</b>';
    echo '<br><b>Promo Holder:</b>' . $row2['name'];
    echo '<br><b>Phone Number:</b>' . $row2['phone'];
    echo '<br><b>Address:</b>' . $row2['address'];



}
?>
                        </td>
                        <td><?php echo '<br>Unit Price :</br> <b>' . $row1['unit_price'] .'</b> ETB'; 
                        echo '<br><br>Total Price Price:</br><b> ' . $row['paid_amount'] . '</b> ETB';
                        ?></td>
                         <td>
                            <?php echo $row['promo_status']; ?>
                            <br><br>
                            <?php
                                if($row['promo_status']=='Pending'){
                                    ?>
                                    <a href="promo_complete.php?pro=<?php echo $row['promo_code']?>&discount=<?php echo $row['discount']?>&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Make Completed</a>
                                    <?php
                                }
                            ?>
                        </td>
                        <td>
                            <?php echo $row['payment_status']; ?>
                            <br><br>
                            <?php
                                if($row['payment_status']=='Pending'){
                                    ?>
                                    <a href="order-change-status.php?id=<?php echo $row['id']; ?>&task=Completed" class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Make Completed</a>
                                    <?php
                                }
                            ?>
                        </td>
                      
	                    <td>
                            <a href="#" class="btn btn-danger btn-xs" data-href="order-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete" style="width:100%;">Delete</a>
	                    </td>
	                </tr>
            		<?php
            	}
            	?>
            </tbody>
          </table>
        </div>
      </div>
  

</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<?php require_once('footer.php'); ?>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById("myImg");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script>