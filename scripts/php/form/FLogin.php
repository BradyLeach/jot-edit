<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccess.php';
include_once $path . '/jot-edit/scripts/php/manager/MContact.php';
include_once $path . '/jot-edit/scripts/php/manager/MLogin.php';
include_once $path . '/jot-edit/scripts/php/manager/MToken.php';
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
class FLogin extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
  
    const PASS      = 'password';    
    const UNAME     = 'username';
    const SUBMIT    = 'submit';
    
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;    //Holds an array of form input nodes
    private   $m_locked = FALSE;    //True if the form has been locked
            
       /**
        * @brief Construct the green and gold records log in form. 	
        */   
    function __construct($id='login') {
        parent::__construct( $id, array("method"=>"post") );
        
        $this->m_formID=$id;
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
        $this->m_inputs[FLogin::UNAME] = new FUsername(FLogin::UNAME,  FLogin::UNAME,"Username" );
        $this->m_inputs[FLogin::PASS] = new CPasswordInput(FLogin::PASS, FLogin::PASS, "Password");
        $this->m_inputs[FLogin::SUBMIT] = new CSubmitButton(FLogin::SUBMIT, array("name"=>"submit"), "submit", "GO!");

    } 
       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){      
        foreach($this->m_inputs as $input) {
            if($input && is_a($input, "CFormInput", FALSE)  ){
                    //Make inputs sticky
                $input->MakeSticky();
            }
        }
    }  
     
        /**
         * @brief Validates the forms inputs.            	
         */ 
    protected function Validate() {         
        if(!$this->m_inputs[FLogin::UNAME]->Validate() || !$this->m_inputs[FLogin::PASS]->Validate()){
            $this->SetFormError("Login failed.Please verrify you are using the correct credentials."); 
            $this->SetErrorFlag();
        }
     }
   
       /**
        * @brief Process the form.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    protected function Process(){
        
        $login = MAccess::Get();
        
        if($login->RequestAccess( filter_input(INPUT_POST, FLogin::UNAME),
                                  filter_input(INPUT_POST, FLogin::PASS)))
        {
            $this->m_processed = TRUE;
        }elseif($this->LimitReached(filter_input(INPUT_POST, FLogin::UNAME))){
            $this->Lock(); 
            $this->SendResetLink(filter_input(INPUT_POST, FLogin::UNAME));
            $this->m_inputs[FLogin::SUBMIT]->AddAttribute('disabled', 'disabled');//or simply disable but not sure.
            $this->SetFormError("Your account has been locked");
            
            $notify = new CText('', 'div', array('class'=>'notify'), 'For information on how to unlock your account visit the');
            $notify->AddChild(new CLink('', array('class'=>'notify'), 'FAQ', '/jot-edit/FAQ'));
            $this->AddChild($notify);
            
        }else{         
            $this->SetFormError("Invali account details.");            
            $this->AddChild(new CText('', 'div', array('class'=>'notify'), 'Please check your details and try again.'));
        }
    }
    
    
    
    
        /**
         * @brief A test to see if the form should be locked.
         * @return TRUE if the form needs to be locked otherwise FALSE.
         */     
    public function LimitReached($user){
        if($user){
            $login = MAccess::Get();
            return($login->IsAccountLocked());    
        }
    }
 
        /**
         * @brief Locks the form so the user can attempt no further logins.
         */
    private function Lock() {

        $this->m_locked = TRUE;
    }
    
    
    private function SendResetLink($user) {

        if(!isset($_SESSION['notified'])){
                //Get the details
            $contact = MContact::Get();
            $email = $contact->GetEmail($user);
            
                //Send the message via the mail manager.
            $mm=MMail::Get();  
            $mm->SendAccountLock($email, $user);          
            
                //Set the session variable to true so the user doesnt get a million 
                //notification emails
             $_SESSION['notified'] = TRUE;
        }
        

    }

       /**
        * @brief Unlocks the form so the user can attempt further logins.
        */    
    private function Unlock() {
        $this->m_locked = FALSE;
    }
 
       /**
        * @brief Check to see if the form is currently locked.
        * @return TRUE if the form is locked, Otherwise FALSE.
        */ 
    public function Locked(){
        return($this->m_locked);       
    }
    
}//End of Class
