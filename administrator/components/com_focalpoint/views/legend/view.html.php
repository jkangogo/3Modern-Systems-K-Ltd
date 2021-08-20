<?php
/**
 * @package   ShackLocations
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2013-2017 John Pitchers <john@viperfish.com.au> - http://viperfish.com.au
 * @copyright 2018-2021 Joomlashack.com. All rights reserved
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of ShackLocations.
 *
 * ShackLocations is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * ShackLocations is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ShackLocations.  If not, see <https://www.gnu.org/licenses/>.
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die();

class FocalpointViewLegend extends HtmlView
{
    /**
     * @var CMSObject
     */
    protected $state = null;

    /**
     * @var CMSObject
     */
    protected $item;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @param string $tpl
     *
     * @return void
     * @throws Exception
     */
    public function display($tpl = null)
    {
        /** @var FocalpointModellegend $model */
        $model = $this->getModel();

        $this->state = $model->getState();
        $this->item  = $model->getItem();
        $this->form  = $model->getForm();

        if ($errors = $model->getErrors()) {
            throw new Exception(implode("\n", $errors));
        }

        $this->addToolbar();

        parent::display($tpl);

        echo FocalpointHelper::renderAdminFooter();
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', true);

        $user  = Factory::getUser();
        $isNew = ($this->item->id == 0);

        $checkedOut = !empty($this->item->checked_out) && $this->item->checked_out != $user->get('id');

        $title = 'COM_FOCALPOINT_TITLE_LEGEND_' . ($isNew ? 'ADD' : 'EDIT');
        ToolbarHelper::title(Text::_($title), 'list-2');

        if (!$checkedOut) {
            if ($user->authorise('core.edit', 'com_focalpoint')
                || ($user->authorise('core.create', 'com_focalpoint'))
            ) {
                ToolBarHelper::apply('legend.apply');
                ToolBarHelper::save('legend.save');
            }

            if ($user->authorise('core.create', 'com_focalpoint')) {
                ToolBarHelper::save2new('legend.save2new');
            }
        }

        if (!$isNew && $user->authorise('core.create', 'com_focalpoint')) {
            ToolBarHelper::save2copy('legend.save2copy');
        }

        ToolBarHelper::cancel('legend.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}
