<?php

require_once 'Site/Form.php';

class Site_Form_Login extends Site_Form
{
    const SUBMIT_ID = 'submit';
    const USERNAME_ID = 'username';
    const PASSWORD_ID = 'password';

    protected function _addElements()
    {
        $this->_addSubmitElement(self::SUBMIT_ID);
        $this->addElement('header');
        
        $this->_addTextElement(self::USERNAME_ID);
        $this->_addTextElement(self::PASSWORD_ID, 'password');
    }
    
    protected function _addRules()
    {
        $this->_addRequiredRules(array(self::USERNAME_ID, self::PASSWORD_ID)); 
    }
}