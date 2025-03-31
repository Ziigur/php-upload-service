<?php
// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle OPTIONS request (preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Use POST to upload files\n";
    exit;
}
if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo "No file uploaded\n";
    exit;
}

$env = loadEnv(__DIR__ . '/.env');

$uploadDir = __DIR__ . "/uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$file = $_FILES['file'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileSize = $_FILES['file']['size'];
$newName = uniqid() . '.' . $ext;
$targetFile = $uploadDir . $newName;

$maxSize = $env["MAX_STORAGE"] ?? 100 * 1024 * 1024; // 100MB
if (getDirectorySize($uploadDir) + $fileSize > $maxSize) {
    http_response_code(500);
    echo "Storage limit reached\n";
    exit;
}

if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
    http_response_code(500);
    echo "Failed to upload file\n";
    exit;
}

$host = $_SERVER['HTTP_HOST']; // Get the server host
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http"; // Handle HTTP/HTTPS
$fullUrl = $protocol . "://" . $host . "/uploads/" . urlencode($newName);

header('Content-Type: application/json');
$output = array(
    "url" => $fullUrl
);
echo json_encode($output, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
echo "\n";


function getDirectorySize($dir) {
    $size = shell_exec("du -sb " . escapeshellarg($dir) . " | cut -f1");
    return (int)trim($size);
}

function loadEnv($file) {
    if (!file_exists($file)) {
        return [];
    }
    return parse_ini_file($file);
}
