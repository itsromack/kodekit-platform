<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerHelperIso extends KObject
{
	/**
	 * Select
	 * @param $name
	 * @param $selected
	 * @return unknown_type
	 */
	public function country($name='country', $selected = '')
 	{
 		$countries = array();
 		$countries[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a Country' ) .' -' );
 		
 		$data = array();
 		$data['region'] = 'world';
 		
 		$model = KFactory::get('admin::com.beer.model.regions');
 		$model->getState()->setData($data);
   
 		$list = $model->getList();
			
 		foreach($list as $code => $country) {
 			$countries[] = KTemplate::loadHelper('select.option',  $code, $country);
 		}

 		return KTemplate::loadHelper('select.genericlist', $countries, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	}

 	public function states($region, $name = '', $selected = '' )
 	{
 		$region = strtolower($region);
  		$data = array();
 		$data['region'] = $region;
 		
 		$model = KFactory::get('admin::com.beer.model.regions');
 		$model->getState()->setData($data);
   
 		$list = $model->getList();

 		$states = array();
 		if(count($list))
 		{
 			$states[] = KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select a State/Provence' ).' -' );
	 		foreach($list as $code => $state) {
	 			$states[] = KTemplate::loadHelper('select.option',  $code, $state);
	 		}
	 		$disabled = '';
 		} else {
 			$states[] = KTemplate::loadHelper('select.option', '', '('.JText::_('No states for this country').')' );
 			$disabled = ' disabled="disabled"';
 		}



 		return KTemplate::loadHelper('select.genericlist', $states, $name, $disabled.' class="inputbox" size="1" ', 'value', 'text', $selected );
 	}
}