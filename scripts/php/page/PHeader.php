<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MContent.php';
include_once $path . '/jot-edit/scripts/php/page/PNav.php';

   /**
    * @author Brady Leach 
    * @date 23/06/2015
    * @brief This is a high level class built to simplify page template generation.
    * @note This object hold the base structure and main navigation menu for the 
    * green and gold records web page. It enables us to add in content during page generation as render is call 
    * after the entir page has been built.
    * 
    * @note this is a test class to define ways to stream line page generation 
    * in a dynaic state.  
    */
class PHeader extends CHeader{

/**
 * @breif Initializes the page header.
 * @todo Fix this function so it is loaded from a configur file.
 * more a tester at this point.
 */    
    
    
    
    
//public function __construct() {
//   parent::__construct("", "");

//    $menuOuter = new CDiv("main_nav_outer", array(/*"class"=>"skewed emboss_r clear_gold"*/));
//    $menuOuter->AddChild($menuInner = new CDiv("main_nav_inner", array(/*"class"=>"unskew embed_r yellow"*/)));
//   $this->AddChild($menuOuter);

//Create badge object
//     $gng_badge = new CDiv("gng_badge", array(/*"class"=>"curve_box_softer bumpy gold"*/));
//     $gng_badge->AddChild(new CImage("gng_nav_badge" , "", "images/bdg.png", "Green and Gold Records"));
//     $this->AddChild($gng_badge);
// $this->AddChild(new CImage("gng_nav_badge" , "", "images/bdg.png", "Green and Gold Records"));
//Get the page navigation
//    $this->AddChild(new PNav());
// }
    
    
   
         /**
         * @breif Initializes the page header.
         * @todo Fix this function so it is loaded from a configur file.
         * more a tester at this point.
         */      
    public function __construct($pageTitle) {
        parent::__construct("main_header", "");
        
        $nav    = new PNav("navigation");
        $badge = new CImage("badge" , "", "images/bdg.png", "Green and Gold Records Logo");
        
        //$this->AddChild($badge);
        $this->AddChild(new CText("main_title", "h1", array(), $pageTitle));
        $this->AddChild($nav);
    }   
    
    
        /**
         * @brief Sets the page heading text.
         * @param string $title the title of the page..
         */     
    public function SetPageHeading($title) {
        if($title) {
           $this->AddChild(new CText("main_title", "h1", array(), $title));        
        }
    }
    
    
    
}//End of class