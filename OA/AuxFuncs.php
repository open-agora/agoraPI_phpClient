<?php

use Ds\Set;

    /**
     * Computes a set of users which have cast at least one vote.
     *
     * @return set of users in a vote
     */
    function votersSet($votes) {
        $result = new Set();
    }

    /**
     * Constructs a string representation of a poll result.
     * (See on-line documentation: https://api.open-agora.com/docs/#!/polls/getPollResults )
     *
     * @return string of result
     */
    function stringResult($result) {
        $stringResult = '';
        foreach ($result as $aRank) {
            /* we iterate over ranks
             * Each rank is a json object formed by a choice, a rank and a score.
             */
            $aChoice = $aRank->choice;
            $stringResult = $stringResult . 'Choice: ' . $aChoice->label . ' Score: '. $aRank->score .'<br />';
        }
        return $stringResult;
    }
