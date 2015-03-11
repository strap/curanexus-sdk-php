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

class Strap {

    // Cache Holder
    private $c = false;

	// API Endpoint
	private $apiURL = "https://api2.straphq.com";

	private $map = ["activity" => "getActivity",
					"report" => "getReport",
					"today" => "getToday",
					"trigger" => "getTrigger",
					"users" => "getUsers"
					];

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

  	// All Methods 
  	public function endpoints($json = false) {

  		return ($json) ? json_encode($this->resources) : $this->resources;
  	}

  	public function getActivity($params=null) {

  		return $this->call( $this->resources["getActivity"], $params);
  	}

  	public function getReport($params=null) {

  		return $this->call( $this->resources["getReport"], $params);
  	}

  	public function getToday($params=null) {
  		
  		return $this->call( $this->resources["getToday"], $params);
  	}

  	public function getTrigger($params=null) {

  		return $this->call( $this->resources["getTrigger"], $params);
  	}

  	public function getUsers($params=null) {

  		return $this->call( $this->resources["getUsers"], $params);
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
  			
  			$key = $this->map[$key];
  			$this->resources[$key] = $value;

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

 	private function call($obj, $params = null) {

 		$uri = $obj->uri;

		$pattern = preg_match("/{([^{}]+)}/", $uri, $matches);

		/* 
		// Matches returns 
		array(2) { [0]=> "{guid}" [1]=> "guid" } 
		*/

		// Handle all the URL strings
		$val = "";
		// If URL resource value is part of $params array
		if($params && is_array($params) ) { 
			$val = ($params[$matches[1]]) ? $params[$matches[1]] : "";	
		} else if ($params && is_string($params) ) {
			// If it is acutall only a string coming in
			$val = $params;
		}
		$uri = preg_replace( "/".$matches[0]."/", $val, $uri);

		if( is_array($params) && $params[$matches[1]] ) {

			unset($params[$matches[1]]);

		} else {
			$params = [];
		}

		if($obj->method == "GET" && $params) {
			$uri = $uri.'?'.http_build_query($params);
		}

		$curl_h = curl_init($uri);

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
