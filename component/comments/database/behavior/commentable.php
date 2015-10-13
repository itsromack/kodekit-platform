<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Comments;

use Nooku\Library;

/**
 * Commentable Controller Behavior
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Nooku\Component\Comments
 */
class DatabaseBehaviorCommentable extends Library\DatabaseBehaviorAbstract
{
    /**
	 * Get a list of comments
	 *
	 * @return Library\ModelEntityInterface
	 */
	public function getComments()
	{
		$comments = $this->getObject('com:comments.model.comments')
					->row($this->id)
					->table($this->getTable()->getName())
					->fetch();

		return $comments;
	}
}