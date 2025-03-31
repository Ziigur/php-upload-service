<?php
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

$uploadDir = __DIR__ . "/uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$file = $_FILES['file'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$newName = uniqid() . '.' . $ext;
$targetFile = $uploadDir . $newName;

if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
    http_response_code(500);
    echo "Failed to upload file\n";
    exit;
}

echo "File uploaded successfully\n";
