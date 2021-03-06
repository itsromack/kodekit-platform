<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-categories for the canonical source repository
 */

namespace Kodekit\Component\Categories;

use Kodekit\Library;

/**
 * Persistable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Categories
 */
class ControllerBehaviorPersistable extends Library\ControllerBehaviorPersistable
{
    /**
     * Load the model state from the request
     *
     * This functions merges the request information with any model state information
     * that was saved in the session and returns the result.
     *
     * @param 	Library\ControllerContextModel $context A controller context object
     * @return  void
     */
    protected function _beforeBrowse(Library\ControllerContextModel $context)
    {
         // Built the session identifier based on the action
        $identifier  = $this->getModel()->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->getState()->table;
        $state       = $context->user->get($identifier);

        //Add the data to the request query object
        $context->request->add($state);

        //Push the request query data in the model
        $this->getModel()->setState($context->request->query->toArray());
    }

    /**
     * Saves the model state in the session
     *
     * @param 	Library\ControllerContextModel $context A controller context object
     * @return 	void
     */
    protected function _afterBrowse(Library\ControllerContextModel $context)
    {
        $model = $this->getModel();
        $state = $model->getState();

        // Built the session identifier based on the action
        $identifier  = $model->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->getState()->table;

        //Set the state in the user session
        $context->user->set($identifier, $state->getValues());
    }
}