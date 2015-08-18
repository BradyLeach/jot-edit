<?php

   /**
    * @author Brady Leach
    * @date 09/04/2015
    * @class CFormInputBase
    * 
    * @brief A php object for encapsulating html5 form input functionality.    
    * 
    * @param string $m_formMember_handle The input field of the form.
    * @param string m_name The name of the FormInput object.    
    *  
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
    * 
    *  @todo Add in error flag functionality.
    */
class CFormInputBase extends CText {

     protected $m_formMember_handle;    ///The input field of the form.
     protected $m_name;                 ///The inputs name value.
        /**
        * @brief Constructor - Creates a FormInputNode.
        * 
        * @param string $id A user entered identification string.[If not supplied one will be generated]. 
        * @param string $labelText The label text for the FormInput object.
        * 
        * @todo add in type and other error checking and validation.  	
        */
    function __construct($id, $labelText) {        
        parent::__construct($id . "_label", "label", NULL, $labelText);
    }
    
       /**
        * @brief Gets the name of this input.
        * @return string the name of the input.      
        * 	
        */
    public function GetName() {
        return($this->m_name);
    }
    
       /**
        * @brief Gets the name of this input.
        * @return string the name of the input.      
        * 	
        */
    public function SetName($name) {
        if($name && strlen($name)>0) {
            $this->m_name = $name;
            $this->m_formMember_handle->AddAttribute("name", $name);
        }
    }
    
       /**
        * @brief Gets the form member handle.
        * @return the handle to the formMember.      
        * 	
        */
    public function GetInput() {
        return($this->m_formMember_handle);
    }
    
        /**
        * @brief Makes the form input sticky by checking if its been set.
        * @note Children will override this function with their own implementation.            
        * 	
        */
    public function MakeSticky() {}
    
       /**
        * @brief Set the form that thie input belongs too.
        * @note Children will override this function with their own implementation.            
        * 	
        */
    public function SetForm($form) {
        
    }

    public function Validate(){}
    
}//End of FormInputNode Class.



/*
 01   click add button
 02       display content option
 03       select content option
 04          display input form
 05          Get input
 06          process input
 07              client side form verification
 08              true ? go to 09 : return to 04;
 09                 Server side verification             
 10                 true ? go to 11 : return to 04; 
 11                     try add content to data base
 12                     true ? go to 13 : return to 04 with error;
 13                         Try add content to postContent 
 14                            
 */




