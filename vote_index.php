<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';
require __DIR__ . '/OA/AuxFuncs.php';

$pollId = $_GET['id'];

if (empty($pollId)) {
    header('Location: curl_index.php');
}

$curlClient = new CurlClient();

$choices = $curlClient->listChoices($pollId);
$votes = $curlClient->getVotes($pollId);

$resultMajority = $curlClient->getResultUrl($pollId);
$resultCondorcet = $curlClient->getResultUrl($pollId, 'condorcet', 'hbar');

$rawMajority = $curlClient->getResult($pollId);
$resultMajorityText = stringResult($rawMajority);
$resultCondorcetText = stringResult($curlClient->getResult($pollId, 'condorcet'));
$nbVots = nbVoters($votes);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test API</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
        .majority {
            display: block;
        }
        .condorcet {
            display: none;
        }
        .mybutton{
            min-width: 30em;
        }
        </style>
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
                <p>Total number of voters: <?php echo $nbVots; ?></p>
                <button id="switch" onclick="switchDisplay()" class="btn btn-primary mybutton">Switch to Condorcet result</button>
                <div class="row">
                    <div class="col-md-12">
                        <div class='majority'>
                            <h2>Majority Result</h2>
                            <img src="<?php echo $resultMajority->url; ?>">
                        </div>
                        <div class='condorcet'>
                            <h2>Condorcet Result</h2>
                            <img src="<?php echo $resultCondorcet->url; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="majority">
                            <h2>Majority Result (textual)</h2>
                            <p><?php echo $resultMajorityText; ?></p>
                        </div>
                        <div class="condorcet">
                            <h2>Condorcet Result (textual)</h2>
                            <p><?php echo $resultCondorcetText; ?></p>
                        </div>
                    </div>
                </div>
                <br />
                <br />
                <br />
                <a href="poll_index.php" class="btn btn-lg btn-primary" role="button">Back</a>
            </div>
        </div>
        <script type="text/javascript">
        function switchDisplay() {
            var x = document.getElementsByClassName('majority');
            var y = document.getElementsByClassName('condorcet');
            var button = document.getElementById('switch')
            if (x[0].style.display === 'none') {
                x[0].style.display = 'block';
                x[1].style.display = 'block';
                y[0].style.display = 'none';
                y[1].style.display = 'none';
                button.innerText = 'Switch to Condorcet result' ;
            } else {
                x[0].style.display = 'none';
                x[1].style.display = 'none';
                y[0].style.display = 'block';
                y[1].style.display = 'block';
                button.innerText = 'Switch to majority result';
            }
        }
        </script>
    </body>
</html>
