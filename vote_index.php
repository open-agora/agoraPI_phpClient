<?php

/**
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */


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
$pollData = $curlClient->getPollData($pollId);

$resultMajority = $curlClient->getResultUrl($pollId);
$resultCondorcet = $curlClient->getResultUrl($pollId, 'condorcet', 'hbar');

$rawMajority = $curlClient->getResult($pollId);
$resultMajorityText = stringResult($rawMajority);
$resultCondorcetText = stringResult($curlClient->getResult($pollId, 'condorcet'));
$votersData = votersData($votes);
$nbVots = count ($votersData[0]);
$voterNames = $votersData[1];
$nbAnons  = $votersData[2];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Vote for: <?php echo $pollData->title; ?></title>
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
            min-width: 20em;
            background-color: #ff9700;
            color: #fff;
            border-color: #ff9700;
        }
        .mybutton:hover, .mybutton:active, .mybutton:focus {
            background-color: #fa8600;
            color: #fff;
            border-color: #fa8600;
        }
        .mybutton:active:focus {
            background-color: #ef7800;
            color: #fff;
            border-color: #ef7800;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <hr />
                <h1 style="text-align:center;"><?php echo $pollData->title; ?></h1>
                <hr />
                <h3>Please cast your vote</h3>
                <form method="POST" action="_vote_send.php">
                    <div class="form-group">
                         <label for="name">Please type your name (otherwise anonymous):</label>
                         <input type="text" class="form-control" id="name" name="name" placeholder="Type your name here">
                    </div>
                    <input name="poll_id" type="hidden" value="<?php echo $pollId; ?>">
                    <div>
                        <p>
                            You vote by ranking some of the choices below (rank 1 is the best, unranked is the worst). Once your ranking are done do not forget to
                            submit your vote using the "Vote" button below the list of choices.
                        </p>
                    </div>
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
                    <div style="margin:1em 0 1em 0; text-align:center;">
                        <button type="submit" class="btn btn-success">Vote</button>
                    </div>
                </form>
                <hr />
                <h2 style="text-align:center;">Results</h2>
                <hr />
                <p>Total number of voters: <?php echo $nbVots; ?></p>
                <p>Anonymous voters: <?php echo $nbAnons; ?></p>
                <p>Other voters: <?php echo $voterNames; ?></p>
                <div class="row">
                    <div class="col-md-8">
                        <div class='majority'>
                            <h2>Majority Result</h2>
                            <img src="<?php echo $resultMajority->url; ?>">
                        </div>
                        <div class='condorcet'>
                            <h2>Condorcet Result</h2>
                            <img src="<?php echo $resultCondorcet->url; ?>">
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top:1em;">
                        <button id="switch" onclick="switchDisplay()" class="btn mybutton btn-default">Switch to Condorcet result</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
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
                <div style="margin:2em 0 2em 0; text-align:center;">
                    <a href="poll_index.php" class="btn btn-lg btn-primary" role="button">Back to Polls Index</a>
                </div>
                <!-- The following caveat is important and should not be remosed unless you know precisely what you are doing. -->
                <?php include 'caveat.php'; ?>
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
