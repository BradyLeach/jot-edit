<?php
include_once "CHtml.php";

    /**
    * @author Brady Leach
    * @date 19/01/2014
    * @class CList
    * 
    * @brief A CList object handles html lists. It has functionality to 
    * insert and remove list items.  
    *   
    * 
    * 
    *       //Create a new unordered list.
    *       $ul = new CList("listID", "ul", "");
    *       
    *       //Add an item to the list. 
    *       $ul->AddItem("itemID", "", new TextNode("", "a", array("href"=>"#"), "list text"));
    * 
    * 
    * 
    * @todo add in remove list item functionality.
    * 
    */
class CList extends CHtml {
    
    
        /**
         * @author Brady Leach  
         * @date 19/01/2014
         * @brief Constructor - Creates a CList object.
         * 
         * @param string $id A user entered identification string.[If not supplied one will be generated]. 
         * @param string $listType A html list type [ol, ul]. 
         * @param array $attributes An array of html tag attributes.
         *
         * @note There is no id checking in place. May add at a later date. The 
         * user should make sure non generated ids are unique.
         * 
         * @todo Add validation.                
         * 	
        */
    function __contruct($id, $listType, $attributes) {
        parent::__contruct($id, $listType, $attributes );
    }   
    
        /**
         * @brief Add an item to the this CList object..
         * 
         * @param string $id A user entered identification string.[If not supplied one will be generated]. 
         * @param array $attributes An array of html tag attributes.
         * @param string $listItem A CHtml(or derived class) object.
         *  
         * @todo Add validation.                
         * 	
        */
    function AddItem( $id, $attributes, $listItem){
        
            //Create the new list item.
        $item = new CHtml($id, "li", $attributes);
        
            //Add out object to the list item.
        $item->AddChild($listItem);
        
            //Add the list item to the list.
        $this->AddChild( $item );        
    }
    
         /**
         * @brief Add an item to the this CList object..
         *
         * @param string $listItem A CHtml(or derived class) object to be removed.
         *  
         * @todo Add implementation.                
         * 	
        */
    function RemoveItem($listItem){
        
        //do stuff
    }   
}//End of CList Class.
