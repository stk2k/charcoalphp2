<?PHP
/**
 * Excel_Reviser Version 0.30beta  Author:kishiyan
 *		with Image OBJ patch       Co-author:sake&ume
 * Copyright (c) 2006-2008 kishiyan <excelreviser@gmail.com>
 * All rights reserved.
 *
 * Support
 *   URL  http://chazuke.com/forum/viewforum.php?f=3
 *
 * Redistribution and use in source, with or without modification, are
 * permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer,
 *    without modification, immediately at the beginning of the file.
 * 2. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *   URL http://www.gnu.org/licenses/gpl.html
 * 
 * @package Excel_Reviser
 * @author kishiyan <excelreviser@gmail.com>
 * @copyright Copyright &copy; 2006-2007, kishiyan
 * @since PHP 4.4.1 w/mbstring,GD
 * @version 0.24 beta 2008/01/13
 */

/*  HISTORY
2006.12.01 change flag : compulsory calculate on open
2006.12.03 corresponds to reading the small block for OLE2
2006.12.03 BUGFIX : about reading the large SST record
2006.12.03 reject OBJPROJ & BUTTONPROPERTYSET record for VB-error prevention
2006.12.08 BUGFIX : Error evasion to sheet-block
2006.12.08 Making of pear-OLE module unnecessary
2006.12.08 add a HyperLink function
2006.12.12 BUGFIX : output-filename miss
2006.12.16 add method to copy sheets
2006.12.17 add Property to set/get multibyte-charactor code
2007.01.07 add Property to set/get ref3d option
2007.01.29 BUGFIX make to read only Internal References SUPBOOK 
2007.02.10 BUGFIX size ajustment for small WorkBook
2007.02.28 add method 'addBlank'
2007.02.28 add method 'setCellMerge'
2007.02.28 add method 'unsetCellMerge'
2007.03.10 correspond to 64bit system
2007.03.10 correspond to big endian system
2007.03.10 BUGFIX order of executing setHeader/Footer and addSheet
2007.03.22 BUGFIX make SST-record for platform dependent character
2007.03.22 change Default_CHARSET (EUC-JP -> eucJP-win)
2007.04.16 add method 'chgColWidth'
2007.04.16 add method 'chgRowHeight'
2007.05.06 BUGFIX setCellMerge and edit comment
2007.05.25 BUGFIX chgRowHeight
2007.05.26 add setParseMode method
2007.05.26 add parseFile method
2007.05.26 add getCellVal method
2007.05.26 add getCellAttrib method
2007.05.26 add getSheetName method
2007.05.26 add getHeader method
2007.05.26 add getFooter method
2007.05.26 add getRowHeight method
2007.05.26 add getColWidth method
2007.05.26 add buildFile method
2007.06.09 BUGFIX Process PrintArea
2007.06.09 add setPrintTitle method
2007.06.09 add setPrintArea method
2007.06.16 BUGFIX getCellVal method
2007.07.28 BUGFIX getCellVal method - cannot get Type_NUMBER
2007.07.30 addImage method (since Ver 0.23b)
2007.08.05 BUGFIX miss-read Asian-phonetic-settings-block
2007.10.20 add Experimental-code for addin-function
2007.11.19 Fixed OLE Validation Vulnerability
2007.11.24 BUGFIX getCellAttrib-method & some
2008.01.19 change error-handling
2008.01.19 add setErrorHandling method
2008.01.19 correspond to the protected file
2008.05.04 delete unknown-record
2008.05.24 add setInheritInfomation method
2007.06.19 enable XF in COLINFO
2007.06.20 BUGFIX getCellVal method for formula
2008.06.24 protect against MAC-binary
*/

define('Reviser_Version','0.30beta');
define('Version_Num', 0.30);

define('Default_CHARSET', 'eucJP-win');
define('Code_BIFF8', 0x600);
define('Code_WorkbookGlobals', 0x5);
define('Code_Worksheet', 0x10);
define('Type_EOF', 0x0a);
define('Type_BOUNDSHEET', 0x85);
define('Type_SST', 0xfc);
define('Type_CONTINUE', 0x3c);
define('Type_EXTSST', 0xff);
define('Type_LABEL', 0x204);
define('Type_LABELSST', 0xfd);
define('Type_WRITEACCESS', 0x5c);
define('Type_OBJPROJ', 0xd3);
define('Type_BUTTONPROPERTYSET', 0x1ba);
define('Type_DIMENSION', 0x200);
define('Type_ROW', 0x208);
define('Type_DEFCOLWIDTH', 0x55);
define('Type_COLINFO', 0x7d);
define('Type_DBCELL', 0xd7);
define('Type_RK', 0x7e);
define('Type_RK2', 0x27e);
define('Type_MULRK', 0xbd);
define('Type_MULBLANK', 0xbe);
define('Type_INDEX', 0x20b);
define('Type_NUMBER', 0x203);
define('Type_FORMULA', 0x406);
define('Type_FORMULA2', 0x6);
define('Type_BOOLERR', 0x205);
define('Type_UNKNOWN', 0xffff);
define('Type_BLANK', 0x201);
define('Type_SharedFormula', 0x4bc);
define('Type_STRING', 0x207);
define('Type_HEADER', 0x14);
define('Type_FOOTER', 0x15);
define('Type_BOF', 0x809);
define('Type_WINDOW2', 0x23e);
define('Type_COUNTRY', 0x8c);
define('Type_SUPBOOK', 0x1ae);
define('Type_EXTERNSHEET', 0x17);
define('Type_NAME', 0x18);
define('Type_MERGEDCELLS', 0xe5);
define('Type_SELECTION', 0x1d);
define('Type_FONT', 0x31);
define('Type_FORMAT', 0x041e);
define('Type_XF', 0xe0);
define('Type_DEFAULTROWHEIGHT', 0x225);
define('Type_FILEPASS', 0x2f);
define('Type_XCT', 0x59);
define('Type_CRN', 0x5a);

/**
* Class for regenerating Excel Spreadsheets
* @package Excel_Reviser
* @author kishiyan <excelreviser@gmail.com>
* @copyright Copyright &copy; 2006-2007, kishiyan
* @since PHP 4.4
* @example ./sample.php sample
*/
class Excel_Reviser
{
	// temp for workbook Globals Substream data
	var $wbdat='';
	// part of workbook Globals Substream data
	var $globaldat=array();
	// original rowrecord
	var $rowblock=array();
	// original colinfo record
	var $colblock=array();
	// buffer for all cell-record
	var $cellblock=array();
	// sheet-block data
	var $sheetbin=array();
	// each parameter of sheet record
	var $boundsheets=array();
	// buffer of user setting parameter
	var $revise_dat=array();
	// each data of shared string
	var $eachsst=array();
	// hyperlink-data by user
	var $hlink = array();
	// sheet-number for erase by user
	var $rmsheets=array();
	// cell for erase by user
	var $rmcells=array();
	// option for some type of formula
	var $exp_mode=0;
	// duplicate-sheet data by user
	var $dupsheet=array();
	// part of original sheet data
	var $stable=array();
	// charactor-set name
	var $charset;
	// option for graph-object reference
	var $opt_ref3d;
	// option for parse mode
	var $opt_parsemode;
	// mergecells data
	var $mergecells=array();
	// set/unset merge info
	var $mergeinfo=array();
	// set column width
	var $colwidth=array();
	// set row height
	var $rowheight=array();
	// font data
	var $recFONT=array();
	// format data
	var $recFORMAT=array();
	// XF data
	var $recXF=array();
	// DEFAULTROWHEIGHT data
	var $defrowH=array();
	// DEFCOLWIDTH data
	var $defcolW=array();
	// print Area data
	var $prnarea=array();
	// print Title data
	var $prntitle=array();
	// debug for image-data
	var $debug_image=1;
	// save magic_quotes Flag
	var $Flag_Magic_Quotes=False;
	// Error-handling method
	var $Flag_Error_Handling=0;
	// Error-Reporting Level
	var $Flag_Error_Reporting= E_ALL;
	// property inherit
	var $Flag_inherit_Info= 0;
	// Streams in OLE-container
	var $orgStreams=array();

	// Constructor
	function Excel_Reviser(){
//error_reporting(E_ALL ^ E_NOTICE);
		$this->charset = Default_CHARSET;
		$this->opt_ref3d = 0;
		$this->globaldat['presheet']='';
		$this->globaldat['presst']='';
		$this->globaldat['last']='';
		$this->globaldat['presup']='';
		$this->globaldat['supbook']='';
		$this->globaldat['extsheet']='';
		$this->globaldat['name']='';
		$this->globaldat['namerecord']='';
		$this->globaldat['exsstbin']='';
	}

	/**
	* Set(Get) internal charset, if you use multibyte-code.
	* @param string $chrset charactor-set name(Ex. SJIS)
	* @return string current charector-set name
	* @access public
	*/
	function setInternalCharset($chrset=''){
		if (strlen(trim($chrset)) > 2) {
			$this->charset = $chrset;
		}
		return $this->charset;
	}

	/**
	* Set(Get) parse mode, 1: include cell-attribute.
	* @param string $mode set parse mode
	* @return string current parse mode
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function setParseMode($mode=null){
		if ($mode == 1) {
			$this->opt_parsemode = 1;
		}
		return $this->opt_parsemode;
	}

	/**
	* Set reference option for graph-object in added sheet
	* (This is experimental function. It operates under only
	*  the conditions which are limited.) 
	* @param integer $opt  1 = change link to self-sheet
	*                      0 = keep original link-address
	* @return integer current value
	* @access public
	* @example ./sample2.php sample2
	*/
	function setOptionRef3d($opt=null){
		if ($opt !== null){
			$this->opt_ref3d= $opt;
		}
		return $this->opt_ref3d;
	}

	/**
	* Set Cells to merge
	* @param integer $sn sheet-number  0 base indexed
	* @param integer $rowst row number for top-cell
	* @param integer $rowen row number for bottom-cell
	* @param integer $colst column number for left-cell
	* @param integer $colen column number for right-cell
	* @access public
	* @example ./sample3.php sample3
	*/
	function setCellMerge($sn,$rowst,$rowen,$colst,$colen){
		if ($sn < 0) return -1;
		if ($rowst < 0 || $rowst > 65535) return -1;
		if ($rowen < 0 || $rowen > 65535) return -1;
		if ($colst < 0 || $colst > 255) return -1;
		if ($colen < 0 || $colen > 255) return -1;
		if ($rowst == $rowen && $colst == $colen) return -1;
		if ($rowst > $rowen) return -1;
		if ($colst > $colen) return -1;
		$mtmp['rows']=$rowst;
		$mtmp['rowe']=$rowen;
		$mtmp['cols']=$colst;
		$mtmp['cole']=$colen;
		$this->mergeinfo['set'][$sn][]=$mtmp;
	}


	/**
	* Unset original MergedCells
	* @param integer  $sn sheet-number  0 base indexed
	* @access public
	* @example ./sample3.php sample3
	*/
	function unsetCellMerge($sn){
		if ($sn < 0) return -1;
		$this->mergeinfo['unset'][$sn]=TRUE;
	}


	/**
	* make MergedCells-info
	* @param integer  $sn sheet-number  0 base indexed
	* @access private
	*/
	function makeMergeinfo($sn){
		if ($sn < 0) return -1;
		if (isset($this->mergeinfo['unset'][$sn])) unset($this->mergecells[$sn]);
		if (isset($this->mergeinfo['set'][$sn])){
			foreach($this->mergeinfo['set'][$sn] as $val){
	            if (count($this->mergecells[$sn])) 
				foreach($this->mergecells[$sn] as $key=>$val0){
					if ($val['rows']==$val0['rows'] && $val['cols']==$val0['cols'])
						unset($this->mergecells[$sn][$key]);
				}
				$this->mergecells[$sn][]=$val;
			}
		}
	}

	/**
	* Parse file and Remake
	* @param string $readfile full path filename for read
	* @param string $outfile filename for web output
	* @param string $path if not null then save file
	* @access public
	* @example ./sample.php sample
	*/
	function reviseFile($readfile,$outfile,$path=null){
		$Flag_Error_Reporting = error_reporting();
		error_reporting(E_ALL ^ E_NOTICE);
		$res = $this->parseFile($readfile);
		if ($this->isError($res)) {
			error_reporting($Flag_Error_Reporting);
			return $res;
		}
		$this->reviseCell();
		$res = $this->makeFile($outfile,$path);
		error_reporting($Flag_Error_Reporting);
		if ($this->isError($res)) return $res;
	}

	/**
	* Remake file
	* @param string $outfile filename for web output
	* @param string $path if not null then save file
	* @access public
	* @example ./sample4.php sample4
	*/
	function buildFile($outfile,$path=null){
		$this->reviseCell();
		$res = $this->makeFile($outfile,$path);
		if ($this->isError($res)) return $res;
	}

	/**
	* Copy Sheet
	* @param integer $orgsn original sheet-number  0 indexed
	* @param integer $num number of sheet to duplicate
	* @access public
	* @example ./sample2.php sample2
	*/
	function addSheet($orgsn,$num){
		if ($num < 1) return;
		$tmp['orgsn']=$orgsn;
		$tmp['count']=$num;
		$this->dupsheet[]=$tmp;
	}

	/**
	* Add hyperlink to Cell
	* @param integer $sn sheet number
	* @param integer $row Row position
	* @param integer $col Column posion  0indexed
	* @param string $desc cell description(option)
	* @param string $link absolute link path
	* @param integer $refrow reference row(option)
	* @param integer $refcol reference column(option)
	* @param integer $refsheet reference sheet number(option)
	* @access public
	* @example ./sample.php sample
	*/
	function addHLink($sn,$row,$col,$desc='',$link, $refrow = null, $refcol = null, $refsheet = null){
		if (trim($link)=='') return;
		if ($desc == '') {
			$opt=0x03;
			$disp='';
			$desc=$link;
		} else {
			$opt=0x17;
			$str=mb_convert_encoding($desc,'UTF-16LE',$this->charset);
			$disp=pack("V",mb_strlen($str,'UTF-16LE')+1).$str."\x00\x00";
		}
		$link=mb_convert_encoding($link,'UTF-16LE',$this->charset)."\x00\x00";
		$linkrcd=pack("vvvv",$row,$row,$col,$col)
				. pack("H*","d0c9ea79f9bace118c8200aa004ba90b02000000")
				. pack("V",$opt).$disp
				. pack("H*","e0c9ea79f9bace118c8200aa004ba90b")
				. pack("V",strlen($link)).$link;
		$this->hlink[$sn][]=pack("vv",0x1b8,strlen($linkrcd)) . $linkrcd;
		$this->addString($sn,$row,$col,$desc, $refrow, $refcol, $refsheet);
	}

