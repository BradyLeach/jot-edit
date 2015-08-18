<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path."/jot-edit/scripts/php/stCore/stCore.php";
include_once $path."/jot-edit/scripts/php/manager/MMail.php";
include_once $path."/jot-edit/scripts/php/manager/MAccount.php";
include_once $path."/jot-edit/scripts/php/manager/MToken.php";

    /**
     * @author Brady Leach 
     * @date 10/06/2015
     * @brief Manages all restricted access functionality
     * 
     * @param $m_amanager this a pointer to its self.
     * @param $m_db_link DBManager Holds handle to the database manager.
     * @param $m_window; int The amount of time an account will be locked.
     * @param $m_tries; int The amount of allowed failed login attempots before the accoutn is locked.   
     * @note This is a singleton class used to group functionality.
     */    
class MAccess {
    
            //members
    private static  $m_amanager;
    protected       $m_db_link;
    private         $m_window = 3600;//The amount of time failed logins will be tracked
    private         $m_tries = 2;    //The max amount to failed log in attempts in the window duration.
    private         $m_locked = FALSE;//TRUE if the account has been loked otherwise FALSE.
    
       /**
        * @brief private constructor for creating a access manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */   
    private function __construct() {
        
        $this->m_db_link = DB_Manager::GetInstance();   
    }

        /**
        * @brief Get the instance of the access manager.
        * @return An instance of the account manager.              
        * 	
        */   
    public static function Get() {
        
        if (!isset(self::$m_amanager))
        {
            $object = __CLASS__;
            self::$m_amanager = new $object;
        }
        return self::$m_amanager;
    }
  
       /**
        * @brief Get the instance of the access manager.
        * @param $username string The username for the account login.
        * @param $key string The password that is paired with the username to gain access to the account. 
        * @return TRUE if access is granted, Otherwise FALSE.              
        * 	
        */       
    public function RequestAccess($username, $key){
        try {
            $userQry = $this->m_db_link->m_db_handle->prepare("SELECT accountID, username, password FROM glogin WHERE username=:uname;");
            $userQry->bindValue(':uname', $username, PDO::PARAM_STR);
            if(!$userQry->execute() || $userQry->rowCount() !== 1 ){ return( FALSE );}
            
            $login = $userQry->fetch();
            
            if(!$this->HitAttemptLimit($login['username'])) {
                          
                if(password_verify($key, $login['password'])) {    
                    $this->InitSession($login['accountID'], $login['username'], $login['password']);
                    return( TRUE );                
                }
            }
            
            $this->FailedAttempt($login['accountID']);
            return( FALSE );

        }catch(Exception $ex){
            //go to db error page
             echo $ex;
        }
    }

