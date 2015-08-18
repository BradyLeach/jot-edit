<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';

include_once $path . '/jot-edit/scripts/php/widget/WName.php';
include_once $path . '/jot-edit/scripts/php/widget/WEmail.php';
include_once $path . '/jot-edit/scripts/php/widget/WPhone.php';
include_once $path . '/jot-edit/scripts/php/widget/WAddress.php';
include_once $path . '/jot-edit/scripts/php/widget/WResetPassword.php';
include_once $path . '/jot-edit/scripts/php/widget/WInviteCreator.php';
include_once $path . '/jot-edit/scripts/php/widget/WEntry.php';
include_once $path . '/jot-edit/scripts/php/widget/WAccountToolBar.php';
include_once $path . '/jot-edit/scripts/php/widget/WAccountTools.php';
include_once $path . '/jot-edit/scripts/php/widget/WButton.php';

include_once $path . '/jot-edit/scripts/php/page/PPage.php';
include_once $path . '/jot-edit/scripts/php/manager/MAccess.php';




    //Manage our sessions to control the toolbar.
require_once $path . '/jot-edit/scripts/php/manager/MSession.php';



    //Load tool bar depending on the user logged in. 
$am = MAccess::Get();
if(!$am->HasAccess()){ Redirect('../../../error/unauthorized-access');}
    
    
    //see what account tool was requested.
$request = filter_input(INPUT_GET, "request");

    //Create a page.
$newPage = new PPage("jot-edit - account tools"); 






    //Add Meta and other tags to the header.
$newPage->AddMeta("robots", "noindex");//Dont want our index page in search results.
$newPage->AddMeta("description", "Green and Gold Records Login page.");
$newPage->AddCss("/jot-edit/css/jotedit.css");        

    //Add the scripts to the page
$newPage->AddScriptLink("https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" );
//$newPage->AddScriptFile("../scripts/js/history/html4+html5/jquery.history.js");
//$newPage->AddScriptFile("../scripts/js/state.js");
$newPage->AddScriptLink("/jot-edit/scripts/js/interfaceEffects.js");
$newPage->AddScriptLink("/jot-edit/scripts/js/action.js");








//$newPage->AddToToolbar(new WButton('account','account','/jot-edit/action/account'));
$newPage->AddToToolbar(new WButton('exit','sign out','/jot-edit/action/logout'));
$newPage->AddToToolbar(new WButton('profile','profiles','/jot-edit/action/profile'));

//  $newPage->AddToToolbar(new WAccountTools('account', array('class'=>'widget'), TRUE));
//Do stuff in here
//
//
        //Artist Panel **This will be changed to ajax once that is fully intergrated
$ap = new CDiv("artist_list", array());
$ap->AddChild(new CPara('te',array(),'artists artists artists.'));
$newPage->AddToContent($ap);



$at = new CDiv("account_tools_holder", array());
$at->AddChild(new WAccountToolBar());
$newPage->AddToContent($at);




//Create the content panel
$pan = new CDiv("", array('class'=>'account_panel'));
$newPage->AddToContent($pan);


switch($request){
    case 'name':
        $pan->AddChild(new WName('edit_name'));
        break;
    case 'email':
        $pan->AddChild(new WEmail('145'));
        break;
    case 'address':
        $pan->AddChild(new WAddress("address"));
        break;
    case 'phone':
        $pan->AddChild(new WPhone('edit_phoneNumber' ));
        break;      
    case 'password':
        $pan->AddChild(new WResetPassword('reset_password'));
        break;
    case 'invite':
        $pan->AddChild(new WInviteCreator('inviteCreator'));
        break;
    default:
        $pan->AddChild(new WName('edit_name'));
        break;
}




    //Render the generated page.
$newPage->Render();



