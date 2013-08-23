<?php
//-------------------------------------------------------------------------
// Multi-Byte FPDF                                            version: 1.0b
//-------------------------------------------------------------------------
// Usage: AddMBFont(FontName,Encoding);
//
// Example:
//    Chinese:  AddMBFont(BIG5  ,'BIG5');
//    Japanese: AddMBFont(GOTHIC,'SJIS');

require('fpdf.php');            // Original Class
require('font/mbttfdef.php');   // Multi-Byte TrueType Font Define

// FPDF Version Check
if ((float) FPDF_VERSION < 1.51) die("You need FPDF version 1.51");

// Encoding & CMap List (CMap information from Acrobat Reader Resource/CMap folder)
$MBCMAP['BIG5']   = array ('CMap'=>'ETenms-B5-H'   ,'Ordering'=>'CNS1'  ,'Supplement'=>0);
$MBCMAP['GB']     = array ('CMap'=>'GBKp-EUC-H'    ,'Ordering'=>'GB1'   ,'Supplement'=>2);
$MBCMAP['SJIS']   = array ('CMap'=>'90msp-RKSJ-H'  ,'Ordering'=>'Japan1','Supplement'=>2);
$MBCMAP['UNIJIS'] = array ('CMap'=>'UniJIS-UTF16-H','Ordering'=>'Japan1','Supplement'=>5);
$MBCMAP['EUC-JP'] = array ('CMap'=>'EUC-H'         ,'Ordering'=>'Japan1','Supplement'=>1);
// EUC-JP has *problem* of underline and not support half-pitch characters.

// if you want convert encoding to SJIS from EUC-JP, you must change $EUC2SJIS to true.
$EUC2SJIS = false;

// Short Font Name ------------------------------------------------------------
// For Acrobat Reader (Windows, MacOS, Linux, Solaris etc)
DEFINE("BIG5",    'MSungStd-Light-Acro');
DEFINE("GB",      'STSongStd-Light-Acro');
DEFINE("KOZMIN",  'KozMinPro-Regular-Acro');
// For Japanese Windows Only
DEFINE("GOTHIC",  'MS-Gothic');
DEFINE("PGOTHIC", 'MS-PGothic');
DEFINE("UIGOTHIC",'MS-UIGothic');
DEFINE("MINCHO",  'MS-Mincho');
DEFINE("PMINCHO", 'MS-PMincho');

