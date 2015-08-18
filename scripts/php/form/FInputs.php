<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccount.php';
include_once $path . '/jot-edit/scripts/php/manager/MLogin.php';
include_once $path . '/jot-edit/scripts/php/form/FInputConfig.php';
include_once $path . '/jot-edit/scripts/php/stCore/Helpers.php';


    /**
     * @author Brady Leach 
     * @date 10/06/2015
     * @class FInputs
     * 
     * @brief High level form input objects thatare used in one or more forms
     * on the green and gold records site. Each input validates its self by using a 
     * congfig file with parametes set.
     * 
     * @note The id value is also used as the form 'name' value for a majority of the 
     * text input objects. Objects that rely on name groupings do not share this 
     * behaviour.
     * 
     */
//class FInputs extends CTextInput {
    
       /**
        * @brief Constructor for creating a username form input.              
        * @param string $id The value that will be used to set the html id and name value.
        * @param string $labelText The label text for the username input.
        * @param boolean $showError TRUE if the error message generated during 
        * validation is to be displayed, otherwise FALSE.
        * 	
        */    /*
    public function __construct($id, $labelText, $showError=FALSE) {
        parent::__construct($id, "", $id, $labelText);
        
        $this->ShowError($showError);
    } 
    */
    /**
     * 
     * @brief Validate the input. 
     *//*
    public function Validate(){}
    */
    
            /**
         * @brief Validates the username form input.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         *//*
    protected function validate($lMin, $lMax, $strRegEx){        
        if(!filter_input(INPUT_POST, $this->m_name)){
            $this->SetErrorAndMsg("This value can not be empty.");
            return( FALSE );
        }
            //Trim down the string
        $trim_userame = MyTrim(filter_input(INPUT_POST, $this->m_name));
        
            //Check the length of the string
        if(!CheckLength($lMin, $lMax, $trim_userame)) {
            $this->SetErrorAndMsg("The value enetered was the incorrect length.");
            return( FALSE );
        }
        //Check the text is valid input
        if(!ValidText($strRegEx, $trim_userame)){
            $this->SetErrorAndMsg("The value enetered contained invalid characters.");
            return( FALSE );               
        }
        //else it must be ok so return true
        return( TRUE );        
    }
}//End of FInputs Class


*/





   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A username form input.
    * @param boolean $m_newUser TRUE if the input is being used to sign up a new
    * user otherwise false.
    */
class FUsername extends CTextInput {
    
    private $m_newUser; //TRUE if the input is being used to sign up a newuser otherwise false.
                            
       /**
        * @brief Constructor for creating a username form input.              
        * @param string $id The value that will be used to set the html id and name value.
        * @param string $labelText The label text for the username input.
        * @param boolean $showError TRUE if the error message generated during 
        * validation is to be displayed, otherwise FALSE.
        * 	
        */    
    public function __construct($id, $name, $labelText, $required=TRUE, $showError=FALSE, $newUser=FALSE) {
        parent::__construct($id, $name, $labelText, $required, $showError);
        
        $this->m_newUser=$newUser;
    } 
        /**
         * @brief Validates the username form input.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
        
       if(!$this->InputValidate(FInputConfig::Read('uname_min'), FInputConfig::Read('uname_max'), FInputConfig::Read('rEx_uname'))){
           return(FALSE);
       }                
            //Trim down the string
        $trim_userame = MyTrim(filter_input(INPUT_POST, $this->m_name));

        //If its a new user make sure the accoutn doesnt exist.
        if($this->m_newUser){
            if($this->UserExists($trim_userame)){  return( FALSE ); }
        }

        //else it must be ok so return true
        return( TRUE );        
    }   
    
    public function UserExists($trim_userame){
        $lm = MLogin::Get();
        if($lm->UsernameTaken($trim_userame)){
            $this->SetErrorAndMsg("The username enetered has already been taken.");
            return( TRUE );
        }
        return( FALSE );
    }
    
    
}//End of GUsername Class


   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A name form input.
    * 
    */
class FName extends CTextInput {

