<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
    include_once $path. "/jot-edit/scripts/php/stCore/stCore.php";
    include_once $path. "/jot-edit/scripts/php/manager/MMail.php";
    include_once $path. "/jot-edit/scripts/php/manager/MToken.php";

   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all account functionality
    * 
    * @param $m_mManager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    * @param $m_headers The email header.
    *   
    * @note This is a singleton class used to group functionality.
    */
class MAccount {
    
        //consts that equal account states.
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const BANNED = 'banned';
    
        //members
    private static $m_amanager; //The instance of its self
    protected $m_db_link;       //Link to the database handle
 
       /**
        * @brief private constructor for creating an account manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */     
    private function __construct() {
        
        $this->m_db_link = DB_Manager::GetInstance();   
    }
    
       /**
        * @brief Get the instance of the account manager.
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
        * @brief Create creator account.
        * @note This is a link table that joins a person account with their 
        * various presona accounts.
        * @param string $accountID The account id number associated with the login.
        * @param string $creatorID The creator id linked with this account. 
        */     
    public function CreateCreatorAccount($accountID, $creatorID){
        
                 //Get the instance of the data base connection. 
        $sql = "INSERT INTO gcreator_account(accountID, creatorID) VALUES(:aID, :cID)";
        $stmt = $this->m_db_link->m_db_handle->prepare($sql);
        $stmt->bindValue(':aID', $accountID, PDO::PARAM_STR);
        $stmt->bindValue(':cID', $creatorID, PDO::PARAM_STR);        
        $stmt->execute();         
    }
    
       /**
        * @brief Process creator registration.
        * @param string $type The destination email address.
        * @param string $fname The recipients username.
        */   
    public function CreateAccount($fname, $sname){
           
        $sql = "INSERT INTO gaccount(given, family) VALUES(:fname, :sname)";
        $stmt = $this->m_db_link->m_db_handle->prepare($sql);
        $stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindValue(':sname', $sname, PDO::PARAM_STR);
        $stmt->execute(); 
    }
    
       /**
        * @brief Insert new accoutn status.
        * @param string $accountID The account id number.
        * @param string $status The status of the account.
        */    
    public function NewAccountStatus($accountID, $status = MAccount::PENDING ){
          
            $sql = "INSERT INTO gaccount_status(status, accountID) VALUES(:status, :accID)";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':accID', $accountID, PDO::PARAM_STR);
            $stmt->execute(); 
    }

       /**
        * @brief Update account status.
        * @param string $accountID The account id number.
        * @param string $status The status of the account.
        */     
    public function UpdateAccountStatus($accountID, $status = MAccount::ACTIVE){
           
         try {
            $sql =  "UPDATE gaccount_status aStatus SET aStatus.status=:status  WHERE aStatus.accountID = :id";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':id', $accountID, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            
            $stmt->execute();   
            if($stmt->rowCount() == 1) { return( TRUE ); }
            else { return ( FALSE ); }
            
         } catch (Exception $ex) {
            //Go to dedicated server error page with error.
         }  
    } 
    
       /**
        * @brief Start the accout creation email verification process.
        * @param string $username The username for the account.
        * @param string $addr The email address to send the verification to. 
        * @param string $accountID The account id number associated with the login.
        */       
    private function StartEmailVerification($username, $addr, $accountID ) {      
        
        $verify = MToken::Get();
        $token = $verify->CreateAccountToken($accountID);
        
             //If the table is created then email the user with the special token.        
        if(count($token)=== 3 ){
            $mm=MMail::Get();  
            $mm->SendVerification($addr, $username, $token['ticket'], $token['token']);
        }

       /* echo "token 1 :" . $token;*/
    }
        
       
        
      public function GetAccountIDByUsername($username){
        try {
           $sql = "SELECT accountID FROM glogin WHERE username = :username";
           $stmt = $this->m_db_link->m_db_handle->prepare($sql);
           $stmt->bindValue(':username', $username, PDO::PARAM_STR);

           if(!$stmt->execute() || $stmt->rowCount() !== 1){ return (FALSE);}

           $accountInfo = $stmt->fetch(PDO::FETCH_ASSOC);

           return($accountInfo['accountID']);


       } catch (Exception $ex) {
           //redirect to database error hadle page.
           echo $ex;      
       }    
    }  
    
    
    public function GetAccountIDByEmail($email){
        try {
           $sql = "SELECT accountID FROM gcontact_email WHERE email = :email";
           $stmt = $this->m_db_link->m_db_handle->prepare($sql);
           $stmt->bindValue(':email', $email, PDO::PARAM_STR);

           if(!$stmt->execute() || $stmt->rowCount() > 1){ return (FALSE);}

           $accountInfo = $stmt->fetchAll();

           return($accountInfo['accountID']);


       } catch (Exception $ex) {
           //redirect to database error hadle page.
           echo $ex;      
       }    
    }
    
    public function UpdateName($aid, $fname, $sname){
       
         try {
            $sql =  "UPDATE gaccount acc SET acc.given=:fname, acc.family=:lname  WHERE acc.id = :id";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':fname', $fname, PDO::PARAM_INT);
            $stmt->bindValue(':lname', $sname, PDO::PARAM_STR);
            $stmt->bindValue(':id', $aid, PDO::PARAM_STR);
            
            $stmt->execute();   
            if($stmt->rowCount() == 1) { return( TRUE ); }
            else { return ( FALSE ); }
            
         } catch (Exception $ex) {
            //Go to dedicated server error page with error.
             echo $ex;
         }             
    }
    
    public function GetName($id){
          try {
           $sql = "SELECT given, family FROM gaccount WHERE id = :id";
           $stmt = $this->m_db_link->m_db_handle->prepare($sql);
           $stmt->bindValue(':id', $id, PDO::PARAM_STR);

           $stmt->execute();
           
           if( $stmt->rowCount() < 1){ return (FALSE);}

          return($stmt->fetchAll());


       } catch (Exception $ex) {
           //redirect to database error hadle page.
           echo $ex;      
       }          
    }
    
    
    
    
    
}//End of class
