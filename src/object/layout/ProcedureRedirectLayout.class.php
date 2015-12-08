<?php
/**
* プロシージャをリダイレクトするレイアウト
*
* PHP version 5
*
* @package    objects.layouts
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ProcedureRedirectLayout extends Charcoal_AbstractLayout
{
    private $_sandbox;
    private $_obj_path;
    private $_params;

    /*
     *    コンストラクタ
     */
    public function __construct( $sandbox, $obj_path, $params = NULL )
    {
        Charcoal_ParamTrait::validateSandbox( 1, $sandbox );
        Charcoal_ParamTrait::validateStringOrObjectPath( 1, $obj_path );
        Charcoal_ParamTrait::validateHashMap( 2, $params, TRUE );

        parent::__construct( p(array()) );

        $this->_sandbox    = $sandbox;
        $this->_obj_path = is_string(us($obj_path)) ? new Charcoal_ObjectPath($obj_path) : $obj_path;
        $this->_params    = $params ? $params : m(array());
    }

    /**
     *    リダイレクト先プロシージャパスを取得
     */
    public function getProcedurePath()
    {
        return $this->_obj_path;
    }

    /**
     *    リダイレクト時のパラメータを取得
     */
    public function getParameters()
    {
        return $this->_params;
    }

    /**
     *    リダイレクト時のURLを取得
     */
    public function makeRedirectURL()
    {
        $url = Charcoal_URLUtil::makeAbsoluteURL( $this->_sandbox, $this->_obj_path, $this->_params );

        return $url;
    }

    /*
     *    文字列化
     */
    public function toString()
    {
        return "[RedirectWebPage:" . us($this->_obj_path->getObjectPathString()) . "]";
    }
}


