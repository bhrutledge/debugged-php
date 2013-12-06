<?php

/**
 * Cache web pages
 *
 * @package Site
 * @author Brian Rutledge <brian@debugged.org>
 */
class Site_Cache
{        
    protected $_lifetime = 0;
    
    protected $_filename = null;

    public function __construct()
    {
        require_once 'Site/Config.php';
        $config = Site_Config::instance()->host;
        
        if (isset($config['cache_lifetime'])) {
            $this->_lifetime = $config['cache_lifetime'];
        }
        
        if (isset($config['cache_dir'])) {
            $cache_filename  = basename($_SERVER['SCRIPT_NAME']);
            if ($_SERVER['QUERY_STRING'] != '') {
                $cache_filename .= '_' . 
                                   base64_encode($_SERVER['QUERY_STRING']);
            }
        
            $this->_filename = $config['cache_dir'] . '/' . $cache_filename;
        }       
    }
    
    public function exists()
    {
        return (file_exists($this->_filename) and 
                (time() - $this->_lifetime < filemtime($this->_filename)));
    }
    
    public function fetch($timestamp=true)
    {
        if (!$this->exists()) {
            return '';
        }
        
        $contents = file_get_contents($this->_filename);
        if ($timestamp) {
            $contents .= "<!-- Cached " . date('r', filemtime($this->_filename)) . " -->";
        }

        return $contents;
    }
    
    public function display($timestamp=true)
    {
        $output = $this->fetch($timestamp);
        if ($output) {
            echo $output;
        }
        
        return $output != '';
    }
    
    public function write($contents)
    {
        if (!($this->_lifetime and $this->_filename)) {
            return true;            
        }
        
        $fp = fopen($this->_filename, 'w');
        if (!$fp) {
            return false;
        }
        
        $result = fwrite($fp, $contents);
        fclose($fp);

        return $result;
    }
}
