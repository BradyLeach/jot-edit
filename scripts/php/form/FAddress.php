<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAddress.php';
include_once $path . '/jot-edit/scripts/php/form/FInputs.php';

   /**
    * @author Brady Leach 
    * @date 06/05/2015
    * @brief The New artist sign up form object.   
    * @note This is a high level class used for grouping core functionality
    * for this specific html form. Each form that is used by the green and gold 
    * site will be a class extending the CForm base class.
    */
class FAddress extends CForm {
    
        //address maybe moved to seperate form as it is not really necessary for. 
        //for first step of sign up.
    const LINE1         = 'line1';
    const LINE2         = 'line2';
    const SUBURB        = 'suburb';
    const POST_CODE     = 'pCode';
    const STATE         = 'state';
    const SUBMIT        = 'submit';
    const REMOVE        = 'remove';
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;  //Holds an array of form input nodes
   
    
    
    
    function __construct($id, $new=TRUE) {
        parent::__construct( $id.='_address', array('class'=>'page_form', 'method'=>'post') );
        
        $this->m_formID=$id;
            //Initialize the text input forms.
        $this->Init($new);
        
            //If the form has been submitted
        if($this->CanValidate()) {
            $this->Validate();
        }
        
            //If the form has been submitted
        if($this->CanValidate() && !$this->HasErrorFlag()) {
            $this->Process($new);
        }

        if( !$this->Processed()){
            //Set all the form inputs we want to retain their value after 
            //form submission.
          $this->MakeStickyInputs();
        }
            //Add inputs to form. 
        $this->InsertInputs($this->m_inputs);        
     
     }
     
     public function SetValues($l1, $l2, $suburb, $pcode, $sid){
         
        $this->m_inputs[FAddress::LINE1]->GetInput()->AddAttribute('value', $l1);
        $this->m_inputs[FAddress::LINE2]->GetInput()->AddAttribute('value', $l2);
        $this->m_inputs[FAddress::SUBURB]->GetInput()->AddAttribute('value', $suburb); 
        $this->m_inputs[FAddress::POST_CODE]->GetInput()->AddAttribute('value', $pcode); 
       
        $this->m_inputs[FAddress::STATE]->SetSelected( $sid .'_'. $this->m_id );   
        
     }
     
    
        /**
        * @brief Initializes all fo the inuts for this form.              
        * 	
        */
    protected function Init($new) {

        $this->InitAddressInput();  //Initialize address detail inputs
        $this->InitSubmit($new);        //Initialize submit inputs
        
            //Set all the form inputs we want to retain their 
            //value after form submission.
        $this->MakeStickyInputs();
    } 
    
    
        /**
        * @brief Initialize address detail inputs.            	
        */      
    private function InitAddressInput(){
        
        $this->m_inputs[FAddress::LINE1] = new FAddressLine('', FAddress::LINE1, "Address Line 1", TRUE, TRUE);       
        $this->m_inputs[FAddress::LINE2] = new FAddressLine('', FAddress::LINE2, "Address Line 2", FALSE, TRUE);
   
        $this->m_inputs[FAddress::SUBURB] = new FName('', FAddress::SUBURB, "Suburb", TRUE, TRUE);        
        $this->m_inputs[FAddress::SUBURB]->GetInput()->AddAttribute('size', 15);

        $this->m_inputs[FAddress::POST_CODE] = new FPCode('', FAddress::POST_CODE, "Post Code", TRUE, TRUE);        
        $this->m_inputs[FAddress::POST_CODE]->GetInput()->AddAttribute('size', 6);  
        
        $this->m_inputs[FAddress::STATE] = new CSelectInput('', array(), FAddress::STATE, "State", 'select a state');
        
            //Load act list
        $this->LoadStateList();       
    }


