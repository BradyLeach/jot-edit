<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/form/FName.php';

class WName extends CDiv {
    
   public function __construct($id, $attributes=array('class'=>'tool_widget')) {
       parent::__construct($id, $attributes);
       
        $this->InitEditForms();
   }
   
   
    private function InitEditForms(){
        $am = MAccount::Get();
        $name = $am->GetName($_SESSION['userID']);
        $nameForm = new FName();
        $nameForm->SetValues($name[0]['given'], $name[0]['family']);
        $this->AddChild($nameForm);
   }
}