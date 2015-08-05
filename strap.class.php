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

  			// Create the endpoint
        $this->$key = new StrapResource( $value, $this->token );

  		}
  	}

  	// Request some information
  	private function _Request($url) {
  		$cu = curl_init($url);

		curl_setopt($cu, CURLOPT_HTTPHEADER,
		    array(
		        'X-Auth-Token: '.$this->token,
		    )
		);

		# do not output, but store to variable
		curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($cu);

		return $response;
 	}

}

/*
Strap Resource for each API endpoint
*/

class StrapResource {

  public $hasNext = false;

  public $pageData = false;

  private $params = [];

  private $details = [];

  private $suppress = false;

  private $token = "";

  private $pageDefault = array('page'     => 1, 
                               'pages'    => 1,
                               'next'     => 2,
                               'per_page' => 30
                               );

  public function __construct($details, $token) {

    $this->details  = $details;
    $this->token    = $token;

    $this->pageData = $this->pageDefault;

    // Check for pagination
    $obj = $this->findMethod("GET");
    if ( $obj ) {
      if ( !$obj->optional || !in_array("page", $obj->optional) ) {
          $this->suppress = true;
      }
    }

  }

  // Call next page
  public function next() {

    // This method should not being doing this...
    if($this->suppress) { return false; }

    if( $this->hasNext ) {

      $page = [ 
                "page"      => $this->pageData["next"],
                "per_page"  => $this->pageData["per_page"]
              ];

      return $this->get($this->params, $page);

    } else {
      return false;
    }

  }

  // Get all the records
  public function all($params = null) {

    // This method should not being doing this...
    if($this->suppress) { return false; }

    // Holder
    $set = array();

    //Get thing started
    $set = $this->get($params);

    // Check and then loop as necesasry
    while( $this->hasNext ) {
      $set = array_merge( $set, $this->next() );
    }

    return $set;
  }

  // Do GET call
  public function get($params = array(), $page = array()) {

    // Set the Details information
    $obj = $this->findMethod("GET");

    if(!$obj) {
      return array("error"=>"Method not allowed");
    }

    $uri = $obj->uri;

    // Store this for next()
    $this->params = $params;

    // Check the type of paramse
    if ( $params && is_string($params) ) { // Check for only string
        $paramString = $params;
        $params = [];
    }

    $pattern = preg_match("/{([^{}]+)}/", $uri, $matches);

    // Do we need Pagination?
    if( !$this->suppress ) {
      // Setup the Paging info in the request
      $temp_page = array(
                  "page" => ( $params["page"] ) ? $params["page"] : $this->pageDefault["page"],
                  "per_page" => ( $params["per_page"] ) ? $params["per_page"] : $this->pageDefault["per_page"]
      );

      $this->pageData = array_merge($temp_page, $page );

      // Merge the page data into request
      // Give preference to params
      $params = array_merge( $this->pageData, $params );
    }
    /* 
    // Matches returns 
    array(2) { [0]=> "{guid}" [1]=> "guid" } 
    */

    // Handle all the URL strings
    $val = "";
    // If URL resource value is part of $params array
    if($params && is_array($params) ) { 
      $val = ($params[$matches[1]]) ? $params[$matches[1]] : "";  

    } else if ( $paramString ) {
      // If it is acutally only a string coming in
      $val = $paramString;
    }
    $uri = preg_replace( "/".$matches[0]."/", $val, $uri);

    if( is_array($params) && $params[$matches[1]] ) {

      unset($params[$matches[1]]);

    }

    if($params) {
      $uri = $uri.'?'.http_build_query($params);
    }

    return $this->call( $uri, $obj->method, $params );
  }

  // Do POST
  public function post($params = array()) {

   // Set the Details information
    $obj = $this->findMethod("POST");

    if(!$obj) {
      return array("error"=>"Method not allowed");
    }

    return $this->call( $obj->uri, $obj->method, $params );
  }

  // Do PUT
  public function put($params = array()) {

   // Set the Details information
    $obj = $this->findMethod("PUT");

    if(!$obj) {
      return array("error"=>"Method not allowed");
    }

    $uri = $obj->uri;

    // Store this for next()
    $this->params = $params;

    // Check the type of paramse
    if ( $params && is_string($params) ) { // Check for only string
        $paramString = $params;
        $params = [];
    }

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

    } else if ( $paramString ) {
      // If it is acutally only a string coming in
      $val = $paramString;
    }
    $uri = preg_replace( "/".$matches[0]."/", $val, $uri);


    return $this->call( $uri, $obj->method, $params );
  }

  // Do PUT
  public function delete($params = array()) {

   // Set the Details information
    $obj = $this->findMethod("DELETE");

    if(!$obj) {
      return array("error"=>"Method not allowed");
    }

    $uri = $obj->uri;

    // Store this for next()
    $this->params = $params;

    // Check the type of paramse
    if ( $params && is_string($params) ) { // Check for only string
        $paramString = $params;
        $params = [];
    }

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

    } else if ( $paramString ) {
      // If it is acutally only a string coming in
      $val = $paramString;
    }
    $uri = preg_replace( "/".$matches[0]."/", $val, $uri);


    return $this->call( $uri, $obj->method, $params );
  }

  private function call( $uri, $method, $params ) {

    // echo "Strap ".$method.": ".$uri." ".var_dump($params)."<hr>";

    $headers = array( 'X-Auth-Token: '.$this->token );

    $cu = curl_init($uri);

    // POST/PUT Body
    if($method == "POST" || $method == "PUT") {
      $params = json_encode($params);

      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Content-Length: '.strlen($params);
      
      curl_setopt($cu, CURLOPT_POST, 1);
      curl_setopt($cu, CURLOPT_POSTFIELDS, $params);
    }

    // Switch the Method
    curl_setopt($cu, CURLOPT_CUSTOMREQUEST, $method);

    // Set options
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($cu, CURLOPT_VERBOSE, 1);
    curl_setopt($cu, CURLOPT_HEADER, 1);
    curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($cu);

    // Pull apart the response
    $header_size = curl_getinfo($cu, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    $hdr = $this->parseHeaders($header)[0];

    curl_close($cu);

    echo "Response: ".$response;

    // Handle the Page Headers
    if ( $hdr["X-Pages"] == $hdr["X-Page"] ) {
        $this->hasNext = false;
        // Reset the Default Page information
        $this->pageData = $this->pageDefault;

    } else {

        // Set the main pageData
        $this->pageData = array_merge($this->pageData, [
                                                        "page"   => (int)$hdr["X-Page"],
                                                        "pages"  => (int)$hdr["X-Pages"],
                                                        "next"   => (int)$hdr["X-Next-Page"]
                                                    ]);
        $this->hasNext = true;
    }

    return json_decode($body);
  }

  private function parseHeaders ($headerContent) {
    
    $headers = array();

    // Split the string on every "double" new line.
    $arrRequests = explode("\r\n\r\n", $headerContent);

    // Loop of response headers. The "count() -1" is to 
    //avoid an empty row for the extra line break before the body of the response.
    for ($index = 0; $index < count($arrRequests) -1; $index++) {

        foreach (explode("\r\n", $arrRequests[$index]) as $i => $line)
        {
            if ($i === 0)
                $headers[$index]['http_code'] = $line;
            else
            {
                list ($key, $value) = explode(': ', $line);
                $headers[$index][$key] = $value;
            }
        }
    }

    return $headers;
  }

  private function findMethod($type) {

    // Examine the details and pull out the right type
    foreach ($this->details as $value) {
      if( $value->method == $type) {
        return $value;
      }
    }
    return false;
  }

}
