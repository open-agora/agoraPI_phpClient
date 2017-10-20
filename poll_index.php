<?php

/**
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$curlClient = new CurlClient();

$polls = $curlClient->listPolls();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Personal poll creation with AgoraPI</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <hr />
                <h1 style="text-align:center;">Personal poll creation with AgoraPI</h1>
                <hr />
                <div class="row">
                    <div class="col-md-7">
                        <h2>New poll creation</h2>
                        <form method="POST" action="_poll_create.php">
                            <div class="form-group">
                                <input name="title" type="text" class="form-control" placeholder="New Poll Title">
                            </div>
                            <p>
                                <em>You will have the possibility to add choices using the Edit button on the list, to the right.</em>
                            </p>
                            <div style="text-align:center;">
                                <button type="submit" class="btn btn-success">Create a new poll</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-5">
                        <h2>Polls list</h2>
                        <ul class="list-group">
                            <?php foreach ((array) $polls as $poll): ?>
                                <li class="list-group-item">
                                    <h3><?php echo $poll->title; ?></h3>
                                    <a href="vote_index.php?id=<?php echo $poll->id; ?>" class="btn btn-success" role="button">Vote/View Result</a>
                                    <a href="poll_choices.php?id=<?php echo $poll->id; ?>" class="btn btn-primary" role="button">Edit</a>
                                    <a href="_poll_delete.php?id=<?php echo $poll->id; ?>" class="btn btn-danger" role="button">Delete</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!-- The following caveat is important and should not be remosed unless you know precisely what you are doing. -->
                <?php include 'caveat.php'; ?>
            </div>
        </div>
    </body>
</html>
