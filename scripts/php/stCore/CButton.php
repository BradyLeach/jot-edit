<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CButton extends CText {
    
    
    function __construct($id, $attributes, $type, $value, $buttonText) {
        parent::__construct($id, "button", $attributes, $buttonText);
        
        if($type && $value) {
            $this->AddAttributes(array("value"=>$value, "type"=>$type ));
        }
    }
    
}//End of class

class CSubmitButton extends CButton {
    
    function __construct($id, $attributes, $value, $buttonText) {
        parent::__construct($id, $attributes, "submit", $value, $buttonText);
    }
}

class CResetButton extends CButton {
    
    function __construct($id, $attributes, $value, $buttonText) {
        parent::__construct($id, $attributes, "reset", $value, $buttonText);
    }
}