	/**
	* Set remove Sheet number
	* @param integer $sheet sheet number  0 indexed
	* @access public
	* @example ./sample.php sample
	*/
	function rmSheet($sheet){
		if (is_numeric($sheet)){
			$this->rmsheets[$sheet]=TRUE;
		}
	}

	/**
	* Set remove Cell
	* @param integer $sheet sheet number
	* @param integer $row Row position
	* @param integer $col Column posion  0 base indexed
	* @access public
	* @example ./sample.php sample
	*/
	function rmCell($sheet,$row,$col){
		if (is_numeric($sheet) && is_numeric($row) && is_numeric($col)){
			$this->rmcells[$sheet][$row][$col]=TRUE;
		}
	}

	/**
	* Set Row height
	* @param integer $sheet sheet number
	* @param integer $row Row position
	* @param integer $height Height of the row, in twips = 1/20 of a point
	* @since Ver0.21
	* @access public
	* @example ./sample3.php sample3
	*/
	function chgRowHeight($sheet,$row,$height){
		if (is_numeric($sheet) && is_numeric($row) && is_numeric($height)){
		if ($sheet < 0) return -1;
		if ($row < 0 || $row > 65535) return -1;
		if ($height <= 0) return -1;
			$this->rowheight[$sheet][$row]=$height;
		}
	}

	/**
	* Set Column width
	* @param integer $sheet sheet number
	* @param integer $col Column position
	* @param integer $width Width of the columns in 1/256 of the width of the zero character
	* @since Ver0.21
	* @access public
	* @example ./sample3.php sample3
	*/
	function chgColWidth($sheet,$col,$width){
		if (is_numeric($sheet) && is_numeric($col) && is_numeric($width)){
		if ($sheet < 0) return -1;
		if ($col < 0 || $col > 255) return -1;
		if ($width <= 0) return -1;
		$this->colwidth[$sheet][$col]=$width;
		}
	}

	/**
	* Add String to Cell
	* @param integer $sheet sheet number
	* @param integer $row Row position
	* @param integer $col Column posion  0indexed
	* @param string $str string
	* @param integer $refrow reference row(option)
	* @param integer $refcol reference column(option)
	* @param integer $refsheet reference sheet number(option)
	* @access public
	* @example ./sample.php sample
	*/
	function addString($sheet,$row, $col, $str, $refrow = null, $refcol = null, $refsheet = null){
		$val['sheet']=$sheet;
		$val['row']=$row;
		$val['col']=$col;
		$val['str']=$str;
		$val['refrow']=$refrow;
		$val['refcol']=$refcol;
		$val['refsheet']=$refsheet;
		$this->revise_dat['add_str'][]=$val;
	}

	/**
	* Add Number to Cell
	* @param integer $sheet sheet number
	* @param integer $row Row position
	* @param integer $col Column position  0indexed
	* @param integer $num number
	* @param integer $refrow reference row(option)
	* @param integer $refcol refernce column(option)
	* @param integer $refsheet reference sheet number(option)
	* @access public
	* @example ./sample.php sample
	*/
	function addNumber($sheet,$row, $col, $num, $refrow = null, $refcol = null, $refsheet = null){
		$val['sheet']=$sheet;
		$val['row']=$row;
		$val['col']=$col;
		$val['num']=$num;
		$val['refrow']=$refrow;
		$val['refcol']=$refcol;
		$val['refsheet']=$refsheet;
		$this->revise_dat['add_num'][]=$val;
	}

	/**
	* Add Formula-Record to Cell by Direct
	*  This is Experimental method  2007/10/13
	*
	* @param integer $sheet sheet number
	* @param integer $row Row position
	* @param integer $col Column position  0 indexed
	* @param integer $record (formula binary-record)
	* @param integer $refrow reference row(option)
	* @param integer $refcol refernce column(option)
	* @param integer $refsheet reference sheet number(option)
	* @access private
	*/
	function addFormulaRecord($sheet,$row, $col, $record, $refrow = null, $refcol = null, $refsheet = null){
		if (strlen($record) < 1) return -1;
		if ($sheet < 0 || $row < 0 || $col < 0 || $refsheet < 0 || $refrow < 0 || $refcol < 0) return -1;
		$formlen = strlen($record);
		$header	= pack("vv", 0x06, 0x16 + $formlen);
		$data	  = pack("vvvdvVv", $row, $col, $this->_getcolxf($sheet,$col), 0, 3, 9, $formlen);
		$val['sheet']=$sheet;
		$val['row']=$row;
		$val['col']=$col;
		$val['record']=$header.$data.$record;
		$val['refrow']=$refrow;
		$val['refcol']=$refcol;
		$val['refsheet']=$refsheet;
		$this->revise_dat['option'][]=$val;
	}

	/**
	* Change original string to new string
	* @param string $org original String
	* @param string $new new string
	* @access public
	* @example ./sample.php sample
	*/
	function changeStr($org, $new){
		if ($new == '') $new = ' ';
		if (mb_detect_encoding($org,"ASCII,".$this->charset.",ISO-8859-1") == 'ISO-8859-1')
			$org=mb_convert_encoding($org,$this->charset,'auto');
		if (mb_detect_encoding($new,"ASCII,".$this->charset.",ISO-8859-1") == 'ISO-8859-1')
			$new=mb_convert_encoding($new,$this->charset,'auto');
		$tmp['org']=mb_convert_encoding($org,'UTF-16LE',$this->charset);
		$tmp['new']=mb_convert_encoding($new,'UTF-16LE',$this->charset);
		$this->revise_dat['replace'][]=$tmp;
	}

	/**
	* overwrite Sheetname
	* @param integer $sn sheet number
	* @param string $str new sheet name
	* @access public
	* @example ./sample.php sample
	*/
	function setSheetname($sn,$str){
			$len = strlen($str);
			if (mb_detect_encoding($str,"ASCII,ISO-8859-1")=="ASCII"){
				$opt =0;
			} else {
				$opt =1;
				$str = mb_convert_encoding($str,'UTF-16LE',$this->charset);
				$len = mb_strlen($str,'UTF-16LE');
			}
			$val = pack("CC",$len,$opt);
		$this->revise_dat['sheetname'][$sn]=$val.$str;
	}

	/**
	* overwrite header string
	* @param integer $sn sheet number
	* @param string $str new header-string
	* @access public
	* @example ./sample.php sample
	*/
	function setHeader($sn,$str){
			if (mb_detect_encoding($str,"ASCII,ISO-8859-1")=="ASCII"){
				$opt =0;
				$len = strlen($str);
			} else {
				$opt =1;
				$str = mb_convert_encoding($str,'UTF-16LE',$this->charset);
				$len = mb_strlen($str,'UTF-16LE');
			}
			$val = pack("vC",$len,$opt);
		$this->revise_dat['header'][$sn]=$val.$str;
	}

	/**
	* overwrite footer string
	* @param integer $sn sheet number
	* @param string $str new footer-string
	* @access public
	* @example ./sample.php sample
	*/
	function setFooter($sn,$str){
			if (mb_detect_encoding($str,"ASCII,ISO-8859-1")=="ASCII"){
				$opt =0;
				$len = strlen($str);
			} else {
				$opt =1;
				$str = mb_convert_encoding($str,'UTF-16LE',$this->charset);
				$len = mb_strlen($str,'UTF-16LE');
			}
			$val = pack("vC",$len,$opt);
		$this->revise_dat['footer'][$sn]=$val.$str;
	}

	/**
	* Add Blank Cell
	* @param integer $sheet sheet number  0 base indexed
	* @param integer $row Row position  0 base indexed
	* @param integer $col Column posion  0 base indexed
	* @param integer $refrow reference row(option)
	* @param integer $refcol reference column(option)
	* @param integer $refsheet ref sheet number(option)
	* @access public
	* @example ./sample3.php sample3
	*/
	function addBlank($sheet,$row, $col, $refrow, $refcol, $refsheet = null){
		$val['sheet']=$sheet;
		$val['row']=$row;
		$val['col']=$col;
		$val['refrow']=$refrow;
		$val['refcol']=$refcol;
		$val['refsheet']=$refsheet;
		$this->revise_dat['add_blank'][]=$val;
	}

	/**
	* Set Printtitle
	* @param integer $sheet sheet number  0 base indexed
	* @param integer $row1st First Row position  0 base indexed
	* @param integer $rowlast Last Row position  0 base indexed
	* @param integer $col1st First Column position  0 base indexed
	* @param integer $collast Last Column position  0 base indexed
	* @access public
	* @example ./sample4.php sample4
	*/
	function setPrintTitle($sheet,$row1st=null,$rowlast=null,$col1st=null,$collast=null){
		if ($sheet < 0) return;
		if ($row1st===null && $col1st===null) return;
		if ($rowlast===null) $rowlast=$row1st;
		if ($collast===null) $collast=$col1st;
		if ($row1st!==null) if ($row1st > $rowlast) return;
		if ($col1st!==null) if ($col1st > $collast) return;
		$this->prntitle[$sheet]['row1st']=$row1st;
		$this->prntitle[$sheet]['rowlast']=$rowlast;
		$this->prntitle[$sheet]['col1st']=$col1st;
		$this->prntitle[$sheet]['collast']=$collast;
	}

	/**
	* Set Row Print-title
	* @param integer $sheet sheet number  0 base indexed
	* @param integer $row1st First Row position  0 base indexed
	* @param integer $rowlast Last Row position  0 base indexed
	* @access public
	* @example ./sample4.php sample4
	*/
	function setPrintTitleRow($sheet,$row1st,$rowlast=null){
		if ($sheet < 0) return;
		if ($row1st===null) return;
		if ($rowlast===null) $rowlast=$row1st;
		if ($row1st > $rowlast) return;
		$this->prntitle[$sheet]['row1st']=$row1st;
		$this->prntitle[$sheet]['rowlast']=$rowlast;
	}

	/**
	* Set Column Printtitle
	* @param integer $sheet sheet number  0 base indexed
	* @param integer $col1st First Column position  0 base indexed
	* @param integer $collast Last Column position  0 base indexed
	* @access public
	* @example ./sample4.php sample4
	*/
	function setPrintTitleCol($sheet,$col1st,$collast=null){
		if ($sheet < 0) return;
		if ($col1st===null) return;
		if ($collast===null) $collast=$col1st;
		if ($col1st > $collast) return;
		$this->prntitle[$sheet]['col1st']=$col1st;
		$this->prntitle[$sheet]['collast']=$collast;
	}

	/**
	* Set PrintArea
	* @param integer $sheet sheet number  0 base indexed
	* @param integer $row1st First Row position  0 base indexed
	* @param integer $rowlast Last Row position  0 base indexed
	* @param integer $col1st First Column position  0 base indexed
	* @param integer $collast Last Column position  0 base indexed
	* @access public
	* @example ./sample4.php sample4
	*/
	function setPrintArea($sheet,$row1st,$rowlast,$col1st,$collast){
		if ($sheet < 0) return;
		if ($row1st>$rowlast || $col1st>$collast) return;
		$this->prnarea[$sheet]['row1st']=$row1st;
		$this->prnarea[$sheet]['rowlast']=$rowlast;
		$this->prnarea[$sheet]['col1st']=$col1st;
		$this->prnarea[$sheet]['collast']=$collast;
	}


	/**
	* Add Blank Cell
	* @param integer $sheet sheet number
	* @param integer $row Row position
	* @param integer $col Column posion  0indexed
	* @param integer $refrow reference row(option)
	* @param integer $refcol reference column(option)
	* @param integer $refsheet reference sheet number(option)
	* @access private
	*/
	function _addBlank($sheet,$row, $col, $refrow, $refcol, $refsheet = null){
		if (($row < 0) || ($col < 0) || ($sheet < 0)) return -1;
		if (($refrow < 0) || ($refcol < 0)) return -1;
		if ($refsheet === null) $refsheet = $sheet;
		$xf= (isset($this->cellblock[$refsheet][$refrow][$refcol]['xf'])) ? $this->cellblock[$refsheet][$refrow][$refcol]['xf'] : $this->_getcolxf($refsheet,$refcol);
		$header    = pack('vv', Type_BLANK, 0x06);
		$data      = pack('vvv', $row, $col, $xf);
		$this->cellblock[$sheet][$row][$col]['xf']=$xf;
		$this->cellblock[$sheet][$row][$col]['type']=Type_BLANK;
		$this->cellblock[$sheet][$row][$col]['dat']='';
		$this->cellblock[$sheet][$row][$col]['record']=bin2hex($header.$data);
	}

	/**
	* Add String to Cell (for internal access)
	* @param  $sn:sheet number,$row:Row position,$col:Column posion  0indexed
	* @param  $str:string
	* @param  $refrow:referrence row(option)
	* @param  $refcol:ref column(option)
	* @param  $refsheet:ref sheet number(option)
	* @access private
	*/
	function _addString($sheet,$row, $col, $str, $refrow = null, $refcol = null, $refsheet = null){
		if (($row < 0) || ($col < 0) || ($sheet < 0)) return -1;
		if ($refsheet === null) $refsheet = $sheet;
		if (($refrow !== null) && ($refcol !== null)) {
			$xf= (isset($this->cellblock[$refsheet][$refrow][$refcol]['xf'])) ? $this->cellblock[$refsheet][$refrow][$refcol]['xf'] : $this->_getcolxf($refsheet,$refcol);
		} else {
			$xf= (isset($this->cellblock[$sheet][$row][$col]['xf'])) ? $this->cellblock[$sheet][$row][$col]['xf'] : $this->_getcolxf($sheet,$col);
		}
		if (mb_detect_encoding($str,"ASCII,ISO-8859-1")=="ASCII"){
			$opt =0;
			$str = mb_convert_encoding($str, "UTF-16LE", "ASCII");
		} else {
			$opt =1;
			$str = mb_convert_encoding($str, "UTF-16LE", $this->charset);
		}
		$len = mb_strlen($str, 'UTF-16LE');
		$tempsst['len']=$len;
		$tempsst['opt']=$opt;
		$tempsst['rtn']=0;
		$tempsst['apn']=0;
		$tempsst['str']=bin2hex($str);
		$tempsst['rt']='';
		$tempsst['ap']='';
		$this->eachsst[]=$tempsst;
		$header    = pack('vv', Type_LABELSST, 0x0a);
		$data      = pack('vvvV', $row, $col, $xf, count($this->eachsst)-1);
		$this->cellblock[$sheet][$row][$col]['xf']=$xf;
		$this->cellblock[$sheet][$row][$col]['type']=Type_LABELSST;
		$this->cellblock[$sheet][$row][$col]['dat']=bin2hex(pack("V",count($this->eachsst)-1));
		$this->cellblock[$sheet][$row][$col]['record']=bin2hex($header.$data);
		return;
	}

