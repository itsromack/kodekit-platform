<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Languages Model Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerModelLanguages extends ComExtensionsModelLanguages
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $filter = 'com://admin/installer.filter.application';
        $this->_state->insert('application', $filter, array('site', 'administrator'));        
    }
}