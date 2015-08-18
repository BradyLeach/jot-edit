<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';

$status = session_status();

if($status == PHP_SESSION_NONE){
    //There is no active session
    $session = new CSession();
    $session->start_session('_s', false);
}elseif($status == PHP_SESSION_DISABLED){
    //Sessions are not available
    
}elseif($status == PHP_SESSION_ACTIVE){
    //Destroy current and start new one
    session_destroy();
    $session = new CSession();
    $session->start_session('_s', false);
}


/*
if($status == PHP_SESSION_NONE){
    //There is no active session
    //$session = new CSession();
    //$session->start_session('_s', false);;
    session_start();
}else
if($status == PHP_SESSION_DISABLED){
    //Sessions are not available
   session_start(); 
}else
if($status == PHP_SESSION_ACTIVE){
    //Destroy current and start new one
    session_destroy();
    session_start();
 //   $session = new CSession();
   // $session->start_session('_s', false);
}*/