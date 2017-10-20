<?php

/**
 * This file contains auxiliary php functions intended to remove uninteresting operations from main files.
 *
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */


/**
 * Constructs a string representation of a poll result (in this case a sequence of choice labels and scores).
 * (See online documentation: https://api.open-agora.com/docs/#!/polls/getPollResults )
 * This function is a simple example of extraction of some values from a poll result.
 *
 * @param object $result
 * @return string representation of $result
 */
function stringResult($result) {
    $stringResult = '';
    foreach ($result as $aRank) {
        /*
         * Iteration over ranks
         * Each rank is a json object formed by a choice, a rank and a score.
         */
        $aChoice = $aRank->choice;
        $stringResult = $stringResult . 'Choice: ' . $aChoice->label . ' Score: '. $aRank->score .'<br />';
    }
    return $stringResult;
}

/**
 * Computes an array formed by :
 *   the set of voters
 *   the string of non anonymous voters
 *   the number of anonymous voters
 *
 * @param string $votes
 * @return array
 */
function votersData($votes) {
    $resultSet = [];
    $names = "";
    $nbAnon = 0;
    foreach ($votes as $aVote) {
        /*
         * Iteration over votes
         * Get the user_id (the user) associated with each vote.
         * Add it in the array if not already in the array (like in a set).
         */
         $id = $aVote->user->id;
         if (! in_array($id , $resultSet )){
             $resultSet[]=$id;
             $username = $aVote->user->name;
             if ($username !="" && strcasecmp($username, "anonymous")!=0 ){
                 //strcasecmp: case insensitive comparison
                 if ($names == "")$names = $username;
                 else $names = $names.", ".$username;
             }else{
                 $nbAnon += 1;
             }
         }
    }
    return array($resultSet, $names, $nbAnon);
}


/**
 * Computes the number of voters from the votes.
 *
 * @param object $votes
 * @return string of result
 */
/*function nbVoters($votes) {
    return count( votersSet($votes, "id") );
    }*/
