
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
 
 table {
  table-layout: fixed;
  width: 100%;
}

.small-cell {
  width: max-content;
}

td {
 
}

/* --- */

.display-table {
  display: table;
  table-layout: fixed;
  width: 100%;
}

.display-table > * {
  display: table-row;
}

.display-table > * > * {
  display: table-cell;

}
     /
        /* Adding some basic styling to button */
          
        .btn {
          /* '  text-decoration: none;
            border: none;
            padding: 12px 40px;
            font-size: 16px;
            background-color: green;
            color: #fff;' */        
            /* box-shadow: 7px 6px 28px 1px rgba(0, 0, 0, 0.24); */
            /* cursor: pointer;
            outline: none; */
            transition: 0.2s all;
        }
        /* Adding transformation when the button is active */
          
        .btn:active {
            transform: scale(0.98);
          
            /* Scaling button to 0.98 to its original size */
            /* box-shadow: 3px  1px rgba(0, 0, 0, 0.24); */
            /* Lowering the shadow */
        }
    </style>
<?php require_once 'header.php';?>

<?php
// Check if the customer is logged in or not
if (!isset($_SESSION['customer'])) {
    header('location: ' . BASE_URL . 'login.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'], 0));
    $total = $statement->rowCount();
    if ($total) {
        header('location: ' . BASE_URL . 'login.php');
        exit;
    }
}
?>

<?php


   
if(isset($_POST['form1'])) {
	$valid = 1;

if (empty($_POST['cust_name'])) {
    $valid = 0;
    $error_message .= "Customer Name cannot be empty.";
}
if (empty($_POST['cust_phone'])) {
    $valid = 0;
    $error_message .= "Phone number cannot be empty.";
}
if (empty($_POST['date'])) {
    $valid = 0;
    $error_message .= "Purchase date empty.";
}
if (empty($_POST['product_name'])) {
    $valid = 0;
    $error_message .= "product_name cannot be empty.";
}

    $path = $_FILES['pp']['name'];
    $path_tmp = $_FILES['pp']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' && $ext!='webp' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, webp, gif or png file<br>';
        }
    } else {
    	$valid = 0;
        $error_message .= 'You must have to upload a photo';
    }

    if($valid == 1) {

    	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_maintenance'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

    	if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) )
        {
        	$photo = array();
            $photo = $_FILES['photo']["name"];
            $photo = array_values(array_filter($photo));

        	$photo_temp = array();
            $photo_temp = $_FILES['photo']["tmp_name"];
            $photo_temp = array_values(array_filter($photo_temp));

        	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_maintenance_photo'");
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row) {
				$next_id1=$row[10];
			}
			$z = $next_id1;

            $m=0;
            for($i=0;$i<count($photo);$i++)
            {
                $my_ext1 = pathinfo( $photo[$i], PATHINFO_EXTENSION );
		        if( $my_ext1=='jpg' || $my_ext1=='png' || $my_ext1=='jpeg' || $my_ext1=='gif' || $my_ext1=='webp' ) {
		            $final_name1[$m] = $z.'.'.$my_ext1;
                    move_uploaded_file($photo_temp[$i],"assets/uploads/maintenance_photos/maintain/".$final_name1[$m]);
                    $m++;
                    $z++;
		        }
            }

            if(isset($final_name1)) {
            	for($i=0;$i<count($final_name1);$i++)
		        {
		        	$statement = $pdo->prepare("INSERT INTO tbl_maintenance_photo (photo,id) VALUES (?,?)");
		        	$statement->execute(array($final_name1[$i],$ai_id));
		        }
            }            
        }

		$final_name = 'maintenance-photo-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, 'assets/uploads/maintenance_photos/'.$final_name );

        
		//Saving data into the main table tbl_product
		$statement = $pdo->prepare("INSERT INTO tbl_maintenance(
										cust_name,
										cust_phone,
                                        product_name,
										date,
										photo_name
										
									) VALUES (?,?,?,?,?)");
		$statement->execute(array(
										$_POST['cust_name'],	
										$_POST['cust_phone'],
                                        $_POST['product_name'],
										$_POST['date'],
										$final_name
										
									));

	
	
    	$success_message = 'Free maintenance request sent successfully. We will contact you soon.';
    



}
}
?>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php require_once 'customer-sidebar.php';?>
                
            </div>

            <section class="bg-white dark:bg-gray-900">
            <h1 class=" text-center mb-4 text-2xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white"></h1>


            

    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-5">
            <h1 class="max-w-2xl mb-4 text-1xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-1xl dark:text-white">Free Maintenance</h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">Make an appointment to see the eyeglass frame you chose in person. We will come to your location to show you the products.</p>
           
            <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";

}
if ($success_message != '') {
   echo "<script>alert('" . $success_message . "')</script>";

}
?>
            <form   method="post" enctype="multipart/form-data" action="" class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6 lg:max-w-xl lg:p-8">
            <?php $csrf->echoInputField();?>
                         
            <div class="mb-6 grid grid-cols-1 gap-4">
            <div class="col-span-2 sm:col-span-1">
              <label for="full_name" class="mb-2 block text-2xl font-medium text-gray-900 dark:text-white"> Customer Name </label>
              <input name="cust_name" value="<?php echo $_SESSION['customer']['cust_name']. ' '.$_SESSION['customer']['cust_lname']; ?>" type="text" id="full_name" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-2xl text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Full Name" required />
            </div>

            <div class="col-span-2 sm:col-span-1">
              <label for="card-number-input" class="mb-2 block text-2xl font-medium text-gray-900 dark:text-white"> Phone Number </label>
              <input type="text" name="cust_phone" value="<?php echo $_SESSION['customer']['cust_phone']; ?>" id="card-number-input" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pe-10 text-2xl text-gray-900 focus:border-primary-500 focus:ring-primary-500  dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Phone" pattern="[0-9]{10}" required />
            </div>
         
            <div class="col-span-2 sm:col-span-1">
              <label for="card-number-input" class="mb-2 block text-2xl font-medium text-gray-900 dark:text-white"> Product Name</label>
              
              <input type="text" id="card-number-input" name="product_name" value="" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pe-10 text-2xl text-gray-900 focus:border-primary-500 focus:ring-primary-500  dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Product Name"required />
            
            </div>
            <div class="col-span-2 sm:col-span-1">
              <label for="card-number-input" class="mb-2 block text-2xl font-medium text-gray-900 dark:text-white"> Product Purchased Date:</label>
              <input type="date" id="card-number-input"  name="date" value="" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pe-10 text-2xl text-gray-900 focus:border-primary-500 focus:ring-primary-500  dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"required />
            </div>
              <div class="menu_vision">  
               <div class="col-span-2 sm:col-span-1">
              <label for="card-number-input" class="mb-2 block text-2xl font-medium text-gray-900 dark:text-white">Photo of the damages product: </label>
              <label for="Image" class="form-label" style="color: red;">(Note:) Photo of damaged product must be uploaded.</label>
              <input type="file" id="formFile" name="pp" onchange="preview()" id="card-number-input" name="cust_address" value="" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pe-10 text-2xl text-gray-900 focus:border-primary-500 focus:ring-primary-500  dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Address" required />
            </div>
            
            <img id="frame" src="" class="img-fluid" />
                   
                       
                   <!-- //////////// -->
                                             <div><input type="hidden" id="timestamp" name="upload_time" value="" onfocus="clearIntervalInstance()"
                                                       ondblclick="runIntervalInstance()">
                                                 </div>                          
                                               </div>
            </div>

           
           
          

          <button  name="form1" type="submit" class="flex w-full items-center justify-center rounded-lg bg-yellow-300 px-5 py-2.5 text-2xl font-medium text-white hover:bg-yellow-800 focus:outline-none focus:ring-4  focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Submit</button>
        </form>

        </div>
        <div class="mr-auto place-self-center lg:col-span-5">
            <img src="assets/img/maintain.png" alt="mockup">
        </div>                
    </div>
