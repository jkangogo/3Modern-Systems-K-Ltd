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
		if (task == 'client.cancel' || document.formvalidator.isValid(document.id('client-form')))
		{
			Joomla.submitform(task, document.getElementById('client-form'));
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_flexbanners&layout=edit&clientid='.(int) $this->item->clientid); ?>" method="post" name="adminForm" id="client-form" class="form-validate form-horizontal">
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
								<?php echo $this->form->getLabel('clientname'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('clientname'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('contactname'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('contactname'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('contactemail'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('contactemail'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('juserid'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('juserid'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('clientid'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('clientid'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>

	<!-- End Content -->
</form>