<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Decoratable Module Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class ModuleBehaviorDecoratable extends Library\ViewBehaviorDecoratable
{
    /**
     * Decorate the module
     *
     * @param Library\ViewContextInterface $context A view context object
     * @return void
     */
    protected function _beforeRender(Library\ViewContextInterface $context)
    {
        if($context->parameters->decorator)
        {
            $decorators = Library\ObjectConfig::unbox($context->parameters->decorator);

            if (is_string($decorators)) {
                $decorators = preg_split('/\s+/', $context->parameters->decorator);
            }

            foreach($decorators as $decorator)
            {
                if(!parse_url($decorator, PHP_URL_SCHEME)) {
                    $decorator = 'mod:pages/'.trim($decorator).'.'.$this->getFormat();
                }

                $this->addDecorator($decorator);
            }
        }
    }
}