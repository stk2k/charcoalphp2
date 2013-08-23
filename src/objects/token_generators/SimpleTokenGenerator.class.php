<?php
/**
* Simple Token Generator
*
* PHP version 5
*
* @package    transformers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SimpleTokenGenerator extends Charcoal_CharcoalObject implements Charcoal_ITokenGenerator
{
	private $_algorithm;

	/*
	 * コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		$this->_algorithm  = $config->getString( s('algorithm'), s('sha1') )->getValue();

		log_debug( "debug", "token algorithm: {$this->_algorithm}" );
	}

	/**
	 * generate a token
	 */
	public function generateToken( Charcoal_HashMap $options = NULL )
	{
		$algorithm = us($this->_algorithm);

		$token = '';
		switch( $algorithm )
		{
		case 'sha1':
			$token = Charcoal_System::hash( 'sha1' );
			break;
		case 'md5':
			$token = Charcoal_System::hash( 'md5' );
			break;
		}

		return $token;
	}


}

return __FILE__;