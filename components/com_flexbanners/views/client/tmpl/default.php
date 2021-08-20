<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// no direct access
defined('_JEXEC') or die;

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
$params		= (isset($this->state->params)) ? $this->state->params : new JObject();
$saveOrder	= $listOrder == 'ordering';
if($user->id=='' || $user->id==NULL) {
	echo JText::_('FLEXBANNER_BANNERSTATISTICS_LOGIN'); 
		} else {

if ($this->items) { 
?>
<script language="javascript" type="text/javascript">
function tableOrdering( order, dir, task )
{
	var form = document.adminForm;

	form.filter_order.value = order;
	form.filter_order_Dir.value = dir;
	document.adminForm.submit( task );
}
</script>
<style>
@font-face {
	font-family: 'IcoMoon';
	src: url('../../../../media/jui/fonts/IcoMoon.eot');
	src: url('../../../../media/jui/fonts/IcoMoon.eot?#iefix') format('embedded-opentype'), url('../../../../media/jui/fonts/IcoMoon.woff') format('woff'), url('../../../../media/jui/fonts/IcoMoon.ttf') format('truetype'), url('../../../../media/jui/fonts/IcoMoon.svg#IcoMoon') format('svg');
	font-weight: normal;
	font-style: normal;
}
.icon-publish:before, .icon-save:before, .icon-ok:before, .icon-checkmark:before {
    font-family: 'IcoMoon';
    content: "G";
}
.icon-publish:before, .icon-save:before, .icon-ok:before, .icon-save-new:before, .icon-save-copy:before, .btn-toolbar .icon-copy:before {
    color: #51A351;
}
.icon-unpublish:before, .icon-cancel:before {
    font-family: 'IcoMoon';
    content: "J";
}
.icon-unpublish:before, .icon-not-ok:before, .icon-eye-close:before, .icon-ban-circle:before, .icon-minus-sign:before, .btn-toolbar .icon-cancel:before {
    color: #BD362F;
}
.icon-expired:before {
    font-family: 'IcoMoon';
    content: "K";
}
.icon-featured:before, .icon-default:before, .icon-expired:before, .icon-pending:before {
    color: #F89406;
}
td.center, th.center, .center {
    float: none;
    text-align: center;
}
.jgrid span.text {
    display: none;
}
.jgrid span.state {
    display: inline-block;
    height: 16px;
    width: 16px;
}
.flexbtable div.pagination {text-align:center; margin:0;}
table.flexbtable, table.flexbtable th, table.flexbtable td {border:1px solid #d5d5d5;padding:2px 5px;}
table.flexbtable img {padding-left:3px;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_flexbanners&view=client'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped adminlist" id="articleList flexbtable">
		<thead>
			<tr>
				<th align="left">
					<?php echo JHtml::_('grid.sort',  'FLEXBANNER_HEADING_NAME', 'name', $listDirn, $listOrder); ?>
				</th>
				<th align="left" width="110" nowrap>
					<?php echo JText::_('FLEXBANNER_BANNERIMAGE'); ?>
				</th>
				<th width="100">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
				</th>
				<th width="120">
					<?php echo JHTML::_('grid.sort',  'FLEXBANNER_LOCATION', 'locationid', $listDirn, $listOrder ); ?>
				</th>
			<th width="80">
				<?php echo JText::_('FLEXBANNER_BANNERDAILYIMP'); ?>
			</th>
			<th width="80">
				<?php echo JText::_('FLEXBANNER_BANNERIMPMADE'); ?>
			</th>
			<th width="120" align="center">
				<?php echo JText::_('FLEXBANNER_BANNERCLICKS'); ?>
			</th>
			<th width="90" align="center">
				<?php echo JText::_('FLEXBANNER_BANNERPERCENTCLICKS'); ?>
			</th>
			<th width="100">
				<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
			</th>
			<th width="60;" class="nowrap">
				<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getLimitBox(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'ordering');
			$canChange = 0;
			?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid?>">
				<td>
					<?php echo $this->escape($item->name); ?>
				</td>
				<td>
					<?php if ($item->type == 0) { ?>
						<img src="<?php echo JURI::root() . $this->escape($item->imageurl); ?>" alt="<?php echo $this->escape($item->imagealt); ?>" width="110"/>
					<?php } elseif ($item->type == 1) {
						echo $this->escape($item->imagealt)."<br /> (Flash Banner)";  
						} elseif ($item->type == 2) {
						echo $this->escape($item->imagealt)."<br /> (Custom Code)";  
					} else { ?>
						<img src="<?php echo $this->escape($item->cloud_imageurl); ?>" alt="<?php echo $this->escape($item->imagealt); ?>" width="110"/>
					<?php }?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'flexbanners.', $canChange, 'cb', $item->startdate, $item->enddate); ?>
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
					echo number_format($this->escape($item->impmade),0).JText::_('FLEXBANNER_BANNEROF').$maximpdisplay; 
					?>
				</td>
				<td class="center">
					<?php 
					if ($this->escape($item->maxclicks!=0)) { $maxclickdisplay=number_format($this->escape($item->maxclicks,0));} else {$maxclickdisplay=JText::_('ADMIN_FLEXBANNER_UNLIMITED');}
					echo number_format($this->escape($item->clicks,0)).JText::_('FLEXBANNER_BANNEROF').$maxclickdisplay; 
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
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
		<?php echo JHtml::_('form.token'); ?>

	</div>

</form>
<?php
	} else {
	echo "<h2>".JText::_( 'FLEXBANNER_NOBANNER')."</h2>";
	}
}
?>