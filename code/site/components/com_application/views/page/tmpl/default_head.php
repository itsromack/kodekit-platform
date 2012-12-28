<?
/**
 * @version     $Id: default.php 4558 2012-08-11 21:12:47Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<head>
<base href="<?= @service('request')->getUrl(); ?>" />
<title><?= @escape(@service('application')->getCfg('sitename' )). ' - ' .@text( 'Administration')  ?></title>
<meta content="text/html; charset=utf-8" http-equiv="content-type"  />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<ktml:meta />
<ktml:link />
<ktml:style />
<ktml:script />

<link href="media://com_application/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />

<style src="media://com_application/css/system.css"  />
<style src="media://com_application/css/general.css"  />
<style src="media://com_application/css/default.css" />
<style src="media://com_application/css/print.css" media="print" />



</head>