class MBFPDF extends FPDF
{

// For Outline, Title, Sub-Title and ETC Multi-Byte Encoding
function _unicode($txt)
{
    if (function_exists('mb_detect_encoding')) {
        if (mb_detect_encoding($txt) != "ASCII") {
            $txt = chr(254).chr(255).mb_convert_encoding($txt,"UTF-16","auto");
        }
    }
    return $txt;
}

function AddCIDFont($family,$style,$name,$cw,$CMap,$registry,$ut,$up)
{
  $i=count($this->fonts)+1;
  $fontkey=strtolower($family).strtoupper($style);
  $this->fonts[$fontkey] =
        array('i'=>$i,'type'=>'Type0','name'=>$name,'up'=>$up,'ut'=>$ut,'cw'=>$cw,'CMap'=>$CMap,'registry'=>$registry);
}

function AddMBFont($family='',$enc='')
{
    global $MBTTFDEF,$MBCMAP;
    $gt=$MBTTFDEF;
    $gc=$MBCMAP;
    if ($enc == '' || isset($gc[$enc]) == false) {
        die("AddMBFont: ERROR Encoding [$enc] Undefine.");
    }
    if (isset($gt[$family])) {
        $ut=$gt[$family]['ut'];
        $up=$gt[$family]['up'];
        $cw=$gt[$family]['cw'];
        $cm=$gc[$enc]['CMap'];
        $od=$gc[$enc]['Ordering'];
        $sp=$gc[$enc]['Supplement'];
        $registry=array('ordering'=>$od,'supplement'=>$sp);
        $this->AddCIDFont($family,''  ,"$family"           ,$cw,$cm,$registry,$ut,$up);
        $this->AddCIDFont($family,'B' ,"$family,Bold"      ,$cw,$cm,$registry,$ut,$up);
        $this->AddCIDFont($family,'I' ,"$family,Italic"    ,$cw,$cm,$registry,$ut,$up);
        $this->AddCIDFont($family,'BI',"$family,BoldItalic",$cw,$cm,$registry,$ut,$up);
    } else {
        die("AddMBFont: ERROR FontName [$family] Undefine.");
    }
}

function GetStringWidth($s)
{
  if($this->CurrentFont['type']=='Type0')
    return $this->GetMBStringWidth($s);
  else
    return parent::GetStringWidth($s);
}

function GetMBStringWidth($s)
{
  //Multi-byte version of GetStringWidth()
  $l=0;
  $cw=&$this->CurrentFont['cw'];
  $japanese = ($this->CurrentFont['registry']['ordering'] == 'Japan1');
  $nb=strlen($s);
  $i=0;
  while($i<$nb)
  {
    $c=$s[$i];
    if(ord($c)<128)
    {
      $l+=$cw[$c];
      $i++;
    }
    else
    {
      $hwkana = ($japanese && ord($c)==142);
      $l+=$hwkana ? 500 : 1000;
      $i+=2;
    }
  }
  return $l*$this->FontSize/1000;
}

// Function Cell override for Encode Change.
function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '')
{

    $k = $this->k;

    if ($this->y + $h > $this->PageBreakTrigger
        && !$this->InFooter
        && $this->AcceptPageBreak()) {
        $x  = $this->x;
        $ws = $this->ws;
        if ($ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        $this->AddPage($this->CurOrientation);
        $this->x = $x;
        if ($ws > 0) {
            $this->ws = $ws;
            $this->_out(sprintf('%.3f Tw', $ws * $k));
        }
    } // end if

    if ($w == 0) {
        $w = $this->w - $this->rMargin - $this->x;
    }

    $s          = '';
    if ($fill == 1 || $border == 1) {
        if ($fill == 1) {
            $op = ($border == 1) ? 'B' : 'f';
        } else {
            $op = 'S';
        }
        $s      = sprintf('%.2f %.2f %.2f %.2f re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
    } // end if

    if (is_string($border)) {
        $x     = $this->x;
        $y     = $this->y;
        if (strpos(' ' . $border, 'L')) {
            $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y+$h)) * $k);
        }
        if (strpos(' ' . $border, 'T')) {
            $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);
        }
        if (strpos(' ' . $border, 'R')) {
            $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
        }
        if (strpos(' ' . $border, 'B')) {
            $s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
        }
    } // end if

    if ($txt != '') {
        if ($align == 'R') {
            $dx = $w - $this->cMargin - $this->GetStringWidth($txt);
        }
        else if ($align == 'C') {
            $dx = ($w - $this->GetStringWidth($txt)) / 2;
        }
        else {
            $dx = $this->cMargin;
        }
        // For Japanese Encode Change
        global $EUC2SJIS;
        if ($EUC2SJIS && function_exists('mb_convert_encoding')) {
            $txt = mb_convert_encoding($txt,"SJIS","EUC-JP");
        }
        $txt    = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
        if ($this->ColorFlag) {
            $s  .= 'q ' . $this->TextColor . ' ';
        }
        $s      .= sprintf('BT %.2f %.2f Td (%s) Tj ET', ($this->x + $dx) * $k, ($this->h - ($this->y + .5 * $h + .3 * $this->FontSize)) * $k, $txt);
        $txt = stripslashes($txt);
        if ($this->underline) {
            $s  .= ' ' . $this->_dounderline($this->x+$dx, $this->y + .5 * $h + .3 * $this->FontSize, $txt);
        }
        if ($this->ColorFlag) {
            $s  .= ' Q';
        }
        if ($link) {
            $this->Link($this->x + $dx, $this->y + .5 * $h - .5 * $this->FontSize, $this->GetStringWidth($txt), $this->FontSize, $link);
        }
    } // end if

    if ($s) {
        $this->_out($s);
    }
    $this->lasth = $h;

    if ($ln > 0) {
        // Go to next line
        $this->y     += $h;
        if ($ln == 1) {
            $this->x = $this->lMargin;
        }
    } else {
        $this->x     += $w;
    }
} // end of the "Cell()" method

function MultiCell($w,$h,$txt,$border=0,$align='L',$fill=0)
{
  if($this->CurrentFont['type']=='Type0')
    $this->MBMultiCell($w,$h,$txt,$border,$align,$fill);
  else
    parent::MultiCell($w,$h,$txt,$border,$align,$fill);
}

