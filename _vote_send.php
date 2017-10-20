<?php

/**
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$pollId = $_POST['poll_id'];
$rank = $_POST['rank'];
$name = $_POST['name'];

if ($name == ""){
    $name="Anonymous";
}

if (empty($pollId)) {
    header('Location: curl_vote.php' . "?id=$pollId");
}

$user = array(
    'name' => $name,
);

$votes = array();
foreach($rank as $choiceId => $rank) {
    if ($rank == -1) {
        continue;
    }
    $vote = array(
        'choice_id' => $choiceId,
        'rank' => (int)$rank,
        'user' => $user,
    );
    $votes[] = $vote;
}

$curlClient = new CurlClient($baseUrl, $key);
$curlClient->sendVote($pollId, $votes);

header('Location: vote_index.php' . "?id=$pollId");
