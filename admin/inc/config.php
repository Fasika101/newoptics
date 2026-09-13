<?php
// Error Reporting Turn On
ini_set('error_reporting', E_ALL);

// Setting up the time zone
date_default_timezone_set('Asia/Dhaka');

// Host Name
$dbhost = 'localhost';

// Database Name
$dbname = 'newecom';

// Database Username
$dbuser = 'root';

// Database Password
$dbpass = '';

// Defining base url
//$config['BASE_URL'] = 'http://localhost/ecomnewoptics/fromgit/newopticsonline/';
define('BASE_URL', 'http://localhost/ecomnewoptics/fromgit/newopticsonline/');

// Getting Admin url
define('ADMIN_URL', BASE_URL . 'admin' . '/');

// Google Gemini API key (get one at https://aistudio.google.com/apikey)
// Used by scan-prescription.php to auto-read uploaded prescription photos
define('GEMINI_API_KEY', 'AIzaSyAaSRkyRoRBPSCYxWTo03V_tuFd0zocM1Y');
define('GEMINI_MODEL', 'gemini-3.6-flash');

// Chapa payment gateway secret key
define('CHAPA_SECRET_KEY', 'CHASECK-SxIJ2hhNWvxLQTA3PkMDkzphahELm39s');

try {
	$pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch( PDOException $exception ) {
	echo "Connection error :" . $exception->getMessage();
}


