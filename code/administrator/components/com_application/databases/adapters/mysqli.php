<?php
/**
 * @version     $Id: mysqli.php 4714 2012-07-13 01:32:32Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default Database MySQLi Adapter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComApplicationDatabaseAdapterMysqli extends KDatabaseAdapterMysqli implements KServiceInstantiatable
{
    /**
	 * The cache object
	 *
	 * @var	JCache
	 */
    protected $_cache;

	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		if(JFactory::getConfig()->getValue('config.caching')) {
	        $this->_cache = JFactory::getCache('database', 'output');
		}

        //Auto connect to the database
        $this->connect();
	}

	/**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return KDatabaseTableInterface
     */
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        if (!$container->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }

        return $container->get($config->service_identifier);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $application = $this->getService('application');

        $config->append(array(
            'options'	=> array(
                'host'		   => $application->getCfg('host'),
                'username'	   => $application->getCfg('user'),
                'password'     => $application->getCfg('password'),
                'database'	   => $application->getCfg('db'),
            ),
            'table_prefix' => $application->getCfg('dbprefix')
        ));

        parent::_initialize($config);
    }

	/**
	 * Retrieves the table schema information about the given table
	 *
	 * This function try to get the table schema from the cache. If it cannot be found
	 * the table schema will be retrieved from the database and stored in the cache.
	 *
	 * @param 	string 	A table name or a list of table names
	 * @return	KDatabaseSchemaTable
	 */
	public function getTableSchema($table)
	{
	    if(!isset($this->_table_schema[$table]) && isset($this->_cache))
		{
		    $database = $this->getDatabase();

		    $identifier = md5($database.$table);

	        if (!$schema = $this->_cache->get($identifier))
	        {
	            $schema = parent::getTableSchema($table);

	            //Store the object in the cache
		   	    $this->_cache->store(serialize($schema), $identifier);
	        }
	        else $schema = unserialize($schema);

		    $this->_table_schema[$table] = $schema;
	    }

	    return parent::getTableSchema($table);
	}
}