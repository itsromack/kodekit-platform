<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<? $activities = object('com:activities.model.activities')->package($package)->name($name)->limit('10')->fetch() ?>

<h3><?= translate('Recent Activities')?></h3>
<div class="scrollable">
<? if(count($activities)) : ?>
    <?
    foreach ($activities as $activity) {
    	$list[substr($activity->created_on, 0, 10)][] = $activity;
    }
    ?>

    <div id="activities-list">
    	<? foreach($list as $date => $activities) : ?>
    		<h4><?= helper('date.humanize', array('date' => $date)) ?></h4>
    		<div class="activities">
    			<? foreach($activities as $activity) : ?>
    			<div class="activity">
    				<i class="icon-<?= $activity->action ?>"></i>
    				<div>
    					<div class="ellipsis">
    						<a href="<?= route('view=article&id='.$activity->row) ?>">
    						   <?= escape($activity->title) ?>
    						</a>
    					</div>
    					 <div class="ellipsis">
    					 	<small class="datetime">
    					 		<?= date("H:i", strtotime($activity->created_on)) ?> - <?= $activity->getAuthor()->getName() ?>
    					 	</small>
    					 </div>
    				</div>
    			</div>
    			<? endforeach ?>
    		</div>
    	<? endforeach ?>
    	<div class="button__group">
	    	<a class="button btn-block" href="<?= route('component=activities&view=activities&package='.$package) ?>">
	    	   <?= translate('More activities') ?>
	    	</a>
    	</div>
    </div>
<? endif ?>
</div>