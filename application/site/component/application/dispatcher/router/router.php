<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Application;

use Kodekit\Library;

/**
 * Router
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Application
 */
class DispatcherRouter extends Library\DispatcherRouter
{
    public function parse(Library\HttpUrlInterface $url)
    {
        // Get the path
        $path = trim($url->getPath(), '/');

        //Remove base path
        $path = substr_replace($path, '', 0, strlen($this->getObject('request')->getBaseUrl()->getPath()));

        // Set the format
        if(!empty($url->format)) {
            $url->query['format'] = $url->format;
        }

        //Set the route
        $url->path = trim($path , '/');

        return $this->_parseRoute($url);
    }

    public function build(Library\HttpUrlInterface $url)
    {
        $result = $this->_buildRoute($url);

        // Get the path data
        $route = $url->getPath();

        //Add the format to the uri
        if(isset($url->query['format']))
        {
            $format = $url->query['format'];

            if($format != 'html') {
                $url->format = $format;
            }

            unset($url->query['format']);
        }

        //Build the route
        $url->path = $this->getObject('request')->getBaseUrl()->getPath().'/'.$route;

        return $result;
    }

    protected function _parseRoute($url)
    {
        $this->_parseSiteRoute($url);
        $this->_parsePageRoute($url);
        $this->_parseComponentRoute($url);

        return true;
    }

    protected function _parseSiteRoute($url)
    {
        $route = $url->getPath();

        //Find the site
        $url->query['site'] = $this->getObject('application')->getSite();

        $route = preg_replace('/^'.preg_quote($url->query['site']).'/', '', $route);
        $url->path = ltrim($route, '/');

        return true;
    }

    protected function _parsePageRoute($url)
    {
        $route   = $url->getPath();
        $pages   = $this->getObject('pages');
        $reverse = array_reverse(iterator_to_array($pages));

        //Set the default
        $page = $pages->getDefault();

        //Find the page
        if(!empty($route))
        {
            //Need to reverse the array (highest sublevels first)
            foreach($reverse as $tmp)
            {
                $tmp     = $pages->getPage($tmp['id']);
                $length = strlen($tmp->route);

                if($length > 0 && strpos($route.'/', $tmp->route.'/') === 0)
                {
                    $page      = $tmp; //Set the page
                    $url->path = ltrim(substr($route, $length), '/');
                    break;
                }
            }
        }

        $url->setQuery($page->getLink()->query, true);
        $url->query['Itemid'] = $page->id;

        $pages->setActive($page->id);

        return true;
    }

    protected function _parseComponentRoute($url)
    {
        $route = $url->path;

        if(isset($url->query['component']) )
        {
            if(!empty($route))
            {
                //Get the router identifier
                $identifier = 'com:'.$url->query['component'].'.dispatcher.router';

                //Parse the view route
                $query = $this->getObject($identifier)->parse($url);

                //Prevent option and/or itemid from being override by the component router
                $query['component'] = $url->query['component'];
                $query['Itemid'] = $url->query['Itemid'];

                $url->setQuery($query, true);
            }
        }

        $url->path = '';

        return true;
    }

    protected function _buildRoute($url)
    {
        $segments = array();

        $view = $this->_buildComponentRoute($url);
        $page = $this->_buildPageRoute($url);
        $site = $this->_buildSiteRoute($url);

        $segments = array_merge($site, $page, $view);

        //Set the path
        $url->path = array_filter($segments);

        return true;
    }

    protected function _buildComponentRoute($url)
    {
        $segments = array();

        //Get the router identifier
        $identifier = 'com:'.$url->query['component'].'.router';

        //Build the view route
        if($this->getObject('manager')->getClass($identifier)) {
            $segments = $this->getObject($identifier)->build($url);
        }

        return $segments;
    }

    protected function _buildPageRoute($url)
    {
        $segments = array();

        //Find the page
        if(!isset($url->query['Itemid']))
        {
            $page = $this->getObject('pages')->getActive();
            $url->query['Itemid'] = $page->id;
        }

        $page = $this->getObject('pages')->getPage($url->query['Itemid']);

        //Set the page route in the url
        if(!$page->default)
        {
            if($page->getLink()->query['component'] == $url->query['component']) {
                $segments = explode('/', $page->route);
            }
        }

        unset($url->query['Itemid']);
        //unset($url->query['component']);

        return $segments;
    }

    protected function _buildSiteRoute($url)
    {
        $segments = array();

        $site = $this->getObject('application')->getSite();
        if($site != 'default' && $site != $this->getObject('application')->getRequest()->getUrl()->toString(Library\HttpUrl::HOST)) {
            $segments[] = $site;
        }

        return $segments;
    }
}
