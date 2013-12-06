<?php

require_once 'Site/Processor.php';
require_once 'Site/Methods.php';

class Site_Processor_Contact extends Site_Processor
{
    /**
     * Requires 'name', 'email', 'subject', and 'message' inputs
     * TODO: Use ID's from Site_Form_Contact
     */
    protected function _processInput($input)
    {
        $from = $input['name'] . ' <' . $input['email'] . '>';
        $result = 
            Site_Methods::mail($input['subject'], $input['message'], $from);
            
        $this->_resultCode = $result ? 'success' : 'error';
        return $result;
    }
}