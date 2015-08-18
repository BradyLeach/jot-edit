<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccount.php';
include_once $path . '/jot-edit/scripts/php/manager/MToken.php';

class WVerify extends CDiv {
    
   public function __construct($id,  $ticket, $token, $attributes=array('class'=>'tool_widget')) {
        parent::__construct($id, $attributes);
        
            //If attempting to access with out token redirect to 404 page
        if(!$token || !$ticket){ Redirect404(); return;}

            //If the token was valid then show success message, otherwise false
        if($this->Valid($ticket, $token)){
           $this->AddMessage('SUCCESS', 'Your account has been verrified you can now log in and edit your profile.');
           $this->AddChild(new CLink ('', array('class'=>'form_link'), "back to index", "/jot-edit/"  ));       

        }else{
            //error message
$this->AddMessage('FAIL', 'An error has occurred.');
           $this->AddChild(new CLink ('', array('class'=>'form_link'), "back to index", "/jot-edit/"  ));       
        } 
        
   }
   

    private function Valid( $ticket, $token){
        $vm = MToken::Get();  //Get the verification manager

            //Start the Validate token process
        if(!$vm->ValidateAccountToken($ticket, $token)){return(FALSE);}

            //Get the account associated with the token.
        $key = $vm->GetAccountTokenKey($ticket,$token);

            //If there is a account  
        if(!$key){ return(FALSE);}
        
            //Get the account manager 
        $am = MAccount::Get(); 

            //if everything updates correctly redirect to success page.
        if( $am->UpdateAccountStatus($key) && $vm->DestroyToken($ticket, $token)){
            return(TRUE);
        }
    }
    
     private function AddMessage($heading, $message){
        $this->AddChild( new CText('notify_heading', 'h2', array(), $heading));
        $this->AddChild( new CPara('notify_message', array(), $message));
    }   
    
}//End of class