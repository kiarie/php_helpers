<?php
/**
 * eLipa Curl (https://ipayafrica.com)
 *
 * @link      https://ipayafrica.com
 * @copyright Copyright (c) 2014-2017 Starford Gwala
 */
 namespace elipa\curl;
class Curl
{
    const DEFAULT_TIMEOUT = 30;
    public $curl;
	

/*
|--------------------------------------------------------------------------
| CURL
|--------------------------------------------------------------------------
|
| This creates a new instance of the class
|
*/

	function __construct()
	{
        if (!extension_loaded('curl')) {
            throw new \ErrorException('Curl library is not loaded');
        }
        
        $this->curl = curl_init();
	}

/*
|--------------------------------------------------------------------------
| get
|--------------------------------------------------------------------------
|
| accepts @url and @arguments
| @url is a url
| @arguments is an array
| returns curl response
|
*/
	public function get($url, $arguments = array())
	{
			if(!empty($arguments)):
					$url = $url.'?'.http_build_query($arguments);
			endif;
			curl_setopt( $this->curl , CURLOPT_URL, $url);
			curl_setopt($this->curl , CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($this->curl, CURLOPT_TIMEOUT, 3);
			// grab URL and pass it to the browser
			$response = curl_exec($this->curl);
			// close cURL resource, and free up system resources
			curl_close($this->curl);
			return $response;
			

	}

/*
|--------------------------------------------------------------------------
| post
|--------------------------------------------------------------------------
|
| accepts @url and @arguments
| @url is a url
| @arguments is an array
| returns curl response
|
*/
	public function post($url, $arguments = array())
	{
			if(empty($arguments)):
				throw new \ErrorException('Missing POST ARGUMENTS');
			endif;
			curl_setopt( $this->curl , CURLOPT_URL, $url);
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($arguments));
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 3);
  			//curl_setopt($this->curl, CURLOPT_TIMEOUT, 3);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array( "content-type: application/x-www-form-urlencoded"));
			$result = curl_exec($this->curl);
			// close cURL resource, and free up system resources
			curl_close($this->curl);
            return $result;

	}
	
}
?>