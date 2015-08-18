<?php

$host="localhost";
$user='webmaster';
$pass='FSuBATyhmTAhT3XN';
$db="gngr"; 





        //Add the state table
    $table = "state";
    try {
         $dbh = new PDO("mysql:dbname=$db;host=$host", $user, 'FSuBATyhmTAhT3XN' );
         $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
         $sql ="CREATE table $table(
         stateID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR( 50 ) NOT NULL COLLATE 'utf8_unicode_ci', 
         shortName  VARCHAR( 8 ) NOT NULL COLLATE 'utf8_unicode_ci');" ;
         
         $dbh->exec($sql);
         print("Created $table Table.\n");

    } catch(PDOException $e) {
      
        echo $e->getMessage();//Remove or change message in production code
    }


    
    
    
    
    
    //Now insert the default states.
    $state="western australia";
    $sState = "wa";
    $id="";
    try {
        $dbh = new PDO("mysql:dbname=$db;host=$host", $user, 'FSuBATyhmTAhT3XN' );
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
        
        $sql ="INSERT INTO state(stateID, name, shortName) VALUES(?, ?, ?);" ;
        $prepQry = $dbh->prepare($sql);
        $prepQry->bindParam(1, $id);
        $prepQry->bindParam(2, $state);
        $prepQry->bindParam(3, $sState);
        
        //insert state 1
        $prepQry->execute();
        print("inserted $state into state.\n");
         
        //insert state 2
        $state="new south wales";
        $sState = "nsw";
        $prepQry->execute();
        print("inserted $state into state.\n");
       
                //insert state 2
        $state="south australia";
        $sState = "sa";
        $prepQry->execute();
        print("inserted $state into state.\n");
        
                //insert state 2
        $state="northern territory";
        $sState = "nt";
        $prepQry->execute();
        print("inserted $state into state.\n");
        
                //insert state 2
        $state="queensland";
        $sState = "qld";
        $prepQry->execute();
        print("inserted $state into state.\n");

    } catch(PDOException $e) {
      
        echo $e->getMessage();//Remove or change message in production code
    }
    