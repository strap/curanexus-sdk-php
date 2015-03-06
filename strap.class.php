<?php

/**
 * Strap SDK PHP class
 * 
 * @author Brian Powell
 * @since 03.06.2014
 * @copyright Strap
 * @version 1.0
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */

class StrapSDK {

    // Cache Holder
    private $c = false;

	// API Endpoint
	private $apiURL = "https://api2.straphq.com";

	// API Token
	private $token = false;

	// Discovery Cache Information
	private $discovery = false;

	// Resources holder
	public $resources = [];

	// Construct for StrapSDK
	public function __construct($token = null, $cachePath = null) {
    	
		// Include the Cache
		require_once __DIR__.'/includes/cache.class.php';

		// Establish Cache
		$this->c = new Cache(array(
					  'name'      => $this->token,
					  'path'      => ($cachePath) ? $cachePath : __DIR__.'/StrapSDK_cache/',
					  'extension' => '.cache'
					));

    	// Make sure we have a token
    	if( $token ) {
    		$this->token = $token;
    	} else {
    		die("Please set the read token.<br>");
    	}

    	if(!$this->c) {
    		die("For some reason...  We do not have the Cache setup...<br>");
    	}

    	// Check the cache from the data and parse it
    	$this->discovery = json_decode( $this->c->retrieve("discovery") );

    	if(!$this->discovery) {
    		$this->_loadDiscover();
    	} else {
    		$this->_buildDiscovery();
    	}

  	}

  	// Returns an the discovery object 
  	public function endpoints($json = false) {
  		return ($json) ? json_encode($this->discovery) : $this->discovery;
  	}

  	// Load the Dsicovery endpoint
  	private function _loadDiscover() {

  		// Pull the Discovery
  		$data = $this->_Request($this->apiURL.'/discover');

  		// Parse the JSON response
  		$data = json_decode($data);

  		// Check for the discovery being good
  		if($data->success === false) {
  			die("The provide Token is not valid.");
  		} else {
  			// Set in the Cache
  			$this->c->store("discovery", json_encode($data), 3000);
  		
  			$this->discovery = $data;

  			// Build the resources
  			$this->_buildDiscovery();
  		}
  	}

  	// Build out the discovery stuff
  	private function _buildDiscovery() {

  		foreach ($this->discovery as $key => $value) {
  			
  			$this->$key = new StrapResource($this->token, $value);

  		}
  	}

  	// Request some information
  	private function _Request($url) {
  		$curl_h = curl_init($url);

		curl_setopt($curl_h, CURLOPT_HTTPHEADER,
		    array(
		        'X-Auth-Token: '.$this->token,
		    )
		);

		# do not output, but store to variable
		curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl_h);

		return $response;
  	}

}

// The class for the resources
class StrapResource {

	private $token = "";
	private $uri = "";
	private $method = "";
	private $optional = "";

	public function __construct($token, $obj) {

		$this->token = $token;
		$this->uri = $obj->uri;
		$this->method = $obj->method;
		$this->optional = $obj->optional;
	}

	public function call($params = null) {

		$pattern = 
		preg_match("/{([^{}]+)}/", $this->uri, $matches);

		/* 
		// Matches returns 
		array(2) { [0]=> "{guid}" [1]=> "guid" } 
		*/

		// Handle all the URL strings
		$val = ($params[$matches[1]]) ? $params[$matches[1]] : "";	
		$this->uri = preg_replace( "/".$matches[0]."/", $val, $this->uri);

		if( $params[$matches[1]] ) {

			unset($params[$matches[1]]);

		}

		if($this->method == "GET" && $params) {
			$this->uri = $this->uri.'?'.http_build_query($params);
		}

		$curl_h = curl_init($this->uri);

		curl_setopt($curl_h, CURLOPT_HTTPHEADER,
		    array(
		        'X-Auth-Token: '.$this->token,
		    )
		);

		# do not output, but store to variable
		curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

		$response = json_decode( curl_exec($curl_h), true);

		$response = ($response) ? $response : [];

		return $response;
	}

}