<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MMail.php';
include_once $path . '/jot-edit/scripts/php/manager/MToken.php';


   /**
    * @author Brady Leach 
    * @date 10/06/2015
    * @brief Manages all user address functionality
    * 
    * @param $m_amanager this a pointer to its self.
    * @param $m_db_link DBManager Holds handle to the database manager.
    *   
    * @note This is a singleton class used to group functionality.
    */
class MAddress {
    
        //members
    private static $m_amanager;     //The instance of its self
    protected $m_db_link;           //Link to the database handle
    protected static $m_states;     


    /**
        * @brief private constructor for creating an address manager.              
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
    
        if (!isset(self::$m_amanager))
        {
            $object = __CLASS__;
            self::$m_amanager = new $object;
        }
        return self::$m_amanager;
    }
    
    
    public function GetStates(){
        if(!isset(self::$m_states)){
           $this->LoadStates();
        }
        
        
        if(self::$m_states){
            return(self::$m_states);
        }
    }
    
    private function LoadStates(){
        try {        

            //If the instance was retrieved succesfully, prepare the statement.
            $stmt =  $this->m_db_link->m_db_handle->prepare("select id, abbrv from gstate");

            //If the statment was prepared and exicuted correctly. Add all the states to our menu.
            if (!$stmt->execute()) { return;}         

            //load the state list.
            while($state = $stmt->fetch(PDO::FETCH_ASSOC)) {
                self::$m_states[$state["id"]] = $state["abbrv"];
            }  
            
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }
    
    private function GetSateID($state){
        if($state){
            $states = $this->GetStates();
            return(array_search($state, $states));
        }
        
        
    }
    
    
    public function AddressExists($aid, $l1, $l2, $suburb, $pcode, $state ){
        $stateID=$this->GetSateID($state);
        try {
            $sql= "SELECT id FROM gaddress "
                . "WHERE  line1=:l1 "
                . "AND line2=:l2 " 
                . "AND suburb=:suburb " 
                . "AND postCode=:pcode "
                . "AND stateID=:state "
                . "AND accountID=:aid;" ; 
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            
            $stmt->bindValue(':l1', $l1, PDO::PARAM_STR);
            $stmt->bindValue(':l2', $l2, PDO::PARAM_STR);
            $stmt->bindValue(':suburb', $suburb, PDO::PARAM_STR);
            $stmt->bindValue(':pcode', $pcode, PDO::PARAM_INT);
            $stmt->bindValue(':state', $stateID, PDO::PARAM_INT);
            $stmt->bindValue(':aid', $aid, PDO::PARAM_INT); 
           
            $stmt->execute();
                    
           if( $stmt->rowCount() >= 1){ return (TRUE);}
           return(FALSE);

        } catch (Exception $ex) {
                //note in log
                //redirect to database error hadle page.
            echo "address_exists : ".$ex->getMessage(); 
            echo "address_exists : ".$ex->getLine();
            echo "address_exists : ".$ex->getPrevious();
        }         
    }
    
    
    public function AddAddress($aid, $l1, $l2, $suburb, $pcode, $state){
        $stateID=$this->GetSateID($state); 
        try {
                //Get the instance of the data base connection. 
            $sql = "INSERT INTO gaddress(line1, line2, suburb, postCode, stateID, accountID) "
            .  "VALUES(:l1, :l2, :suburb, :pcode, :stateID, :aid);";
            
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            
            $stmt->bindValue(':l1', $l1, PDO::PARAM_STR);
            $stmt->bindValue(':l2', $l2, PDO::PARAM_STR);
            $stmt->bindValue(':suburb', $suburb, PDO::PARAM_STR);
            $stmt->bindValue(':pcode', $pcode, PDO::PARAM_STR);
            $stmt->bindValue(':stateID', $stateID, PDO::PARAM_STR);
            $stmt->bindValue(':aid', $aid, PDO::PARAM_STR);
            
            if(!$stmt->execute() || $stmt->rowCount() !== 1){ return (FALSE);}
            else{return(TRUE);}
        } catch (Exception $ex) {
            echo "add address : ".$ex->getMessage();
        }  
    }
    
    public function UpdateAddress($aid, $rid, $l1, $l2, $suburb, $pcode, $state){       
        $stateID=$this->GetSateID($state); 
         try {
            $sql= "UPDATE gaddress "
                . "SET line1=:l1, line2=:l2, suburb=:suburb, postCode=:pcode,stateID=:state "
                . "WHERE id=:rid;";
            $stmt = $this->m_db_link->m_db_handle->prepare($sql);
            $stmt->bindValue(':l1', $l1, PDO::PARAM_STR);
            $stmt->bindValue(':l2', $l2, PDO::PARAM_STR);
            $stmt->bindValue(':suburb', $suburb, PDO::PARAM_STR);
            $stmt->bindValue(':pcode', $pcode, PDO::PARAM_STR);
            $stmt->bindValue(':state', $stateID, PDO::PARAM_STR);
            $stmt->bindValue(':rid', $rid, PDO::PARAM_STR);
                           
            if($stmt->execute() && $stmt->rowCount() == 1) { return( TRUE ); }
            else { return ( FALSE ); }
            
         } catch (Exception $ex) {
            //Go to dedicated server error page with error.
             echo $ex;
         }        
    }

    
    public function RemoveAddress($aid, $addressID){
        try {
            //Get the instance of the data base connection. 
            $stmt = $this->m_db_link->m_db_handle->prepare("DELETE FROM gaddress WHERE id=:id AND accountID=:aid");            
            $stmt->bindValue(':id', $addressID, PDO::PARAM_STR);
            $stmt->bindValue(':aid', $aid, PDO::PARAM_STR);
            $stmt->execute();            
            if( $stmt->rowCount() !== 1){ return (FALSE);}
            else{return(TRUE);}
        } catch (Exception $ex) {
            echo $ex;
        }        
    }
    
    
    public function GetAddressList($aid){
        try {
            //Get the instance of the data base connection. 
            $stmt = $this->m_db_link->m_db_handle->prepare("SELECT id, line1, line2, suburb, postCode, stateID FROM gaddress WHERE accountID=:aid");            
            $stmt->bindValue(':aid', $aid, PDO::PARAM_INT);
            $stmt->execute();         
            return($stmt->fetchAll());
        } catch (Exception $ex) {
            echo $ex;
        }         
    }
    
    
    
}//end of class
    