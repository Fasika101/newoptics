<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
{
	$footer_about = $row['footer_about'];
	$contact_email = $row['contact_email'];
	$contact_phone = $row['contact_phone'];
	$contact_address = $row['contact_address'];
	$footer_copyright = $row['footer_copyright'];
	$total_recent_post_footer = $row['total_recent_post_footer'];
    $total_popular_post_footer = $row['total_popular_post_footer'];
  //  $newsletter_on_off = $row['newsletter_on_off'];
    $before_body = $row['before_body'];
}
?>




<section class="footer-main">
	<div class="container">
		<div class="row">
		    <div class="col-md-3 col-sm-6 footer-col">
				<h3>About Us</h3>
				<div class="row">
					<div class="col-md-12">
						<ul>
							<?php
				            $i = 0;
				            $statement = $pdo->prepare("SELECT * FROM tbl_post WHERE place = 0 ORDER BY post_id DESC");
				            $statement->execute();
				            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
				            foreach ($result as $row) {
				                $i++;
				                if($i > 7) {
				                    break;
				                }
				                ?>
				                <li><a href="blog-single.php?slug=<?php echo $row['post_slug']; ?>"><?php echo $row['post_title']; ?></a></li>
				                <?php
				            }
           					?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 footer-col">
				<h3><?php echo 'Tips and Guides'; ?></h3>
				<div class="row">
					<div class="col-md-12">
						<ul>
							<?php
				            $i = 0;
				            $statement = $pdo->prepare("SELECT * FROM tbl_post WHERE place = 1 ORDER BY post_id DESC ");
				            $statement->execute();
				            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
				            foreach ($result as $row) {
				                $i++;
				                if($i > 7) {
				                    break;
				                }
				                ?>
				                <li><a href="blog-single.php?slug=<?php echo $row['post_slug']; ?>"><?php echo $row['post_title']; ?></a></li>
				                <?php
				            }
           					?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 footer-col">
				<h3><?php echo 'Free Services'; ?></h3>
				<div class="row">
				    <div class="col-md-12">
					
						<ul>
							
				              
				        <li>
					<div class="text"><a href="see_frame.php">See Frame First</a</div></li>
					<li>
					<div class="text"><a href="free-maintenance.php">Free Maintenance</a></div></li>
					
				             
				            
						</ul>
						</div>
					
				</div>
			</div>
			<!--<div class="col-md-3 col-sm-6 footer-col">-->
			<!--	<h3><?php echo 'Free Services'; ?></h3>-->
			<!--	<div class="row">-->
					
			<!--			<ul>-->
							
			<!--	                <li><a href="#">See Frame First</a></li>-->
			<!--					 <li><a href="free-maintenance.php">Free Maintenance<a></li>-->
				             
				            
			<!--			</ul>-->
				
			<!--	</div>-->
			<!--</div>	-->
		
			
			
			<div class="col-md-3 col-sm-6 footer-col">
				<h3><?php echo LANG_VALUE_114; ?></h3>
				<div class="contact-item">
					<div class="text"><?php echo nl2br($contact_address); ?></div>
				</div>
				<div class="contact-item">
					<div class="text"><?php echo '+251 919 485109'; ?></div>
				</div>
				<div class="contact-item">
				    <ul>
				        <li>
					<div class="text"><?php echo 'info@newonlineoptics.com'; ?></div></li>
					<li>
					<div class="text"><?php echo 'help@newonlineoptics.com'; ?></div></li>
					</ul>
				</div>
					<div class="col-md-12">
							<h3><?php echo 'Social Links'; ?></h3>
			
			    	<div class="contact-item">
				    	<div >
					         <a style="font-size: 2rem;" class="bi bi-facebook"  href="https://www.facebook.com/newopticsethiopia" ></a> &nbsp;&nbsp;
					         <a style="font-size: 2rem;" class="bi bi-instagram"  href="https://instagram.com/new_online_optics?igshid=YmMyMTA2M2Y=" ></a> &nbsp;&nbsp;
					         <a style="font-size: 2rem;" class="bi bi-telegram"  href="https://t.me/newoptics" ></a>&nbsp;&nbsp;
					         <a style="font-size: 2rem;" class="bi bi-tiktok"  href="https://vm.tiktok.com/ZMF9Sx9hs" ></a>
				    	</div>
			    	</div>
				
				
				
			   </div>
			   </div>
				
				
				
			
			</div>

		</div>

</section>



<div class="footer-bottom">
	<div class="container">
		<div class="row">
			<div class="col-md-12 copyright">
				<?php echo 'New Online Optics &copy; '.date ('Y') .'.'. 'All Rights Reserved' ; ?>
			</div>
		</div>
	</div>
</div>


<a href="#" class="scrollup" >
	<i class="fa fa-angle-up" style="background-color: black;">
	<!-- Ask	<a href="chat.php"> -->
</i>
	<?php //require_once 'chat.php';?>

</a>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $stripe_public_key = $row['stripe_public_key'];
    $stripe_secret_key = $row['stripe_secret_key'];
}
?>

<script src="assets/js/jquery-2.2.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="https://js.stripe.com/v2/"></script>
<script src="assets/js/megamenu.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/owl.animate.js"></script>
<script src="assets/js/jquery.bxslider.min.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/rating.js"></script>
<script src="assets/js/jquery.touchSwipe.min.js"></script>
<script src="assets/js/bootstrap-touch-slider.js"></script>
<script src="assets/js/select2.full.min.js"></script>
<script src="assets/js/custom.js"></script>
<script>
	function confirmDelete()
	{
	    return confirm("Do you sure want to delete this data?");
	}
	$(document).ready(function () {
		advFieldsStatus = $('#advFieldsStatus').val();

		$('#paypal_form').hide();
		$('#stripe_form').hide();
		$('#bank_form').hide();

        $('#advFieldsStatus').on('change',function() {
            advFieldsStatus = $('#advFieldsStatus').val();
            if ( advFieldsStatus == '' ) {
            	$('#paypal_form').hide();
				$('#stripe_form').hide();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'PayPal' ) {
               	$('#paypal_form').show();
				$('#stripe_form').hide();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'Stripe' ) {
               	$('#paypal_form').hide();
				$('#stripe_form').show();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'Bank Deposit' ) {
            	$('#paypal_form').hide();
				$('#stripe_form').hide();
				$('#bank_form').show();
            }
        });
	});


	$(document).on('submit', '#stripe_form', function () {
        // createToken returns immediately - the supplied callback submits the form if there are no errors
        $('#submit-button').prop("disabled", true);
        $("#msg-container").hide();
        Stripe.card.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
            // name: $('.card-holder-name').val()
        }, stripeResponseHandler);
        return false;
    });
    Stripe.setPublishableKey('<?php echo $stripe_public_key; ?>');
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('#submit-button').prop("disabled", false);
            $("#msg-container").html('<div style="color: red;border: 1px solid;margin: 10px 0px;padding: 5px;"><strong>Error:</strong> ' + response.error.message + '</div>');
            $("#msg-container").show();
        } else {
            var form$ = $("#stripe_form");
            var token = response['id'];
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            form$.get(0).submit();
        }
    }
</script>
<?php //require_once 'chat.php';

?>
<script type="text/javascript" src="https://apiv2.popupsmart.com/api/Bundle/400455" async></script>
</body>
</html>