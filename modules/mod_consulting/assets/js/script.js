"use strict";

	var recaptchaConsultingSubmit =  (token) =>  {
		MODCONSULTING.sendInfo(token);
	}


	let MODCONSULTING = {
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
					let widgetId = document.getElementById('recaptcha4').getAttribute('data-widgetid');	
									
					// disable all form inputs to prevent double entry
					[].forEach.call(this, function(input){						
						input.setAttribute('disabled', true);
					});			

					if(MODCONSULTING.validateInput(this)) {
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

			// Name
			if(form.elements['name'].value.length == 0) {
				errors.push('You must provide your name');
			}

			// Organization
			if(form.elements['organization'].value.length == 0) {
				errors.push('You must provide your organization');
			}


			// Email
			if(form.elements['email'].value.length == 0) {
				errors.push('You must provide your email address.');
			}		
			if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.elements['email'].value)) {
				errors.push('You must provide a valid email address');
			}


			// Description
			if(form.elements['description'].value.length == 0) {
				errors.push('Please let us know how we can help.');
			}	

			// Type
			if(!document.querySelector('.checkboxes input[name="type[]"]:checked')) {
				errors.push('Please select at least one Type of Support')
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
				let widgetId = document.getElementById('recaptcha4').getAttribute('data-widgetid');	


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
							console.log(parsed);
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

	}






	// Function to be called when DOM is ready
	var callbackCU = function() {
		MODCONSULTING.myForm = document.querySelector('form[id^="consulting-"]');
		//set correct alert parent
		MODCONSULTING.alertContainer = document.getElementById('system-message-container');
		MODCONSULTING.formSubmit();
	}

	// Check if DOM is ready
	if ( document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
	  callbackCU();	  
	} else {
	  document.addEventListener('DOMContentLoaded', callbackCU);
	}