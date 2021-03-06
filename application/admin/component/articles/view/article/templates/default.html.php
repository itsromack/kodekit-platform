<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>
<?= helper('behavior.keepalive') ?>
<?= helper('behavior.validator') ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<? if($article->isTranslatable()) : ?>
    <ktml:block extend="actionbar">
        <?= helper('com:languages.listbox.languages', array('url' => route())) ?>
    </ktml:block>
<? endif ?>

<form action="" method="post" id="article-form" class="-koowa-form">
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />

    <div class="main">
        <div class="title">
            <input class="required" type="text" name="title" maxlength="255" value="<?= $article->title ?>" placeholder="<?= translate('Title') ?>" />
            <div class="slug">
                <span class="add-on"><?= translate('Slug') ?></span>
                <input type="text" name="slug" maxlength="255" value="<?= $article->slug ?>" />
            </div>
        </div>
        <?= object('com:ckeditor.controller.editor')->render(array('name' => 'text', 'text' => $article->text)) ?>
    </div>
    <div class="sidebar no--scrollbar">
        <?= import('default_sidebar.html'); ?>
    </div>
</form>
