<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MInvite.php';
include_once $path . '/jot-edit/scripts/php/form/FInputs.php';

   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Form to invite a creator. 
    * 
    * @param string $m_formID The id of the form.
    * @param array $m_inputs Holds all of th einput elements for the form. 
    *  
    * @note This is a high level class used for grouping core functionality
    * for this specific html form. This class is considered to the the 
    * equivilent of a html level document. Each form that is used by the green 
    * and gold site will be a class extending the CForm base class.
    */
class FInviteCreator extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
  
    const EMAIL     = 'email';    
    const NAME      = 'name';
    const SUBMIT    = 'submit';
    
        //varaibles
    protected $m_formID = "invite_creator_form";    ///The forms id.
    protected $m_inputs;                            //Holds an array of form input nodes

       /**
        * @brief Constructs an creator invitation form.              
        * @note The constructor initializes teh form. If the post input has
        * been set then the form will be validated, processed. If validation or
        * processing fails then the form will be redisplayed with the current
        * post values.
        * 	
        */
    function __construct() {
        parent::__construct( $this->m_formID, array("class"=>"page_form", "method"=>"post") );

                
            //Initialize the text input forms.
        $this->Init();
        
            //If the form has been submitted
        if($this->CanValidate()) {
            $this->Validate();
        }
        
            //If the form has been submitted
        if($this->CanValidate() && !$this->HasErrorFlag()) {
            $this->Process();
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
    protected function Init() {
        
            ///The personal details
        $this->m_inputs[FInviteCreator::NAME] = new FName(FInviteCreator::NAME, FInviteCreator::NAME, "Name :", TRUE, TRUE);

            ///The email address for login and contact
        $this->m_inputs[FInviteCreator::EMAIL] = new CEmailInput(FInviteCreator::EMAIL, FInviteCreator::EMAIL , "Email :");

            //Submit button
        $this->m_inputs[FInviteCreator::SUBMIT] = new CSubmitButton(FInviteCreator::SUBMIT, array("name"=>"submit"), "submit", "Invite");
                
    } 

       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){      
        foreach($this->m_inputs as $input) {
            if($input && is_a($input, "CFormInput", FALSE) && !is_a($input, "CPasswordInput", FALSE) ){
                    //Make inputs sticky
                $input->MakeSticky();
            }
        }
    }  

     
        /**
         * @brief Validates the forms inputs.            	
         */ 
     protected function Validate() {         
            //validate the act name.
        if(!$this->m_inputs[FInviteCreator::NAME]->Validate() || 
           !$this->m_inputs[FInviteCreator::EMAIL]->Validate()){
            $this->SetFormError("Invalid name or email."); 
            $this->SetErrorFlag();           
        }

     }

    
          /**
         * @brief Process the form.
         */     
    protected function Process(){
        
        $invite = MInvite::Get();
        if($invite->InviteNewCreator(
                filter_input(INPUT_POST, FInviteCreator::NAME),
                filter_input(INPUT_POST, FInviteCreator::EMAIL))){
            $this->m_processed = TRUE;
        }
        
    }
    
}//End of Class
