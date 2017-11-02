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
/*
 *  Client creation. Poll data: choices, votes and general info.
 */
$curlClient = new CurlClient();

$choices = $curlClient->listChoices($pollId);
$votes = $curlClient->getVotes($pollId);
$pollData = $curlClient->getPollData($pollId);
$votersData = votersData($votes);
$nbVots = count ($votersData[0]);
$voterNames = $votersData[1];
$nbAnons  = $votersData[2];

/*
 * Results computation and presentation.
 * Majority, Condorcet and Instant runoff.
 */
$resultMajority = $curlClient->getResultUrl($pollId);
$resultCondorcet = $curlClient->getResultUrl($pollId, 'condorcet', 'hbar');
$resultRunoff = $curlClient->getResultUrl($pollId, 'runoff', 'vbar');

$rawMajority = $curlClient->getResult($pollId);
$resultMajorityText = stringResult($rawMajority);
$resultCondorcetText = stringResult($curlClient->getResult($pollId, 'condorcet'));
$resultRunoffText = stringResult($curlClient->getResult($pollId, 'runoff'));
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Vote for: <?php echo $pollData->title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
        .majority, .btn-cond, .btn-run {
            display: block;
        }
        .condorcet, .runoff, .btn-maj {
            display: none;
        }
        .btn-switch{
            margin-top: .5em;
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
                        <div class='runoff'>
                            <h2>Instant Runoff Result</h2>
                            <img src="<?php echo $resultRunoff->url; ?>">
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-top:1em;">
                        <button id="switch-cond" onclick="switchDisplay('cond')" class="btn mybutton btn-default btn-cond btn-switch">Switch to Condorcet result</button>
                        <button id="switch-run" onclick="switchDisplay('run')" class="btn mybutton btn-default btn-run btn-switch">Switch to Instant Runoff result</button>
                        <button id="switch-maj" onclick="switchDisplay('maj')" class="btn mybutton btn-default btn-maj btn-switch">Switch to Majority result</button>
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
                        <div class="runoff">
                            <h2>Instant Runoff Result (textual)</h2>
                            <p><?php echo $resultRunoffText; ?></p>
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
        function switchDisplay(resultType) {
            var divmaj = document.getElementsByClassName('majority');
            var divcond = document.getElementsByClassName('condorcet');
            var divrun = document.getElementsByClassName('runoff');
            var buttons =  document.getElementsByClassName('btn-switch');
            for (i = 0 ; i < buttons.length ; i++){
                if (buttons[i].id == 'switch-'.concat(resultType)){
                    buttons[i].style.display = 'none';
                }else{
                    // Buttons default to 'block'.
                    buttons[i].style.display = 'block';
                }
            }
            // Each of the div classes have the same number of members.
            // We iterate over divsmaj.length
            for (i = 0 ; i < divmaj.length ; i++){
                if (resultType == 'maj'){
                    divmaj[i].style.display = 'block';
                    divcond[i].style.display = 'none';
                    divrun[i].style.display = 'none';
                }else if (resultType == 'cond'){
                    divmaj[i].style.display = 'none';
                    divcond[i].style.display = 'block';
                    divrun[i].style.display = 'none';
                }else{
                    divmaj[i].style.display = 'none';
                    divcond[i].style.display = 'none';
                    divrun[i].style.display = 'block';

                }
            }
        }
        </script>
    </body>
</html>
