<?php

    /**
    * @author Brady Leach
    * @date 06/05/2015
    * @class CFormSelect
    * 
    * @brief A php object for encapsulating html5 form select functionality.    
    * 
    * @param string m_name The name of the CFormSelect object.    
    *  
    * @note The parameters for this class and other classes like this are to 
    * weakly enforce valid html5 generation.
    * 
    * @note The form is is added to the select input when it is added to a form. 
    * This avoids having to know the id of the form this select node is being 
    * added to.
    * 
    * @note example usage 
    *      //Create CHtml
    *      $formSelect = new FormSelectNode($id, $attributes)
    * 
    *      //Example Source 
    *       $formNode = new Form("myFormName", array("method"=>"post"));
    * 
    *       //To Add child objects individually  
    *       $formNode->AddLabelSelect($myFormSelectObject);
    * 
    *       //To output a MediaNode 
    *       $formNode->Output();
    * 
    * 
    *  @see __Parent for further details.
    * 
    */
class CSelectInput extends CFormInputBase {
    
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
    function __construct($id, $attributes, $name, $labelText, $placeholderText) {        
        parent::__construct($id, $labelText);
               
            //Store the type and name for helper functions.
        $this->m_name = $name;
        
            //Set up the actual input tag.
        $this->m_formMember_handle = new CHtml($id, "select", $attributes);
        $this->m_formMember_handle->AddAttribute("name", $this->m_name);
        $this->AddChild($this->m_formMember_handle);
        
        $this->AddOption('placeholder',$placeholderText, 'placeholder');

    }
    
            /**
        * @brief Add an option to the select list.
        * 
        * @param string $option The option to be added to the list. 
        * @todo add in type and other error checking and validation.  	
        */    
    public function AddOption($id, $label, $value) {
        if($id &&  $label && $value) {
            $this->m_formMember_handle->AddChild(new CText($id, "option", array('value'=>$value), $label));
        }
    }
    
    public function SetSelected($id){
        if(array_key_exists($id, $this->m_formMember_handle->m_children)){
            $option = $this->m_formMember_handle->m_children[$id];
            $option->m_tag->AddAttribute('selected','selected');
        }
        else{
            $option = $this->m_formMember_handle->m_children['placeholder'];
            $option->m_tag->AddAttribute('selected','selected');
        }
    }
    
    
        /**
        * @brief Add a list of options to the select list.
        * 
        * @param array $options The option to be added to the list.   	
        */    
    public function AddOptions($options) {
        
            //If the array is valid and not empty add tag attributes.
        if(is_array($options) && !empty($options)) {

                //For each of the options.
            foreach( $options as $option) {
                $this->AddOption($option);
            }//End foreach
        }//End if
    } 
    
    private function RealOption($value){
        $options = $this->m_formMember_handle->m_children;
        if($options){
            foreach( $options as $option){
                if($option->m_tag->GetAttribute('value') == $value){
                    return(TRUE);
                }
            }
        }
        
        return(FALSE);
    }
    
    public function Validate() {
        /*
        $selected = filter_input(INPUT_POST, $this->m_name);
        
        if(!$selected){return(FALSE);}
        
        
        if( is_a($selected, 'array')) {
            foreach($selected as $selection ){
        
                if($this->RealOption($selection->GetAttribute('value'))){
                    break;
                }else{
                    return(FALSE);
                }
            }
            
            return(TRUE);
        }else{
            if($this->RealOption($selection->GetAttribute('value'))){
                   return(TRUE);
            }
            else{return(FALSE);}
        }
         * 
         */
        
         $selected = filter_input(INPUT_POST, $this->m_name);
        
        if(!$selected || $selected =='placeholder'){return(FALSE);}
        
        if($this->RealOption($selected)){
                   return(TRUE);
        } else{return(FALSE);}
        
    }

}//End of Class.


