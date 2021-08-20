<?php
/**
 * @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die ;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.framework', true);
JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

$canDo	= FlexbannersHelper::getActions();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'size.cancel' || document.formvalidator.isValid(document.id('size-form')))
		{
			Joomla.submitform(task, document.getElementById('size-form'));
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_flexbanners&layout=edit&sizeid=' . (int)$this -> item -> sizeid);?>" method="post" name="adminForm" id="size-form" class="form-validate">
	<!-- Begin Content -->
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Details'); ?>
	<div class="row-fluid">
		<div class="span12" >

						<?php if ($canDo->get('core.edit.state')) : ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('state'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('state'); ?>
								</div>
							</div>
						<?php endif; ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('sizename'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('sizename'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('width'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('width'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('height'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('height'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('maxfilesize'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('maxfilesize'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('sizeid'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('sizeid'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token');?>

</form>