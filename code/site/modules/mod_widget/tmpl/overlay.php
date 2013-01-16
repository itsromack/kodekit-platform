<?
/**
 * @version     $Id: overlay.php 1481 2012-02-10 01:46:24Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Default
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @overlay(array('url' => @route($url), 'options' => array('selector' => $module->params->get('selector', 'body')))); ?>