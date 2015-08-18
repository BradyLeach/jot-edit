<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MMail.php';
include_once $path . '/jot-edit/scripts/php/manager/MToken.php';

   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all user login functionality 
    * @param $m_cmanager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.    *   
    * @note This is a singleton class used to group functionality.
    */
class MCreator {

        //members
    private static $m_cmanager; //The instance of its self
    protected $m_db_link;       //Link to the database handle
    protected static $m_status; //List of profile status options   
    
       /**
        * @brief private constructor for creating a login manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */     
    private function __construct() {
        
        $this->m_db_link = DB_Manager::GetInstance();   
    }
    
       /**
        * @brief Get the instance of the creator manager.
        * @return An instance of the creator manager.              
        * 	
        */     
    public static function Get() {
    
        if (!isset(self::$m_cmanager))
        {
            $object = __CLASS__;
            self::$m_cmanager = new $object;
        }
        return self::$m_cmanager;
    }
    
    public function GetStatus(){
        if(!isset(self::$m_status)){
           $this->LoadStatus();
        }
        
        
        if(self::$m_status){
            return(self::$m_status);
        }
    }
    
    private function LoadStatus(){
        try {        

            //If the instance was retrieved succesfully, prepare the statement.
            $stmt =  $this->m_db_link->m_db_handle->prepare("select id from gcreator_status");

            //If the statment was prepared and exicuted correctly. Add all the states to our menu.
            if (!$stmt->execute()) { return;}         

            //load the state list.
            while($state = $stmt->fetch(PDO::FETCH_ASSOC)) {
                self::$m_states[$state["id"]] = $state["id"];
            }  
            
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

       /**
        * @brief Add a creator account.
        * @param string $type The type of account to be created.
        * @param string $name The name of the creator(display name). 
        */    
    public function CreateCreator($type, $name){
        
        $cleanName = ToAscii($name);
       // $cleanName = CleanURL($name);
        //echo $cleanName;
                 //Get the instance of the data base connection. 
        $sql = "INSERT INTO gcreator(name, creatorType, cStatus, url) VALUES(:name, :cType, :status, :url)";
        $stmt = $this->m_db_link->m_db_handle->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR); 
        $stmt->bindValue(':cType', $type, PDO::PARAM_STR);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->bindValue(':url', $cleanName , PDO::PARAM_STR);
        $stmt->execute();         
    }
    
            
       /**
        * @brief A check to see if a username is taken.         
        * @return TRUE if the user name is taken, otherwise FALSE.            	
        */     
     public function CreatorNameTaken($name) {
        $user = $this->GetCreatorId($name);       
        if($user===FALSE){ return( FALSE );}
        return( TRUE );
    }
    
       /**
        * @brief Create User login id.
        * @param string $username The username for the account.
        * @param string $password The password for the account. 
        * @param string $accountID The account id number associated with the login.
        */  
    private function GetCreatorId($creatorname) {
         try{
                //Get the instance of the data base connection. 
            $sql = "SELECT id FROM gcreator WHERE name=:cname;";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':cname', $creatorname, PDO::PARAM_STR);
            if(!$stmt->execute() || $stmt->rowCount() !== 1){ return( FALSE ); }
            
            $id = $stmt->fetch();
            return($id['id']);
         } catch (Exception $ex) {
               echo $ex;
               //note in log
               //redirect to error page.
         } 
    }
    
    public function GetProfileList($aid){
        try {
            //Get the instance of the data base connection. 
            $stmt = $this->m_db_link->m_db_handle->prepare("SELECT c.id, c.name, c.creatorType, c.cStatus, c.url  FROM gcreator c JOIN gcreator_account ca ON c.id = ca.id WHERE accountID=:aid");            
            $stmt->bindValue(':aid', $aid, PDO::PARAM_INT);
            $stmt->execute();         
            return($stmt->fetchAll());
        } catch (Exception $ex) {
            echo $ex;
        }              
    }

}//End of class.
    
    

