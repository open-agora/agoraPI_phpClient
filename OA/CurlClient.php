<?php

/**
 * This file defines a class used to perform basic operations related to AgoraPI.
 * Most methods of this class interact with AgoraPI and relate to elements located in this API.
 *
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */

namespace OA;

class CurlClient {

    private $baseUrl;
    private $token;

    /**
     * Constructor: parses api.ini to initialize API base URL and key token.
     */
    public function __construct() {
        $ini = parse_ini_file("api.ini");
        $this->baseUrl = $ini['base_url'];
        $this->token = $ini['token'];
    }

    /**
     * Queries AgoraPI to get all the polls associated with the client key.
     * Returns an array of poll objects.
     * @return array of poll objects
     */
    public function listPolls() {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'page_size' => 100,
        ));
        $url = $this->baseUrl . '/polls?' . $query;
        return json_decode($this->get($url));
    }

    /**
     * Quick creation of a poll with minimal information (title only).
     * @param string $title
     * @return poll object
     */
    public function createPoll($title) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . '/polls?' . $query;
        $data = ["title" => $title];

        return json_decode($this->post($url, $data));
    }

    /**
     * Deletes a poll associated with ID $pollId.
     * @param string $pollId
     * @return RESULT
     */
    public function deletePoll($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/polls/$pollId?" . $query;
        return $this->delete($url);
    }

    /**
     * Returns the list of choices associated to the poll of ID $pollId
     * @param string $pollId
     * @return array of choice objects
     */
    public function listChoices($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'poll_id' => $pollId,
            'page_size' => 100, // In this case, the page_size is probably too much
        ));
        $url = $this->baseUrl . '/choices?' . $query;
        return json_decode($this->get($url));
    }

    /**
    * Returns the information data of a poll from $pollId
    * @param string $pollId
    * @return poll object
    */
    public function getPollData($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/polls/$pollId?" . $query;
        return json_decode($this->get($url));
    }

    /**
     * Returns the corresponding choice object from $choiceId
     * @param string $choiceId
     * @return choice object
     */
    public function getChoice($choiceId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/choices/$choiceId?" . $query;
        return json_decode($this->get($url));
    }

    /**
     * Creation of a choice associated with a poll from minimal information ($pollId and choice $label)
     * @param string $pollId
     * @param string $label
     * @return choice object
     */
    public function createChoice($pollId, $label) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . '/choices?' . $query;
        $data = [
            "poll_id" => $pollId,
            "label" => $label,
        ];
        return json_decode($this->post($url, $data));
    }

    /**
     * Deletes choice $choiceId
     * @param string $choiceId
     * @return type
     */
    public function deleteChoice($choiceId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/choices/$choiceId?" . $query;
        return $this->delete($url);
    }

    /**
     * Takes $pollId, an array of votes $votes, with minimal information (choice id and rank)
     * and sends the vote.
     * There is no user here. Each call to sendVote is processed individually and a user is created.
     * One new user per call.
     * @param type $pollId
     * @param array $votes
     * @return vote object (containing the id of the created user)
     */
    public function sendVote($pollId, $votes) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/votes/for-poll/$pollId?" . $query;
        return json_decode($this->post($url, $votes));
    }

    /**
     * Returns an array of votes object (triplet choice/ rank / user) from $pollId.
     * @param string $pollId
     * @return array of vote object
     */
    public function getVotes($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'poll_id' => $pollId,
            'page_size' => 100,
        ));
        $url = $this->baseUrl . '/votes?' . $query;
        return json_decode($this->get($url));
    }

    /**
     * Return a result object from $pollId and $resultType,
     * this result is array of json objects, assuming that the whole array
     * fits into a single page (i.e;, there are less than 10 choices).
     * @param string $pollId
     * @param string $resultType, can be majority or condorcet
     * @return result object
     */
    public function getResult($pollId, $resultType = 'majority') {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'page_size' => 100,
        ));
        $url = $this->baseUrl . "/polls/$pollId/results/$resultType?" . $query;
        $result = json_decode($this->get($url));
        return $result;
    }

    /**
     * Returns a result object from $pollId, $resultType and $chartType, this result²
     * holds an url to a graphic view of the result.
     * @param string $pollId
     * @param string $resultType, can be majority or condorcet
     * @param string $chartType, can be hbar, vbar or pie
     * @return result object
     */
    public function getResultUrl($pollId, $resultType = 'majority', $chartType = 'vbar') {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/polls/$pollId/results/$resultType/charts/$chartType?" . $query;
        return json_decode($this->get($url));
    }

    /**
     * Executes a GET request on the given url, and returns the response
     * @param string $url
     * @return string
     * @throws \LogicException
     */
    private function get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // because of certificate
        $output = curl_exec($ch);
        if (false === $output) {
            throw new \LogicException(curl_error($ch));
        }
        curl_close($ch);
        return $output;
    }

    /**
     * Executes a POST request on the given url. The data provided is encoded and added as json payload.
     * Returns the response
     * @param string $url
     * @param object $data
     * @return string
     * @throws \LogicException
     */
    private function post($url, $data) {
        $payload = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // because of certificate
        $output = curl_exec($ch);
        if (false === $output) {
            throw new \LogicException(curl_error($ch));
        }
        curl_close($ch);
        return $output;
    }

    /**
     * Executes a DELETE request on the given user.
     * Returns the response
     * @param string $url
     * @return type
     * @throws \LogicException
     */
    private function delete($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // because of certificate
        $output = curl_exec($ch);
        if (false === $output) {
            throw new \LogicException(curl_error($ch));
        }
        curl_close($ch);
        return $output;
    }
}
