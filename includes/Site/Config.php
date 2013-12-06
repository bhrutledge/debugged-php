<?php

require_once 'Site/sfYaml.php';

/**
 * Singleton YAML configuration loader
 *
 * Config files are accessible as array properties.  For example, a file 
 * called 'host.yml' can be accessed with Site_Config::instance()->host.
 * This assumes SITE_ROOT_DIR has been defined, and that config files live in 
 * SITE_ROOT_DIR/config. 
 *
 * @package Site
 * @author Brian Rutledge <brian@debugged.org>
 */
class Site_Config
{ 
    /**
     * Configuration storage
     * @var array
     */
    private $_store = array();
 
    /**
     * Singleton instance
     * @var mixed
     */
    static private $_instance = null;
 
    /**
     * Singleton constructor
     * @return void
     */
    private function __construct()
    {}
 
    /**
     * Retrieves singleton instance
     * @return mixed  the singleton instance
     */
    static public function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Site_Config;
        }
        
        return self::$_instance;
    }
 
    /**
     * Retrieves a YAML configuration
     * @param string $label  the name of the configuration
     * @return array  the loaded configuration (empty if not found)
     */
    public function __get($label)
    {
        if (!isset($this->_store[$label])) {
            $this->_store[$label] = 
                sfYaml::load(SITE_ROOT_DIR . "/config/$label.yml");
        }
        
        return $this->_store[$label];
    }
}

