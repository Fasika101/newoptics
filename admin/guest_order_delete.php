<?php require_once 'header.php';?>

<?php
if (!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if ($total == 0) {
        header('location: logout.php');
        exit;
    } else {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $payment_id = $row['payment_id'];
            $payment_status = $row['payment_status'];
            $shipping_status = $row['shipping_status'];
        }
    }
}
?>

<?php

if (($payment_status == 'Completed') && ($shipping_status == 'Completed')):
    // No return to stock
else:
    // Return the stock
    $statement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
    $statement->execute(array($payment_id));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        // $statement1 = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
        // $statement1->execute(array($row['product_id']));
        // $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
        // foreach ($result1 as $row1) {
        //     $p_qty = $row1['p_qty'];
        // }
        // $final = $p_qty + $row['quantity'];
        // $statement1 = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
        // $statement1->execute(array($final, $row['product_id']));

////////////////////////////////
        $statement2 = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=? AND color_id=?");
        $statement2->execute(array($row['product_id'], $row['color_id']));
        $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result2 as $row2) {
            $color_qty = $row2['color_qty'];
        }
        $final_c = $color_qty + $row['quantity'];
        $statement2 = $pdo->prepare("UPDATE tbl_product_color SET color_qty=? WHERE p_id=? AND color_id=?");
        $statement2->execute(array($final_c, $row['product_id'], $row['color_id']));

        $statement7 = $pdo->prepare("SELECT SUM(color_qty) AS 'count_col' FROM tbl_product_color WHERE p_id=?  ");
        $statement7->execute(array($row['product_id']));
        $result7 = $statement7->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result7 as $row7) {

            $sum = $row7['count_col'];

        }
        $statement7 = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
        $statement7->execute(array($sum, $row['product_id']));

    }
endif;

// Delete from tbl_order
$statement = $pdo->prepare("DELETE FROM tbl_order WHERE payment_id=?");
$statement->execute(array($payment_id));

// Delete from tbl_payment
$statement = $pdo->prepare("DELETE FROM tbl_payment WHERE id=?");
$statement->execute(array($_REQUEST['id']));

header('location: guest_new.php');
?>

						<?php

// $statement7 = $pdo->prepare("SELECT SUM(color_qty) AS 'count_col' FROM tbl_product_color WHERE p_id=?  ");
// $statement7->execute(array($row['product_id']));
// $result7 = $statement7->fetchAll(PDO::FETCH_ASSOC);
// foreach ($result7 as $row7) {

//     $sum = $row7['count_col'];

// }
// $statement7 = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
// $statement7->execute(array($sum, $row['product_id']));

?>