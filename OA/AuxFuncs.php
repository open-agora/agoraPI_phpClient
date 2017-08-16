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
 * Computes an array of voters from the votes of a given poll.
 *
 * @param string $votes
 * @return Set of voters
 */
function votersSet($votes) {
    $result = [];
    foreach ($votes as $aVote) {
        /*
         * Iteration over votes
         * Get the user_id (the user) associated with each vote.
         * Add it in the array if not already in the array (like in a set).
         */
         if (! in_array($aVote->user_id , $resultSet )){
             $resultSet[]=$aVote->user_id;
         }
    }
    return $resultSet;
}


/**
 * Computes the number of voters from the votes.
 *
 * @param object $votes
 * @return string of result
 */
function nbVoters($votes) {
    return count( votersSet($votes) );
}
