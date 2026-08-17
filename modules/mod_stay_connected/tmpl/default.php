<?php
	/**
	 * Module for Joomla 
	 * @package     Stay Connected
	 * @author		Sal Bernal
	 *
	 * @copyright   Copyright (C) 2026 Caminar Latino.
	 *
	 *
	 * Template to display Stay Connected form
	 * 
	 */
	
	// No direct access
	\defined('_JEXEC') or die('Restricted access');

	use Joomla\CMS\Factory;
	use Joomla\CMS\Form\Form;
	use Joomla\CMS\Language\Text;
	use Joomla\CMS\Router\Route;
	use Joomla\CMS\HTML\HTMLHelper;

														

?>

	<?php if(!empty($title)): ?>
		<h2 class="text-center" style="margin-top: 0;"><?php echo Text::_('MOD_STAY_CONNECTED_PAGE_TITLE'); ?></h2>		
	<?php endif; ?>


	<?php if(!empty($introText)): ?>
		<div>
			<p>
				<?php echo $introText; ?>
			</p>
		</div>
	<?php endif; ?>

<script type="text/javascript">
    var CaptchaCallback = function() {
    	const recaptchaDivs = document.querySelectorAll('.g-recaptcha');

		[].forEach.call(recaptchaDivs, function(div){						
			let widgetId = grecaptcha.render(div, {
				'sitekey' : div.getAttribute('data-sitekey'),
				'size'	: div.getAttribute('data-size'),
				'callback' : div.getAttribute('data-callback')
			});
			div.setAttribute('data-widgetid', widgetId);
		});			
    };
</script>
				

	<?php if(!empty($siteKey) && !empty($secretKey)): ?>
		<div Class="<?php echo !empty($moduleClass) ? $moduleClass : ''; ?>" style="max-width: 600px; margin: 0 auto;" id="stay-connected">
			<form id="stay-connected-<?php echo $moduleId ?>" class="form-validate styled-form" method="post" action="<?php echo Route::_('index.php?option=com_ajax&module=stay_connected&method=SendMessage&format=json&id=' . $moduleId) ?>">
				<fieldset>
					<legend class="v-hidden">Stay Connected</legend>
					<div class="control-group">
						<input
							aria-invalid="false"
							class="form-control"
							id="a-password"
							name="a_password"
							style="display: block !important; padding: 0; opacity: 0; height: 0px;"
							type="text"
							tabindex="-1"
							autocomplete="off"
						/>	
						<!-- first_name -->
						<div class="control-label v-hidden">
							<label for="form-first_name" title="<?php echo Text::_('MOD_STAY_CONNECTED_FIRST_NAME_DESC'); ?>" data-content="<?php echo Text::_('MOD_STAY_CONNECTED_FIRST_NAME_DESC'); ?>" class="required">
								<?php echo Text::_('MOD_STAY_CONNECTED_FIRST_NAME_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<input type="text" name="first_name" id="form-first_name" value="" class="required" aria-required="true" required="required" placeholder="<?php echo Text::_('MOD_STAY_CONNECTED_FIRST_NAME_LABEL'); ?>" />
						</div>				
					</div>
					<!-- email-->
					<div class="control-group">
						<div class="control-label v-hidden">
							<label for="form-email" title="<?php echo Text::_('MOD_STAY_CONNECTED_EMAIL_DESC'); ?>" data-content="<?php echo Text::_('MOD_STAY_CONNECTED_EMAIL_DESC'); ?>" class="required">
								<?php echo Text::_('MOD_STAY_CONNECTED_EMAIL_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<input type="text" name="email"id="form-email" value="" class="required" aria-required="true" required="required" placeholder="<?php echo Text::_('MOD_STAY_CONNECTED_EMAIL_LABEL'); ?>" />
						</div>
					</div>					

				
					<input type="hidden" name="service_value" value="">

		            <input aria-invalid="false"
		                   class="form-control"
		                   id="form-mod-id"
		                   name="mod_id"
		                   style="display: block !important; opacity: 0; height: 0px;"
		                   value="<?php echo $moduleId; ?>" />	
				</fieldset>
				<div id="recaptcha1" class="g-recaptcha"
					data-sitekey = "<?php echo $siteKey ?>"
					data-callback="recaptchaStayConnectedSubmit"
					data-size="invisible"
					data-widgetid="">			
				</div>		
				<div class="text-center">
					<button type="submit" class="btn btn-primary">Submit</button>
				
				</div>
					
			</form>
		</div>
	<?php endif; ?>







