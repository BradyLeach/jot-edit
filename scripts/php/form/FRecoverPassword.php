<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MContact.php';
include_once $path . '/jot-edit/scripts/php/manager/MLogin.php';
include_once $path . '/jot-edit/scripts/php/manager/MToken.php';
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
class FRecoverPassword extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
    const UNAME     = 'username';
    const EMAIL     = 'email';  
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

            ///The email address for login and contact
        $this->m_inputs[FRecoverPassword::UNAME]  = new FIUsername(FRecoverPassword::UNAME, FRecoverPassword::UNAME, "Username :");     
        $this->m_inputs[FRecoverPassword::EMAIL] = new CEmailInput(FRecoverPassword::EMAIL, FRecoverPassword::EMAIL , "Email :", TRUE, TRUE);
        
            //Submit button
        $this->m_inputs[FRecoverPassword::SUBMIT] = new CSubmitButton(FRecoverPassword::SUBMIT, array("name"=>"submit"), "submit", "Send");
        
    } 

       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){      

            //Make inputs sticky
        $this->m_inputs[FRecoverPassword::EMAIL]->MakeSticky();
        $this->m_inputs[FRecoverPassword::UNAME]->MakeSticky(); 
    }  

     
        /**
         * @brief Validates the forms inputs.            	
         */ 
     protected function Validate() {         
            //validate the act name.
        if(!$this->m_inputs[FRecoverPassword::EMAIL]->Validate() ||
           !$this->m_inputs[FRecoverPassword::UNAME]->Validate()    ){
            $this->SetErrorFlag(); 
            $this->SetFormError("Invalid input\n. Please check the details and try again.");
            return;
        }
        
        if(!$this->ValidMailPair(filter_input(INPUT_POST,FRecoverPassword::UNAME), filter_input(INPUT_POST,FRecoverPassword::EMAIL))){
            $this->SetErrorFlag(); 
            $this->SetFormError("Could not complete request\n. Please check the details and try again.");
            return;            
        }
     }

    public function ValidMailPair($uname, $email){
        $cm = MContact::Get();
        $accEmail=$cm->GetEmail($uname);
        if($accEmail === $email){
            return( TRUE );
        }
        else{
            return( FALSE );
        }
    }
    
        /**
         * @brief Process the form.
         */     
    protected function Process(){
        $email = filter_input(INPUT_POST,FRecoverPassword::EMAIL);
        $uname = filter_input(INPUT_POST,FRecoverPassword::UNAME);
        
        if($this->SendResetLink( $email, $uname)){
            $this->m_processed = TRUE;
        }else{
            $this->SetFormError('There was a problem submitting your request. Please try again shortly.');
        }
    }
    
    
    private function SendResetLink( $email, $user) {
        $token = array();
        $verify = MToken::Get();
        if($verify->SetUpAccountToken($user, $token)){ 

            $mm=MMail::Get();
            return($mm->SendPasswordRecovery($email, $user, $token['ticket'], $token['token']));
        }  
        else{
            //error        
            //record in log
            //redirect to technicall error page.
            return( FALSE );
        }
    }
    
    
    
}//End of Class
