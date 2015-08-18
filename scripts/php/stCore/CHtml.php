<?php
	//Include parent class explisitly.
	include_once "CNode.php";
	include_once "CHtmlTag.php";
        include_once "Helpers.php";
	 
    
   /**
    * @author   Brady Leach
    * @date     19/01/2014
    * @class    CHtml
    * 
    * @param $m_tag A CHtmlTag object. 
    * @param $m_tagedg A boolean flag. True if CHtml has a valid tag 
    * otherwise false.
    * 
    * @brief    A CHtml holds a list of child CNode objects. CNode objects
    * include CImage, CText etc. A CHtml extends CNode and can hold 
    * children but has no content. Other objects with content to output 
    * will extend from CHtml. These childv classes will customise the 
    * output functions for the values they hold.  
    * 
    * @note The display order for a CHtml node is as follows :
    *       -element id attributes[key=>value,...], src - open tag 
    *           - children
    *       -element - closing tag
    *   
    * @note If no tag is set then only the children will be output.     
    * 
    * 
    * 
    *      //Create CHtml
    *      $CHtml = new CHtml($id, $element, $attributes)
    * 
    *      //Example Source 
    *       myHtml = new CHtml("", "div", array("class"=>"myCssClass"));
    * 
    *       //To Add child objects individually  
    *       myHtml->AddChild($myNode)//mixed var
    * 
    *       //To Add child object lists 
    *       myHtml->AddChildlren($myNodes)//mixed var array
    * 
    *       //To output a CMediaBase 
    *       myHtml->Output();
    * 
    * 
    *  @see CText, CMediaBase, CImage for similar classes
    * 
    * @todo Need to make a TOHtml function that out puts the 
    * object as a string of valid html5.
    * 
    */
class CHtml extends CNode {
		
        
	protected $m_tag;       ///The CHtmlTag for the object.	
        protected $m_tagged;    ///If the object has a html5 valid tag. 	
 

        /**
        * @author Brady Leach  
        * @date 19/01/2014
        * @brief Constructor - Creates a CHtml.
        * 
        * @param string $id A user entered identification string.[If not supplied one will be generated]. 
        * @param string $element A html element [a, p, div etc]. 
        * @param array $attributes An array of html tag attributes.
        *
        * @note There is no id checking in place. May add at a later date. The 
        * user should make sure non generated ids are unique.
        * 
        * @see     CImage, CVideo, CAudio 
        * @todo    Add a html tag validator.                
        * 	
        */
    function __construct( $id, $element, $attributes) {

            //Initialise parents
        parent::__construct( $id );

        if($element) {             
            $this->SetTag($element, $attributes);            
            //Add the id to the tag attributes.
            $this->AddAttribute( "id",$this->m_id );
        }
        else
        {
            //If there is element set the tagged flag to false.
            $this->m_tagged = FALSE;	
        }
    }
    
    
        /**
        * @brief    Sets the tag for the html element
        *
        * @param   $element     - A html element [a, p, div etc]. 
        * @param   $attributes  - An array of html tag attributes. 
        */
    function SetTag( $element, $attributes) {

            //Try create  the tag if it is set.
        if($element) { 
                //Create a new tag object with the html element and the attributes.
            $this->m_tag = new CHtmlTag( $element, $attributes );

                //Set the tagged flag to true.
            $this->m_tagged = TRUE;
        }//End if
    }
    
        /**
        * @brief Outputs the CNode contents and its children if they exist.
        * @see CHtml for display details 
        *  
        */
    public function Output() {
            //If the tag is set output it.
        if($this->m_tagged) {
                //displays open tag.
            $this->m_tag->OutputOpen();

                //If the object has children output them.
            $this->OutputChildren();

                //Outputs closing tag.
            $this->m_tag->OutputClose();
        //If no tag was set then just display the children
        } else {
                //If the object has children output them.
            $this->OutputChildren();
        }
    }
        
        /**
        * @brief Adds an attribute to the tag.
        * @note CHtml This is to avoid directly accessing the tag member.
        * 
        */
        function AddAttribute($attribute, $value){
            if($attribute && $value) {
                $this->m_tag->AddAttribute($attribute, $value);
        }
    }
    
            /**
        * @brief Adds an attribute to the tag.
        * @note CHtml This is to avoid directly accessing the tag member.
        * 
        */
        function AddAttributes($attributes){
            if($attributes) {
                $this->m_tag->AddAttributes($attributes);
        }
    }        
           
