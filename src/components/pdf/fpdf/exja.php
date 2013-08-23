<?php
require(USCES_PLUGIN_DIR.'/classes/fpdf/mbfpdf.php');

// EUC-JP->SJIS 変換を自動的に行なわせる場合に mbfpdf.php 内の $EUC2SJIS を
// true に修正するか、このように実行時に true に設定しても変換してくます。
$GLOBALS['EUC2SJIS'] = true;

$pdf=new MBFPDF();
$pdf->AddMBFont(GOTHIC ,'SJIS');
$pdf->AddMBFont(PGOTHIC,'SJIS');
$pdf->AddMBFont(MINCHO ,'SJIS');
$pdf->AddMBFont(PMINCHO,'SJIS');
$pdf->AddMBFont(KOZMIN ,'SJIS');
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont(GOTHIC,'U',20);
$pdf->Write(10,"MSゴシック 摂氏 18 C 湿度 83 %\n");
$pdf->SetFont(PGOTHIC,'U',20);
$pdf->Write(10,"MSPゴシック 摂氏 18 C 湿度 83 %\n");
$pdf->SetFont(MINCHO,'U',20);
$pdf->Write(10,"MS明朝 摂氏 18 C 湿度 83 %\n");
$pdf->SetFont(PMINCHO,'U',20);
$pdf->Write(10,"MSP明朝 摂氏 18 C 湿度 83 %\n");
$pdf->SetFont(KOZMIN,'U',20);
$pdf->Write(10,"小塚明朝 摂氏 18 C 湿度 83 %\n");
$pdf->Output();
?>
