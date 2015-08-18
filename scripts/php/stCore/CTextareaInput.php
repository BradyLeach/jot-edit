<?php

    /**
    * @author Brady Leach
    * @date 06/05/2015
    * @class CTextarea
    * 
    * @brief A php object for encapsulating html5 form select functionality.    
    * 
    * @param string m_name The name of the CFormSelect object.  
    * @param string m_textarea The handle to the CFormTextarea object.   
    *  
    * @note The parameters for this class and other classes like this are to 
    * weakly enforce valid html5 generation.
    * 
    * @note The form is is added to the select input when it is added to a form. 
    * This avoids having to know the id of the form this select node is being 
    * added to.
    * 
    * 
    *  @see __Parent for further details.
    * 
    */
class CTextareaInput extends CFormInputBase {
         
       /**
        * @brief Constructor - Creates a FormInputNode.
        * 
        * @param string $id A user entered identification string.[If not supplied one will be generated]. 
        * @param array $attributes [attribute=>value] An array of html tag attributes.
        * @param string $name The name of the CFormSelect object.
        * @param string $labelText The label text for the CFormSelect object.
        * 
        * @todo add in type and other error checking and validation.  	
        */
    function __construct($id, $attributes, $name, $labelText) {        
        parent::__construct($id, $labelText);
                     
            //Store the type and name for helper functions.
        $this->m_name = $name;
        
            //Set up the actual input tag.
        $this->m_formMember_handle = new CText($id, "textarea", $attributes, "");
        $this->m_formMember_handle->AddAttribute("name", $this->m_name);
        $this->AddChild($this->m_formMember_handle);

    }
    
           
        /**
        * @brief Makes the  form input sticky by checking if its been set.
        * 	
        */
    public function MakeSticky() {

            //Set the stickey value for the act name input
        if( filter_input(INPUT_POST, $this->m_name) ) {
            $this->m_formMember_handle->SetText(filter_input(INPUT_POST, $this->m_name)); 
        } 
    }
}