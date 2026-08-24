<?php
	/**
	 * Module for Joomla 
	 * @package     Volunteer
	 * @author		Sal Bernal
	 *
	 * @copyright   Copyright (C) 2026 Caminar Latino.
	 *
	 *
	 * Template to display Volunteer form
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
		<h2 class="text-center" style="margin-top: 0;"><?php echo $title; ?></h2>		
	<?php endif; ?>


	<?php if(!empty($introText)): ?>
		<div>
			<p>
				<?php echo $introText; ?>
			</p>
		</div>
	<?php endif; ?>
				

	<?php if(!empty($siteKey) && !empty($secretKey)): ?>
		<div Class="<?php echo !empty($moduleClass) ? $moduleClass : ''; ?>" style="max-width: 600px; margin: 0 auto;" id="volunteer">
			<form id="volunteer-<?php echo $moduleId ?>" class="form-validate styled-form" method="post" action="<?php echo Route::_('index.php?option=com_ajax&module=volunteer&method=SendMessage&format=json&id=' . $moduleId) ?>">
				<fieldset>
					<legend class="v-hidden">Stay Connected</legend>

					<div class="flex-outer">
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
								<label for="form-first_name" title="<?php echo Text::_('MOD_VOLUNTEER_FIRST_NAME_DESC'); ?>" data-content="<?php echo Text::_('MOD_VOLUNTEER_FIRST_NAME_DESC'); ?>" class="required">
									<?php echo Text::_('MOD_VOLUNTEER_FIRST_NAME_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="first_name" id="form-first_name" value="" class="required" aria-required="true" required="required" placeholder="<?php echo Text::_('MOD_VOLUNTEER_FIRST_NAME_LABEL'); ?>" />
							</div>				
						</div>

						<!-- last_name -->
						<div class="control-group">						
							<div class="control-label v-hidden">
								<label for="form-last_name" title="<?php echo Text::_('MOD_VOLUNTEER_LAST_NAME_DESC'); ?>" data-content="<?php echo Text::_('MOD_VOLUNTEER_LAST_NAME_DESC'); ?>" >
									<?php echo Text::_('MOD_VOLUNTEER_LAST_NAME_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="last_name" id="form-last_name" value="" placeholder="<?php echo Text::_('MOD_VOLUNTEER_LAST_NAME_LABEL'); ?>" />
							</div>				
						</div>
					</div>


					<div class="flex-outer">
						<!-- email-->
						<div class="control-group">
							<div class="control-label v-hidden">
								<label for="form-email" title="<?php echo Text::_('MOD_VOLUNTEER_EMAIL_DESC'); ?>" data-content="<?php echo Text::_('MOD_VOLUNTEER_EMAIL_DESC'); ?>" class="required">
									<?php echo Text::_('MOD_VOLUNTEER_EMAIL_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="email"id="form-email" value="" class="required" aria-required="true" required="required" placeholder="<?php echo Text::_('MOD_VOLUNTEER_EMAIL_LABEL'); ?>" />
							</div>
						</div>	


						<!-- phone-->
						<div class="control-group">
							<div class="control-label v-hidden">
								<label for="form-phone" title="<?php echo Text::_('MOD_VOLUNTEER_PHONE_DESC'); ?>" data-content="<?php echo Text::_('MOD_VOLUNTEER_PHONE_DESC'); ?>">
									<?php echo Text::_('MOD_VOLUNTEER_PHONE_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="phone"id="form-phone" value="" placeholder="<?php echo Text::_('MOD_VOLUNTEER_PHONE_LABEL'); ?>" />
							</div>
						</div>							
					</div>
				

					<!-- reference -->
					<div class="control-group">
						<div class="control-label v-hidden">
							<label for="form-reference" title="<?php echo Text::_('MOD_VOLUNTEER_REFERENCE_DESC'); ?>" data-content="<?php echo Text::_('MOD_VOLUNTEER_REFERENCE_DESC'); ?>">
								<?php echo Text::_('MOD_VOLUNTEER_REFERENCE_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<textarea name="reference" id="form-reference" rows="4" placeholder="<?php echo Text::_('MOD_VOLUNTEER_REFERENCE_PLACE'); ?>"></textarea>
						</div>
					</div>


					<div class="flex-outer">
						<!-- interest-->
						<div class="control-group">
							<div class="control-label v-hidden">
								<label for="form-interest" title="<?php echo Text::_('MOD_VOLUNTEER_INTEREST_DESC'); ?>" data-content="<?php echo Text::_('MOD_VOLUNTEER_INTEREST_DESC'); ?>">
									<?php echo Text::_('MOD_VOLUNTEER_INTEREST_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<select id="form-interest" name="interest">
									<option value=""><?php echo Text::_('MOD_VOLUNTEER_INTEREST_LABEL'); ?></option>
									<option value="<?php echo Text::_('MOD_VOLUNTEER_INTEREST_1'); ?>"><?php echo Text::_('MOD_VOLUNTEER_INTEREST_1'); ?></option>
									<option value="<?php echo Text::_('MOD_VOLUNTEER_INTEREST_2'); ?>"><?php echo Text::_('MOD_VOLUNTEER_INTEREST_2'); ?></option>
									<option value="<?php echo Text::_('MOD_VOLUNTEER_INTEREST_3'); ?>"><?php echo Text::_('MOD_VOLUNTEER_INTEREST_3'); ?></option>
								</select>
							</div>
						</div>	


						<!-- type-->
						<div class="control-group">
							<div class="control-label v-hidden">
								<label for="form-type" title="<?php echo Text::_('MOD_VOLUNTEER_TYPE_DESC'); ?>" data-content="<?php echo Text::_('MOD_VOLUNTEER_TYPE_DESC'); ?>">
									<?php echo Text::_('MOD_VOLUNTEER_TYPE_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<select id="form-type" name="type">
									<option value=""><?php echo Text::_('MOD_VOLUNTEER_TYPE_LABEL'); ?></option>
									<option value="<?php echo Text::_('MOD_VOLUNTEER_TYPE_1'); ?>"><?php echo Text::_('MOD_VOLUNTEER_TYPE_1'); ?></option>
									<option value="<?php echo Text::_('MOD_VOLUNTEER_TYPE_2'); ?>"><?php echo Text::_('MOD_VOLUNTEER_TYPE_2'); ?></option>
								</select>
							</div>
						</div>							
					</div>
				

		            <input aria-invalid="false"
		                   class="form-control"
		                   id="form-mod-id"
		                   name="mod_id"
		                   style="display: block !important; opacity: 0; height: 0px;"
		                   value="<?php echo $moduleId; ?>" />	
				</fieldset>
				<div id="recaptcha2" class="g-recaptcha"
					data-sitekey = "<?php echo $siteKey ?>"
					data-callback="recaptchaVolunteerSubmit"
					data-size="invisible"
					data-widgetid="">			
				</div>		
				<div class="text-center">
					<button type="submit" class="btn btn-primary"><?php echo Text::_('MOD_VOLUNTEER_FORM_SUBMIT_LABEL'); ?></button>
				
				</div>
					
			</form>
		</div>
	<?php endif; ?>







