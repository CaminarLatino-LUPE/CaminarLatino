<?php
	/**
	 * System Plugin for Joomla 
	 * @package     Joomla.Module
	 * @subpackage  System.stay_connected
	 * @author		Sal Bernal
	 *
	 * @copyright   Copyright (C) 2026 Caminar Latino.
	 */

	defined('_JEXEC') or die;

	use Joomla\CMS\Factory;
	use Joomla\CMS\Helper\ModuleHelper;	
	use Joomla\CMS\HTML\HTMLHelper;

	/**
	 * Module Helper
	 */
	
	class modStayConnectedHelper {

		/**
		 * Receives the posted input data, validates it and send email notification
		 */
		public static function SendMessageAjax() {

	        $app = Factory::getApplication();
	        $input = $app->input;

			// variables	
			$return = array();	
			$return['message'] = '';
			$return['status'] = '';	

			// check if this form was submitted
			if($_SERVER['REQUEST_METHOD'] == "POST"
				&& filter_has_var(INPUT_POST, 'first_name')
				&& filter_has_var(INPUT_POST, 'email')
				&& filter_has_var(INPUT_POST, 'g-recaptcha-response')
				&& filter_has_var(INPUT_POST, 'mod_id')
				&& empty($_POST['a_password'])
			) {

				$inputEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

				// Prevent russian emails
				if(substr($inputEmail, -3) !== '.ru') {

					$captcha = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING);

					// Get Params
				    $module = ModuleHelper::getModuleById(filter_input(INPUT_POST, 'mod_id', FILTER_SANITIZE_STRING));
				    $params = new \JRegistry($module->params);

					$siteKey = $params->get('captcha_site', '', 'string');
					$secretKey = $params->get('captcha_secret', '', 'string');


					if(!empty($captcha) && !empty($siteKey) && !empty($secretKey)) {


			            // CURL Check
			            $ch = curl_init();
			            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
			            $captchaPost = array(
			                'secret' => $secretKey,
			                'response' => $captcha,
			                'remote_id' => $_SERVER['REMOTE_ADDR']
			            );
			            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			            curl_setopt($ch, CURLOPT_POST, TRUE);
			            curl_setopt($ch, CURLOPT_POSTFIELDS, $captchaPost);
			            $response = curl_exec($ch);

			            if($response !== false) {
				            $captchaResult = json_decode($response);
				            if ($captchaResult->success == 1) {

								$inputFirstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);	
				
								$redirectURI = 'https://caminarlatino.org';
								$clientId = '9f8ac45e-d1df-4e4d-83df-93dd4c09e71e';
								$clientSecret = 'Y7h8w7CGAcl9ND8-H74XRA';
								$scope = 'contact_data+offline_access';
								$state = 'arbitrary%20state%20string%20value';
								$code = 'Yy0fKXBTEYnn0SebuXJjB_6FtLvTqytGbMUD-hobcMQ';
								$token = 'eyJraWQiOiIwbzhyUkI2dGh4RWJkMkM3V2gxeGVsUUNDaTdoOWtOaGdpR0dka1BCTEw0IiwiYWxnIjoiUlMyNTYifQ.eyJ2ZXIiOjEsImp0aSI6IkFULmRZcjRTWkRZZUFxUFZ2Q2lYeTlqRl9MMkZMbGltSHN3LXJ2YzA4Q1pMX1Eub2FyMmdvd3VreTY0TU80MzkwaDciLCJpc3MiOiJodHRwczovL2lkZW50aXR5LmNvbnN0YW50Y29udGFjdC5jb20vb2F1dGgyL2F1czFsbTNyeTltRjd4MkphMGg4IiwiYXVkIjoiaHR0cHM6Ly9hcGkuY2MuZW1haWwvdjMiLCJpYXQiOjE3ODQ5NjgwOTUsImV4cCI6MTc4NTA1NDQ5NSwiY2lkIjoiOWY4YWM0NWUtZDFkZi00ZTRkLTgzZGYtOTNkZDRjMDllNzFlIiwidWlkIjoiMDB1MXN0YXEyeG1ISGJWcnQwaDgiLCJzY3AiOlsib2ZmbGluZV9hY2Nlc3MiLCJjb250YWN0X2RhdGEiXSwiYXV0aF90aW1lIjoxNzg0OTYxNTQ0LCJzdWIiOiJ0ZWNoQGNhbWluYXJsYXRpbm8ub3JnIiwicGxhdGZvcm1fdXNlcl9pZCI6IjdhYjIwNzNjLTVjZTItNDBhNC05NDMyLTRmYzI0ZmI4YWZjNiJ9.Nshlh7aoAegf6_GKhLBVu_ZHpSIQyONHZjYSwdpzRcgMBOirC3_8HeAeX9Wl6ET5qC2eegbaMkdk9RqRift61r8PN0cOI9jepAIJ9SyTfMvU5yACFTEwPJUHa8A2mdsgmiTorRI_CcauqljsaiXq5WEgVrl_zVBvT9KtWOGXvFPX9x6aS9iFjHsJmgq_XKNkZRgAk8rD6qFN1lR7NbGfLF4MnUJPrI6Z5tiNzNm3GyaNKDWA-qd5HF_VtL78dz3rmX7wtCERHUb1C1oN-c_8mIp_lulNMWuuk2mW74g6Kj-quo98pSwNv3y1NsW-Kgg6gMRw4vWm3a7ninh6HtJUyA';
								$refreshToken = 'OaIY3haY6NJjOHFeskbJ_mAaP665UXL8TbkelVzlybc';
								$listId = '2e5e474e-89de-11f1-b35a-02420a320002';


								$temp = self::getAuthorizationURL($clientId, $redirectURI, $scope, $state);
								$temp2 = self::getAccessToken($redirectURI, $clientId, $clientSecret, $code);
								$temp3 = self::getContactsLists($token);
								$temp5 = self::refreshToken($refreshToken);													
								$newContact = self::addContact($inputFirstName, $inputEmail, $listId);


								if(!$newContact) {
									self::closeApp('error', 'Error adding contact - Please contact system administrator');
								}
								else {
									self::closeApp('success', '');
								}	


				            }
				            // Wrong reCaptcha user response
				            else {
			            		self::closeApp('error', 'reCaptcha response was wrong - Please try again');
				            }
			            	
			            }
			            // No response from captcha validation
			            else {		              
			                self::closeApp('error', 'Problem validating reCaptcha - Please contact the system administrator');
			            }
					}
					// Missing reCaptcha parameters
					else {		
						self::closeApp('error', 'No Recipient email address or reCaptcha keys entered - Please contact the system administrator');

					}	

				}
				else {
					// Bad request. Something fishy		
					self::closeApp('error', 'User Email Problem - Please contact the system administrator');	
				}		    			
			}
			else {
				// Bad request. Something fishy		
				self::closeApp('error', 'No data provided - Please contact the system administrator');	
			}

		}


		/**
		 * Dies, send status and message
		 * @param  [string] $status  [The status of the user input]
		 * @param  [string] $message [The message to display]
		 * @return [void]          [description]
		 */
		public static function closeApp($status, $message) {
            echo json_encode(array('status'=>$status, 'message'=>$message));
            die();
		}

		public static function getAccessToken($redirectURI, $clientId, $clientSecret, $code) {

		    // Use cURL to get access token and refresh token
		    $ch = curl_init();

		    // Define base URL
		    $base = 'https://authz.constantcontact.com/oauth2/default/v1/token';

		    // Create full request URL
		    $url = $base . '?code=' . $code . '&redirect_uri=' . $redirectURI . '&grant_type=authorization_code';
		    curl_setopt($ch, CURLOPT_URL, $url);

		    // Set authorization header
		    // Make string of "API_KEY:SECRET"
		    $auth = $clientId . ':' . $clientSecret;
		    // Base64 encode it
		    $credentials = base64_encode($auth);
		    // Create and set the Authorization header to use the encoded credentials, and set the Content-Type header
		    $authorization = 'Authorization: Basic ' . $credentials;
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization, 'Content-Type: application/x-www-form-urlencoded'));

		    // Set method and to expect response
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		    // Make the call
		    $result = curl_exec($ch);
		    curl_close($ch);
		    return $result;		    

		}
	

		public static function getAuthorizationURL($clientId, $redirectURI, $scope, $state) {
		    // Create authorization URL
		    $baseURL = "https://authz.constantcontact.com/oauth2/default/v1/authorize";
		    $authURL = $baseURL . "?client_id=" . $clientId . "&scope=" . $scope . "&response_type=code&state=" . $state . "&redirect_uri=" . $redirectURI;

		    return $authURL;

		}


		/**
		 * Function to get all contacts lists
		 * @param  [type] $token [description]
		 * @return [type]        [description]
		 */
		public static function getContactsLists ($token) {
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://api.cc.email/v3/contact_lists?include_count=true&status=active&include_membership_count=all',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
			    'Accept: */*',
			    'Content-Type: application/json',
			    'Authorization: Bearer ' . $token
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);

		    return $response;

		}


		/**
		 * Function to get specificed contacts list
		 * @param  [type] $token [description]
		 * @return [type]        [description]
		 */
		public static function getContactsList ($token) {
			$curl = curl_init();


			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://api.cc.email/v3/contact_lists/2ccd6334-e2c9-11ed-b6a1-fa163efd402b?include_membership_count=active',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
			    'Accept: */*',
			    'Content-Type: application/json',
			    'Authorization: Bearer ' . $token
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);

		    return $response;
		}		


		/**
		 * Function to add new contact to specified list
		 * @param [type] $firstName   [description]
		 * @param [type] $lastName    [description]
		 * @param [type] $email       [description]
		 * @param [type] $companyName [description]
		 */
		public static function addContact ($firstName, $email, $listId) {


			// Get Access Token
			$db = Factory::getContainer()->get('DatabaseDriver');

	        $query = $db
	            ->getQuery(true)
	            ->select('access_token')
	            ->from($db->quoteName('#__refresh_tokens'))
	            ->where($db->quoteName('id') . " = " . $db->quote('1'));


	        $db->setQuery($query);
	        $token = $db->loadResult();


	        // Add Contact
	        if($token) {

				$curl = curl_init();

				$post = [
					'email_address' => [
						'address' => $email,
						'permission_to_send' => 'implicit'
					],
					'create_source' => 'Account',
					'first_name' => $firstName,					
					'list_memberships' => [$listId]
				];

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://api.cc.email/v3/contacts',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_POSTFIELDS => json_encode($post),
				  CURLOPT_HTTPHEADER => array(
				    'Accept: application/json',
				    'Content-Type: application/json',
				    'Cache-Control: no-cache',
				    'Authorization: Bearer ' . $token
				  ),
				));

				$response = json_decode(curl_exec($curl));

				curl_close($curl);

				if(property_exists($response, 'contact_id')) {
					return true;
				}
				else {
					return false;
				}

	        }
	        else {
	        	return false;
	        }



		}	


		/**
		 * Function to refresh access token
		 * @param  [type] $refreshToken [description]
		 * @return [type]               [description]
		 */
		public static function refreshToken ($refreshToken) {

			$clientId = '9f8ac45e-d1df-4e4d-83df-93dd4c09e71e';
			$clientSecret = 'Y7h8w7CGAcl9ND8-H74XRA';


		    // Use cURL to get a new access token and refresh token
		    $ch = curl_init();

		    // Define base URL
		    $base = 'https://authz.constantcontact.com/oauth2/default/v1/token';

		    // Create full request URL
		    $url = $base . '?refresh_token=' . $refreshToken . '&grant_type=refresh_token';
		    curl_setopt($ch, CURLOPT_URL, $url);

		    // Set authorization header
		    // Make string of "API_KEY:SECRET"
		    $auth = $clientId . ':' . $clientSecret;
		    // Base64 encode it
		    $credentials = base64_encode($auth);
		    // Create and set the Authorization header to use the encoded credentials, and set the Content-Type header
		    $authorization = 'Authorization: Basic ' . $credentials;
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization, 'Content-Type: application/x-www-form-urlencoded'));

		    // Set method and to expect response
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		    // Make the call
		    $result = curl_exec($ch);
		    curl_close($ch);
		    return $result;

		}		


	}