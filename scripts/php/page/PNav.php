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
class PNav extends CNav{

    
        /**
         * @breif Initializes the page footer.
         * @todo Fix this function so it is loaded from a configur file.
         * more a tester at this point.
         */        
    public function __construct($id) {
        parent::__construct($id, array('class'=>'main_nav'));

        $navList = new CList($this->m_id . '_list', 'ul', array());
        
        $navList->AddItem('', array(), new CLink("home_btn",array('class'=>'nav_item'),"home", "/jot-edit/"));
        $navList->AddItem('', array(), new CLink("blogs_btn",array('class'=>'nav_item'),"blogs", "/jot-edit/blogs"));
        $navList->AddItem('', array(), new CLink("other_btn",array('class'=>'nav_item'),"features", "/features"));
    
        
        $this->AddChild($navList);
    }
    
}//E