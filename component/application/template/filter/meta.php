<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Meta Template Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class TemplateFilterMeta extends Library\TemplateFilterMeta
{
    public function filter(&$text, Library\TemplateInterface $template = null)
    {
        $meta = $this->_parseTags($text);
        $text = str_replace('<ktml:meta>', $meta, $text);
    }
}