function MBMultiCell($w,$h,$txt,$border=0,$align='L',$fill=0)
{
  //Multi-byte version of MultiCell()
  $cw=&$this->CurrentFont['cw'];
  $japanese = ($this->CurrentFont['registry']['ordering'] == 'Japan1');
  if($w==0)
    $w=$this->w-$this->rMargin-$this->x;
  $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
  $s=str_replace("\r",'',$txt);
  $nb=strlen($s);
  if($nb>0 and $s[$nb-1]=="\n")
    $nb--;
  $b=0;
  if($border)
  {
    if($border==1)
    {
      $border='LTRB';
      $b='LRT';
      $b2='LR';
    }
    else
    {
      $b2='';
      if(is_int(strpos($border,'L')))
        $b2.='L';
      if(is_int(strpos($border,'R')))
        $b2.='R';
      $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
    }
  }
  $sep=-1;
  $i=0;
  $j=0;
  $l=0;
  $ns=0;
  $nl=1;
  $ascii=true;
  while($i<$nb)
  {
    //Get next character
    $c=$s[$i];
    //Check if ASCII or MB
    $prev_ascii=$ascii;
    $ascii=(ord($c)<128);
    $hwkana = ($japanese && ord($c)==142);
    if($c=="\n")
    {
      //Explicit line break
      if($this->ws>0)
      {
        $this->ws=0;
        $this->_out('0 Tw');
      }
      $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
      $i++;
      $sep=-1;
      $j=$i;
      $l=0;
      $ns=0;
      $nl++;
      if($border and $nl==2)
        $b=$b2;
      continue;
    }
    if(!($ascii && $prev_ascii) && $i != $j)
    {
      $sep=$i;
      $ls=$l;
    }
    elseif($c==' ')
    {
      $sep=$i;
      $ls=$l;
      $ns++;
    }
    $l+=$ascii ? $cw[$c] : $hwkana ? 500 : 1000;
    if($l>$wmax)
    {
      //Automatic line break
      if($sep==-1)
      {
        if($i==$j)
          $i+=$ascii ? 1 : 2;
        if($this->ws>0)
        {
          $this->ws=0;
          $this->_out('0 Tw');
        }
        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
      }
      else
      {
        if($align=='J')
        {
          if($s[$sep]==' ')
            $ns--;
          if($s[$i-1]==' ')
          {
            $ns--;
            $ls-=$cw[' '];
          }
          $this->ws=($ns>0) ? ($wmax-$ls)/1000*$this->FontSize/$ns : 0;
          $this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
        }
        $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
        $i=($s[$sep]==' ') ? $sep+1 : $sep;
      }
      $sep=-1;
      $j=$i;
      $l=0;
      $ns=0;
      $nl++;
      if($border and $nl==2)
        $b=$b2;
    }
    else
      $i+=$ascii ? 1 : 2;
  }
  //Last chunk
  if($this->ws>0)
  {
    $this->ws=0;
    $this->_out('0 Tw');
  }
  if($border and is_int(strpos($border,'B')))
    $b.='B';
  $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
  $this->x=$this->lMargin;
}

function Write($h,$txt,$link='')
{
  if($this->CurrentFont['type']=='Type0')
    $this->MBWrite($h,$txt,$link);
  else
    parent::Write($h,$txt,$link);
}

function MBWrite($h,$txt,$link)
{
  //Multi-byte version of Write()
  $cw=&$this->CurrentFont['cw'];
  $japanese = ($this->CurrentFont['registry']['ordering'] == 'Japan1');
  $w=$this->w-$this->rMargin-$this->x;
  $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
  $s=str_replace("\r",'',$txt);
  $nb=strlen($s);
  $sep=-1;
  $i=0;
  $j=0;
  $l=0;
  $nl=1;
  while($i<$nb)
  {
    //Get next character
    $c=$s[$i];
    //Check if ASCII or MB
    $ascii=(ord($c)<128);
    $hwkana = ($japanese && ord($c)==142);
    if($c=="\n")
    {
      //Explicit line break
      $this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
      $i++;
      $sep=-1;
      $j=$i;
      $l=0;
      if($nl==1)
      {
        $this->x=$this->lMargin;
        $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
      }
      $nl++;
      continue;
    }
    if(!$ascii or $c==' ')
      $sep=$i;
    $l+=$ascii ? $cw[$c] : $hwkana ? 500 : 1000;
    if($l>$wmax)
    {
      //Automatic line break
      if($sep==-1 or $i==$j)
      {
        if($this->x>$this->lMargin)
        {
          //Move to next line
          $this->x=$this->lMargin;
          $this->y+=$h;
          $w=$this->w-$this->rMargin-$this->x;
          $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
          $i++;
          $nl++;
          continue;
        }
        if($i==$j)
          $i+=$ascii ? 1 : 2;
        $this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
      }
      else
      {
        $this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
        $i=($s[$sep]==' ') ? $sep+1 : $sep;
      }
      $sep=-1;
      $j=$i;
      $l=0;
      if($nl==1)
      {
        $this->x=$this->lMargin;
        $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
      }
      $nl++;
    }
    else
      $i+=$ascii ? 1 : 2;
  }
  //Last chunk
  if($i!=$j)
    $this->Cell($l/1000*$this->FontSize,$h,substr($s,$j,$i-$j),0,0,'',0,$link);
}