	/**
	* Add Number to Cell
	* @param  $sn:sheet number,$row:Row position,$col:column posion  0indexed
	* @param  $num:number
	* @param  $refrow:referrence row(option), $refcol:ref column(option)
	* @param  $refsheet:ref sheet number(option)
	* @access private
	*/
	function _addNumber($sheet,$row, $col, $num, $refrow = null, $refcol = null, $refsheet = null){
		if (($row < 0) || ($col < 0) || ($sheet < 0)) return -1;
		if ($refsheet === null) $refsheet = $sheet;
		if (($refrow !== null) && ($refcol !== null)) {
			$xf= (isset($this->cellblock[$refsheet][$refrow][$refcol]['xf'])) ? $this->cellblock[$refsheet][$refrow][$refcol]['xf'] : $this->_getcolxf($refsheet,$refcol);
		} else {
			$xf= (isset($this->cellblock[$sheet][$row][$col]['xf'])) ? $this->cellblock[$sheet][$row][$col]['xf'] : $this->_getcolxf($sheet,$col);
		}
		$packednum = (pack("N",1)==pack("L",1)) ? strrev(pack("d", $num)) : pack("d", $num); // added 
		$header    = pack('vv', Type_NUMBER, 0x0e);
//		$data      = pack('vvvd', $row, $col, $xf, $num);
	$data      = pack('vvv', $row, $col, $xf).$packednum; // edited 
		$this->cellblock[$sheet][$row][$col]['xf']=$xf;
		$this->cellblock[$sheet][$row][$col]['type']=Type_NUMBER;
//		$this->cellblock[$sheet][$row][$col]['dat']=bin2hex(pack("d", $num));
	$this->cellblock[$sheet][$row][$col]['dat']=bin2hex($packednum); //edited 
		$this->cellblock[$sheet][$row][$col]['record']=bin2hex($header.$data);
		return;
	}

	/**
	* read OLE container
	* @param  $Fname:filename
	* @access private
	*/
	function __oleread($Fname){
		if(!is_readable($Fname)) {
			return $this->raiseError("ERROR Cannot read file ${Fname} \nProbably there is not reading permission whether there is not a file");
		}
	// 2007.11.19
		$this->Flag_Magic_Quotes = get_magic_quotes_runtime();
		if ($this->Flag_Magic_Quotes) set_magic_quotes_runtime(0);
		$ole_data = @file_get_contents($Fname);
		if ($this->Flag_Magic_Quotes) set_magic_quotes_runtime($this->Flag_Magic_Quotes);
		if (!$ole_data) { 
			return $this->raiseError("ERROR Cannot open file ${Fname} \n");
		}
		if (substr($ole_data, 0, 8) != pack("CCCCCCCC",0xd0,0xcf,0x11,0xe0,0xa1,0xb1,0x1a,0xe1)) {
			return $this->raiseError("ERROR Template file(${Fname}) is not EXCEL file.\n");
	   	}
		$numDepots = $this->__get4($ole_data, 0x2c);
		$sStartBlk = $this->__get4($ole_data, 0x3c);
		$ExBlock = $this->__get4($ole_data, 0x44);
		$numExBlks = $this->__get4($ole_data, 0x48);

		$len_ole = strlen($ole_data);
		if ($numDepots > ($len_ole / 65536 +1))
			return $this->raiseError("ERROR file($Fname) is broken (numDepots)");
		if ($sStartBlk > ($len_ole / 512 +1))
			return $this->raiseError("ERROR file($Fname) is broken (sStartBlk)");
		if ($ExBlock > ($len_ole / 512 +1))
			return $this->raiseError("ERROR file($Fname) is broken (ExBlock)");
		if ($numExBlks > ($len_ole / 512 +1))
			return $this->raiseError("ERROR file($Fname) is broken (numExBlks)");

		$DepotBlks = array();
		$pos = 0x4c;
		$dBlks = $numDepots;
		if ($numExBlks != 0) $dBlks = (0x200 - 0x4c)/4;
		for ($i = 0; $i < $dBlks; $i++) {
			$DepotBlks[$i] = $this->__get4($ole_data, $pos);
			$pos += 4;
		}

		for ($j = 0; $j < $numExBlks; $j++) {
			$pos = ($ExBlock + 1) * 0x200;
			$ReadBlks = min($numDepots - $dBlks, 0x200 / 4 - 1);
			for ($i = $dBlks; $i < $dBlks + $ReadBlks; $i++) {
				$DepotBlks[$i] = $this->__get4($ole_data, $pos);
				$pos += 4;
			}   
			$dBlks += $ReadBlks;
			if ($dBlks < $numDepots) $ExBlock = $this->__get4($ole_data, $pos);
		}

		$pos = 0;
		$index = 0;
		$BlkChain = array();
		for ($i = 0; $i < $numDepots; $i++) {
			$pos = ($DepotBlks[$i] + 1) * 0x200;
			for ($j = 0 ; $j < 0x200 / 4; $j++) {
				$BlkChain[$index] = $this->__get4($ole_data, $pos);
				$pos += 4 ;
				$index++;
			}
		}

		$eoc= 0xFE | (0xFFFFFF << 8);
		$pos = 0;
		$index = 0;
		$sBlkChain = array();
		while ($sStartBlk != $eoc) {
			$pos = ($sStartBlk + 1) * 0x200;
			for ($j = 0; $j < 0x80; $j++) {
				$sBlkChain[$index] = $this->__get4($ole_data, $pos);
				$pos += 4 ;
				$index++;
			}
			$chk[$sStartBlk]=true;
			$sStartBlk = $BlkChain[$sStartBlk];
			if(isset($chk[$sStartBlk])){
	return $this->raiseError("Big Block chain for small-block ERROR 1\nTemplate file is broken");
			}
		}
		unset($chk);
		$block = $this->__get4($ole_data, 0x30);
		$pos = 0;
		$entry = '';
		while ($block != $eoc)  {
			$pos = ($block + 1) * 0x200;
			$entry .= substr($ole_data, $pos, 0x200);
			$chk[$block]=true;
			$block = $BlkChain[$block];
			if(isset($chk[$block])){
	return $this->raiseError("Big Block chain for Entry  ERROR 2\nTemplate file is broken");
			}
		}
		unset($chk);
		$offset = 0;
		$bookKey=0;
		$tmpDir=array();
		$rootBlock =$this->__get4($entry, 0x74);
		while ($offset < strlen($entry)) {
			  $d = substr($entry, $offset, 0x80);
			  $name = str_replace("\x00", "", substr($d,0,$this->__get2($d,0x40)));
			if (($name == "Workbook") || ($name == "Book")) {
				$wbstartBlock =$this->__get4($d, 0x74);
				$wbsize = $this->__get4($d, 0x78);
			}
			if ($name == "Root Entry" || $name == "R") {
//				$rootBlock =$this->__get4($d, 0x74);
			} else if (strlen($name)>0){
				$tmpDir['startB']=$this->__get4($d, 0x74);
				$tmpDir['size']=$this->__get4($d, 0x78);
				$tmpDir['dat']='';
				if (($name == "Workbook") || ($name == "Book")) $bookKey=$name;
				if ($this->Flag_inherit_Info != 1){
					if (($name == "Workbook") || ($name == "Book")) $this->orgStreams[$name]=$tmpDir;
				} else {
					$this->orgStreams[$name]=$tmpDir;
				}
			}
			$offset += 0x80;
		}

		if (! isset($rootBlock)) return $this->raiseError("Unknown OLE-type. Can't find Root-Entry");
		$pos = 0;
		$rdata = '';
		while ($rootBlock != $eoc)  {
			$pos = ($rootBlock + 1) * 0x200;
			$rdata .= substr($ole_data, $pos, 0x200);
			$chk[$rootBlock]=true;
			$rootBlock = $BlkChain[$rootBlock];
			if(isset($chk[$rootBlock])){
				return $this->raiseError("Root Block chain read ERROR 2.1\n  Template file is broken");
			}
			unset($chk);
		}
		foreach($this->orgStreams as $name=>$tdir){
			if ($tdir['size'] <1) continue;
			if ($tdir['size'] < 0x1000) {
				$pos = 0;
				$tData = '';
				$block = $tdir['startB'];
				while ($block != $eoc) {
					$pos = $block * 0x40;
					$tData .= substr($rdata, $pos, 0x40);
					$chk[$block]=true;
					$block = $sBlkChain[$block];
					if(isset($chk[$block])){
		return $this->raiseError("Root Block chain read ERROR 2.2\n  Template file is broken");
					}
				}
				unset($chk);
				$this->orgStreams[$name]['dat'] = $tData;
			} else {
				$numBlocks = ($tdir['size'] + 0x1ff) / 0x200;
				if ($numBlocks == 0) continue;
				$tData = '';
				$block = $tdir['startB'];
				$pos = 0;
				while ($block != $eoc) {
					$pos = ($block + 1) * 0x200;
					$tData .= substr($ole_data, $pos, 0x200);
					$chk[$block]=true;
					$block = $BlkChain[$block];
					if(isset($chk[$block])){
					return $this->raiseError("Big Block chain ERROR 3\nTemplate file is broken");
					}
				}
				unset($chk);
				$this->orgStreams[$name]['dat'] = $tData;
			}
		}
		return $this->orgStreams[$bookKey]['dat'];
	}

