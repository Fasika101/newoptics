<?php require_once 'header.php';?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<?php
if (isset($_POST['apply_for_promo'])) {

     $valid = 1;

if (empty($_POST['customer_phone'])) {
    $valid = 0;
    $error_message = 'Phone cannot be empty!';
}

if (empty($_POST['customer_promo'])) {
    $valid = 0;
    $error_message = 'Phone cannot be empty!';
}


if (empty($_POST['customer_name'])) {
    $valid = 0;
    $error_message = 'Name cannot be empty!';

} else {
   
        $statement = $pdo->prepare("SELECT * FROM promo WHERE phone=?");
        $statement->execute(array($_POST['customer_phone']));
        $total = $statement->rowCount();
        if ($total) {
            $valid = 0;
            $error_message = 'You have already Applied for Promo Code.';
        }
    }



if($valid == 1) {
$statement = $pdo->prepare("INSERT INTO promo (
                                        name,
                                        lname,
                                        address,
                                        phone,
                                        promo_code,
                                        promo_amount,
                                        status,
                                        company
                                    ) VALUES (?,?,?,?,?,?,?,?)");
        $statement->execute(array(
            strip_tags($_POST['customer_name']),
             strip_tags($_POST['customer_lname']),
            0,
            strip_tags($_POST['customer_phone']),
            strip_tags($_POST['customer_promo']),
            0,
            1,
            0

        ));
        $success_message .= 'Promo Code Application Successful';
}
else{
    $error_message .= 'Promo Code Application not Successful';

}
}

if (isset($_POST['withdraw_request'])) {

    $valid = 1;

    if (empty($_POST['account_type'])) {
        $valid = 0;
        $error_message = 'Please choose your account type!';
    }
    if (empty($_POST['account_no'])) {
    $valid = 0;
    $error_message = 'Account Number cannot be empty!';
    }


    if (empty($_POST['account_no'])) {
        $valid = 0;
        $error_message = 'Account Number cannot be empty!';
    }

    if (empty($_POST['request_amount'])) {
        $valid = 0;
        $error_message = 'Please specify the amount you want to withdraw!';

    } else {
$statement10 = $pdo->prepare("SELECT * FROM promo WHERE phone=?");
$statement10->execute(array($_SESSION['customer']['cust_phone']));
$result10 = $statement10->fetchAll(PDO::FETCH_ASSOC);

global $cust_promo_code;
global $cust_balance;

foreach ($result10 as $row10) {

   $cust_promo_code = $row10['promo_code'];
   $cust_balance = $row10['balance'];
   $min_amount = '50';


if ($_POST['request_amount'] > $cust_balance) {
    $valid = 0;
    $error_message = 'Not Enough balance in your account.';
}
if ($_POST['request_amount'] < $min_amount) {
    $valid = 0;
    $error_message = 'Minimun Withdraw Amount is 500 ETB.';
}


}

// $statement9 = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE cust_promo=?  ");
// $statement9->execute(array($row10['promo_code']));
// $result9 = $statement9->fetchAll(PDO::FETCH_ASSOC);
// foreach ($result9 as $row9) {
//     $total_amount = $row9['acc_balance'];
//     $min_amount = '500';

//         if ($_POST['request_amount'] > $total_amount) {
//             $valid = 0;
//             $error_message = 'Not Enough balance in your account.';
//         }
//          if ($_POST['request_amount'] < $min_amount) {
//             $valid = 0;
//             $error_message = 'Not Enough balance in your account.';
//         }
        


//     }
}

    if ($valid == 1) {
        $token = md5(time());
        $cust_datetime = date('Y-m-d h:i:s');
        

        $statement = $pdo->prepare("INSERT INTO tbl_withdraw (
                                        cust_name,
                                        cust_phone,
                                        cust_promo,
                                        acc_balance,
                                        request_amount,
                                        bank,
                                        acc_no,
                                        status,
                                        date
                                    ) VALUES (?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
            strip_tags($_POST['customer_name']),
            strip_tags($_POST['customer_phone']),
            strip_tags($_POST['customer_promo']),
            strip_tags($_POST['balance']),
            strip_tags($_POST['request_amount']),
            strip_tags($_POST['account_type']),
            strip_tags($_POST['account_no']),
            strip_tags($_POST['status']),
            $cust_datetime
        ));
        $success_message .= 'Withdraw Request sent successfuly';
    } else {
        $error_message .= 'Withdraw Request not Successful';

    }
}

