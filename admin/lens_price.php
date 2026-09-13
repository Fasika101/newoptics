<?php require_once 'header.php';?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Lens Price</h1>
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
								<th width="200">Lens Name</th>
								<!-- <th width="60">Old Price</th> -->
								<th width="60">Price</th>
								<th>Category</th>
								<th width="80">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
$i = 0;
$statement = $pdo->prepare("SELECT * FROM lens_type_price");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $i++;
    ?>
								<tr>
									<td><?php echo $i; ?></td>
									<!-- <td><?php //echo $row['p_old_price']; ?></td> -->
									<td><?php echo $row['lens_name']; ?></td>
									<td><?php echo $row['lens_price']; ?></td>
                                    <td><?php echo $row['lens_category']; ?></td>
									
									<td>
										<a href="lens_price_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Edit Price</a>
										<!-- <a href="#" class="btn btn-danger btn-xs" data-href="product-delete.php?id=<?php echo $row['p_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a> -->
									</td>
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




<?php require_once 'footer.php';?>