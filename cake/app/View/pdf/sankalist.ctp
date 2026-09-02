<?php
App::import('Vendor', 'tcpdf/tcpdf');
App::import('Vendor', 'fpdi/fpdi');
 
/**
 * TCPDF＋FPDIを簡単に使えるようにするためのクラス
 */
//=============================================================================
//   main
//=============================================================================

/**
 * テスト
 */
 	$pdf = new FPDI();

	$pdf->SetMargins(0, 0, 0);
	$pdf->SetCellPadding(0);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// ページ追加
	$pdf->AddPage();
	$pdf->setSourceFile('/files/SchoolList.pdf');
	$page = $pdf->importPage(1);
	$pdf->useTemplate($page);
	$pdf->SetFont('kozminproregular','B',28);//日本語のフォントも使えるよーこれは明朝体
	$font_path = './ipag00303/ipag.ttf';
	if (file_exists($font_path)) {
		$font_name = $pdf->addTTFfont($font_path, 'TrueTypeUnicode');
		$pdf->SetFont($font_name, '', 10);

?>