       /**
        * @brief Records a failed attempt to access an account.
        * @param $account The account number that has been violated.           
        * 	
        */     
    private function FailedAttempt($account){
        $currentTime= time();
        try{
            $qry = $this->m_db_link->m_db_handle->prepare("INSERT INTO gaccess_attempt(userID, time) VALUES (:user, :now)");
            $qry->bindValue(':user', $account, PDO::PARAM_INT);
            $qry->bindValue(':now', $currentTime, PDO::PARAM_INT);
            $qry->execute();
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    
       /**
        * @brief A check to see if there has been too many failed login attempts.
        * @param $username The username for the account that has been violated.           
        * 	
        */     
    public function HitAttemptLimit($username){
        $period = time() - $this->m_window;

        $userID = $this->GetAccountId($username);
        try{
            $qry = $this->m_db_link->m_db_handle->prepare("SELECT time FROM gaccess_attempt WHERE userID=:user AND time>:ctime;" );
            $qry->bindValue(':user', intval($userID) , PDO::PARAM_INT);
            $qry->bindValue(':ctime', $period, PDO::PARAM_INT);
             
            if($qry->execute() && $qry->rowCount() < $this->m_tries){ return( FALSE ); }

            $this->LockAccount();            
            return( TRUE );            
        } catch (Exception $ex) {
            echo $ex;
            //go to database error page;
        }
    }
    
        /**
        * @brief Set the current access gateway to locked.           
        * 	
        */      
    private function LockAccount(){
        $this->m_locked = TRUE;
    }
    
       /**
        * @brief Check to see if the  current access gateway is locked.           
        * @return TRUE if the account gatway is locked otherwise false.	
        */     
    public function IsAccountLocked(){
        return($this->m_locked);
    }
    
       /**
        * @brief A check to see if there has been too many failed login attempts.
        * @param $userID string The account number for the account. 
        * @param $username string The username for the account.           
        * @param $password string The password for the account.            	
        */    
    private function InitSession($userID, $username, $password){

        require_once 'MSession.php';
                
            // XSS protection as we might print this value
        $userID = preg_replace("/[^0-9]+/", "", $userID);
        $_SESSION['userID'] =  $userID ;
        
            // XSS protection as we might print this value
        $username = preg_replace("/^[^\w$@*-]+$/", "", $username);
        $_SESSION['username'] = $username;
        
        $_SESSION['passPhrase'] = $this->UserPassPhrase($password);       
        
        //init access level
        
    }

       /**
        * @brief A check to see if there has been too many failed login attempts.         
        * @return TRUE if the user has access, otherwise FALSE.            	
        */     
    public function HasAccess() {
        if( $this->SessionInitialized())
        {
            $pass = $this->GetPassword($_SESSION['userID']);
            if(!$pass){return( FALSE );}
            
            if( $_SESSION[ 'passPhrase'] == $this->UserPassPhrase($pass)){
                return( TRUE );
            }               
        }
        return( FALSE );
    }    
    
       /**
        * @brief A check to see if there has been too many failed login attempts.         
        * @return TRUE if the user has access, otherwise FALSE.            	
        */     
    public function HasAdminAccess() {
        if( $this->HasAccess())
        {   
            if( $this->IsAdmin($_SESSION['userID'])){
                return( TRUE );
            }               
        }
        return( FALSE );
    }   
    
    
    private function IsAdmin($aid){
        try{
            $qry = $this->m_db_link->m_db_handle->prepare("SELECT id from gadmin WHERE accountID=:aID;");
            $qry->bindValue(':aID', $aid, PDO::PARAM_INT);
            if(!$qry->execute() || $qry->rowCount() !== 1){ return(FALSE); }
            
            return(TRUE);
            
        } catch (Exception $ex) {
            echo $ex;
        }        
    }

        
       /**
        * @brief Check to see if the session has been initialized.
        * @return TRUE if the session has been properly initialized, otherwise
        * FALSE.           	
        */     
    private function SessionInitialized(){
        return(
             isset($_SESSION['userID']) && 
             isset($_SESSION['username']) &&
             isset($_SESSION['passPhrase']) );
    }

       /**
        * @brief Gets the browser infomration for the current client.
        * @return The browser information for the current user.           	
        */     
    private function GetBrowserInfo() {
        
      //  $v=  filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
        return(filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'));
    }
  
       /**
        * @brief Gets the password infomration for the current client.
        * @return The password for the accountID.           	
        */     
    private function GetPassword($accountID) {
        
        
        try{
            $qry = $this->m_db_link->m_db_handle->prepare("SELECT password from glogin WHERE accountID=:aID;");
            $qry->bindValue(':aID', $accountID, PDO::PARAM_INT);
            if(!$qry->execute() || $qry->rowCount() !== 1){ return; }
            
            $password = $qry->fetch();
            return($password[0]);
            
        } catch (Exception $ex) {
            echo $ex;
        }
    }
 
       /**
        * @brief Gets the UserID infomration for the current client by username.
        * @return The account id for the username, otherwse FALSE.           	
        */ 
    public function GetAccountId($username) {
        try{
            $qry = $this->m_db_link->m_db_handle->prepare("SELECT accountID from glogin WHERE username=:username;");
            $qry->bindValue(':username', $username, PDO::PARAM_STR);
            if(!$qry->execute() && $qry->rowCount() !== 1){ return( FALSE ); }
            
            $id = $qry->fetch();
            return($id['accountID']);
            
        } catch (Exception $ex) {
            echo $ex;
        }
    }
   
       /**
        * @brief Gets the user pass phrase infomration for a user by password.
        * @return The the user pass phrase.           	
        */     
    private function UserPassPhrase($key){
             // Get the user-agent string of the user.
        return(hash('sha512', $key . $this->GetBrowserInfo()));
    }


       /**
        * @brief Log the user out and destry the current session.          	
        */   
    public function Logout(){
        //Start the session
        if(session_status() == PHP_SESSION_NONE){
            //There is no active session
            $session = new CSession();
            $session->start_session('_s', false);
        }

        // Unset all session values 
        $_SESSION = array();

        // get session parameters 
        $params = session_get_cookie_params();

        // Delete the actual cookie. 
        setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

        // Destroy session 
        session_destroy();       
    }
    
   
    
}//End of class