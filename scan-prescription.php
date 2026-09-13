<?php
/**
 * Prescription scanner endpoint.
 * Receives an uploaded prescription photo, sends it to the Google Gemini API
 * and returns the extracted values as JSON, so the product page can auto-fill
 * the SPH / CYL / Axis / ADD / PD dropdowns.
 *
 * POST (multipart/form-data): prescr_photo = image file
 * Response (JSON): { success: true, data: {...} } or { success: false, error: "..." }
 */

require_once('admin/inc/config.php');

header('Content-Type: application/json');

function respond_error($message, $http_code = 400) {
    http_response_code($http_code);
    echo json_encode(array('success' => false, 'error' => $message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_error('Invalid request method.', 405);
}

if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === '' || GEMINI_API_KEY === 'PASTE_YOUR_GEMINI_API_KEY_HERE') {
    respond_error('Prescription scanning is not configured (missing API key).', 500);
}

if (!isset($_FILES['prescr_photo']) || $_FILES['prescr_photo']['error'] !== UPLOAD_ERR_OK) {
    respond_error('No prescription photo received.');
}

$tmp_path = $_FILES['prescr_photo']['tmp_name'];

// Max 8 MB
if (filesize($tmp_path) > 8 * 1024 * 1024) {
    respond_error('Image is too large. Maximum size is 8 MB.');
}

// Validate that it really is an image and get its mime type
$image_info = @getimagesize($tmp_path);
if ($image_info === false) {
    respond_error('The uploaded file is not a valid image.');
}
$allowed_mimes = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
$mime = $image_info['mime'];
if (!in_array($mime, $allowed_mimes)) {
    respond_error('Only JPG, PNG, GIF or WEBP images are allowed.');
}

$image_base64 = base64_encode(file_get_contents($tmp_path));

$prompt = <<<'PROMPT'
You are an optical prescription reader. Analyze this photo of an eyeglasses
prescription (it may be handwritten or printed) and extract the values.

Rules:
- OD = right eye, OS = left eye. R/RE = right, L/LE = left.
- SPH (sphere), CYL (cylinder) and ADD values must be returned as signed decimals
  rounded to the nearest 0.25 step, formatted like "-2.50", "+1.75" or "0.00".
  A value written as "plano", "pl" or 0 means "0.00".
- AXIS is an integer between 0 and 180 (no degree sign).
- PD (pupillary distance) may be one combined number (e.g. 63) or two monocular
  numbers for right/left (e.g. 31.5/31.0).
- vision_type: "progressive" if there is an ADD / NEAR / bifocal / progressive
  indication, "single" if it is clearly distance-only or reading-only single
  vision, otherwise "unknown".
- Use null for any value that is not present or not readable. NEVER guess.
- confidence: "high" if everything was clearly readable, "medium" if some values
  were hard to read, "low" if the image is blurry or barely readable.
- notes: one short sentence about anything the customer should double check
  (empty string if nothing).
PROMPT;

$response_schema = array(
    'type' => 'OBJECT',
    'properties' => array(
        'is_prescription' => array('type' => 'BOOLEAN'),
        'vision_type' => array('type' => 'STRING', 'enum' => array('single', 'progressive', 'unknown')),
        'right_eye' => array(
            'type' => 'OBJECT',
            'properties' => array(
                'sph' => array('type' => 'STRING', 'nullable' => true),
                'cyl' => array('type' => 'STRING', 'nullable' => true),
                'axis' => array('type' => 'INTEGER', 'nullable' => true),
                'add' => array('type' => 'STRING', 'nullable' => true),
            ),
        ),
        'left_eye' => array(
            'type' => 'OBJECT',
            'properties' => array(
                'sph' => array('type' => 'STRING', 'nullable' => true),
                'cyl' => array('type' => 'STRING', 'nullable' => true),
                'axis' => array('type' => 'INTEGER', 'nullable' => true),
                'add' => array('type' => 'STRING', 'nullable' => true),
            ),
        ),
        'pd' => array(
            'type' => 'OBJECT',
            'properties' => array(
                'type' => array('type' => 'STRING', 'enum' => array('one', 'two', 'none')),
                'one' => array('type' => 'NUMBER', 'nullable' => true),
                'right' => array('type' => 'NUMBER', 'nullable' => true),
                'left' => array('type' => 'NUMBER', 'nullable' => true),
            ),
        ),
        'confidence' => array('type' => 'STRING', 'enum' => array('high', 'medium', 'low')),
        'notes' => array('type' => 'STRING'),
    ),
    'required' => array('is_prescription', 'vision_type', 'right_eye', 'left_eye', 'pd', 'confidence'),
);

$payload = array(
    'contents' => array(
        array(
            'parts' => array(
                array('text' => $prompt),
                array('inline_data' => array('mime_type' => $mime, 'data' => $image_base64)),
            ),
        ),
    ),
    'generationConfig' => array(
        'temperature' => 0,
        'responseMimeType' => 'application/json',
        'responseSchema' => $response_schema,
    ),
);

$model = defined('GEMINI_MODEL') ? GEMINI_MODEL : 'gemini-2.5-flash';
$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent';

$ch = curl_init($url);
curl_setopt_array($ch, array(
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'x-goog-api-key: ' . GEMINI_API_KEY,
    ),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 60,
));

// WAMP's PHP has no system CA bundle, so point cURL at the one shipped with the project
$ca_bundle = __DIR__ . '/admin/inc/cacert.pem';
if (file_exists($ca_bundle)) {
    curl_setopt($ch, CURLOPT_CAINFO, $ca_bundle);
}
$api_response = curl_exec($ch);
$curl_error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($api_response === false) {
    respond_error('Could not reach the scanning service: ' . $curl_error, 502);
}
if ($http_code < 200 || $http_code >= 300) {
    $decoded = json_decode($api_response, true);
    $api_msg = isset($decoded['error']['message']) ? $decoded['error']['message'] : ('HTTP ' . $http_code);
    respond_error('Scanning service error: ' . $api_msg, 502);
}

$decoded = json_decode($api_response, true);
if (!isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
    respond_error('The scanning service returned an unexpected response.', 502);
}

$data = json_decode($decoded['candidates'][0]['content']['parts'][0]['text'], true);
if (!is_array($data)) {
    respond_error('Could not parse the scanned prescription data.', 502);
}

if (isset($data['is_prescription']) && $data['is_prescription'] === false) {
    respond_error('This image does not look like an eyeglasses prescription. Please upload a clear photo of your prescription.');
}

echo json_encode(array('success' => true, 'data' => $data));
