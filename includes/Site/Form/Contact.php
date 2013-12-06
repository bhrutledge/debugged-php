<?php

require_once 'Site/Form.php';

class Site_Form_Contact extends Site_Form
{
    // TODO: Make these public?
    const SUBMIT_ID = 'submit';
    const NAME_ID = 'name';
    const EMAIL_ID = 'email';
    const SUBJECT_ID = 'subject';
    const MESSAGE_ID = 'message';

    protected function _addElements()
    {
        $this->_addSubmitElement(self::SUBMIT_ID);
        $this->addElement('header');
        
        $textIds = array(self::NAME_ID, self::EMAIL_ID, self::SUBJECT_ID);
        foreach ($textIds as $textId) {
            $this->_addTextElement($textId);
        }
            
        $this->addElement('textarea', self::MESSAGE_ID, 
            $this->_config[self::MESSAGE_ID]['label'], 
            array('id' => self::MESSAGE_ID, 'rows' => 10, 'cols' => 60));
    }
    
    protected function _addRules()
    {
        $this->_addRequiredRules(array(self::NAME_ID, self::EMAIL_ID, 
                                       self::SUBJECT_ID, self::MESSAGE_ID));

        $this->addRule(self::EMAIL_ID, 
            $this->_config[self::EMAIL_ID]['invalid'], 'email');
    }
}