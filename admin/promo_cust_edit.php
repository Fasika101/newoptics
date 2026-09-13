<?php require_once('header.php'); ?>

<?php


if (isset($_POST['approve_promo'])) {

    $valid = 1;

    if (empty($_POST['customer_phone'])) {
        $valid = 0;
        $error_message = 'Phone cannot be empty!';
    }
    if (empty($_POST['cust_id'])) {
        $valid = 0;
        $error_message = 'Id cannot be empty!';
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
        							promo_amount=? WHERE id=?
        							");
        $statement->execute(array(
            $_POST['customer_name'],
            0,
            $_POST['customer_phone'],
            $_POST['customer_promo'],
            $_POST['promo_amount'],
            $_POST['cust_id']

        ));

        $success_message .= 'Promo Code Application Successful';
    } else {
        $error_message .= 'Promo Code Application not Successful';

    }
}

?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM promo WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Promo</h1>
	</div>
	<div class="content-header-right">
		<a href="promo_page.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<?php							
foreach ($result as $row) {
	$id = $row['id'];
    $name = $row['name'];
    $phone = $row['phone'];

}
?>

<section class="content">

  <div class="row">
    <div class="col-md-12">

		<?php if($error_message): ?>
		<div class="callout callout-danger">
		
		<p>
		<?php echo $error_message; ?>
		</p>
		</div>
		<?php endif; ?>

		<?php if($success_message): ?>
		<div class="callout callout-success">
		
		<p><?php echo $success_message; ?></p>
		</div>
		<?php endif; ?>
         <form action="" method="post">
                                    
                        <div class="row">
                            
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                            
                              
                                <div class="form-group">
                                    <label for="">Customer Name </label>
                                    <input type="text" class="form-control" name="customer_name" value="<?php echo $name; ?>" readonly>
                                    <input type="hidden" class="form-control" name="cust_id" value="<?php echo $id; ?>" readonly>

                                </div>
                                <div class="form-group">
                                    <label for="">Customer Phone </label>
                                    <input type="text" class="form-control" name="customer_phone" value="<?php echo $phone; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Promo Code </label>
                                    <input type="text" class="form-control" name="customer_promo" value="" >
                                </div>
                               
                                <div class="form-group">
                                            <label for="">Promo Amount in %</label>
                                              <select id="promo_amount" name="promo_amount" class="form-control select" onchange="one_pd_value(this.value)" style="width: 120px;">
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
                                
                                            <input type="submit" class="btn btn-success" value="<?php echo 'Submit'; ?>" name="approve_promo">
                                
                            </div>

                        </div>                        
                    </form>



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

<?php require_once('footer.php'); ?>