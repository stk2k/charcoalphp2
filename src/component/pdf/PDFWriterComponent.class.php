<?php
/**
* PDF Writer Component
*
* PHP version 5
*
* @package    component.pdf
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'PDFWriterComponentException.class.php';
require_once( 'EnumPDFOrientation.class.php';
require_once( 'EnumPDFPaperSize.class.php';
require_once( 'EnumPDFTextAlign.class.php';
require_once( 'EnumPDFCellBorder.class.php';
require_once( 'EnumPDFFontStyle.class.php';
require_once( 'EnumPDFFontFamily.class.php';
require_once( 'EnumPDFCellNextPos.class.php';

require_once( 'fpdf/mbfpdi.php' );

class Charcoal_PDFWriterComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $_pdf;
	private $_unit;
	private $_creator;
	private $_authhor;
	private $_zoom;
	private $_layout;
	private $_auto_break;
	private $_auto_break_margin;
	private $_fill_color;
	private $_margin_left;
	private $_margin_top;
	private $_margin_right;

	private $_base_pos;

	/**
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_base_pos = new Charcoal_PositionFloat( f(0.0), f(0.0) );
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_unit              = $config->getString( s('unit'), s("mm") )->getValue();
		$this->_creator           = $config->getString( s('creator'), s("CharcoalPHP") )->getValue();
		$this->_authhor           = $config->getString( s('authhor'), s("CharcoalPHP") )->getValue();
		$this->_zoom              = $config->getString( s('zoom'), s("real") )->getValue();
		$this->_layout            = $config->getString( s('layout'), s("continuous") )->getValue();
		$this->_auto_break        = $config->getBoolean( s('auto_break'), b(TRUE) )->getValue();
		$this->_auto_break_margin = $config->getInteger( s('auto_break_margin'), i(5) )->getValue();
		$this->_fill_color        = $config->getArray( s('fill_color'), v(array(255,255,255)) )->getValue();
		$this->_margin_left       = $config->getInteger( s('margin_left'), i(10.0) )->getValue();
		$this->_margin_top        = $config->getInteger( s('margin_left'), i(10.0) )->getValue();
		$this->_margin_right      = $config->getInteger( s('margin_left'), i(10.0) )->getValue();

		log_debug( "debug,pdf", "unit:" . $this->_unit );
		log_debug( "debug,pdf", "creator:" . $this->_creator );
		log_debug( "debug,pdf", "authhor:" . $this->_authhor );
		log_debug( "debug,pdf", "zoom:" . $this->_zoom );
		log_debug( "debug,pdf", "layout:" . $this->_layout );
		log_debug( "debug,pdf", "auto_break:" . $this->_auto_break );
		log_debug( "debug,pdf", "auto_break_margin:" . $this->_auto_break_margin );
		log_debug( "debug,pdf", "fill_color:" . implode( ",", $this->_fill_color ) );
		log_debug( "debug,pdf", "margin_left:" . $this->_margin_left );
		log_debug( "debug,pdf", "margin_top:" . $this->_margin_top );
		log_debug( "debug,pdf", "margin_right:" . $this->_margin_right );
	}
 
	/**
	 * PDFを作成
	 *
	 */
	public function create( Charcoal_Integer $orientation, Charcoal_Integer $paper_size, Charcoal_String $title )
	{
		$php_encoding = Charcoal_Profile::getString( s('PHP_CODE') );

		mb_internal_encoding('EUC-JP');


		$orientations = array(
			EnumPDFOrientation::PORTRAIT => 'P',
			EnumPDFOrientation::LANDSCAPE => 'L',
			);

		$paper_sizes = array(
			EnumPDFPaperSize::A3 => array( 297, 420 ),
			EnumPDFPaperSize::A4 => array( 210, 297 ),
			);

		$pdf_orientation = $orientations[ui($orientation)];
		$pdf_paper_size  = $paper_sizes[ui($paper_size)];

//		$this->_pdf = new MBfpdi($pdf_orientation, "mm", array( 210, 297 ));
		$this->_pdf = new MBfpdi($pdf_orientation, $this->_unit, $pdf_paper_size);
		$this->_pdf->AddMBFont(KOZMIN, 'EUC-JP');
		$this->_pdf->AddMBFont(GOTHIC, 'EUC-JP');
		$this->_pdf->AddMBFont(UIGOTHIC, 'EUC-JP');
		$this->_pdf->AddMBFont(MINCHO, 'EUC-JP');
		$this->_pdf->Open();
		$this->_pdf->SetFont(GOTHIC,'',20);
//
		$this->_pdf->SetAutoPageBreak( FALSE, 0 );
		$this->_pdf->AliasNbPages(); 

/*
		// Creator
		$this->_pdf->SetCreator( us($this->_creator) );

		// Author
		$this->_pdf->SetAuthor( us($this->_authhor) );

		// Title
//		$title = mb_convert_encoding( us($title), "EUC-JP", "UTF-8" );
//		$this->_pdf->SetTitle($title );

//		$this->_pdf->SetDisplayMode( us($this->_zoom), us($this->_layout) );

		$this->_pdf->SetAutoPageBreak( ub($this->_auto_break), ui($this->_auto_break_margin) );

		list( $r, $g, $b ) = uv($this->_fill_color);
		$this->_pdf->SetFillColor( $r, $g, $b );
	
		$this->_pdf->SetLeftMargin( ui($this->_margin_left) );
		$this->_pdf->SetTopMargin( ui($this->_margin_top) );
		$this->_pdf->SetRightMargin( ui($this->_margin_right) );
*/
//		$this->_pdf->SetXY( 10, 10 );

//		$this->_pdf->MultiCell(40, 24, mb_convert_encoding( "aaaaaaaaほげまつ？", "EUC-JP", "UTF-8" ), 1, 'L');
//		$this->_pdf->SetXY( 10, 10 );
//		$text = mb_convert_encoding( "ほげまつ？", "EUC-JP", "UTF-8" );
//		$this->_pdf->Write(12, $text);
	}

	/**
	 * ページを追加
	 *
	 */
	public function addPage()
	{
		$this->_pdf->AddPage();
	}

	/**
	 * 塗りつぶし色を設定
	 *
	 *	@param red Charcoal_Integer fill color of red(0-255)
	 *	@param green Charcoal_Integer fill color of green(0-255)
	 *	@param blue Charcoal_Integer fill color of blue(0-255)
	 *
	 */
	public function setFillColor( Charcoal_Integer $red, Charcoal_Integer $green, Charcoal_Integer $blue )
	{
		$this->_pdf->SetFillColor( ui($red), ui($green), ui($blue) );
	}

	/**
	 * フォントを設定
	 *
	 */
	public function setFont( Charcoal_Integer $font_family, Charcoal_Float $size, Charcoal_Integer $styles = NULL )
	{
		$size = uf($size);
		$styles = ui($styles);

		if ( $styles === NULL ){
			$styles = EnumPDFFontStyle::NOTHING;
		}

		// setup font family option
		$font_families = array(
			EnumPDFFontFamily::KOZMIN 		=> KOZMIN,
			EnumPDFFontFamily::GOTHIC 		=> GOTHIC,
			EnumPDFFontFamily::PGOTHIC 		=> PGOTHIC,
			EnumPDFFontFamily::UIGOTHIC 	=> UIGOTHIC,
			EnumPDFFontFamily::MINCHO 		=> MINCHO,
			EnumPDFFontFamily::PMINCHO 		=> PMINCHO,
			);
		$font_family_opt = isset($font_families[ui($font_family)]) ? $font_families[ui($font_family)] : NULL;

		// setup styles option
		$styles_opt = '';
		if ( ($styles & EnumPDFFontStyle::BOLD) === EnumPDFFontStyle::BOLD ){
			$styles_opt .= 'B';
		}
		if ( ($styles & EnumPDFFontStyle::ITALIC) === EnumPDFFontStyle::ITALIC ){
			$styles_opt .= 'I';
		}
		if ( ui($styles & EnumPDFFontStyle::UNDERLINE) === EnumPDFFontStyle::UNDERLINE ){
			$styles_opt .= 'U';
		}

		$this->_pdf->SetFont( $font_family_opt, $styles_opt, $size );

//		$this->_pdf->SetFont( KOZMIN, $styles_opt, $size );
	}


	/**
	 * 文字列書き出し（改行あり）
	 *
	 */
	public function writeCell( 
			Charcoal_Float $width, 
			Charcoal_Float $height, 
			Charcoal_String $text, 
			Charcoal_Integer $border, 
			Charcoal_Integer $align, 
			Charcoal_Boolean $fill = NULL
		)
	{
		$border = ui($border);

		if ( $fill === NULL ){
			$fill = b(FALSE);
		}
		$text = mb_convert_encoding( us($text), "EUC-JP", "UTF-8" );

		// setup border option
		$border_opt = '';
		if ( ($border & EnumPDFCellBorder::LEFT) === EnumPDFCellBorder::LEFT ){
			$border_opt .= 'L';
		}
		if ( ($border & EnumPDFCellBorder::TOP) === EnumPDFCellBorder::TOP ){
			$border_opt .= 'T';
		}
		if ( ($border & EnumPDFCellBorder::RIGHT) === EnumPDFCellBorder::RIGHT ){
			$border_opt .= 'R';
		}
		if ( ($border & EnumPDFCellBorder::BOTTOM) === EnumPDFCellBorder::BOTTOM ){
			$border_opt .= 'B';
		}

		// setup align option
		$aligns = array(
			EnumPDFTextAlign::LEFT 		=> 'L',
			EnumPDFTextAlign::CENTER 	=> 'C',
			EnumPDFTextAlign::RIGHT 	=> 'R',
			EnumPDFTextAlign::JUSTIFY 	=> 'J',
			);
		$align_opt = isset($aligns[ui($align)]) ? $aligns[ui($align)] : NULL;

		$this->_pdf->MultiCell( 
				uf($width), 
				uf($height), 
				us($text), 
				$border_opt, 
				$align_opt, 
				$fill->isTrue() ? 1 : 0
			 );
	}

	/**
	 * 文字列書き出し（改行なし）
	 *
	 */
	public function writeCellNoWrap( 
			Charcoal_Float $width, 
			Charcoal_Float $height, 
			Charcoal_String $text, 
			Charcoal_Integer $border, 
			Charcoal_Integer $next_pos, 
			Charcoal_Integer $align, 
			Charcoal_Boolean $fill = NULL
		)
	{
		$border = ui($border);

		if ( $fill === NULL ){
			$fill = b(FALSE);
		}
		$text = mb_convert_encoding( us($text), "EUC-JP", "UTF-8" );

		// setup border option
		$border_opt = '';
		if ( ($border & EnumPDFCellBorder::LEFT) === EnumPDFCellBorder::LEFT ){
			$border_opt .= 'L';
		}
		if ( ($border & EnumPDFCellBorder::TOP) === EnumPDFCellBorder::TOP ){
			$border_opt .= 'T';
		}
		if ( ($border & EnumPDFCellBorder::RIGHT) === EnumPDFCellBorder::RIGHT ){
			$border_opt .= 'R';
		}
		if ( ($border & EnumPDFCellBorder::BOTTOM) === EnumPDFCellBorder::BOTTOM ){
			$border_opt .= 'B';
		}

		// setup next pos
		$next_poses = array(
			EnumPDFCellNextPos::RIGHT 			=> 0,
			EnumPDFCellNextPos::NEXT_LINE_HEAD	=> 1,
			EnumPDFCellNextPos::BELOW 			=> 2,
			);
		$next_pos_opt = isset($next_poses[ui($next_pos)]) ? $next_poses[ui($next_pos)] : 0;

		// setup align option
		$aligns = array(
			EnumPDFTextAlign::LEFT 		=> 'L',
			EnumPDFTextAlign::CENTER	=> 'C',
			EnumPDFTextAlign::RIGHT 	=> 'R',
			);
		$align_opt = isset($aligns[ui($align)]) ? $aligns[ui($align)] : NULL;

		$this->_pdf->Cell( 
				uf($width), 
				uf($height), 
				us($text), 
				$border_opt, 
				$next_pos_opt,
				$align_opt, 
				$fill->isTrue() ? 1 : 0
			 );
	}

	/**
	 * 書き出し基準位置移動
	 *
	 */
	public function setBasePosition( Charcoal_Float $left, Charcoal_Float $top )
	{
		$this->_base_pos->setLeft( $left );
		$this->_base_pos->setTop( $top );
	}

	/**
	 * 書き出し基準位置移動
	 *
	 */
	public function setBaseLeft( Charcoal_Float $left )
	{
		$this->_base_pos->setLeft( $left );
	}

	/**
	 * 書き出し基準位置移動
	 *
	 */
	public function setBaseTop( Charcoal_Float $top )
	{
		$this->_base_pos->setTop( $top );
	}

	/**
	 * 書き出し位置移動
	 *
	 */
	public function move( Charcoal_Float $x, Charcoal_Float $y )
	{
		$base_x = $this->_base_pos->getLeft();
		$base_y = $this->_base_pos->getTop();

		$this->_pdf->SetXY( $base_x + uf($x), $base_y + uf($y) );
	}

	/**
	 * 書き出し位置移動
	 *
	 */
	public function moveAbsolute( Charcoal_Float $x, Charcoal_Float $y )
	{
		$this->_pdf->SetXY( uf($x), uf($y) );
	}

	/**
	 * 文字列書き出し
	 *
	 */
	public function write( Charcoal_Float $size, Charcoal_String $text )
	{
		$text = mb_convert_encoding( us($text), "EUC-JP", "UTF-8" );
		$this->_pdf->Write( ui($size), us($text) );
	}

	/**
	 * 線の太さ
	 *
	 */
	public function setLineWidth( Charcoal_Float $width )
	{
		$this->_pdf->SetLineWidth( uf($width) );
	}

	/**
	 * 現在のページ番号
	 *
	 */
	public function getPageNo()
	{
		return $this->_pdf->PageNo();
	}

	/**
	 * 矩形
	 *
	 */
	public function drawRect( Charcoal_RectangleFloat $rect, Charcoal_Boolean $fill = NULL )
	{
		if ( $fill === NULL ){
			$fill = b(TRUE);
		}

		$fill_option = $fill->isTrue() ? 'DF' : 'D';

		$base_x = $this->_base_pos->getLeft();
		$base_y = $this->_base_pos->getTop();

		$this->_pdf->Rect( $base_x + $rect->getLeft(), $base_y + $rect->getTop(), $rect->getWidth(), $rect->getHeight(), $fill_option );
	}

	/**
	 * 矩形
	 *
	 */
	public function drawRectAbsolute( Charcoal_RectangleFloat $rect, Charcoal_Boolean $fill = NULL )
	{
		if ( $fill === NULL ){
			$fill = b(TRUE);
		}

		$fill_option = $fill->isTrue() ? 'DF' : 'D';

		$this->_pdf->Rect( $rect->getLeft(), $rect->getTop(), $rect->getWidth(), $rect->getHeight(), $fill_option );
	}

	/**
	 * 直線
	 *
	 */
	public function drawLine( Charcoal_Float $x1, Charcoal_Float $y1, Charcoal_Float $x2, Charcoal_Float $y2 )
	{
		$base_x = $this->_base_pos->getLeft();
		$base_y = $this->_base_pos->getTop();

		$this->_pdf->Line( $base_x + uf($x1), $base_y + uf($y1), $base_x + uf($x2), $base_y + uf($y2) );
	}

	/**
	 * 直線
	 *
	 */
	public function drawLineAbsolute( Charcoal_Float $x1, Charcoal_Float $y1, Charcoal_Float $x2, Charcoal_Float $y2 )
	{
		$this->_pdf->Line( uf($x1), uf($y1), uf($x2), uf($y2) );
	}


	/**
	 * ブラウザに出力
	 *
	 */
	public function outputInline( Charcoal_String $name )
	{
		$this->_pdf->Output( us($name), 'I' );
	}

	/**
	 * ブラウザに出力してダウンロード
	 *
	 */
	public function outputDownload( Charcoal_String $name )
	{
		$this->_pdf->Output( us($name), 'D' );
	}

	/**
	 * 文字列として取得
	 *
	 */
	public function outputString()
	{
		return $this->_pdf->Output( '', 'S' );
	}

	/**
	 * ファイルとして取得
	 *
	 */
	public function outputFile( Charcoal_String $file_name )
	{
		return $this->_pdf->Output( us($file_name), 'F' );
	}
}

