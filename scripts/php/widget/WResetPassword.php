<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/form/FResetPassword.php';

class WResetPassword extends CDiv {
    
   public function __construct($id, $attributes=array('class'=>'tool_widget')) {
       parent::__construct($id, $attributes);
       
        $this->InitEditForms();
   }
   
   

    private function InitEditForms( ){

        //If the token was valid then show success message, otherwise false

        //access to the password reset is granted, so create the form
        $resetForm = new FResetPassword();

        if($resetForm->Processed()){
            $this->AddMessage('SUCCESS', 'Your password has been reset');
            $this->AddChild(new CLink ('', array('class'=>'form_link'), "return to tools", "/jot-edit/action/account/password"  ));       
        }
        else{
            $this->AddChild($resetForm);
        }                      
    }
    
    
    private function AddMessage($heading, $message){
        $this->AddChild( new CText('notify_heading', 'h2', array(), $heading));
        $this->AddChild( new CPara('notify_message', array(), $message));
    }
   
}