<?php
require(USCES_PLUGIN_DIR.'/classes/fpdf/mbfpdf.php');

$pdf=new MBFPDF();
$pdf->AddMBFont(BIG5,'BIG5');
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont(BIG5,'',20);
$pdf->Write(10,'?????ƒi 18 C ƒ^E? 83 %');
$pdf->Output();
?>
