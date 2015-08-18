<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/page/PCallout.php';

class WAccountTools extends CDiv {
    
    public function __construct($id='account', $attributes=array('class'=>'widget'), $open=FALSE) {
        parent::__construct($id.'_widget', $attributes);
        
        $this->m_tools = new CList('', 'ul', array('class'=>'tool_list'));
        
        $this->InitWidgetButton();
        $this->InitWidgetCallOut($open);       
    }
    
   
    private function InitWidgetButton(){
            //Create the login link switch        
        $this->AddChild(new CLink($this->m_id . '_link', array('class'=>'button'), 'account', '/jot-edit/action/account'));
    }
    
    
    private function InitWidgetCallOut($open){
            //Create the call out box
        $ats = new PCallout('account_tools');
        
        if($open){
            $ats->AddAttribute('style', 'display:block');
        }
        
        //load generic tools for all accoutn holders
        $this->AddTool('name', 'btnName', 'g', '/jot-edit/action/account/name');
        $this->AddTool('address', 'btnAddress', ',', '/jot-edit/action/account/address');
        $this->AddTool('phone', 'btnPhone', 'c', '/jot-edit/action/account/phone');
        $this->AddTool('email', 'btnEmail', 'm', '/jot-edit/action/account/email');
        $this->AddTool('password', 'btnPassword', 'n', '/jot-edit/action/account/password');
      

        $ats->AddChild($this->m_tools);
 
            //Add the Call out box and its content to the widget
        $this->AddChild($ats);
    }   
    
    private function AddTool($longText, $linkId, $symbolText, $href){
        $li = new CHtml('', 'li', array());
        $li->AddChild(new CPara('', array(), $longText));
        $li->AddChild(new CLink($linkId, array(), $symbolText, $href));
        $this->m_tools->AddChild($li);
    }
      
}