?>


<?php
// Check if the customer is logged in or not
if (!isset($_SESSION['customer'])) {
    header('location: ' . BASE_URL . 'logout.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'], 0));
    $total = $statement->rowCount();

  

   
    if ($total) {
        header('location: ' . BASE_URL . 'logout.php');
        exit;
    }
}

?>

       <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}
if ($success_message != '') {
    echo "<script>alert('" . $success_message . "')</script>";
    //  header('location: product.php?id=' . $_REQUEST['id']);
}
?>
<div class="page">
    <div class="container">
      
            <div class="col-md-12">
                <?php require_once 'customer-sidebar.php';?>
                
            </div> 
            
          
        <?php

        global $cust_promo;
            $statement4 = $pdo->prepare("SELECT * FROM promo WHERE phone=? ");
            $statement4->execute(array($_SESSION['customer']['cust_phone']));
            $result4 = $statement4->fetchAll(PDO::FETCH_ASSOC);
           
           global $val;


                foreach ($result4 as $row4) {
                  

                
                    $val = $row4['phone'];
                    $status = $row4['status'];

                
                }
                
                if ($val == '') { 
                    echo 'You Currently Do not have a Promo Code. Click on the button below to Apply for one.';

                    echo '<br><button class="bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400" data-target="#promo_apply" data-toggle="modal" style="margin:5px; color: white !important;">Apply For a Promo Code</button>';
                   
                   

                } 
                else if($status == '0'){

                    echo 'Your Promo Code Is Inactive for the moment. Please email us on <b>help@newonlineoptics.com</b> to resolve any issues regarding your promocode status.';

                }
                
                
                else {
                  $cust_promo = $row4['promo_code'];
                  ?>
     
                  <table class="table table-borderless">
                      <tbody>
                      <tr>
                          <td><?php echo 'Your Promo Code: ';?>
                          <?php echo "<b>" . $row4['promo_code'] . "</b> <br>";  echo ' Promo Code percentage: <b>'.$row4['promo_amount'].' % </b><br>' ;
                          
                          echo 'Status: ';

                          if($row4['status'] == 1){
                              echo '<b>Active</b>';
                          }
                          else{
                              echo 'Inactive ';
                          }

                          ?>
                        
                        </td>  </tr>
                
<tr>
<td><?php
echo 'Total Balance: ';?><?php echo '<b>'.  $row4['balance'] .' ETB</b>';?></td></tr>
<?php

?><tr><td><?php
                   echo '<button class=" bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400" data-target="#help" data-toggle="modal" style="margin:5px; color: white !important;" >Request Withdraw</button>';
         }   ?>    </td> 
                  <tr> </tbody></table>         
           
               <div class="col-md-12" style="background-color: transparent !important; margin-top: 0px;">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs" style="background-color: transparent !important;" role="tablist">
                              <li class="nav-item">
								<a class="nav-link active " style="" href="#promo_history" aria-controls="promo_history" role="tab" data-toggle="tab"><?php echo 'Total Earning History'; ?></a>
                              </li>
                               <li class="nav-item">
								<a class="nav-link" href="#with_hist" aria-controls="with_hist" role="tab" data-toggle="tab"><?php echo 'Withdraw Requests'; ?></a>
                              </li>
                              </li>
                               <li class="nav-item">
								<a class="nav-link" href="#with_app" aria-controls="with_app" role="tab" data-toggle="tab"><?php echo 'Withdraw History'; ?></a>
                              </li>
                            </ul>

							<!-- Tab panes -->
					<div class="tab-content">
                             <div role="tabpanel" class="tab-pane" id="promo_history" style="margin-top: -30px;">
                                <br>        
               
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo LANG_VALUE_7; ?></th>
                                   
                                    <th><?php echo LANG_VALUE_27; ?></th>
                                    <th><?php echo LANG_VALUE_29; ?></th>
                                   
                                    <th><?php echo 'Promo Code'; ?></th>
                                    <th><?php echo 'Your Commision'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            

            <?php
