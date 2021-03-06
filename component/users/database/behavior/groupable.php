<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * Groupable Database Behavior
 *
 * Takes care of creating N:N relationships given an item value and a collection of values.
 *
 * @author  Arunas Mazeika <http://github.Com/arunasmazeika>
 * @package Kodekit\Component\Users
 */
class DatabaseBehaviorGroupable extends Library\DatabaseBehaviorAbstract
{
    /**
     * The groups
     *
     * @param UsersEntityGroups
     */
    private $__groups;

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'table'   => 'users_groups_users',
            'columns' => array(
                'collection' => 'users_group_id',
                'item'       => 'users_user_id'),
            'values'  => 'groups'
        ));

        parent::_initialize($config);
    }

    /**
     * Returns the groups the user is part of
     *
     * @return array An array of group identifiers
     */
    public function getGroups()
    {
        if (!$this->isNew() && !isset($this->__groups))
        {
            $this->__groups = $this->getObject('com:users.model.groups')
                ->user($this->id)
                ->fetch();
        }

        return $this->__groups;
    }

    protected function _afterUpdate(Library\DatabaseContextInterface $context)
    {
        // Same as insert.
        $this->_afterInsert($context);
    }

    protected function _afterInsert(Library\DatabaseContextInterface $context)
    {
        $data   = $context->data;
        $config = $this->getConfig();

        if (isset($data->{$config->values}) && $data->getStatus() != Library\Database::STATUS_FAILED)
        {
            if ($groups = $data->{$config->values}) {
                $this->_insertGroups($groups, $context);
            }

            $this->_cleanupGroups($groups, $context);

            // Unset data for avoiding un-necessary queries on subsequent saves.
            unset($data->{$config->values});
        }
    }

    protected function _insertGroups($groups, Library\DatabaseContextInterface $context)
    {
        $groups = (array) $groups;

        $config = $this->getConfig();

        $query = $this->getObject('lib:database.query.insert')
                      ->table($config->table)
                      ->columns(array($config->columns->item, $config->columns->collection));

        foreach ($groups as $group) {
            $query->values(array($this->_getItemValue($context), $group));
        }

        // Just ignore duplicate entries.
        $query = str_replace('INSERT', 'INSERT IGNORE', (string) $query);

        $context->subject->getDriver()->execute($query);
    }

    protected function _cleanupGroups($groups, Library\DatabaseContextInterface $context)
    {
        $groups = (array) $groups;

        $config = $this->getConfig();
        $driver = $context->getSubject()->getDriver();

        $query = $this->getObject('lib:database.query.select')
                      ->table($config->table)
                      ->columns(array($config->columns->collection))
                      ->where("{$config->columns->item} = :item")
                      ->bind(array('item' => $this->_getItemValue($context)));

        $current = $driver->select($query, Library\Database::FETCH_FIELD_LIST);
        $remove  = array_diff($current, $groups);

        if (count($remove))
        {
            $query = $this->getObject('lib:database.query.delete')
                           ->table($config->table)
                           ->where("{$config->columns->collection} IN :groups")
                           ->where("{$config->columns->item} = :item")
                           ->bind(array('groups' => $remove, 'item' => $this->_getItemValue($context)));

            $driver->execute((string) $query);
        }
    }

    protected function _getItemValue(Library\DatabaseContextInterface $context)
    {
        return $context->data->id;
    }
}