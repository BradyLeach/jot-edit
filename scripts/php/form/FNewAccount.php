<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccount.php';
include_once $path . '/jot-edit/scripts/php/manager/MCreator.php';
include_once $path . '/jot-edit/scripts/php/manager/MContact.php';
include_once $path . '/jot-edit/scripts/php/manager/MLogin.php';
include_once $path . '/jot-edit/scripts/php/manager/MToken.php';
include_once $path . '/jot-edit/scripts/php/form/FInputs.php';

   /**
    * @author Brady Leach 
    * @date 06/05/2015
    * @brief The New Creator sign up form object. 
    * 
    * @param $m_formID string The id of the form.
    * @param $m_inputs array Holds all of th einput elements for the form. 
    * @param $m_type string The type of creator account that is going to be created. 
    * @note This is a high level class used for grouping core functionality
    * for this specific html form. This class is considered to the the 
    * equivilent of a html level document. Each form that is used by the green 
    * and gold site will be a class extending the CForm base class.
    */
class FNewAccount extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
  
    const ACT_NAME  = 'act_name'; 
    const EMAIL     = 'email'; 
    const USERNAME  = 'username';
    const PASSWORD  = 'password'; 
    const PASSWORD2 = 'password2';    
    const FIRST_NAME= 'first_name';
    const SURNAME   = 'surname';    
    const SUBMIT    = 'submit';
    
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;    //Holds an array of form input no
    protected $m_type;      //The type of creator account that is goign to be created. 
    
       /**
        * @brief Construct the green and gold records creator registration form. 	
        */     
    function __construct($id='new_account', $type='artist') {
        parent::__construct( $id,  array("class"=>"page_form", "method"=>"post") );

         $this->m_type = $type;   
          $this->m_formID=$id;
            //Initialize the text input forms.
        $this->Init();        
            //If the form has been submitted
        if($this->CanValidate()) {  $this->Validate(); }        
            //If the form has been submitted
        if($this->CanValidate() && !$this->HasErrorFlag()) { $this->Process(); }        
        
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
        $this->m_inputs[FNewAccount::FIRST_NAME] = new FIName(FNewAccount::FIRST_NAME, FNewAccount::FIRST_NAME, FNewAccount::FIRST_NAME, "first Name", TRUE, TRUE);
        $this->m_inputs[FNewAccount::SURNAME] = new FIName(FNewAccount::SURNAME,FNewAccount::SURNAME, FNewAccount::SURNAME, "Surname", TRUE, TRUE);    
       
            ///The Act details
        $this->m_inputs[FNewAccount::ACT_NAME] = new FICreatorName(FNewAccount::ACT_NAME,FNewAccount::ACT_NAME, FNewAccount::ACT_NAME, "Act Name", TRUE, TRUE);
      
            ///The email address for login and contact
        $this->m_inputs[FNewAccount::EMAIL] = new CEmailInput(FNewAccount::EMAIL, FNewAccount::EMAIL, "Email", TRUE, TRUE);
       
            ///Login details.
        $this->m_inputs[FNewAccount::USERNAME] = new FIUsername(FNewAccount::USERNAME, FNewAccount::USERNAME,  "User Name", TRUE, TRUE, TRUE);
        $this->m_inputs[FNewAccount::PASSWORD] = new CPasswordInput(FNewAccount::PASSWORD, FNewAccount::PASSWORD, "Password", TRUE, TRUE);
        $this->m_inputs[FNewAccount::PASSWORD2] = new CPasswordInput(FNewAccount::PASSWORD2, FNewAccount::PASSWORD2, "Confirm Password", TRUE, TRUE);
        
            //Submit button
        $this->m_inputs[FNewAccount::SUBMIT] = new CSubmitButton(FNewAccount::SUBMIT, array("class"=>"btn_submit", "name"=>"submit"), "submit", "Register");
                
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
     protected function Validate() {         
       /*     //validate the act name.
        $this->ValidateCreatorName();
        $this->ValidateName();
        $this->ValidateEmail();
        $this->ValidateUsername();
        $this->ValidatePassword(); 
        */
         
        foreach($this->m_inputs as $input){
            if(is_a($input, 'CSubmitButton')){continue;}
            
            if(!$input->Validate()){
                $this->SetErrorFlag();
            }            
        }
        if(!$this->PasswordMatch() ){ 
            $this->SetErrorFlag();
            $this->SetFormError("The password you entered did not match."); 
            return;
        } 
        
     }
     
        /**
         * @brief Check to see if the two passwords match. 
         * @return TRUE if the passwords match, otherwise FALSE 
         */  
    protected function PasswordMatch() {
        $p1 = filter_input(INPUT_POST, FNewAccount::PASSWORD);
        $p2 = filter_input(INPUT_POST, FNewAccount::PASSWORD2); 
        return( $p1 === $p2 );
    }   
 
    
          /**
         * @brief Process the form.
         */     
    protected function Process(){
        
        $account = MAccount::Get();  //Get the account manager.
        $login = MLogin::Get();      //Get the Login manager.
        $contact = MContact::Get();  //Get the contact manager.
        $creator = MCreator::Get();  //Get the creator manager.
        $db = DB_Manager::GetInstance();    //Get the database manager.

        try {
                //Beginf the transaction
            $db->m_db_handle->beginTransaction();
            
                //Create the account
            $account->CreateAccount(filter_input(INPUT_POST, FNewAccount::FIRST_NAME), filter_input(INPUT_POST, FNewAccount::SURNAME));
            
                //Get the account id
            $aID = $db->m_db_handle->lastInsertID();
            
                //Create the login credentials
            $login->CreateLogin(filter_input(INPUT_POST, FNewAccount::USERNAME),filter_input(INPUT_POST, FNewAccount::PASSWORD), $aID);
           
                //Email address
            $contact->AddEmail($aID, filter_input(INPUT_POST, FNewAccount::EMAIL));
           
                //Creator profile [ MAY REMOVE THIS AND MAKE THIS A SEPERATE STEP WITH ADDITIONAL FEATURES. ]
            $creator->CreateCreator($this->m_type, filter_input(INPUT_POST, FNewAccount::ACT_NAME));
            
                //Create the link table for the account and the profile. 
            $account->CreateCreatorAccount($aID, $db->m_db_handle->lastInsertID());
            
                //Update the account status
            $account->NewAccountStatus($aID);

                //If everything commits, processed is true and the email account verification begins.
            if( $db->m_db_handle->commit() ){
                 $this->m_processed = TRUE;
                 $this->VerifyNewCreator(filter_input(INPUT_POST, FNewAccount::USERNAME), filter_input(INPUT_POST, FNewAccount::EMAIL), $aID);
                 //Set up hard drive with the disk manager.
            }else{
                    //If there were any errors roll back
                $db->m_db_handle->rollBack();
            }
        } catch (Exception $ex) {
            $db->m_db_handle->rollBack();
            echo $ex;
            //note in log
            //redirect to database error page.
        }
    }
    
    protected function VerifyNewCreator($username, $addr, $accountID){
    
        $verify = MToken::Get();
        $token = $verify->CreateAccountToken($accountID);

        //If the table is created then email the user with the special token.        
        if(count($token)=== 3 ){
            $mm=MMail::Get();  
            $mm->SendVerification($addr, $username, $token['ticket'], $token['token']);
        }
    }
}//End of Class

