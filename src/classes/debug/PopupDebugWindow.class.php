<?php
/**
* ポップアップデバッグウィンドウクラス
*
* PHP version 5
*
* @package    debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_PopupDebugWindow  
{
	private $title;
	private $html;
	private $head;
	private $body;
	
	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		$this->html = new Charcoal_XmlElement( s("html") );
		$this->head = new Charcoal_XmlElement( s("head") );
		$this->body = new Charcoal_XmlElement( s("body") );

		$this->html->add( $this->head );
		$this->html->add( $this->body );

		$this->html = $this->html;
	}
	
	/*
	 *	HEADタグ
	 */
	public function getHead()
	{
		return $this->head;
	}
	
	/*
	 *	BODYタグ
	 */
	public function getBody()
	{
		return $this->body;
	}
	
	/*
	 *	ポップアップ
	 */
	public function popup( $title = NULL, $window_id = NULL )
	{
		$renderer = new Charcoal_XmlRenderer();

		$contents_html = $renderer->render( $this->html );
//		$contents_html = htmlspecialchars($contents_html, ENT_QUOTES);

		if ( $window_id === NULL ){
			$window_id = Charcoal_Framework::getRequestID();
		}
		if ( $title === NULL ){
			$title = $window_id;
		}

		$version = Charcoal_Framework::getVersion();

		echo '<script type="text/javascript"><!--' . PHP_EOL;
		echo 'var wnd = window.open("","' . $window_id . '");' . PHP_EOL;
		echo 'wnd.document.write("' . $contents_html .'");' . PHP_EOL;
		echo 'wnd.document.title="CharcoalPHP ver.' . $version . '/' . $title .'";' . PHP_EOL;
		echo '--></script>';
	}
}
return __FILE__;
