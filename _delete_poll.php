<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$id = $_GET['id'];

if (!empty($id)) {
    $curlClient = new CurlClient($baseUrl, $key);
    $curlClient->deletePoll($id);
}

header('Location: poll_index.php'); 
