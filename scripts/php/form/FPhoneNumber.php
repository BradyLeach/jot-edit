<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MContact.php';
include_once $path . '/jot-edit/scripts/php/form/FInputs.php';

   /**
    * @author Brady Leach 
    * @date 06/05/2015
    * @brief The login form to gain access to the members area of the site. 
    * 
    * @param 4m_formID string The id of the form.
    * @param $m_inputs array Holds all of th einput elements for the form. 
    * @note This is a high level class used for grouping core functionality
    * for this specific html form. This class is considered to the the 
    * equivilent of a html level document. Each form that is used by the green 
    * and gold site will be a class extending the CForm base class.
    */
class FPhoneNumber extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
  
    const NUM       = 'number';  
    const REMOVE    = 'remove';
    const SUBMIT    = 'submit';
    
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;    //Holds an array of form input nodes
            
        /**
         * 
         * @param string $id the id of the form. 
         * @param boolean $new. TRUE if the form is for a new phone number, otherwise false.
         * @note this should be set to match the 
         * id of the corrisponding database entry. This value is used in the database 
         * removal and update routines.
         */
    function __construct($id='' , $new=TRUE) {
        parent::__construct( $id .'_phone', array('class'=>'page_form', 'method'=>'post') );
        
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

       if(!$this->m_processed){
            //Set all the form inputs we want to retain their value after 
            //form submission.
            $this->MakeStickyInputs();
       }
            //Add inputs to form. 
        $this->InsertInputs($this->m_inputs);        
    }
   
        /**
        * @brief Initializes all fo the inuts for this form.              
        * 	
        */
    protected function Init($new) {
        $this->m_inputs[FPhoneNumber::NUM] = new FPhone('',FPhoneNumber::NUM, "Phone number",TRUE, TRUE);
        
        if(!$new){
            $this->m_inputs[FPhoneNumber::SUBMIT] = new CSubmitButton('', array("name"=>FPhoneNumber::SUBMIT), "update", "update");
            $this->m_inputs[FPhoneNumber::REMOVE] = new CSubmitButton('', array("name"=>FPhoneNumber::SUBMIT), "remove", "remove");
        }else{
            $this->m_inputs[FPhoneNumber::SUBMIT] = new CSubmitButton('', array("name"=>FPhoneNumber::SUBMIT), "submit", "add");
        }
    } 
       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){
        if($this->CanValidate()){
            foreach($this->m_inputs as $input) {
                if($input && is_a($input, "CTextInput", FALSE) && $input->GetType() != "password"){
                        //Make inputs sticky
                    $input->MakeSticky();
                }
            }
        }
    }  
    
    public function SetValues($number){
         
        $this->m_inputs[FPhoneNumber::NUM]->GetInput()->AddAttribute('value', $number);
        
     }
     
     
        /**
         * @brief Validates the forms inputs.            	
         */ 
    protected function Validate() {         
        if(!$this->m_inputs[FPhoneNumber::NUM]->Validate()){
            //$this->SetFormError(""); 
            $this->SetErrorFlag();
        }
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

        $pnum = filter_input(INPUT_POST, FPhoneNumber::NUM);
        $cm = MContact::Get();
        
        if($new){                
                //process new number entry
            $this->NewPhoneNumber($cm, $_SESSION['userID'], $pnum);
        }
        else{
            $this->ModifyPhoneNumber($cm, $_SESSION['userID'], $pnum);                
        }    
    }
    
    
           /**
        * @brief Process the form.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    private function NewPhoneNumber($cm, $aid, $number){
                                      
        if($cm->PhoneNumberExists( $aid, $number)){
            $this->SetFormError("An entry with those detals already exists."); 
            return;//just like normal error
        }
           //Update the name
        if($cm->AddPhoneNumber($aid, $number)){
            $this->m_processed = TRUE;
            Redirect('../../../action/account/phone');
            return;//Add form success
        }            
    }
    
       /**
        * @brief Process the form.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    private function ModifyPhoneNumber($cm, $aid, $number){
     
        $btn = filter_input(INPUT_POST, 'submit');
        
        if($btn === FPhoneNumber::REMOVE){            
            $this->Remove($cm, $aid, $number);  
        }else{
            $this->Update($cm, $aid, $this->GetRecordID(), $number);
        }
    }
    
    
    
    private function Update($cm, $aid, $recordID, $number){
        if(!$cm->PhoneNumberExists($aid, $number)){
            if(!$cm->UpdatePhoneNumber($recordID, $number)) {
                  $this->SetFormError("There was an error."); 
            }else{
                $this->m_processed = TRUE;
                Redirect('../../../action/account/phone');
            }
        }else{
          $this->SetFormError("An entry with those detals already exists.");  
        }
    }
    
    private function Remove($cm, $accountID, $number){
        if($cm->PhoneNumberExists($accountID, $number)){
            if(!$cm->RemovePhoneNumber($accountID, $number)) {
                $this->SetFormError("An entry with those detals already exists.");  
            }else{
                 $this->m_processed = TRUE;
                 Redirect('../../../action/account/phone');
            }
        }else{
          $this->SetFormError("No entry that matches thoses details exists.");  
        }
    }
 
    private function GetRecordID(){
        return(substr($this->m_id, 0, strpos($this->m_id, "_")));
    }
  
}//End of Class
