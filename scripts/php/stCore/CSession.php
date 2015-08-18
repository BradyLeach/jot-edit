<?php

class CSession {

    
    function __construct() {
            // override default behaviour.
        session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
 
            // This line prevents unexpected effects when using objects as save handlers.
        register_shutdown_function('session_write_close');

    }

    public function start_session($session_name, $secure) {
        //ini_set('session.use_trans_sid','0');
        //ini_set('session.gc_probability', 1);
        
            // Make sure the session cookie is not accessible via javascript.
        $httponly = true;
 
            // Hash algorithm to use for the session. (use hash_algos() to get a list of available hashes.)
        $session_hash = 'sha512';  
        
            // Check if hash is available
        if (in_array($session_hash, hash_algos())) {
                // Set the has function.
            ini_set('session.hash_function', $session_hash);
        }  
        
            // How many bits per character of the hash.
            // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
        ini_set('session.hash_bits_per_character', 5);
 
            // Force the session to only use cookies, not URL variables.
        ini_set('session.use_only_cookies', 1);
        
            // Get session cookie parameters 
        $cookieParams = session_get_cookie_params(); 
            // Set the parameters
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
            // Change the session name 
        session_name($session_name);
            // Now we cat start the session
        session_start();
            // This line regenerates the session and delete the old one. 
            // It also generates a new encryption key in the database. 
       session_regenerate_id( TRUE ); 
        
         // session_regenerate_id( FALSE ); 
        
        
    }
    
    
    public function open() {
        $this->db = DB_Manager::GetInstance(); 
        $this->db->m_db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
        
    } 
    
    public function close() {
        $this->db = NULL;
        return( TRUE );
    }
    
    
    function read($id) {
        try{
            if(!isset($this->read_stmt)) {
              $this->read_stmt = $this->db->m_db_handle->prepare("SELECT data FROM gsecure_session WHERE id = :id LIMIT 1");
            }
            $this->read_stmt->bindValue(':id', $id, PDO::PARAM_STR );
            $this->read_stmt->execute();

            $data = $this->read_stmt->fetch();
            $key = $this->getkey($id);
            $data = $this->decrypt($data['data'], $key);

            return ($data);
        }catch(Exception $ex){
            //go to database error page
                        echo $ex;
        }
    }
    
    
    function write($id, $data) {
        
        $key = $this->getkey($id);              // Get unique key        
        $data = $this->encrypt($data, $key);    // Encrypt the data
        $time = time();                         // Get the curretn time.
        
        try {
            if(!isset($this->w_stmt)) {
               $this->w_stmt = $this->db->m_db_handle->prepare("REPLACE INTO gsecure_session (id, set_time, data, session_key) VALUES (:id, :time, :data, :key)");
            }

            $this->w_stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $this->w_stmt->bindValue(':time', $time, PDO::PARAM_STR);
            $this->w_stmt->bindValue(':data', $data, PDO::PARAM_STR);
            $this->w_stmt->bindValue(':key', $key, PDO::PARAM_STR);
            
            if($this->w_stmt->execute()) { return( TRUE ); }
            
            return( FALSE );
        }catch(Exception $ex){
            //Go to database error page.
                        echo $ex;
        }
    }
    
    
    
    function destroy($id) {
        try{
            if(!isset($this->delete_stmt)) {
              $this->delete_stmt = $this->db->m_db_handle->prepare("DELETE FROM gsecure_session WHERE id = :id");
            }
            $this->delete_stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $this->delete_stmt->execute();
            $rc = $this->delete_stmt->rowCount();
            if($rc === 1 ) {
                return ( TRUE );
            }
            return( FALSE );
        }catch(Exception $ex){
            //Go to database error page.
            echo $ex;
        }
    }
    
    
    function gc($ttl) {
        try{
            if(!isset($this->gc_stmt)) {
              $this->gc_stmt = $this->db->m_db_handle->prepare("DELETE FROM gsecure_session WHERE set_time < :maxLife");
            }
            $maxLife = time() - $ttl;
            $this->gc_stmt->bindValue(':maxLife', $maxLife, PDO::PARAM_INT);
            if($this->gc_stmt->execute()) {
                return ( TRUE );
            }
            return( FALSE );
        }catch(Exception $ex) {
           //Go to database error page. 
            echo $ex;
        }
    }
    
    
    
    
    private function getkey($id) {
        try {
            if(!isset($this->key_stmt)) {
              $this->key_stmt = $this->db->m_db_handle->prepare("SELECT session_key FROM gsecure_session WHERE id = :id LIMIT 1");
            }

            $this->key_stmt->bindValue(':id', $id, PDO::PARAM_STR);
            if(!$this->key_stmt->execute()) {return;}
            
            if($this->key_stmt->rowCount() == 1) { 
                $key = $this->key_stmt->fetch();
                return($key['session_key']);
            } else {
                $random_key = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
                return($random_key);
            }           
            
        }catch(Exception $ex){
            //Go to database error pages
            echo $ex;
        }
    }
    
    
    
    
    private function encrypt($data, $key) {
        $encryption = new CEncrypt($key);
        return($encryption->encrypt($data));
    }
    
    
    private function decrypt($data, $key) {
        $decryption = new CEncrypt($key);
        return($decryption->decrypt($data));
    }
    
   
    
    
}//End of the session


