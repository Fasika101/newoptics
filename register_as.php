<?php require_once 'header.php';?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
    $contact_email = $row['contact_email'];
    $receive_email_thank_you_message = $row['receive_email_thank_you_message'];
}
?>
<script src="assets/country/country.js"></script>
<?php
if (isset($_POST['form1'])) {

    $valid = 1;

    if (empty($_POST['cust_name'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_123 . "<br>";
    }

    if (empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_131 . "<br>";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= LANG_VALUE_134 . "<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            $total = $statement->rowCount();
            if ($total) {
                $valid = 0;
                $error_message .= LANG_VALUE_147 . "<br>";
            }
        }
    }

    if (empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_124 . "<br>";
    }

    // if(empty($_POST['cust_address'])) {
    //     $valid = 0;
    //     $error_message .= LANG_VALUE_125."<br>";
    // }

    if (empty($_POST['cust_country'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_126 . "<br>";
    }

    if (empty($_POST['cust_region'])) {
        $valid = 0;
        $error_message .= 'Region can not be empty' . "<br>";
    }

    if (empty($_POST['cust_city'])) {
        $valid = 0;
        $error_message .= 'City can not be empty' . "<br>";
    }

    // if(empty($_POST['cust_zip'])) {
    //     $valid = 0;
    //     $error_message .= LANG_VALUE_129."<br>";
    // }

    if (empty($_POST['cust_password']) || empty($_POST['cust_re_password'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_138 . "<br>";
    }

    if (!empty($_POST['cust_password']) && !empty($_POST['cust_re_password'])) {
        if ($_POST['cust_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message .= LANG_VALUE_139 . "<br>";
        }
    }

    if ($valid == 1) {

        $token = md5(time());
        $cust_datetime = date('Y-m-d h:i:s');
        $cust_timestamp = time();

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_customer (
                                        cust_name,
                                        cust_cname,
                                        cust_email,
                                        cust_phone,
                                        cust_country,
                                        cust_region,
                                        cust_city,

                                        cust_password,
                                        cust_token,
                                        cust_datetime,
                                        cust_timestamp,
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
            strip_tags($_POST['cust_name']),
            strip_tags($_POST['cust_cname']),
            strip_tags($_POST['cust_email']),
            strip_tags($_POST['cust_phone']),
            strip_tags($_POST['cust_country']),
            strip_tags($_POST['cust_region']),
            strip_tags($_POST['cust_city']),

            md5($_POST['cust_password']),
            $token,
            $cust_datetime,
            $cust_timestamp,
            1,
        ));

    }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="row d-flex justify-content-center text-center">
        <div class="col-4">
            <span><h4>Registration Page</h4></span>
        </div>
    </div>
</div>


<div class="page" style="padding-top: 0px;" >
<div class="container">
  <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
            <div class="caption">
                <h3>Individual Customer</h3>
                <p>Register as an individual customer and ... </p></p>
                <p><a href="registration.php" class="btn btn-primary" role="button">Register Now</a>
            </div>
            </div>
        </div>
        <!-- <div class="col-sm-6 col-md-4">-->
        <!--    <div class="thumbnail">-->
        <!--    <div class="caption">-->
        <!--        <h3>Company</h3>-->
        <!--        <p>Register as a company and ... </p></p>-->
        <!--        <p><a href="register_company.php" class="btn btn-primary" role="button">Register Now</a>-->
        <!--    </div>-->
        <!--    </div>-->
        <!--</div>-->
         <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
            <div class="caption">
                <h3>Become partner</h3>
                <p>Register as an partner customer and ... </p></p>
                <p><a href="register_partner.php" class="btn btn-primary" role="button">Register Now</a>
            </div>
            </div>
        </div>
    </div>


</div>
      </div>

<?php require_once 'footer.php';?>
