<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

/**
 * Search Pages Element
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class JElementSearchpages extends JElement
{
    var $_name = 'Searchpages';

    public function fetchElement($name, $value, $param, $group)
    {
        $config = array(
            'name'     => $group . '[' . $name . ']',
            'selected' => $value,
            'deselect' => false,
        );

        $html = Kodekit::getObject('com:articles.template.helper.listbox')->searchpages($config);
        return $html;
    }
}