        /**
        * @brief   Iterates through all children calling thier outputs.
        * @see     CHtml for display details 
        * 
        */
    function OutputChildren(){

        if($this->m_children and is_array($this->m_children))
        {
            foreach ($this->m_children as $child)
            {
                    $child->Output();	
            }
        }
    }		
}//END OF CHtml






        /**
        * @author   Brady Leach
        * @date     19/01/2014
        * @class    CXhtml
        * 
        * @brief A CXhtml handles the provides an overloaded output
        * function to handle xhtml tags.    
        * 
        * @note The display order for a CXhtml node is as follows :
        *       element id attributes[key=>value,...] /> 
        *           children
        * @note If no tag is set then only the children will be output.     
        * 
        * @see CHtml fro detailed usage. 
        * @see CText, CMediaBase, CImage for similar classes
        * 
        */
class CXhtml extends CHtml {
    
        /**
         * @brief   Outputs the CNode contents and its children if they exist 
         * using xhtml closing tags.
         * 
         * @see     CHtml for display details 
         * 
         */
    function Output() {

            //If the tag is set output it.
        if($this->m_tagged) {
                //displays open tag.
            $this->m_tag->OutputOpen();

                //If the object has children output them.
            $this->OutputChildren();
            
        //If no tag was set then just display the children   
        } else {
                //If the object has children output them.
            $this->OutputChildren();
        }
    }
        
        
        /**
        * @brief Sets the tag for the html element.
        *
        * @param string $element A html element [a, p, div etc].
        * @param array $attributes [attribute=>value] An array of html tag attributes.
        *
        * @note This function is used to help "overload" the parent contructor.  
        */
    function SetTag( $element, $attributes) {
 
            //Try create  the tag if it is set.
        if($element) { 
            
                //Create a new tag object with the html element and the attributes.
            $this->m_tag = new CXhtmlTag( $element, $attributes );
        
                //Set the tagged flag to true.
            $this->m_tagged = TRUE;
        }        
    }    
} //End of CXhtml Class




    /**
     * @author  Brady Leach
     * @date	19/01/2014
     * @class   CText
     * 
     * @param string $m_text A string of text to print.
     * 
     * @brief Creates a CText. A CText holds a string of text to be output.
     * 
     * @note The display order for a CText is as follows :
     *      element id attributes[key=>value,...], src > - open tag
     *          $m_text
     *          children
     *      element - close tag
     * If no tag is set, the text and the children will be output.         
     *
     * 
     * 
     *      //Create CText 
     *       $CText = new CText($id, $element, $attributes, $text)
     * 
     *      //Example Source 
     *       myText = new CText("myID", "div", array("class"=>"cCssClass"), "hello world");
     * 
     *      //To output a CMediaBase 
     *      myText->Output();
     * 
     * 
     *  @see    CHtml for detailed behaviour of a CHtml.
     * 
     */
class CText extends CHtml {

        
 	protected $m_text;    ///The text content of the CText.	
  
 
        /**
        * @brief   __construct A html object that display a string of text before its children.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param string $element A html element [a, p, div etc].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        *	
        * 	
        */
    function __construct( $id, $element, $attributes, $text ) {
            //Set the parent
        parent::__construct( $id, $element, $attributes );

            //Set the text content.
        $this->m_text =  $text;		
    }
    
    public function SetText($text) {
        if($text && strlen($text) > 0) {
            $this->m_text = $text;
        }
        
    }
	
	
   
        /**
        * @brief Outputs the CNode and its children.
        * @see CHtml for display details 
        * 
        */
    function Output() {

            //If there is a tag set display it,
            //Otherwise display the text and the children. 
        if($this->m_tagged) {
            $this->m_tag->OutputOpen();

                //Output the text.
            echo $this->m_text;

                //If the object has children output them.
            $this->OutputChildren();

            $this->m_tag->OutputClose();
        }else {

                //Output the text.
            echo $this->m_text;

                //If the object has children output them.
            $this->OutputChildren();
        }
    }	
}//End of CText Class



