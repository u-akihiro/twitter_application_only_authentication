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

		$tweets = array();
		for ( $c = 0; $c < 100; $c ++ ) {
			$response = file_get_contents($url, false, stream_context_create($options));
			$decoded = json_decode($response);

			$tweets[] = $decoded;

			if ( property_exists($decoded->search_metadata, 'next_results') ) {
				$url = $this->api_url . $decoded->search_metadata->next_results;
			} else {
				return $tweets;
			}
		}
    }
}
