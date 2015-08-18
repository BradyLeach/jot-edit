<?php


   /**
    * @author Brady Leach
    * @date 09/04/2015
    * @class CFormInput
    * 
    * @brief A php object for encapsulating html5 form input functionality.    
    * 
    * @param string $m_type The form input type (ie text, number and submit).
    * @param boolean $m_errFlag True if there has been a validation error.
    * @param string $m_errMsge The message to be displayed in an error.
    * @param boolean $m_showErrMsge True if the error message is to be shown, otherwise false. 
    * @note The parameters for this class and other classes like this are to 
    * weakly enforce valid html5 generation.
    * 
    * @note example usage 
    * 
    * 
    *      //Create CHtml
    *      $formInput = new FormInputNode($id, $attributes)
    * 
    *      //Example Source 
    *       $formNode = new Form("myFormName", array("method"=>"post"));
    * 
    *       //To Add child objects individually  
    *       $formNode->AddLabelInput($myFormInputObject);
    * 
    *       //To output a MediaNode 
    *       $formNode->Output();
    * 
    * 
    *  @see __Parent for further details.
    * 
    */
class CFormInput extends CFormInputBase {
    
    
    protected $m_type;      ///The inputs type.
    protected $m_errFlag;   ///True if there has been a validation error.
    protected $m_errMsge;   ///The message to be displayed in an error.
    protected $m_showErrMsge;///True if the error message is to be shown, otherwise false..
        /**
        * @brief Constructor - Creates a CFormInput.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated]. 
        * @param array $attributes An array of html tag attributes.
        * @param string $type The form input type (ie text, number and submit).
        * @param string $name The name of the CFormInput object.
        * @param string $labelText The label text for the CFormInput object.
        * 
        * @todo add in type and other error checking and validation.  	
        */
    function __construct($id, $attributes, $type, $name, $labelText) {        
        parent::__construct($id , $labelText);
        
        
            //Store the type and name for helper functions.
        $this->m_name = $name;
        $this->m_type = $type;
        $this->m_errFlag = FALSE;
        $this->m_showErrMsge = TRUE;
        
            //Set up the actual input tag.
        $this->m_formMember_handle = new CXhtml($id, "input", $attributes);
        $this->m_formMember_handle->AddAttributes(array("name"=>$this->m_name, "type"=>$this->m_type ));
        $this->AddChild($this->m_formMember_handle);

    }
                
        /**
        * @brief Gets the type of this input.
        * @return string the type of the input.      
        * 	
        */
    public function GetType() {
        return($this->m_type);
    }
    
       /**
        * @brief Sets the type of this input.    
        * @todo add in type check.	
        */
    public function SetType($type) {
        if($type && !IsEmpty($type)){
        $this->m_type = $type;
        }
    }
    
    
    public function HasError() {
        return($this->m_errFlag);
    }
    
    public function SetError() {
        $this->m_errFlag = TRUE;
    }
    
    
    public function ClearError() {
        $this->m_errFlag = FALSE;
    }
    
    
     public function CanShowError() {
        return($this->m_showErrMsge);
    }     
    public function ShowError($show) {
        if(is_bool($show)){
            $this->m_showErrMsge = $show;
        }        
    }    
    
    
       /**
        * @brief Gets the error message that has been set for this input.
        * @return string the type of the input.      
        * 	
        */
    public function GetErrorMsg() {
        return($this->m_errMsg);
    }  
       
    
           /**
        * @brief Gets the error message that has been set for this input.
        * @return string the type of the input.      
        * 	
        */
    public function SetErrorMsg($msg) {
        if($msg && strlen($msg) > 0) {
            $this->m_errMsg = $msg;
        }
    }  
    
    public function SetErrorAndMsg($msg) {
        $this->SetError();
        $this->SetErrorMsg($msg);
    }
    
       /**
        * @brief Makes the  form input sticky by checking if its been set.             
        * 	
        */
    public function MakeSticky() {

            //Set the stickey value for the act name input
        if( filter_input(INPUT_POST, $this->m_name) && !$this->HasError() ) {
            $this->m_formMember_handle->AddAttribute("value", filter_input(INPUT_POST, $this->m_name)); 
        } 
    }    
}//End of Class.



class CKeybordInput extends CFormInput {
    protected $m_required;
    
    function __construct($id, $name, $type, $labelText, $required=TRUE, $showError=FALSE) {
        parent::__construct($id, "", $type, $name, $labelText);
        $this->ShowError($showError);
        $this->m_required = $required;
    }  
    
    
    
