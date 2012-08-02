<?php
/**
 * @version		$Id: html.php 3541 2012-04-02 18:24:42Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Contacts Html View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsViewContactsHtml extends ComDefaultViewHtml
{
    /**
     * Display the view
     *
     * @return	string	The output of the view
     */
    public function display()
    {
        //Get the parameters
        $params = JFactory::getApplication()->getParams();

        //Get the category
        $category = $this->getCategory();

        //Get the parameters of the active menu item
        if ($page = JFactory::getApplication()->getMenu()->getActive())
        {
            $menu_params = new JParameter( $page->params );
            if (!$menu_params->get( 'page_title')) {
                $params->set('page_title',	$category->title);
            }
        }
        else $params->set('page_title',	$category->title);

        //Set the page title
        JFactory::getDocument()->setTitle( $params->get( 'page_title' ) );

        //Set the pathway
        if($page->query['view'] == 'categories' ) {
            JFactory::getApplication()->getPathway()->addItem($category->title, '');
        }

        //Set the breadcrumbs
        $this->assign('params',	  $params);
        $this->assign('category', $category);
        
        return parent::display();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getService('com://site/contacts.model.categories')
                         ->table('contacts')
                         ->id($this->getModel()->getState()->category)
                         ->getItem();

        //Set the category image
        if (isset( $category->image ) && !empty($category->image))
        {
            $path = JPATH_IMAGES.'/stories/'.$category->image;
            $size = getimagesize($path);

            $category->image = (object) array(
                'path'   => '/'.str_replace(JPATH_ROOT.DS, '', $path),
                'width'  => $size[0],
                'height' => $size[1]
            );
        }

        return $category;
    }
}