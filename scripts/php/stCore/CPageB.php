<?php

include_once 'stCore.php';

    /**
     * @author  Brady Leach 
     * @date	23/03/2015
     * @brief   The CPage holds a header and body to be filled with content. 
     * 
     * @see    CHtml for detailed behaviour of a CHtml.
     * 
     *      //Create A CPage Object     
     *       $page = new CPage($pageTitle, $pageCSS, $jScript, $docType)
     * 
     *      //Example Source     
     *      to come
     * 
     *      //To output a PostNode      
     *      $CPage->Render();
     *      
     * @todo Add input validation.
     * @todo Add in meta tag support. 
     * 
     */
class CPageB extends CHtml {
    
    protected $m_docType;   //The doctype of the loaded page.
    protected $m_head;      //A html CHead object
    protected $m_body;      //A CHtml object
    
    
         /**
         * @brief   __construct A CHead object.
         * @param string $docType The page doctype setting.  	
         */
    function __construct($docType="!DOCTYPE html") {
        
        parent::__construct("", "html", "");
        $this->m_docType = $docType;
        
            //init an empty page head block
        $this->InitHead();
        
            //Init an empty table body
        $this->InitBody();
        
    }
  
       /**
        * @brief Initializes the page head. 
        */ 
    private function InitHead() {
            //Create the html CHead and add it to the page as a child.
        $this->m_head = new CHtml("", "head", "");
        $this->AddChild($this->m_head);
    }

       /**
        * @brief Initializes the page body. 
        */     
    private function InitBody() {
            //Create the html body and add it to the page as a child.
        $this->m_body = new CHtml("", "body", "");
        $this->AddChild($this->m_body);
    }
    
        /**
         * @brief Renders the CPage and all its children.
         * @note This function should be the only function the user calls to 
         * draw a CPage. It only needs to be call once after the complet page 
         * hierachy construction has been completed. 	
        */
    public function Render() {
            //output the doctype tag.
        echo "<".$this->m_docType.">\n";
        
            //Output the complete html page.
        $this->Output();
    }
    
    
        /**
         * @brief   Add a CHtml element to the page body.
         * @param string $content The CHtml( or child classes of CHtml) 
         * to be added to the page body. 
         */     
    protected function AddToBody($content){
        $this->m_body->AddChild($content);
    }
    
    
     
        /**
         * @brief Add a script file to the page body.
         * @param string $scriptHref A path to the script file. 
         * @param string $type The type of script being added.
         * @note The script is added to the body. This is to help speed up
         * load time. Scripts should be added just before the render call.
         */        
    public function AddScriptFile($scriptHref, $type="text/javascript") {
        if($scriptHref) {
            $this->m_body->AddChild(new CHtml("","script", array("src"=>$scriptHref, "type"=>$type)));
        }
    }
    
        /**
         * @brief Add a script file to the page body.
         * @param string $scriptHref A path to the script file. 
         * @note The script is added to the body. This is to help speed up
         * load time. Scripts should be added just before the render call.
         */        
    public function AddScriptLink($scriptHref ) {
        if($scriptHref) {
            $this->m_body->AddChild(new CHtml("","script", array("src"=>$scriptHref)));
        }
    }
    
        /**
         * @brief Add a script to the page head.
         * @param string $script A js text script. 
         * @param string $type The type of script being added.
         */    
    public function AddScript($script, $type="text/javascript") {
        if($script) {
            $this->m_head->AddChild(new CText( "","script", array("type"=>$type), $script )); 
        }
    }
    
       /**
        * @brief Add a meta tag to the page head.
        * @param string $name The name attribute for the meta tag. 
        * @param string $content The content value for the meta tag. 
        */ 
    public function AddMeta($name, $content) {
        if($name && $content) {
            $this->m_head->AddChild(new CXhtml("","meta", array("name"=>$name, " content"=>$content))); 
        }
    }
    
       /**
        * @brief Add a base tag to the page head.
        * @note If you are using htaccess and mod_rewrite links can become 
        * unstable. Adding a base enables you to maintain relative links.
        */ 
    public function AddBase($base) {
        if($base) {
            $this->m_head->AddChild(new CXhtml("","base", array("href"=>$base))); 
        }
    }
    
       /**
        * @brief Add a link tag to the page head.
        * @param string $href src reference to the link. 
        * @param string $rel the relationship to the document. 
        * @param string $type the type of link. 
        */     
    public function AddLink($href, $rel, $type ) {
        if($href && $rel && $type){
            $this->m_head->AddChild(new CXhtml("","link", array("href"=>$href, "rel"=>$rel, "type"=>$type)));
        }
    }
    
        /**
         * @brief Add acss file link tag to the page head.
         * @param string $cssPath path to the css document.
         */      
    public function AddCss($cssPath) {
        if($cssPath) {
            $this->m_head->AddChild(new CXhtml("","link", array("href"=>$cssPath, "rel"=>"stylesheet", "type"=>"text/css")));        
        }
    }
    
        /**
         * @brief Sets the page title tag..
         * @param string $title the title of the page..
         */     
    public function SetTitle($title) {
        if($title) {
            $this->m_head->AddChild(new CText("title", "title", "", $title));        
        }
    }
    
        /**
         * @brief Sets the page title tag..
         * @param string $charset Set the char set for the page.
         */     
    public function SetCharset($charset) {
        if($charset) {          
            $this->m_head->AddChild(new CXhtml("","meta", array("charset"=>$charset)));
        }
    }
    
}
