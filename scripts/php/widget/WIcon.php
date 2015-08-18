<?php
include_once 'stCore/stCore.php';

class WIcon extends CDiv {
    
   public function __construct($id, $icn_text, $href) {
       parent::__construct($id.'_widget', array());
       
        $this->InitWidgetButton($icn_text, $href);
   }
   
    private function InitWidgetButton($icn_text, $href){
            //Create the login link switch
        $link = new CLink($this->m_id.'_link', array('class'=>'icon'), $icn_text, $href);
      //  $link->AddChild(new CDiv($this->m_id.'_image', array('class'=>'icon_img')));
        $this->AddChild($link);
   }
}