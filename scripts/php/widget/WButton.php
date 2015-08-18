<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';

class WButton extends CDiv {
    
   public function __construct($id, $btn_text, $href) {
       parent::__construct($id.'_widget', array('class'=>'widget'));
       
        $this->InitWidgetButton($btn_text, $href);
   }
   
    private function InitWidgetButton($btn_text, $href){
            //Create the login link switch
        $this->AddChild(new CLink($this->m_id.'_link', array('class'=>'button'), $btn_text, $href));
   }
}