<?php
/**
* Query Target
*
* PHP version 5
*
* @package    component.charcoal.db
* @author CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'EnumQueryTargetType.class.php' );

class Charcoal_QueryTarget extends Charcoal_Object
{
    private $model_name;
    private $alias;
    private $joins;

    /*
     *  Constructor
     *
     *    @param Charcoal_String $expression
     *
     *  example:
     *
     *  'model_a as a'
     *  Table aliase name is set to model_a
     *  => SELECT * FROM model_a as a
     *
     *  'model_a + model_b'
     *  interpreted as 'INNER JOIN'
     *  => SELECT * FROM model_a INNER JOIN model_b
     *
     *  'model_a (+ model_b'
     *  interpreted as 'LEFT JOIN'
     *  => SELECT * FROM model_a LEFT JOIN
     *
     *  'model_a +) model_b'
     *  interpreted as 'RIGHT JOIN'
     *  => SELECT * FROM model_a RIGHT JOIN
     *
     *  'model_a + model_b on model_b.id = model_a.id'
     *  Join condtion is attached to model_b
     *  => SELECT * FROM model_a INNER JOIN model_b ON model_b.id = model_a.id
     *
     */
    public function __construct( $expression )
    {
        parent::__construct();

        $tokens = $this->tokenize( $expression );

        if ( count($tokens) < 1 ){
            _throw( new Charcoal_QueryTargetException( "no query target element is not specified." ) );
        }

        $state = NULL;
        $element_list = NULL;
        foreach( $tokens as $token )
        {
            if ( strlen($token) === 0 )    continue;
    
            $token = strtolower($token);

            if ( $state === NULL ){
                $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_MODEL), s($token) );
                $state = "model set";
            }
            elseif ( in_array( $state, array("model set","join set")) && $token == 'as' ){
                $state = "as";
            }
            elseif ( $state == "as" ){
                $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_AS_NAME), s($token) );
                $state = "as set";
            }
            elseif ( $state == "model set" || $state == "as set" || $state == "join set" || $state == "on set" ){
                if ( $token == 'on' ){
                    $state = "on";
                }
                elseif ( $token == '+' ){
                    $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_INNER_JOIN) );
                    $state = "join";
                }
                elseif ( $token == '(+' ){
                    $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_LEFT_JOIN) );
                    $state = "join";
                }
                elseif ( $token == '+)' ){
                    $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_RIGHT_JOIN) );
                    $state = "join";
                }
                elseif ( $state == "on set" ){
                    $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_ON_CONDITION), s($token) );
                }
            }
            elseif ( $state == "join" ){
                $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_MODEL), s($token) );
                $state = "join set";
            }
            elseif ( $state == "on" ){
                $element_list[] = new Charcoal_QueryTargetElement( i(Charcoal_EnumQueryTargetType::TARGET_ON_CONDITION), s($token) );
                $state = "on set";
            }
        }

