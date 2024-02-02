<?php

/**
 * @author Muhammad Adnan <adnannadeem1994@gmail.com>
 * @date   2021/11/12
 *
 * This class send Curl request
 */

namespace App\Classes;


class CurlRequestSend
{

    private $data = [];
    private $host = '';
    private $uri = '/';
    private $method = 'GET';
    private $headers = '';
    protected $responce = ['responce'=>'','error'=>''];

    public function __construct()
    {
        // setting up the current project url
        $this->host = url('/');
        // setting header on instance
        $this->setHeaders();
    }

    protected function setHeaders()
    {
        // setting up header
        $this->headers = [
            'Accept-Encoding' => 'Accept-Encoding: utf-8',
            'Accept' => 'Accept: application/json; charset=UTF-8',
            'Content-Type' => 'Content-Type: application/json; charset=UTF-8',
            'Accept-Language' => 'Accept-Language: '.config('app.locale'),
            'User-Agent' => 'User-Agent: JoeyCo',
            //'Host'=>'Host: ' .$this->host,
        ];
    }

    // set host
    public function setHost($host)
    {
        // setting up host
        $this->host = $host;
        return $this;
    }

    // set Uri
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    // set Method
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    // set Request data
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    // set header data
    public function setHeader($key,$value)
    {
        // adding or updating header  value
        $this->headers[$key] = $value;
        return $this;
    }

    // send raw responce
    public function rawResponce()
    {
        // checking error
        if($this->responce['error'] == '')
        {
            $this->responce['error'] = null;
        }
        return $this->responce;
    }


    // send raw responce
    public function arrayResponce()
    {
        // converting raw responce to array responce
        $this->responce['responce'] = explode("\n",$this->responce['responce']);
        // checking error
        if($this->responce['error'] == '')
        {
            $this->responce['error'] = null;
        }
        else
        {
            $this->responce['error'] = explode("\n",$this->responce['error']);
        }

        return $this->responce;
    }

    // set Request data
    public function send()
    {
        $url = $this->host.'/'.$this->uri;

        //initilzing
        $ch = curl_init();

        // checking type of method used
        if(!empty($this->data) && $this->method == 'POST')
        {
            $this->headers['Content-Length'] = 'Content-Length: '. strlen(json_encode($this->data));
        }
        elseif(!empty($this->data) && $this->method == 'GET')
        {
            // creating url query string
            $url_query_string =  http_build_query($this->data);
            // adding query string in to url
            $url.= '?'.$url_query_string;
        }

        // getting headers
        $headers = array_values($this->headers);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // sending curl request
        $curl_responce = curl_exec($ch);
        $error = curl_error($ch);
        $close = curl_close($ch);

        // setting row responce
        $this->responce = ['responce'=>$curl_responce,'error'=>$error];
        return $this;

    }

}