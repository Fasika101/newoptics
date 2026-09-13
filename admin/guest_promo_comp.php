<?php require_once 'header.php';?>

<?php
if (!isset($_REQUEST['pro']) || !isset($_REQUEST['discount'])) {
    header('location: logout.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE cust_promo=?");
    $statement->execute(array($_REQUEST['pro']));
    $total = $statement->rowCount();
    if ($total == 0) {
        header('location: logout.php');
        exit;
    }
}
?>
<?php
$statement = $pdo->prepare("UPDATE tbl_payment SET promo_status=? WHERE id=?");
$statement->execute(array('Complete', $_REQUEST['id']));
?>

<?php

$statement = $pdo->prepare("SELECT * FROM promo WHERE promo_code=?");
$statement->execute(array($_REQUEST['pro']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $balance = $row['balance'];
    $pr = $row['promo_code'];

    $deduct = $_REQUEST['discount'];

    $new = $balance + $deduct;

    $statement = $pdo->prepare("UPDATE promo SET balance=? WHERE promo_code=?");
    $statement->execute(array($new, $_REQUEST['pro']));

}

header('location: guest_confirmed.php');
?>