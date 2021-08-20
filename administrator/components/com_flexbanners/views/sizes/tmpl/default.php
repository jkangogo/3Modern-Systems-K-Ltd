<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/


// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$params     = (isset($this->state->params)) ? $this->state->params : new JObject;
?>

<form action="<?php echo JRoute::_('index.php?option=com_flexbanners&view=sizes'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<table class="table table-striped adminlist">
		<thead>
			<tr>
				<th width="1%" class="center">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th width="5%" class="nowrap center">
					<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<th class="title" style="text-align:left">
					<?php echo JHtml::_('searchtools.sort', 'ADMIN_FLEXBANNER_SIZENAME', 'a.sizename', $listDirn, $listOrder); ?>
				</th>
				<th width="20%" class="nowrap">
					<?php echo JHtml::_('searchtools.sort', 'ADMIN_FLEXBANNER_SIZEWIDTH', 'a.width', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JHtml::_('searchtools.sort', 'ADMIN_FLEXBANNER_SIZEHEIGHT', 'a.height', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('searchtools.sort', 'ADMIN_FLEXBANNER_MAXFILESIZE', 'a.maxfilesize', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.sizeid', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'ordering');
			$canCreate	= $user->authorise('core.create',		'com_flexbanners');
			$canEdit	= $user->authorise('core.edit',			'com_flexbanners');
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
			$canChange	= $user->authorise('core.edit.state',	'com_flexbanners') && $canCheckin;
			?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->sizeid); ?>
					</td>
					<td class="center">
						<div class="btn-group">
							<?php echo JHtml::_('jgrid.published', $item->state, $i, 'sizes.', $canChange); ?>
							<?php // Create dropdown items and render the dropdown list.

							if ($canChange)
							{
								JHtml::_('actionsdropdown.' . ((int) $item->state === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'sizes');
								JHtml::_('actionsdropdown.' . ((int) $item->state === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'sizes');
								echo JHtml::_('actionsdropdown.render', $this->escape($item->sizename));
							}
							?>
						</div>
					</td>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'sizes.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_flexbanners&task=size.edit&sizeid='.(int) $item->sizeid); ?>">
									<?php echo $this->escape($item->sizename); ?></a>
							<?php else : ?>
									<?php echo $this->escape($item->sizename); ?>
							<?php endif; ?>
						</div>
				</td>
				<td class="center">
					<?php echo $item->width;?>
				</td>
				<td class="center">
					<?php echo $item->height;?>
				</td>
				<td class="center">
					<?php echo $item->maxfilesize;?>
				</td>
				<td class="center hidden-phone">
					<?php echo $item->sizeid; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>

</form>
