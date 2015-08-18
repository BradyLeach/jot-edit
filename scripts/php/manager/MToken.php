<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path. "/jot-edit/scripts/php/stCore/stCore.php";
include_once $path. "/jot-edit/scripts/php/manager/MMail.php";
include_once $path. "/jot-edit/scripts/php/manager/MAccount.php";


   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all verification token functionality
    * 
    * @param $m_tManager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    *   
    * @note This is a singleton class used to group functionality.
    */        
class MToken {
    
            //members
    private static $m_tManager; //The instance of its self
    protected $m_db_link;       //Link to the database handle
    
    
       /**
        * @brief private constructor for creating an verification token manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */      
    private function __construct() {
        
        $this->m_db_link = DB_Manager::GetInstance();   
    }

        /**
        * @brief Get the instance of the token manager.
        * @return An instance of the account manager.              
        * 	
        */    
    public static function Get() {
    
        if (!isset(self::$m_tManager))
        {
            $object = __CLASS__;
            self::$m_tManager = new $object;
        }
        return self::$m_tManager;
    }
    
       /**
        * @brief Create a verification token pair.
        * @param int $lifespan The life span of the token.
        * @note The token pair that is returned is a ticket-token-id
        * @return An array containing a token, ticket, and token pair id.	
        */   
    public function CreateToken ($lifespan=86400){
        //Create the verification key.
        $tokenPair['ticket']    = $this->GenerateHexToken();
        $tokenPair['token']     = $this->GenerateHexToken();        
        $tokenPair['id']        = $this->InsertTokenRecord( $tokenPair['ticket'], $tokenPair['token'], $lifespan);
        return($tokenPair);
    }

       /**
        * @brief Create a account verification token pair.
        * @param string $key The account key that the token is being generated for. 
        * @param int $lifespan The life span of the token.
        * @note The token pair that is returned is a ticket-token-id
        * @return An array containing a token, ticket, and token pair id.	
        */       
    public function CreateAccountToken ($key, $lifespan=86400){
        //Create the verification key.
        $tokenPair['ticket']    = $this->GenerateHexToken();
        $tokenPair['token']     = $this->GenerateHexToken(); 
        $tokenPair['id']        = $this->InsertaccountTokenRecord($key, $tokenPair['ticket'], $tokenPair['token'], $lifespan);
        return($tokenPair);
    }
     
       /**
        * @brief Get an account id that is associated token pair.
        * @param string $ticket The ticket number portion of the token pair. 
        * @param int $token The token compnent for the token pair.
        * @return The account id associated with the token pair. 
        * If no account is attached to the token, False is returned. 	
        */   
    public function GetAccountTokenKey($ticket, $token) {
        //if the token is null bail out
        if(!$this->ValidToken( $ticket, $token ) ){return( FALSE );}
        
        //Get the record if one exists for thie token.
        $record = $this->SelectAccountTokenRecord($ticket,$token);
        
        if(count($record) !== 3){return(FALSE);}
        
        if(!$this->StillAlive($record['ttl'], $record['born'])){
            $this->DeleteTokenRecord($ticket, $token);
            return( FALSE );            
        }
        
        return($record['account']);
    }

       /**
        * @brief Validate a ticket-token pair.
        * @param string $ticket The ticket number portion of the token pair. 
        * @param int $token The token compnent for the token pair.
        * @return TRUE if the token is valid, Otherwise FALSE. 	
        */    
    public function ValidateToken($ticket, $token){
        //if the token is null bail out
        if(!$this->ValidToken( $ticket, $token ) ){return( FALSE );}
        //Get the record if one exists for thie token.
        $record = $this->SelectTokenRecord($ticket,$token);
        
        //If the record does not contain the correct number of variables, bail out
        if(count($record) !== 2){return(FALSE);}
        //If the token has expired, bail out.
        if(!$this->StillAlive($record['ttl'], $record['born'])){
            $this->DeleteTokenRecord($ticket, $token);
            return( FALSE );            
        }
        //If we made it here the token is valid.
        return( TRUE );        
    } 
    
