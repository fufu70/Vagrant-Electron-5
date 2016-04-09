<?php

	/**
	 * The api is pretty simplistic:
	 * 
	 * 	action 	read or write
	 * 	pin 	A0 - D6
	 * 	value 	0 or 1
	 */

	define('DEVICE_ID', '240043000c51343334363138');
	define('ACCESS_TOKEN', 'ad49e6c929d8efe7ed9610169a790ed73587bd63');
	define('ELECTRON_DOMAIN', 'https://api.particle.io/v1/devices');
	define('READ', 'read');
	define('WRITE', 'write');
	define('ANALOG', 'analog');
	define('DIGITAL', 'digital');
	
	/**
	 * Gets the function name for the electron api.
	 * 
	 * @param  string $action The read or write action.
	 * @param  string $pin    The pin.
	 * @return string         The function name
	 */
	function get_function($action, $pin) {

		$action = strtolower($action);
		$pin = strtoupper($pin);
		$function_str = "";

		if (is_analog($pin)) {
			$function_str .= ANALOG;
		} else {
			$function_str .= DIGITAL;
		}

		if ($action === READ) {
			$function_str .= READ;
		} else {
			$function_str .= WRITE;
		}

		return $function_str;
	}

	/**
	 * Checks to see if the pin is analog. If the pin has 'A'
	 * it stands for analog.
	 * 
	 * @param  string  $pin  The pin that is to be accessed.
	 * @return boolean       If the pin is analog.
	 */
	function is_analog($pin) {
		return strpos($pin, 'A') !== false;
	}
	
	/**
	 * Sets up the url to append the POST parameters to.
	 * 
	 * @param  string $function The function to be requested from
	 *                          the electron.
	 * @return string           The url.
	 */
	function setup_url($function) {
		return ELECTRON_DOMAIN . '/' . DEVICE_ID . '/' . $function;
	}

	/**
	 * Sets up the post values to send to the Electron
	 * 
	 * @param  string $action The action to be called
	 * @param  string $pin    The pin on the electron
	 * @param  string $value  The value to send to the pin
	 * @return array          The post to send to the electron
	 */
	function setup_post($action, $pin, $value) {
		$post_arr = array(
			'arg' => $pin,
			'access_token' => ACCESS_TOKEN
		);

		if ($action === WRITE) {
			if (!is_analog($pin)) {
				if ($value == 1) {
					$post_arr['arg'] .= ' HIGH';
				} else {
					$post_arr['arg'] .= ' LOW';
				}
			} else {
				if ($value > 255) {
					$post_arr['arg'] .= ' 255';
				} else if ($value < 0) {
					$post_arr['arg'] .= ' 0';
				} else {
					$post_arr['arg'] .= ' ' . $value;	
				}
			}
		}

		return $post_arr;
	}

	/**
	 * Goes and sends a curl request to get the action, value, and pin updated
	 * correctly.
	 * 
	 * @param  string $url  The rest api call.
	 * @param  array  $post The post information to pass
	 * @return array        The result of the Particle Electron api call.
	 */
	function send_curl($url, $post) {
		$ch = curl_init();
		$post_string = "";

		// setup the POST and url encode each value
		foreach($post as $key=>$value) { 
			$post_string .= $key . '=' . urlencode($value) . '&'; 
		}
		
		rtrim($post_string, '&');

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($post));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $post_string);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		return $result;
	}

	/**
	 * Goes and initializes all variables and then tries to 
	 * the curl response.
	 */
	function init() {
		$value  = $_GET['value'] ?: '';
		$pin    = $_GET['pin'];
		$action = $_GET['action'];


		$url = setup_url(get_function($action, $pin));
		$post = setup_post($action, $pin, $value);

		$results = send_curl($url, $post);

		echo json_encode($results);
		
		die();
	}

	init();
	
?>