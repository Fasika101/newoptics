<?php require_once 'header.php';?>
<?php
if (isset($_POST['generate_text'])) {
    $valid = 1;
// if (empty($_POST['link'])) {
//     $valid = 0;
//     $error_message .= 'Link Cannot be empty.';
// }

if (empty($_POST['name'])) {
    $valid = 0;
    $error_message .= 'Name Cannot be empty.';
}else {
    $statement = $pdo->prepare("SELECT * FROM tbl_qr WHERE product_name=?");
    $statement->execute(array($_POST['name']));
    $total = $statement->rowCount();
    if ($total) {
        $valid = 0;
        $error_message .= 'Product Name already exists' . " ";
    }
}

    // include 'qr_code/phpqrcode/qrlib.php';
    // $name = $_POST['name'];
    // $folder = "qr_code/images/";
    // $link = $_POST['link'];
    // $file_name = $name . ".png";
    // $file_name = $folder . $file_name;
    // QRcode::png($link, $file_name);
    //echo"<img src='images/qrh.png'>";

    //To Display Code Without Storing
    //  QRcode::png($link);


    //saving data to database
 if($valid == 1) {
    
$statement2 = $pdo->prepare("SELECT * FROM tbl_product WHERE p_name=?");
$statement2->execute(array($_POST['name']));
$result = $statement2->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $link2 = $row['p_id'];
    


include 'qr_code/phpqrcode/qrlib.php';
$name = $_POST['name'];
$folder = "qr_code/images/";
$link ="newonlineoptics.com/product.php?id=". $row['p_id'];
$file_name = $name . ".png";
$file_name = $folder . $file_name;
QRcode::png($link, $file_name);


    //Saving data into the main table tbl_qr
$statement = $pdo->prepare("INSERT INTO tbl_qr(
										product_name,
										qr_link
										
									) VALUES (?,?)");
$statement->execute(array(
    $_POST['name'],
    $link,

));

}
}
}


// $directory = "images/";
// $images = glob($directory . "/*.*");

// foreach($images as $image)
// {
//   echo $image;
// }

//diplay all images
// $all_files = glob("qr_code/images/*.*");
// for ($i = 0; $i < count($all_files); $i++) {
//     $image_name = $all_files[$i];
//     echo '<img src="' . $image_name . '" alt="' . $image_name . '" />' . $image_name . "<br /><br />";
// }

if (array_key_exists('delete_file',  $_POST)) {
    $filename = $_POST['delete_file'];
    //$filenam = $_POST['file_name'];
    
    if (file_exists($filename)) {
        unlink($filename);

        // Delete from tbl_rating
$statement = $pdo->prepare("DELETE FROM tbl_qr WHERE product_name=?");
$statement->execute(array($_POST['file_name']));


        $success_message .= $filename .'has been deleted.';

        //echo 'File ' . $filename . ' has been deleted';
    } else {
       $error_message .='Could not delete ' . $filename . ', file does not exist';
    }
    
}

?>
<style>
	#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (Image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image (Image Text) - Same Width as the Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation - Zoom in the Modal */
.modal-content, #caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>
                         <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";
}
if ($success_message != '') {
    echo "<script>alert('" . $success_message . "')</script>";


}
// global $p_name_valid;
// $statement3 = $pdo->prepare("SELECT * FROM tbl_qr");
// $statement3->execute();
// $result3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
// foreach ($result3 as $row3) {
//     // $link2 = $row3['p_id'];
//     $p_name_valid2 = $row3['product_name'];
// }

// $p_name_valid = $p_name_valid2;
?>
<section class="content-header">
	<div class="content-header-left">
		<h1>Generate QR Code</h1>
	</div>

</section>
<section class="content-header">
    <div id="wrapper" style="padding: 10px;">
 <form class="form-horizontal" method="post" action="">
  <!-- //name*<input type="name" name="name"> -->
 	                   
  <div class="form-group">
     
							<label for="" class="col-sm-3 control-label">Product Name <span>*</span></label><br>
							<div class="col-sm-4">
								<select name="name" class="form-control select2">
									<option value="">Select Product</option>
								 <?php 
      							    $statement = $pdo->prepare("SELECT * FROM tbl_product D  WHERE NOT EXISTS(SELECT * FROM tbl_qr C WHERE D.p_name = C.product_name)");
                       
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
									foreach ($result as $row) {
										?>
										<option value="<?php echo $row['p_name']; ?>"><?php echo $row['p_name']; ?></option>
										<?php
									}
                                
									?>
								</select>
							</div>
						</div>
  <!-- link*<input type="link" name="link"> -->
  <div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="generate_text" >Generate QR Code</button>
							</div>
						</div>

  
 </form>
 <!-- <img src='images/*.pngx'> -->
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
								<th>QR Codes</th>
								<th >Product Name</th>
                                <th >Action</th>
								

							</tr>
						</thead>
						<tbody>
<?php   


$i = 0;


$folder_path = "qr_code/images/"; //image's folder path

$num_files = glob($folder_path . "*.{JPG,jpg,gif,png,bmp}", GLOB_BRACE);

$folder = opendir($folder_path);

if ($num_files > 0) {
    while (false !== ($file = readdir($folder))) {
        $file_path = $folder_path . $file;
        $path_parts = pathinfo($file_path);
        $extension = strtolower($path_parts['extension']);
        $fille = $path_parts['filename'];
        if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'bmp') {
   
$i++;


// //////////
//  $directory = "qr_code/images/";
// // $images = glob($directory . "/*.*");

// $all_files = glob($directory . "/*.*");

// for ($i = 0; $i < count($all_files); $i++) {
//     $image_name = $all_files[$i];
//     // echo '<img src="' . $image_name . '" alt="' . $image_name . '" />' . $image_name . "<br /><br />";
?>

			
								<tr>
                                    <td><?php echo $i; ?></td>
									<td><?php echo '<img src="' . $file_path . '" height="200" />'; ?></td>
                                                              
									<td ><?php  echo $fille;?></td>
                                    <td>
                                            <?php echo '<form method="post">';
                                            echo '<input type="hidden" value="' . $file_path. '" name="delete_file" />';
                                            echo '<input type="hidden" value="' . $fille . '" name="file_name" />';
                                            echo '<input class="btn btn-danger btn-xs" type="submit" value="Delete image" />';
                                            echo '</form>';
                                            
                                            ?>
	
									</td>

								</tr>
<?php

     }

    }
}

//}
?>								
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</section>

	<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>


<?php require_once 'footer.php';?>
<script>// Get the modal
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
if (window . history . replaceState) {
    window . history . replaceState(null, null, window . location . href);
}

	</script>