//        log_debug( "debug, smart_gateway", "element_list: " . print_r($element_list,true) );


        $main_model = NULL;
        $alias = NULL;
        $state = "main model";
        $join_stack = new Charcoal_Stack();
        foreach( $element_list as $element )
        {
            /** @var Charcoal_QueryTargetElement $element */
            if ( $state === "main model" ){

                switch( $element->getType() )
                {
                case Charcoal_EnumQueryTargetType::TARGET_MODEL:
                    $main_model = $element->getExpression();
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_AS_NAME:
                    $alias = $element->getExpression();
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_INNER_JOIN:
                    $join_stack->push( new Charcoal_QueryJoin(i(Charcoal_EnumSQLJoinType::INNER_JOIN)) );
                    $state = "joins";
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_LEFT_JOIN:
                    $join_stack->push( new Charcoal_QueryJoin(i(Charcoal_EnumSQLJoinType::LEFT_JOIN)) );
                    $state = "joins";
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_RIGHT_JOIN:
                    $join_stack->push( new Charcoal_QueryJoin(i(Charcoal_EnumSQLJoinType::RIGHT_JOIN)) );
                    $state = "joins";
                    break;
                }
            }
            elseif ( $state === "joins" ){
                $join = $join_stack->pop();

                switch( $element->getType() )
                {
                case Charcoal_EnumQueryTargetType::TARGET_MODEL:
                    $join->setModelName( s($element->getExpression()) );
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_AS_NAME:
                    $join->setAlias( s($element->getExpression()) );
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_ON_CONDITION:
                    $prev_cond = $join->getCondition();
                    $condition = $prev_cond ?  $prev_cond . ' ' . $element->getExpression() : $element->getExpression();
                    $join->setCondition( $condition );
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_INNER_JOIN:
                    $join_stack->push( $join );
                    $join = new Charcoal_QueryJoin(i(Charcoal_EnumSQLJoinType::INNER_JOIN));
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_LEFT_JOIN:
                    $join_stack->push( $join );
                    $join = new Charcoal_QueryJoin(i(Charcoal_EnumSQLJoinType::LEFT_JOIN));
                    break;
                case Charcoal_EnumQueryTargetType::TARGET_RIGHT_JOIN:
                    $join_stack->push( $join );
                    $join = new Charcoal_QueryJoin(i(Charcoal_EnumSQLJoinType::RIGHT_JOIN));
                    break;
                }

                $join_stack->push( $join );
            }
        }

        $joins = $join_stack->toArray();

//        log_debug( "debug, smart_gateway", "joins: " . print_r($joins,true) );

        $this->model_name  = $main_model;
        $this->alias       = $alias;
        $this->joins       = $joins;
//        log_debug( "debug, smart_gateway", "query_target_list: " . print_r($this,true) );
    }

    /**
     *  model name
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->model_name;
    }

    /**
     *  get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /*
     *  set alias
     */
    public function setAlias( $alias )
    {
        $this->alias = $alias;
    }

    /**
     *  get joins
     *
     * @return Charcoal_QueryJoin[]
     */
    public function getJoins()
    {
        return $this->joins;
    }

    /*
     *  add join
     */
    public function addJoin( Charcoal_QueryJoin $join )
    {
        $this->joins[] = $join;
    }

    /*
     *  tokenize
     */
    private function tokenize( $query_target )
    {
        $query_target = us($query_target);

        $cnt = strlen($query_target);

        $buffer = NULL;
        $is_escaped = FALSE;
        $in_dq = FALSE;
        $tokens = NULL;
        for($i=0;$i<$cnt;$i++){
            $c = $query_target[$i];

            if ( $in_dq ){
                if ( $c === '"' ){
                    if ( !$is_escaped ){
                        $tokens[] = $buffer;
                        $buffer = NULL;
                        $in_dq = FALSE;
                    }
                    else{
                        $buffer .= $c;
                    }
                }
                elseif ( $c === '\\' ){
                    $is_escaped = TRUE;
                }
                else{
                    $buffer .= $c;
                }
            }
            elseif ( $c === '"' ){
                $in_dq = TRUE;
            }
            elseif ( $c === ' ' ){
                if ( $buffer ){
                    $tokens[] = $buffer;
                    $buffer = NULL;
                }
            }
            else{
                $buffer .= $c;
            }
        }
        if ( $buffer ){
            $tokens[] = $buffer;
            $buffer = NULL;
        }

//        log_debug( "debug, smart_gateway", "tokens: " . print_r($tokens,true) );

        return $tokens;
    }
    
    /**
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        $json = array();
        
        $joins = array();
        foreach($this->joins as $j){
            /** @var Charcoal_QueryJoin $j */
            $joins['join_type'] = $j->getJoinType();
            $joins['model_name'] = us($j->getModelName());
            $joins['alias'] = us($j->getAlias());
            $joins['condition'] = us($j->getCondition());
        }
    
        $json['model_name'] = $this->model_name;
        $json['alias'] = $this->alias;
        $json['joins'] = $joins;
        
        return json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}

