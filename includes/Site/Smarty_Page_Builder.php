<?php

require_once 'Site/Smarty_Builder.php';
require_once 'Site/Smarty_Page.php';

/**
 * Build Smarty instances representing pages of a site
 *
 * Configuration is read from the 'host' properties of Site_Config.  The '
 * host' configuration must contain the 'web_base' and 'web_domain' fields.
 *  
 * @package Site
 * @author Brian Rutledge
 */
class Smarty_Page_Builder extends Smarty_Builder
{
    /**
     * Creates a Smarty instance
     * @return mixed  a new Smarty instance
     */
    protected function create()
    {
        return new Smarty_Page();        
    }
    
    /**
     * Configures a Smarty instance
     *
     * Assigns 'base' variable to the template
     *
     * @param mixed $smarty  a Smarty instance
     */
    protected function setup(&$smarty)
    {
        parent::setup($smarty);

        $smarty->assign('base', 
                        'http://' . $this->_host_config['web_domain'] . 
                                    $this->_host_config['web_base']);
        
        if (isset($_GET['modal'])) {
            $smarty->assign('modal', true);
        }
    }
}