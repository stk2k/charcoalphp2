<?php
/**
* Interface of recordset
* 
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRecordset extends Iterator
{
    const FETCHMODE_NUM = 1;
    const FETCHMODE_ASSOC = 2;
    const FETCHMODE_BOTH  = 3;
    const FETCHMODE_INTO  = 4;
    const FETCHMODE_CLASS  = 5;
    const FETCHMODE_COLUMN  = 6;

    const FETCHMODE_DEFAULT  = self::FETCHMODE_ASSOC;

    /**
     * fetch record
     *
     * @return mixed        fetch result
     */
    public function fetch();

    /**
     * close cursor
     *
     */
    public function close();
}