	/**
	* parse sheetblock
	* @access private
	*/
	function __parsesheet(&$dat,$sn,$spos){
		$code = 0;
		$version = $this->__get2($dat,$spos + 4);
		$substreamType = $this->__get2($dat,$spos + 6);
		if ($version != Code_BIFF8) {
			return $this->raiseError("Contents(included sheet) is not BIFF8 format.\n");
		}
		if ($substreamType != Code_Worksheet) {
			return $this->raiseError("Contents is unknown format.\nCan't find Worksheet.\n");
		}
		$tmp='';
		$dimnum=0;
		$bof_num=0;
		$sposlimit=strlen($dat);
		while($code != Type_EOF) {
			if ($spos > $sposlimit) {
				return $this->raiseError("Sheet $sn Read ERROR\nTemplate file is broken.\n");
			}
			$code = $this->__get2($dat,$spos);
			$length = $this->__get2($dat,$spos + 2);
			if ($code == Type_BOF) $bof_num++;
			if ($bof_num > 1){
				$tmp.=substr($dat, $spos, $length+4);
				while($code != Type_EOF) {
					if ($spos > $sposlimit) {
						return $this->raiseError("Parse-Sheet Error\n");
					}
					$spos += $length+4;
					$code = $this->__get2($dat,$spos);
					$length = $this->__get2($dat,$spos + 2);
					$tmp.=substr($dat, $spos, $length+4);
				}
				$bof_num--;
				$spos += $length+4;
				$code = $this->__get2($dat,$spos);
				$length = $this->__get2($dat,$spos + 2);
				$tmp.=substr($dat, $spos, $length+4);
			}else
			switch ($code) {
				case Type_HEADER:
					if ($tmp) {
						$this->sheetbin[$sn]['preHF']=$tmp;
						$tmp='';
					}
					$this->sheetbin[$sn]['header']=substr($dat, $spos, $length+4);
					break;
				case Type_FOOTER:
//					if ($tmp) {
//						$this->sheetbin[$sn]['preHF']=$tmp;
//						$tmp='';
//					}
					$this->sheetbin[$sn]['footer']=substr($dat, $spos, $length+4);
					break;
				case Type_DEFCOLWIDTH:
					$tmp.=substr($dat, $spos, $length+4);
					$this->sheetbin[$sn]['preBT']=$tmp;
					$tmp='';

					$this->defcolW[$sn]=$this->__get2($dat,$spos+4);
					break;
				case Type_DEFAULTROWHEIGHT:
					$tmp.=substr($dat, $spos, $length+4);
					$this->defrowH[$sn]=$this->__get2($dat,$spos+6);
					break;
				case Type_COLINFO:
					$work['head']=substr($dat, $spos, 4);
					$colst=$this->__get2($dat,$spos + 4);
					$colen=$this->__get2($dat,$spos + 6);
					if ($colen >255) $colen=255;
					$work['width']=$this->__get2($dat,$spos + 8);
					$work['xf']=$this->__get2($dat,$spos + 10);
					$work['opt']=$this->__get2($dat,$spos + 12);
					$work['unk']=$this->__get2($dat,$spos + 14);
					for ($i=$colst;$i<=$colen;$i++){
						$work['colst']=$i;
						$work['colen']=$i;
						$work['all']=substr($dat, $spos, 4);
						$work['all'].=pack("v",$i).pack("v",$i);
						$work['all'].=substr($dat, $spos+8, $length-4);
						$this->colblock[$sn][$i]=$work;
					}
					unset($work);
					break;
				case Type_DIMENSION:
					$tmp.=substr($dat, $spos, $length+4);
	if ($dimnum==0){
					$this->sheetbin[$sn]['preCB']=$tmp;
					$tmp='';
	}
	$dimnum++;
					break;
				case Type_ROW:
					$row=$this->__get2($dat,$spos + 4);
					$this->rowblock[$sn][$row]['rowhead']=bin2hex(substr($dat, $spos, 4));
					$this->rowblock[$sn][$row]['col1st']=$this->__get2($dat,$spos + 6);
					$this->rowblock[$sn][$row]['collast']=$this->__get2($dat,$spos + 8);
					$this->rowblock[$sn][$row]['height']=$this->__get2($dat,$spos + 10);
					$this->rowblock[$sn][$row]['notused0']=$this->__get2($dat,$spos + 12);
					$this->rowblock[$sn][$row]['notused1']=$this->__get2($dat,$spos + 14);
					$this->rowblock[$sn][$row]['opt0']=$this->__get2($dat,$spos + 16);
					$this->rowblock[$sn][$row]['opt1']=$this->__get2($dat,$spos + 18);
					break;
				case Type_RK2:
				case Type_LABEL:
				case Type_LABELSST:
				case Type_NUMBER:
				case Type_FORMULA2:
				case Type_BOOLERR:
				case Type_BLANK:
					$row=$this->__get2($dat,$spos + 4);
					$col=$this->__get2($dat,$spos + 6);
					$this->cellblock[$sn][$row][$col]['xf']=$this->__get2($dat,$spos + 8);
					$this->cellblock[$sn][$row][$col]['type']=$code;
					$this->cellblock[$sn][$row][$col]['dat']=bin2hex(substr($dat, $spos+10, $length-6));
					$this->cellblock[$sn][$row][$col]['record']=bin2hex(substr($dat, $spos, $length+4));
					$this->cellblock[$sn][$row][$col]['string']='';
	if ($code == Type_FORMULA2){
		$dispnum = substr($dat, $spos+10, 8);
		$opflag = $this->__get2($dat,$spos + 18) | 0x02; // Calculate on open
		$tokens = substr($dat, $spos+20, $length - 16);
		$this->cellblock[$sn][$row][$col]['dat']=bin2hex($dispnum . pack("v",$opflag) . $tokens);
		$this->cellblock[$sn][$row][$col]['record']='';
		if ($this->exp_mode & 0x01) {
			if ($this->__get2($dat,$spos + $length + 4) == Type_SharedFormula){
				$spos += $length + 4;
				$length = $this->__get2($dat,$spos + 2);
				$sharedform[$row][$col]['firstR'] = $this->__get2($dat,$spos + 4);
				$sharedform[$row][$col]['lastR'] = $this->__get2($dat,$spos + 6);
				$sharedform[$row][$col]['firstC'] = $this->__get1($dat,$spos + 8);
				$sharedform[$row][$col]['lastC'] = $this->__get1($dat,$spos + 9);
				$sharedform[$row][$col]['formula'] = bin2hex(substr($dat,$spos+ 12,$length-8));
				$cur[$row][$col]=$this->__detrelcel($this->cellblock[$sn][$row-1][$col]['dat'],$sharedform[$row][$col]['formula'],$row-1,$col);
			}
			$sfdat=pack("H*", $this->cellblock[$sn][$row][$col]['dat']);
			if((($this->__get2($sfdat,8) & 8) == 8) && ($this->__get2($sfdat,14) == 5) && ($this->__get1($sfdat,16) == 1)){
				$refr =$this->__get2($sfdat,17);
		        	$refc =$this->__get2($sfdat,19);
				if (isset($sharedform[$refr][$refc]['formula'])){
					$this->cellblock[$sn][$row][$col]['record']='';
					$this->cellblock[$sn][$row][$col]['dat']=substr($sfdat, 0, 8);
					$this->cellblock[$sn][$row][$col]['dat'].=pack('v',0);
					$this->cellblock[$sn][$row][$col]['dat'].=substr($sfdat, 10, 4);
					$this->cellblock[$sn][$row][$col]['dat']=bin2hex($this->cellblock[$sn][$row][$col]['dat']);
	//			$this->cellblock[$sn][$row][$col]['dat'].=$sharedform[$refr][$refc]['formula'];
					$this->cellblock[$sn][$row][$col]['dat'].=bin2hex($this->__editformula($cur[$refr][$refc], pack("H*",$sharedform[$refr][$refc]['formula']), $row, $col));
				}
			}
		} else {
			if ($this->__get2($dat,$spos + $length + 4) == Type_SharedFormula){
				$spos += $length + 4;
				$length = $this->__get2($dat,$spos + 2);
				$this->cellblock[$sn][$row][$col]['sharedform']=substr($dat,$spos,$length+4);
			}
	  	}
		if ($this->__get2($dat,$spos + $length + 4) == Type_STRING){
			$spos += $length + 4;
			$length = $this->__get2($dat,$spos + 2);
			$this->cellblock[$sn][$row][$col]['string']=substr($dat,$spos,$length+4);
		}
	}
	
					break;
				case Type_MULBLANK:
					$muln=($length-6)/2;
					$row=$this->__get2($dat,$spos + 4);
					$col=$this->__get2($dat,$spos + 6);
					$i=-1;
					while(++$i < $muln){
						$this->cellblock[$sn][$row][$i+$col]['xf']=$this->__get2($dat,$spos+8+$i*2);
						$this->cellblock[$sn][$row][$i+$col]['type']=Type_BLANK;
						$this->cellblock[$sn][$row][$i+$col]['dat']='';
						$this->cellblock[$sn][$row][$i+$col]['record']=bin2hex(pack("vvvv", 0x0201, 0x06, $row, $i+$col). substr($dat, $spos+8+$i*2, 2));
					}
					break;
				case Type_MULRK:
					$muln=($length-6)/6;
					$row=$this->__get2($dat,$spos + 4);
					$col=$this->__get2($dat,$spos + 6);
					$i=-1;
					while(++$i < $muln){
						$this->cellblock[$sn][$row][$i+$col]['xf']=$this->__get2($dat,$spos+8+$i*6);
						$this->cellblock[$sn][$row][$i+$col]['type']=Type_RK;
						$this->cellblock[$sn][$row][$i+$col]['dat']=bin2hex(substr($dat, $spos+10+$i*6, 4));
						$this->cellblock[$sn][$row][$i+$col]['record']=bin2hex(pack("vvvv", 0x027e, 0x0a, $row, $i+$col). substr($dat, $spos+8+$i*6, 6));
					}
					break;
				case Type_MERGEDCELLS:
					$numrange=$this->__get2($dat,$spos+4);
					for($i=0;$i<$numrange;$i++){
						$mtmp['rows']=$this->__get2($dat,$spos+6+$i*8);
						$mtmp['rowe']=$this->__get2($dat,$spos+8+$i*8);
						$mtmp['cols']=$this->__get2($dat,$spos+10+$i*8);
						$mtmp['cole']=$this->__get2($dat,$spos+12+$i*8);
						$this->mergecells[$sn][]=$mtmp;
					}
					break;
				case Type_SELECTION:
					$tmp.= substr($dat, $spos, $length+4);
					if ($this->__get2($dat,$spos+$length+4)==Type_SELECTION) break;
					$this->sheetbin[$sn]['preMG']=$tmp;
					$tmp='';
					break;
				case Type_DBCELL:
					break;
				case Type_BUTTONPROPERTYSET:
					break;
				case Type_EOF:
					break;
				default:
					$tmp.= substr($dat, $spos, $length+4);
			}
			$spos += $length+4;
		}
		$this->sheetbin[$sn]['tail']=$tmp;
	}

	/**
	* detect some type of relative token in formula-record
	* @access private
	*/
	function __detrelcel($org,$share,$row,$col){
		$org=pack("H*",$org);
		$org=substr($org,14);
		$share=pack("H*",$share);
		$lenorg=strlen($org);
		$lenshare=strlen($share);
		if ($lenorg != $lenshare) return;
		$i=0;
		while($i < $lenorg - 3){
			if (($this->__get1($org,$i)==0x44) && ($this->__get1($share,$i)==0x4c)){
				if (((($this->__get2($org,$i+1)-$row) & 0xffff)== $this->__get2($share,$i+1)) &&
					((($this->__get1($org,$i+3)-$col) & 0xff)== $this->__get1($share,$i+3))){
					$tmp[$i]=1;
				}
			}
			$i++;
		}
		return $tmp;
	}

	/**
	* change relative token to absolute
	* @access private
	*/
	function __editformula($cur, $formula, $row, $col){
		$i=0;
		$tmp='';
		$lenform=strlen($formula);
		while($i < $lenform){
			if ($cur[$i]){
				$tmp.=chr(0x44);
				$tmp.=pack("v",$this->__get2($formula,$i+1)+$row);
				$tmp.=pack("C",$this->__get1($formula,$i+3)+$col);
				$i+=3;
			} else {
				$tmp.=substr($formula,$i,1);
			}
			$i++;
		}
		return $tmp;
	}

	/**
	* remake Row records
	* @access private
	*/
	function __makeRowRecord($sn){
		$tmp='';
		if(isset($this->rowblock[$sn]))
		foreach((array)$this->rowblock[$sn] as $key => $val) {
			$tmp.=pack("H*",$val['rowhead']);
			$tmp.=pack("vvvv",$key,$val['col1st'],$val['collast'],$val['height']);
			$tmp.=pack("vvvv",$val['notused0'],$val['notused1'],$val['opt0'],$val['opt1']);
		}
		return $tmp;
	}

	/**
	* remake Column records
	* @access private
	*/
	function __makeColRecord($sn){
		$tmp='';
		if(isset($this->colblock[$sn]))
		foreach((array)$this->colblock[$sn] as $key => $val) {
			if ($val['all']){
				$tmp.=$val['all'];
			} else {
				$tmp.=$val['head'];
				$tmp.=pack("vvv",$val['colst'],$val['colen'],$val['width']);
				$tmp.=pack("vvv",$val['xf'],$val['opt'],$val['unk']);
			}
		}
//print bin2hex($tmp)."\n";exit;
		return $tmp;
	}

	/**
	* remake Cell records
	* @access private
	*/
	function __makeCellRecord($sn){
		$tmp='';
		if(isset($this->cellblock[$sn]))
		foreach((array)$this->cellblock[$sn] as $keyR => $rowval) {
			ksort($rowval);
			foreach($rowval as $keyC => $cellval) {
			  if (isset($this->rmcells[$sn][$keyR][$keyC])) continue;
// FIXME		  if ($this->rmcells[$sn][$keyR][$keyC]) continue;
		if (!isset($cellval['record'])) $cellval['record']='';
			  if ($cellval['record']) {
				$tmp.=pack("H*",$cellval['record']);
			  } else {
				$tmp.=pack("vv",$cellval['type'],strlen(pack("H*",$cellval['dat']))+6);
				$tmp.=pack("vvv",$keyR,$keyC,$cellval['xf']);
				$tmp.=pack("H*",$cellval['dat']);
			  }
				if (isset($cellval['sharedform'])) $tmp.=$cellval['sharedform'];
				if (isset($cellval['string'])) $tmp.=$cellval['string'];
			}
		}
		return $tmp;
	}

	/**
	* remake sheet-block
	* @access private
	*/
	function __makeSheet($sn,$ref){
		$this->makeMergeinfo($sn);
		$sno=$this->stable[$sn];
		$tmp='';
		$tmp.=$this->sheetbin[$sno]['preHF'];
		if (isset($this->revise_dat['header'][$sn])){
			$tmp.=pack("vv",Type_HEADER,strlen($this->revise_dat['header'][$sn]));
			$tmp.=$this->revise_dat['header'][$sn];
		} else
		$tmp.=$this->sheetbin[$sno]['header'];
		if (isset($this->revise_dat['footer'][$sn])){
			$tmp.=pack("vv",Type_FOOTER,strlen($this->revise_dat['footer'][$sn]));
			$tmp.=$this->revise_dat['footer'][$sn];
		} else
		$tmp.=$this->sheetbin[$sno]['footer'];
// 2007.04.15 change start by ume
		$tmp.=$this->sheetbin[$sno]['preBT'];
		$tmp.=$this->__makeColRecord($sn);
// 2007.04.15 change end
		$tmp.=$this->sheetbin[$sno]['preCB'];
		$tmp.=$this->__makeRowRecord($sn);
		$tmp.=$this->__makeCellRecord($sn);
// TEST
$tmp.=$this->_makeImageOBJ($sn);
		if ($sn == $sno) {
			$tmp.=$this->sheetbin[$sno]['preMG'];
			$tmp.=$this->makemergecells($sn);
			$tmp.=$this->sheetbin[$sno]['tail'];
		} else {
			if ($this->opt_ref3d){
				$search='5110130001020000b0000b003b....';
				$change='5110130001020000b0000b003b'.bin2hex(pack("v",$ref));
				$this->sheetbin[$sno]['preMG']=pack("H*",ereg_replace($search,$change,bin2hex($this->sheetbin[$sno]['preMG'])));
				$this->sheetbin[$sno]['tail']=pack("H*",ereg_replace($search,$change,bin2hex($this->sheetbin[$sno]['tail'])));
			}
			$tmp.=$this->resetSelectFlag($this->sheetbin[$sno]['preMG']);
			$tmp.=$this->makemergecells($sn);
			$tmp.=$this->resetSelectFlag($this->sheetbin[$sno]['tail']);
		}
		if (isset($this->hlink[$sn]))
		foreach((array)$this->hlink[$sn] as $val){
			$tmp.=$val;
		}
		$tmp.=pack("H*","0a000000");
		return $tmp;
	}

	/**
	* rebuild MERGEDCELLS-record
	* @access private
	*/
	function makemergecells($sn){
		if (! isset($this->mergecells[$sn])) return '';
		if (count($this->mergecells[$sn])==0) return '';
		$ret='';
		$i=0;
		$tmp='';
		foreach($this->mergecells[$sn] as $val){
			$tmp.=pack("v",$val['rows']);
			$tmp.=pack("v",$val['rowe']);
			$tmp.=pack("v",$val['cols']);
			$tmp.=pack("v",$val['cole']);
			if (++$i >=1026 ){
				$ret.=pack("vv",Type_MERGEDCELLS,strlen($tmp)+2).pack("v",1026).$tmp;
				$tmp='';
				$i=0;
			}
		}
		if ($i>0) $ret.=pack("vv",Type_MERGEDCELLS,strlen($tmp)+2).pack("v",$i).$tmp;
		return $ret;
	}

	/**
	* Clear Selected Flag from WINDOW2-record
	* @access private
	*/
	function resetSelectFlag(&$dat){
		$spos=0;
		$limit=strlen($dat);
		while($spos < $limit){
			$code=$this->__get2($dat,$spos);
			if ($code == Type_WINDOW2){
				$chdat  = substr($dat, 0, $spos+5);
				$chdat .= pack("C", $this->__get1($dat, $spos + 5) & 0xf9);
				$chdat .= substr($dat, $spos + 6);
				return $chdat;
			}
			$spos += $this->__get2($dat,$spos + 2) + 4;
		}
		return $dat;
	}

