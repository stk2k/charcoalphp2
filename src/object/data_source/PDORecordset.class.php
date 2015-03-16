<?php
/**
* data source for PDO
*
* PHP version 5
*
* @package    objects.data_sources
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PDORecordset implements Charcoal_IRecordset
{
	private $statement;
	private $valid;
	private $fetch_mode;
	private $current;
	private $key;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $statement, $fetch_mode, $options )
	{
		parent::__construct();

		$this->statement = $statement;
		$this->valid = true;
		$this->fetch_mode = $this->setFetchMode( $fetch_mode, $options );
		$this->current = NULL;
		$this->key = 0;
	}


	/**
	 * Set fetch mode
	 *
	 * @param integer $fetch_mode         fetch mode(self::FETCHMODE_XXX)
	 * @param mixed $options        option parameters
	 */
	private function setFetchMode( $fetch_mode, $options )
	{
		switch( $fetch_mode ){
		case Charcoal_IRecordset::FETCHMODE_COLUMN:
			$colno = isset($options['colno']) ? $options['colno'] : NULL;
			if ( $colno === NULL || !is_numeric($colno) ){
				_throw( new Charcoal_InvalidArgumentException('colno') );
			}
			$this->statement->setFetchMode( PDO::FETCH_COLUMN, $colno );
			return PDO::FETCH_COLUMN;
			break;
		case Charcoal_IRecordset::FETCHMODE_CLASS:
			$class_name = isset($options['classname']) ? $options['classname'] : NULL;
			$ctorargs = isset($options['ctorargs']) ? $options['ctorargs'] : NULL;
			if ( $class_name === NULL || !is_string($class_name) ){
				_throw( new Charcoal_InvalidArgumentException('class_name') );
			}
			if ( $ctorargs === NULL || !is_array($ctorargs) ){
				_throw( new Charcoal_InvalidArgumentException('ctorargs') );
			}
			$this->statement->setFetchMode( PDO::FETCH_CLASS, $class_name, $ctorargs );
			return PDO::FETCH_CLASS;
			break;
		case Charcoal_IRecordset::FETCHMODE_INTO:
			$object = isset($options['object']) ? $options['object'] : NULL;
			if ( $object === NULL || !is_object($object) ){
				_throw( new Charcoal_InvalidArgumentException('object') );
			}
			$this->statement->setFetchMode( PDO::FETCH_INTO, $object );
			return PDO::FETCH_INTO;
			break;
		case Charcoal_IRecordset::FETCHMODE_ARRAY:
			$this->statement->setFetchMode( PDO::FETCH_NUM );
			return PDO::FETCH_NUM;
			break;
		case Charcoal_IRecordset::FETCHMODE_ASSOC:
			$this->statement->setFetchMode( PDO::FETCH_ASSOC );
			return PDO::FETCH_ASSOC;
			break;
		case Charcoal_IRecordset::FETCHMODE_BOTH:
			$this->statement->setFetchMode( PDO::FETCH_BOTH );
			return PDO::FETCH_BOTH;
			break;
		default:
			_throw( new Charcoal_InvalidArgumentException('fetch_mode') );
		}
	}

	/**
	 * fetch record
	 *
	 */
	public function fetch()
	{
		$result = $this->statement->fetch( $this->fetch_mode );
		if ( !$result ){
			$this->valid = false;
		}
		return $result;
	}

	public function current()
	{
		return $this->current;
	}

	public function key()
	{
		return $this->key;
	}

	public function next()
	{
		$this->current = $this->fetch();
		$this->key ++;
	}

	public function rewind()
	{
	}

	public function valid()
	{
		return $this->valid;
	}
}

