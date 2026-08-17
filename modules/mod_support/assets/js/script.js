"use strict";

	var recaptchaSupportSubmit =  (token) =>  {
		MODSUPPORT.sendInfo(token);
	}


	let MODSUPPORT = {
		myForm: null,
		alertContainer: null,




		/**
		 * Function to sumbit form
		 * @return {[type]} [description]
		 */
		formSubmit: function() {

			if(this.myForm != null) {

				this.myForm.addEventListener('submit', function(e){
					e.preventDefault();

					// get recaptcha widgetId
					let widgetId = document.getElementById('recaptcha3').getAttribute('data-widgetid');	
									
					// disable all form inputs to prevent double entry
					[].forEach.call(this, function(input){						
						input.setAttribute('disabled', true);
					});			

					if(MODSUPPORT.validateInput(this)) {
						// Trigger AJAX function

						grecaptcha.execute(widgetId);
					}

				});
			}		
		},


		/**
		 * Function to validate form data
		 * @param  {HTML form element} form [The form to be used]
		 * @return {bool}      [Depending on validation]
		 */
		validateInput: function(form) {

			// variables
			var errors = [];
			var alertContainer = this.alertContainer;

			// First Name
			// if(form.elements['first_name'].value.length == 0) {
			// 	errors.push('You must provide your first name');
			// }


			// Email
			// if(form.elements['email'].value.length == 0) {
			// 	errors.push('You must provide your email address.');
			// }		
			// if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.elements['email'].value)) {
			// 	errors.push('You must provide a valid email address');
			// }


			// Preferred Language
			const langArray = Array.from(document.querySelectorAll('input[name="language"]')); 
			let checkedValue = null; 
			for (let i = 0; i < langArray.length; i++) {
			  if (langArray[i].checked) {
			    checkedValue = langArray[i].value;
			    break; 
			  }
			}
			if(checkedValue === null) {
				errors.push('You must select your Preferred Language.');
			}


			// Method
			let methodValue = form.elements['cmethod'].value;

			if(methodValue === '') {
				errors.push('You must select your Preferred Method of Contact');
			}
			else {
				if(+methodValue === 1) {
					// Email validation
					if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.elements['contact'].value)) {
						errors.push('You must provide a valid Email Address');
					}
				}
				else {
					// Phone validation
					if(!/^^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$$/.test(form.elements['contact'].value)) {
						errors.push('You must provide a valid Phone Number');
					}
				}				
			}




			// Description
			if(form.elements['description'].value.length == 0) {
				errors.push('Please let us know how we can help.');
			}	

			// show error message

			if(errors.length) {
				caminarlatino.showAlert(alertContainer, errors, 'warning', true);	
				// enable all form elements to prevent double entry
				[].forEach.call(form, function(input){
					input.removeAttribute('disabled');
				});					
				return false;

			}
			else {
				return true;
			}
		},		

		/**
		 * Function to send form info after captcha authentication
		 * @param  {int} token [the captcha token sent]
		 * @return {[type]}       [description]
		 */
		sendInfo: function(token) {
			let myForm = this.myForm; 
			let alertContainer = this.alertContainer;
			return new Promise(function(resolve, reject) { 
			    if (grecaptcha === undefined) {
			    	caminarlatino.showAlert(alertContainer, 'Recaptcha non defined', 'error', true);
			        //return;
			        reject();
			    }

				// get recaptcha widgetId
				let widgetId = document.getElementById('recaptcha3').getAttribute('data-widgetid');	


			    let response = grecaptcha.getResponse(widgetId);

			    if (!response) {
			    	caminarlatino.showAlert(alertContainer, 'Coud not get recaptcha response', 'error', true);
			        //return;
			        reject();
			    }

				[].forEach.call(myForm, function(element){
					element.removeAttribute('disabled');
				});	
				let postData = utilities.serialize(myForm);

				let url = myForm.getAttribute('action');

				let request =  utilities.ajaxRequest(url, postData, 'POST')
					.then(function (data) {
						var parsed = JSON.parse(data.response);						
						var sent = false;
						var alertMessage = '';
						if(parsed.status == 'success') {	
							caminarlatino.showAlert(alertContainer, 'Your message has been submitted, someone will contact you shortly', 'success', true);
							myForm.reset();
						}
						else {			
							caminarlatino.showAlert(alertContainer, parsed.message, 'error', true);
						}	
						grecaptcha.reset(widgetId);


					})
					.catch(function (error) {
						caminarlatino.showAlert(alertContainer, 'Error submitting the form. Please contact the system administrator', 'error', true);
						console.log('Something went wrong', error);
						grecaptcha.reset(widgetId);
					});				


			});

		},

		methodOnChange: () => {
			let form = document.querySelector('form[id^="support-"]');

			let methodField = form.elements['cmethod'];
			let contactField = form.elements['contact'];
			if(methodField !== null) {
				methodField.addEventListener('change', (e) => {
					if(+e.target.value === 1){
						contactField.placeholder = 'Enter Email Address';
					}
					else {
						contactField.placeholder = 'Enter Phone Number';
					}
				});
			}
		}

	}






	// Function to be called when DOM is ready
	var callbackCU = function() {
		MODSUPPORT.myForm = document.querySelector('form[id^="support-"]');
		//set correct alert parent
		MODSUPPORT.alertContainer = document.getElementById('system-message-container');
		MODSUPPORT.methodOnChange();
		MODSUPPORT.formSubmit();
	}

	// Check if DOM is ready
	if ( document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
	  callbackCU();	  
	} else {
	  document.addEventListener('DOMContentLoaded', callbackCU);
	}