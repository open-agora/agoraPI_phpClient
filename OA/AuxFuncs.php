<?php


    /**
     * Constructs a string representation of a poll result.
     * (See on-line documentation: https://api.open-agora.com/docs/#!/polls/getPollResults )
     *
     * @param object $result
     * @return string of result
     */
    function stringResult($result) {
        $stringResult = '';
        foreach ($result as $aRank) {
            /*
             * We iterate over ranks
             * Each rank is a json object formed by a choice, a rank and a score.
             */
            $aChoice = $aRank->choice;
            $stringResult = $stringResult . 'Choice: ' . $aChoice->label . ' Score: '. $aRank->score .'<br />';
        }
        return $stringResult;
    }

    /**
     * Computes the number of voters from the majority result.
     *
     * @param object $majorityResult
     * @return string of result
     */
    function nbVotersOld($majorityResult) {
        $total = 0;
        foreach ($majorityResult as $aRank) {
            /**
             * we iterate over ranks
             */
             $total += $aRank->score;
        }
        return $total;
    }

    /**
     * Computes the set of voters from the votes of a given poll.
     *
     * @param string $votes
     * @return Set of voters
     */
    function votersSet($votes) {
        $resultSet = [];
        foreach ($votes as $aVote) {
            /**
             * we iterate over votes
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
     * @param object $majorityResult
     * @return string of result
     */
    function nbVoters($votes) {
        return count( votersSet($votes) );
    }