function _putfonts()
{
  $nf=$this->n;
  foreach($this->diffs as $diff)
  {
    //Encodings
    $this->_newobj();
    $this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
    $this->_out('endobj');
  }
  $mqr=get_magic_quotes_runtime();
  set_magic_quotes_runtime(0);
  foreach($this->FontFiles as $file=>$info)
  {
    //Font file embedding
    $this->_newobj();
    $this->FontFiles[$file]['n']=$this->n;
    if(defined('FPDF_FONTPATH'))
      $file=FPDF_FONTPATH.$file;
    $size=filesize($file);
    if(!$size)
      $this->Error('Font file not found');
    $this->_out('<</Length '.$size);
    if(substr($file,-2)=='.z')
      $this->_out('/Filter /FlateDecode');
    $this->_out('/Length1 '.$info['length1']);
    if(isset($info['length2']))
      $this->_out('/Length2 '.$info['length2'].' /Length3 0');
    $this->_out('>>');
    $f=fopen($file,'rb');
    $this->_putstream(fread($f,$size));
    fclose($f);
    $this->_out('endobj');
  }
  set_magic_quotes_runtime($mqr);
  foreach($this->fonts as $k=>$font)
  {
    //Font objects
    $this->_newobj();
    $this->fonts[$k]['n']=$this->n;
    $this->_out('<</Type /Font');
    if($font['type']=='Type0')
      $this->_putType0($font);
    else
    {
      $name=$font['name'];
      $this->_out('/BaseFont /'.$name);
      if($font['type']=='core')
      {
        //Standard font
        $this->_out('/Subtype /Type1');
        if($name!='Symbol' and $name!='ZapfDingbats')
          $this->_out('/Encoding /WinAnsiEncoding');
      }
      else
      {
        //Additional font
        $this->_out('/Subtype /'.$font['type']);
        $this->_out('/FirstChar 32');
        $this->_out('/LastChar 255');
        $this->_out('/Widths '.($this->n+1).' 0 R');
        $this->_out('/FontDescriptor '.($this->n+2).' 0 R');
        if($font['enc'])
        {
          if(isset($font['diff']))
            $this->_out('/Encoding '.($nf+$font['diff']).' 0 R');
          else
            $this->_out('/Encoding /WinAnsiEncoding');
        }
      }
      $this->_out('>>');
      $this->_out('endobj');
      if($font['type']!='core')
      {
        //Widths
        $this->_newobj();
        $cw=&$font['cw'];
        $s='[';
        for($i=32;$i<=255;$i++)
          $s.=$cw[chr($i)].' ';
        $this->_out($s.']');
        $this->_out('endobj');
        //Descriptor
        $this->_newobj();
        $s='<</Type /FontDescriptor /FontName /'.$name;
        foreach($font['desc'] as $k=>$v)
          $s.=' /'.$k.' '.$v;
        $file=$font['file'];
        if($file)
          $s.=' /FontFile'.($font['type']=='Type1' ? '' : '2').' '.$this->FontFiles[$file]['n'].' 0 R';
        $this->_out($s.'>>');
        $this->_out('endobj');
      }
    }
  }
}

function _putType0($font)
{
  //Type0
  $this->_out('/Subtype /Type0');
  $this->_out('/BaseFont /'.$font['name'].'-'.$font['CMap']);
  $this->_out('/Encoding /'.$font['CMap']);
  $this->_out('/DescendantFonts ['.($this->n+1).' 0 R]');
  $this->_out('>>');
  $this->_out('endobj');
  //CIDFont
  $this->_newobj();
  $this->_out('<</Type /Font');
  $this->_out('/Subtype /CIDFontType0');
  $this->_out('/BaseFont /'.$font['name']);
  $this->_out('/CIDSystemInfo <</Registry (Adobe) /Ordering ('.$font['registry']['ordering'].') /Supplement '.$font['registry']['supplement'].'>>');
  $this->_out('/FontDescriptor '.($this->n+1).' 0 R');
  $W='/W [1 [';
  foreach($font['cw'] as $w)
    $W.=$w.' ';
  $this->_out($W.']');
  if($font['registry']['ordering'] == 'Japan1')
    $this->_out(' 231 325 500 631 [500] 326 389 500');
  $this->_out(']');
  $this->_out('>>');
  $this->_out('endobj');
  //Font descriptor
  $this->_newobj();
  $this->_out('<</Type /FontDescriptor');
  $this->_out('/FontName /'.$font['name']);
  $this->_out('/Flags 6');
  $this->_out('/FontBBox [0 0 1000 1000]');
  $this->_out('/ItalicAngle 0');
  $this->_out('/Ascent 1000');
  $this->_out('/Descent 0');
  $this->_out('/CapHeight 1000');
  $this->_out('/StemV 10');
  $this->_out('>>');
  $this->_out('endobj');
}
}
?>
