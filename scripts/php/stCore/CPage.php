<?php

   /**
    * @author Brady Leach 
    * @date 23/03/2015
    * @class CHtmlHead 
    * 
    * @brief A encapsulated html head block. This class is used to simplify
    * creating a html head head block.   
    * 
    * @see CHtml for detailed behaviour of a CHtml.
    * 
    * 
    *       //Create A CHead Object
    *       $head = new CHead($pageTitle, $pageCSS, $jScripts)
    * 
    *       //To output a PostNode      
    *       $head->Output();
    * 
    * 
    
    * 
    * @todo Add input validation.
    * @todo Add in meta tag support. 
    * @todo Add in functionality to support being pased an array of css and script files.
    * @todo Add in functionality to add css and js script files insertion.  
    * 
    */
class CHtmlHead extends CHtml {
    
    
         /**
         * @brief   __construct A CHead object.
         * 
         * @param string $pageTitle The title for the current page (if empty, one will be generated).
         * @param string $pageCSS A path to the base css document. 
         * @param string $jScript A path to the base JS document. 
        */
    function __construct($pageTitle, $pageCSS, $jScript){
        
            //initialize the base object.
        parent::__construct($pageTitle, "head", "");
        
            //Add in the head details.
        $this->AddChild(new CXhtml("meta","meta", array("charset"=>"utf-8")));
        $this->AddChild(new CXhtml("view","meta", array("name"=>"viewport", " content"=>"width=device-width")));
        $this->AddChild(new CText("title", "title", "", $pageTitle));
        $this->AddChild(new CXhtml("css","link", array("href"=>$pageCSS, "rel"=>"stylesheet", "type"=>"text/css")));
        $this->AddChild(new CHtml("js","script", array("src"=>$jScript)));
        
                
    }    
    
    //AddScript
    //AddMeta
    //addCss
    //setTitle
    
    
}//End of CHead class




    /**
     * @author  Brady Leach 
     * @date	23/03/2015
     * @brief   The CPage holds a header and body to be filled with content. 
     * 
     * @see    CHtml for detailed behaviour of a CHtml.
     *  
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
     * 
     * @todo Add input validation.
     * @todo Add in meta tag support. 
     * 
     */
class CPage extends CHtml {
    
    protected $m_head;      //A html CHead object
    protected $m_body;      //A CHtml object
    protected $m_docType;   //The doctype of the loaded page.
    
         /**
         * @brief   __construct A CHead object.
         * 
         * @param string $pageTitle The title for the current page (if empty, one will be generated).
         * @param string $pageCSS A path to the base css document. 
         * @param string $jScript A path to the base JS document.
         * @param string $docType The page doctype setting. 
         * 	
        */
    function __construct($pageTitle, $pageCSS, $jScript, $docType) {
        
        parent::__construct("html", "html", "");
        $this->m_docType = $docType;
        
            //Create the html CHead and add it to the page as a child.
        $this->m_head= new CHtmlHead($pageTitle, $pageCSS, $jScript);
        $this->AddChild($this->m_head);
        
            //Create the html body and add it to the page as a child.
        $this->m_body = new CHtml("", "body", "");
        $this->AddChild($this->m_body);
    }
    
        /**
         * @brief   Add a CHtml to the page body.
         * 
         * @param string $content The CHtml( or child classes of CHtml) 
         * to be added to the page body. 
         * 	
        */     
    function AddContent($content){
        $this->m_body->AddChild($content);
    }
    
    
        /**
         * @brief Renders the CPage and all its children.
         *  
         * @note This function should be the only function the user calls to 
         * draw a CPage. It only needs to be call once after the complet page 
         * hierachy construction has been completed. 	
        */
    function Render() {
            //output the doctype tag.
        echo "<".$this->m_docType.">\n";
        
            //Output the complete html page.
        $this->Output();
    }
    
    public function GetPageHead() {
        return ($this->m_head);
    }
}