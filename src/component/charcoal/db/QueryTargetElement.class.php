<?php
/**
* Query Target Element
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_QueryTargetElement extends Charcoal_Object
{
    private $type;
    private $expression;

    /*
     *  Constructor
     */
    public function __construct( $type, $expression = NULL )
    {
        $this->type   = ui($type);
        $this->expression = us($expression);
    }

    /**
     *  query target type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *  expression
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }
}