/* ===================== Pagination Code Starts ================== */
$adjacents = 5;

$statement = $pdo->prepare("SELECT * FROM promo WHERE phone=? ORDER BY id DESC");
$statement->execute(array($_SESSION['customer']['cust_phone']));
$total_pages = $statement->rowCount();

$targetpage = BASE_URL . 'promo_page.php';
$limit = 10;
$page = @$_GET['page'];
if ($page) {
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
}

$statement = $pdo->prepare("SELECT * FROM promo WHERE phone=? ORDER BY id DESC LIMIT $start, $limit");
$statement->execute(array($_SESSION['customer']['cust_phone']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($page == 0) {
    $page = 1;
}

$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$lpm1 = $lastpage - 1;
$pagination = "";
if ($lastpage > 1) {
    $pagination .= "<div class=\"pagination\">";
    if ($page > 1) {
        $pagination .= "<a href=\"$targetpage?page=$prev\">&#171; previous</a>";
    } else {
        $pagination .= "<span class=\"disabled\">&#171; previous</span>";
    }

    if ($lastpage < 7 + ($adjacents * 2)) {
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page) {
                $pagination .= "<span class=\"current\">$counter</span>";
            } else {
                $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
            }

        }
    } elseif ($lastpage > 5 + ($adjacents * 2)) {
        if ($page < 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                if ($counter == $page) {
                    $pagination .= "<span class=\"current\">$counter</span>";
                } else {
                    $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                }

            }
            $pagination .= "...";
            $pagination .= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
            $pagination .= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
        } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
            $pagination .= "<a href=\"$targetpage?page=1\">1</a>";
            $pagination .= "<a href=\"$targetpage?page=2\">2</a>";
            $pagination .= "...";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page) {
                    $pagination .= "<span class=\"current\">$counter</span>";
                } else {
                    $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                }

            }
            $pagination .= "...";
            $pagination .= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
            $pagination .= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
        } else {
            $pagination .= "<a href=\"$targetpage?page=1\">1</a>";
            $pagination .= "<a href=\"$targetpage?page=2\">2</a>";
            $pagination .= "...";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page) {
                    $pagination .= "<span class=\"current\">$counter</span>";
                } else {
                    $pagination .= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                }

            }
        }
    }
    if ($page < $counter - 1) {
        $pagination .= "<a href=\"$targetpage?page=$next\">next &#187;</a>";
    } else {
        $pagination .= "<span class=\"disabled\">next &#187;</span>";
    }

    $pagination .= "</div>\n";
}
/* ===================== Pagination Code Ends ================== */
?>
 
               <?php
               global $promo_val;
    $statement1 = $pdo->prepare("SELECT * FROM tbl_payment WHERE promo_code=? AND promo_status='Complete' AND payment_status='Completed'");
    $statement1->execute(array($row4['promo_code']));
    $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
    $i = 0;

    foreach ($result1 as $row1) {
$i++;
 $promo_val = $row1['promo_code'];
    ?>   <tr id="1">
                                        <td><?php echo $i; ?></td>
     <td class="row-data"><?php echo $row1['payment_date']; ?>
    
                                        </td>
                                        <td ><?php echo $row1['paid_amount'].'<b> ETB</b>';?></td>
                                        <td><?php echo $row1['promo_code'];?></td>
                                        <td><?php echo $row1['discount'].'<b> ETB</b>'; ?></td>
                            
  </tr>
  

<?php }?>
 
