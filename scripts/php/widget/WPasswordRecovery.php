<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/form/FRecoverPassword.php';

class WPasswordRecovery extends CDiv {
    
   public function __construct($id, $attributes=array('class'=>'tool_widget')) {
       parent::__construct($id, $attributes);
       
       $form = new FRecoverPassword();
        if($form->Processed()){
            $this->AddMessage('SUCCESS', 'An email has been sent with instruction on how to reset your password.');
        }
        else{
            $this->AddChild($form);
        }
    }
   
    private function AddMessage($heading, $message){
        $this->AddChild( new CText('notify_heading', 'h2', array(), $heading));
        $this->AddChild( new CPara('notify_message', array(), $message));
    }
    
   
}