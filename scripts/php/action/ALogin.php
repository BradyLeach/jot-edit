<?php /*header('content-type: application/json; charset=utf-8');*/
include_once "../stCore/stCore.php";
include_once "../form/FLogin.php";
include_once "../page/PPage.php";

//session_start();

$newLogin = new FLogin( );
$result = array('result'=>'error', 'message'=>'error', 'redirect'=>'');

    if($newLogin->Locked()){
        $message = '<div class=form_error>Your account has been locked.';
        $message .= '<div class=notify>For information on how to unlock your account visit the<a class=notify href=FAQ>FAQ.</a></div></div>';
        $result = array('result'=>'locked', 'message'=>$message, 'redirect'=>'');
                
    }elseif($newLogin->Processed()){//If the form has been successfully processed redirect.   

        $result = array('result'=>'pass', 'message'=>'login successfull', 'redirect'=>'/jot-edit/studio');
        
    }else{
        
        $message= '<div class=form_error>Invalid account details.';
        $message .= '<div class=notify>Please check your details and try again</div></div>';
        $result = array('result'=>'fail', 'message'=>$message, 'redirect'=>'');
    }
$res   =  json_encode($result);
    
echo $res;
       
//Shouldn
//Redirect404();

