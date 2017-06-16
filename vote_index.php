<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$pollId = $_GET['id'];

if (empty($pollId)) {
    header('Location: curl_index.php');
}

$curlClient = new CurlClient($baseUrl, $key);

$choices = $curlClient->listChoices($pollId);
$votes = $curlClient->getVotes($pollId);

$resultMajority = $curlClient->getResult($pollId);
$resultCondorcet = $curlClient->getResult($pollId, 'condorcet', 'hbar');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test API</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <h2>Add a new vote</h2>
                <form method="POST" action="_vote_send.php">
                    <input name="poll_id" type="hidden" value="<?php echo $pollId; ?>">
                    <ul class="list-group">
                        <?php foreach ((array) $choices as $choice): ?>
                            <li class="list-group-item">
                                <div class="form-group row">
                                    <div class="col-md-1"><?php echo $choice->num; ?></div>
                                    <div class="col-md-9"><?php echo $choice->label; ?></div>
                                    <div class="col-md-2">
                                        <select name="rank[<?php echo $choice->id; ?>]" class="form-control">
                                            <option value="-1">Unranked</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="submit" class="btn btn-primary">vote</button>
                </form>
                <hr />
                <p>nb votes : <?php echo count($votes); ?></p>
                <div class="row">
                    <div class="col-md-7">
                        <h2>Majority</h2>
                        <img src="<?php echo $resultMajority->url; ?>">
                    </div>
                    <div class="col-md-5">
                        <h2>Condorcet</h2>
                        <img src="<?php echo $resultCondorcet->url; ?>">
                    </div>
                </div>
                <br />
                <br />
                <br />
            </div>
        </div>
    </body>
</html>
