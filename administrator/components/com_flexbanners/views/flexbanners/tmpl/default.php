<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

	JHtml::_('bootstrap.tooltip');
	JHtml::_('dropdown.init');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_flexbanners.category');
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$params		= (isset($this->state->params)) ? $this->state->params : new JObject;
$saveOrder	= $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_flexbanners&task=flexbanners.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<form action="<?php echo JRoute::_('index.php?option=com_flexbanners&view=flexbanners'); ?>" method="post" name="adminForm" id="adminForm">

	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<table class="table table-striped adminlist" id="articleList">
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone"><?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2');
					?></th>
				<th width="20">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<th width="180">
					<?php echo JHtml::_('searchtools.sort',  'COM_FLEXBANNER_HEADING_NAME', 'name', $listDirn, $listOrder); ?>
				</th>
				<th align="left" width="150" nowrap>
					<?php echo JText::_('ADMIN_FLEXBANNER_BANNERIMAGE'); ?>
				</th>
				<th width="160">
					<?php echo JHTML::_('searchtools.sort',  'ADMIN_FLEXBANNER_CLIENT', 'clientid', $listDirn, $listOrder ); ?>
				</th>
				<th width="180">
					<?php echo JHTML::_('searchtools.sort',  'ADMIN_FLEXBANNER_LOCATION', 'locationid', $listDirn, $listOrder ); ?>
				</th>
				<th width="130">
					<?php echo JText::_('ADMIN_FLEXBANNER_BANNERDAILYIMP'); ?>
				</th>
				<th width="130" nowrap="nowrap">
					<?php echo JText::_('ADMIN_FLEXBANNER_BANNERIMPMADE'); ?>
				</th>
				<th width="90" align="center">
					<?php echo JText::_('ADMIN_FLEXBANNER_BANNERCLICKS'); ?>
				</th>
				<th width="200" align="center">
					<?php echo JText::_('ADMIN_FLEXBANNER_BANNERPERCENTCLICKS'); ?>
				</th>
				<th width="80">
					<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th width="10" class="nowrap">
					<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
				</th>
		</thead>
		<tfoot>
			<tr>
				<td colspan="14">
					<?php echo $this->pagination->getListFooter(); ?>
 				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering  = ($listOrder == 'ordering');
				$item->cat_link = JRoute::_('index.php?option=com_categories&extension=com_flexbanners&task=edit&type=other&clientid[]='. $item->catid);
				$canCreate  = $user->authorise('core.create',	  'com_flexbanners.category.'.$item->catid);
				$canEdit    = $user->authorise('core.edit',	  'com_flexbanners.category.'.$item->catid);
				$canCheckin = $user->authorise('core.manage',	  'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
				$canChange  = $user->authorise('core.edit.state', 'com_flexbanners.category.'.$item->catid) && $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid?>">
					<td class="order nowrap center hidden-phone">
						<?php
						$iconClass = '';
						if (!$canChange)
						{
							$iconClass = ' inactive';
						}
						elseif (!$saveOrder)
						{
								$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
						}
						?>
						<span class="sortable-handler <?php echo $iconClass ?>">
							<i class="icon-menu"></i>
						</span>
						<?php if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5"
								value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
						<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<div class="btn-group">
							<?php echo JHtml::_('jgrid.published', $item->state, $i, 'flexbanners.', $canChange, 'cb', $item->startdate, $item->enddate); ?>
							<?php // Create dropdown items and render the dropdown list.
							if ($canChange)
							{
								JHtml::_('actionsdropdown.' . ((int) $item->state === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'flexbanners');
								JHtml::_('actionsdropdown.' . ((int) $item->state === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'flexbanners');
								echo JHtml::_('actionsdropdown.render', $this->escape($item->name));
							}
							?>
						</div>
					</td>
					<td class="has-context">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'flexbanners.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_flexbanners&task=flexbanner.edit&id='.(int) $item->id); ?>">
									<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
							<div class="small">
								<?php echo $this->escape($item->category_title); ?>
							</div>
						</div>
					</td>
				<td>
					<?php if($item->type == 0) { ?>
						<img src="<?php echo JURI::root(). "../" . $this->escape($item->imageurl); ?>" alt="<?php echo $this->escape($item->imagealt); ?>" width="110"/>
					<?php } elseif ($item->type == 1) { 
						echo $this->escape($item->imagealt)."<br /> (Flash Banner)";  
						} else { ?>
						<img src="<?php echo $this->escape($item->cloud_imageurl); ?>" alt="<?php echo $this->escape($item->imagealt); ?>" width="110"/>
					<?php }?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->client_name); ?>
				</td>
				<td class="center">
					<?php echo $item->locationname;?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->dailyimpressions);?>
				</td>
				<td class="center">
					<?php 
					if ($this->escape($item->maximpressions!=0)) { $maximpdisplay=number_format($this->escape($item->maximpressions,0));} else {$maximpdisplay=JText::_('ADMIN_FLEXBANNER_UNLIMITED');}
					echo number_format($this->escape($item->impmade),0)." of ".$maximpdisplay; 
					?>
				</td>
				<td>
					<?php 
					if ($this->escape($item->maxclicks!=0)) { $maxclickdisplay=number_format($this->escape($item->maxclicks,0));} else {$maxclickdisplay=JText::_('ADMIN_FLEXBANNER_UNLIMITED');}
					echo number_format($this->escape($item->clicks,0))." of ".$maxclickdisplay; 
					?>
				</td>
				<td class="center">
					<?php 
					if ( $this->escape($item->impmade) != 0 ) {$percentClicks = substr(100 * $this->escape($item->clicks)/$this->escape($item->impmade), 0, 5);} else {$percentClicks = 0;}					
					echo (number_format(($percentClicks),2))."%";?>
				</td>
				<td class="center nowrap">
					<?php if ($item->language=='*'):?>
						<?php echo JText::alt('JALL','language'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->id); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
					<div style="float:right">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="W43XUP6SDZW9N">
						<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
						<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
						<div><b><?php echo JText::_('ADMIN_FLEXBANNER_DONATE'); ?></b></div>
						</form>
						
					</div>