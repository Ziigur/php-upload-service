<?php
header('Content-Type: application/json');
$host = $_SERVER['HTTP_HOST']; // Get the server host
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http"; // Handle HTTP/HTTPS
$fullUrl = $protocol . "://" . $host;

$output = [];
$output['actions'] = array();
array_push(
    $output['actions'],
    array("action"=>"upload", "url"=>$fullUrl . '/upload.php', "method"=>"POST")
);

echo json_encode($output, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
echo "\n";
