<?php
/**
* Result context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_ResultContext extends Charcoal_AbstractWrapperContext
{
    /**
     *  Constructor
     */
    public function __construct( $context )
    {
        parent::__construct( $context );
    }

    /**
     *  return current record set
     */
    public function result()
    {
        return $this->getContext()->getResultSet();
    }

    /**
     *  Make list of copies on target DTO
     */
    public function pack( $target_dto )
    {
        $rs = $this->getContext()->getResultSet();

        if ( $rs && is_array($rs) ){
            $collected_rs = array();
            foreach( $rs as $record_dto ){
                $to_dto = clone $target_dto;

                $values = $record_dto->getAll();
                foreach( $values as $key => $val ){
                    if ( property_exists($to_dto,$key) ){
                        $to_dto->$key = $val;
                    }
                }

                $collected_rs[] = $to_dto;
            }
            $this->getContext()->setResultSet( v($collected_rs) );
        }
        return $this;
    }

    /**
     *  transform
     */
    public function transform( $transformer, $target_dto, $options = NULL )
    {
        $rs = $this->getContext()->getResultSet();

        if ( $rs && is_array($rs) ){
            $transformed_rs = array();
            foreach( $rs as $record_dto ){
                $to_dto = clone $target_dto;

                if ( $options )
                    $transformer->transform( $record_dto, $to_dto, $options );
                else
                    $transformer->transform( $record_dto, $to_dto );

                $transformed_rs[] = $to_dto;
            }
            $this->getContext()->setResultSet( v($transformed_rs) );
        }

        return $this;
    }


}

