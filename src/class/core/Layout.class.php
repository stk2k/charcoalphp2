<?php
/**
* レイアウト情報を保持するクラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Layout extends Charcoal_Object
{
    private $attr;

    /*
     *  constructor
     *
     * @param array $attr
     */
    public function __construct( $attr )
    {
        parent::__construct();

        $this->attr = $attr;
    }

    /**
     *  get attribute
     *
     * @param string|Charcoal_String $key
     *
     * @return mixed
     */
    public function getAttribute( $key )
    {
        $key = us($key);
        return isset($this->attr[$key]) ? $this->attr[$key] : '';
    }

    /*
     *   stringify
     */
    public function toString()
    {
        return '[Layout:' . print_r($this->attr,true) . ']';
    }
}


