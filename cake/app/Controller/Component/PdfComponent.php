<?php
App::import('Vendor', 'tcpdf/tcpdf');
App::import('Vendor', 'fpdi/fpdi');

class PdfComponent extends Component {
	
	public function pdfstart($size = NULL) {
		if(!is_null($size)) $wk = array(182, 257); //B5
		$pdf = new FPDI('P', 'mm', $size);//P 縦、L　横
		
		$pdf->SetMargins(0, 0, 0);
		$pdf->SetCellPadding(0);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(false);
		return($pdf);
	}
	
	
	public function pdfend($pdf, $wkdate = NULL, $name = NULL) {
		$pdf->Output($wkdate . $name . ".pdf", 'FD');
	//	unlink($wkdate . $name . "pdf");
	}
}

?>