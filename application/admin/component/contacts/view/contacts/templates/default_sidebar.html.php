<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<h3><?= translate('Categories') ?></h3>

<?= @template('com:categories.view.categories.list.html', array('categories' => object('com:contacts.model.categories')->sort('title')->table('contacts')->getRowset())); ?>