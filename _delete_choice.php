<?php

/**
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$id = $_GET['id'];
$pollId = $_GET['poll_id'];

if (!empty($id)) {
    $curlClient = new CurlClient();
    $curlClient->deleteChoice($id);
}

header('Location: poll_choices.php' . "?id=$pollId");
