<?php
    $path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
    include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
    include_once $path . '/jot-edit/scripts/php/manager/MMail.php';
    include_once $path . '/jot-edit/scripts/php/manager/MToken.php';

   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all user contact functionality
    * 
    * @param $m_amanager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    *   
    * @note This is a singleton class used to group functionality.
    * 
    * @todo Probably need to break this into an email manager and a phone umber manager.
    */
class MContact {
    
        //members
    private static $m_cmanager; //The instance of its self
    protected $m_db_link;       //Link to the database handle
 
       /**
        * @brief private constructor for creating an contact manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */     
    private function __construct() {
        
        $this->m_db_link = DB_Manager::GetInstance();   
    }
    
       /**
        * @brief Get the instance of the contact manager.
        * @return An instance of the contact manager.              
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

    
    
    
    public function GetEmailList($aid){
        try {
            //Get the instance of the data base connection. 
            $stmt = $this->m_db_link->m_db_handle->prepare("SELECT id, email FROM gcontact_email WHERE accountID=:aid");            
            $stmt->bindValue(':aid', $aid, PDO::PARAM_INT);
            $stmt->execute();         
            return($stmt->fetchAll());
        } catch (Exception $ex) {
            echo $ex;
        }              
    }
    
    
    public function EmailExists($aid, $pnum){
        try {
           $sql = "SELECT mail.id FROM gcontact_email mail WHERE mail.accountID=:aid AND mail.email=:email;" ;
           $stmt = $this->m_db_link->m_db_handle->prepare($sql);
           $stmt->bindValue(':aid', $aid, PDO::PARAM_STR);
           $stmt->bindValue(':email', $pnum, PDO::PARAM_STR);

           if(!$stmt->execute() || $stmt->rowCount() >= 1){ return (TRUE);}

           return(FALSE);

       } catch (Exception $ex) {
           //note in log
           //redirect to database error hadle page.
           echo $ex;      
       }    
    }    
    
       /**
        * @brief Add a creator email account.
        * @param string $accountID The account id to create the email address for.
        * @param string $email The token number that allows access to the.
        * @return TRUE is user registered, otherwise FALSE	
        */   
    public function AddEmail($accountID, $email) {
          
        //Get the instance of the data base connection. 
        $sql = "INSERT INTO gcontact_email(email, accountID) VALUES(:email, :aID)";
        $stmt = $this->m_db_link->m_db_handle->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':aID', $accountID, PDO::PARAM_STR);
        $stmt->execute();   
    }
    
    public function UpdateEmail($pid, $email){       
         try {
            $sql =  "UPDATE gcontact_email email SET email.email=:mail WHERE email.id=:id";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':mail', $email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $pid, PDO::PARAM_INT);
                           
            if($stmt->execute() && $stmt->rowCount() == 1) { return( TRUE ); }
            else { return ( FALSE ); }
            
         } catch (Exception $ex) {
            //Go to dedicated server error page with error.
             echo $ex;
         }       
    }
    
       /**
        * @brief Get the email of a user by username.
        * @param string $username The username of the email contact owner.
        * @return The username is returned, otherwise FALSE	
        */   
    public function GetEmail($username){
        try {
           $sql = "SELECT c.email FROM gcontact_email c JOIN glogin l ON l.accountID= c.accountID WHERE l.username = :username";
           $stmt = $this->m_db_link->m_db_handle->prepare($sql);
           $stmt->bindValue(':username', $username, PDO::PARAM_STR);

           if(!$stmt->execute()/* || $stmt->rowCount() !== 1*/){ return (FALSE);}

           $contact = $stmt->fetch();

           return($contact['email']);
       } catch (Exception $ex) {
           //note in log
           //redirect to database error hadle page.
           echo $ex;      
       }    
    }
    
       /**
        * @brief Remove a creator email account.
        * @param string $accountID The account id to create the email address for.
        * @param string $email The email address to be removed.
        * @return TRUE if the email is removed, otherwise FALSE	
        */   
    public function RemoveEmail($aid, $email) {
        try {
            //Get the instance of the data base connection. 
            $sql = "DELETE FROM gcontact_email WHERE accountID=:id AND email=:mail";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':id', $aid, PDO::PARAM_STR);
            $stmt->bindValue(':mail', $email, PDO::PARAM_STR);
                        
             if(!$stmt->execute() || $stmt->rowCount() !== 1){ return (FALSE);}
             else{return(TRUE);}
        } catch (Exception $ex) {
            echo $ex;
        }  
    }
    

    
    
    
    
    
    public function GetPhoneList($aid){
        try {
            //Get the instance of the data base connection. 
            $stmt = $this->m_db_link->m_db_handle->prepare("SELECT id, number FROM gcontact_phone WHERE accountID=:aid");            
            $stmt->bindValue(':aid', $aid, PDO::PARAM_INT);
            $stmt->execute();         
            return($stmt->fetchAll());
        } catch (Exception $ex) {
            echo $ex;
        }              
    }

    
            
    public function PhoneNumberExists($aid, $pnum){
        try {
           $sql = "SELECT pnum.id FROM gcontact_phone pnum WHERE pnum.accountID=:aid AND pnum.number=:num;" ;
           $stmt = $this->m_db_link->m_db_handle->prepare($sql);
           $stmt->bindValue(':aid', $aid, PDO::PARAM_STR);
           $stmt->bindValue(':num', $pnum, PDO::PARAM_STR);

           if(!$stmt->execute() || $stmt->rowCount() >= 1){ return (TRUE);}

           return(FALSE);

       } catch (Exception $ex) {
           //note in log
           //redirect to database error hadle page.
           echo $ex;      
       }    
    }   
    
         /**
        * @brief Add a creator email account.
        * @param string $accountID The account id to create the email address for.
        * @param string $email The token number that allows access to the.
        * @return TRUE is user registered, otherwise FALSE	
        */   
    public function AddPhoneNumber($accountID, $number) {
        try {
            //Get the instance of the data base connection. 
            $sql = "INSERT INTO gcontact_phone(number, accountID) VALUES(:number, :aID)";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':number', $number, PDO::PARAM_STR);
            $stmt->bindValue(':aID', $accountID, PDO::PARAM_STR);
                        
             if(!$stmt->execute() || $stmt->rowCount() !== 1){ return (FALSE);}
             else{return(TRUE);}
        } catch (Exception $ex) {
            echo $ex;
        }
  
    }
    
    public function UpdatePhoneNumber($pid, $pnum){       
         try {
            $sql =  "UPDATE gcontact_phone pnum SET pnum.number=:num WHERE pnum.id=:id";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':num', $pnum, PDO::PARAM_INT);
            $stmt->bindValue(':id', $pid, PDO::PARAM_INT);
                           
            if($stmt->execute() && $stmt->rowCount() == 1) { return( TRUE ); }
            else { return ( FALSE ); }
            
         } catch (Exception $ex) {
            //Go to dedicated server error page with error.
             echo $ex;
         }       
    }
    
    
       /**
        * @brief Add a creator email account.
        * @param string $accountID The account id to create the email address for.
        * @param string $email The token number that allows access to the.
        * @return TRUE is user registered, otherwise FALSE	
        */   
    public function RemovePhoneNumber($aid, $pnum) {
        try {
            //Get the instance of the data base connection. 
            $sql = "DELETE FROM gcontact_phone WHERE accountID=:id AND number=:num";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':id', $aid, PDO::PARAM_STR);
            $stmt->bindValue(':num', $pnum, PDO::PARAM_STR);
                        
             if(!$stmt->execute() || $stmt->rowCount() !== 1){ return (FALSE);}
             else{return(TRUE);}
        } catch (Exception $ex) {
            echo $ex;
        }  
    }
    

    

    
      
    
}//End of class.
    
    