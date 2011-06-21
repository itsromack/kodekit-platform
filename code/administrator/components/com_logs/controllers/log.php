<?php
/** $Id$ */

class ComLogsControllerLog extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->_request->package = $config->package;
        
        if ($this->isDispatched() && $config->package) {
            $this->_request->layout = 'package_list';
            
            // Inherit the views from the calling component's view
            $view = clone $this->getView()->getIdentifier();
            $view->package = $config->package;
            
            $this->getView()->views = KFactory::get($view)->views;
        }
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'package' => null,
        ));
        
        parent::_initialize($config);
        
        $config->view = 'admin::com.logs.view.logs.html';
        $config->model = 'admin::com.logs.model.logs';
        $config->toolbar = 'admin::com.logs.controller.toolbar.logs';
    }
    
    public function getToolbar()
    {
        $toolbar = parent::getToolbar();
        
        if ($this->_request->package && $this->isDispatched()) {
            $toolbar->setTitle(ucfirst($this->_request->package).' Logs');
        }
        
        return $toolbar;
    }
}