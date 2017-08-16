<?php

/**
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$pollId = $_POST['poll_id'];
$label = $_POST['label'];

if (!empty($label)) {
    $curlClient = new CurlClient();
    $curlClient->createChoice($pollId, $label);
}

header('Location: poll_choices.php' . "?id=$pollId");
