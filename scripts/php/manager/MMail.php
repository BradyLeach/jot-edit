<?php


   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all mail functionality
    * 
    * @param $m_mManager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    * @param $m_headers The email header.
    *   
    * @note This is a singleton class used to group functionality.
    */
class MMail {

    private static $m_mailManager;  //The instance of its self
    protected $m_db_link;           //Link to the database handle
    protected $m_headers;           //The emails header
    
       /**
        * @brief private constructor for creating an mail manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */    
    private function __construct() {
        
        $this->m_db_link = DB_Manager::GetInstance();
        $this->m_headers = 'MIME-Version: 1.0' . "\r\n";
        $this->m_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $this->m_headers .= 'From: brady_leach@dodo.com' . "\r\n";
    }
  
       /**
        * @brief Get the instance of the mail manager.
        * @return An instance of the mail manager.              
        * 	
        */   
    public static function Get() {
    
        if (!isset(self::$m_mailManager))
        {
            $object = __CLASS__;
            self::$m_mailManager = new $object;
        }
        return self::$m_mailManager;
    }
    

       /**
        * @brief Send account verification.
        * @param string $to The destination email address.
        * @param string $uname The recipients username.
        * @param string $ticket The ticket number that allows access to the .
        * @param string $token The token number that allows access to the .              
        * 	
        */      
    public function SendVerification($to, $uname, $ticket, $token) {
        
        $message = "<table><tr> <h1 style=\"color:#F00\">hi,</h1>" . $uname . "</tr>";
        $message .="<tr>To verify your account follow this link</tr>";
        $message .="<tr>http://localhost/jot-edit/action/verify/". $ticket."/".$token."</tr>";
        $message .="</table>";
           
        return($this->SendMail($to, 'New Account Activation', $message));
    }
    

       /**
        * @brief Send artist invite.
        * @param $to The destination email address.
        * @param $name The recipients username.
        * @param $ticket The ticket number that allows access to the .
        * @param $token The token number that allows access to the .              
        * 	
        */      
    public function SendArtistInvite($to, $name, $ticket, $token) {
        
        $message = "<table><tr> <h1 style=\"color:#F00\">hi,</h1>" . $name . "</tr>";
        $message .="<tr>Here is your link to create and manage your new Green and Gold Records account.</tr>";
        $message .="<tr>To create your account follow this link</tr>";
        $message .="<tr>http://localhost/jot-edit/action/register-artist/". $ticket."/".$token."</tr>";
        $message .="<tr>Once you have createde your account you will be able to:</tr>";
        $message .="<tr><ul><li>Create text, image and audio posts</li><li> upload products to the store</li>";
        $message .="<li>manage your digital and physical murchendise sales</li><li>be part of a growing community of independent artist</li></ul></tr>";
        $message .="</table>";
        return($this->SendMail($to, 'Join Green and Gold Records', $message));
    }
    
    
        /**
        * @brief Send account lock email.
        * @param string $to The destination email address.
        * @param string $uname The recipients username.
        * @param string $ticket The ticket number that allows access to the .
        * @param string $token The token number that allows access to the .              
        * 	
        */   
    public function SendAccountLock($to, $uname/*, $ticket, $token*/) {
        
        $message = "<table><tr> <h1 style=\"color:#F00\">hi,</h1>" . $uname . "</tr>";
        $message .="<tr>Your account has been locked. This ic casued by too many failed login attempts.</tr>";
        $message .="<tr>you account will unlock automatically in 2 hours.</tr>";
        $message .="<tr>To unlock it now and reset your password follow the link below.</tr>";
        $message .="<tr>http://localhost/jot-edit/action/password-recovery</tr>";
        $message .="</table>";
           
        return($this->SendMail($to, 'access locked', $message));
    }
    
   
       /**
        * @brief Send password reset email.
        * @param string $to The destination email address.
        * @param string $uname The recipients username.
        * @param string $ticket The ticket number that allows access to the .
        * @param string $token The token number that allows access to the .              
        * 	
        */   
    public function SendPasswordRecovery($to, $uname, $ticket, $token) {
        
        $message = "<table><tr> <h1 style=\"color:#F00\">hi,</h1>" . $uname . "</tr>";
        $message .="<tr>To reset your password please follow the link below.</tr>";
        $message .="<tr>If your account has been locked it will unlock automatically in 2 hours.</tr>";
        $message .="<tr>http://localhost/jot-edit/action/password-reset/". $ticket."/".$token."</tr>";
        $message .="</table>";
           
        return( $this->SendMail($to, 'Password Recovery', $message));
    }
    
    
        /**
        * @brief The function thatsends the mail.
        * @param string $to The destination email address.
        * @param string $subject The subject matter of the email.
        * @param string $message The email message content.
        * @param array $addParams The additional params for the php mail function.              
        * 	
        */     
    protected function SendMail($to, $subject, $message, $addParams=NULL) {
        try {
            return(mail($to, $subject, $message, $this->m_headers, $addParams)); 
 
        }catch(Exception $ex){
            //write to log
            echo $ex;
        }
    }
}
