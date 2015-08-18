<?php
function Redirect($url) 
{ 
    if (!headers_sent()) 
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $url);
        exit;
    } 
    else 
    {
        die('Could not redirect; Headers already sent (output).');
        
        //go to error page
    }
}






function Redirect404() 
{
    if (!headers_sent()) 
    {
        header($_SERVER["SERVER_PROTOCOL"]. " 404 Not Found"); 
        exit;
    } 
    else 
    {
        die('Could not redirect; Headers already sent (output).');
        //go to error page
    }
}

