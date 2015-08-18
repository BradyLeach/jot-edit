<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/form/FAddress.php';
include_once $path . '/jot-edit/scripts/php/manager/MAddress.php';



class WAddress extends CDiv {
    
    private $m_heading; //A h2 object for holding the success notification heading
    private $m_message; //a p object for holding the success message.
    
   public function __construct($id, $heading='', $message='', $attributes=array('class'=>'tool_widget')) {
       parent::__construct($id, $attributes);
       
       $this->m_heading = new CText('notify_address', 'h2', array(), $heading);
       $this->m_message = new CPara('notify_address_message', array(), $message);
       $this->AddChild($this->m_heading );
       $this->AddChild($this->m_message );
       
       $this->InitNewForms();
       $this->InitEditForms();
        
   }
   
   public function SetMessage($header, $msg){
       $this->m_heading->SetText($header);
       $this->m_message->SetText($msg);
   }
   
    private function InitEditForms(){

        $addm= MAddress::Get();
        $addressList = $addm->GetAddressList($_SESSION['userID']);
        if(isset($addressList)){
            //for each stored address load a form
            foreach($addressList as $addr){
                $form = new FAddress($addr['id'], FALSE);  
                $form->SetValues($addr['line1'], $addr['line2'], $addr['suburb'], $addr['postCode'], $addr['stateID']);             
                $this->AddChild($form);
            }
        }
   }
   
    private function InitNewForms(){

         $this->AddChild(new FAddress('new'));
   }
}