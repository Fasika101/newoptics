<?php require_once 'header.php';?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Customers Promo Earning</h1>
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
                                <th width="180">Balance</th>
								
							</tr>
						</thead>
						<tbody>
							<?php
$i = 0;


                           $statement1 = $pdo->prepare("SELECT * FROM promo WHERE status=1 AND balance>0");
                           $statement1->execute();
                           $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                           foreach ($result1 as $row1) {   
							   $i++;
 ?>					  
						   		<tr>	 <td><?php echo $i; ?></td>
									<td><?php echo $row1['name']; ?></td>
									<td><?php echo $row1['phone']; ?></td>
                                    <td><?php echo $row1['promo_code']; ?></td>
									<td><?php echo $row1['balance']; ?></td>
						       </tr>
<?php
?> <?php 
						   }
                           ?>
                   			  
								<?php

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