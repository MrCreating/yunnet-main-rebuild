<?php

// delete domain
$fileData = array_slice(explode('/', $_SERVER['REQUEST_URI'], 3), 1);

$folderTypes = ['js', 'css', 'images'];
$contentType = 'text/plain';

$folder = strtolower(basename($fileData[0]));
if (!in_array($folder, $folderTypes)) {
    header('Content-Type: ' . $contentType);
    echo "File not found";
    http_response_code(404);
}

$file = strtolower(basename($fileData[1]));
$filepath = __DIR__ . '/' . $folder . '/' . $file;

if (file_exists($filepath)) {
    if ($folder == 'js') $contentType = 'text/javascript';
    if ($folder == 'css') $contentType = 'text/css';
    if ($folder == 'images') $contentType = 'image/png';

    header('Content-Type: ' . $contentType);
    echo file_get_contents($filepath);
} else {
    echo "File not found";
    header('Content-Type: ' . $contentType);
    http_response_code(404);
}

?>