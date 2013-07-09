<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>
<article <?= !$article->published ? 'class="article-unpublished"' : '' ?>>
    <div class="page-header">
	    <? if (@service('component')->canEdit()) : ?>
	    <a style="float: right;" class="btn" href="<?= @helper('route.article', array('row' => $article, 'layout' => 'form')) ?>">
	        <i class="icon-edit"></i>
	    </a>
	    <? endif; ?>
	    <h1><?= $article->title ?></h1>
	    <?= @helper('date.timestamp', array('row' => $article, 'show_modify_date' => false)); ?>
	    <? if (!$article->published) : ?>
	    <span class="label label-info"><?= @text('Unpublished') ?></span>
	    <? endif ?>
	    <? if ($article->access) : ?>
	    <span class="label label-important"><?= @text('Registered') ?></span>
	    <? endif ?>
	</div>

    <?= @helper('com:attachments.image.thumbnail', array('row' => $article)) ?>

    <? if($article->fulltext) : ?>
    <div class="article__introtext">
        <?= $article->introtext ?>
    </div>
    <? else : ?>
    <?= $article->introtext ?>
    <? endif ?>

    <?= $article->fulltext ?>
    
    <?= @template('com:tags.view.tags.default.html') ?>
    <?= @template('com:attachments.view.attachments.default.html', array('attachments' => $attachments, 'exclude' => array($article->attachments_attachment_id))) ?>
</article>