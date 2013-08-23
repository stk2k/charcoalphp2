<?php
/**
* XMLを出力するレンダラ
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_XmlRenderer
{
	/**
	 *	要素以下を描画
	 */
	public static function render( Charcoal_XmlElement $element, $returns )
	{
		$html = '';

		$html .= self::renderElementStart( $element );
		$html .= self::renderElementContents( $element );

		$children = $element->getChildren();
		if ( $children && is_array($children) ){
			foreach( $children as $child ){
				$html .= self::render( $child );
			}
		}

		$html .= self::renderElementEnd( $element );

		return $html;
	}

	/**
	 *	HTML要素の開始タグを描画
	 */
	protected static function renderElementStart( Charcoal_XmlElement $element )
	{
		$tag         = $element->getTag();
		$children    = $element->getChildren();
		$attributes  = $element->getAttributes();
//print "renderElementStart:$tag<br>";

		$xml = "<$tag";
		if ( $attributes && is_array($attributes) ){
			foreach( $attributes as $key => $value ){
				$xml .= ' ' . $key . '="' . $value . '"';
			}
		}
		$xml .= ">";

		return $xml;
	}

	/**
	 *	HTML要素のコンテンツを描画
	 */
	protected static function renderElementContents( Charcoal_XmlElement $element )
	{
		$contents    = $element->getContents();

		$xml = '';
		if ( $contents ){
			$xml .= $contents;
		}

		return $xml;
	}

	/**
	 *	XML要素の終了タグを描画
	 */
	protected static function renderElementEnd( Charcoal_XmlElement $element )
	{
		$tag = $element->getTag();
//print "renderElementEnd:$tag<br>";

		$xml = "</$tag>";

		return $xml;
	}

}
return __FILE__;
