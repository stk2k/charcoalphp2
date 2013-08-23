<?php
/**
* Simple Router
*
* PHP version 5
*
* @package    url_mappers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SimpleRouter extends Charcoal_CharcoalObject implements Charcoal_IRouter
{
	/*
	 * Construct object
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
		parent::configure( $config );
	}

	/**
	 * Lookup routing rules
	 *
	 * @return Charcoal_Boolean TRUE if any rule is matched, otherwise FALSE
	 */
	public function route( Charcoal_IRequest $request, Charcoal_IRoutingRule $rule )
	{
		// Get path info
		$request_uri = $_SERVER["REQUEST_URI"];
		$script_name = $_SERVER["SCRIPT_NAME"];
		$dir_name    = dirname($script_name);

		$pos = strpos( $request_uri, $dir_name );
		$url = substr( $request_uri, $pos + strlen($dir_name) );

		log_info( 'debug,router', 'router', "routing started. URL=[$url]" );

		$rule_keys = $rule->getKeys();

		if ( $rule_keys && is_array($rule_keys) )
		{
			log_info( 'debug,router', 'router', "rule keys=[" . implode(",",$rule_keys) . "]" );

			foreach( $rule_keys as $pattern )
			{
				$proc = $rule->getProcPath( s($pattern) );
				log_info( 'debug,router', 'router', "pattern=[$pattern] proc=[$proc]" );

				if ( $proc )
				{
					$a = self::_match( $pattern, $url );
					log_info( 'debug,router', 'router', "params:" . print_r($a,true) );

					// match
					if ( $a !== NULL ){
						$request->setArray( $a );
						$request->set( s(PROC_KEYWORD), $proc );
						log_info( 'debug,router', 'router', "routing rule matched! pattern=[$pattern] proc_path=[$proc]" );
						return b(TRUE);
					}
				}
			}

			log_warning( 'system,debug,router', 'router', "no routing rule is matched." );
		}
		else{
			log_warning( 'system,debug,router', 'router', "routing rule are not defined." );
		}

		return b(FALSE);
	}

	/**
	 * URLがパターンに合致するか
	 */
	private static function _match( $rule, $url )
	{
		log_info( 'debug,router', 'router', "matching test: rule=[$rule] url=[$url]" );

		$url_dir_array = explode( '/', $url );
		$rule_dir_array = explode( '/', $rule );

		if ( count($url_dir_array) !== count($rule_dir_array) ){
			// マッチしなかった
			log_info( 'debug,router', 'router', "[$rule] did not matched to [$url]" );
			return NULL;
		}

		$a = array();

		foreach( $rule_dir_array as $rule_dir ){
			$url_dir = array_shift( $url_dir_array );
			if ( strpos($rule_dir,':') === 0 ){
				// コロンで始まる階層は変数名
				$key = substr($rule_dir,1);
				$a[ $key] = $url_dir;
			}
			else if ( $rule_dir !== $url_dir ){
				// マッチしなかった
				log_info( 'debug,router', 'router', "[$rule] did not matched to [$url]" );
				return NULL;
			}
			log_info( 'debug,router', 'router', "maches directory: [$rule_dir]" );
		}

		log_info( 'debug,router', 'router', "[$rule] matched to [$url]" );
		log_info( 'debug,router', 'router', "parameters:" . print_r($a,true) );
		log_info( 'debug,router', 'router', "URLマッピングルールにマッチしました。ルール=[$rule]" );

		return $a;
	}

}

return __FILE__;