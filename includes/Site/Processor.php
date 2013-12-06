<?php

class Site_ProcessorResult
{
    public $success;
    public $code;
    public $message;
    
    public function toArray()
    {
        return array('success' => $this->success, 'code' => $this->code,    
                     'message' => $this->message);
    }
}

abstract class Site_Processor
{
    protected $_name;
    protected $_config;
    protected $_resultCode;
    
    public function __construct($name)
    {
        $this->_name = $name;
        $this->_config = Site_Config::instance()->processors[$name];
    }
    
    /**
     * Must set _resultCode and return a boolean
     */
    abstract protected function _processInput($input);
        
    public function process($input)
    {
        $result = new Site_ProcessorResult();
        $result->success = $this->_processInput($input);
        $result->code = $this->_resultCode;
        $result->message = $this->_config['messages'][$result->code];
        
        return $result;
    }
}