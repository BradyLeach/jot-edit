<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';


include_once $path . '/jot-edit/scripts/php/widget/WButton.php';
include_once $path . '/jot-edit/scripts/php/widget/WLogin.php';
include_once $path . '/jot-edit/scripts/php/widget/WEntry.php';
include_once $path . '/jot-edit/scripts/php/page/PPage.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccess.php';




    //Manage our sessions to control the toolbar.
require_once $path . '/jot-edit/scripts/php/manager/MSession.php';
    
        //see what account tool was requested.
    $request = filter_input(INPUT_GET, "request");

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
    
    
    
    
    /**
     *  MAIN PAGE TOOL BAR
     * 
     */
        //Load tool bar depending on the user logged in. 
    $am = MAccess::Get();
    if($am->HasAccess()){

        $newPage->AddToToolbar(new WButton('account','account','/jot-edit/action/account'));
        $newPage->AddToToolbar(new WButton('exit','sign out','/jot-edit/action/logout'));
    }
    else{
        //Add the entry widget
        $newPage->AddToToolbar(new WEntry());
    }
    
    

    
    /**
     *  MENU DROP DOWNS
     * 
     */
    
        //Artist Panel **This will be changed to ajax once that is fully intergrated
    $ap = new CDiv("artist_list", array());
    $ap->AddChild(new CPara('te',array(),'artists artists artists.'));
    $newPage->AddToContent($ap);

    


    /**
     *  MAIN PAGE CONTENT
     * 
     */
    //Create the content panel
    $pan = new CDiv("", array('class'=>'account_panel'));
    $newPage->AddToContent($pan);
    
    
switch ($request){
    case 'unauthorized-access':
        //Add the entry widget
        $pan->AddChild(new CText('', 'h1', array('class'=>'error'), 'You must be logged in to access this area.'));
        //$pan->AddChild(new WLogin('login_widget'));
        $newPage->SetTitleAndHeading('UNAUTHORISED ACCESS');
        break;
    case 'database error':
        //Add the entry widget
        $pan->AddChild(new CText('', 'h1', array('class'=>'error'), 'There has been a technical glitch.Im not sayign it was the jews but you know '));
        $newPage->SetTitleAndHeading('UNAUTHORISED ACCESS');
        break;
    default:
        echo 'default';
        break;
}
    
    
    
    
        //Render the generated page.
    $newPage->Render();
    