/**
     * @author  Brady Leach
     * @date	19/01/2014
     * @class   CLink
     * 
     * @param string $m_text A string of text to print.
     * @param string $m_link A string of text to print.
     * 
     * @brief A CLink holds an a tag with a ref="" and a string of text to
     * display. Children can be added like all CHtml extensions.  
     * 
     * @note The display order for a CText is as follows :
     *      a tag with link
     *          $m_text
     *          children
     *      a close tag
     *
     * 
     * @note example usage:
     * 
     *  
     *      //Create CText 
     *       $myLink = new CLink($id, $attributes, $text, $ref);
     * 
     *      //Example Source 
     *       myLink = new CLink("myID", array("class"=>"cCssClass"), "hello world", "www.helloworldlink.com");
     * 
     *      //To output a CMediaBase 
     *      myLink->Output();
     * 
     * 
     *  @see    CHtml for detailed behaviour of a CHtml.
     * 
     */
class CLink extends CText {

        var $m_link;   ///The text content of the CText.
 
        
        /**
        * @brief   __construct A html CLink object that display a 
        * string of text before its children, contained within an anchor tag.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        * @param string $ref the reference link].	
        * 	
        */
    function __construct( $id, $attributes, $text, $ref) {
            //Set the parent
        parent::__construct( $id, "a", $attributes, $text );

            //Set the text content.
        $this->m_text =  $text;	
        $this->m_link =  $ref;	
        
            //Add the id to the tag attributes.
        $this->m_tag->AddAttribute( "href", $this->m_link);
    }

}//End of CLink Class





/**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CDiv
     * 
     * @brief A CDiv holds a div tag and a string of text to
     * display. Children can be added like all CText extensions.  
     * 
     * @note example usage:
     * 
     *  
     *      //Create CText 
     *       $myDiv = new CDiv($id, $attributes, $text);
     * 
     *      //Example Source 
     *       myDiv = new CDiv("myID", array("class"=>"cCssClass"), "hello world");
     * 
     *      //To output a CMediaBase 
     *      myDiv->Output();
     * 
     * 
     *  @see    CHtml for detailed behaviour of a CHtml.
     * 
     */
class CDiv extends CHtml {
 
        
        /**
        * @brief   __construct A html CDiv object that display a 
        * string of text before its children, contained within an div tag.
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * 	
        */
    function __construct( $id, $attributes) {
            //Set the parent
        parent::__construct( $id, "div", $attributes);

    }

}//End of CDiv Class




/**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CSection
     * 
     * @brief A CSection holds a div tag and a string of text to
     * display. Children can be added like all CText extensions.  
     * 
     *  @see CDiv for detailed behaviour of a CSection.
     * 
     */
class CSection extends CText {
 
        
        /**
        * @brief   __construct A html CDiv object that display a 
        * string of text before its children, contained within an div tag.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        * 	
        */
    function __construct( $id, $attributes, $text="") {
            //Set the parent
        parent::__construct( $id, "section", $attributes, $text );

    }

}//End of CSection Class



/**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CHeader
     * 
     * @brief A CHeader holds a div tag and a string of text to
     * display. Children can be added like all CText extensions.  
     * 
     *  @see CDiv for detailed behaviour of a CSection.
     * 
     */
class CHeader extends CText {
 
        
        /**
        * @brief   __construct A html CHeader object that display a 
        * string of text before its children, contained within an div tag.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        * 	
        */
    function __construct( $id, $attributes, $text="") {
            //Set the parent
        parent::__construct( $id, "header", $attributes, $text );

    }

}//End of CSection Class

/**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CFooter
     * 
     * @brief A CFooter holds a div tag and a string of text to
     * display. Children can be added like all CText extensions.  
     * 
     *  @see CDiv for detailed behaviour of a CSection.
     * 
     */
class CFooter extends CText {
 
        
        /**
        * @brief   __construct A html CFooter object that display a 
        * string of text before its children, contained within an div tag.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        * 	
        */
    function __construct( $id, $attributes, $text="") {
            //Set the parent
        parent::__construct( $id, "footer", $attributes, $text );

    }

}//End of CSection Class

/**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CArticle
     * 
     * @brief A CArticle holds a div tag and a string of text to
     * display. Children can be added like all CText extensions.  
     * 
     *  @see CDiv for detailed behaviour of a CSection.
     * 
     */
class CArticle extends CText {
 
        
        /**
        * @brief   __construct A html CArticle object that display a 
        * string of text before its children, contained within an div tag.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        * 	
        */
    function __construct( $id, $attributes, $text="") {
            //Set the parent
        parent::__construct( $id, "article", $attributes, $text );
    }

}//End of CSection Class

