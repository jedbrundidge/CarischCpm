<?php
//require ("fpdf/fpdf.php");

$uid = $this->session->userdata('uid');
if (!$uid) die ("You must login to do that.");
$sid = $this->db->query("SELECT `store` FROM `employees` WHERE `uniq_id` = (SELECT `employee_id` FROM `warnings` WHERE `warning_id` = $warning_id LIMIT 0,1)");
$sid = $sid->first_row();
$verified = $this->cpm_functions->verify_access($uid,$sid->store);
if ($verified) {
	$pdf = new FPDF();
	$pdf->addFont("barCode39fHR","","bar39fh.php");
	$pdf->SetRightMargin(160);
	$pdf->SetTopMargin(118);
	$pdf->SetAutoPageBreak(TRUE,62);
	$pdf->AddPage();
	$pdf->addFont("BarCode39fHR","","bar39fh.php");
	$bottom = $this->cpm_pdflayouts->warning_bottom($warning_id);
	$information = $this->cpm_pdflayouts->warning_info($warning_id);
	
	if ($information !== FALSE && $bottom !== FALSE) {
		foreach ($information as $val) {
			switch ($val["type"]) {
				case "SetRightMargin":
					$pdf->SetRightMargin($val[0]);
					break;
				case "SetTopMargin":
					$pdf->SetTopMargin($val[0]);
					break;
				case "MultiCell":
					$pdf->MultiCell($val[0],$val[1],$val[2],$val[3],$val[4]);
					break;
				case "SetXY":
					$pdf->SetXY($val[0],$val[1]);
					break;
				case "Rect":
					$pdf->Rect($val[0],$val[1],$val[2],$val[3]);
					break;
				case "setFont":
					$pdf->setFont($val[0],$val[1],$val[2]);
					break;
				case "Text":
					$pdf->Text($val[0],$val[1],$val[2]);
					break;
			}
		}
		foreach ($bottom as $val) {
			switch ($val["type"]) {
				case "MultiCell":
					$pdf->MultiCell($val[0],$val[1],$val[2],$val[3],$val[4]);
					break;
				case "Rect":
					$pdf->Rect($val[0],$val[1],$val[2],$val[3]);
					break;
				case "setFont":
					$pdf->setFont($val[0],$val[1],$val[2]);
					break;
				case "Text":
					$pdf->Text($val[0],$val[1],$val[2]);
					break;
			}
		}
		$pdf->Output("warning.pdf","I");
	} else {
		die("PDF generation failed. 15");
	}
}
?>