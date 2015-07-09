<?php
$uid = $this->session->userdata('uid');
$p_warnings = array();
$p_terminations = array();
$warnings = $this->db->query("SELECT `store`,`warning_id` FROM `warnings` WHERE `scanned` = 0 AND `status` = 0");
$terminations = $this->db->query("SELECT `store`,`termination_id` FROM `terminations` WHERE `scanned` = 0 AND `status` = 2");
foreach ($warnings->result() as $val) {
	if ($this->cpm_functions->verify_access($uid,$val->store) === true) {
		$p_warnings[] = $val->warning_id;
	}
}
foreach ($terminations->result() as $val) {
	if ($this->cpm_functions->verify_access($uid,$val->store) === true) {
		$p_terminations[] = $val->termination_id;
	}
}

//print_r($p_warnings);
//echo "<br>";
//print_r($p_terminations);
?>
<table><tr><td>Employee</td><td>Store</td><td>Date Issued</td><td>Document Type</td></tr>
<?php 
foreach ($p_warnings as $w_id) {
	$out = $this->db->query("SELECT `first_name`,`last_name`,`uniq_id`,`warnings`.`store` AS `store`,`warning_date` FROM `warnings` JOIN `employees` ON `uniq_id` = `employee_id` WHERE `warning_id` = $w_id ORDER BY `warning_date`");
	$out = $out->first_row();
	$name = ucwords(strtolower("$out->first_name $out->last_name"));
	echo "<tr><td>$name</td><td>$out->store</td><td>$out->warning_date</td><td align=\"right\">Warning</td></tr>";
}
?>
</table>