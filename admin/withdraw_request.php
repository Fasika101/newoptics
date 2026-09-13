<?php require_once 'header.php';?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Customers Withdraw request</h1>
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
								<th width="180">Phone Number</th>
                                <th width="180">Promo Code</th>
                                <th width="180">To be deposited to</th>
                                <th width="180">Account no.</th>
                                <th width="180">Withdraw amount</th>
                                <th width="180">Request Date and Time</th>
                                <th width="180">Request Status</th>
                                <th width="180">Approve</th>
                                <th width="180">Info</th>

							</tr>
						</thead>
						<tbody>
							<?php
$i = 0;

$statement = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE status='Requested' ");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $i++;
    ?>
    <tr>
	    <td><?php echo $i++; ?></td>
        <td><?php echo $row['cust_name']; ?></td>
        <td><?php echo $row['cust_phone']; ?></td>
        <td><?php echo $row['cust_promo']; ?></td>
        <td><?php echo $row['bank']; ?></td>
        <td><?php echo $row['acc_no']; ?></td>
        <td><?php echo $row['request_amount'];
    echo ' ETB'; ?></td>
        <td><?php echo $row['date']; ?></td>

            <td><?php echo $row['status']; ?></td>

                       <td>     <?php
if ($row['status'] == 'Requested') {
        ?>
                                    <a href="withdraw_request_approve.php?id=<?php echo $row['id']; ?>&task=Withdrawn&req_amnt=<?php echo $row['request_amount']; ?>" class="btn btn-warning btn-xs" style="width:100%;margin-bottom:4px;">Approve Request</a>
                                    <?php
}
    ?>
                        </td>
                     <td><?php echo $row['comment'];?></td>	</tr>


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