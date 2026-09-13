<?php require_once 'header.php';?>
<style type="text/css">

    body
    {
        background:#f2f2f2;
    }

    .payment
	{
		border:1px solid #f2f2f2;
		height:280px;
        border-radius:20px;
        background:#fff;
	}
   .payment_header
   {
	   background:rgba(50,205,50);
	   padding:20px;
       border-radius:20px 20px 0px 0px;

   }

   .check
   {
	   margin:0px auto;
	   width:50px;
	   height:50px;
	   border-radius:100%;
	   background:#fff;
	   text-align:center;
   }

   .check i
   {
	   vertical-align:middle;
	   line-height:50px;
	   font-size:30px;
   }

    .content
    {
        text-align:center;
        padding-right: 20px;
        padding-left: 20px;
    }

    .content  h1
    {
        font-size:25px;
        padding-top:25px;
    }

    .content a
    {
        width:200px;
        height:35px;
        color:#fff;
        border-radius:30px;
        padding:5px 10px;
        background:rgba(0,255,0);
        transition:all ease-in-out 0.3s;
    }

    .content a:hover
    {
        text-decoration:none;
        background:#000;
    }
    .ff{
        display: flex;
  justify-content: center;
  align-items: center;
    }


</style>
<div class="page">




                   <div class="container">
   <div class="row" >
    <div class="ff">
      <div class="col-md-6 mx-auto mt-5">
         <div class="payment">
            <div class="payment_header">
               <div class="check"><i class="fa fa-check" aria-hidden="true"></i></div>
            </div>
            <div class="content">
               <h1>Congratulations, <?php
             
               if (empty($_REQUEST['name'])) {
    echo "";
} else {
    echo $_REQUEST['name'];
}

             ?></h1>
              
               <p>Your account has been created successfully. Please open your email and follow the steps provided to verify your account.</p>
               <a href="login.php">Go to Log in</a>
            </div>

         </div>
      </div>
      </div>
   </div>
</div>

</div>

<?php require_once 'footer.php';?>