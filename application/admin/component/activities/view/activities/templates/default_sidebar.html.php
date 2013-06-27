<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<?= @helper('behavior.validator') ?>

<script inline>
window.addEvent('domready', function(){
	/* Reset the filter values to blank */
	document.id('activities-filter').addEvent('reset', function(e){
		e.target.getElements('input').each(function(el){
			if(['days_back','start_date', 'user'].contains(el.name)){
				el.value = '';
			}
		});
		e.target.submit();
	});
});
</script>


<h3><?=@text( 'Extensions' )?></h3>
<ul class="navigation">
    <a class="<?= empty($state->package) ? 'active' : ''; ?>" href="<?= @route('package=') ?>">
    <?= @text('All extensions')?>
    </a>
    <?php foreach ($packages as $package): ?>
    <a <?= $package->id == $state->package ? 'class="active"' : '' ?> href="<?=@route('package='.$package->id)?>"><?=ucfirst($package->package)?></a>
    <?php endforeach ?>
</ul>

<form action="" method="get" id="activities-filter">
    <fieldset>
        <legend><?=@text( 'Filters' )?></legend>
        <div class="input-prepend">
            <span class="add-on">Start</span>
            <input type="date" name="start_date" value="<?= $state->start_date ?>" />
        </div>
        <div class="input-prepend">
            <span class="add-on">Days back</span>
            <input type="text" name="days_back" value="<?=($state->days_back) ? $state->days_back : '' ?>" />
        </div>
        <div class="input-prepend">
            <span class="add-on">User</span>
            <?= @helper('com:users.listbox.users',
                array(
                    'autocomplete' => true,
                    'name'		   => 'user',
                    'validate'     => false,
                    'attribs'      => array('size' => null),
                )) ?>
        </div>
        <div class="btn-group">
            <input type="submit" name="submitfilter" class="btn" value="<?=@text('Filter')?>" />
            <input type="reset" name="cancelfilter" class="btn" value="<?=@text('Reset')?>" />
        </div>
    </fieldset>
</form>
