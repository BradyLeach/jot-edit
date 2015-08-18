<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/widget/WPasswordRecovery.php';
include_once $path . '/jot-edit/scripts/php/widget/WPasswordReset.php';
include_once $path . '/jot-edit/scripts/php/widget/WRegister.php';
include_once $path . '/jot-edit/scripts/php/widget/WVerify.php';
include_once $path . '/jot-edit/scripts/php/widget/Wlogin.php';
include_once $path . '/jot-edit/scripts/php/widget/WEntry.php';
include_once $path . '/jot-edit/scripts/php/widget/WButton.php';
include_once $path . '/jot-edit/scripts/php/page/PPage.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccess.php';




        //Manage our sessions to control the toolbar.
    require_once $path . '/jot-edit/scripts/php/manager/MSession.php';


    
    
    
    
    
    
    
    
            //Create a page.
    $newPage = new PPage(); 
        //Add Meta and other tags to the header.
    $newPage->AddMeta("robots", "noindex");//Dont want our index page in search results.
    $newPage->AddMeta("description", "Green and Gold Records Login page.");
    $newPage->AddCss("/jot-edit/css2/gngr2.css");        

        //Add the scripts to the page
    $newPage->AddScriptLink("https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" );
    //$newPage->AddScriptFile("../scripts/js/history/html4+html5/jquery.history.js");
    //$newPage->AddScriptFile("../scripts/js/state.js");
    $newPage->AddScriptLink("/jot-edit/scripts/js/interfaceEffects.js");
    $newPage->AddScriptLink("/jot-edit/scripts/js/action.js");
    
    
    
    
    
    
    
    
        
    
    
    
    
    
        //Load tool bar depending on the user logged in. 
    $am = MAccess::Get();
    if($am->HasAccess()){

        $newPage->AddToToolbar(new WButton('account','account','/jot-edit/action/account'));
        $newPage->AddToToolbar(new WButton('exit','sign out','/jot-edit/action/logout'));
        $newPage->AddToToolbar(new WButton('profile','profiles','/jot-edit/action/profiles'));
    }
    else{
        //Add the entry widget
        $newPage->AddToToolbar(new WEntry());
    }

    
    
    
    
    
    
    
    
    
    
    

        //Artist Panel **This will be changed to ajax once that is fully intergrated
    $ap = new CDiv("artist_list", array());
    $ap->AddChild(new CPara('te',array(),'artists artists artists.'));
    $newPage->AddToContent($ap);

    

    
    
    
    /***
    
    
    //HANDLE ACTUAL PAGE REQUEST    
    
    
    **/
    
        //see what account tool was requested.
    $action = filter_input(INPUT_GET, "request");
    $token  = filter_input(INPUT_GET, "token");
    $ticket = filter_input(INPUT_GET, "ticket");
    

         //Create the content panel
    $pan = new CDiv("", array('class'=>'account_panel'));
    
        //Depending on request get html block and store it in $pan
    switch($action){
        case 'login':
            $pan->AddChild(new WLogin('login_widget'));
            break;
        case 'logout':
            $am = MAccess::Get();
            $am->Logout();
            Redirect('../../../');//change to index page
            break;        
       case 'register-artist':
            $pan->AddChild(new WRegister('new_art_widget', 'artist', $ticket, $token));
            break; 
        case 'register-standard':
            $pan->AddChild(new WRegister('new_mem_widget', 'fan', $ticket, $token));
            break; 
        case 'register-contributor':
            $pan->AddChild(new WRegister('new_con_widget', 'contributor', $ticket, $token));
            break;         
        case 'password-recovery':
            $newPage->SetTitle('Password Recovery');
            $pan->AddChild(new WPasswordRecovery('prcvr_widget'));
            break;  
        case 'password-reset':
            $newPage->SetTitle('Password Reset');
            $pan->AddChild(new WPasswordReset('prst_widget', $ticket, $token));
            break;
        case 'verify':
            $newPage->SetTitle('Verification');
            $pan->AddChild(new WVerify('verify_widget',$ticket, $token));
            break;  
        default:
            Redirect404();
            break;
    }
    
        //Add the action widget holder to the page.
    $newPage->AddToContent($pan);
    
    
    
    
    
    
    
    
    
    
    
//NO Special permission is needed to access content so build the rest of the page.
    
        //Render the generated page.
    $newPage->Render();

    //Add more content panels
//$newPage->AddToContent(new CDiv("", array('class'=>'panelb')));
//$newPage->AddToContent(new CDiv("", array('class'=>'panelc')));






