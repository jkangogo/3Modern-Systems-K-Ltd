<?php
/**
 * @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die ;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', '#jform_catid', null, array('disable_search_threshold' => 0 ));
JHtml::_('formbehavior.chosen', 'select');

// Add the script to the document head.
JFactory::getDocument() -> addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "flexbanner.cancel" || document.formvalidator.isValid(document.getElementById("flexbanner-form")))
		{
			Joomla.submitform(task, document.getElementById("flexbanner-form"));
		}
	};
	
	jQuery(document).ready(function ($){
		$("#jform_type").on("change", function (a, params) {

			var v = typeof(params) !== "object" ? $("#jform_type").val() : params.selected;

			var img_url = $("#image, #url");
			var custom  = $("#custom");

			switch (v) {
				case "0":
					// Image
					img_url.show();
					custom.hide();
					break;
				case "1":
					// Custom
					img_url.hide();
					custom.show();
					break;
			}
		}).trigger("change");
	});
');
?>	

<form action="<?php echo JRoute::_('index.php?option=com_flexbanners&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="flexbanner-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<!-- Begin Banner -->
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Details'); ?>
	<div class="row-fluid">
		<div class="span9" >
			<?php foreach($this->form->getFieldset('flexbannerclient') as $field): ?>
			<div class="control-group">
				<div class="control-label">
					<?php echo $field -> label; ?>
				</div>
				<div class="controls">
					<?php echo $field -> input; ?>
				</div>
			</div>
			<?php endforeach; ?>
				<?php echo $this -> form -> getControlGroup('locationid'); ?>
			<?php echo $this -> form -> getControlGroup('sizeid'); ?>
			<div class="control-group">
				<div class="control-label">
					<?php echo $this -> form -> getLabel('type'); ?>
				</div>
				<div class="controls">
					<?php echo $this -> form -> getInput('type'); ?>
				</div>

				<div id="image">
					<div class="row-fluid">
						<div class="control-label">
							<?php echo $this -> form -> getLabel('imageurl'); ?>
						</div>
						<div class="controls">
							<?php echo $this -> form -> getInput('imageurl'); ?>
						</div>
					</div>

					<div class="control-label">
						<label><?php echo JText::_('ADMIN_FLEXBANNER_BANNERIMAGE'); ?></label>
					</div>
					<div class="controls">
						<?php
						if (preg_match("/gif|jpg|jpeg|png/", $this->item->imageurl)) {
						?><img src="../<?php echo $this -> item -> imageurl; ?>" name="imagelib" />
						<?php
						} else {
						?><img src="../administrator/com_flexbanners/images/blank.png" name="imagelib" />
						<?php
						}
						?>
					</div>
				</div>
				<div id="flash">
					<div class="row-fluid">
						<div class="control-label">
							<?php echo $this -> form -> getLabel('flash'); ?>
						</div>
						<div class="controls">
							<?php echo $this -> form -> getInput('flash'); ?>
						</div>
					</div>
				</div>
				<div class="clr"><BR /></div>
				<div id="cloud_image">
						<div class="control-label">
							<?php echo $this -> form -> getLabel('cloud_imageurl'); ?>
						</div>
						<div class="controls">
							<?php echo $this -> form -> getInput('cloud_imageurl'); ?>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label><?php echo JText::_('ADMIN_FLEXBANNER_BANNERIMAGE'); ?></label>
							</div>
							<div class="controls">
								<?php if (preg_match("/swf|html/", $this->item->cloud_imageurl)) {
								?><img src="../administrator/com_flexbanners/images/blank.png" name="imagelib">
								<?php
								} elseif (preg_match("/gif|jpg|png/", $this->item->cloud_imageurl)) {
								?><img src="<?php echo $this -> item -> cloud_imageurl; ?>" name="imagelib" />
								<?php
								} else {
								?><img src="../administrator/com_flexbanners/images/blank.png" name="imagelib" />
								<?php
								}
								?>
							</div>
							
						</div>
					</div>

					<div id="custom">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this -> form -> getLabel('customcode'); ?>
							</div>
							<div class="controls">
								<?php echo $this -> form -> getInput('customcode'); ?>
							</div>
						</div>
					</div>
					<div class="clr"><BR /></div>
					<div id="linkurl">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this -> form -> getLabel('linkid'); ?>
							</div>
							<div class="controls">
								<?php echo $this -> form -> getInput('linkid'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this -> form -> getLabel('imagealt'); ?>
							</div>
							<div class="controls">
								<?php echo $this -> form -> getInput('imagealt'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this -> form -> getLabel('newwin'); ?>
							</div>
							<div class="controls">
								<?php echo $this -> form -> getInput('newwin'); ?>
							</div>
						</div>
					</div>

					<div class="control-group">
						<div class="control-label">
							<?php echo $this -> form -> getLabel('id'); ?>
						</div>
						<div class="controls">
							<?php echo $this -> form -> getInput('id'); ?>
						</div>
					</div>
				</div>
			</div>



	<!-- End Newsfeed -->
	<!-- Begin Sidebar -->
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>

	<!-- End Sidebar -->
	<?php echo JHtml::_('bootstrap.endTab'); ?>	

	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'restrictions', 'Content Restrictions'); ?>
	<?php echo $this -> form -> renderFieldset('flexbanner'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'otherparams', 'Banner Details'); ?>
	<?php echo $this -> form -> renderFieldset('otherparams'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
					<?php foreach ($this->form->getFieldset('publish') as $field) :
					?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field -> label;?>
						</div>
						<div class="controls">
							<?php echo $field -> input;?>
						</div>
					</div>
					<?php endforeach;?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	
</form>
