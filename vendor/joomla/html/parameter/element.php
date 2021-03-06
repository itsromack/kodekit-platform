<?php
/**
* @version		$Id: element.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla.Framework
* @subpackage	Parameter
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Parameter base class
 *
 * The JElement is the base class for all JElement types
 *
 * @abstract
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 */
class JElement extends JObject
{
	/**
	* element name
	*
	* This has to be set in the final
	* renderer classes.
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = null;

	/**
	* reference to the object that instantiated the element
	*
	* @access	protected
	* @var		object
	*/
	var	$_parent = null;

	/**
	 * Constructor
	 *
	 * @access protected
	 */
	function __construct($parent = null)
    {
		$this->_parent = $parent;
	}

	/**
	* get the element name
	*
	* @access	public
	* @return	string	type of the parameter
	*/
	function getName()
    {
		return $this->_name;
	}

	function render($param, $value, $group = 'params')
	{
		$name	= (string) $param->attributes()->name;
		$label	= (string) $param->attributes()->label;
		$descr	= (string) $param->attributes()->description;

		//make sure we have a valid label
		$label = $label ? $label : $name;
		$result[0] = $this->fetchTooltip($label, $descr, $param, $group, $name);
		$result[1] = $this->fetchElement($name, $value, $param, $group);
		$result[2] = $descr;
		$result[3] = $label;
		$result[4] = $value;
		$result[5] = $name;

		return $result;
	}

	function fetchTooltip($label, $description, $param, $group='', $name='')
	{
		$output = '<label for="'.$group.'['.$name.']'.'" class="control-label">'. $label .'</label>';
		return $output;
	}

	function fetchElement($name, $value, $param, $group)
    {
		return;
	}
}
