<?php require_once 'header.php';?>

<?php
if (!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM promo WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if ($total == 0) {
        header('location: logout.php');
        exit;
    }
}
?>

<?php

// Delete from tbl_customer
$statement = $pdo->prepare("DELETE FROM promo WHERE id=?");
$statement->execute(array($_REQUEST['id']));



header('location: promo_page.php');
?>