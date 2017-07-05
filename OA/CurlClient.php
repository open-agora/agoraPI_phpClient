<?php

/**
 * This file is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
 */

namespace OA;

class CurlClient {

    private $baseUrl;
    private $token;

    /**
     * Create the client. Parse the api.ini to initialize bas url an key token.
     */
    public function __construct() {
        $ini = parse_ini_file("api.ini");
        $this->baseUrl = $ini['base_url'];
        $this->token = $ini['token'];

    }

    /**
     * Query the service to get all polls associated with the client key.
     * Return an array of poll objects.
     * @return array of poll objects
     */
    public function listPolls() {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . '/polls?' . $query;
        return json_decode($this->get($url));
    }

    /**
     * Quick creation of a poll from the service with minimal information (title only)
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
     * Take a poll id an delete it from the service
     * @param type $pollId
     * @return type
     */
    public function deletePoll($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/polls/$pollId?" . $query;
        return $this->delete($url);
    }

    /**
     * Take a poll id and return the list of choices linked to it
     * @param type $pollId
     * @return array of choice objects
     */
    public function listChoices($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'poll_id' => $pollId,
        ));
        $url = $this->baseUrl . '/choices?' . $query;
        return json_decode($this->get($url));
    }

    /**
     * take a choice id and return the corresponding choice object
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
     * Quick creation of a choice linked to a poll with minimal information (pollId and label)
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
     * Take a choice id and delete it from the service
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
     * Take a poll id and an array of votes with minimal information (choice id and rank)
     * And send the vote to the service.
     * ther is no notion of user here. Each vote is individually processed with a user created each time.
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
     * Take a poll id and return an array of votes object (triplet choice/ rank / user)
     * @param string $pollId
     * @return array of vote object
     */
    public function getVotes($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'poll_id' => $pollId,
        ));
        $url = $this->baseUrl . '/votes?' . $query;
        return json_decode($this->get($url));
    }

    /**
     * Take a poll id and two parameters.
     * Return a result object, wich is the json result assuming that everything
     * fits into a single page.
     * @param string $pollId
     * @param string $resultType, can be majority or condorcet
     * @return result object
     */
    public function getResult($pollId, $resultType = 'majority') {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/polls/$pollId/results/$resultType?" . $query;
        $result = json_decode($this->get($url));
        return $result;
    }

    /**
     * Take a poll id and two parameters.
     * Return a result object, wich hold an url to a graphic view of the result
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
     * Execute a GET request on the given url, and return the response
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
     * Execute a POST request on the given url. The data provided is encoded and added as json payload.
     * Return the response
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
     * Execute a DELETE request on the given user.
     * Return the response
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
