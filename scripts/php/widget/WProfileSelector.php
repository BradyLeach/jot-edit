<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . "/jot-edit/scripts/php/stCore/stCore.php";
include_once $path . "/jot-edit/scripts/php/page/PCallout.php";
//include_once $path . "/jot-edit/scripts/php/form/FLogin.php";

class WProfileSelector extends CDiv {
    
    private $m_form;
    
    public function __construct($id='profileSelector', $attributes=array('class'=>'widget'), $open=FALSE) {
        parent::__construct($id.'_widget', $attributes);
        
        $this->InitWidgetButton();
        $this->InitWidgetCallOut($open);       
    }
   
    private function InitWidgetButton(){
            //Create the login link switch        
        $this->AddChild(new CLink('profileSelector', array('class'=>'pictureList'), 'profile', '/jot-edit/action/profile'));
    }
    
    
    private function InitWidgetCallOut($open){
            //Create the call out box
        $loginBox = new PCallout('profile_select_callout');
        
        if($open){
            $loginBox->AddAttribute('style', 'display:block');
        }
            //Add the form and password recovery link to the callout box.
        
        
        $loginBox->AddChild(new CLink ("forgot-password", array('class'=>'form_link'), "forgotten password", "/jot-edit/action/password-recovery"  ));       
 
            //Add the Call out box and its content to the widget
        $this->AddChild($loginBox);
    }
   
    public function Processed(){
        return($this->m_form->Processed());
    }
   
    public function Locked(){
        return($this->m_form->Locked());
    }
   
   
    
}