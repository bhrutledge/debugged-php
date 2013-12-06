<?php

require_once 'HTML/QuickForm.php';
require_once 'Site/Config.php';

class Site_Form extends HTML_QuickForm
{
    protected $_config;
    
    protected function _addTextElement($textId, $textType='text',
                                       $size=30, $maxLength=50)
    {
        $this->addElement($textType, $textId, 
                          $this->_config[$textId]['label'], 
                          array('id' => $textId, 'size' => $size, 
                                'maxlength' => $maxLength));
    }
    
    protected function _addRadioElement($groupId, $optionIds, $defaultId)
    {
        $options = array();

        foreach ($optionIds as $optionId) {
            $options[] =& $this->createElement('radio', null, null,
                $this->_config[$optionId]['label'], 
                $this->_config[$optionId]['value'],
                array('id' => $optionId));
        }

        $this->addGroup($options, $groupId, 
                        $this->_config[$groupId]['label']);

        $this->setDefaults(array($groupId => 
                                 $this->_config[$defaultId]['value']));
    }
    
    protected function _addSubmitElement($submitId)
    {
        $this->addElement('submit', $submitId,
            $this->_config[$submitId]['label']);
    }
    
    protected function _addRequiredRules($requiredIds)
    {
        foreach ($requiredIds as $requiredId) {      
            $this->addRule($requiredId, 
                $this->_config[$requiredId]['missing'], 'required');
        }
    }
    
    protected function _addElements()
    {
    }
    
    protected function _addRules()
    {
    }

    public function __construct($formName='', $method='post', $action='', 
                                $target='', $attributes=null, 
                                $trackSubmit=false)
    {
        parent::__construct($formName, $method, $action, $target,
                            $attributes, $trackSubmit);
                            
        $this->_config = Site_Config::instance()->forms[$formName];        
        $this->setRequiredNote(null);
        $this->removeAttribute('name');

        $this->_addElements();
        $this->_addRules();
    }
}