<!-- <tr> <td></td>
  <td></td>
  <td></td> -->
  <!-- <td>Your Total Earning</td>
  <?php 
  $statement7 = $pdo->prepare("SELECT SUM(discount) AS 'count_col' FROM tbl_payment WHERE promo_code=?  AND promo_status='Complete' AND payment_status='Completed'");
  $statement7->execute(array($promo_val ));

  $result7 = $statement7->fetchAll(PDO::FETCH_ASSOC); 
   foreach ($result7 as $row7) {?>
<td><?php

  $sum = $row7['count_col'] ;
  echo '<b>'. $sum.' ETB</b>';?></td> </tr>
<?php
   }?> -->
  
              

                            </tbody>
                        </table>
                        <div class="pagination" style="overflow: hidden;">
                        <?php
echo $pagination;
?>
                    </div>
           
    
           
   </div>
</div>
<!-- withdraw history -->
                         
                             <div role="tabpanel" class="tab-pane" id="with_hist" style="margin-top: -30px;">		
			<br><div class="table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">SL</th>
								<th width="180">Name</th>
								<th width="180">Phone Number</th>
                                <th width="180">Promo Code</th>
                                
                                <th width="180">To be deposited to</th>
                                <th width="180">Account no.</th>
                                <th width="180">Withdraw amount</th>
                                <th width="180">Request Date and Time</th>
                                <th width="180">Request Status</th>
                                <th width="180">Comment</th>
								
							</tr>
						</thead>
						<tbody>
							<?php
$i = 1;


$statement = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE cust_promo=? AND status='Requested' ");
$statement->execute(array($cust_promo));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
 
    
    ?><tr>
	    <td><?php echo  $i++;; ?></td>
        <td><?php echo $row['cust_name']; ?></td>
        <td><?php echo $row['cust_phone']; ?></td>
        <td><?php echo $row['cust_promo']; ?></td>
      
        <td><?php echo $row['bank']; ?></td>
        <td><?php echo $row['acc_no']; ?></td>
        <td><?php echo $row['request_amount']; echo ' ETB';?></td>
        <td><?php echo $row['date'];?></td>
        <td><?php echo $row['status']; ?></td>
        <td><?php echo $row['comment']; ?></td>
</tr>
      
                        
                   			  
								<?php
}
?>					
						</tbody>
					</table>
</div>
				</div>
   <!-- approved withdraw -->
     <div role="tabpanel" class="tab-pane" id="with_app" style="margin-top: -30px;">		
	<br>
    <div class="table-responsive">
    		<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">SL</th>
								<th width="180">Name</th>
								<th width="180">Phone Number</th>
                                <th width="180">Promo Code</th>
                                
                                <th width="180">Deposited to</th>
                                <th width="180">Account no.</th>
                                <th width="180">Withdraw amount</th>
                                <th width="180">Request Date and Time</th>
                                <th width="180">Request Status</th>
								
							</tr>
						</thead>
						<tbody>
							<?php
$i = 1;


$statement = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE cust_promo=? AND status='Withdrawn' ");
$statement->execute(array($cust_promo));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
 
    
    ?><tr>
	    <td><?php echo  $i++;; ?></td>
        <td><?php echo $row['cust_name']; ?></td>
        <td><?php echo $row['cust_phone']; ?></td>
        <td><?php echo $row['cust_promo']; ?></td>
      
        <td><?php echo $row['bank']; ?></td>
        <td><?php echo $row['acc_no']; ?></td>
        <td><?php echo $row['request_amount']; echo ' ETB';?></td>
        <td><?php echo $row['date'];?></td>
        <td><?php echo $row['status']; ?></td>
</tr>
      
                        
                   			  
								<?php
}
?>					
						</tbody>
					</table>
</div>
                    