	/**
	* parse sst-record
	* @access private
	*/
	function __parsesst(&$dat, $pos, $length) {
		$numref=$this->__get4($dat,$pos+8);
		$sspos =12;
		$sstnum=0;
		$limit=$pos + $length +4;
		while ($sstnum < $numref) {
			if ($pos+$sspos+2 > $limit) {
				if ($this->__get2($dat,$limit) == Type_CONTINUE) {
					$pos = $limit;
					$length = $this->__get2($dat,$pos + 2);
					$limit += $length + 4;
					$sspos = 4;
				} else break;
			}
			$slen=$this->__get2($dat,$pos+$sspos);
			$tempsst['len']=$slen;
			$opt=$this->__get1($dat,$pos+$sspos+2);
			$sspos += 3;
			if ($opt & 0x01) $slen *=2;
			if ($opt & 0x04) $optlen =4; else $optlen =0;
			if ($opt & 0x08) {
				$optlen +=2;
				$rtnum = $this->__get2($dat,$pos+$sspos);
				if ($opt & 0x04) $apnum = $this->__get4($dat,$pos+$sspos+2);
				else $apnum = 0;
			} else {
				$rtnum = 0;
				if ($opt & 0x04) $apnum = $this->__get4($dat,$pos+$sspos);
				else $apnum = 0;
			}
			$tempsst['opt']=$opt;
			$tempsst['rtn']=$rtnum;
			$tempsst['apn']=$apnum;
			$sspos += $optlen;
			if ($pos+$sspos+$slen > $limit) {
				$fusoku=($pos+$sspos+$slen)-$limit;
				$slen -= $fusoku;
				$sststr=$this->__to_utf16(substr($dat,$pos+$sspos,$slen),$opt);
				if ($opt & 0x01) $fusoku /=2;
				while ($fusoku >0 ) {
					if ($this->__get2($dat,$pos + $length + 4) == Type_CONTINUE) {
						$pos += $length +4;
						$length = $this->__get2($dat,$pos + 2);
						$opt = $this->__get1($dat,$pos + 4);
						$limit = $pos + $length + 4;
						$sspos = 5;
						if ($opt == 1) $fusoku *= 2;
						if ($pos + $sspos + $fusoku > $limit) {
							$fusoku = ($pos + $sspos+ $fusoku) - $limit;
							$sststr.=$this->__to_utf16(substr($dat,$pos + $sspos,$limit-($pos + $sspos)),$opt);
							if ($opt & 0x01) $fusoku /=2;
						} else {
							$sststr.=$this->__to_utf16(substr($dat,$pos + $sspos,$fusoku),$opt);
							$sspos += $fusoku;
							$fusoku=0;
						}
					} else break 2;
				}
			} else {
				$sststr=$this->__to_utf16(substr($dat,$pos+$sspos,$slen),$opt);
				$sspos += $slen;
			}
			if ($rtnum) {
				if ($pos+$sspos+4*$rtnum > $limit) {
					$fusoku=($pos+$sspos+4*$rtnum)-$limit;
					$rt=substr($dat,$pos+$sspos,4*$rtnum - $fusoku);
					if ($this->__get2($dat,$pos + $length + 4) == Type_CONTINUE) {
						$pos += $length + 4;
						$length =$this->__get2($dat,$pos + 2);
						$limit = $pos + $length + 4;
						$sspos = 4;
						$rt.=substr($dat,$limit + $sspos, $fusoku);
						$sspos += $fusoku;
					} else break;
				} else {
					$rt=substr($dat,$pos+$sspos,4*$rtnum);
					$sspos +=4*$rtnum;
				}
			} else $rt="";
			if ($apnum) {
				if ($pos+$sspos+$apnum > $limit) {
					$fusoku=$pos+$sspos+$apnum-$limit;
					$ap=substr($dat,$pos+$sspos,$apnum-$fusoku);
					if ($this->__get2($dat,$limit) == Type_CONTINUE) {
//						$pos = $limit;
						$pos += $length + 4;
						$length = $this->__get2($dat,$pos + 2);
//						$limit += $length + 4;
						$limit = $pos + $length + 4;
						$sspos = 4;
						$ap.=substr($dat,$pos + $sspos, $fusoku);
						$sspos += $fusoku;
					} else break;
				} else {
					$ap=substr($dat,$pos+$sspos,$apnum);
					$sspos +=$apnum;}
			} else $ap="";
//			$sspos +=$apnum;
			$tempsst['str']=bin2hex($sststr);
			$tempsst['rt']=bin2hex($rt);
			$tempsst['ap']=bin2hex($ap);
			$sstarray[$sstnum]=$tempsst;
			$sstnum++;
		}
//print_r($sstarray);
//exit;
		return $sstarray;
	}

	/**
	* convert charset to UTF16
	* @param $str:string,$opt:0=ascii,1=UTF-16
	* @return UTF16 string
	* @access private
	*/
	function __to_utf16(&$str,$opt=0)
	{
		return ($opt & 0x01) ? $str : mb_convert_encoding($str, "UTF-16LE", "ASCII");
	}

	/**
	* convert 1,2,4 bytes string to number
	* @param $d:string,$p:position
	* @return number
	* @access private
	*/
	function __get4(&$d, $p) {
		return ord($d[$p]) | (ord($d[$p+1]) << 8) |
			(ord($d[$p+2]) << 16) | (ord($d[$p+3]) << 24);
	}

	/**
	* @access private
	*/
	function __get2(&$d, $p) {
		return ord($d[$p]) | (ord($d[$p+1]) << 8);
	}

	/**
	* @access private
	*/
	function __get1(&$d, $p) {
		return ord($d[$p]);
	}

	/**
	* remake sst record
	* @access private
	*/
	function __makesst(&$sstarray,$totalref) {
		$numref = count($sstarray);
		if (!$numref) return;
		$sstbin='';
		$record = 0xfc;
		$rdat = pack("VV",$totalref,$numref);
		$nokori = 0x2020 - 8;
		foreach ($sstarray as $val) {
			$str=pack("H*",$val['str']);
			$strutf8 = mb_convert_encoding($str, 'utf-8',"UTF-16LE");
			if ($val['rt']) $rt=pack("H*",$val['rt']); else $rt='';
			if ($val['ap']) $ap=pack("H*",$val['ap']); else $ap='';
	
			if ($nokori < 10) {
				$sstbin .= pack("vv",$record,strlen($rdat)).$rdat;
				$record = 0x3c;
				$rdat = '';
				$nokori = 0x2020;
			}
			if (mb_detect_encoding($strutf8,"ASCII,ISO-8859-1")=="ASCII"){
				$opt =0;
				$str = $strutf8;
				$len = strlen($str);
				$lenb = $len;
			} else {
				$opt =1;
				$len = mb_strlen ($str,"UTF-16LE");
				$lenb = 2 * $len;
			}
			if ($ap){
				$opt |= 0x04;
				$apn = strlen($ap);
			} else $apn = 0;
			if ($rt){
				$opt |= 0x08;
				$rtn = strlen($rt) / 4;
			} else $rtn=0;
			$rdat.=pack("vC",$len,$opt);
			if ($rtn) $rdat.=pack("v",$rtn);
			if ($apn) $rdat.=pack("V",$apn);
			$nokori = 0x2020 - strlen($rdat);
			while ($nokori < $lenb) {
				$nokori &= 0xfffe;
				$rdat .= substr($str,0,$nokori);
				$str = substr($str,$nokori);
				$sstbin .= pack("vv",$record,strlen($rdat)).$rdat;
				$lenb -= $nokori;
				$record =0x3c;
				$opt &=1;
				$rdat = pack("C",$opt);
				$nokori = 0x201f;
			}
			$rdat .= $str;
			$nokori = 0x2020 - strlen($rdat);
			while ($nokori < $rtn) {
				$rdat .= substr($rt,0,$nokori);
				$rt = substr($rt,$nokori);
				$sstbin .= pack("vv",$record,strlen($rdat)).$rdat;
				$rtn -= $nokori;
				$record =0x3c;
				$nokori = 0x2020;
				$rdat = '';
			}
			$rdat .= $rt;
			$nokori = 0x2020 - strlen($rdat);
			while ($nokori < $apn) {
				$rdat .= substr($ap,0,$nokori);
				$ap = substr($ap,$nokori);
				$sstbin .= pack("vv",$record,strlen($rdat)).$rdat;
				$apn -= $nokori;
				$record =0x3c;
				$nokori = 0x2020;
				$rdat = '';
			}
			$rdat .= $ap;
			$nokori = 0x2020 - strlen($rdat);
		}
		if ($rdat) $sstbin .= pack("vv",$record,strlen($rdat)).$rdat;
		return $sstbin;
	}

	/**
	* Parse Excel file
	* @param  $filename:full path for OLE file
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function parseFile($filename,$mode=null){
		if ($mode == 1) $this->opt_parsemode = 1;
		$dat = $this->__oleread($filename);
		if ($this->isError($dat)) return $dat;
		if (strlen($dat) < 256) {
			return $this->raiseError("Contents is too small (".strlen($dat).")\nProbably template file is not right Excel file.\n");
		}
		$presheet=1;
		$pos = 0;
		$version = $this->__get2($dat,$pos + 4);
		$substreamType = $this->__get2($dat,$pos + 6);
		if ($version != Code_BIFF8) {
			return $this->raiseError("Contents is not BIFF8 format.\n");
		}
		if ($substreamType != Code_WorkbookGlobals) {
			return $this->raiseError("Contents is unknown format.\nCan't find WorkbookGlobal.");
		}
		$code=-1;
		$poslimit=strlen($dat);
		while ($code != Type_EOF){
			if ($pos > $poslimit){
				return $this->raiseError("Global Area Read Error\nTemplate file is broken");
			}
		    $code = $this->__get2($dat,$pos);
		    $length = $this->__get2($dat,$pos+2);
		    switch ($code) {
			case Type_FILEPASS:
				return $this->raiseError("Cannot read contents. \nThis file is protected.");
				break;
			case Type_SST:
				$this->globaldat['presst']=$this->wbdat;
				$this->wbdat='';
				$this->eachsst = $this->__parsesst($dat, $pos, $length);
				while ($this->__get2($dat,$pos + $length + 4) == Type_CONTINUE){
					$pos += $length + 4;
					$length = $this->__get2($dat,$pos+2);
				}
			    break;
			case Type_EXTSST:
//				$this->globaldat['exsstbin'] = '';	// FIXME
				break;
			case Type_OBJPROJ:
			case Type_BUTTONPROPERTYSET:
				break;
			case Type_BOUNDSHEET:
				if ($presheet) {
					$this->globaldat['presheet']=$this->wbdat;
					$this->wbdat='';
					$presheet=0;
				}
				$rec_offset = $this->__get4($dat, $pos+4);
			    $sheetno['code'] = substr($dat, $pos, 2);
			    $sheetno['length'] = substr($dat, $pos+2, 2);
			    $sheetno['offsetbin'] = substr($dat, $pos+4, 4);
			    $sheetno['offset'] = $rec_offset;
			    $sheetno['visible'] = substr($dat, $pos+8, 1);
			    $sheetno['type'] = substr($dat, $pos+9, 1);
			    $sheetno['name'] = substr($dat, $pos+10, $length-6);
			    $this->boundsheets[] = $sheetno;
			    break;
			case Type_COUNTRY:
					$this->wbdat .= substr($dat, $pos, $length+4);
					$this->globaldat['presup']=$this->wbdat;
					$this->wbdat='';
			    break;
			case Type_XCT:
			case Type_CRN:
				break;
			case Type_SUPBOOK:
// tentative countermeasures for unknown SUPBOOK-record(External References) on 2007.1.29
//
				if (substr($dat, $pos+6,2)=="\x01\x04"){
					$this->globaldat['presup'].=$this->wbdat;
					$this->globaldat['supbook']=substr($dat, $pos, $length+4);
				}
				$this->wbdat='';
				break;
			case Type_EXTERNSHEET:
				if (strlen($this->globaldat['presup'])==0) {
					$this->globaldat['presup'].=$this->wbdat;
					$this->wbdat='';
				}
				$this->globaldat['extsheet']=substr($dat, $pos, $length+4);
				$this->globaldat['name']='';
				while($this->__get2($dat, $pos+$length+4)==Type_NAME){
					$pos +=$length+4;
					$length = $this->__get2($dat,$pos+2);
					if ($this->__get2($dat,$pos+4)!=0x20 || $this->__get1($dat,$pos+11)!=0 || $this->__get1($dat,$pos+12)==0){
//						$this->globaldat['name'].=substr($dat, $pos, $length+4);
					} else {
						$this->globaldat['namerecord'].=substr($dat, $pos, $length+4);
						$lenform=$this->__get2($dat,$pos+8);
						$namtype=$this->__get1($dat,$pos+19);
						$tmp['flags2notu']=substr($dat, $pos, 12);
						$tmp['sheetindex']=$this->__get2($dat,$pos+12);
						$tmp['menu2name']=substr($dat, $pos+14, 6);
						$tmp['formula']=$this->analizeform(substr($dat,$pos+20,$lenform));
						$tmp['remain']=substr($dat,$pos+20+$lenform,$length-(16+$lenform));
						$this->boundsheets[$this->__get2($dat,$pos+12)-1]['namerecord'][$namtype]=$tmp;
					}
				}
			    break;
			case Type_WRITEACCESS:
/*
				$wa = "5c007000120000687474703a2f2f6368617a756b652e636f6d";
				$wa.= "20202020202020202020202020202020202020202020202020";
				$wa.= "20202020202020202020202020202020202020202020202020";
				$wa.= "20202020202020202020202020202020202020202020202020";
				$wa.= "20202020202020202020202020202020";
				$this->wbdat .= pack("H*",$wa);
*/
				$ERVer= mb_convert_encoding(Reviser_Version,'UTF-16LE','auto');
				$wa = "\x5c\x00\x70\x00" . pack("C", 34 + mb_strlen($ERVer,'UTF-16LE'));
				$wa.= pack("H*","000145007800630065006c005f0052006500760069007300650072002000");
				$wa.= $ERVer;
				$wa.= pack("H*","2000200068007400740070003a002f002f006300680061007a0075006b0065002e0063006f006d00");
				$wa = str_pad($wa, 0x74);
				$this->wbdat .=$wa;
			    break;
			case Type_EOF:
				$this->globaldat['last']= $this->wbdat . substr($dat, $pos, $length+4);
			    break;

