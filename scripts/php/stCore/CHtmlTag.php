<?php
include_once "Helpers.php";

    /**
     * @author   Brady Leach
     * @date     20/01/2014
     * @class    CHtmlTag
     * 
     * @param string $m_element A Html5 element. ie [a, p, section, ...]
     * @param array $attributes An array of html tag attributes.
     * @brief    Encapsulated html tag handles output.  
     * 
     * @note  If the element is not set the object will not be valid.
     * 
     * @note There is no functionality in place to stop the id being edited in 
     * the attribute array. The node id will remain the same.
     *       
     * 
     * 
     *      //Create CHtmlTag
     *      $htmltag = new CHtmlTag( string $element, $attributes[(string $attribute, string $value)])
     * 
     *      //Example Source
     *      $htmltag = new CHtmlTag("a", array("class" => "cssClass", ...);
     * 
     *      //Output the opening tag
     *      $htmltag->OutputOpen();
     * 
     *      //Output the closing tag.
     *      $htmltag->OutputClose();
     * 
     *      //To add an attribute to a tag
     *      $htmltag->AddAttribute(string $attribute, string $value);     
     *  
     * 
     * 
     * @todo Add in a id html5 tag with element and attribute validator.
     * @todo Explore object security by stopping the id attribute being modified.
     * 
     */
class CHtmlTag {
		
		
    protected $m_element; ///A html5 tag ie: [a, p, section, etc]
    protected $m_attributes = array(); ///A list of html attributes and values ie: [class, title, name etc]

    
    /**
     * @author Brady Leach
     * @date 20/01/2014
     * @brief Constructor - Creates a CHtmlTag.
     * 
     * @param string $element A html element [a, p, div etc].
     * @param array $attributes An array of html tag attributes.
     * 
     * @note There is no functionality in place to stop the id being edited 
     * in the attribute array. The Node id will remain the same.
     * 
     * @todo Add in a id html5 tag with element and attribute validator                  
     * 	
     */
    function __construct($element, $attributes) {

        //Assign the element.
            $this->m_element=$element;

        //Add the attributes if any exist.
            $this->AddAttributes($attributes);
    }
	
	
//OUTPUT FUNCTIONS 
	
    
    /**
     * @brief Outputs the opening of a html tag..
     * @todo Add error checking and html5 validation.
     * 
     */
    function OutputOpen() {

            //Output the opening brace.
        echo "<" . $this->m_element;

            //Loop through and display all key pairs.
        foreach($this->m_attributes as $attribute => $value) {
            echo  " " . $attribute . "=\"" . $value ."\"" ;
        }

            //Output the closing brace.
        echo ">";	
    }
		
			

        /**
         * @brief Outputs the closing html tag.
         * @todo Add error checking and html5 validation.
         * 
         */	
    function OutputClose() {
        echo  "</" . $this->m_element . ">" ; 
    }

    
    
    
    
        /**
         * @brief Add an attribute to the current list of attributes.
         * @param string $attribute The attribute key.
         * @param string $value The attributes key value.
         * 
         * @note If a key already exists in the attribute list it will be replaced.
         * @todo Add error checking and html5 validation.
         */
    function AddAttribute($attribute, $value) {
            $this->m_attributes[$attribute] =  $value;
    }
	
    
        /**
         * @brief Add an array of attributes to the current list of attributes.
         * 
         * @param array $attributes Ann array of html attributes to be added to this.
         * 
         * @note If a key already exists in the attribute list it will be replaced.
         * @todo Add error checking and html5 validation.
         */
    function AddAttributes($attributes) {

            //If the array is valid and not empty add tag attributes.
        if(is_array($attributes) && !empty($attributes)) {

                //For each attribute break down the key and value pair.
            foreach( $attributes as $attribute => $value) {

                    //If the attribute is valid add it to the tag.
                    ///@todo add html 5 attribute error checking. 
                if($attribute) {
                    $this->AddAttribute($attribute,$value);
                }
            }//End foreach
        }//End if
    }
    
        /**
         * @brief Set the element parameter for this tag.
         * 
         * @note If a key already exists in the attribute list it will be replaced.
         * @todo Add error checking and html5 validation.
         */
    function SetElement($element){
        $this->m_element = $element ;
    }
    
        /**
         * @brief Get the element parameter for this tag.
         * 
         */
    function GetElement(){
        return $this->m_element ;
    }	
    
         /**
         * @brief Get the attribute value. If it is not set nothign is returned.
         * 
         */
    function GetAttribute($attribute){
        
            //If the attribute has been set in the attribute array return it.
        if( $attribute && array_key_exists($attribute, $this->m_attributes) ) {
            return $this->m_attributes[$attribute];
        }
        
    }
}//End of CHtmlTag Class.



    /**
     * @author Brady Leach
     * @date 20/01/2014
     * @brief Outputs a tag with xhtml style closing.
     * @see CHtmlTag for more detailed usage.
     */
class CXhtmlTag extends CHtmlTag {
    

    /**
     * @brief Outputs the opening of a html tag.
     * @todo Add error checking and html5 validation.
     * 
     */
    function OutputOpen() {

            //Output the opening brace.
        echo "<" . $this->m_element;


            //Loop through and display all key pairs.
        foreach($this->m_attributes as $attribute => $value) {
            echo  " " . $attribute . "=\"" . $value ."\"" ;
        }

        echo "/>";	
    }    
}

	
