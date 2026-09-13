<?php require_once 'header.php';?>

<?php



if (!isset($_REQUEST['id']) || !isset($_REQUEST['task'])) {
    header('location: logout.php');
    exit;
} else {
    // Check the id is valid or not 
    $statement = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if ($total == 0) {
        header('location: logout.php');
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_withdraw WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
   $balance = $row['acc_balance'];
   $deduct = $row['request_amount'];
   $pr = $row['cust_promo'];
if( $deduct <= $balance   ){
$new =  $balance - $deduct ;
$statement = $pdo->prepare("UPDATE tbl_withdraw SET status=?  WHERE id=? ");
$statement->execute(array($_REQUEST['task'], $_REQUEST['id']));

$statement = $pdo->prepare("UPDATE tbl_withdraw SET acc_balance=? WHERE cust_promo=? ");
$statement->execute(array($new, $pr));
}
else{
    $statement = $pdo->prepare("UPDATE tbl_withdraw SET comment=? WHERE cust_promo=? AND request_amount=? ");
$statement->execute(array('Request Amount Exceeds', $pr, $_REQUEST['req_amnt']));


}

}
header('location: withdraw_request.php');
?>