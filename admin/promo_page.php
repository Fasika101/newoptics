<?php require_once 'header.php';?>
<?php
if (isset($_POST['approve_promo'])) {

    $valid = 1;

    if (empty($_POST['customer_phone'])) {
        $valid = 0;
        $error_message = 'Phone cannot be empty!';
    }

    if (empty($_POST['customer_name'])) {
        $valid = 0;
        $error_message = 'Customer Name cannot be empty!';
    }
    if (empty($_POST['promo_amount'])) {
        $valid = 0;
        $error_message = 'Promo Amount cannot be empty!';
    }

    if (empty($_POST['customer_promo'])) {
        $valid = 0;
        $error_message = 'Promo cannot be empty!';

    } else {

        $statement = $pdo->prepare("SELECT * FROM promo WHERE promo_code=?");
        $statement->execute(array($_POST['customer_promo']));
        $total = $statement->rowCount();
        if ($total) {
            $valid = 0;
            $error_message = 'You have already given this Promo Code.';
        }
    }

    if ($valid == 1) {

        $statement = $pdo->prepare("UPDATE promo SET 
        							name=?, 
        							address=?, 
        							phone=?, 
        							promo_code=?,
        							promo_amount=?
        							");
        	$statement->execute(array(
        							$_POST['customer_name'],
        							0,
        							$_POST['customer_phone'],
        							$_POST['customer_promo'],
        							$_POST['promo_amount'],
        						
        						));

      
        $success_message .= 'Promo Code Application Successful';
    } else {
        $error_message .= 'Promo Code Application not Successful';

    }
}
?>
<section class="content-header">
	<div class="content-header-left">
		<h1>Customer Code Mangement</h1>
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
								<th width="30">SL</th>
								<th width="180">Name</th>
								<th width="180">Phone</th>
								<th width="180">Promo Code</th>
								<th width="180">Promo Code Amount</th>
								<th>Status</th>
								<th width="100">Change Status</th>
								<th width="100">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
$i = 0;
$statement = $pdo->prepare("SELECT * FROM promo WHERE company=0");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $i++;
    ?>
								<tr class="<?php if ($row['status'] == 1) {echo 'bg-g';} else {echo 'bg-r';}?>">
									<td><?php echo $row['id']; ?></td>
									<td><?php echo $row['name'].' '.$row['lname']; ?></td>
									<td><?php echo $row['phone']; ?></td>
									<td><?php echo $row['promo_code']; ?></td>
                                    <td><?php echo $row['promo_amount']; ?></td>
									<td><?php if ($row['status'] == 1) {echo 'Active';} else {echo 'Inactive';}?></td>
									<td>
										<a href="promo_change_status.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-xs">Change Status</a>
									</td>
									
                                    <td>
										<!-- <a  class="btn btn-primary btn-xs"  href="#modal<?php echo $row['id']; ?>"data-toggle="modal" data-target="#edit">Edit</a> -->
	                                      <a href="promo_cust_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Edit</a>
									
                                    </td>
         <!--                           <td>-->
									<!--	<a href="#" class="btn btn-danger btn-xs" data-href="promo_delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>-->
									<!--</td>-->
								</tr>
								<?php
}
?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


</section>


<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Promo Code Confirmation</h4>
            </div>
            <div class="modal-body">
               
                <form action="" method="post" >                                           
                                        
                                        <div class="form-group">
                                             <label for="">Full Name</label><br>
                                              <input type="hidden" name="customer_name" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_name']; ?>" ><?php echo $row['id']; ?></input><br>

                                              <input type="hidden" name="customer_name" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_name']; ?>" ><?php echo $_SESSION['customer']['cust_name']; ?></input><br>
                                        </div>
                                        <div class="form-group">
                                             <label for="">Phone Number</label><br>
                                              <input type="hidden" name="customer_phone" style="font-size:14px;" value="<?php echo $_SESSION['customer']['cust_phone'];?>"><?php echo $_SESSION['customer']['cust_phone']; ?></input><br>
                                             
                                        </div>
                                        <div class="form-group">
                                              <label for="">Promo Code</label><br>
                                              <input type="text" name="customer_promo" style="font-size:14px;" value="" >
                                        </div>
                                        <div class="form-group">
                                              <label for="">Promo Amount in %</label><br>
                                              <select id="promo_amount" name="promo_amount" class="form-control select" onchange="one_pd_value(this.value)" style="width: 120px;"">
									        	<option value="" selected>Choose One</option>
										         <option value="5">5%</option>
										        <option value="10">10%</option>
										        <option value="15">15%</option>
										        <option value="20">20%</option>
                                                <option value="25">25%</option>
										        <option value="30">30%</option>
										        <option value="35">35%</option>
										        <option value="40">40%</option>
                                                <option value="45">45%</option>
										        <option value="50">50%</option>
										        <option value="55">55%</option>
										        <option value="60">60%</option>
                                                <option value="65">65%</option>
										        <option value="70">70%</option>
										        <option value="75">75%</option>
										        <option value="80">80%</option>
                                                <option value="85">85%</option>
										        <option value="90">90%</option>
										        <option value="95">95%</option>
									          </select>
                                        </div>
                                                                    
                                         <div class="modal-footer">
                                             <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <input type="submit" class="btn btn-success" value="<?php echo 'Submit'; ?>" name="approve_promo">
                                        </div>
                                    </form>     
            </div>
           
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete this customer?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>


<?php require_once 'footer.php';?>