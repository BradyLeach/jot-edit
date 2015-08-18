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
    * @param  $m_locked boolean True if the form has been locked  
    * @note This is a high level class used for grouping core functionality
    * for this specific html form. This class is considered to the the 
    * equivilent of a html level document. Each form that is used by the green 
    * and gold site will be a class extending the CForm base class.
    */
class FEmail extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
  
    const EMAIL      = 'email';  
    const REMOVE     = 'remove';
    const SUBMIT    = 'submit';
    
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;    //Holds an array of form input nodes
    private   $m_locked = FALSE;    //True if the form has been locked
            
       /**
        * @brief Construct the green and gold records log in form. 	
        */   
    function __construct($id='', $new=TRUE) {
        parent::__construct( $id.'_email', array('class'=>'page_form', 'method'=>'post') );
        
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

            //Set all the form inputs we want to retain their value after 
            //form submission.
        $this->MakeStickyInputs();

            //Add inputs to form. 
        $this->InsertInputs($this->m_inputs);        
     }
     
    
        /**
        * @brief Initializes all fo the inuts for this form.              
        * 	
        */
    protected function Init($new) {
        $this->m_inputs[FEmail::EMAIL] = new CEmailInput('',FEmail::EMAIL, "Email",TRUE,TRUE);
        if($new){
             $this->m_inputs[FEmail::SUBMIT] = new CSubmitButton('', array("name"=>FEmail::SUBMIT), "update", "add");
        }else {
            $this->m_inputs[FEmail::SUBMIT] = new CSubmitButton('', array("name"=>FEmail::SUBMIT), "update", "update");
            $this->m_inputs[FEmail::REMOVE] = new CSubmitButton('', array("name"=>FEmail::SUBMIT), "remove", "remove");
    
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
    
    
    public function SetValues($email){
         
        $this->m_inputs[FEmail::EMAIL]->GetInput()->AddAttribute('value', $email);     
     }   
     
        /**
         * @brief Validates the forms inputs.            	
         */ 
    protected function Validate() {         
        if(!$this->m_inputs[FEmail::EMAIL]->Validate()){
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

        $email = filter_input(INPUT_POST, FEmail::EMAIL);
        $cm = MContact::Get();
        
        if($new){                
                //process new number entry
            $this->NewEmail($cm, $_SESSION['userID'], $email);
        }
        else{
            $this->ModifyEmail($cm, $_SESSION['userID'], $email);                
        } 
    }
    
    
    
       /**
        * @brief Process the a new email entry.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    private function NewEmail($cm, $aid, $email){
                                      
        if($cm->EmailExists($aid, $email)){
            $this->SetFormError("An entry with those detals already exists."); 
            return;//just like normal error
        }
           //Update the name
        if($cm->AddEmail($aid, $email)){
            $this->m_processed = TRUE;
            Redirect('../../../action/account/email');
            return;//Add form success
        }            
    }
    
    
    
    
       /**
        * @brief Process the form.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    private function ModifyEmail($cm, $aid, $email){
     
        $btn = filter_input(INPUT_POST, 'submit');
        
        if($btn === FPhoneNumber::REMOVE){            
            $this->Remove($cm, $aid, $email);  
        }else{
            $this->Update($cm, $aid, $this->GetRecordID(), $email);
        }
    }
    
    
    
    private function Update($cm, $aid, $recordID, $email){
        if(!$cm->EmailExists($aid, $email)){
            if(!$cm->UpdateEmail($recordID, $email)) {
                  $this->SetFormError("There was an error."); 
            }else{
                $this->m_processed = TRUE;
                Redirect('../../../action/account/email');
            }
        }else{
          $this->SetFormError("An entry with those detals already exists.");  
        }
    }
    
    
    private function Remove($cm, $accountID, $email){
        if($cm->EmailExists($accountID, $email)){
            if(!$cm->RemoveEmail($accountID, $email)) {
            $this->SetFormError("An entry with those detals already exists.");  
            }else{
                 $this->m_processed = TRUE;
                 Redirect('../../../action/account/email');
            }
        }else{
          $this->SetFormError("No entry that matches thoses details exists.");  
        }
    }
 
    private function GetRecordID(){
        return(substr($this->m_id, 0, strpos($this->m_id, "_")));
    }    
 
    
}//End of Class
