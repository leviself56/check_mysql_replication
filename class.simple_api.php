<?php
/**
 * @project:	ALRB Web Development
 * @access:		Tue Apr 11 15:19:00 CST 2023
 * @author:		Levi Self <levi@airlinkrb.com>
 **/

class SimpleAPI {

	protected $auth_header;
	protected $auth_user;
	protected $auth_pass;
	protected $auth_token;
	protected $authentification;
	
	public function call($request_type, $url, $payload) {
		// VERIFY WE HAVE AN ACCEPTABLE TYPE:
		if ($request_type == "GET" || $request_type == "POST" ||
			$request_type == "PATCH" || $request_type == "DELETE" ||
			$request_type == "PUT") {

			// BUILD THE COMPLETE API URL
			$complete_url = $url;

			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL, $complete_url);

			if ($request_type == "GET") {
				curl_setopt($cURL, CURLOPT_HTTPGET, true);
			} elseif ($request_type == "POST") {
				curl_setopt($cURL, CURLOPT_POST, true);
				curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode($payload));
			} elseif ($request_type == "PATCH") {
				curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'PATCH');
				curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode($payload));
			} elseif ($request_type == "PUT") {
				curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode($payload));
			}

			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

			if (isset($this->authentification)) {
				curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Accept: application/json',
					$this->auth_header));
			} else {
				curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Accept: application/json'));
			}
			$user_agent	=	"Php/7.0 (Debian) SimpleAPI";
			curl_setopt($cURL, CURLOPT_USERAGENT, $user_agent);
				
			$result = curl_exec($cURL);
			curl_close($cURL);

			$json = json_decode($result, true);
			return $json;
		} else {
			$error = "INCORRECT REQUEST TYPE: ".$request_type;
			return $error;
		}
	}

	public function Auth($array) {
		// DEFINE ARRAY AS
		// TYPE (BASIC | TOKEN), TOKEN, USER, PASS
		// array(
		// 	"type"	=>	"basic",
		// 	"user"	=>	"admin",
		// 	"pass"	=>	"SimplePass");
		//
		// 	OR
		// array(
		// 	"type"	=>	"token",
		// 	"token"	=>	"1V3h5Rn.gc$");
		switch ($array['type']) {
			case "basic":
				$type = "basic";
				$user = $array['user'];
				$pass = $array['pass'];
				break;
			case "token":
				$type = "token";
				$token= $array['token'];
				break;
		}

		$this->auth_type = $type;
		if (isset($user)) {
			$this->auth_user = $user;
			$this->auth_pass = $pass;
			$base64		 = base64_encode("$user:$pass");
			$this->auth_header="Authorization: Basic $base64";
		}
		if (isset($token)) {
			$this->auth_token= $token;
			$this->auth_header="Authorization: Bearer $token";
		}
		$this->authentification = true;
	}
}
?>
