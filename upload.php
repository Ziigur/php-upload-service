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