/**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CPara
     * 
     * @brief A CParagraph holds a div tag and a string of text to
     * display. Children can be added like all CText extensions.  
     * 
     *  @see CDiv for detailed behaviour of a CSection.
     * 
     */
class CPara extends CText {
 
        
        /**
        * @brief   __construct A html CArticle object that display a 
        * string of text before its children, contained within an div tag.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        * 	
        */
    function __construct( $id, $attributes, $text="") {
            //Set the parent
        parent::__construct( $id, "p", $attributes, $text );
    }

}//End of CSection Class


/**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CNav
     * 
     * @brief A CNav holds a nav tag and a string of text to
     * display. Children can be added like all CText extensions.  
     * 
     *  @see CDiv for detailed behaviour of a CSection.
     * 
     */
class CNav extends CText {
 
        
        /**
        * @brief   __construct A html CArticle object that display a 
        * string of text before its children, contained within an div tag.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $text The CNode objects text content.
        * 	
        */
    function __construct( $id, $attributes, $text="") {
            //Set the parent
        parent::__construct( $id, "nav", $attributes, $text );
    }

}//End of CNav Class







    /**
     * @author Brady Leach
     * @date 22/01/2014
     * @class CImage
     * 
     * @param string $m_src The path to the media content.
     * 
     * @brief An CImage holds a path to locate the image source. Images 
     * are a part of the media node family
     *  
     * @note Unlike other CHtml objects media nodes, such as CImage and 
     * CVideo objecs close the html element tag before displaying the 
     * children elements.This allows for easy gallery and media nesting 
     * 
     * @note The display order for a media node is as follows:
     *      mediaElement id attributes[key=>value,...], src - open tag
     *      mediaElement - close tag
     *          children
     * 
     * 
     *          //Create CImage 
     *          $CImage = new CImage($id, $attributes, $source, $alt)
     * 
     *          //Example Source  
     *          myImage = new CImage($imageID, array("class"=>"myCssClass"), ../MyImagePath/image.png", "this is the image alt text"  );
     * 
     *          //To output a CImage  
     *          myImage->Output();
     * 
     *
     * @see CHtml for detailed behaviour of a CHtml.
     * @see CVideo and CAudio.     
     * 
     */
class CImage extends CXhtml {

    protected $m_src;   ///The path to the image source.
    
        /**
        * @brief   __construct an CImage 
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $source The path to the image source.
        * @param string $alt The alt text value required for html image.
        * 	
        */
    function __construct( $id, $attributes, $source, $alt ) {
            //Set the parent
        parent::__construct( $id, "img", $attributes );

            //Set the text content.
        $this->m_src =  $source;

            //Add the id to the tag attributes.
        $this->m_tag->AddAttribute( "src", $source);
        
            //Add the id to the tag attributes.
        $this->m_tag->AddAttribute( "alt", $alt);
    }   
         
        
        /**
        * @brief    Default display for the CImage. 
        * 
        * @see      CMediaBase for detailed behaviour.
        * 
        */
    function Output() {

            //CImage node must have a tag so output it.
        $this->m_tag->OutputOpen();
        
            //If the object has children output them.
        $this->OutputChildren();
 
    } 
       
}//End of CImage Class.






    /**
     * @author  Brady Leach
     * @date	22/01/2014
     * @class   CMediaBase
     * @brief   A CMediaBase holds the shared functionality of html5 media 
     * elements such as video and audio. Child classes will extend the specific 
     * functionality. Shared functionality includes adding and removing sources
     * and tracks.
     *  
     * @note Html5 video and audio tags can have child content that is not a 
     * source or a track. CMediaBase objects follow this convention.
     * 
     * @note The file extension is extracted from the path and used as the type .
     * in the html source tag attribute. "type"="video/mp4"
     * 
     * @note The media attribute tag is not supported at this time. Once It 
     * becomes more widly supported functionality will be added.
     *    
     * @see CHtml for detailed behaviour of a CHtml.
     * @see CVideo and CAudio.     
     * 
     * @todo Add in track functionality when it is supported in all major browsers.
     * 
     */
