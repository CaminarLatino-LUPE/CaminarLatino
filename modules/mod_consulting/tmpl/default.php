<?php
	/**
	 * Module for Joomla 
	 * @package     Consulting
	 * @author		Sal Bernal
	 *
	 * @copyright   Copyright (C) 2026 Caminar Latino.
	 *
	 *
	 * Template to display Consulting form
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
		<div Class="<?php echo !empty($moduleClass) ? $moduleClass : ''; ?>" style="max-width: 600px; margin: 0 auto;" id="consulting">
			<form id="consulting-<?php echo $moduleId ?>" class="form-validate styled-form" method="post" action="<?php echo Route::_('index.php?option=com_ajax&module=consulting&method=SendMessage&format=json&id=' . $moduleId) ?>">
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
							<label for="form-name" title="<?php echo Text::_('MOD_CONSULTING_NAME_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_NAME_DESC'); ?>" class="required">
								<?php echo Text::_('MOD_CONSULTING_NAME_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<input type="text" name="name" id="form-name" value="" placeholder="<?php echo Text::_('MOD_CONSULTING_NAME_LABEL'); ?>" class="required" aria-required="true" required="required" />
						</div>				
					</div>


					<div class="flex-outer" style="align-items: flex-end;">
						<!-- organization -->
						<div class="control-group">							
							<div class="control-label v-hidden">
								<label for="form-organization" title="<?php echo Text::_('MOD_CONSULTING_ORGANIZATION_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_ORGANIZATION_DESC'); ?>">
									<?php echo Text::_('MOD_CONSULTING_ORGANIZATION_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="organization" id="form-organization" value="" placeholder="<?php echo Text::_('MOD_CONSULTING_ORGANIZATION_LABEL'); ?>" class="required" aria-required="true" required="required" />
							</div>				
						</div>

						<!-- role -->
						<div class="control-group">							
							<div class="control-label v-hidden">
								<label for="form-role" title="<?php echo Text::_('MOD_CONSULTING_ROLE_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_ROLE_DESC'); ?>">
									<?php echo Text::_('MOD_CONSULTING_ROLE_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="role" id="form-role" value="" placeholder="<?php echo Text::_('MOD_CONSULTING_ROLE_LABEL'); ?>" />
							</div>				
						</div>


					</div>


					<div class="flex-outer" style="align-items: flex-end;">

						<!-- email -->
						<div class="control-group">
							<div class="control-label v-hidden">
								<label for="form-email" title="<?php echo Text::_('MOD_CONSULTING_EMAIL_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_EMAIL_DESC'); ?>" >
									<?php echo Text::_('MOD_CONSULTING_EMAIL_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="email"id="form-email" value=""  placeholder="<?php echo Text::_('MOD_CONSULTING_EMAIL_LABEL'); ?>" class="required" aria-required="true" required="required" />
							</div>
						</div>	


						<!-- phone -->
						<div class="control-group">
							<div class="control-label v-hidden">
								<label for="form-phone" title="<?php echo Text::_('MOD_CONSULTING_PHONE_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_PHONE_DESC'); ?>" >
									<?php echo Text::_('MOD_CONSULTING_PHONE_LABEL'); ?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="phone"id="form-phone" value=""  placeholder="<?php echo Text::_('MOD_CONSULTING_PHONE_LABEL'); ?>" />
							</div>
						</div>	
							
					</div>
				

					<!-- type -->
					<div class="control-group">
						<div class="control-label">
							<label for="form-type" title="<?php echo Text::_('MOD_CONSULTING_TYPE_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_TYPE_DESC'); ?>" >
								<?php echo Text::_('MOD_CONSULTING_TYPE_LABEL'); ?>
							</label>
						</div>
						<div class="controls">

						<fieldset id="form-type" class="required checkboxes" required="">
						<legend class="visually-hidden"><?php echo Text::_('MOD_CONSULTING_TYPE_LABEL'); ?></legend>
							<div class="checkboxes clearfix">
								<input type="checkbox" id="form-type0" name="type[]" value="0" >
								<label for="form-type0" ><?php echo Text::_('MOD_CONSULTING_TYPE_0'); ?></label>
								<input type="checkbox" id="form-type1" name="type[]" value="1" >
								<label for="form-type1" ><?php echo Text::_('MOD_CONSULTING_TYPE_1'); ?></label>	
								<input type="checkbox" id="form-type2" name="type[]" value="2" >
								<label for="form-type2" ><?php echo Text::_('MOD_CONSULTING_TYPE_2'); ?></label>
								<input type="checkbox" id="form-type3" name="type[]" value="3" >
								<label for="form-type3" ><?php echo Text::_('MOD_CONSULTING_TYPE_3'); ?></label>
								<input type="checkbox" id="form-type4" name="type[]" value="4" >
								<label for="form-type4" ><?php echo Text::_('MOD_CONSULTING_TYPE_4'); ?></label>								
							</div>				
						</fieldset>
						</div>
					</div>	

				
					<!-- description -->
					<div class="control-group">
						<div class="control-label v-hidden">
							<label for="form-description" title="<?php echo Text::_('MOD_CONSULTING_DESCRIPTION_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_DESCRIPTION_DESC'); ?>">
								<?php echo Text::_('MOD_CONSULTING_DESCRIPTION_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<textarea name="description" id="form-description" rows="4" placeholder="<?php echo Text::_('MOD_CONSULTING_DESCRIPTION_PLACEHOLDER'); ?>"></textarea>
						</div>
					</div>

					<!-- timeline -->
					<div class="control-group">						
						<div class="control-label v-hidden">
							<label for="form-timeline" title="<?php echo Text::_('MOD_CONSULTING_TIMELINE_DESC'); ?>" data-content="<?php echo Text::_('MOD_CONSULTING_TIMELINE_DESC'); ?>" >
								<?php echo Text::_('MOD_CONSULTING_TIMELINE_LABEL'); ?>
							</label>
						</div>
						<div class="controls">
							<input type="text" name="timeline" id="form-timeline" value="" placeholder="<?php echo Text::_('MOD_CONSULTING_TIMELINE_LABEL'); ?>"  />
						</div>				
					</div>



		            <input aria-invalid="false"
		                   class="form-control"
		                   id="form-mod-id"
		                   name="mod_id"
		                   style="display: block !important; opacity: 0; height: 0px;"
		                   value="<?php echo $moduleId; ?>" />	
				</fieldset>
				<div id="recaptcha4" class="g-recaptcha"
					data-sitekey = "<?php echo $siteKey ?>"
					data-callback="recaptchaConsultingSubmit"
					data-size="invisible"
					data-widgetid="">			
				</div>		
				<div class="text-center">
					<button type="submit" class="btn btn-primary"><?php echo Text::_('MOD_CONSULTING_FORM_SUBMIT_LABEL'); ?></button>
				
				</div>
					
			</form>
		</div>
	<?php endif; ?>







