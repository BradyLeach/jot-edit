<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once  $path . '/jot-edit/scripts/php/manager/MAccess.php';
include_once  $path . '/jot-edit/scripts/php/manager/MAccount.php';
include_once  $path . '/jot-edit/scripts/php/form/FInputs.php';

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
class FName extends CForm {
    
        //Consts [mainly to avoid syntax errors when working with form id's]
        //every input that will be in the form will be listed here.
  
    const FNAME  = 'nameGiven';   
    const SNAME  = 'nameFamily'; 
    const SUBMIT = 'submit';
    
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;    //Holds an array of form input nodes
            
       /**
        * @brief Construct the green and gold records log in form. 	
        */   
    function __construct($id='name' ) {
        parent::__construct( $id, array('class'=>'page_form', 'method'=>'post') );
        
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
     
     protected function FrgWex(){
         if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST'){
            return( filter_input(INPUT_POST, 'submit') === FName::REMOVE ||
                    filter_input(INPUT_POST, 'submit') === FName::SUBMIT );
         }
         return(FALSE);
     }
     
    
        /**
        * @brief Initializes all fo the inuts for this form.              
        * 	
        */
    protected function Init() {
        $this->m_inputs[FName::FNAME] = new FIName('',FName::FNAME, "Name",TRUE, TRUE);
        $this->m_inputs[FName::SNAME] = new FIName('',FName::SNAME, "Surname",TRUE, TRUE);  
        $this->m_inputs[FName::SUBMIT] = new CSubmitButton('', array("name"=>FName::SUBMIT), "update", "update");
        

    } 
       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){
        if($this->CanValidate()){
            foreach($this->m_inputs as $input) {
                if($input && is_a($input, "CTextInput", FALSE) && $input->GetType() != "password"){
                        //Make inputs sticky
                    $input->MakeSticky();
                }
            }
        }
    } 
     
    public function SetValues($fname, $lname){
         
        $this->m_inputs[FName::FNAME]->GetInput()->AddAttribute('value', $fname);     
        $this->m_inputs[FName::SNAME]->GetInput()->AddAttribute('value', $lname);     
     }
     
     
        /**
         * @brief Validates the forms inputs.            	
         */ 
    protected function Validate() {         
        if(!$this->m_inputs[FName::FNAME]->Validate() || !$this->m_inputs[FName::SNAME]->Validate()){
            //$this->SetFormError(""); 
            $this->SetErrorFlag();
        }
     }
   
       /**
        * @brief Process the form.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */     
    protected function Process(){
        //Get the access manager
        $am = MAccess::Get();
        
            //If a user is logged
        if($am->HasAccess()){
                //Get the id of the logged user
            if(isset($_SESSION['userID'])) {
                $id = $_SESSION['userID'];
            }
            
            //Update the name
            $accMan = MAccount::Get();                
            if($accMan->UpdateName($id, filter_input(INPUT_POST, FName::FNAME), filter_input(INPUT_POST, FName::SNAME))){
                $this->m_processed = TRUE;
                Redirect('../../../action/account/name');
            }
      
            
            
        }
        
    }
 
    
}//End of Class
