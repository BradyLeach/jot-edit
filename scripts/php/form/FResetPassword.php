<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccount.php';
include_once $path . '/jot-edit/scripts/php/manager/MLogin.php';
include_once $path . '/jot-edit/scripts/php/form/FInputs.php';
   /**
    * @author Brady Leach 
    * @date 06/05/2015
    * @brief The form to reset a useres password.. 
    * 
    * @param $m_formID string The id of the form.
    * @param $m_inputs array Holds all of th einput elements for the form. 
    *  
    * @note This is a high level class used for grouping core functionality
    * for this specific html form. This class is considered to the the 
    * equivilent of a html level document. Each form that is used by the green 
    * and gold site will be a class extending the CForm base class.
    * 
    * @todo This turned into a mess. Need to look at a better way of handling...
    */
class FResetPassword extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
  
    const PASS      = 'password';    
    const PASS2     = 'password2';
    const UNAME     = 'username';
    const SUBMIT    = 'submit';
    
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;    //Holds an array of form input nodes
            
       /**
        * @brief Construct the green and gold records reset password form. 	
        */       
    function __construct($id='reset_password', $internal=TRUE) {
        parent::__construct( $id, array('class'=>'page_form', 'method'=>'post') );

                
            //Initialize the text input forms.
        $this->Init();
        
            //If the form has been submitted
        if($this->CanValidate()) {
            $this->Validate($internal);
        }
        
            //If the form has been submitted
        if($this->CanValidate() && !$this->HasErrorFlag()) {
            $this->Process($internal);
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
        $this->m_inputs[FResetPassword::UNAME] = new FIUsername ('',FResetPassword::UNAME,"Username :" );
      
       
            ///The pass for login and contact
        $this->m_inputs[FResetPassword::PASS] = new CPasswordInput('',FResetPassword::PASS , "Password :");
        
        
            ///The pass repeat for login and contact
        $this->m_inputs[FResetPassword::PASS2] = new CPasswordInput('',FResetPassword::PASS2 , "Re enter password :");

        
            //Submit button
        $this->m_inputs[FResetPassword::SUBMIT] = new CSubmitButton('', array("name"=>"submit"), "submit", "Reset");
                
    } 

       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){      
        foreach($this->m_inputs as $input) {
            if($input && is_a($input, "CFormInputBase", FALSE) && !is_a($input, "CPasswordInput", FALSE) ){
                    //Make inputs sticky
                $input->MakeSticky();
            }
        }
    }  

     
        /**
         * @brief Validates the forms inputs.            	
         */ 
     protected function Validate($internal) {         
            //validate the act name.
        if(!$this->m_inputs[FResetPassword::UNAME]->Validate()){ $this->SetErrorFlag();}
        if(!$this->m_inputs[FResetPassword::PASS]->Validate()) { $this->SetErrorFlag();}
        if(!$this->PasswordMatch() ){ 
            $this->SetErrorFlag();
            $this->SetFormError("The password you entered did not match."); 
            return;
        }    
        if(!$internal){
            if(!$this->UserTokenMatch() ){ 
                $this->SetErrorFlag();
                $this->SetFormError("please check the username and try again."); 
                return;
            }
        }
     }
     
    
        /**
         * @brief Check to see if the username has a valid token for password reset. 
         * @return TRUE if the password can be reset, otherwise FALSE 
         */  
    protected function UserTokenMatch() {  
        //Get the account manager
        $am = MAccount::Get();
        //Get the id for the user accoutn associated to the username
        $aID = $am->GetAccountIDByUsername(filter_input(INPUT_POST, FResetPassword::UNAME));
        
        //If there is no id for the username return false
        if($aID === FALSE){ return( FALSE ); }
        
        //Get the token manager
        $tm = MToken::Get();
        
        //Return if the account has a token set or not
        return($tm->IsAccountTokenSet($aID));
        
    }
     
        /**
         * @brief Check to see if the two passwords match. 
         * @return TRUE if the passwords match, otherwise FALSE 
         */  
    protected function PasswordMatch() {
        $p1 = filter_input(INPUT_POST, FResetPassword::PASS);
        $p2 = filter_input(INPUT_POST, FResetPassword::PASS2); 
        return( $p1 === $p2 );
    }

    
        /**
         * @brief Process the form.
         */     
    protected function Process($internal){
        
        $login = MLogin::Get();

        try{
            $login->UpdatePassword(
                filter_input(INPUT_POST, FResetPassword::UNAME),
                filter_input(INPUT_POST, FResetPassword::PASS));
            
            if($internal){
                $am = MAccess::Get();
                $am->RequestAccess($_SESSION['username'], filter_input(INPUT_POST, FResetPassword::PASS));                
            }
            
        } catch (Exception $ex) {
            //Write exception to log
            //redirect to database error page.  
            echo $ex;
            return;
        }
        $this->m_processed = TRUE;
        $login->ClearFailedLoginRecord( filter_input(INPUT_POST, FResetPassword::UNAME));        
    }

    
}//End of Class
