<?php
include_once "Helpers.php"; //Includes the id generator.

       /**
        * @author   Brady Leach
        * @date     19/01/2014
        * @class    CNode
        *
        * @brief Abstract CNode class. A CNode has one parent and n sibling. Each 
        * CNode should have a unique identifier, however this is not enforced. 
        * 
        * @param  ptr $m_ptr_parent The parent node of $this. Each node can have 
        * one parent but multiple children.
        * @param string $m_id The id of the node. If an id is not entered on 
        * creation one is generated. User entered id should be unique. There is 
        * no checking to ensure aa id is unique. The user should ensure that ids .
        * are unique.
        * @param array $m_children[childID, child] A list of children CNode objects. 
        * 
        * @note    - $children of a CNode must have unique id's amongst all 
        * siblings otherwise Objects existing within that sibling group will be
        * over ridden by the most recent update.
        *   
        * @note $children  The children array uses the Nodes $id as the key.    
        * 
        * @see HtmlNode, TextNode, MediaNode
        * @todo Add in a id registry to ensure uniqe id.
        * @todo Build navigation cluss functionality. At most a tree/linked list 
        * type deal but at least few basic search routines. For example 
        * get sibling nodes, or search siiblings for a particular child node. 
        * Also some basic search refinment such as Searchup through parents, 
        * down through children etc.
        * 
        */
abstract class CNode {
  
 	protected $m_ptr_parent;        /// A pointer to the nodes parent.
        protected $m_id;                /// The id of this node.	
        protected $m_children = array();/// An array of children nodes.	
 
       /**
        * @brief Constructor - Creates a empty CNode.
        *
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        *
        * @note There is no id checking in place. May add at a later date. The user 
        * should make sure non generated ids are unique.
        * @note ID that begin with a leading underscore will have a unique id generated and prepended
        * to it. This is because the default values for some form inputs have
        * a _formType appeneded to them. This is because some of the forms will
        * use the id of the corrisplonding database table it represents.
        * 
        * 
        * @todo Add in a id registry to ensure uniqe id.	
        */     
    public function __construct ($id) {
            //initialise parent pointer to null.
            //A parents id is set when the CNode is added to another node as a child. 
        $this->ptr_parent = NULL;

            //If there is an id, set CNode id = id, otherwise generate an id.
        if($id) {
            if($id[0]=="_"){//If the id leads with underscore generate an id.
                $ukey = IdGenerator();
                $this->m_id =  (string)$ukey . $id;   
               
            }
            else{
                $this->m_id = $id; 
            }
        } else {			
                $this->m_id = IdGenerator();
        }
    }

    
      /**
      * @brief Sets the protected CNode id value.
      *
      * @param string $id A user entered identification string.
      * 
      * @note There is no id checking in place. May add at a later date. The 
      * user should make sure non generated ids are unique.
      * 
      * @todo    Add in functionality to update the key in the parents children list.
      * 
      */
    public function SetID($id) {
            //add in checking later...
        $this->m_id = $id;	
    }
	

    
        /**
        * @brief Gets this CNode ID.
        * 
        * @return The is of the CNode is returned as string.
        * 
        * @todo Add in functionality to update the key in the parents children list.
        * 
        */
    public function GetID() {
            //add in checking later...
        return $this->m_id;	
    }

    
	
        /**
        * @brief  Sets the protected node $parent variable.
        * 
        * @param ptr $ptr_parent A pointer to the parent CNode object.
        * 
        * @note This is protected so it can only be accessed via the add 
         * child or addChildren functions.
        * 
        */
    protected function SetParentPTR($ptr_parent) {
        $this->ptr_parent = $ptr_parent;	
    }	


	
        /**
         * @brief Gets the pointer to this CNode parent.
         * @return A pointer to CNode parent is returned.
         * 
         */
    function GetParentPTR() {
        return $this->ptr_parent;	
    }

    

        /**
         * @brief A test to see if a CNode has any children.
         * @return True if the CNode has chldren, otherwise false.
         * 
         */	
    function HasChildren() {
        return empty($this->children);	
    }	
    
    
	
        /**
         * @brief Adds a collection of CNode objects to $this children list.
         * @param array $children [n, child] An array of CNode objects to added to 
         * the list of children.
         */
     function AddChildren($children ) {
            //If a valid array of children was passed set the hasChild flag.
        if(is_array($children) && !empty($children)) {

                //For each child CNode set id and ad to children.
            foreach($children as $child) {
                $this->AddChild($child);	
            }//End for
        }//End if
     }
    
    
	
        /**
         * @brief Inserts a child CNode into $this list of children.
         
         * @param    $child - A CNode to be added to the list of children.
         * 
         * @note As a child is inserted into the list it has its parent pointer set to $this.
         * @todo     Add in checking to ensure array is being passed.
         * 
         */
     function AddChild($child) {
            //Set the parent_ptr of each child node to this and add to child list.		
        $child->SetParentPTR($this);
        $this->m_children[ $child->m_id ] = $child; 	
     }
	
	
	
        /**
        * @brief Removes a child from $this list of children based on the CNode id.
        * 
        * @note If the key cannot be found in the list of children nothing is removed.
        * 
        */
    function RemoveChild($childID) {

            //check if the key is in the list of children.
        if( array_key_exists($childID, $this->m_children)) {

                //Set its parent to null 				
            $this->m_children[$childID]->SetParentPTR(NULL);

                //Remove from the children list
            unset($this->m_children[$childID]);
        }//End if
    }			 
}//End CNode Class.

