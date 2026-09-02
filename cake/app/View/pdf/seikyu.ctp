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
	$pdf->setSourceFile('Book1.pdf');
	$page = $pdf->importPage(1);
	$pdf->useTemplate($page);
	$pdf->SetFont('kozminproregular','B',28);//日本語のフォントも使えるよーこれは明朝体
	$font_path = './ipag00303/ipag.ttf';
	if (file_exists($font_path)) {
		$font_name = $pdf->addTTFfont($font_path, 'TrueTypeUnicode');
		$pdf->SetFont($font_name, '', 10);
	}
	$pdf->Text(95, 20, "請求書"); 
	$pdf->SetFontSize(10);
	$pdf->Text(175, 37, $date); 
	$pdf->SetFontSize(16);
	$pdf->Text(18, 43, $mtenpo['Mtenpo']['kaishaname']); 
	$pdf->SetFontSize(14);
	$pdf->Text(18, 57,  $mtenpo['Mtenpo']['daihyouname']); 
	$pdf->SetFontSize(10);
	$pdf->Text(18, 64, $mtenpo['Mtenpo']['address']); 
	$pdf->Text(18, 69, "電話番号：" . $mtenpo['Mtenpo']['telno']); 
//var_dump($mtenpo);exit;
	
	$pdf->Text(130, 50, "株式会社　ハートフル"); 
	$pdf->Text(130, 55, "〒112-0002"); 
	$pdf->Text(130, 60, "東京都文京区小石川3-27-16フォルム小石川409"); 
	$pdf->Text(130, 65, "代表取締役　佐藤　隆三"); 
	$pdf->Text(130, 70, "電話番号：03-3830-0583"); 
	$pdf->Text(130, 75, "ryuzo54@gmail.com"); 
	$pdf->Text(20, 100, "Ｎ"); 
	$pdf->Text(20, 105, "Ｏ．");
	$pdf->Text(60, 103, "件　　　名"); 
	if($id == 37 OR $id == 57) {
		$pdf->Text(110, 103, "件数"); 
		$pdf->Text(126, 103, "単　価"); 
		$pdf->Text(154, 103, "税　分"); 
		$pdf->Text(181, 103, "請求金額");
	} else { 
			 
		$pdf->Text(110, 103, "人数"); 
		$pdf->Text(125, 103, "売　　上"); 
		$pdf->Text(150, 103, "オーナー分"); 
		$pdf->Text(180, 103, "請求金額"); 
	}
	
	//扶桑社
	if($id == 57) {
		$pdf->Text(20.5, 114, "1"); 
		$pdf->Text(28, 114, "取材協力セッション代"); 
		$pdf->Text(114, 114, "1"); 
		$pdf->Text(133, 114, '35,000'); 
		$pdf->Text(164, 114, '2,800'); 
		$pdf->Text(190, 114, '37,800');
		$y = 114;
		$y = $y + 9.25;
		$pdf->Text(28, $y, "  　　　 以　下　余　白　　"); 
		$y = 244;
		$pdf->Text(158, $y, "合　計");
		$pdf->Text(190, $y, "37,800");
		$y = 244;
		$pdf->Text(20, $y, "以下の口座にお振込ください。");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "銀行名　ジャパンネット銀行");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "支店名　スズメ支店");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "口座番号　（普）1346823");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "口座名義　ハートフルウラナイブースジギョウブ");
		$pdf->Image('./img/22.gif', 180, 68, '', '', 'gif');


	//キュービック
	} else if($id == 37) {
		$pdf->Text(20.5, 114, "1"); 
		$pdf->Text(28, 114, $mon  . "月星座占いライター料"); 
		$pdf->Text(114, 114, "1"); 
		$pdf->Text(133, 114, '20,000'); 
		$pdf->Text(164, 114, '1,600'); 
		$pdf->Text(190, 114, '21,600');
		$y = 114;
		$y = $y + 9.25;
		$pdf->Text(28, $y, "  　　　 以　下　余　白　　"); 
		$y = 244;
		$pdf->Text(158, $y, "合　計");
		$pdf->Text(190, $y, "21,600");
		$y = 244;
		$pdf->Text(20, $y, "以下の口座にお振込ください。");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "銀行名　ジャパンネット銀行");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "支店名　スズメ支店");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "口座番号　（普）1346823");
		$y = $y + 6.25; 
		$pdf->Text(28, $y, "口座名義　ハートフルウラナイブースジギョウブ");
		$pdf->Image('./img/22.gif', 180, 68, '', '', 'gif');
	
	} else { 
//var_dump($shukei);exit;
		$y = 25;
		$Y = 104;
		$count = 1;
		$c_suu = 0;
		$c_kei = 0;
	foreach ($shukei as $shukeis) {
		$pdf->SetXY(20, $Y);
		$pdf->Cell(6, $y, $count, 0, 0, 'C'); 
		
		$pdf->Cell(88, $y, $mon ."月分鑑定料  ". $shukeis['mtenpos']['tenponame']); 

		$wk= sprintf("%4d", $shukeis['0']['mpeople_id']);
		$c_suu +=  $shukeis['0']['mpeople_id'];
		$pdf->Cell(3.5, $y, $wk, 0, 0 ,'R'); 
		
		$wk = number_format($shukeis['0']['kanteiryoukin']);
		$pdf->Cell(26, $y, $wk,  0, 0 ,'R'); 
		
		if($shukeis['mtenpos']['mdivision_id'] == MDVI_ID)
		if($shukeis['0']['omisebun'] > 0) $wk = $shukeis['0']['omisebun']; else $wk = 0;
			$shukeis['0']['omisebun'] = $shukeis['0']['omisebun'] * (-1);
		if($wk == 0)	
			$wk=  number_format($shukeis['0']['kanteiryoukin']  -$shukeis['0']['omisebun']);
		$pdf->Cell(29.5, $y, $wk,  0, 0 ,'R'); 

		$wk = number_format($shukeis['0']['omisebun']);
		$c_kei += $shukeis['0']['omisebun'];
		$pdf->Cell(28, $y, $wk,  0, 0 ,'R'); 
		$Y = $Y + 9.25;
		$count += 1;
	}	
		if($id == 18) {	
			$Y = $Y + 9.25;
			$pdf->Text(60, $Y, "件　　　名"); 
			$pdf->Text(110, $Y, "件数"); 
			$pdf->Text(126, $Y, "単　価"); 
			$pdf->Text(154, $Y, ""); 
			$pdf->Text(181, $Y, "請求金額");
	
		//	$Y = $Y + 9.25;
		
			$pdf->SetXY(20, $Y);
			$pdf->Cell(6, $y, $count, 0, 0, 'C'); 
			$pdf->Cell(88, $y, $mon . "月サーバー・ドメイン登録管理料"); 
			$pdf->Cell(3.5, $y, 1, 0, 0 ,'R');
			$pdf->Cell(26, $y, number_format(1500),  0, 0 ,'R'); 
			$pdf->Cell(29.5, $y, '  ',  0, 0 ,'R'); 
			$pdf->Cell(28, $y, number_format(1500),  0, 0 ,'R'); 
			$c_kei += 1500;
			$Y = $Y + 9.25;
/*
			$pdf->SetXY(20, $Y);
			$pdf->Cell(6, $y, $count, 0, 0, 'C'); 
			$pdf->Cell(88, $y, $mon . "月胡桃あい週刊星占い料"); 
			$pdf->Cell(3.5, $y, 1, 0, 0 ,'R');
			$pdf->Cell(26, $y, number_format(10000),  0, 0 ,'R'); 
			$pdf->Cell(29.5, $y, '  ',  0, 0 ,'R'); 
			$pdf->Cell(28, $y, number_format(10000),  0, 0 ,'R'); 
			$c_kei += 10000;
			$Y = $Y + 9.25;
*/
		}
/*
		if($id == 43) {	
			$Y = $Y + 9.25;
			$pdf->Text(60, $Y, "件　　　名"); 
			$pdf->Text(110, $Y, "件数"); 
			$pdf->Text(126, $Y, "単　価"); 
			$pdf->Text(154, $Y, ""); 
			$pdf->Text(181, $Y, "請求金額");
	
		//	$Y = $Y + 9.25;
		
			$pdf->SetXY(20, $Y);
			$pdf->Cell(6, $y, $count, 0, 0, 'C'); 
			$pdf->Cell(88, $y, $mon . "ティッシュ2000個"); 
			$pdf->Cell(3.5, $y, 1, 0, 0 ,'R');
			$pdf->Cell(26, $y, number_format(5832),  0, 0 ,'R'); 
			$pdf->Cell(29.5, $y, '  ',  0, 0 ,'R'); 
			$pdf->Cell(28, $y, number_format(5832),  0, 0 ,'R'); 
			$c_kei += 5832;
			$Y = $Y + 9.25;
		}
*/
//恋愛術師だったら
		if($id == 20) {	
			$re_wk = 451037 - 45294 - 54706 - 100000;
			$pdf->SetXY(20, $Y);
			$pdf->Cell(6, $y, $count, 0, 0, 'C'); 
			$pdf->Cell(88, $y, '請求書発行時点の貸付残高', 'C'); 
			$pdf->Cell(3.5, $y, 1, 0, 0 ,'R');
			$pdf->Cell(26, $y, number_format($re_wk),  0, 0 ,'R'); 
			$pdf->Cell(29.5, $y, '  ',  0, 0 ,'R'); 
			$pdf->Cell(28, $y, number_format($re_wk),  0, 0 ,'R'); 
			$c_kei += $re_wk;
			$Y = $Y + 9.25;
		}
		$pdf->SetXY(20, $Y);
		$pdf->Cell(88, $y,  "以　下　余　白", 0, 0, 'C'); 
	


		//合計
		$Y = 233;
		$pdf->SetXY(155, $Y);
		$pdf->Cell(18, $y,"人　　数  ",0,0,'C'); 
		$pdf->Cell(28.5, $y, number_format($c_suu),  0, 0 ,'R'); 
			$Y = $Y + 9.25;
		$pdf->SetXY(155, $Y);
		$pdf->Cell(18, $y,"請求金額  ",0,0,'C'); 
		$pdf->Cell(28.5, $y, number_format($c_kei),  0, 0 ,'R'); 

}

	$y = 244;
	$pdf->Text(20, $y, "以下の口座にお振込ください。");
	$y = $y + 6.25; 
	$pdf->Text(28, $y, "銀行名　ジャパンネット銀行");
	$y = $y + 6.25; 
	$pdf->Text(28, $y, "支店名　スズメ支店");
	$y = $y + 6.25; 
	$pdf->Text(28, $y, "口座番号　（普）1346823");
	$y = $y + 6.25; 
	$pdf->Text(28, $y, "口座名義　ハートフルウラナイブースジギョウブ");



	$pdf->Image('./img/22.gif', 180, 68, '', '', 'gif');

	// mixed Write( float $h, string $txt, [mixed $link = ''], [int $fill = 0], [string $align = ''], [boolean $ln = false], [int $stretch = 0], [boolean $firstline = false], [boolean $firstblock = false], [float $maxh = 0])
	//$pdf->Write(0, 'TCPDFはwriteHTMLCell()が一番使いやすい', '', 0, 'L', true, 0, false, false, 0);

	// void writeHTMLCell( float $w, float $h, float $x, float $y, [string $html = ''], [mixed $border = 0], [int $ln = 0], [int $fill = 0], [boolean $reseth = true], [string $align = ''], [boolean $autopadding = true])
	//$pdf->writeHTMLCell(100, 25, 20, 20, '<div style="text-align:right">あいうえお<br>999,999,999<span style="font-size:20pt; color:#ff0000;">円</span><br>Hello world!</div>', array('TBLR' => array('width' => 0.1)));

	// 第2引数の説明
	// TCPDF::Output($filename, $desc)
	// I: send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
	// D: send to the browser and force a file download with the name given by name.
	// F: save to a local server file with the name given by name.
	// S: return the document as a string (name is ignored).
	// FI: equivalent to F + I option
	// FD: equivalent to F + D option
	// E: return the document as base64 mime multi-part email attachment (RFC 2045)
	// tcpdf.phpのOutput()は第2引数にFが含まれていないとき、ファイル名から英数字以外の
	// 文字を削除してしまうので、いったんFIかFDでサーバーにも保存してすぐに削除してしまうのがいい。
	//$filename = $pdf->escapeFilename('PDFサンプル.pdf');
	$pdf->Output($year . $mon . $mtenpo['Mtenpo']['kaishaname']  . '.PDF', 'FD');
	unlink($filename);


