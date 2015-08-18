<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MCreator.php';
include_once $path . '/jot-edit/scripts/php/form/FInputs.php';

   /**
    * @author Brady Leach 
    * @date 06/05/2015
    * @brief The New artist sign up form object.   
    * @note This is a high level class used for grouping core functionality
    * for this specific html form. Each form that is used by the green and gold 
    * site will be a class extending the CForm base class.
    */
class FProfile extends CForm {
    
        //address maybe moved to seperate form as it is not really necessary for. 
        //for first step of sign up.
    const NAME       = 'name';
    const LIVE       = 'live';
    const DEACTIVATE = 'deactivate';
    const STATUS     = 'cStatus';
    const SUBMIT     = 'submit';
    const REMOVE     = 'remove';
        //varaibles
    protected $m_formID; ///The forms id.
    protected $m_inputs;  //Holds an array of form input nodes
   
    
    
    
    function __construct($id) {
        parent::__construct( $id.='_address', array('class'=>'page_form', 'method'=>'post') );
        
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

        if( !$this->Processed()){
            //Set all the form inputs we want to retain their value after 
            //form submission.
          $this->MakeStickyInputs();
        }
            //Add inputs to form. 
        $this->InsertInputs($this->m_inputs);        
     
     }
     
     public function SetValues($name){
         
        $this->m_inputs[FProfile::NAME]->GetInput()->AddAttribute('value', $name);   
        
     }
     
    
        /**
        * @brief Initializes all fo the inuts for this form.              
        * 	
        */
    protected function Init() {

        $this->InitProfile();  //Initialize address detail inputs
        $this->InitSubmit();        //Initialize submit inputs
        
            //Set all the form inputs we want to retain their 
            //value after form submission.
        $this->MakeStickyInputs();
    } 
    
    
        /**
        * @brief Initialize address detail inputs.            	
        */      
    private function InitProfile(){
        
        $this->m_inputs[FProfile::NAME] = new FICreatorName('', FProfile::NAME, "Profile Name", TRUE, TRUE);       
      
    }


       /**
        * @brief Initialize login detail inputs.            	
        */      
   private function InitSubmit() {
       
        $this->m_inputs[FProfile::DEACTIVATE] = new CSubmitButton('', array( 'name'=>FProfile::SUBMIT), 'deactivate', 'deactivate');
        $this->m_inputs[FProfile::LIVE] = new CSubmitButton('', array( 'name'=>FProfile::SUBMIT), 'live', 'live');
        $this->m_inputs[FProfile::REMOVE] = new CSubmitButton('', array("name"=>FProfile::SUBMIT), "remove", "remove");

   }

       
       /**
        * @brief Make the inputs that we want to be sticky.            	
        */     
    private function MakeStickyInputs(){
        if($this->CanValidate()){
            foreach($this->m_inputs as $input) {
                if($input && is_a($input, "CKeybordInput", FALSE) && $input->GetType() != "password"){
                        //Make inputs sticky
                    $input->MakeSticky();
                }
            }
        }
    }  
     
     protected function Validate() {
             
        foreach($this->m_inputs as $input){
            if(is_a($input, 'CSubmitButton')){continue;}
            
            if(!$input->Validate()){
                $this->SetErrorFlag();
            }            
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
        if(!$am->HasAccess()){ return; } //redirect to login page

        $cm = MCreator::Get();

        $this->Modify($cm, $_SESSION['userID']);                

    }
    
    
       /**
        * @brief Process the a new address entry.
        * @note The form is processed by calling the various manager classes 
        * that manage data base input.
        */   

    
    private function Modify($addm, $aid, $address){
        $btn = filter_input(INPUT_POST, 'submit');
        
        if($btn === FProfile::REMOVE){       //REMOVE     
            $this->Remove($addm, $aid, $address);  
        }elseif($btn === FProfile::DEACTIVATE){ //DEACTIVATE
            $this->Update($addm, $aid, $this->GetRecordID(), $address);
        }elseif($btn === FProfile::LIVE){   //GO LIVE
            $this->Update($addm, $aid, $this->GetRecordID(), $address);
        }        
    }
    
     private function Update($addm, $aid, $recordID, $address){
        //if profile exists
            //if NOT update profile
                //Set form error
            //else
                //processed true
                //redirect back to fresh page
         
     }
    
    
    private function Remove($addm, $accountID, $address){
        //if profile exists
            //if NOT deactivate profile
                //Set form error
            //else
                //processed true
                //redirect back to fresh page
    }
 
    private function GetRecordID(){
        return(substr($this->m_id, 0, strpos($this->m_id, "_")));
    }    
 
  
}//End of Class