        /**
         * @brief Validates the username form input.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    protected function InputValidate($lMin, $lMax, $strRegEx){
            //If field is empty and not required return true
        if(IsEmpty(filter_input(INPUT_POST, $this->m_name)) && !$this->m_required ){
            return( TRUE );
        }
      
        //If field is empty and is required retrurn false
        if( IsEmpty(filter_input(INPUT_POST, $this->m_name)) && $this->m_required){
            $this->SetErrorAndMsg("This field is required.");
            return( FALSE );
        }
        
        //Trim down the string
        $trimed = MyTrim(filter_input(INPUT_POST, $this->m_name));

            //Check the length of the string
        if(!CheckLength($lMin, $lMax, $trimed)) {
            $this->SetErrorAndMsg("The value entered was the incorrect length.");
            return( FALSE );
        }
        //Check the text is valid input
        if(!ValidText($strRegEx, $trimed)){
            $this->SetErrorAndMsg("The value entered contained invalid characters.");
            return( FALSE );               
        }
        
        //else it must be ok so return true
        return( TRUE );      
    }     
        
       
}



class CTextInput extends CKeybordInput {
    
    function __construct($id, $name, $labelText, $required=TRUE, $showError=FALSE) {
        parent::__construct($id, $name, "text", $labelText, $required, $showError);
    }  
    
}




class CEmailInput extends CKeybordInput {
 
        /**
         * @brief Create an email form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */      
    function __construct($id, $name,  $labelText, $required=TRUE, $showError=FALSE) {
        parent::__construct($id, $name,  "email", $labelText, $required, $showError);
    } 
    
        /**
         * @brief Validates the email form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */    
    public function Validate(){
       if($this->InputValidate(FInputConfig::Read('email_min'), FInputConfig::Read('email_max'), FInputConfig::Read('rEx_email'))){
           return( TRUE );
       }  
       return( FALSE );          
    }  
}





class CPasswordInput extends CKeybordInput {
    
    private $m_strength;
    
    function __construct($id, $name,  $labelText, $required=TRUE, $showError=FALSE, $strength='medium') {
        parent::__construct($id, $name,  "password", $labelText, $required, $showError);
        
        $this->m_strength = $strength;
    } 
        /**
         * @brief Validates the email form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */    
    public function Validate(){
        if(!$this->InputValidate(FInputConfig::Read($this->m_strength.'_pword_min'), FInputConfig::Read($this->m_strength.'_pword_max'), FInputConfig::Read('rEx_pword'))){return( FALSE );}
        if(!$this->CheckStrength(filter_input(INPUT_POST, $this->m_name))){return( FALSE );}
           
      
       return( TRUE );          
    } 
    
    
        /**
         * @brief Checks the strength of a password.
         * @note if the strength member has been incorrectly set it will be
         * checked against full strength by default. 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */     
    public function CheckStrength($pword){ 
          switch ($this->m_strength){
            case 'weak':
            return($this->ValidateWeak($pword));

            case 'medium':
            return($this->ValidateMedium($pword));

            case 'strong':
            return($this->ValidateStrong($pword));

            default:
            return($this->ValidateStrong($pword));           
        }//End of switch      
    }
    
        /**
         * @brief Validates A strong password form input.
         * @note passwords strength is as follows:
         *   strong  : Must contain Mixcase alphanumeric characters, a punctuation symbol and be between 8 - 56 characters long;  
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */     
    private function ValidateStrong($pword){
            //If the password contains the correct characters, return TRUE.
        if(HasLowercase($pword, 1) && HasUppercase($pword, 1) && HasDigits($pword, 1) && HasSymbols($pword, 1) ){
            return( TRUE );
        } else{
            $this->SetErrorAndMsg("The password must contain mix case alphabetic characters, digits and symbols");  
            return( FALSE );
        }//End of correct character check.        
    }
    
        /**
         * @brief Validates A medium password form input.
         * @note passwords strength is as follows:
         *   medium  : Must contain mixcase alphanumeric characters and is between 8 - 16 characters long.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */     
    private function ValidateMedium($pword){

                //If the password contains the correct characters, return TRUE.
            if(HasLowercase($pword, 1) && HasUppercase($pword, 1) && HasDigits($pword, 1)){
                return( TRUE );
            } else{
                $this->SetErrorAndMsg("The password must contain mix case alphabetic characters and digits");  
                return( FALSE );
            }//End of correct character check.
                
    }
    
       /**
        * @brief Validates A weak password form input.
        * @note passwords strength is as follows:
        *   weak    : Doesn't enfoce any password requirments and is between 8 - 16 characters long.
        * @return bool TRUE if the a valid username was entered, otherwise FALSE.
        */     
    private function ValidateWeak($pword){
        //If the password contains the correct characters, return TRUE.
        if(HasLowercase($pword, 1) || HasUppercase($pword, 1) || HasDigits($pword, 1) || HasSymbols($pword, 1) ){
            return( TRUE );
        } else{
            $this->SetErrorAndMsg("The password must contain alphabetic characters, digits and/or symbols.");  
            return( FALSE );
        }//End of correct character check.
    }
}






class CNumberInput extends CKeybordInput {
    
    function __construct($id, $name,  $labelText, $required=TRUE, $showError=FALSE) {
        parent::__construct($id, $name,  "number", $labelText, $required, $showError);
    }  
    

        /**
         * @brief Validates the email form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */    
    public function Validate(){
       if($this->InputValidate(0, 255, '/[\d]/')){
           return( TRUE );
       }  
       return( FALSE );          
    }  
    
    
}


class CFileInput extends CKeybordInput {

    function __construct($id, $name,  $labelText, $required=TRUE, $showError=FALSE) {
        parent::__construct($id, $name,  "file", $labelText, $required, $showError);
    }     
    
         /**
         * @brief Validates the email form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */    
    public function Validate(){
       if(!$this->InputValidate(FInputConfig::Read('file_min'), FInputConfig::Read('file_max'), FInputConfig::Read('rEx_file'))){
           return( TRUE );
       }  
       return( FALSE );          
    }     
}



