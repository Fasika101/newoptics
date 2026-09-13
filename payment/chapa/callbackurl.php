<?php
/**
 * Chapa server-to-server callback.
 * Chapa calls this URL itself after a payment attempt, passing the transaction
 * reference in the request (trx_ref / tx_ref). This runs even if the customer
 * never returns to the site, so it is the reliable place to confirm payments.
 *
 * IMPORTANT: the tx_ref must come from Chapa's request, NOT from the session —
 * Chapa's server has no session cookie with this site.
 */

require_once '../../admin/inc/config.php';

// Chapa sends the reference as a query parameter (naming has varied between
// trx_ref and tx_ref), so accept both, GET or POST.
$tx_ref = '';
foreach (array('trx_ref', 'tx_ref') as $param) {
    if (!empty($_GET[$param])) { $tx_ref = $_GET[$param]; break; }
    if (!empty($_POST[$param])) { $tx_ref = $_POST[$param]; break; }
}

if ($tx_ref === '') {
    http_response_code(400);
    echo 'missing transaction reference';
    exit;
}

// Verify the transaction with Chapa before trusting anything
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.chapa.co/v1/transaction/verify/' . urlencode($tx_ref),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . CHAPA_SECRET_KEY,
    ),
));

// Use the project CA bundle when the server has none (local WAMP)
$ca_bundle = __DIR__ . '/../../admin/inc/cacert.pem';
if (file_exists($ca_bundle)) {
    curl_setopt($curl, CURLOPT_CAINFO, $ca_bundle);
}

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    http_response_code(502);
    echo 'verify request failed';
    exit;
}

$data = json_decode($response, true);
$payment_verified = isset($data['status'], $data['data']['status'])
    && $data['status'] === 'success'
    && $data['data']['status'] === 'success';

if ($payment_verified) {
    $statement = $pdo->prepare("UPDATE tbl_payment SET payment_status=? WHERE payment_id=? AND payment_status='Pending'");
    $statement->execute(array('Completed', $tx_ref));
    echo 'ok';
} else {
    // Payment failed or was cancelled: leave the record as Pending
    echo 'not verified';
}
