<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$curlClient = new CurlClient();

$polls = json_decode($curlClient->listPolls());

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
                <div class="row">
                    <div class="col-md-8">
                        <h2>Create poll</h2>
                        <form method="POST" action="_create_poll.php">
                            <div class="form-group">
                                <input name="title" type="text" class="form-control" placeholder="Title">
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <h2>Polls list</h2>
                        <ul class="list-group">
                            <?php foreach ($polls as $poll): ?>
                                <li class="list-group-item">
                                    <?php echo $poll->title; ?><br />
                                    <a href="poll_choices.php?id=<?php echo $poll->id; ?>" class="btn btn-default" role="button">Edit</a>
                                    <a href="_delete_poll.php?id=<?php echo $poll->id; ?>" class="btn btn-default" role="button">Delete</a>
                                    <a href="vote_index.php?id=<?php echo $poll->id; ?>" class="btn btn-default" role="button">Vote</a>
                                </li>
                            <?php endforeach; ?>
                        </ul> 
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>