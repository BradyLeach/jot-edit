<?php
    $path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
    include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
    include_once $path . '/jot-edit/scripts/php/manager/MMail.php';
    include_once $path . '/jot-edit/scripts/php/manager/MToken.php';
    include_once $path . '/jot-edit/scripts/php/form/FInputs.php';

   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all user login functionality
    * 
    * @param $m_amanager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    *   
    * @note This is a singleton class used to group functionality.
    */
class MLogin {

        //members
    private static $m_cmanager; //The instance of its self
    protected $m_db_link;       //Link to the database handle
 
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
        * @brief Get the instance of the login manager.
        * @return An instance of the login manager.              
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
    
    
       /**
        * @brief Create User login details.
        * @param string $username The username for the account.
        * @param string $password The password for the account. 
        * @param string $accountID The account id number associated with the login.
        */  
    public function CreateLogin($username, $password, $accountID) {
          
                 //Get the instance of the data base connection. 
        $sql = "INSERT INTO glogin(username, password, accountID) VALUES(:uname, :key, :aID)";
        $stmt = $this->m_db_link->m_db_handle->prepare($sql);
        $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
        $stmt->bindValue(':key', password_hash ($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $stmt->bindValue(':aID', $accountID, PDO::PARAM_STR);
        
        $stmt->execute();  
    }
    
    
        /**
        * @brief Create User login details.
        * @param string $username The username for the account.
        * @param string $key The password for the account. 
        */      
    public function UpdatePassword($username, $key){
        try{
                //Get the instance of the data base connection. 
            $stmt = $this->m_db_link->m_db_handle->prepare("UPDATE glogin SET password=:key WHERE username=:uname;");
            $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
            $stmt->bindValue(':key', password_hash ($key, PASSWORD_BCRYPT), PDO::PARAM_STR);

            $stmt->execute();
            if($stmt->rowCount() === 1){return( TRUE );}
            
            return( FALSE );
            
        } catch(Exception $ex){
            echo $ex;
        }
    }
    
        
       /**
        * @brief A check to see if a username is taken.         
        * @return TRUE if the user name is taken, otherwise FALSE.            	
        */     
    public function UsernameTaken($username) {
        $user = $this->GetLoginId($username);       
        if($user===FALSE){ return( FALSE );}
        return( TRUE );
    }
   
    
       /**
        * @brief Create User login id.
        * @param string $username The username for the account.
        * @param string $password The password for the account. 
        * @param string $accountID The account id number associated with the login.
        */  
    private function GetLoginId($username) {
         try{
                //Get the instance of the data base connection. 
            $sql = "SELECT id FROM glogin WHERE username=:uname;";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':uname', $username, PDO::PARAM_STR);
            if(!$stmt->execute() || $stmt->rowCount() !== 1){ return( FALSE ); }
            
            $id = $stmt->fetch();
            return($id['id']);
         } catch (Exception $ex) {
               echo $ex;
               //note in log
               //redirect to error page.
         } 
    }
    
    
    public function ClearFailedLoginRecord($username){
        try{
            $am = MAccount::Get();
            $id = $am->GetAccountIDByUsername($username);
        
            $qry = 'DELETE FROM gaccess_attempt WHERE userID=:id';        
            $stmt = $this->m_db_link->m_db_handle->prepare($qry);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            
         } catch (Exception $ex) {
               echo $ex;
               //note in log
               //redirect to error page.
         } 
        
    }
 
    
    
}//End of class.
    
    