abstract class CMediaBase extends CHtml {

    
        /**
        * @brief Adds a source tag to a video tag 
        * 
        * @param string $source A path to the video source.	
        *
        */     
    function AddSource($source) {
        
            //Get the extension from the new source.
        $extension = GetExtension($source);        

            //Add a source to the list of sources.
        $this->AddChild(
            new CXhtml($this->m_id . "-". $extension , "source", array("src" => $source, 
                "type"=>$this->m_tag->GetElement()."/".$extension))
        );    
    }
    
    
       /**
        * @brief Adds a list of source tags to a video tag 
        * 
        * @param array $sources A list of strings containing file sources.	
        *
        */  
    function AddSources($sources) {
        
            //For each string in the array add it as a source tag. 
        foreach($sources as $source){            
           $this->AddSource($source);
        }       
    }     
    
       /**
        * @brief Remove a source from the CVideo source list if it exists. 
        * 
        * @param $source - A source to be removed from the source list.	
        * 
        */
    function RemoveSource($source) {
        $extension = GetExtension($source);
        
            //Remove from the children list
        $this->m_children->RemoveChild($extension);
    }        
}//End of CMediaBase Class.





    /**
     * @author  Brady Leach
     * @date	22/01/2014
     * @class   CVideo
     * @brief   A CVideo is an encapsulated html5 video element. It holds a 
     * list of paths to locate the video source. The main function of the 
     * multiple paths uis to provide multiple file types for cross browser 
     * support. Videos are a part of the media node family 
     * 
     * @note The source tags will be added as children of $this and stored using 
     * their extensions as identifires. For example : ../videoPath/video.mp4 will have a key of mp4. 
     * It is recomended that a minimum of 2 video formats be set. 
     * [.mp4 and .webm] will cover all major browsers.
     * 
     * @note The file extension is extracted from the path and used as the type 
     * in the html source tag attribute. "type"="video/mp4"
     * 
     * @note The media attribute tag is not supported at this time. Once It 
     * becomes more widly supported functionality will be added.
     *
     * 
     *      //Create CVideo 
     *      $CVideo = new CVideo($id, $attributes, $sources)
     * 
     *      //Example Source Code
     *      myVideo = new CVideo($imageID, array("class"=>"myCssClass"), array("../MyVideoPath/video.mp4", "../MyVideoPath/video.webm"));
     * 
     *      //To output a CImage
     *      myVideo->Output();
     * 
     *
     * @see     CHtml for detailed behaviour of a CHtml.
     * @see     CImage and CAudio.     
     * 
     * @todo    Add in track functionality when it is supported in 
     *          all major browsers.
     * 
     */
class CVideo extends CMediaBase {

    
       /**
        * @brief    __construct an CImage
        *
        * @note     If a source with an extension exists it will be replaced.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param $source The path to the media content.
        * 	
        * @see CMediaBase, CAudio 
        */
 	function __construct( $id, $attributes, $source) {
            //Set the parent
		parent::__construct( $id, "video", $attributes );
        
            //Add the id to the tag attributes.
        $this->AddSource($source);   
    }           
}//End of CVideo Class.






    /**
     * @author  Brady Leach
     * @date	22/01/2014
     * @class   CAudio
     * @brief   An CAudio is an encapsulated html5 audio element. It holds a 
     * list of paths to locate the audio source. The main function of the 
     * multiple paths is to provide multiple file types for cross browser 
     * support. Videos are a part of the media node family
     *  
     * @note The source tags will be added as children of $this and stored using 
     * their extensions as identifires. For example : ../videoPath/video.mp4 will have a key of mp4. 
     * It is recomended that a minimum of 2 video formats be set. 
     * [.mp4 and .webm] will cover all major browsers.
     * 
     * @note The file extension is extracted from the path and is used as the 
     * type in the html source tag attribute. "type"="video/mp4"
     * 
     * @note The media attribute tag is not supported at this time. Once It 
     * becomes more widly supported functionality will be added.
     *    
     * @see CVideo for usage details.
     * @see CHtml for detailed behaviour of a CHtml.
     * @see CMediaBase and CAudio.     
     * 
     * @todo Add in track functionality when it is supported in  all major browsers.
     * 
     */
class CAudio extends CMediaBase {

    
       /**
        * @brief __construct an CAudio. 
        *          
        * @see CMediaBase, CVideo 
        * 
        * @note If a source with an extension exists it will be replaced.
        * 
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * @param string $sources The list of paths to the media content.	
        *
        * @todo Add in support for a lsit of media sources.
        */
 	function __construct( $id, $attributes, $sources) {
            //Set the parent
		parent::__construct( $id, "audio", $attributes );
        
            //Add the id to the tag attributes.
        $this->AddSource($sources);
   
    }        
}