       /**
        * @brief Initialize login detail inputs.            	
        */      
   private function InitSubmit($new) {
       
        if(!$new){
            $this->m_inputs[FAddress::SUBMIT] = new CSubmitButton('', array( 'name'=>FAddress::SUBMIT), 'update', 'update');
            $this->m_inputs[FAddress::REMOVE] = new CSubmitButton('', array("name"=>FAddress::SUBMIT), "remove", "remove");
        }else{
            $this->m_inputs[FAddress::SUBMIT] = new CSubmitButton('', array("name"=>FAddress::SUBMIT), "update", "add");
        }
   }
   
     
       /**
        * @brief Loads the posible act members from the db.              
        * 
        * @todo once the data base is build add implementation.	
        */
    protected function LoadStateList() {
        
        $addrManager = MAddress::Get();
        $states = $addrManager->GetStates();
        
        //load the state list.
        foreach($states as $stateID => $state) {
            $this->m_inputs[FAddress::STATE]->AddOption($stateID .'_'. $this->m_id, $state, $state);
        }  
        
    }
              
     
       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){
        if($this->CanValidate()){
            foreach($this->m_inputs as $input) {
                if($input && is_a($input, "CKeybordInput", FALSE) && $input->GetType() != "password"){
                        //Make inputs sticky
                    $input->MakeSticky();
                }
            }
        }
    }  
     
     protected function Validate() {
             
        foreach($this->m_inputs as $input){
            if(is_a($input, 'CSubmitButton')){continue;}
            
            if(!$input->Validate()){
                $this->SetErrorFlag();
            }            
        }
     }
     
     private function GetAddressObject(){

        $address['line1']=filter_input(INPUT_POST, FAddress::LINE1 );
        $address['line2']=filter_input(INPUT_POST, FAddress::LINE2);
        $address['suburb']=filter_input(INPUT_POST, FAddress::SUBURB);
        $address['pcode']=filter_input(INPUT_POST, FAddress::POST_CODE);
        $address['state']=filter_input(INPUT_POST, FAddress::STATE);

        return($address);     
     }
     
       /**
        * @brief Process the form.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    protected function Process($new){
        //Get the access manager
        $am = MAccess::Get();              
        
            //If a user is logged
        if(!$am->HasAccess()){ return; } //redirect to login page

        $address = $this->GetAddressObject();
        $addm = MAddress::Get();
        
        if($new){                
                //process new number entry
            $this->NewAddress($addm, $_SESSION['userID'], $address);
        }
        else{
            $this->Modify($addm, $_SESSION['userID'], $address);                
        }
    }
    
    
           /**
        * @brief Process the a new address entry.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    private function NewAddress($addm, $aid, $address){
                                       
        if($addm->AddressExists($aid, $address['line1'], $address['line2'], $address['suburb'], $address['pcode'], $address['state'])){
            $this->SetFormError("An entry with those detals already exists."); 
            return;//just like normal error
        }
              
           //Update the name
        if($addm->AddAddress($aid, $address['line1'], $address['line2'], $address['suburb'], $address['pcode'], $address['state'])){
            $this->m_processed = TRUE;
            Redirect('../../../action/account/address');
            return;//Add form success
        }      
    }
    
    private function Modify($addm, $aid, $address){
        $btn = filter_input(INPUT_POST, 'submit');
        
        if($btn === AddAddress::REMOVE){            
            $this->Remove($addm, $aid, $address);  
        }else{
            $this->Update($addm, $aid, $this->GetRecordID(), $address);
        }          
    }
    
    public function Reset(){
         $this->SetValues('', '', '', '', '');
    }
        
    
    private function Update($addm, $aid, $recordID, $address){
        if(!$addm->AddressExists($aid, $address['line1'], $address['line2'], $address['suburb'], $address['pcode'], $address['state'])){
            if(!$addm->UpdateAddress($aid, $recordID, $address['line1'], $address['line2'], $address['suburb'], $address['pcode'], $address['state'])) {
                  $this->SetFormError("There was an error."); 
            }else{
                $this->m_processed = TRUE;
                Redirect('../../../action/account/address');
            }
        }else{
          $this->SetFormError("Duplicate record exists.");  
        }
    }
    
    
    private function Remove($addm, $accountID, $address){
        if($addm->AddressExists($accountID, $address['line1'], $address['line2'], $address['suburb'], $address['pcode'], $address['state'])){
            if(!$addm->RemoveAddress($accountID, $this->GetRecordID())) {
                $this->SetFormError("There was an error.");
            }else{
                 Redirect('../../../action/account/address');
            }
        }else{
          $this->SetFormError("No entry that matches thoses details exists.");  
        }
    }
 
    private function GetRecordID(){
        return(substr($this->m_id, 0, strpos($this->m_id, "_")));
    }    
 
          
}//End of Class