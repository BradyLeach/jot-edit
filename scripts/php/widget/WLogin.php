<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/form/FLogin.php';

class WLogin extends CDiv {
    
   public function __construct($id, $attributes=array('class'=>'tool_widget')) {
        parent::__construct($id, $attributes);
       
        $form = new FLogin();
        if($form->Processed()){Redirect('../../../' );}
        
        $form->AddAttribute('class', 'page_form');
        $this->AddChild($form);
        $this->AddChild(new CLink ("forgot-password", array('class'=>'form_link'), "forgotten password", "/jot-edit/action/password-recovery"  ));       
   }
   
}//End of class