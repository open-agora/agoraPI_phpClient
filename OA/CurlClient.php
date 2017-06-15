<?php

namespace OA;

class CurlClient {

    private $baseUrl;
    private $token;

    public function __construct() {
        $ini = parse_ini_file("api.ini");
        $this->baseUrl = $ini['base_url'];
        $this->token = $ini['token'];
    }

    public function listPolls() {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . '/polls?' . $query;
        return $this->get($url);
    }

    public function createPoll($title) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . '/polls?' . $query;
        $data = ["title" => $title];

        return $this->post($url, $data);
    }

    public function deletePoll($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/polls/$pollId?" . $query;
        return $this->delete($url);
    }

    public function listChoices($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'poll_id' => $pollId,
        ));
        $url = $this->baseUrl . '/choices?' . $query;
        return $this->get($url);
    }

    public function getChoice($choiceId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/choices/$choiceId?" . $query;
        return $this->get($url);
    }

    public function createChoice($pollId, $label) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . '/choices?' . $query;
        $data = [
            "poll_id" => $pollId,
            "label" => $label,
        ];
        return $this->post($url, $data);
    }

    public function deleteChoice($choiceId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/choices/$choiceId?" . $query;
        return $this->delete($url);
    }

    public function sendVote($pollId, $votes) {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/votes/for-poll/$pollId?" . $query;
        return $this->post($url, $votes);
    }

    public function getVotes($pollId) {
        $query = http_build_query(array(
            'api_token' => $this->token,
            'poll_id' => $pollId,
        ));
        $url = $this->baseUrl . '/votes?' . $query;
        return $this->get($url);
    }

    public function getResult($pollId, $resultType = 'majority', $chartType = 'vbar') {
        $query = http_build_query(array(
            'api_token' => $this->token,
        ));
        $url = $this->baseUrl . "/polls/$pollId/results/$resultType/charts/$chartType?" . $query;
        return $this->get($url);
    }

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
