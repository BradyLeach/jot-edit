<?php

    /**
     * @author  Brady Leach
     * @date	15/01/2014
     * @brief   Returns the file extension of the given path.
     * @param   $path - A path to a file.
     * @return  file extension as a string..
     * @todo	Add error checking and html5 validation.
     */	
function GetExtension($path) {	

        //Get the sub string after the last period.	
    return(substr( strrchr( trim($path) , "." ), 1 ));
}

    /**
     * @brief Check to see if a string falls with in the min and max. Strings that 
     * are equal to the min and max will return true.
     * @param int $min  The minimum length the string can be.
     * @param int $max  The maximum length the string can be.
     * @param string $text The text to be tested.
     * @return TRUE if the sting falls in the permitted length, otherwise FALSE.
     */
function CheckLength($min, $max, $text) {	
        //If params valid return the result
    if($min && is_int($min)) {
            
        if($max && is_int($max)) {
                
            if($text ){
                return(strlen($text) >= $min && strlen($text) <= $max);
            }
        }
    }
    return( FALSE );
}

    /**
     * @brief Check to see if a string contains uppercase letters.
     * @param string $text The text to be tested.
     * @param int $amount The amount of characters that should be present. 
     * @return TRUE if the sting contains uppercase letters, otherwise FALSE.
     */
function HasUppercase($text, $amount){
    if(preg_match_all('/[A-Z]/', $text ) >= $amount) {return( TRUE );} 
    return( FALSE );
}
   
    /**
     * @brief Check to see if a string contains lowercase letters.
     * @param string $text The text to be tested.
     * @param int $amount The amount of characters that should be present. 
     * @return TRUE if the sting contains lowercase letters, otherwise FALSE.
     */
function HasLowercase($text, $amount){
    if(preg_match_all('/[a-z]/', $text ) >= $amount) {return( TRUE );} 
    return( FALSE );
}
      

    /**
     * @brief Check to see if a string contains digits 0-9.
     * @param string $text The text to be tested.
     * @param int $amount The amount of characters that should be present. 
     * @return TRUE if the sting contains digits, otherwise FALSE.
     */
function HasDigits($text, $amount){
    if(preg_match_all('/[0-9]/', $text ) >= $amount) {return( TRUE );} 
    return( FALSE );
}

    /**
     * @brief Check to see if a string contains digits 0-9.
     * @param string $text The text to be tested.
     * @param int $amount The amount of characters that should be present. 
     * @return TRUE if the sting contains digits, otherwise FALSE.
     */
function HasSymbol($text, $amount){
    if(preg_match_all('/[!@#$&*]/', $text ) >= $amount) {return( TRUE );} 
    return( FALSE );
}




function IdGenerator(){
	static $id = 0000;
	return ($id++);
}

function IsEmpty($var){
     //If the date is valid and not empty set it.
        if($var && strlen($var) > 0 )
        {
           return FALSE;
        }
        else {//asign a generic author value.
            return TRUE; 
        }
}


function Append_Log ($type, $msg) {
    
    $logFile =  "../php_script_log.txt";	

    switch($type)
    {       
        case "error";
            file_put_contents($logFile,  "ERROR   : " . $msg, FILE_APPEND );
        break;
        case "warning";
            file_put_contents($logFile,  "WARNING : " . $msg, FILE_APPEND );
        break;
        case "success";
            file_put_contents($logFile,  "SUCCESS : " . $msg, FILE_APPEND );
        break;
        default;
            file_put_contents($logFile, "UNKNOWN : " . $msg, FILE_APPEND );
        break;
    }        
}


function MyTrim($txt){
    $snumb = trim($txt);
    return(rtrim($snumb)  /*preg_replace('/ /', '', $txt) */);
}



function ValidText($regEx, $text){
    if($regEx && $text){
        return(preg_match($regEx, $text));
    }
}


