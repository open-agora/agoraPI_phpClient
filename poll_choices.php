<?php

/**
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$pollId = $_GET['id'];

if (empty($pollId)) {
    header('Location: curl_index.php');
}

$curlClient = new CurlClient();

$choices = $curlClient->listChoices($pollId);
$pollData = $curlClient->getPollData($pollId);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit a poll: <?php echo $pollData->title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <hr />
                <h1 style="text-align:center;">Edit a poll: <?php echo $pollData->title; ?></h1>
                <hr />
                <div class="row">
                    <div class="col-md-6">
                        <h2>Add a choice</h2>
                        <form method="POST" action="_choice_create.php">
                            <input name="poll_id" type="hidden" value="<?php echo $pollId; ?>">
                            <div class="form-group">
                                <input name="label" type="text" class="form-control" placeholder="Label">
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h2>Choices list</h2>
                        <ul class="list-group">
                            <?php foreach ($choices as $choice): ?>
                                <li class="list-group-item">
                                    <?php echo $choice->label; ?><br />
                                    <div style="text-align:right;">
                                        <a href="_choice_delete.php?id=<?php echo $choice->id . "&poll_id=$pollId"; ?>" class="btn btn-danger" role="button">Delete</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p>
                            Careful: there is no confirmation asked. <b>Every deletion is final</b>.
                            It can have a severe impact on an on-going poll.
                        </p>
                    </div>
                </div>
                <div style="margin:2em 0 2em 0; text-align:center;">
                    <a href="poll_index.php" class="btn btn-lg btn-primary" role="button">Back to Polls Index</a>
                </div>
                <!-- The following caveat is important and should not be remosed unless you know precisely what you are doing. -->
                <?php include 'caveat.php'; ?>

            </div>
        </div>
    </body>
</html>
