<?php
//Include parent class explisitly.
	include_once "CHtml.php";
	 
    
   /**
    * @author Brady Leach
    * @date 09/04/2015
    * @class CForm
    * 
    * @brief A php object for handling html forms.
    * @param bool $m_errorFlag True if an input has raised an error otherwise false.
    * @param bool $m_processed Set to true once the from has been succesfully processed.      
    * @param array $m_errors An array of errors related to the form. 
    * @note $M_errors is used in addition to the input error system. 
    * If you have input errors surpressed you can display form errors.
    * They will be displayed after the final form input
    *  
    * @note example usage 
    * 
    * 
    *      //Create CHtml
    *      $formNode = new CForm($id, $attributes)
    * 
    *      //Example Source 
    *       $formNode = new CForm("myFormName", array("method"=>"post"));
    * 
    *       //To Add child objects individually  
    *       $formNode->AddChild($myFormInputObject);
    * 
    *       //To output a MediaNode 
    *       $formNode->Output();
    * 
    * 
    *  @see __Parent for further details.
    * 
    */
class CForm extends CHtml {

    protected $m_errorFlag; ///True if an input has raised an error otherwise false.
    protected $m_processed; ///Set to true once the from has been succesfully processed
    protected $m_errors;    ///An array of errors related to the form. 
      
       /**
        * @brief Constructor - Creates a CForm.
        * 
        * @param string $id A user entered identification string.[If not supplied one will be generated]. 
        * @param array $attributes An array of html tag attributes.
        *
        * @note There is no id checking in place. May add at a later date. The 
        * user should make sure non generated ids are unique.
        * 
        * @todo Add more robust functionality.                
        * @todo Fix up the form system, the overall design is poor, and inefficient.	
        */
    public function __construct( $id, $attributes ) {
            
            //Set the parent
        parent::__construct( $id, "form", $attributes );
        
        $this->m_errorFlag = FALSE;
        $this->m_processed = FALSE;
        
        $this->AddChild(new CXhtml('', "input", array('type'=>'hidden', 'name'=>'key', 'value'=>$id)));
        
    }
 

       /**
        * @brief Inserts an array of FormInputs into the form.
        *  @param array $inputArray An array of FormInput objects to be added to this 
        * @todo Add error checking.                
        * 	
        */
    public function InsertInputs($inputArray){
        
       foreach($inputArray as $input){
           $this->InsertInput($input);
       }
    }
                 
            
       /**
        * @brief Insert a FormSelectNode into the form.
        * @param FormInput $input A FormInput object to be added as a child. 
        * @todo Add error checking.                
        * 	
        */
    public function InsertInput($input) {
        
        if( $input && is_a($input, "CFormInput", FALSE)) {
                //If the input is set and it is the correct type insert it.
            $this->InsertInputAsChild($input);
        }
        elseif( $input &&  is_a($input, "CSelectInput", FALSE) || ( is_a($input, "CTextareaInput") )){
                //If the input is set and it is a select list insert it.
            $this->InsertSelectAsChild($input);
        }
        elseif($input && is_a($input, "CButton", FALSE)) {
                //Add the input as a child of the form.
            $this->AddChild($input);
        }
    }
    
    
       /**
        * @brief Insert a FormInput into the form.
        * @param FormInput $input A FormInput object to be added as a child. 
        * @todo Add better dynamic error system.                 
        * 	
        */
    private function InsertInputAsChild($input) {
            //Add the input as a child of the form.
         $this->AddChild($input);
         
            //If input error flagged, add error pane to the form.
        if($input->HasError() && $input->CanShowError())
        {
           $this->AddChild(new CText($input->GetID() . "_e", "div", array("class"=>"form_error"), $input->GetErrorMsg()));
        }
    }
    
       /**
        * @brief Add an error message to a list of form error messages.        * 
        * @param string $message An error message to be added to the forms list of error messages. 
        * @todo Add better dynamic error system.
        * @todo Add function to write to log.                 
        * 	
        */
    protected function SetFormError($message) {
        if(!$message){return;}
      
        $this->AddChild(new CText("", "div", array('class'=>'form_error'), $message));        
    }
    
    
       /**
        * @brief Insert a FormSelectNode into the form.
        * @param FormInput $input A FormInput object to be added as a child. 
        * @todo Add better dynamic error system.                 
        * 	
        */
    private function InsertSelectAsChild($input) {
            
            //Add the forms id to the select list.
       $input->GetInput()->AddAttribute("form", $this->GetID());
            
            //Add the select node to the form.           
        $this->AddChild($input);
    }
    
               
        /**
        * @brief Check to see if the fom is ready to be validated.              	
        */
    protected function CanValidate() {
        
            //If the form has been post
        if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST'){
            return(filter_input(INPUT_POST, 'key') === $this->m_id);
        }
        return(FALSE);
    }
    
    public function SetErrorFlag() {
       $this->m_errorFlag = TRUE; 
    }
    
    public function HasErrorFlag() {
       return($this->m_errorFlag ); 
    }
    
    public function ClearErrorFlag() {
        $this->m_errorFlag = FALSE;
    }
    
        /**
        * @brief Validate the form.
        * @note This will be implemented by CForm children.               	
        */
    protected function Validate(){}
    
    
    public function Processed(){
        return( $this->m_processed );
    }
}//End of CForm Class