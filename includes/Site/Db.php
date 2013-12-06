<?php

require_once 'Site/Config.php';

/**
 * Singleton database handle loader
 *
 *
 * @package Site
 * @author Brian Rutledge <brian@debugged.org>
 */
class Site_Db
{ 
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
            $config = Site_Config::instance()->host['pdo'];

            $options = array();
            if (isset($config['options']) and is_array($config['options'])) {
                $options = $config['options'];
            }

            self::$_instance = new PDO($config['dsn'], $config['username'], 
                                       $config['password'], $options);
            self::$_instance->setAttribute(PDO::ATTR_ERRMODE, 
                                           PDO::ERRMODE_EXCEPTION);
        }
        
        return self::$_instance;
    }
}

