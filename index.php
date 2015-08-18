<?php
include_once "scripts/php/stCore/stCore.php";
include_once "scripts/php/widget/WEntry.php";
include_once "scripts/php/widget/WButton.php";
include_once "scripts/php/widget/WAccountTools.php";
include_once "scripts/php/widget/WProfileSelector.php";
include_once "scripts/php/page/PPage.php";
include_once "scripts/php/manager/MAccess.php";




    //Manage our sessions to control the toolbar.
require_once 'scripts/php/manager/MSession.php';

//$request = filter_input(INPUT_GET, "request");




    //Create a page.
$newPage = new PPage("Jot-edit.com"); 


/*
 *  HTML HEAD INIT
 */


    //Add Meta and other tags to the header.
$newPage->AddMeta("keywords", "collaboration, script, screenplay, stageplay, text editor, script manager");
$newPage->AddMeta("description", "Jot-Edit is a collaborative note taking and text editor.");
$newPage->AddCss("css/jotedit.css");        

    //Add the scripts to the page
$newPage->AddScriptLink("https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js", "");
$newPage->AddScriptFile("scripts/js/interfaceEffects.js");
$newPage->AddScriptFile("scripts/js/action.js");


/*
 *  ACCESS CONTROLL
 */

    //Load tool bar depending on the user logged in. 
$am = MAccess::Get();
if($am->HasAccess()){
   // $newPage->AddToToolbar(new GNGW_Button('account','account','/jot-edit/action/account'));
    
    $newPage->AddToToolbar(new WButton('exit','sign out','/jot-edit/action/logout'));
    $newPage->AddToToolbar(new WButton('account','account','/jot-edit/action/account'));
}
else{
    $newPage->AddToToolbar(new WEntry());     
}



/*
 *  MAIN PAGE INIT
 */

    //Artist Panel **This will be changed to ajax once that is fully intergrated
$ap = new CDiv("artist_list", array());
$ap->AddChild(new CPara('te',array(),'this is some text in the artist panel.'));
$newPage->AddToContent($ap);



    //Add the content panel
$pan = new CDiv("", array('class'=>'panel'));
$newPage->AddToContent($pan);

    //Add more content panels
$newPage->AddToContent(new CDiv("", array('class'=>'panelb')));
$newPage->AddToContent(new CDiv("", array('class'=>'panelc')));






/*
 *  RENDER THE PAGE THAT WAS BUILT
 */

    //Render the generated page.
$newPage->Render();








/**
 * LoadIndexPage()
 * new PPageage("Green and Gold Records")
 * PPageage->getHeader()->AddMeta("name", array("values"));
 * PPageage->getHeader()->AddMeta("name", array("values"));
 * load latest 10 posts
 */
/**
 * LoadContactPage()
 * new PPageage("contact page")
 * PPageage->getHeader()->AddMeta("name", array("values"));
 * PPageage->getHeader()->AddMeta("name", array("values"));
 * load all contact cards
 */