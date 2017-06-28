<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$pollId = $_POST['poll_id'];
$label = $_POST['label'];

if (!empty($label)) {
    $curlClient = new CurlClient();
    $curlClient->createChoice($pollId, $label);
}

header('Location: poll_choices.php' . "?id=$pollId");
