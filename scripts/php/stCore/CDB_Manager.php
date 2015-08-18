<?php

require_once 'CDB_Config.php';

    
   /**
    * @author   Brady Leach
    * @author   Guillaume Boschini
    * @date     12/05/2015
    * @class    DB_Manager
    * 
    * @param $m_db_handle A holder for the pdo objet handle. 
    * @param $m_instance An instance of class to access $m_db_handle
    * 
    * @brief    A singleton class for getting an instance of the pdo object 
    * that manages database interaction.  
    * 
    * @note Example Code
    * 
    * 
    *      //Get instance of the DB_Manager singleton.
    *      $myDBManager = DB_Manager::GetInstance;
    * 
    *      //Example Source 
    *       $stmt = $myDBManager->m_db_handle->prepare($qry);
    *       $stmt->bind(params);
    * 
    *  @note This class is based on a StackOverflow post from Guillaume Boschini
    *  @todo Add in some exception handling. 
    */

class DB_Manager
{
    public $m_db_handle; // handle of the db connexion
    private static $m_instance;

    
    
       /**
        * @brief Constructor - Creates a DB_Manager object.
        * @note This is declared private to enforce the singleton pattern.   
        * 	
        */
    private function __construct()
    {
        try {
            // building data source name from config
            $dsn = 'mysql:host=' . DB_Config::read('db.host') .
                   ';dbname='    . DB_Config::read('db.basename') .
                  // ';port='      . DB_Config::read('db.port') .
                   ';connect_timeout=15';

            // getting DB user from config                
            $user = DB_Config::read('db.user');

            // getting DB password from config                
            $password = DB_Config::read('db.password');

            $this->m_db_handle = new PDO($dsn, $user, $password);
            $this->m_db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }catch (PDOException $ex) {
             echo $ex->getMessage();
        }
    }

    
    public static function GetInstance()
    {
        if (!isset(self::$m_instance))
        {
            $object = __CLASS__;
            self::$m_instance = new $object;
            
        }
        return self::$m_instance;
    }

    // others global functions
}