			case Type_FONT:
			case Type_FORMAT:
			case Type_XF:
				$this->wbdat .= substr($dat, $pos, $length+4);
				if ($this->opt_parsemode) $this->saveAttrib($code,substr($dat, $pos, $length+4));
				break;
			default:
				$this->wbdat .= substr($dat, $pos, $length+4);
			}
			$pos += $length + 4;
		}
		foreach ($this->boundsheets as $key=>$val){
		    $res = $this->__parsesheet($dat,$key,$val['offset']);
			if ($this->isError($res)) return $res;
		}
	}

	/**
	* Remake Excel file
	* @param  $filename:file-name for web output
	* @return stdout for web-output
	* @access private
	*/
	function makeFile($filename,$path=null){
		$this->_makesupblock();
		$totalref = count($this->eachsst);	// FIXME
		$sstbin=$this->__makesst($this->eachsst,$totalref);
		$tmplen=strlen($this->globaldat['presheet']);
		$tmplen += strlen($this->globaldat['presst']);
		$tmplen += strlen($this->globaldat['last']);
		$tmplen += strlen($this->globaldat['presup']);
		$tmplen += strlen($this->globaldat['supbook']);
		$tmplen += strlen($this->globaldat['extsheet']);
		$tmplen += strlen($this->globaldat['name']);
		$tmplen += strlen($this->globaldat['namerecord']);
		$tmplen += strlen($sstbin.$this->globaldat['exsstbin']);
//		$refnum1=$refnum;
		foreach ($this->boundsheets as $key=>$val){
			$tmplen += strlen($val['code']);
			$tmplen += strlen($val['length']);
			$tmplen += strlen($val['offsetbin']);
			$tmplen += strlen($val['visible']);
			$tmplen += strlen($val['type']);
			$tmplen += strlen($val['name']);
//			$sheetdat[$key]=$this->__makeSheet($key,$refnum1++);
			$sheetdat[$key]=$this->__makeSheet($key,$key);
		}
	
		foreach ((array)$sheetdat as $key=>$val){
			$this->boundsheets[$key]['offsetbin']=pack("V",$tmplen);
			$tmplen += strlen($val);
		}
	// make global-block
		$tmp=$this->globaldat['presheet'];
		foreach ($this->boundsheets as $key=>$val){
			$tmp .= $val['code'];
			$tmp .= $val['length'];
			$tmp .= $val['offsetbin'];
			$tmp .= $val['visible'];
			$tmp .= $val['type'];
			$tmp .= $val['name'];
		}
		$tmp .= $this->globaldat['presup'].$this->globaldat['supbook'];
		$tmp .= $this->globaldat['extsheet'];
		$tmp .= $this->globaldat['name'];
		$tmp .= $this->globaldat['namerecord'];
		$tmp .= $this->globaldat['presst'] . $sstbin . $this->globaldat['exsstbin'];
		$tmp .= $this->globaldat['last'];
		foreach ((array)$sheetdat as $val){
			$tmp .= $val;
		}
	
	// from here making Excel-file
		if (($path === null) || (trim($path)=="")) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
			print $this->makeole2($tmp);
		} else {
			if (substr($path,-1) == '/') $path = substr($path,0,-1);
			if (!file_exists($path)) return $this->raiseError("The path $path does not exist.");
			$filename = $path . '/' . $filename;
			$_FILEH_ = @fopen($filename, "wb");
			if ($_FILEH_ == false) {
				return $this->raiseError("Can't open $filename. It may be in use or protected.");
			}
			fwrite($_FILEH_, $this->makeole2($tmp));
			@fclose($_FILEH_);
		}
	}

	/**
	* Remake Cell block
	* @access private
	*/
	function reviseCell(){
		$shname=array();
		$tmpsn=count($this->boundsheets);
		for ($i=0;$i<$tmpsn;$i++){
			$shname[$this->boundsheets[$i]['name']] = 0;
		}
		for ($i=0;$i<$tmpsn;$i++){
			$this->stable[$i]=$i;
			$shname[$this->boundsheets[$i]['name']]++;
		}

		foreach($this->dupsheet as $val){
			if (isset($this->boundsheets[$val['orgsn']])){
				for($i=0;$i<$val['count'];$i++){
					$this->stable[$tmpsn+$i]=$val['orgsn'];
				  if (isset($this->mergecells[$val['orgsn']]))
					$this->mergecells[$tmpsn+$i]=$this->mergecells[$val['orgsn']];
				  if (isset($this->rowblock[$val['orgsn']]))
					$this->rowblock[$tmpsn+$i]=$this->rowblock[$val['orgsn']];
				  if (isset($this->colblock[$val['orgsn']]))
					$this->colblock[$tmpsn+$i]=$this->colblock[$val['orgsn']];
				  if (isset($this->cellblock[$val['orgsn']]))
					$this->cellblock[$tmpsn+$i]=$this->cellblock[$val['orgsn']];
					$this->boundsheets[$tmpsn+$i]=$this->boundsheets[$val['orgsn']];
					if (isset($this->revise_dat['sheetname'][$tmpsn+$i])){
						$this->boundsheets[$tmpsn+$i]['name'] = $this->revise_dat['sheetname'][$tmpsn+$i];
						$this->boundsheets[$tmpsn+$i]['length'] = pack("v",6 + strlen($this->revise_dat['sheetname'][$tmpsn+$i]));
						if (isset($shname[$this->boundsheets[$tmpsn+$i]['name']])){
							$shname[$this->boundsheets[$tmpsn+$i]['name']] +=1;
						} else  $shname[$this->boundsheets[$tmpsn+$i]['name']] =1;
					} else {
	//	if the names are same, add different-number
						if ($shname[$this->boundsheets[$tmpsn+$i]['name']] > 0){
							$shname[$this->boundsheets[$tmpsn+$i]['name']]++;
							$dupstr = '('.($shname[$this->boundsheets[$tmpsn+$i]['name']] -1).')';
							$strcnt=$this->__get1($this->boundsheets[$tmpsn+$i]['name'],0) + strlen($dupstr);
							if ($this->__get1($this->boundsheets[$tmpsn+$i]['name'],1) == 0) {
								$this->boundsheets[$tmpsn+$i]['name'] .= $dupstr;
							} else {
								$this->boundsheets[$tmpsn+$i]['name'] .= mb_convert_encoding($dupstr, "UTF-16LE", "ASCII");
							}
							$this->boundsheets[$tmpsn+$i]['name']=pack("C",$strcnt).substr($this->boundsheets[$tmpsn+$i]['name'],1);
							$this->boundsheets[$tmpsn+$i]['length'] = pack("v",6 + strlen($this->boundsheets[$tmpsn+$i]['name']));
						}
					}
				}
				$tmpsn += $val['count'];
			}
		}
		foreach($this->boundsheets as $key=>$val){
			if (isset($this->revise_dat['sheetname'][$key]))
			if (strlen($this->revise_dat['sheetname'][$key])){
			    $this->boundsheets[$key]['name'] = $this->revise_dat['sheetname'][$key];
			    $this->boundsheets[$key]['length'] = pack("v",6 + strlen($this->revise_dat['sheetname'][$key]));
			}
		}
// end of sheet dup
		if(isset($this->revise_dat['replace']))
		foreach((array)$this->revise_dat['replace'] as $val) {
			$search=bin2hex($val['org']);
			$replace=bin2hex($val['new']);
			foreach((array)$this->eachsst as $key => $dmy) {
				$this->eachsst[$key]['str']=str_replace($search, $replace, $this->eachsst[$key]['str']);
			}
		}

// Start of Experimental code on 2007/10/13
		if (isset($this->revise_dat['option']))
		foreach((array)$this->revise_dat['option'] as $key => $val) {
			if (($this->__get2($val['record'],2)+4) != strlen($val['record'])) continue;
			if ($val['refsheet'] === null) $val['refsheet'] = $val['sheet'];
			if (($val['refrow'] !== null) && ($val['refcol'] !== null)) {
				$xf= (isset($this->cellblock[$val['refsheet']][$val['refrow']][$val['refcol']]['xf'])) ? $this->cellblock[$val['refsheet']][$val['refrow']][$val['refcol']]['xf'] : $this->_getcolxf($val['refsheet'],$val['refcol']);
			} else {
				$xf= (isset($this->cellblock[$val['sheet']][$val['row']][$val['col']]['xf'])) ? $this->cellblock[$val['sheet']][$val['row']][$val['col']]['xf'] : $this->_getcolxf($val['sheet'],$val['col']);
			}
			$data = substr($val['record'],0,8).pack('v', $xf).substr($val['record'],10);
			$this->cellblock[$val['sheet']][$val['row']][$val['col']]['record']=bin2hex($data);
		}
// End of Experimental code

		if (isset($this->revise_dat['add_str']))
		foreach((array)$this->revise_dat['add_str'] as $key => $val) {
			$this->_addString($val['sheet'],$val['row'], $val['col'], $val['str'], $val['refrow'], $val['refcol'], $val['refsheet']);
		}
		if (isset($this->revise_dat['add_num']))
		foreach((array)$this->revise_dat['add_num'] as $key => $val) {
			$this->_addNumber($val['sheet'],$val['row'], $val['col'], $val['num'], $val['refrow'], $val['refcol'], $val['refsheet']);
		}
		if (isset($this->revise_dat['add_blank']))
		foreach((array)$this->revise_dat['add_blank'] as $key => $val) {
			$this->_addBlank($val['sheet'],$val['row'], $val['col'], $val['refrow'], $val['refcol'], $val['refsheet']);
		}
		if (count((array)$this->colwidth)>0)
		foreach((array)$this->colwidth as $key => $val) {
			foreach($val as $key1 => $val1) {
				if (isset($this->colblock[$key][$key1])){
					if ($val1 == 0) {
						$this->colblock[$key][$key1]['opt'] |=0x01;
					} else {
						$this->colblock[$key][$key1]['width']=$val1;
					}
					$this->colblock[$key][$key1]['all']='';
				} else {
					$work['head']=pack("H*","7d000c00");
					$work['colst']=$key1;
					$work['colen']=$key1;
					$work['xf']=$this->_getcolxf($key,$key1);
					$work['unk']=0x02;
					$work['all']='';
					if ($val1 > 0) {
						$work['width']=$val1+ 0x0a0;
						$work['opt']=0x02;
					} else {
						$work['width']=0x0900;
						$work['opt']=0x03;
					}
					$this->colblock[$key][$key1]=$work;
				}
			}
		}

		if (count((array)$this->rowheight)>0)
		foreach((array)$this->rowheight as $key => $val) {
			foreach($val as $key1 => $val1) {
				if (isset($this->rowblock[$key][$key1])){
					if ($val1 == 0) {
						$this->rowblock[$key][$key1]['opt0'] |=0x20;
					} else {
						$this->rowblock[$key][$key1]['height']=$val1;
						$this->rowblock[$key][$key1]['opt0'] |=0x40;
					}
				} else {
					$this->rowblock[$key][$key1]['rowhead']="08021000";
					$this->rowblock[$key][$key1]['col1st']=0;
					$this->rowblock[$key][$key1]['collast']=count($this->cellblock[$key][$key1]);
					$this->rowblock[$key][$key1]['height']=$val1;
					$this->rowblock[$key][$key1]['notused0']=0;
					$this->rowblock[$key][$key1]['notused1']=0;
					$this->rowblock[$key][$key1]['opt0']=0x40;
					$this->rowblock[$key][$key1]['opt1']=0x0f;
				}
			}
		}
		krsort($this->rmsheets);
		$refnum=0;
		foreach ($this->rmsheets as $key => $val) {
			if ((count($this->boundsheets) > 1) && $val){
				unset($this->boundsheets[$key]);
			}
		}
		$this->_setPrintInfo();
	}

	/**
	* make OLE container & output to STDOUT
	* @param $tmpbin:binary data
	* @return web header and data
	* @access private
	*/
    function makeole2(& $tmpbin){
		$naiyou['bin']=$tmpbin;
		$naiyou['name']='Workbook';
		$streams[]=$naiyou;
	if (isset($this->orgStreams["\x05SummaryInformation"])){
		$naiyou['bin']=$this->orgStreams["\x05SummaryInformation"]['dat'];
		$naiyou['name']="\x05SummaryInformation";
		$streams[]=$naiyou;
	}
	if (isset($this->orgStreams["\x05DocumentSummaryInformation"])){
	        $naiyou['bin']=$this->orgStreams["\x05DocumentSummaryInformation"]['dat'];
	        $naiyou['name']="\x05DocumentSummaryInformation";
	        $streams[]=$naiyou;
	}
	$AlTbls=0;
	$blocks=array();
	$MSATSID=array();
	$nextSec=0;
	$rootentry=str_pad($this->asc2utf('Root Entry'), 64, "\x00")	//0- 64
		. pack("v",2*(1+strlen('Root Entry')))		//64- 2
		. "\x05"		//66- 1
		. "\x01"		//67- 1
		. pack("V",-1)	//68- 4
		. pack("V",-1)	//72- 4
		. ((count($streams)==3)? pack("V",2):pack("V",1))	//76- 4
		. str_repeat("\x00", 16)	//80- 16
		. pack("V",0)	//96- 4
		. pack("d",0)	//100- 8
		. pack("d",0)	//108- 8
		. pack("V",0)	//116- 4
		. pack("V",0)	//120- 4
		. pack("V",0);	//124- 4
	foreach($streams as $key=>$dat){
		$orglen=strlen($dat['bin']);
		if ($orglen < 0x1000) {
			$streams[$key]['bin']=str_pad($dat['bin'], 0x1000, "\x00");
			$orglen = 0x1000;
		} else {
			if ($orglen % 512 != 0)
			$streams[$key]['bin'] .= str_repeat("\x00", 512 - ($orglen % 512));
		}
		$needSecs = strlen($streams[$key]['bin'])/512;
		$AlTbls += $needSecs;
	// 1st each binary-dat
		for($i=0;$i<$needSecs-1;$i++){
			$blocks[$nextSec+$i]=$nextSec+$i+1;
		}
		$blocks[$nextSec+$i]=-2;
		$userstream=str_pad($this->asc2utf($dat['name']), 64, "\x00")	//0- 64
			. pack("v",2*(1+strlen($dat['name'])))		//64- 2
			. "\x02"		//66- 1
			. "\x01";		//67- 1
		if (count($streams)==3 && $key==1) {
			$userstream.= pack("VVV", 1, 3,-1);
		} else {
			$userstream.= pack("VVV",-1,-1,-1);	//68-76- 4x3
		}
			$userstream.= str_repeat("\x00", 16)	//80- 16
			. pack("V",0)	//96- 4
			. pack("d",0)	//100- 8
			. pack("d",0)	//108- 8
			. pack("V",$nextSec)	//116- 4
			. pack("V",$orglen)	//120- 4
			. pack("V",0);	//124- 4
		$nextSec=$nextSec+$i+1;
		$rootentry.=$userstream;
	}
	$rootentry.=str_repeat("\x00", 512 - (strlen($rootentry) % 512));
	//2ns RootEntry directory
	$rootSec=$nextSec;
	$DirSecs= strlen($rootentry) / 512;
	for($i=0;$i<$DirSecs-1;$i++){
		$blocks[$nextSec+$i]=$nextSec+$i+1;
	}
	$blocks[$nextSec+$i]=-2;
	$nextSec=$nextSec+$i+1;
	//3rd allocation table
	$alcsecs=floor((count($blocks)+127)/127);
	for($i=0;$i<$alcsecs;$i++){
		$blocks[$nextSec+$i]=-3;
		$MSATSID[]=$nextSec+$i;
	}
	$nextSec=$nextSec+$i+1;
	$blocks[$nextSec+$i]=-2;
	$totalAlTblnum=$alcsecs;
	$head=pack("H*","D0CF11E0A1B11AE1")
			. str_repeat("\x00", 16)
			. pack("v", 0x3b)
			. pack("v", 0x03)
			. pack("v", -2)
			. pack("v", 9)
			. pack("v", 6)
			. str_repeat("\x00", 10)
			. pack("V", $totalAlTblnum)
			. pack("V", $rootSec)
			. pack("V", 0)
			. pack("V", 0x1000)
			. pack("V", 0)  //Short Block Depot
			. pack("V", 1)
			. pack("V", -2)	//$masterAlTbl
			. pack("V", 0);	//$masterAlnum)
	// make OLE container
	$oledat =$head;
	for($i=0;$i<109;$i++){
		if(isset($MSATSID[$i])){
			$oledat.=pack("V",$MSATSID[$i]);
		} else {
			$oledat.=pack("V",-1);
		}
	}
	foreach($streams as $dat){
		$oledat.=$dat['bin'];
	}
	$oledat.=$rootentry;
	for($i=0;$i<$alcsecs*128;$i++){
		if(isset($blocks[$i])){
			$oledat.=pack("V",$blocks[$i]);
		} else {
			$oledat.=pack("V",-1);
		}
	}
	return $oledat;
    }

	/**
	* convert charset ASCII to UTF16
	* @param $ascii string
	* @return UTF16 string
	* @access private
	*/
	function asc2utf($ascii){
		$utfname='';
		for ($i = 0; $i < strlen($ascii); $i++) {
			$utfname.=$ascii{$i}."\x00";
		}
		return $utfname;
	}

	/**
	* get Cell Attribute
	* @access private
	*/
	function saveAttrib($code,$dat){
		switch ($code) {
			case Type_FONT:
				$this->recFONT[]=$dat;
				break;
			case Type_FORMAT:
				$fmt=$this->cnvstring(substr($dat,6),2);
				$this->recFORMAT[$this->__get2($dat,4)]=$fmt;
				break;
			case Type_XF:
				$this->recXF[]=$dat;
				break;
		}
	}

	/**
	* convert string from UTF to internal-charset
	* @access private
	*/
	function cnvstring(&$chars,$len){
		if ($len==1) {
			$strpos=2;
			$opt=$this->__get1($chars,1);
		} elseif ($len==2){
			$strpos=3;
			$opt=$this->__get1($chars,2);
		} else return substr($chars,2);
		if ($opt)
			return mb_convert_encoding(substr($chars,$strpos),$this->charset,'UTF-16LE');
		else
			return substr($chars,$strpos);
	}

	/**
	* Get Cell Value
	* @param integer $sn sheet number
	* @param integer $row Row position
	* @param integer $col Column position  0indexed
	* @return mixed cell value
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function getCellVal($sn,$row,$col){
		if (isset($this->cellblock[$sn][$row][$col])) {
			$cell=$this->cellblock[$sn][$row][$col];
			$tmp['type'] = $cell['type'];
			switch ($cell['type']) {
				case Type_LABEL:
					$desc=$this->cnvstring(pack("H*",$cell['dat']),2);
					break;
				case Type_LABELSST:
					$strnum=$this->__get2(pack("H*",$cell['dat']),0);
					$sstr=$this->eachsst[$strnum]['str'];
					$desc=mb_convert_encoding(pack("H*",$sstr),$this->charset,'UTF-16LE');
					break;
				case Type_RK:
				case Type_RK2:
					$rknum = $this->__get4(pack("H*",$cell['dat']),0);
					if (($rknum & 0x02) != 0) {
						$value = $rknum >> 2;
					} else {
						$sign = ($rknum & 0x80000000) >> 31;
						$exp = ($rknum & 0x7ff00000) >> 20;
						$mantissa = (0x100000 | ($rknum & 0x000ffffc));
						$value = $mantissa / pow( 2 , (20- ($exp - 1023)));
						if ($sign) {$value = -1 * $value;}
					}
					if (($rknum & 0x01) != 0) $value /= 100;

					$desc=$value;
					break;
				case Type_NUMBER:
					$temp=(pack("N",1)==pack("L",1)) ? strrev(pack("H*",$cell['dat'])) : pack("H*",$cell['dat']);
					$strnum=unpack("d",$temp);
					$desc=$strnum[1];
					break;
				case Type_FORMULA:
				case Type_FORMULA2:
					$result=substr(pack("H*",$cell['dat']),0,8);
					if (substr($result,6,2)=="\xFF\xFF"){
						switch (substr($result,0,1)) {
						case "\x00":
							$desc=$this->cnvstring(substr($cell['string'],4),2);
							break;
						case "\x01":
							$desc=(substr($result,2,1)=="\x01")? "TRUE":"FALSE";
							break;
						case "\x02": $desc='#ERROR!';
							break;
						case "\x03": $desc='';
							break;
						}
					} else {
						$t0=(pack("N",1)==pack("L",1)) ? strrev($result) : $result ;
						$desc0=unpack("d",$t0);
						$desc=$desc0[1];
					}
					break;
				case Type_BOOLERR:
					$result=pack("H*",$cell['dat']);
					if ($this->__get1($result,1) !=0) {
						$desc='#ERROR!';
					} elseif ($this->__get1($result,0) !=0) {
						$desc = "TRUE";
					} else {
						$desc = "FALSE";
					}
					break;
				case Type_BLANK:
					$desc='';
					break;
				default:
					$tmp['type'] = -1;
					$desc='';
			}
			$tmp['val'] = $desc;
		} else {
			$tmp['type'] = 0;
			$tmp['val'] = '';
		}
		return $tmp;
	}

	/**
	* Get Cell Attribute
	* @param integer $sn sheet number
	* @param integer $row Row position
	* @param integer $col Column position
	* @return mixed cell value
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function getCellAttrib($sn,$row,$col){
		if ($this->opt_parsemode !=1) return -1;
		$xfno=$this->cellblock[$sn][$row][$col]['xf'];
		if ($xfno !== null) {
			$dat=$this->recXF[$xfno];
			$xf['attrib']=($this->__get1($dat,13) & 0xfc) >> 2;
			$xf['stylexf']=($this->__get1($dat,8) & 0x4) >> 2;
			$oya=($this->__get2($dat,8) & 0xfff0) >> 4;
			if ($oya != 0xfff) $xf['parent']=$oya;
			$cond = $xf['stylexf'] ? ~$xf['attrib'] : $xf['attrib'];
			if ($cond & 0x2)
				$xf['fontindex']=$this->__get2($dat,4)-1;
				else $xf['fontindex']=0;
			if ($cond & 0x1)
				$xf['formindex']=$this->__get2($dat,6);
				else $xf['formindex']=0;
//			if ($cond & 0x4){
				$xf['halign']=$this->__get1($dat,10) & 0x7;
				$xf['wrap']=($this->__get1($dat,10) & 0x8) >> 3;
				$xf['valign']=($this->__get1($dat,10) & 0x70)>> 4;
				$xf['rotation']=$this->__get1($dat,11);
//			}
//			if ($cond & 0x8){
				$xf['Lstyle']=$this->__get1($dat,14) & 0x0f;
				$xf['Rstyle']=($this->__get1($dat,14) & 0xf0) >> 4;
				$xf['Tstyle']=$this->__get1($dat,15) & 0x0f;
				$xf['Bstyle']=($this->__get1($dat,15) & 0xf0) >> 4;
				$xf['Lcolor']=$this->__get1($dat,16) & 0x7f;
				$xf['Rcolor']=($this->__get2($dat,16) & 0x3f80) >> 7;
				$xf['diagonalL2R']=($this->__get1($dat,17) & 0x40) >> 6;
				$xf['diagonalR2L']=($this->__get1($dat,17) & 0x80) >> 7;
				$xf['Tcolor']=$this->__get1($dat,18) & 0x7f;
				$xf['Bcolor']=($this->__get2($dat,18) & 0x3f80) >> 7;
				$xf['Dcolor']=($this->__get4($dat,18) & 0x1fc000) >> 14;
				$xf['Dstyle']=($this->__get2($dat,20) & 0x1e0) >> 5;
//			}
//			if ($cond & 0x10){
				$xf['fillpattern']=($this->__get1($dat,21) & 0xfc) >> 2;
				$xf['PtnFRcolor']=$this->__get1($dat,22) & 0x7f;
				$xf['PtnBGcolor']=($this->__get2($dat,22)>> 7) & 0x7f;
//			}
			$tmp['xf']=$xf;
			if ($xf['formindex']==0) $tmp['format']='';
			else $tmp['format']=$this->recFORMAT[$xf['formindex']];

			$dat=$this->recFONT[$xf['fontindex']];
			$font['height']=$this->__get2($dat,4);
			$font['style']=$this->__get2($dat,6);
			$font['color']= $this->__get2($dat,8);
			$font['weight']=$this->__get2($dat,10);
			$font['escapement']=$this->__get2($dat,12);
			$font['underline']=$this->__get1($dat,14);
			$font['family']=$this->__get1($dat,15);
			$font['charset']=$this->__get1($dat,16);
			$font['fontname']=$this->cnvstring(substr($dat,18),1);
			$tmp['font']=$font;

			return $tmp;
		} else return null;
	}


	/**
	* Get sheet-name
	* @param integer $sn sheet number
	* @return string sheetname
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function getSheetName($sn){
		return $this->cnvstring($this->boundsheets[$sn]['name'],1);
	}


	/**
	* Get Header
	* @param integer $sn sheet number
	* @return string header
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function getHeader($sn){
		return $this->cnvstring(substr($this->sheetbin[$sn]['header'], 4),2);
	}


	/**
	* Get Footer
	* @param integer $sn sheet number
	* @return string footer
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function getFooter($sn){
		return $this->cnvstring(substr($this->sheetbin[$sn]['footer'], 4),2);
	}


	/**
	* Get Row Height
	* @param integer $sn sheet number
	* @param integer $row Row position
	* @return integer row-height
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function getRowHeight($sn,$row){
		if (isset($this->rowblock[$sn][$row]['height'])){
			$ret=$this->rowblock[$sn][$row]['height'];
		} else {
			$ret=$this->defrowH[$sn];
		}
		return $ret;
	}

	/**
	* Get Column Width
	* @param integer $sn sheet number
	* @param integer $col Column position
	* @return integer column-width
	* @access public
	* @example ./sample_ex1.php sample_ex1
	*/
	function getColWidth($sn,$col){
		if (isset($this->colblock[$sn][$col]['width'])){
			$ret=$this->colblock[$sn][$col]['width'];
		} else {
			$ret=$this->defcolW[$sn] * 256 + 256;
		}
		return $ret;
	}


	/**
	* @access private
	*/
	function _setPrintInfo(){
		if (count($this->prntitle)>0)
		foreach($this->prntitle as $sheet => $val){
			unset($this->boundsheets[$sheet]['namerecord'][7]);
			$area='';
			$tmp['sheetindex']= $sheet+1;
			$tmp['menu2name']=pack("H*",'000000000007');
			$tmp['remain']='';
			if ($val['col1st']!==null)
				$area.="3bX0000ffff".bin2hex(pack("vv",$val['col1st'],$val['collast']));
			if ($val['row1st']!==null)
				$area.="3bX" . bin2hex(pack("vv",$val['row1st'],$val['rowlast'])) ."0000ff00";
			if ($val['col1st']!==null && $val['row1st']!==null){
				$tmp['formula']="291700".$area."10";
				$tmp['flags2notu']=pack("H*",'18002a00200000011a000000');
			} else {
				$tmp['formula']=$area;
				$tmp['flags2notu']=pack("H*",'18001b00200000010b000000');
			}
			$this->boundsheets[$sheet]['namerecord'][7]=$tmp;
		}

		if (count($this->prnarea)<1) return;
		foreach($this->prnarea as $sheet => $val){
			unset($this->boundsheets[$sheet]['namerecord'][6]);
			$area='';
			$tmp['flags2notu']=pack("H*",'18001b00200000010b000000');
			$tmp['sheetindex']= $sheet+1;
			$tmp['menu2name']=pack("H*",'000000000006');
			$tmp['remain']='';
			$area.="3bX".bin2hex(pack("vvvv",$val['row1st'],$val['rowlast'],$val['col1st'],$val['collast']));
			$tmp['formula']=$area;
			$this->boundsheets[$sheet]['namerecord'][6]=$tmp;
		}
	}

	/**
	* @access private
	*/
	function _makesupblock(){
		if (count($this->dupsheet)+count($this->rmsheets)+count($this->prntitle)+count($this->prnarea) >0){
			$curnum=count($this->boundsheets);
			$this->globaldat['supbook']=pack("vvvv",Type_SUPBOOK,4,$curnum,0x401);
			$exsheetdat='';
			for($i=0;$i<$curnum;$i++){
				$exsheetdat.=pack("vvv",0,$i,$i);
			}
			$this->globaldat['extsheet']=pack("vvv",0x17,strlen($exsheetdat)+2,$curnum).$exsheetdat;
			$nr='';
			foreach((array)$this->boundsheets as $sn =>$tmp){
				if (isset($tmp['namerecord'][6]))
				if (count($tmp['namerecord'][6])>0){
					$nr.=$tmp['namerecord'][6]['flags2notu'].pack("v",$sn+1).$tmp['namerecord'][6]['menu2name'];
					$nr.=pack("H*",str_replace('X',bin2hex(pack('v',$sn)),$tmp['namerecord'][6]['formula']));
					$nr.=$tmp['namerecord'][6]['remain'];
				}
				if (isset($tmp['namerecord'][7]))
				if (count($tmp['namerecord'][7])>0){
					$nr.=$tmp['namerecord'][7]['flags2notu'].pack("v",$sn+1).$tmp['namerecord'][7]['menu2name'];
					$nr.=pack("H*",str_replace('X',bin2hex(pack('v',$sn)),$tmp['namerecord'][7]['formula']));
					$nr.=$tmp['namerecord'][7]['remain'];
				}
			}
			$this->globaldat['namerecord']=$nr;
		}
		return;
	}


	/**
	* @access private
	*/
	function analizeform($form){
		$fpos=0;
		$flen=strlen($form);
		$ret='';
		while ($fpos < $flen){
			$token=$this->__get1($form,$fpos);
			if ($token > 0x3F) $token -=0x20;
			if ($token > 0x3F) $token -=0x20;
			switch ($token){
			case 0x3:
			case 0x4:
			case 0x5:
			case 0x6:
			case 0x7:
			case 0x8:
			case 0x9:
			case 0xA:
			case 0xB:
			case 0xC:
			case 0xD:
			case 0xE:
			case 0xF:
			case 0x10:
			case 0x11:
			case 0x12:
			case 0x13:
			case 0x14:
			case 0x15:
			case 0x16:
				$ret.=bin2hex(substr($form,$fpos,1));
				$fpos+=1;
				break;
	//		case 0x17:
	//		case 0x18:
	//		case 0x19:
	//			$fpos = $flen;
	//			break;
			case 0x1C:
			case 0x1D:
				$ret.=bin2hex(substr($form,$fpos,2));
				$fpos+=2;
				break;
			case 0x1E:
			case 0x29:
			case 0x2E:
			case 0x2F:
			case 0x3D:
				$ret.=bin2hex(substr($form,$fpos,3));
				$fpos+=3;
				break;
			case 0x21:
				$ret.=bin2hex(substr($form,$fpos,4));
				$fpos+=4;
				break;
			case 0x1:
			case 0x2:
			case 0x22:
			case 0x23:
			case 0x24:
			case 0x2A:
			case 0x2C:
				$ret.=bin2hex(substr($form,$fpos,5));
				$fpos+=5;
				break;
			case 0x39:
			case 0x3A:
			case 0x3C:
				$ret.=bin2hex(substr($form,$fpos,1));
				$ret.="X";
				$ret.=bin2hex(substr($form,$fpos+3,4));
				$fpos+=7;
				break;
			case 0x26:
			case 0x27:
			case 0x28:
				$ret.=bin2hex(substr($form,$fpos,7));
				$fpos+=7;
				break;
			case 0x1F:
			case 0x20:
			case 0x25:
			case 0x2B:
			case 0x2D:
				$ret.=bin2hex(substr($form,$fpos,9));
				$fpos+=9;
				break;
			case 0x3B:
			case 0x3D:
				$ret.=bin2hex(substr($form,$fpos,1));
				$ret.="X";
				$ret.=bin2hex(substr($form,$fpos+3,8));
				$fpos+=11;
				break;
			default:
				$ret=bin2hex($form);
				$fpos = $flen;
			}
		}
		return $ret;
	}

	/**
	* Add Image to Sheet
	* @param integer $sn sheet number
	* @param integer $row Row position
	* @param integer $col Column posion  0indexed
	* @param string  $image path to the image file
	* @param integer $x horizontal offset pixel(option)
	* @param integer $y vertical offset pixel(option)
    * @param integer $scale_x The horizontal scale
    * @param integer $scale_y The vertical scale
	* @access public
	*/
    function addImage($sn, $row, $col, $image, $x = 0, $y = 0, $scale_x = 1, $scale_y = 1){
		$val['sheet']=$sn;
		$val['row']=$row;
		$val['col']=$col;
		$val['image']=$image;
		$val['dx']=$x;
		$val['dy']=$y;
		$val['scaleX']=$scale_x;
		$val['scaleY']=$scale_y;
		$this->revise_dat['add_image'][$sn][]=$val;
	}

    /**
    * @access private
    */
    function _posImage($sn,$colstart, $rowstart, $x1, $y1, $width, $height) {
        $colend = $colstart;
        $rowend = $rowstart;
        if ($x1 >= $this->_sizeCol($sn,$colstart)) $x1 = 0;
        if ($y1 >= $this->_sizeRow($sn,$rowstart)) $y1 = 0;
        $width += $x1;
        $height += $y1;
        while ($width >= $this->_sizeCol($sn,$colend))
            $width -= $this->_sizeCol($sn,$colend++);
        while ($height >= $this->_sizeRow($sn,$rowend))
            $height -= $this->_sizeRow($sn,$rowend++);
        if ($this->_sizeCol($sn,$colstart) == 0) return;
        if ($this->_sizeCol($sn,$colend) == 0) return;
        if ($this->_sizeRow($sn,$rowstart) == 0) return;
        if ($this->_sizeRow($sn,$rowend) == 0) return;
        $x1 = $x1 / $this->_sizeCol($sn,$colstart) * 1024;
        $y1 = $y1 / $this->_sizeRow($sn,$rowstart) *  256;
        $x2 = $width / $this->_sizeCol($sn,$colend) * 1024;
        $y2 = $height / $this->_sizeRow($sn,$rowend) *  256;
        $data  = pack("vvVvvv", 0x5d, 0x3c, 0x01, 0x08, 0x01, 0x614);
        $data .= pack("vvvv", $colstart, $x1, $rowstart, $y1);
        $data .= pack("vvvv", $colend, $x2, $rowend, $y2);
        $data .= pack("vVv", 0, 0, 0);
        $data .= pack("CCCCCCCC", 9, 9, 0, 0, 8, 0xff, 1, 0);
        $data .= pack("vVvvvvV", 0, 9, 0, 0, 0, 1, 0);
        return($data);
    }

    /**
    * @access private
    */
    function _getcolxf($sn,$col) {
		if (isset($this->colblock[$sn][$col])){
			$cxf = $this->colblock[$sn][$col]['xf'];
		} else {
			$cxf = 0x0f;
		}
		return $cxf;
	}

    /**
    * @access private
    */
    function _sizeCol($sn,$col) {
		$c=$this->getColWidth($sn,$col);
		return ($c == 0) ? 0 : (floor(8 * $c / 256 + 0));
	}

    /**
    * @access private
    */
    function _sizeRow($sn,$row) {
		$r=$this->getRowHeight($sn,$row);
		return ($r == 0) ? 0 :(floor($r / 15));
	}

    /**
    * @access private
    */
    function _makeImageOBJ($sn) {
		$tmp="";
		if (isset($this->revise_dat['add_image'][$sn]))
		foreach($this->revise_dat['add_image'][$sn] as $val)
			$tmp.=$this->_addImage($val['sheet'],$val['row'],$val['col'], $val['image'],
			 $val['dx'], $val['dy'], $val['scaleX'], $val['scaleY']);
		return($tmp);
	}

    /**
    * @access private
    */
    function _addImage($sn, $row, $col, $imgname, $x1=0, $y1=0, $scale_x=1, $scale_y=1){
		$ermes=array();
		$ext=strtolower(substr($imgname,-4));
		$im='';
		switch($ext){
			case ".jpg":
			case "jpeg":
				if (function_exists('imagecreatefromjpeg'))
					$im = @imagecreatefromjpeg($imgname);
				break;
			case ".gif":
				if (function_exists('imagecreatefromgif'))
					$GIFim = @imagecreatefromgif($imgname);
					$im = imagecreatetruecolor(imagesx ($GIFim), imagesy ($GIFim));
					imagecopy($im, $GIFim, 0, 0, 0, 0, imagesx ($GIFim), imagesy ($GIFim));
					imagedestroy($GIFim);
				break;
			case ".png":
				if (function_exists('imagecreatefrompng'))
					$im = @imagecreatefrompng($imgname);
				break;
			case ".bmp";
		        // Open file.
		        if (!$bmh = @fopen($imgname,"rb")) {
					$ermes[]="Couldn't open $imgname";
					break;
				}
				if ($this->Flag_Magic_Quotes) set_magic_quotes_runtime(0);
		        $data = fread($bmh, filesize($imgname));
				fclose($bmh);
				if ($this->Flag_Magic_Quotes) set_magic_quotes_runtime($this->Flag_Magic_Quotes);
		        if (strlen($data) <= 0x36) {
					$ermes[]="$imgname is too small.";
					break;
				}
		        if (substr($data,0,2) != "BM") $ermes[]="$imgname isn't BMP image.";
				$planes = $this->__get2($data,26);
				$bits  = $this->__get2($data,28);
		        $compress = $this->__get4($data,30);
		        if ($planes != 1) $ermes[]="$imgname: only 1 plane supported.";
		        if ($compress != 0) $ermes[]="$imgname: compression not supported.";
		        $size   = $this->__get4($data,2) - 0x36 + 0x0C;
		        $width  = $this->__get4($data,18);
		        $height = $this->__get4($data,22);
				if ( count($ermes)==0 )
		        if ($bits == 24 ) {
			        $data = substr($data, 0x36);
					break;
				} else {
					$ermes[]="$imgname isn't a 24bit color.";
					$data='';
					break;
				}
			default:
				$ermes[]="$imgname is unknown image type.";
		}
		if ($im){
			$width = imagesx ($im);
			$height = imagesy ($im);
			$BPLine = $width * 3;
			$Stride = ($BPLine + 3) & ~3;
			$size = $Stride * $height + 0x0C;
			$data='';
			$numpad = $Stride - $BPLine;
			for ($y = $height - 1; $y >= 0; --$y) {
				for ($x = 0; $x < $width; ++$x) {
					$colr = imagecolorat ($im, $x, $y);
					$data .= substr(pack ('V', $colr), 0,3);
				}
				for ($i = 0; $i < $numpad; ++$i)
					$data .= pack ('C', 0);
			}
		}
		if ($width <1 || $height<1 || strlen($data) < 3) $ermes[]="$imgname: Too small size";
		if ($width > 0xFFFF) $ermes[]="$imgname: Too large width";
		if ($height > 0xFFFF) $ermes[]="$imgname: Too large height";
		if (isset($ermes))
		if (count($ermes) > 0) {
			if ($this->debug_image){
				print_r($ermes);
				exit;
			}
			return '';
		}
		$data  = pack("Vvvvv", 0x000c, $width, $height, 0x01, 0x18) . $data;
        $width  *= $scale_x;
        $height *= $scale_y;

        $recOBJ=$this->_posImage($sn, $col, $row, $x1, $y1, $width, $height);

		$recCONT="";
		if ($size >0x814){
			$st=0x814;
			$header = pack("vvvvV", 0x007f, 8 + 0x814, 0x09, 0x01, $size);
			$recIMDATA = $header.substr($data,0,0x814);
			while($st+0x814 < $size){
				$recCONT .= pack("vv",0x3c,0x814).substr($data,$st,0x814);
				$st+=0x814;
			}
			if ($st< $size)
				$recCONT .= pack("vv",0x3c,$size-$st).substr($data,$st);
		} else {
			$header = pack("vvvvV", 0x007f, 8 + $size, 0x09, 0x01, $size);
			$recIMDATA = $header.$data;
		}
		unset($im);
		return $recOBJ.$recIMDATA.$recCONT;
    }

	/**
	* Set(Get) Error-Handling Method.
	* @param integer $mode error handling method(default 0)
	* @return integer error handling method
	* @access public
	*/
	function setErrorHandling($mode=''){
		if (is_numeric($mode)) {
			$this->Flag_Error_Handling = $mode;
		}
		return $this->Flag_Error_Handling;
	}

	/**
	* Set(Get) Flag_inherit_Info
	* @param integer $mode 1:inherit property (default 0)
	* @return integer flag-value
	* @access public
	*/
	function setInheritInfomation($mode=''){
		if (is_numeric($mode)) {
			$this->Flag_inherit_Info = $mode;
		}
		return $this->Flag_inherit_Info;
	}

	/**
	* @param mixed $data object
	* @return boolean True:The error occurred
	* @access public
	*/
    function isError($data){return is_a($data, 'ErrMess');}

	/**
	* @access private
	*/
    function &raiseError($message = ''){
		if ($this->Flag_Error_Handling == 0){
			die($message);
		}
		return new ErrMess($message);
	}
}
/**
* error class
*/
class ErrMess {
    var $message = '';
	/**
	* @param string $message Error message
	* @access public
	*/
    function ErrMess($message){$this->message = $message;}
	/**
	* @return string Error message
	* @access public
	*/
    function getMessage() {return ($this->message);}
}

/**
* convert UNIXTIME to MS-EXCEL time
* @param integer $timevalue UNIXTIME
* @return integer MS-EXCEL time
* @access public
* @example ./sample.php sample
*/
function unixtime2ms($timevalue) {
	return (($timevalue /60 /60 +9) /24 + 25569);
}

/**
* convert MS-EXCEL time to UNIXTIME
* @param integer $timevalue MS-EXCEL time
* @return integer UNIXTIME
* @access public
*/
function ms2unixtime($timevalue,$offset1904 = 0){
	if ($timevalue > 1)
		$timevalue -= ($offset1904 ? 24107 : 25569);
	return getdate(round(($timevalue * 24 -9) * 60 * 60));
}
?>
