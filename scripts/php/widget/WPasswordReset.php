<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccount.php';
include_once $path . '/jot-edit/scripts/php/form/FResetPassword.php';
include_once $path . '/jot-edit/scripts/php/manager/MToken.php';

class WPasswordReset extends CDiv {
    
   public function __construct($id, $ticket, $token, $attributes=array('class'=>'tool_widget')) {
       parent::__construct($id, $attributes);
       
            //If attempting to access with out token redirect to 404 page
        if(!$token || !$ticket){ Redirect404(); die;}   
        
        $this->InitEditForms( $ticket, $token);
   }
   
   
    private function InitEditForms( $ticket, $token){

            //If the token was valid then show success message, otherwise false
        if($this->Valid($ticket, $token)){
            //access to the password reset is granted, so create the form
            $resetForm = new FResetPassword('reset_password', FALSE);
          
            if($resetForm->Processed()){
                $this->DestroyToken($ticket, $token);
                $this->AddMessage('SUCCESS', 'Your password has been reset');
                $this->AddChild(new CLink ('', array('class'=>'form_link'), "back to index", "/jot-edit/"  ));       
            }
            else{
                $this->AddChild($resetForm);
            }
        }else{
            //error message
             $this->AddMessage('FAIL', 'Your token in no longer valid');
        }            
    }
    
    
    private function AddMessage($heading, $message){
        $this->AddChild( new CText('notify_heading', 'h2', array(), $heading));
        $this->AddChild( new CPara('notify_message', array(), $message));
    }
    
    
    private function DestroyToken($ticket, $token){
        $vm = MToken::Get();  //Get the verification manager
        $vm->DestroyToken($ticket, $token );
    }
 
    
    
    private function Valid( $ticket, $token){
        
        $vm = MToken::Get();  //Get the verification manager

            //Start the Validate token process
        return($vm->ValidateToken($ticket, $token));
    }
    
    
}//End of class