</div>
<!-- end approved withdraw -->
			</div>
            
                             </div>

                             
                    </div>

                    
            </div>

                    
  <!-- withdraw history -->
     
    



    



    <!-- withdrwal Request -->

     <div id="help" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Withdrwal Form</h3>
                </div>
                <div class="modal-body">
                     <form action="" method="post" >

                    <?php echo 'Your Balance is: '; echo '<b>'.$row4['balance']. ' ETB</b>';?>
                    <input type="hidden" name="balance" style="font-size:14px;" value="<?php echo $row4['balance']; ?>" ></input><br>

                    <p>Notice: Minimum Withdrwal Amount is 500ETB.</p>
                    
                    
                    <div class="form-group">
                        <label for="">Full Name: </label>
                        <input type="hidden" name="customer_name" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_name']; ?>" ><?php echo $_SESSION['customer']['cust_name']; ?></input><br>
                     </div>
                     <div class="form-group">
                          <label for="">Phone Number: </label>
                          <input type="hidden" name="customer_phone" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_phone'];?>"><?php echo $_SESSION['customer']['cust_phone']; ?></input>
                     </div>
                     <div class="form-group">
                          <label for="">Your Promo Code: </label>
                          <input type="hidden" name="customer_promo" style="font-size:14px;" value="<?php echo  $cust_promo;?>"></input><?php echo $cust_promo;?></input>
                     </div>
                     <div class="form-group">
                         <label for="">To be deposoted to: </label><br>
                         <select name="account_type" style="padding: 10px; background:#edf2ff; border:none;"class="form-select text-2xl" >
                           <option selected placeholder="Enter your account">Choose</option>
                           <option value="Telebirr">Telebirr</option>
                           <option value="CBE">CBE </option>
                         </select>
                     </div>
                     <div class="form-group">
                          <label for=""><?php echo 'Account Number: '; ?> </label>
                          <input placeholder="Enter your account" type="text" class="form-control" name="account_no" value="<?php if(isset($_POST['cust_name'])){echo $_POST['cust_name'];} ?>">
                      </div>
                      <div class="form-group">
                          <label for=""><?php echo 'Withdraw amount: '; ?> </label>
                          <input placeholder="Enter withdraw amount" type="text" class="form-control" name="request_amount" value="<?php if(isset($_POST['cust_name'])){echo $_POST['cust_name'];} ?>">
                      </div>
                        <input type="hidden" name="status" style="font-size:14px;" value="Requested" ></input>
                     <div class="form-group">
                         <input type="submit" class="btn btn-success" value="<?php echo 'Request Withdraw'; ?>" name="withdraw_request">
                      </div>
                        </form>                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            	</div>
        	</div>
   		 </div> 
   	</div>
              <!-- end withdraw request     -->


              <!-- apply promo -->

     <div id="promo_apply" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Promo Code Application</h3>
                </div>
                <div class="modal-body">
                    <p>When you apply for a promo code and get approved, you’ll receive a unique discount code to share with your friends and family. When they use it to purchase from our website, they’ll enjoy a discount, and you’ll earn a percentage of the product’s price as a reward.
                                        </p>
                    <form action="" method="post" >

                                        <div class="form-group">
                                             <label for="">Full Name</label><br>
                                              <input type="hidden" name="customer_name" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_name']; ?>" ><?php echo $_SESSION['customer']['cust_name'].''.$_SESSION['customer']['cust_lname']; ?></input>
                                               <input type="hidden" name="customer_lname" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_lname']; ?>"></input>
                                              <br>
                                        </div>
                                        <div class="form-group">
                                             <label for="">Phone Number</label><br>
                                              <input type="hidden" name="customer_phone" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_phone'];?>"><?php echo $_SESSION['customer']['cust_phone']; ?></input><br>
                                              <input type="hidden" name="customer_promo" style="font-size:14px;" value="Your Promo Code Application is under review"></input><br>
                                              
                                            </div>
                                                                    
                                        <div class="col-md-12 form-group">
                                            <input type="submit" class="bg-yellow-300 inline-flex justify-center items-center py-3 px-5 text-2xl font-large text-center text-white rounded-lg bg-yellow-200 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-yellow-400" value="<?php echo 'Apply For Promo Code'; ?>" name="apply_for_promo">
                                        </div>
                                    </form>  
                     
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            	</div>
        	</div>
   		 </div> 
   	</div>
              <!-- end apply promo  -->



<?php require_once 'footer.php';?>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