setlocale(LC_ALL, 'en_AU.UTF8');
function ToAscii($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|$@+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

















    /**
     * @brief   Checks is a name is valid.
     * @param   string $name The name to check
     * @return  file extension as a string..
     * @todo	Add error checking and html5 validation.
     */	/*
function ValidName($name) {

    $trimmed = MyTrim($name);
    
    if( !IsEmpty($trimmed) && strlen($trimmed) < 64 && ctype_alpha(str_replace(array("-",' '), '', $trimmed)) ){
        return( TRUE );
    }
    return(FALSE);  
}

/*

function ValidEmail($email){
    $trimmedMail = MyTrim($email);
        //The email search pattern.
    $test_exp = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/'; 

        //If matched then set matched to true.
    return(preg_match($test_exp, $trimmedMail));  
 
}

*/
function ValidPhoneNumber($number) {

    if($number) {
        //Remove white space from in the numbers
        $snumb = preg_replace('/ /', '', $number);

        //str_replace('g', 'y', $num);
        echo $snumb;
        //echo urlencode($snumb);
            //If matched then set matched to true.
        return((strlen($snumb) < 13) && ctype_digit($snumb) ); 
    }
   return(FALSE);
}

/*

function ExistsInDB($table, $column, $value) {
    try {        
            //Get the instance of the data base connection. 
        $db = DB_Manager::GetInstance();
        $db->m_db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //If the instance was retrieved succesfully, prepare the statement.
        $stmt = $db->m_db_handle->prepare("select :column from $table WHERE $column = :value");
        $stmt->bindParam(':column', $column, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);

            //If the statment doesn't exicute correctly. 
        $stmt->execute();         
        
            //If the statment returned any rows the item exists in the DB.
        if($stmt->rowCount() > 0 ) { 
            return( TRUE );                
        }

        return( FALSE );
            
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        // @todo add in propper handling of database failure.
    }
}
*/
/*
function ValidActName($actName){
    
    
        if($actName) {
            //Trim down the string
        $trim_actName = MyTrim($actName);
        
        if(strlen($trim_actName) > 0 && strlen($trim_actName) < 250) {
            
            $re = "/^[\w \"@$-]+$/";
    
            return(preg_match($re, $trim_actName)); 
        }
    }
    
    return( FALSE ); 
}


 function ValidUserName($username){
    if($username) {
            //Trim down the string
        $trim_userame = MyTrim($username);
        
        if(strlen($trim_userame) > 2 && strlen($trim_userame) < 40) {
            
            $re = "/^[\w$@*-]+$/";
    
            return(preg_match($re, $trim_userame)); 
        }
    }
    
    return( FALSE );
 }


function ValidPassword($pword) {
   $r1='/[A-Z]/';  //Uppercase
   $r2='/[a-z]/';  //lowercase
  // $r3='/[!@#$&*]/';  // not enforceing special chars
   $r4='/[0-9]/';  //numbers

   if( $pword ) {
    if(preg_match_all($r1, $pword )< 1) {return( FALSE );}
    if(preg_match_all($r2, $pword )< 1) {return( FALSE );}
    if(preg_match_all($r4, $pword )< 1) {return( FALSE );}
    if(strlen($pword)<8){ return ( FALSE );}
   }

   return TRUE;
}
*/

   /**
    * @brief reduce rich character set string to URL-compatible string
    * @param string $text original string
    * @return string
    *//*
setlocale(LC_ALL, 'en_AU.UTF8');
function CleanURL($text) {
    setlocale(LC_ALL, 'en_AU');
        // replace accented characters with unaccented characters
    $newText = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
 
    echo $text . "\n";
    echo $newText . "\n";
        // remove unwanted punctuation, convert some to '-'
    static $punc = array(
        // remove
        "'" => '', '"' => '', '`' => '', '=' => '', '+' => '', '*' => '', '&' => '', '^' => '', '' => '',
        '%' => '', '$' => '', '#' => '', '@' => '', '!' => '', '<' => '', '>' => '', '?' => '',
        // convert to minus
        '[' => '-', ']' => '-', '{' => '-', '}' => '-', '(' => '-', ')' => '-',
        ' ' => '-', ',' => '-', ';' => '-', ':' => '-', '/' => '-', '|' => '-'
    );
    
    $newText = strtr($newText, $punc);
    echo $newText . "\n";
        // clean up multiple '-' characters
    $newText = preg_replace('/-{2,}/', '-', $newText);
 echo $newText . "\n";
        // remove trailing '-' character if string not just '-'
    if ($newText != '-') {
        $newText = rtrim($newText, '-');
    }
    
        // return a URL-encoded string
    return rawurlencode($newText);
}

*/