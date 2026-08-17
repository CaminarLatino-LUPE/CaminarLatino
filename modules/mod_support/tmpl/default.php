<?php
	/**
	 * Module for Joomla 
	 * @package     Support
	 * @author		Sal Bernal
	 *
	 * @copyright   Copyright (C) 2026 Caminar Latino.
	 *
	 *
	 * Template to display Support form
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
		<div Class="<?php echo !empty($moduleClass) ? $moduleClass : ''; ?>" style="max-width: 600px; margin: 0 auto;" id="support">
			<form id="support-<?php echo $moduleId ?>" class="form-validate styled-form" method="post" action="<?php echo Route::_('index.php?option=com_ajax&module=support&method=SendMessage&format=json&id=' . $moduleId) ?>">
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
						<!-- name -->
						<div class="control-label v-hidden">
							<label for="form-name" title="<?php echo Text::_('MOD_SUPPORT_NAME_DESC'); ?>" data-content="<?php echo Text::_('MOD_SUPPORT_NAME_DESC'); ?>" class="required">
								<?php echo Text::_('MOD_SUPPORT_NAME_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<input type="text" name="name" id="form-name" value="" placeholder="<?php echo Text::_('MOD_SUPPORT_NAME_LABEL'); ?>" />
						</div>				
					</div>


					<div class="flex-outer" style="align-items: flex-end;">

						<!-- cmethod -->
						<div class="control-group">
							<div class="control-label">
								<label for="form-cmethod" title="<?php echo Text::_('MOD_SUPPORT_METHOD_DESC'); ?>" data-content="<?php echo Text::_('MOD_SUPPORT_METHOD_DESC'); ?>">
									<?php echo Text::_('MOD_SUPPORT_METHOD_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<select id="form-cmethod" name="cmethod">
									<option value=""><?php echo Text::_('MOD_SUPPORT_METHOD_0'); ?></option>
									<option value="1"><?php echo Text::_('MOD_SUPPORT_METHOD_1'); ?></option>
									<option value="2"><?php echo Text::_('MOD_SUPPORT_METHOD_2'); ?></option>
								</select>
							</div>
						</div>	



						<!-- contact -->
						<div class="control-group">
							<div class="control-label v-hidden">
								<label for="form-contact" title="<?php echo Text::_('MOD_SUPPORT_CONTACT_DESC'); ?>" data-content="<?php echo Text::_('MOD_SUPPORT_CONTACT_DESC'); ?>" >
									<?php echo Text::_('MOD_SUPPORT_CONTACT_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="contact"id="form-contact" value=""  placeholder="" />
							</div>
						</div>	
							
					</div>
				


					<!-- language -->
					<div class="control-group">
						<div class="control-label" style="display: inline-block;">
							<label for="form-language" title="<?php echo Text::_('MOD_SUPPORT_LANGUAGE_DESC'); ?>" data-content="<?php echo Text::_('MOD_SUPPORT_LANGUAGE_DESC'); ?>">
								<?php echo Text::_('MOD_SUPPORT_LANGUAGE_LABEL'); ?>
							</label>
						</div>
						<div class="controls" style="display: inline-block;">
							<fieldset>
								<legend class="visually-hidden"><?php echo Text::_('MOD_SUPPORT_LANGUAGE_LABEL'); ?></legend>
								<div class="radio clearfix">
									<input type="radio" id="form-language0" name="language" value="1">
									<label for="form-language0" style="float:left;"><?php echo Text::_('MOD_SUPPORT_LANGUAGE_0'); ?></label>
									<input class="btn-check" type="radio" id="form-language1" name="language" value="2" style="margin-left: 20px;">
									<label for="form-language1" style="float:left;"><?php echo Text::_('MOD_SUPPORT_LANGUAGE_1'); ?></label>
								</div>
							</fieldset>
						</div>
					</div>	


				
					<!-- description -->
					<div class="control-group">
						<div class="control-label v-hidden">
							<label for="form-description" title="<?php echo Text::_('MOD_SUPPORT_DESCRIPTION_DESC'); ?>" data-content="<?php echo Text::_('MOD_SUPPORT_DESCRIPTION_DESC'); ?>">
								<?php echo Text::_('MOD_SUPPORT_DESCRIPTION_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<textarea name="description" id="form-description" rows="4" placeholder="<?php echo Text::_('MOD_SUPPORT_DESCRIPTION_PLACEHOLDER'); ?>"></textarea>
						</div>
					</div>


		            <input aria-invalid="false"
		                   class="form-control"
		                   id="form-mod-id"
		                   name="mod_id"
		                   style="display: block !important; opacity: 0; height: 0px;"
		                   value="<?php echo $moduleId; ?>" />	
				</fieldset>
				<div id="recaptcha3" class="g-recaptcha"
					data-sitekey = "<?php echo $siteKey ?>"
					data-callback="recaptchaSupportSubmit"
					data-size="invisible"
					data-widgetid="">			
				</div>		
				<div class="text-center">
					<button type="submit" class="btn btn-primary"><?php echo Text::_('MOD_SUPPORT_FORM_SUBMIT_LABEL'); ?></button>
				
				</div>
					
			</form>
		</div>
	<?php endif; ?>