        /**
        * @brief Validate the account ticket-token pair.
        * @param string $ticket The ticket number portion of the token pair. 
        * @param int $token The token compnent for the token pair.
        * @return TRUE if the token is valid, Otherwise FALSE. 	
        */    
    public function ValidateAccountToken($ticket, $token){
        //if the token is null bail out
        if(!$this->ValidToken( $ticket, $token ) ){return( FALSE );}
        //Get the record if one exists for thie token.
        $record = $this->SelectAccountTokenRecord($ticket,$token);


        //If the record does not contain the correct number of variables, bail out
        if(count($record) !== 3){return(FALSE);}

        //If the token has expired, bail out.
        if(!$this->StillAlive($record['ttl'], $record['born'])){

            $this->DeleteTokenRecord($ticket, $token);
            return( FALSE );            
        }
        //If we made it here the token is valid.
        return( TRUE );        
    } 

    
        /**
        * @brief Destroy a token by token.
        * @param string $ticket The ticket number portion of the token pair. 
        * @param int $token The token compnent for the token pair.
        * @return TRUE if the token is valid, Otherwise FALSE. 	
        */     
    public function DestroyToken($ticket, $token){
         //if the token is null bail out
        if(!$this->ValidToken($ticket, $token ) ){return( FALSE );} 
        return($this->DeleteTokenRecord($ticket, $token));
    }
    
    
        /**
        * @brief Tests to see if an account token is set.
        * @param string $id The account id associated with the verification token.
        * @return TRUE if the account token is set, Otherwise FALSE. 	
        */ 
    public function IsAccountTokenSet($id) {
        try {
            $stmt = $this->m_db_link->m_db_handle->prepare("SELECT account FROM gtoken_account where account=:id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT); 
            $stmt->execute(); 
            
            if($stmt->rowCount() === 1){ return( TRUE ); } 
            
            return( FALSE );
            
        } catch (Exception $ex) {
            //redirect to db error page
            echo $ex;
        }    
    }    
    
            /**
        * @brief Tests to see if an account token is set.
        * @param string $id The account id associated with the verification token.
        * @return TRUE if the account token is set, Otherwise FALSE. 	
        */ 
    public function GetAccountTokenSet($id) {
        try {
            $stmt = $this->m_db_link->m_db_handle->prepare("SELECT account FROM gtoken_account where account=:id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT); 
            $stmt->execute(); 
            
            if($stmt->rowCount() === 1){ return( TRUE ); } 
            
            return( FALSE );
            
        } catch (Exception $ex) {
            //redirect to db error page
            echo $ex;
        }    
    }  
    
    
    public function SetUpAccountToken($user, &$token){
        
        $account = MAccount::Get();
        $aID = $account->GetAccountIDByUsername($user);
        
        if(!$this->IsAccountTokenSet($aID)) {
            $token = $this->CreateAccountToken($aID);
        }else{
            $token = $this->SelectAccountTokenRecordByID($aID);
        }
        if(count($token)=== 5 ){                  
                return(TRUE);
        }  
         
        return( FALSE );
    }
    
    
       /**
        * @brief Tests to see if an account token is set.
        * @return a 32bit random hex token. 	
        */    
    public function GenerateHexToken() {
        $bytes = openssl_random_pseudo_bytes(32, $cstrong);
        $hex   = bin2hex($bytes);
        return($hex);
    }

    
    
    
    
    
    
    
    
    
    
    
       /**
        * @brief Inserts a new token .
        * @param string $ticket The ticket number portion of the token pair. 
        * @param int $token The token compnent for the token pair.
        * @param int $lifespan The life span of the token. 
        * @return The id of the last inserted row.. 	
        */    
    private function InsertTokenRecord ($ticket, $token , $lifespan) {
        try {
            //Prepare teh query to set up the pending table.
            $stmt = $this->m_db_link->m_db_handle->prepare("INSERT INTO gtoken(ticket, token, born, ttl) VALUES(:ticket, :token, :born, :ttl); ");
            $stmt->bindValue(':ticket', $ticket, PDO::PARAM_STR);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR);
            $stmt->bindValue(':born', $_SERVER["REQUEST_TIME"], PDO::PARAM_INT);
            $stmt->bindValue(':ttl', $lifespan, PDO::PARAM_INT);            
            if($stmt->execute()){return( $this->m_db_link->m_db_handle->lastInsertID());} 

        } catch (Exception $ex) {
            //redirect to db error page
            echo $ex;
        }
    }
    
    
      /**
       * @brief Inserts a new account token.
       * @param string $account The account number associated with the token.  
       * @param int $ticket The ticket number portion of the token pair. 
       * @param int $token The token compnent for the token pair.
       * @param int $lifespan The life span of the token. 
       * @return The id of the last inserted row.. 	
       */     
    private function InsertAccountTokenRecord ($account, $ticket , $token, $lifespan) {
        try {
            $this->m_db_link->m_db_handle->beginTransaction();
            
            //Set up standard token table.
            $tokenID = $this->InsertTokenRecord ($ticket, $token , $lifespan);
            
            //Prepare the query to set the account-token table.
            $stmt = $this->m_db_link->m_db_handle->prepare("INSERT INTO gtoken_account(tokenID, account) VALUES(:tokenID, :account); ");
            $stmt->bindValue(':tokenID', $tokenID, PDO::PARAM_INT);
            $stmt->bindValue(':account', $account, PDO::PARAM_INT);  
            
            if($stmt->execute()){
                $this->m_db_link->m_db_handle->commit();
                return( /*$this->m_db_link->m_db_handle->lastInsertID()*/$tokenID);
            }else{
                 $this->m_db_link->m_db_handle->rollBack();
            } 

        } catch (Exception $ex) {
            //redirect to db error page
            echo $ex;
        }
    }
    
 
      /**
       * @brief Select a token.       
       * @param int $ticket The ticket number portion of the token pair. 
       * @param int $token The token compnent for the token pair.
       * @return a token record pair. 	
       */        
    private function SelectTokenRecord($ticket, $token) {
        try {
            $qry="SELECT born, ttl FROM gtoken where token=:token AND ticket=:ticket;";
            $stmt = $this->m_db_link->m_db_handle->prepare($qry);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR); 
            $stmt->bindValue(':ticket', $ticket, PDO::PARAM_STR); 
            $stmt->execute(); 
            
            if($stmt->rowCount() === 1){
                return($stmt->fetch(PDO::FETCH_ASSOC));                
            }            
            return(FALSE);
            
        } catch (Exception $ex) {
            //redirect to db error page
            echo $ex;
        }
    }
    
    
      /**
       * @brief Select account token record.       
       * @param int $ticket The ticket number portion of the token pair. 
       * @param int $token The token compnent for the token pair.
       * @return The id of the last inserted row.. 	
       */     
    private function SelectAccountTokenRecord($ticket, $token) {
        try {
            $qry="SELECT ac.account, t.born, t.ttl  FROM gtoken t JOIN gtoken_account ac ON t.id = ac.tokenID WHERE token =:token AND ticket =:ticket;";
            $stmt = $this->m_db_link->m_db_handle->prepare($qry);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR); 
            $stmt->bindValue(':ticket', $ticket, PDO::PARAM_STR); 
            $stmt->execute(); 
            if($stmt->rowCount() === 1){
                return($stmt->fetch(PDO::FETCH_ASSOC));                
            }            
            return(FALSE);
            
        } catch (Exception $ex) {
            //redirect to db error page
             echo $ex;
        }
    }
  
          /**
       * @brief Select account token record.       
       * @param int $ticket The ticket number portion of the token pair. 
       * @param int $token The token compnent for the token pair.
       * @return The id of the last inserted row.. 	
       */     
    private function SelectAccountTokenRecordByID($id) {
        try {
            $qry="SELECT ac.account, t.born, t.ttl, t.ticket, t.token  FROM gtoken t JOIN gtoken_account ac ON t.id = ac.tokenID WHERE ac.account =:id;";
            $stmt = $this->m_db_link->m_db_handle->prepare($qry);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);  
            $stmt->execute(); 
            if($stmt->rowCount() === 1){
                return($stmt->fetch(PDO::FETCH_ASSOC));                
            }            
            return(FALSE);
            
        } catch (Exception $ex) {
            //redirect to db error page
             echo $ex;
        }
    }
    
    
      /**
       * @brief Delete a token.       
       * @param int $ticket The ticket number portion of the token pair. 
       * @param int $token The token compnent for the token pair.
       * @return TRUE on success, otherwise FALSE. 	
       */     
    private function DeleteTokenRecord($ticket, $token) {
        try {
            $qry="DELETE FROM gtoken where token=:token AND ticket=:ticket;";
            $stmt = $this->m_db_link->m_db_handle->prepare($qry);
            $stmt->bindValue(':token', $token, PDO::PARAM_STR); 
            $stmt->bindValue(':ticket', $ticket, PDO::PARAM_STR); 
            $stmt->execute(); 
            
            if($stmt->rowCount() === 1){ return( TRUE ); }
            
            return(FALSE);
            
        } catch (Exception $ex) {
            //redirect to db error page
             echo $ex;
        }
    }
    
    
    
    
    
    
    
    
    
    
      /**
       * @brief Check to see if a valid token pair has been given..       
       * @param int $ticket The ticket number portion of the token pair. 
       * @param int $token The token compnent for the token pair.
       * @return TRUE on success, otherwise FALSE. 	
       */    
    private function ValidToken($ticket, $token) {
        if(!$ticket){ return(False); }
        if(!$token){ return(False); }
        //if the token is not the correct length, bail out.
        if(strlen($token) != 64 && strlen($ticket) != 64){ return(FALSE); }
        
        //if the token contains any illegal charracters, bail out.
        if(!preg_match("/^[A-fa-f0-9]{64}?/", $token)){ return(FALSE); } 
        if(!preg_match("/^[A-fa-f0-9]{64}?/", $ticket)){ return(FALSE); }
        
        //If we made it here its all good, return true.
        return( TRUE ); 
    }
    
    
       /**
       * @brief Check to see if a token is still alive.       
       * @param int $totalLife The time to live of the token.
       * @param int $born The time that the token was created..
       * @return TRUE if the token is still alive, otherwise FALSE. 	
       */   
    private function StillAlive($totalLife, $born) {
        // Check to see if link has expired
        if ($_SERVER["REQUEST_TIME"] - $born > $totalLife) {
            return( FALSE );
        }
        else {
            return( TRUE );
        }
    }
    
    
}//End of class

