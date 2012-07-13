<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * User Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersDatabaseRowUser extends KDatabaseRowDefault
{
    /**
     * Determines if passwords should be encrypted.
     *
     * @var bool True if encryption must be performed, false otherwise.
     */
    protected $_password_encryption;

    public function __construct(KConfig $config = null) 
    {
        if (!$config) {
            $config = new KConfig();
        }

        parent::__construct($config);

        $this->_password_encryption = $config->password_encryption;
    }

    protected function _initialize(KConfig $config) 
    {
        $config->append(array(
            'password_encryption' => true)
        );

        parent::_initialize($config);
    }

    public function setPasswordEncryption($value = true) 
    {
        $this->_password_encryption = (bool) $value;
    }

	public function __get($column)
    {
        //@TODO : Add mapped properties support
        if($column == 'gid') {
           $column = 'users_group_id';
        }

        if($column == 'params' && !($this->_data['params'] instanceof JParameter))
		{
			$xml_path	= JPATH_ADMINISTRATOR.'/components/com_users/databases/rows';
			$xml_name	= str_replace(' ', '_', strtolower($this->group_name));

			if(!file_exists($file = $xml_path.'/'.$xml_name.'.xml')) {
				$file = $xml_path.'/user.xml';
			}

			$params	= new JParameter($this->_data['params']);
			$params->loadSetupFile($file);

			$this->_data['params'] = $params;
		}

    	return parent::__get($column);
    }
    
    public function load()
    {
    	if ($result = parent::load()) {
    		// Negate enabled due to enabled => block column map.
    		$this->enabled = $this->enabled ? 0 : 1;
    	}
    	return $result;
    }
  
	public function save()
	{
		// Load the old row if editing an existing user.
		if(!$this->isNew())
		{
			$old_row = $this->getService('com://admin/users.database.table.users')
				->select($this->id, KDatabase::FETCH_ROW);
		}

        // Load component parameters.
        $params = JComponentHelper::getParams('com_users');

		$user = JFactory::getUser();
		
		// Validate received data.
		if(($this->isNew() || $this->isModified('name')) && trim($this->name) == '')
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_('Please enter a name!'));

			return false;
		}

		if(($this->isNew() || $this->isModified('username')) &&  trim($this->username) == '')
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_('Please enter a username!'));

			return false;
		}

		if(($this->isNew() || $this->isModified('username')) && preg_match('#[<>"\'%;()&]#i', $this->username) || strlen(utf8_decode($this->username)) < 2)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_('Please enter a valid username. No spaces, at least 2 characters '.
				'and must contain <strong>only</strong> letters and numbers.'));

			return false;
		}

	   if($this->isModified('username'))
       {
            $query = $this->getService('koowa:database.query.select')
                ->where('username = :username')
                ->where('id <> :id')
                ->bind(array('username' => $this->username, 'id' => (int) $this->id));

            $total = $this->getService('com://admin/users.database.table.users')->count($query);

            if($total)
            {
                $this->setStatus(KDatabase::STATUS_FAILED);
                $this->setStatusMessage(JText::_('This username is already in use.'));

                return false;
            }
        }

		if(($this->isNew() || $this->isModified('email')) && (trim($this->email) == '') || !($this->getService('koowa:filter.email')->validate($this->email)))
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_('Please enter a valid e-mail address.'));

			return false;
		}

		if($this->isModified('email'))
		{
			$query = $this->getService('koowa:database.query.select')
                ->where('email = :email')
                ->where('id <> :id')
                ->bind(array('email' => $this->email, 'id' => (int) $this->id));

			$total = $this->getService('com://admin/users.database.table.users')->count($query);

			if($total)
			{
				$this->setStatus(KDatabase::STATUS_FAILED);
				$this->setStatusMessage(JText::_('This e-mail address is already registered.'));

				return false;
			}
		}

		/*
		 * If username field is an email it has to be the same with email field.
		 * This removes the possibilitiy that a user can get locked out of her account
		 * if someone else uses that username as the email field.
		 */
		if ($this->getService('koowa:filter.email')->validate($this->username) === true && $this->username !== $this->email)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_('Your e-mail and username should match if you want to use an e-mail address as your username.'));

			return false;
		}

		// Don't allow users to block themselves.
		if($this->isModified('enabled') && !$this->isNew() && $user->id == $this->id && !$this->enabled)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_("You can't block yourself!"));

			return false;
		}

	    // Don't allow to save a user without a group.
        if(($this->isNew() || $this->isModified('users_group_id')) && !$this->users_group_id)
        {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::_("You can't create a user without a user group."));

            return false;
        }

		// Don't allow users below super administrator to edit a super administrator.
		if(!$this->isNew() && $this->isModified('users_group_id') && $old_row->users_group_id == 25 && $user->gid != 25)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_("You can't edit a super administrator account."));

			return false;
		}

		// Don't allow users below super administrator to create an administrators.
		if($this->isModified('users_group_id') && $this->users_group_id == 24 && !($user->gid == 25 || ($user->id == $this->id && $user->gid == 24)))
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_("You can't create a user with this user group level. "
				."Only super administrators have this ability."));

			return false;
		}

		// Don't allow users below super administrator to create a super administrator.
		if($this->isModified('users_group_id') && $this->users_group_id == 25 && $user->gid != 25)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_("You can't create a user with this user group level. "
				."Only super administrators have this ability."));

			return false;
		}

		// Don't allow users to change the user level of the last active super administrator.
		if(isset($this->_modifid['users_group_id']) && $old_row->users_group_id != 25)
		{
			$query = $this->getService('koowa:database.query.select')
                ->where('users_group_id = :users_group_id')
                ->where('enabled = :enabled')
                ->bind(array('users_group_id' => 25, 'enabled' => 1));

			$total = $this->getService('com://admin/users.database.table.users')->count($query);

			if($total <= 1)
			{
				$this->setStatus(KDatabase::STATUS_FAILED);
				$this->setStatusMessage(JText::_("You can't change this user's group because ".
					"the user is the only active super administrator for your site."));

				return false;
			}
		}

		// Check if passwords match.
		if($this->isModified('password') && $this->password !== $this->password_verify)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_("Passwords don't match!"));

			return false;
		}

		// Generate a random password if empty and the record is new.
		if($this->isNew() && !$this->password)
		{
			$this->password	        = $this->getService('com://admin/users.helper.password')->getRandom($params->get('min_passw_len'));
			$this->password_verify	= $this->password;
		}

		if($this->isModified('password') && $this->password)
		{
			if ($this->_password_encryption) {
                // Check the password length.
                $min_passw_len = $params->get('min_passw_len');
                if (strlen($this->password) < $min_passw_len) {
                    $this->setStatus(KDatabase::STATUS_FAILED);
                    $this->setStatusMessage(JText::sprintf('PASSWORD TOO SHORT', $min_passw_len));
                    return false;
                }
				$this->password	= $this->getService('com://admin/users.helper.password')->encrypt($this->password);
            }
		}
		else
		{
			$this->password = $old_row->password;

			unset($this->_modified['password']);
		}

		if($this->isNew()) {
			$this->registered_on = gmdate('Y-m-d H:i:s', time());
		}

		// TODO: This shouldn't be executed on every save.
		$query = $this->getService('koowa:database.query.select')
            ->columns('name')
            ->where('id = :id')
            ->bind(array('id' => $this->users_group_id));

		$this->group_name = $this->getService('com://admin/users.database.table.groups')
            ->select($query, KDatabase::FETCH_FIELD);

		// Set parameters.
		if($this->isModified('params'))
		{
			$params	= new JParameter('');
			$params->bind($this->_data['params']);

			$this->params = $params->toString();

			if(!$this->isNew() && $this->_data['params'] == $old_row->params->toString()) {
				unset($this->_modified['params']);
			}
		}

		// Need to reverse the value of 'enabled', because the mapped column is 'block'.
		if($this->isNew() || $this->isModified('enabled')) {
			$this->enabled = $this->enabled ? 0 : 1;
		}
		
		// Get modified columns for further use.
		$modified = $this->getModified();

		if(!parent::save()) {
			return false;
		}

		// Syncronize ACL.
		if($this->getStatus() == KDatabase::STATUS_CREATED)
		{
            $aro = $this->getService('com://admin/groups.database.row.aro')
                ->setData(array(
            		'section_value' => 'users',
            		'value' => $this->id,
            		'name' => $this->name
                ));
            // Load an existing row if any.
            $aro->load();
            $aro->save();
            
            $arosgroup = $this->getService('com://admin/groups.database.row.arosgroup')
                ->setData(array(
                    'group_id' => $this->users_group_id,
                    'aro_id'   => $aro->id
                ));
            // Load an existing row if any.
            $arosgroup->load();
            $arosgroup->save();
		}
		else
		{
            if(array_search('name', $modified) !== false || array_search('users_group_id', $modified) !== false) 
            {
                $aro = $this->getService('com://admin/groups.database.table.aros')
                    ->select(array('value' => $this->id), KDatabase::FETCH_ROW);

                if(array_search('name', $modified) !== false)
                {
                    $aro->name = $this->name;
                    $aro->save();
                }

                if(array_search('users_group_id', $modified) !== false) 
                {
                    $this->getService('com://admin/groups.database.table.arosgroups')
                        ->select(array('aro_id' => $aro->id), KDatabase::FETCH_ROW)
                        ->delete();
                    
                    $this->getService('com://admin/groups.database.table.arosgroups')
                         ->select(null, KDatabase::FETCH_ROW)
                         ->setData(array(
                            'group_id' => $this->users_group_id,
                            'aro_id'   => $aro->id
                         ))->save();
                }
            }
		}

		return true;
	}

	public function delete()
	{
		$user = JFactory::getUser();

		// Don't allow users to delete themselves.
		if($user->id == $this->id)
		{
			$this->_status			= KDatabase::STATUS_FAILED;
			$this->_status_message	= JText::_("You can't delete yourself!");

			return false;
		}

		// Don't allow administrators to delete other administrators or super administrators.
		if($user->gid == 24 && ($this->users_group_id == 24 || $this->users_group_id == 25))
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage(JText::_("You can't delete a user with this user group level. "
				."Only super administrators have this ability."));

			return false;
		}

		if(!parent::delete()) {
			return false;
		}

        // Syncronize ACL.
		if($this->_status == KDatabase::STATUS_DELETED)
		{
            $aro = $this->getService('com://admin/groups.database.table.aros')
                         ->select(array('value' => $this->id), KDatabase::FETCH_ROW);
            $aro->delete();
         
            $this->getService('com://admin/groups.database.table.arosgroups')
                 ->select(array('aro_id' => $aro->id), KDatabase::FETCH_ROW)
                 ->delete();
		}

		return true;
	}

    /**
     * Check user permissions
     *
     * @param	string	$acoSection	The ACO section value
     * @param	string	$aco		The ACO value
     * @param	string	$axoSection	The AXO section value	[optional]
     * @param	string	$axo		The AXO value			[optional]
     * @return	boolean	True if authorize
     *
     * @deprecated since 12.3, will be removed from 13.2
     */
    public function authorize( $acoSection, $aco, $axoSection = null, $axo = null )
    {
        $value	= $this->group_name;

        return JFactory::getACL()->acl_check( $acoSection, $aco,	'users', $value, $axoSection, $axo );
    }
	
	/**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        
        //unset($data['password']);
        unset($data['activation']);
        
        $data['params'] = $this->params->toArray();
        return $data;
    }

    /**
     * Method to get a parameter value
     *
     * Provided for compatibility with JUser
     *
     * @param 	string 	$key 		Parameter key
     * @param 	mixed	$default	Parameter default value
     * @return	mixed				The value or the default if it did not exist
     *
     * @deprecated since 12.3, will be removed from 13.2
     */
    public function getParam( $key, $default = null )
    {
        return $this->params->get( $key, $default );
    }

    /**
     * Method to set a parameter
     *
     * Provided for compatibility with JUser
     *
     * @param 	string 	$key 	Parameter key
     * @param 	mixed	$value	Parameter value
     * @return	mixed			Set parameter value
     *
     * @deprecated since 12.3, will be removed from 13.2
     */
    function setParam( $key, $value )
    {
        return $this->_params->set( $key, $value );
    }

    /**
     * Method to set a default parameter if it does not exist
     *
     * Provided for compatibility with JUser
     *
     * @param 	string 	$key 	Parameter key
     * @param 	mixed	$value	Parameter value
     * @return	mixed			Set parameter value
     *
     * @deprecated since 12.3, will be removed from 13.2
     */
    function defParam( $key, $value )
    {
        return $this->_params->def( $key, $value );
    }
}