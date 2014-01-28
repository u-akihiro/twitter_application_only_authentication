<?php

class TwitSearch
{
    private $api_url;
    private $url;
    private $access_token;

    public function __construct($api_url, $access_token)
    {
        $this->api_url = $api_url;
        $this->access_token = $access_token;
    }
   
    public function search($params = array())
    {
        $query = http_build_query($params);
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Bearer ' . $this->access_token
                )
            )
        );

        $url = $this->api_url .'?'. $query;
        
        $response = file_get_contents($url, false, stream_context_create($options));

        if ( $response !== false ) {
            return $response;
        } else {
            throw new Exception('HTTP Error');
        }
    }
}
