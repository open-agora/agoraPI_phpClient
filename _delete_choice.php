<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$id = $_GET['id'];
$pollId = $_GET['poll_id'];

if (!empty($id)) {
    $curlClient = new CurlClient($baseUrl, $key);
    $curlClient->deleteChoice($id);
}

header('Location: poll_choices.php' . "?id=$pollId");