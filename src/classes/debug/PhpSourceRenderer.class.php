<?php
/**
* PHPソースレンダラクラス
*
* PHP version 5
*
* @package    debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_PhpSourceRenderer
{
	/**
	 *	レンダリング
	 *
	 *	@return string レンダリング結果（HTML）
	 */
	public static function render( $tokens, $number_format = NULL, $classes = NULL, $start = 1, $end = -1, $tabsize = 4 )
	{
		if ( !$classes ){
			$classes = array();
		}
		if ( $start < 1 ){
			$start = 1;
		}
		if ( $end < $start ){
			$end = count($tokens);
		}

		$class = isset($classes['source_code']) ? $classes['source_code'] : 'source_code';
		$html = "<pre class=\"$class\">";

		for( $i=$start; $i<$end; $i++ ){
			// 行
			$even_odd = (($i % 2) === 0) ? 'even' : 'odd';
			$class = isset($classes[$even_odd]) ? $classes[$even_odd] : $even_odd;
			$html .= "<div class=\"$class\">";
			// 行番号
			if ( $number_format ){
				$class = isset($classes['line_no']) ? $classes['line_no'] : 'line_no';
				$line_no = sprintf( $number_format, $i );
				$html .= "<span class=\"$class\">$line_no</span>";
			}
			// ソースコード
			$tokens_line = isset($tokens[$i]) ? $tokens[$i] : NULL;
			$cnt = count($tokens_line);
			for( $j=0; $j<$cnt; $j++ ){
				$token = $tokens_line[$j];
				$token = $token;
				$code = htmlspecialchars($token->getCode());
				$type = $token->getType();
				// タブ展開
				if ( $tabsize && is_int($tabsize) ){
					$code = str_replace( '	', str_repeat('&nbsp;',$tabsize), $code );
				}
				switch( $type ){
				case Charcoal_PhpSourceElement::TYPE_KEYWORD:		$type = 'keyword';			break;
				case Charcoal_PhpSourceElement::TYPE_IDENTIFIER:	$type = 'identifier';		break;
				case Charcoal_PhpSourceElement::TYPE_COMMENT:		$type = 'comment';			break;
				case Charcoal_PhpSourceElement::TYPE_DELIMITER:		$type = 'delimiter';		break;
				case Charcoal_PhpSourceElement::TYPE_CONST_STRING:	$type = 'const_string';		break;
				}
				$class = isset($classes[$type]) ? $classes[$type] : $type;
				$html .= "<span class=\"$class\">$code</span>";
			}
			$html .= "</div>";
		}
		$html .= "</pre>";

		return $html;
	}


}

