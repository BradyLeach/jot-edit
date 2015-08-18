<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MContent.php';
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
class PFooter extends CFooter{

    
        /**
         * @breif Initializes the page footer.
         * @todo Fix this function so it is loaded from a configur file.
         * more a tester at this point.
         */        
    public function __construct() {
        parent::__construct("", "");
        
        $footerBar = new CDiv("footer_bar", array("class"=>"skewed emboss_l clearer_gold"));
        $footerBar->AddChild(new CDiv("footer_bar_rss", array("class"=>"unskew embed_l clearer_yellow")));
        $this->AddChild($footerBar);
        $this->AddChild(new CImage("", array("title"=>"Powered by ShystTek"), "/jot-edit/images/ShystTek.png", "Powered by ShystTek"));
        $this->AddChild(new CText("page_author", "address", "", "designs@shystTech.com"));           
    }
    
}//End of class