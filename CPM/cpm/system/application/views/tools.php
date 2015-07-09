<form method="POST">
SSN Lookup <input type="text" name="ssn_lookup" maxlength="9" size="9">
<input type="submit">
<?php
if ($_POST) {
	echo "<br>";
	$ssn_lookup = $_POST['ssn_lookup'];
	$info = $this->db->query("SELECT `uniq_id`,`e_id`,`first_name`,`last_name`,`doh` FROM `employees` JOIN `employee_info` ON `uniq_id` = `unique_id` WHERE `ssn_hash` = '$ssn_lookup'");
	$info = $info->result();
	foreach ($info as $info) {
		echo "$info->uniq_id,$info->e_id,$info->first_name,$info->last_name,$info->doh";
	}
}
?>