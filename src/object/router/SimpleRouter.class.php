<?php
/**
* Simple Router
*
* PHP version 5
*
* @package    objects.routers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_SimpleRouter extends Charcoal_AbstractRouter
{
	/**
	 * Lookup routing rules
	 *
	 * @return array returns combined array, FALSE if any pattern is matched.
	 */
	public function route( Charcoal_IRequest $request, Charcoal_IRoutingRule $rule )
	{
		// Get path info
		//$request_uri = $_SERVER["REQUEST_URI"];
		//$script_name = $_SERVER["SCRIPT_NAME"];
		//$dir_name    = dirname($script_name);

		//$pos = strpos( $request_uri, $dir_name );
		//$url = substr( $request_uri, $pos + strlen($dir_name) );

		$url = rtrim($_SERVER["REQUEST_URI"],'/');

		log_info( 'debug,router', "routing started. URL=[$url]" );

		$proc_key = $this->getSandbox()->getProfile()->getString( 'PROC_KEY', 'proc' );

		$rule_keys = $rule->getKeys();

		if ( $rule_keys && is_array($rule_keys) )
		{
			log_info( 'debug,router', "rule keys=[" . implode(",",$rule_keys) . "]" );

			foreach( $rule_keys as $pattern )
			{
				$proc = $rule->getProcPath( s($pattern) );
				log_info( 'debug,router', "pattern=[$pattern] proc=[$proc]" );

				if ( $proc )
				{
					log_info( 'debug,router', "testing pattern=[$pattern] url=[$url]" );
					$params = self::_match( $pattern, $url );
					log_info( 'debug,router', "params:" . print_r($params,true) );

					// match
					if ( $params !== NULL ){
						$request->setArray( $params );
						$request->set( $proc_key, $proc );
						log_info( 'debug,router', "routing rule matched! pattern=[$pattern] proc_path=[$proc]" );

						$result = array(
							'proc' => $proc,
							'params' => $params,
							'pattern' => $pattern,
							);

						return $result;
					}
				}
			}

			log_warning( 'system,debug,router', "no routing rule is matched." );
		}
		else{
			log_warning( 'system,debug,router', "routing rule are not defined." );
		}

		return FALSE;
	}

	/**
	 * URLがパターンに合致するか
	 */
	private static function _match( $rule, $url )
	{
		log_info( 'debug,router', "matching test: rule=[$rule] url=[$url]" );

		$parsed_url = parse_url($url);

		$url = isset($parsed_url['path']) ? $parsed_url['path'] : '';
		$query = isset($parsed_url['query']) ? $parsed_url['query'] : '';

		$params = array();
		parse_str( $query, $params );

		$url = trim($url,'/');
		$rule = trim($rule,'/');

		$url_dir_array = explode( '/', $url );
		$rule_dir_array = explode( '/', $rule );

		$cnt_url = count($url_dir_array);
		$cnt_rule = count($rule_dir_array);

		if ( $cnt_url !== $cnt_rule ){
			// マッチしなかった
			log_info( 'debug,router', "[$rule] did not matched to [$url]: cnt_url=$cnt_url cnt_rule=$cnt_rule" );
			return NULL;
		}

		foreach( $rule_dir_array as $rule_dir ){
			$url_dir = array_shift( $url_dir_array );
			if ( strpos($rule_dir,':') === 0 && strlen($url_dir) > 0 ){
				// コロンで始まる階層は変数名
				$key = substr($rule_dir,1);
				$params[ $key ] = $url_dir;
			}
			else if ( $rule_dir !== $url_dir ){
				// マッチしなかった
				log_info( 'debug,router', "[$rule] did not matched to [$url]: rule_dir=$rule_dir url_dir=$url_dir" );
				return NULL;
			}
			log_info( 'debug,router', "maches directory: [$rule_dir]" );
		}

		log_info( 'debug,router', "[$rule] matched to [$url]" );
		log_info( 'debug,router', "parameters:" . print_r($params,true) );
		log_info( 'debug,router', "URLマッピングルールにマッチしました。ルール=[$rule]" );

		return $params;
	}

}

