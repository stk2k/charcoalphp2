<?php
/**
* PhpTidy Component
*
* PHP version 5.3
*
* @package    component.xml.parser.php
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_TidyNode extends Charcoal_Object
{
    private $tidy_node;

    /**
     *  Constructor
     */
    public function __construct( $tidy_node )
    {
        parent::__construct();

        $this->tidy_node = $tidy_node;
    }

    /**
     * get name
     *
     * @return string       name of this node
     */
    public function getName()
    {
        return $this->tidy_node->name;
    }

    /**
     * get value
     *
     * @return string       value of this node
     */
    public function getValue()
    {
        return $this->tidy_node->value;
    }

    /**
     * get type
     *
     * @return integer       type of this node
     */
    public function getType()
    {
        return $this->tidy_node->type;
    }

    /**
     * get line
     *
     * @return integer       line of this node
     */
    public function getLine()
    {
        return $this->tidy_node->line;
    }

    /**
     * get column
     *
     * @return integer       column of this node
     */
    public function getColumn()
    {
        return $this->tidy_node->column;
    }

    /**
     * get proprietary
     *
     * @return integer       proprietary of this node
     */
    public function getProprietary()
    {
        return $this->tidy_node->proprietary;
    }

    /**
     * get id
     *
     * @return integer       id of this node
     */
    public function getId()
    {
        return $this->tidy_node->id;
    }

    /**
     * get attributes
     *
     * @return array       attributes of this node
     */
    public function getAttributes()
    {
        return $this->tidy_node->attribute;
    }

    /**
     * get child nodes
     *
     * @return array       child nodes of this node
     */
    public function getChildren()
    {
        $children = array();
        if ( is_array($this->tidy_node->child) ){
            foreach( $this->tidy_node->child as $child ){
                $children[] = new Charcoal_TidyNode( $child );
            }
        }
        return $children;
    }

    /**
     * get parent node
     *
     * @return Charcoal_TidyNode       parent node of this node
     */
    public function getParent()
    {
        $parent_tidy_node = $this->tidy_node->getParent();

        return $parent_tidy_node ? new Charcoal_TidyNode( $parent_tidy_node ) : NULL;
    }

    /**
     * Checks if a node has children
     *
     * @return bool       Returns TRUE if the node has children, FALSE otherwise.
     */
    public function hasChildren()
    {
        return $this->tidy_node->hasChildren();
    }

    /**
     * Checks if a node has siblings
     *
     * @return bool       Returns TRUE if the node has siblings, FALSE otherwise.
     */
    public function hasSiblings()
    {
        return $this->tidy_node->hasSiblings();
    }

}

