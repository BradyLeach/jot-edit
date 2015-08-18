<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/page/PCallout.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccess.php';

class WAccountToolBar extends CDiv {
    
    public function __construct($id='account_tool_bar', $attributes=array()) {
        parent::__construct($id, $attributes);
        
        $this->m_tools = new CList('', 'ul', array('class'=>'tool_list'));
        
        $this->InitWidgetCallOut();       
    }
    

    private function InitWidgetCallOut(){

        //load generic tools for all accoutn holders
        $this->AddTool('name', 'btnName', 'J', '/jot-edit/action/account/name');
        $this->AddTool('address', 'btnAddress', ',', '/jot-edit/action/account/address');
        $this->AddTool('phone', 'btnPhone', 'c', '/jot-edit/action/account/phone');
        $this->AddTool('email', 'btnEmail', 'm', '/jot-edit/action/account/email');
        $this->AddTool('password', 'btnPassword', 'n', '/jot-edit/action/account/password');
      
        //user has admin clearence
        //Add invite tool
        //Add Create Profile tool
        $am = MAccess::Get();
        if($am->HasAdminAccess()){
            $this->AddTool('invite', 'btnInvite', 'Y', '/jot-edit/action/account/invite'); 
        }
        
        //Add the tool buttons to the tool bar
        $this->AddChild($this->m_tools);
    }   
    
    private function AddTool($longText, $linkId, $symbolText, $href){
        $li = new CHtml('', 'li', array());
        $li->AddChild(new CPara('', array(), $longText));
        $li->AddChild(new CLink($linkId, array(), $symbolText, $href));
        $this->m_tools->AddChild($li);
    }
    
    
      
}