<?php

/**
 * Build Smarty instances for a site
 *
 * Configuration is read from the 'host' property of Site_Config.  The only
 * required field is 'smarty_dir'.  The individual Smarty directories (eg, 
 * template_dir) are optional, but must be prefixed with 'smarty_' (eg, 
 * 'smarty_template_dir').
 *
 * @package Site
 * @author Brian Rutledge <brian@debugged.org>
 */
class Smarty_Builder
{
    /**
     * Directories used by Smarty, with default values
     * @var array
     */
    private static $_smarty_dirs = array('template_dir' => 'templates',
                                         'config_dir' => 'configs',
                                         'compile_dir' => 'templates_c',
                                         'cache_dir' => 'cache');

    /**
    * A cache of the host configuration
    * @var array
    */
    protected $_host_config = array();

    /**
     * Constructor
     * @return void
     */
    public function __construct()
    {
        require_once 'Site/Config.php';
        $this->_host_config = Site_Config::instance()->host;
    }

    /**
     * Creates a Smarty instance
     * @return mixed  a new Smarty instance
     */
    protected function create()
    {
        require_once 'Smarty/Smarty.class.php';
        return new Smarty();        
    }
    
    /**
     * Configures a Smarty instance
     * @param mixed $smarty  a Smarty instance
     */
    protected function setup(&$smarty)
    {
        $smarty_dir = $this->_host_config['smarty_dir'];
        
        foreach (self::$_smarty_dirs as $dir => $default_value) {
            $config_var = "smarty_$dir";
            $config_value = "";
            
            if (isset($this->_host_config[$config_var])) {
                $config_value = $this->_host_config[$config_var];
            }
            else {
                $config_value = "$smarty_dir/$default_value";        
            }
            
            $smarty->$dir = $config_value;
        }
        
        $plugins_dir = array($smarty_dir . DS . 'plugins',
                             dirname(__FILE__) . DS . 'plugins');
        
        $smarty->plugins_dir =
            array_merge($plugins_dir, $smarty->plugins_dir);
    }
    
    /**
     * Creates and configures a Smarty instance
     * @return mixed  a new Smarty instance, configured for the site
     */
    public function build()
    {
        $smarty = $this->create();
        $this->setup($smarty);
        
        return $smarty;
    }
}

