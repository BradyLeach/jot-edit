<?php
//include_once "scripts/php/SimpleButton.php";
//include_once comments.php;
include_once "CHtml.php";
    /**
     * @author  Brady Leach 
     * @date	19/03/2015
     * 
     * @class PostNode
     * @param CHtml $m_header A container to hold the CHtml objects.
     * @param CHtml $m_body A container to hold the CHtml objects.
     * @param CHtml $m_interface A container to hold the CHtml objects.
     * 
     * @brief A PostNode is a high level display node object that contains a 
     * collection of different content. It holds a number of child CHtml 
     * objects containig data such as images, audio, vidoe links and events.
     *
     *  @note The display order of a PostNode object's content is dependent on 
     * the order it is inserted into the post by the user. 
     * 
     * 
     *      //Create A PostNode      
     *      $post = new Post($id, $element, $attributes, $text)
     * 
     *      //Example Source     
     *      to come
     * 
     *      //To output a PostNode      
     *      $post->Output();
     * 
     *  @see    CHtml for detailed behaviour of a CHtml.
     * 
     * @todo Add input validation.
     * 
     */
class PostNode extends CHtml {

    protected $m_header;      //The parent node that holds the post header CHtml objects.
    protected $m_body;        //The parent node that holds the post body CHtml objects
    protected $m_interface;    //The parent node that holds the interface objects (edit, like, report etc).
 
        /**
         * @brief   __construct A PostNode object.
         * 
         * @param string $id A unique id for a CHtml (if empty, one will be generated).
         * @param string $author The author of the post. 
         * @param string $createdOn The date the post was first created.
         * @param string $editedOn The date that the post was most recently edited.
         * @param string $title The title of the post.
         * @param string $text A string of text to print.
         * 	
        */
    function __construct( $id, $author="Error", $createdOn="Error", $editedOn="Error", $title="Error", $text) {
        //Init the parent
        parent::__construct( $id, "div", array("class"=>"post") );
        
            //Create the header container
        $this->m_header = new CHtml($this->m_id ."header", "div", array("class"=>"post_header"));
        $this->AddChild($this->m_header);
        
            //Create the body container
        $this->m_body = new CHtml($this->m_id ."body", "div", "");
        $this->m_body->AddChild(new CText($this->m_id ."text", "", "textareaTest",$text));
        $this->AddChild($this->m_body) ;
        
            //Create the interface container
         //$this->m_interface = new CHtml($this->m_id . "interface", "div", array("class"=>"userpane"));
         //$this->AddChild($this->m_interface) ;
                 
            //Build display hierarchy
        $this->BuildHeader($author, $createdOn, $editedOn, $title);       
        //$this->BuildInterface();
    }  
        
   
        /**
        * @brief Builds the Post Header. A post header contains all the 
        * infomration about the post including author and the date of it's 
        * most recent modifiation.
        * 
        * @param string $author The author of the post. 
        * @param string $createdOn The date the post was first created.
        * @param string $editedOn The date that the post was most recently edited.
        * @param string $title The title of the post.
        * 	
        */   
    protected function BuildHeader($author, $createdOn, $editedOn, $title) {            
        
            //Create the author href CImage
        $this->m_header->AddChild(new CHtml($this->m_id ."imageLink", "a", array("class"=>"post_header", "href"=>"#")));
        $this->m_header->m_children[$this->m_id ."imageLink"]->AddChild(new CImage($this->m_id . "image", array("class"=>"profile_pic profile_pic_medium left", "title"=>$author) , "media/user/". $author . "/images/profile/profileThumb.jpg" ,$author));
               
            //Create the author CText
        $this->m_header->AddChild(new CText($this->m_id . "author", "h2", "", $author));
       
            
            //Create the title CText
        $this->m_header->AddChild(new CText($this->m_id . "title", "h3", "", $title));
        
               
            //Create the createdOn CText
        $this->m_header->AddChild(new CText($this->m_id . "createdOn", "em", "", $createdOn));

    }
 
        /**
        * @brief Bilds the user interface for the post.
        * 	
        * @note The user interface holds buttons that allows users to interact with a post. 
        */  
    protected function BuildInterface(){

    }
    
   
        /**
        * @brief Adds content to the post.
        * 	 
        */
    function AddContent ($content){
        $this->m_body->AddChild($content);
    }
    
}//End of PostNode 
