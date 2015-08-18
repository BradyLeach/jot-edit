<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
include_once $path . '/jot-edit/scripts/php/manager/MContent.php';
include_once $path . '/jot-edit/scripts/php/page/PHeader.php';
include_once $path . '/jot-edit/scripts/php/page/PFooter.php';
include_once $path . '/jot-edit/scripts/php/page/PToolbar.php';

   /**
    * @author Brady Leach 
    * @date 23/03/2015
    * @brief This is a high level class built to simplify page template generation.
    * @note This object hold the base structure and main navigation menu for the 
    * green and gold records web page. It is used inplace of requiring code snippets.
    * It enables us to add in content during page generation as render is call 
    * after the entir page has been built.
    * 
    * @note this is a test class to define ways to stream line page generation 
    * in a dynaic state.
    *   
    * 
    * @see    CPageB for detailed behaviour of parent class. 
    * 
    *      //Create A Head Object     
    *       $PPageage = new GNG_Page($pageTitle);
    * 
    *      //Example Source           
    *      //Add a ContentHeader Object to the content holder
    *      $PPageage->AddToContent(new CDiv("content", array("class"=>"curve_box_softer stroke_white clearest_skyblue")));
    * 
    *      //To output a PostNode      
    *      $PPageage->Render();
    *
    * @note The page consistes of the following container hierachy.
    * 
    *      body
    *           header
    *           container                                  
    *           footer
    *           script
    *                 
    * 
    * The structure is initialized when the object is created. Content is 
    * later added to these designated contianers through member functions.
    * 
    *      $myPage->AddToContent($myHtmlObject);
    * 
    * @note Initializing the structure enables us to add scripts to the 
    * page at any time during page generation and know they will always be 
    * the last chld of the page body.  
    */
class PPage extends CPageB{
    private $m_tools;      //The page toolbar. 
    private $m_header;      //The page header. This is a header object.
    private $m_container;   //All content containers are children of this container.
    private $m_footer;      //The page footer. This is a footer object.
   
    
    public function __construct($pageTitle="GREEN AND GOLD RECORDS") {
        parent::__construct();
        
        $this->InitHead($pageTitle);
        $this->InitBody($pageTitle);
    }
  
    public function AddToHeader($content) {
        if(is_a($content, "CHtml")) {
            $this->m_header->AddChild($content);
        }     
    }  
    public function AddToToolbar($toolWidget) {
        if(is_a($toolWidget, "CHtml")) {
            $this->m_tools->AddChild($toolWidget);
        }     
    } 
    
    public function AddToContent($content) {
        if(is_a($content, "CHtml")) {
            $this->m_container->AddChild($content);
        }        
    }
    
    public function AddToFooter($content) {
        if(is_a($content, "CHtml")) {
            $this->m_footer->AddChild($content);
        }        
    }
    
        /**
         * @breif Initializes the head for the green and gold records page.
         */
    private function InitHead($pageTitle) {
        
        $this->SetTitle($pageTitle); 
        $this->SetCharset("UTF-8");
        $this->AddMeta("viewport", "width=device-width");        
    }
    
        /**
         * @brief Sets the page heading text.
         * @param string $title the title of the page..
         */     
    public function SetHeading($title) {
        if($title) {
          $this->m_header->SetPageHeading($title);
        }
    }
    
        
        /**
         * @brief Sets the page heading text.
         * @param string $title the title of the page..
         */     
    public function SetTitleAndHeading($title) {
        if($title) {
          $this->m_header->SetPageHeading($title);
          $this->SetTitle($title);
        }
    }
        /**
         * @breif Initializes the body for the green and gold records page.It 
         * sets up all content containers. These include, main content, header 
         * and footer. It the calls the functions that sets up the visual 
         * interface for the page.
         */
    private function InitBody($pageTitle) {
        
        $this->m_tools = new PToolbar();
        $this->AddToBody($this->m_tools);
        
            //Create a header object
        $this->m_header = new PHeader($pageTitle);
        $this->AddToBody($this->m_header);
        
            //Create the main page  structure-content, userpane and adds.
        $this->m_container = new CDiv("container_holder", array("class"=>"container")); 
        $this->AddToBody($this->m_container);
        
        $this->m_footer = new PFooter();
        $this->AddToBody($this->m_footer);
    }
    
/*    
    //being moved to acion panel
    private function GenerateArtistList() {

        $cmanager = MContent::Get();
        $artists = $cmanager->GetLiveArtists();
                
        $artist_nav = new CDiv("artist_nav", "");
        $artist_menu = new CList("artist_menu", "ul", "");    
        $artist_nav->AddChild($artist_menu);
        
        foreach($artists as $artist) {
            $artist_menu->AddItem($artist['name'], "", 
                    new CLink("btn_".$artist['name'], "", $artist['name'], "http://localhost/jot-edit/artists/".$artist['url']));
        }

        return( $artist_nav );
            
    }  
       */

}//End of class.



