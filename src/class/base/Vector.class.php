<?php
/**
* 配列クラス
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Vector extends Charcoal_Collection implements ArrayAccess
{
    /**
     * Retrieve default value
     *
     * @return Charcoal_Vector        default value
     */
    public static function defaultValue()
    {
        return new self();
    }

    /*
     *    先頭を取得
     */
    public function getHead()
    {
        $cnt = count( $this->values );
        if ( $cnt > 0 ){
            return $this->values[ 0 ];
        }
        return NULL;
    }

    /*
     *    最後尾を取得
     */
    public function getTail()
    {
        $cnt = count( $this->values );
        if ( $cnt > 0 ){
            return $this->values[ $cnt - 1 ];
        }
        return NULL;
    }


    /*
     *    最後尾の要素を削除
     */
    public function removeTail()
    {
        return array_pop( $this->values );
    }

    /*
     *    先頭の要素を削除
     */
    public function removeHead()
    {
        return array_shift( $this->values );
    }

    /*
     *    任意の位置の要素を削除
     */
    public function remove( Charcoal_Integer $index, Charcoal_Integer $length = NULL )
    {
        if ( !$length ){
            $length = i(1);
        }
        $index  = $index->getValue();
        $length = $length->getValue();

        return array_splice ( $this->values, $index, $length );
    }

    /**
     *    Check if the collection is empty
     *
     *    @return bool        TRUE if this collection has no elements, FALSE otherwise
     */
    public function isEmpty()
    {
        return count( $this->values ) === 0;
    }

    /*
     *    array_flip
     */
    public function flip()
    {
        return array_flip( $this->values );
    }

    /*
     *    最後尾に追加
     */
    public function add( $item )
    {
        $new_array_cnt = array_push( $this->values, $item );

        return $new_array_cnt;
    }

    /**
     *  Add array data
     *
     *  @param array $items        array data to add
     */
    public function addAll( $items )
    {
//        Charcoal_ParamTrait::validateVector( 1, $items );

        foreach( $items as $item ){
            array_push( $this->values, $item );
        }
    }

    /*
     *    値を取得
     */
    public function getValue()
    {
        return $this->values;
    }

    /*
     *    値を設定
     */
    protected function setValue( array $value )
    {
        $this->values = $value;
    }

    /*
     *    要素値を取得
     */
    public function get( Charcoal_Integer $index )
    {
        $index = $index->getValue();
        return isset($this->values[$index]) ? $this->values[$index] : NULL;
    }

    /*
     *    要素値を更新
     */
    public function set( Charcoal_Integer $index, $value )
    {
        $index = $index->getValue();
        $this->values[$index] = $value;
    }

    /*
     * プロパティの取得
     */
    public function __get( $name )
    {
        $data =  isset($this->values[ $name ]) ? $this->values[ $name ] : NULL;
        return $data;
    }

    /*
     * プロパティの設定
     */
    public function __set( $name, $value )
    {
        $this->values[ $name ] = $value;
    }

    /*
     *    ArrayAccessインタフェース:offsetGetの実装
     */
    public function offsetGet($offset)
    {
        if ( $offset instanceof Charcoal_Object ){
            $offset = us( $offset );
        }
        return isset($this->values[ $offset ]) ? $this->values[ $offset ] : NULL;
    }

    /*
     *    ArrayAccessインタフェース:offsetSetの実装
     */
    public function offsetSet($offset, $value)
    {
        if ( $offset instanceof Charcoal_Object ){
            $offset = us( $offset );
        }
        $this->values[ $offset ] = $value;
    }

    /*
     *    ArrayAccessインタフェース:offsetExistsの実装
     */
    public function offsetExists($offset)
    {
        if ( $offset instanceof Charcoal_Object ){
            $offset = $offset->s();
        }
        return isset($this->values[$offset]);
    }

    /*
     *    ArrayAccessインタフェース:offsetUnsetの実装
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /*
     *    キー一覧を取得
     */
    public function keys()
    {
        return new Vector( array_keys( $this->values ) );
    }

    /*
     *    配列の先頭を取り出す
     */
    public function shift()
    {
        return array_shift( $this->values );
    }

    /*
     *    配列の最後尾に追加する
     */
    public function push()
    {
        return array_shift( $this->values );
    }

    /*
     *    要素を検索
     */
    public function indexOf( Object $object, Charcoal_Integer $index = NULL )
    {
        if ( $index === NULL ){
            $index = 0;
        }
        $size = $this->size();
        for( $i=$index; $i < $size; $i++ ){
            $item = $this->values[$i];
            if ( $item instanceof Charcoal_Object ){
                if ( $item->equals($object) ){
                    return $i;
                }
            }
        }

        return FALSE;
    }

    /*
     *    check if contains a value
     */
    public function contains( $value )
    {
        return in_array( $value, $this->values );
    }

    /*
     *    配列化
     */
    public function toArray()
    {
        return $this->values;
    }

    /*
     *    逆順にする
     */
    public function reverse()
    {
        return new Charcoal_Vector( array_reverse( $this->values ) );
    }

    /*
     *    文字列で連結する
     */
    public function join( $delimiter = ',', $with_type = FALSE, $max_size = 0 )
    {
        return Charcoal_System::implodeArray( $delimiter, $this->values, $with_type, $max_size );
    }

    /*
     *    文字列化
     */
    public function toString()
    {
        return $this->join();
    }

}

