<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MMail.php';



   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all content functionality
    * 
    * @param $m_cmanager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    * @param $m_artists; array[string] Holds the current list of artist.
    *   
    * @note This is a singleton class used to group functionality.
    */    
class MContent {  

    private static $m_cmanager;
    protected $m_db_link;
    protected $m_artists;
    
       /**
        * @brief private constructor for creating a content manager.              
        * @note This means the instance of the object can only be retrieved by
        * calling the Get function with the the class scope operator.
        * 	
        */     
    private function __construct() {
        
        $this->m_db_link = DB_Manager::GetInstance();   
    }
    
    
        /**
        * @brief Get the instance of the content manager.
        * @return An instance of the account manager.              
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
        * @brief Get the list of current live artists.           
        * @note A live artist is one who currently has their profile active. 
         * They can be searched for and their posts will be displayed in the 
         * public feed.
        */     
    public function GetLiveArtistList( ) {
          try {        
            //If the instance was retrieved succesfully, prepare the statement.
            $stmt = $this->m_db_link->m_db_handle->prepare("select name, url from gcreator where cStatus=:status and creatorType=:cType");
            $stmt->bindValue(':status', 'live', PDO::PARAM_STR);
            $stmt->bindValue(':cType', 'artist', PDO::PARAM_STR);

            //If the statment was prepared and exicuted correctly. Add all the states to our menu.
            if ($stmt->execute()) {
                $this->m_artists = $stmt->fetchAll();
                return( $this->m_artists );
            }            
        } catch (PDOException $ex) {
                //Go do data base error page with the problem.
              echo $ex->getMessage();
        }
    } 
    
    
       /**
        * @brief Test to see if an artist profile is currently live.          
        * @param $artist string the name of an artist to check for. 	
        */   
    public function IsLiveArtist($artist) {
        if($artist) {
            if(!$this->m_artists){$this->GetLiveArtistList( );}
            foreach($this->m_artists as $liveArtist) {
                if($liveArtist['url'] === $artist) {
                    return ( TRUE );
                }
            } 
        }
        return( FALSE );
    }  
    
    
       /**
        * @brief Get an array with the requested artist info.          
        * @param $artist string the name of an artist to check for. 
        * @return The artist details array, otherwise return FALSE.	
        */     
     public function GetLiveArtist($artist) {
        if($artist) {
                //Look for the requested artist in the list of artists.
            foreach($this->m_artists as $liveArtist) {
                    //If it exists then return the artist info array.
                if($liveArtist['url'] === $artist) {
                    return ( $liveArtist );
                }
            } 
        }
        return( FALSE );
    }     
    
    
       /**
        * @brief Get the list of current live artists. 
        * @return The full list of live artists.	
        */    
    public function GetLiveArtists(){
        if (!isset($this->m_artists))
        {
            $this->m_artists = $this->GetLiveArtistList();
        }
        return ($this->m_artists);
    }
}