</section>

<!-- 
            <div class="col-md-12">
                <div class="user-content">
                   
                   <?php
if ($error_message != '') {
    echo "<script>alert('" . $error_message . "')</script>";

}
if ($success_message != '') {
   echo "<script>alert('" . $success_message . "')</script>";

}
?>
                <form action="" method="post" enctype="multipart/form-data">
                        <?php $csrf->echoInputField();?>
                         
                    <div class="row">
                        <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <h3>
                        <?php echo 'Free Maintenance'; ?>
                         
                    </h3>
                            <div class="form-group">
                                <label for=""><?php echo 'Customer Name:'; ?> </label>
                                <input type="text" class="form-control" name="cust_name" value="<?php echo $_SESSION['customer']['cust_name']; ?>">
                            </div>
                             <div class="form-group">
                                <label for=""><?php echo 'Phone Number'; ?> </label>
                                <input type="text" class="form-control" name="cust_phone" value="<?php echo $_SESSION['customer']['cust_phone']; ?>">
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo 'Product Name:'; ?></label>
                                <input type="text" class="form-control" name="product_name" value="">
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo 'Product Purchased Date:'; ?></label>
                                <input type="date" class="form-control" name="date" value="">
                            </div>
                        <div class="menu_vision">     
                            <div class="table table-borderless">
         
                            <div class="mb-5">
                                  <label for="Image" class="form-label">PHOTO OF THE DAMAGED PRODUCT:</label>
                                  <label for="Image" class="form-label" style="color: red;">(Note:) Photo of damaged product must be uploaded.</label>
                                  <input class="form-control" type="file" id="formFile" name="pp" onchange="preview()">
                             </div>
                            
                 
                   
                 
                         <img id="frame" src="" class="img-fluid" />
                   
                       

		            	  <div><input type="hidden" id="timestamp" name="upload_time" value="" onfocus="clearIntervalInstance()"
			    					ondblclick="runIntervalInstance()">
                              </div>                          
                            </div>
                         
                        </div>
                             
                    <br> 
                        
                        <input type="submit" class="btn btn-primary" value="<?php echo 'Submit'; ?>" name="form1">
                           
                            
                        </div>
                       
                    </div>
                </form>
                       

                </div>
            </div> -->
        </div>
    </div>
</div>


<?php require_once 'footer.php';?>

<script>
            function preview() {
                frame.src = URL.createObjectURL(event.target.files[0]);
            }
            function clearImage() {
                document.getElementById('formFile').value = null;
                frame.src = "";
            }

            if (window . history . replaceState) {
    window . history . replaceState(null, null, window . location . href);
}

        </script>



