<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachment Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Attachments
 */
abstract class ControllerAttachment extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model' => 'com:attachments.model.attachments'
        ));

        parent::_initialize($config);

        //Force the toolbars
        $config->toolbars = array('menubar', 'com:attachments.controller.toolbar.attachment');
    }

    protected function _actionRender(Library\CommandContext $context)
    {
        $view = $this->getView();

        //Set the layout
        if($view instanceof Library\ViewTemplate)
        {
            $layout = clone $view->getIdentifier();
            $layout->name  = $view->getLayout();

            $alias = clone $layout;
            $alias->package = 'attachments';

            $this->getObject('manager')->registerAlias($layout, $alias);
        }

        return parent::_actionRender($context);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        //Force set the 'table' in the request
        $request->query->table  = $this->getIdentifier()->package;
        $request->data->table   = $this->getIdentifier()->package;

        return $request;
    }
}
