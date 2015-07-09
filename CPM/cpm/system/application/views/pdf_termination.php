<?php
//require ('fpdf/fpdf.php');
$term = $this->db->query("SELECT `reason_desc`,`termination_details`,`no_rehire_desc`,`employee_id`,`users`.`first_name` AS `mfname`,`users`.`last_name` AS `mlname`,`employees`.`first_name` AS `efname`,`employees`.`last_name` AS `elname`,`manager_id`,`termination_date`,`last_day_worked`,`separation`,`reason`,`rehire`,`reference_warning`,`scanned`,`status`,`terminations`.`store` FROM
	`terminations` JOIN `employees` ON `employee_id` = `uniq_id`  JOIN `users` ON `user_id` = `manager_id` JOIN `termination_descriptions` ON `terminations`.`termination_id` = `termination_descriptions`.`termination_id` JOIN `termination_definitions` ON `reason` = `reason_code` WHERE `terminations`.`termination_id` = $tid");
$term = $term->first_row();

$sName = $this->db->query("SELECT `store_name` FROM `stores` WHERE `store_id` = $term->store")->first_row()->store_name;

$employee_name = ucwords(strtolower("$term->efname $term->elname"));
$manager_name = ucwords(strtolower("$term->mfname $term->mlname"));
$rehire = "No";
if ($term->rehire == 1) {
	$rehire = "Yes";
} elseif ($term->rehire == 2) {
	$rehire = "Conditionally";
}

$pdf = new FPDF();
$pdf->addFont('barCode39fHR','','bar39fh.php');
$pdf->SetRightMargin(160);
$pdf->SetTopMargin(118);
$pdf->SetAutoPageBreak(TRUE,62);
$pdf->AddPage();
$pdf->setFont('barCode39fHR','',24);
$pdf->Text(10,20,"*4-$tid*");
$pdf->setFont('Arial','B',18);
$pdf->Text(15,35,'Voluntary Termination');
$pdf->setFont('Arial','',10);
$pdf->Text(15,40,"Employee:      $employee_name ($term->employee_id)");
$pdf->Text(15,45,"Issued by:       $manager_name ($term->manager_id) / $sName");
$pdf->Text(15,55,"Termination Date:	$term->termination_date");
$pdf->Text(15,60,"Last Day: 			$term->last_day_worked");
$pdf->Text(15,65,"Reason: $term->reason_desc");
$pdf->Text(100,40,"Would you rehire? $rehire");
$pdf->Text(15,275,"______________________________________________________");
$pdf->Text(25,280,"Manager Signature                                                  Date");
if ($rehire != "Yes") {
$pdf->SetXY(135,45);
	$pdf->MultiCell(50,4,"$term->no_rehire_desc",1,'J');
}
$pdf->SetXY(15,70);
$pdf->MultiCell(175,4,"$term->termination_details",1,'J');


$pdf->Output('term.pdf','I');
?>