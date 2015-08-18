<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccess.php';

   /**
    * @author Brady Leach 
    * @date 23/06/2015
    * @brief This is a high level class built to simplify page template generation.
    * @note This object hold the base structure and main navigation menu for the 
    * green and gold records web page. It enables us to add in content during page generation as render is call 
    * after the entir page has been built.
    * 
    * @note this is a test class to define ways to stream line page generation 
    * in a dynaic state.  
    */
class PToolbar extends CDiv{

    
        /**
         * @breif Initializes the page footer.
         * @todo Fix this function so it is loaded from a configur file.
         * more a tester at this point.
         */        
    public function __construct() {
        parent::__construct("toolbar", "");
        //$this->Init();
        
    }
    
    
    private function Init(){
        $am = MAccess::Get();
        
        if($am->HasAccess()){
          //  echo "here"; 
        }
        else{
                //If there is no user logged in then load the default gateway.
           // $this->AddChild(new GNGW_Entry()); 
        }
        //is a user logged in
            //level of user logged in
                //if fan logged load | EXIT | ACCOUNT TOOLS | CREDENTIAL TOOLS
                //if craetor logged load | EXIT | ACCOUNT TOOLS | CREDENTIAL TOOLS | PROFILE TOOLS
                //if admin logged | ADMIN TOOLS
        //else
            //no one logged so just load the ENTRY
        
    }
    
}//End of class