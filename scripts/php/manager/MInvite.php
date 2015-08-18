<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MMail.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccount.php';
      
    /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all invitation functionality
    * 
    * @param $m_iManager $this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    *  
    * @note This is a singleton class used to group functionality.
    */
class MInvite {
            //members
    private static $m_iManager; //a pointer to its self.
    private $m_db_link;         //Holds handle to the database manager.
    
    
       /**
        * @brief private constructor for creating an invitation manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */
    private function __construct() {
            //Get the database handle
        $this->m_db_link = DB_Manager::GetInstance();   
    }

       /**
        * @brief Get the instance of the invitation manager.
        * @return An instance of this.              
        * 	
        */
    public static function Get() {
    
        if (!isset(self::$m_iManager))
        {
            $object = __CLASS__;
            self::$m_iManager = new $object;
        }
        return self::$m_iManager;
    }
    
       /**
        * @brief Get the instance of the invitation manager.
        * @param string $name The name of the creator you wish to invite to the website.
        * @param string $email The email address to send the invitation to.  
        * @return TRUE if the invitation is created and sent successfully, 
        * otherwise FALSE.              
        * 	
        */    
     public function InviteNewCreator($name, $email) {

        try{     
            $mail   = MMail::Get();
            $verify = MToken::Get();
            $token = $verify->CreateToken();
        
            //If the token that was created is valid
            if(!$token || !$token['id'] || !$verify->ValidateToken($token['ticket'], $token['token'])) { return( FALSE );}
 
                //try create the user account
            if( !$this->CreateInvite($name, $email, $token['id'])){ return( FALSE );}
            
            $mail->SendArtistInvite($email, $name, $token['ticket'], $token['token']);
           
            return( TRUE );
            
                //Start the email accoutn verificatoin process.
           // $this->CreateUserDrive($creatorID);           
        } catch (Exception $ex) {
            //Go to dedicated server error page with error.
            echo $ex;
        }       
    }
    
    
       /**
        * @brief Get the instance of the invitation manager.
        * @param string $name The name of the creator you wish to invite to the website.
        * @param string $email The email address to send the invitation to.  
        * @return TRUE if the invitation is created and sent successfully, 
        * otherwise FALSE.              
        * 	
        */     
    private function CreateInvite($name, $email, $tokenID){
       try {
                //Get the instance of the data base connection. 
           $sql = "INSERT INTO ginvite(tokenID, recipient, email ) VALUES(:tokenID, :name, :email)";
           $stmt = $this->m_db_link->m_db_handle->prepare($sql);
           $stmt->bindValue(':tokenID', $tokenID, PDO::PARAM_STR);
           $stmt->bindValue(':name', $name, PDO::PARAM_STR);
           $stmt->bindValue(':email', $email, PDO::PARAM_STR); 

           return($stmt->execute()); 
       }catch(Exception $ex){
           echo $ex;
       }
    }
      
}//End of classs