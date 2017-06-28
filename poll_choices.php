<?php

use OA\CurlClient;

require __DIR__ . '/OA/CurlClient.php';

$pollId = $_GET['id'];

if (empty($pollId)) {
    header('Location: curl_index.php');
}

$curlClient = new CurlClient();

$choices = $curlClient->listChoices($pollId);

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
                    <div class="col-md-6">
                        <h2>Create choice</h2>
                        <form method="POST" action="_create_choice.php">
                            <input name="poll_id" type="hidden" value="<?php echo $pollId; ?>">
                            <div class="form-group">
                                <input name="label" type="text" class="form-control" placeholder="Label">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h2>Choices list</h2>
                        <ul class="list-group">
                            <?php foreach ($choices as $choice): ?>
                                <li class="list-group-item">
                                    <?php echo $choice->label; ?><br />
                                    <a href="_delete_choice.php?id=<?php echo $choice->id . "&poll_id=$pollId"; ?>" class="btn btn-default" role="button">Delete</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <a href="poll_index.php" class="btn btn-lg btn-primary" role="button">Back</a>
            </div>
        </div>
    </body>
</html>
