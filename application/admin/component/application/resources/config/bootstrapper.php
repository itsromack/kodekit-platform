<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

return array(

    'identifiers' => array(

        /*'dispatcher'  => array(
            'behaviors' => 'com:pages.dispatcher.behavior.accessible'
        ),*/

        'com:application.template.locator.component'  => array(
            'override_path' => APPLICATION_BASE.'/public/theme/default/templates/views'
        ),

        'com:application.template.filter.asset'         => array(
            'schemes' => array('assets://application/' => '/administrator/theme/default/')
        )
    )
);

