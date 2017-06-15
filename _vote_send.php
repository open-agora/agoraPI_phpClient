<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$pollId = $_POST['poll_id'];
$rank = $_POST['rank'];

if (empty($pollId)) {
    header('Location: curl_vote.php' . "?id=$pollId");
}

$votes = array();
foreach($rank as $choiceId => $rank) {
    if ($rank == -1) {
        continue;
    }
    $vote = array(
        'choice_id' => $choiceId,
        'rank' => (int)$rank,
    );
    $votes[] = $vote;
}

$curlClient = new CurlClient($baseUrl, $key);
$curlClient->sendVote($pollId, $votes);

header('Location: vote_index.php' . "?id=$pollId");
