<?php

namespace JenkinsCli;

class Api {

    CONST suffix = '/api/json';

    public function build($jobName, array $parameters=[]) {
        $data = ['parameter' => []];
        foreach ($parameters as $name => $value) {
            $data['parameter'][] = ['name' => $name, "value" => $value];
        }
        //$data = ['parameter' => [
        //    ['name' => 'BRANCH_TO_BUILD', 'value' => $branch],
        //    ['name' => 'skipBuildGrunt', 'value' => '1']
        //]];
        return $this->post("/job/$jobName/build", ['json' => json_encode($data)]);
    }

    public function getAllJobs() {
        $result = $this->get('');
        $jobs = [];
        foreach ($result['jobs'] as $job) {
            $jobs[] = $job['name'];
        }
        return $jobs;
    }

    protected function post($url, $postFields=null) {
        $userId = $this->getUser();
        $apiToken = $this->getApiToken();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getBaseUrl() . $url . self::suffix);
        curl_setopt($ch, CURLOPT_USERPWD, "$userId:$apiToken");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
        // curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if (!is_null($postFields)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    protected function get($url)
    {
        $userId = $this->getUser();
        $apiToken = $this->getApiToken();
        $context = stream_context_create(array('http' => array('header' => "Authorization: Basic " . base64_encode("$userId:$apiToken"))));
        $json = file_get_contents($this->getBaseUrl() . $url . self::suffix, false, $context);
        $result = json_decode($json, true);
        return $result;
    }


    protected function getUser() {
        $userId = getenv('JENKINS_USERID');
        if (empty($userId)) {
            throw new \Exception('JENKINS_USERID not found');
        }
        return $userId;
    }

    protected function getApiToken() {
        $apiToken = getenv('JENKINS_APITOKEN');
        if (empty($apiToken)) {
            throw new \Exception('JENKINS_APITOKEN not found');
        }
        return $apiToken;
    }

    protected function getBaseUrl() {
        $url = getenv('JENKINS_BASURL');
        if (empty($url)) {
            throw new \Exception('JENKINS_BASURL not found');
        }
        return rtrim($url, '/');
    }

}