        /**
         * @brief Validates the name form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
       if($this->InputValidate(FInputConfig::Read('name_min'), FInputConfig::Read('name_max'), FInputConfig::Read('rEx_name'))){
           return( TRUE );
       }  
       return( FALSE );
    }
    
}


   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A unit for an address form input.
    * 
    */
class FUnit extends CTextInput {

        /**
         * @brief Validates the unit part of an address form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
       if($this->InputValidate(FInputConfig::Read('unit_min'), FInputConfig::Read('unit_max'), FInputConfig::Read('rEx_unit'))){
           return( TRUE );
       }  
       return( FALSE );
    }
    
}



   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A unit for an address form input.
    * 
    */
class FAddressLine extends CTextInput {

        /**
         * @brief Validates the unit part of an address form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
       if($this->InputValidate(FInputConfig::Read('addrLine_min'), FInputConfig::Read('addrLine_max'), FInputConfig::Read('rEx_addrLine'))){
           return( TRUE );
       }  
       return( FALSE );
    }
    
}




   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A name form input.
    * 
    */
class FPhone extends CNumberInput {

        /**
         * @brief Validates the name form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
       if($this->InputValidate(FInputConfig::Read('phone_min'), FInputConfig::Read('phone_max'), FInputConfig::Read('rEx_phone'))){
           return( TRUE );
       }  
       return( FALSE );
    }
    
}

   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A name form input.
    * 
    */
class FStNumber extends CNumberInput {

        /**
         * @brief Validates the name form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
       if($this->InputValidate(FInputConfig::Read('stnum_min'), FInputConfig::Read('stnum_max'), FInputConfig::Read('rEx_stnum'))){
           return( TRUE );
       }  
       return( FALSE );
    }
    
}

   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A name form input.
    * 
    */
class FPCode extends CNumberInput {

        /**
         * @brief Validates the name form input.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
       if($this->InputValidate(FInputConfig::Read('pcode_min'), FInputConfig::Read('pcode_max'), FInputConfig::Read('rEx_pcode'))){
           return( TRUE );
       }  
       return( FALSE );
    }
    
}



   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A creator name form input.
    * @note The creator name is displayed as the url to the users home page.
    * So it must be URL safe.
    * 
    */
class FCreatorName extends CTextInput {

    private $m_newCreator; //TRUE if the input is being used to sign up a newuser otherwise false.
                            
       /**
        * @brief Constructor for creating a username form input.              
        * @param string $id The value that will be used to set the html id and name value.
        * @param string $labelText The label text for the username input.
        * @param boolean $showError TRUE if the error message generated during 
        * validation is to be displayed, otherwise FALSE.
        * 	
        */    
    public function __construct($id, $name, $labelText, $required=TRUE, $showError=FALSE, $newCreator=FALSE) {
        parent::__construct($id, $name, $labelText, $required, $showError);
        
        $this->m_newCreator=$newCreator;
    }    
    
        /**
         * @brief Validates the username form input.
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */
    public function Validate(){
        
       if(!$this->InputValidate(FInputConfig::Read('cname_min'), FInputConfig::Read('cname_max'), FInputConfig::Read('rEx_cname'))){
           return(FALSE);
       }                
            //Trim down the string
        $trim_userame = MyTrim(filter_input(INPUT_POST, $this->m_name));

        //If its a new user make sure the accoutn doesnt exist.
        if($this->m_newCreator){
            if($this->CreatorExists($trim_userame)){  return( FALSE ); }
        }

        //else it must be ok so return true
        return( TRUE );        
    }   
    
