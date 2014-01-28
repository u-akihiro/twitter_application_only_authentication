<?php

class AppOnlyAuth
{
    private $url;
    private $consumer_key;
    private $consumer_secret;

    public function __construct($url, $consumer_key, $consumer_secret)
    {
        $this->url = $url;
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
    }

    private function credential_gen()
    {
        $token_credential = urlencode($this->consumer_key) .':'. urlencode($this->consumer_secret);
        return base64_encode($token_credential);
    }

    public function request()
    {
        $credential = $this->credential_gen();

        $data = array('grant_type' => 'client_credentials');

        $options = array('http' => array(
            'method' => 'POST',
            'content' => http_build_query($data),
            'header' => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $credential
            )
        )); 

        $response = file_get_contents($this->url, false, stream_context_create($options));

        if ( $response !== false ) {
            $decode = json_decode($response);
            return $decode;
        } else {
            throw new Exception('HTTP Error');
        }
    }
}
