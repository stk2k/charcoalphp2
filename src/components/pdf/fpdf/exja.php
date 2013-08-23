<?php
require(USCES_PLUGIN_DIR.'/classes/fpdf/mbfpdf.php');

// EUC-JP->SJIS �ϊ��������I�ɍs�Ȃ킹��ꍇ�� mbfpdf.php ���� $EUC2SJIS ��
// true �ɏC�����邩�A���̂悤�Ɏ��s���� true �ɐݒ肵�Ă��ϊ����Ă��܂��B
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
$pdf->Write(10,"MS�S�V�b�N �ێ� 18 C ���x 83 %\n");
$pdf->SetFont(PGOTHIC,'U',20);
$pdf->Write(10,"MSP�S�V�b�N �ێ� 18 C ���x 83 %\n");
$pdf->SetFont(MINCHO,'U',20);
$pdf->Write(10,"MS���� �ێ� 18 C ���x 83 %\n");
$pdf->SetFont(PMINCHO,'U',20);
$pdf->Write(10,"MSP���� �ێ� 18 C ���x 83 %\n");
$pdf->SetFont(KOZMIN,'U',20);
$pdf->Write(10,"���˖��� �ێ� 18 C ���x 83 %\n");
$pdf->Output();
?>