    public function CreatorExists($trim_userame){
        $lm = MCreator::Get();
        if($lm->CreatorNameTaken($trim_userame)){
            $this->SetErrorAndMsg("The name enetered has already been taken.");
            return( TRUE );
        }
        return( FALSE );
    }   
}




   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief A password form input.
    * @brief High level form input objects thatare used in one or more forms
    * on the greena and gold records site. Each input validates its self by using a 
    * congfig file with parametes set.
    * @param string $strength The strength of the password check.
    * @note The strength relates to how strict the validator will be when checking 
    * the password.
    * passwords strength is as follows:
    *   weak    : Doesn't enfoce any password requirments and is between 8 - 16 characters long.
    *   medium  : Must contain mixcase alphanumeric characters and is between 8 - 16 characters long.
    *   strong  : Must contain Mixcase alphanumeric characters, a punctuation symbol and be between 8 - 56 characters long; 
    * 
    * @note The id value is also used as the form 'name' value for a majority of the 
    * text input objects. Objects that rely on name groupings do not share this 
    * behaviour. 
    *//*
class GPassword extends CPasswordInput {
    private $m_strength; //strength of the password.
    
    
       /**
        * @brief Constructor for creating a username form input.              
        * @param string $id The value that will be used to set the html id and name value.
        * @param string $labelText The label text for the username input.
        * @param boolean $showError TRUE if the error message generated during. 
        * @param boolean $strength The strength of the password. 
        * validation is to be displayed, otherwise FALSE.
        * 	
        *//*      
    public function __construct($id, $labelText, $showError=FALSE, $strength='medium') {
        parent::__construct($id, "", $id, $labelText);
        
        $this->ShowError($showError);
        $this->m_strength=$strength;
    }
      
        /**
         * @brief Validates the password form input depending on the strength.
         * @note The string length and reg ex that controls what is a valid string
         * can be found in the config file.
         * 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */     
/*    public function Validate(){  
        if(filter_input(INPUT_POST, $this->m_name)) {

                //Trim down the string
            $pword = MyTrim(filter_input(INPUT_POST, $this->m_name));

            //If the password is the incorrect length.
            if(!$this->CheckPasswordLength($pword)) {return( FALSE );}      
            
                //Check that the password contains no invalid characters
            if(!ValidText(GVConfig::Read('rEx_pword'), $pword)){return( FALSE );}
            
                //Check that the strength is valid.
            if(!$this->CheckStrength($pword)){return( FALSE );}
            
            
            //If we made it here the password is valid.
            return( TRUE );
            
        }//End of value set check
        return( FALSE ); 
    }
    
        /**
         * @brief Checks the strength of a password.
         * @note if the strength member has been incorrectly set it will be
         * checked against full strength by default. 
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */     
 /*   public function CheckStrength($pword){ 
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
         * @brief Checks the length of a password depending of its config parameters.
         * @return bool TRUE if the length is valid, otherwise FALSE and set error.
         */     
 /*   private function CheckPasswordLength($text){
        
        if(!CheckLength(GVConfig::Read($this->m_strength.'_pword_min'), GVConfig::Read($this->m_strength.'_pword_max'), $text)) {
           $this->SetErrorAndMsg("The password enetered was the incorrect length.");
           return( FALSE );
       } 
       
       return( TRUE );
    } 
          
        /**
         * @brief Validates A strong password form input.
         * @note passwords strength is as follows:
         *   strong  : Must contain Mixcase alphanumeric characters, a punctuation symbol and be between 8 - 56 characters long;  
         * @return bool TRUE if the a valid username was entered, otherwise FALSE.
         */     
  /*  private function ValidateStrong($pword){
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
 /*   private function ValidateMedium($pword){

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
 /*   private function ValidateWeak($pword){
        //If the password contains the correct characters, return TRUE.
        if(HasLowercase($pword, 1) || HasUppercase($pword, 1) || HasDigits($pword, 1) || HasSymbols($pword, 1) ){
            return( TRUE );
        } else{
            $this->SetErrorAndMsg("The password must contain alphabetic characters, digits and/or symbols.");  
            return( FALSE );
        }//End of correct character check.
    }
}*/