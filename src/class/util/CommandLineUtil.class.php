<?php
/**
* utility class for parsing command line
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_CommandLineUtil
{
	/*
	 * 引数を空白で分割（ダブルクウォート、シングルクウォート対応）
	 *
	 */
	public static function splitParams( $args )
	{
		Charcoal_ParamTrait::checkString( 1, $args );

		$args = us($args);
		$pos = 0;
		$dq = FALSE;
		$sq = FALSE;
		$max_pos = strlen($args);

		$args_array = array();
		$arg_tmp = '';

		$escape = FALSE;
		
		while( $pos < $max_pos )
		{
			$ch = substr($args,$pos,1);

			$escape_now = FALSE;

			if ( $escape ){
				$arg_tmp .= $ch;
			}
			else if ( $dq ){
				if ( $ch == '\\' ){
					$escape = TRUE;
					$escape_now = TRUE;
				}
				else if ( $ch == '"' ){
					$dq = FALSE;
				}
				else{
					$arg_tmp .= $ch;
				}
			}
			else if ( $sq ){
				if ( $ch == '\\' ){
					$escape = TRUE;
					$escape_now = TRUE;
				}
				else if ( $ch == "'" ){
					$sq = FALSE;
				}
				else{
					$arg_tmp .= $ch;
				}
			}
			else{
				if ( $ch == ' ' ){
					$args_array[] = $arg_tmp;
					$arg_tmp = '';
				}
				else if ( $ch == '\\' ){
					$escape = TRUE;
					$escape_now = TRUE;
				}
				else if ( $ch == '"' ){
					$dq = TRUE;
				}
				else if ( $ch == "'" ){
					$sq = TRUE;
				}
				else{
					$arg_tmp .= $ch;
				}
			}

			if ( !$escape_now && $escape ){
				$escape = FALSE;
			}

			$pos ++;
		}

		if ( strlen($arg_tmp) > 0 ){
			$args_array[] = $arg_tmp;
		}

		return $args_array;
	}

	/*
	 * 引数をパースし、連想配列にして返却する。
	 *
	 *    command -param1 value1 -param2 value2 ...
	 */
	public static function parseParams( $argv )
	{
		$param = NULL;
		$p_array = array();

		foreach( $argv as $arg ){
			if ( strpos($arg,'-') === 0 ){
				if ( $param ){
					// add empty element
					$p_array[ $param ] = '';
					$param = NULL;
				}
				// ハイフンで始まる文字列はパラメータ名
				$param = substr($arg,1);
			}
			else if ( $param ){
				// パラメータ名の後の文字列は値
				$p_array[ $param ] = $arg;
				$param = NULL;
			}
		}

		return $p_array;
	}

}

