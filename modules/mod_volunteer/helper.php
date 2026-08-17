<?php
	/**
	 * System Plugin for Joomla 
	 * @package     Joomla.Module
	 * @subpackage  System.volunteer
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
	
	class modVolunteerHelper {

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
					$toEmail = $params->get('to_email', '', 'string');


					if(!empty($captcha) && !empty($toEmail) && !empty($siteKey) && !empty($secretKey)) {


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
								$inputLastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
								$inputPhone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);	
								$inputReference = filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_STRING);
								$inputInterest = filter_input(INPUT_POST, 'interest', FILTER_SANITIZE_STRING);
								$inputType = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
				
							    
								// set the recipient
								$emails = explode(',',$toEmail);
								foreach($emails as $email){

									// create a mailer object
									$mailer = Factory::getMailer();

									$mailer->isHTML(true);
									$mailer->Encoding = 'base64';

									// set the sender to the site default
									$config = Factory::getConfig();
									$sender = array(
									    $config->get('mailfrom'),
									    $config->get('fromname'));
									$mailer->setSender($sender);

									$mailer->addRecipient($email);

									// set the message subject
									$mailer->setSubject('Volunteer Form');


									$message = '<div><p>Hello Administrator, there has been a new submission to the "Volunteer" form on the Caminar Latino website. The information is listed below.</p></div>';
									$message .= '<table style="width: 100%;"><tbody>';

									$message .= '<tr style="background: #e9eadb;">';
									$message .= '<td style="width: 140px;padding: 4px 10px;"><b>NAME</b></td><td style="text-align: center; padding: 4px 10px;">' . $inputFirstName . ($inputLastName !== '' ? ' ' . $inputLastName : '') . '</td>';
									$message .= '</tr>';


									$message .= '<tr>';
									$message .= '<td style="padding: 4px 10px;"><b>CONTACT INFORMATION</b></td><td style="text-align: center; padding: 4px 10px;"><a href="mailto:' . $inputEmail .  '">' . $inputEmail  . '</a>' . ($inputPhone !== '' ? '<br />' . $inputPhone : '') . '</td>';
									$message .= '</tr>';


									$message .= '<tr style="background: #e9eadb;">';
									$message .= '<td style="width: 140px;padding: 4px 10px;"><b>REFERENCE</b></td><td style="text-align: center; padding: 4px 10px;">' . $inputReference .  '</td>';
									$message .= '</tr>';
																							

									$message .= '<tr>';
									$message .= '<td style="padding: 4px 10px;"><b>AREA OF INTEREST</b></td><td style="text-align: center; padding: 4px 10px;">' . $inputInterest    . '</td>';
									$message .= '</tr>';

									$message .= '<tr style="background: #e9eadb;">';
									$message .= '<td style="padding: 4px 10px;"><b>TYPE</b></td><td style="text-align: center; padding: 4px 10px;">' . $inputType    . '</td>';
									$message .= '</tr>';									


									$message .= '</tbody></table>';

									$mailer->setBody(self::buildEmail('Volunteer Form', $message));

									// Send message
									if(!$mailer->Send()) {
										self::closeApp('error', 'Your message could not be sent at this time - Please contact the system administrator');
									}														
								}
					   			self::closeApp('success', '');
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



		/**
		 * Builds a nicely-formatted email
		 * @param  [string] $heading [The heading of the email]
		 * @param  [html] $content [The content of the email]
		 * @return [html]          [The html-formatted email]
		 */
		public static function buildEmail($heading, $content){		
			// get the root URL
			$root = \JURI::root();
			$pos = strpos($root, 'media/');
			if($pos){
				$root = substr($root, 0, $pos);	
			}

			// build the message
			$message = '<html><body>';
			$message .= '<div style="width:100%!important;padding:0;margin:0;background-color:#393a3d">';
			$message .= '<table style="font-family:Helvetica;font-size:12px" border="0" cellpadding="0" cellspacing="0" width="100%">';
			$message .= '<tbody>';
			$message .= '<tr>';
			$message .= '<td style="padding:40px 0px" align="center">';
			$message .= '<table style="font-family:Helvetica;font-size:12px" align="center" border="0" cellpadding="0" cellspacing="0" width="640">';
			$message .= '<tbody>';
			$message .= '<tr>';
			$message .= '<td valign="top">';
			$message .= '<table style="background-color:#FFF;font-family:Helvetica;font-size:12px" border="0" cellpadding="0" cellspacing="0" width="650">';
			$message .= '<tbody>';
			$message .= '<tr>';
			$message .= '<td style="padding-bottom:10px" valign="top">';
			$message .= '<table border="0" cellpadding="0" cellspacing="0" width="650">';
			$message .= '<tbody>';
			$message .= '<tr style="background-color:#093493; ">';
			$message .= '<td align="center"><a href="' . $root . '" target="_blank" style="display: inline-block; margin: 30px 0;" ><img  src="' . $root . 'images/logos/logo_cl_small.png" alt="Caminar Latino Logo" /></a></td>';
			$message .= '</tr>';
			$message .= '<tr style="background-color:#2C6CE2;color:#FFF;font-size:22px;font-weight:bold;">';
			$message .= '<td align="center" style="padding:20px;">' . $heading . '</td>';
			$message .= '</tr>';
			$message .= '<tr>';
			$message .= '<td style="padding:30px;">' . $content . '</td>';
			$message .= '</tr>';
			$message .= '</tbody>';
			$message .= '</table>';
			$message .= '</td>';
			$message .= '</tr>';
			$message .= '</tbody>';
			$message .= '</table>';
			$message .= '</td>';
			$message .= '</tr>';
			$message .= '</tbody>';
			$message .= '</table>';
			$message .= '</td>';
			$message .= '</tr>';
			$message .= '</tbody>';
			$message .= '</table>';
			$message .= '</div>';
			$message .= '</body></html>';

			// return the message
			return $message;
		}		


	}