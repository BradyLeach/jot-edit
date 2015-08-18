<?php
$path = filter_input(INPUT_SERVER,'DOCUMENT_ROOT');
include_once $path . '/jot-edit/scripts/php/stCore/stCore.php';
    
    /**
     * @author  Brady Leach
     * @date	12/05/2015
     * @class   CDiv
     * 
     * @brief A CCallout is a container with tag in the upper corner.  
     * 
     * @note example usage:
     * 
     *  
     *      //Create CText 
     *       $myCallout = new CCallout($id, $attributes);
     * 
     *  @see    CHtml for detailed behaviour of a CHtml.
     * 
     */
class PCallout extends CDiv {
 
        
        /**
        * @brief   __construct A html CCallout object that display a 
        * string of text before its children, contained within an div tag.
        * @param string $id A user entered identification string. [If not supplied one will be generated].
        * @param array $attributes An array of html tag attributes. 
        * 	
        */
    function __construct( $id) {
        
            //Set the parent
        parent::__construct( $id, array("class"=>"callout"));
        
        $this->AddChild(new CHtml ("", "b", array("class"=>"callout_tag_border")));
        $this->AddChild(new CHtml ("", "b", array("class"=>"callout_tag")));
    }

}//End of CDiv Class

