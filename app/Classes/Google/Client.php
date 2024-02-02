<?php

namespace App\Classes\Google;

use \Laravel\Config;

class Client {
    
    private $requestData;
    private $service;
    
    private $proto = 'https://';
    private $host = 'maps.googleapis.com';
    private $path = '/maps/api/';
    
    public function __construct($service) {
        $this->service = $service;
    }

    private function buildURL($type, $params) {
        $params[] = 'sensor=false';
        $queryString = [];

        foreach ($params as $i => $param) {
            list($key, $value) = explode('=', $param);
            $params[$i] = urlencode($key) . '=' . urlencode($value);
        }

        if (Config::get('constants.google_api_client') && Config::get('constants.google_api_crypto_key')) {

            $params[] = 'client=' . Config::get('constants.google_api_client');

            $stringToSign = $this->path . $type . '/json?' . implode('&', $queryString);

            $key = Config::get('constants.google_api_crypto_key');
            $key = str_replace(['-', '_'], ['+', '/'], $key);
            $key = base64_decode($key);

            $signature = \hash_hmac('sha1', $stringToSign, $key, true);
            $signature = base64_encode($signature);
            $signature = str_replace(['+', '/'], ['-', '_'], $signature);

            $params[] = 'signature=' . $signature;
        }

        return $this->proto . $this->host . $this->path . $type . '/json?' . implode('&', $params);
    }

    
    public function setRequestData($requestData) {
        $this->requestData = $requestData;
    }
    
    public function send() {
        $url = $this->buildURL($this->service, $this->requestData);
        $ch = curl_init($url."&key=AIzaSyDTK4viphUKcrJBSuoidDqRhVA4AWnHOo0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $return = curl_exec($ch);
        $data = json_decode($return, true);
        curl_close($ch);
        
        if (!empty($data['status'])) {
            if ($data['status'] === 'MAX_WAYPOINTS_EXCEEDED') {
                throw new \JoeyCo\Exception\GoogleException("You've exceeded the maximum number of waypoints you can add to this order.", 406);
            } else if ($data['status'] === 'OVER_QUERY_LIMIT') {
                $env = \Laravel\Request::env();
                $msg = 'Google API Over Limit (' . $env . ')';

                \JoeyCo\Tools\Mail::send('JoeyCo', 'samer@joeyco.com', $msg, $msg);

                throw new \JoeyCo\Exception\GoogleException('There was an error fetching the location. Please contact JoeyCo support if this problem persists: +1 (855) 556-3926.', 400);
            } else if ($data['status'] === 'ZERO_RESULTS' || $data['status'] === 'INVALID_REQUEST') {
                throw new \JoeyCo\Exception\GoogleException("Please check the spelling of the address and postal code.", 400);
            } else if ($data['status'] === 'REQUEST_DENIED') {
                throw new \JoeyCo\Exception\GoogleException('An unknown has occured during routing. Please contact JoeyCo support if this problem persists: +1 (855) 556-39266.', 400);
            } else if ($data['status'] === 'UNKNOWN_ERROR') {
                throw new \JoeyCo\Exception\GoogleException('An unknown has occured during routing.', 400);
            }
        }

        return $data;
    }
    
}
