<table><tr><td>WID</td><td>Employee</td><td>Issuer</td><td>Reviewer</td><td>Status</td><td>Warning Date</td><td>Review Date</td><td>Terminated</td><td>Store</td><td>Scanned</td></tr>
<?php 

$reviewers = $this->db->query("select `user_id`,CONCAT(`first_name`,' ',`last_name`) AS `name` from users where user_type = 3 and default_store = 000 and active = 1");
$reviewers = $reviewers->result_array();
$stati = array(
0=>"Complete",
1=>"Pending Reviewer",
2=>"Pending Issuer",
4=>"Pending HR",
3=>"Pending Completion",
99=>"Revoked"
);
$reviewers[] = array('user_id' => '9138','name' => 'Missy Bright');
$docs = $this->db->query("SELECT `warning_id`,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `employees` WHERE `uniq_id` = `employee_id`) AS `employee_name`,`employee_id`,`warnings`.`store`,`scanned`,`warning_date`,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `users` WHERE `user_id` = `submitting_user_id`) AS `submitting_user_name`,(SELECT `eid` FROM `users` WHERE `user_id` = `submitting_user_id`) AS `sub_eid`,`submitting_user_id`,`requested_reviewer`,`warnings`.`status`,`terminated`,`review_date` FROM `warnings` ORDER BY `warning_id` DESC LIMIT 100");
foreach ($docs->result() as $doc) {
	echo "<tr>";
	echo "<td>$doc->warning_id</td>";
	echo "<td><a href=\"/cpm/index.php/personnel/employee/$doc->employee_id\">".ucwords(strtolower($doc->employee_name))."</a></td>";
	echo "<td><a href=\"/cpm/index.php/personnel/employee/$doc->sub_eid\">".ucwords(strtolower($doc->submitting_user_name))."</a></td>";
	
	echo "<td><SELECT value=\"rev\">";
	foreach ($reviewers as $rev) {
		$selected = '';
		if ($rev['user_id'] == $doc->requested_reviewer) {
			$selected = " SELECTED ";
		}
		echo "<option $selected value=\"".$rev['user_id']."\">".ucwords(strtolower($rev['name']))."</option>";
	}
	echo "</SELECT></td>";
	
	
	echo "<td><SELECT value=\"status\">";
	foreach ($stati as $key=>$status) {
		$selected = '';
		if ($doc->status == $key) {
			$selected = " SELECTED ";
		}
		echo "<option $selected value=\"$key\">$status</option>";
	}
	echo "</SELECT></td>";
	echo "<td>$doc->warning_date</td>";
	echo "<td>$doc->review_date</td>";
	if ($doc->terminated) $term = "True"; else $term = "False";
	echo "<td>$term</td>";
	echo "<td><a href=\"/cpm/index.php/personnel/view_employees/$doc->store/1\">$doc->store</a></td>";
	if ($doc->scanned) $scanned = "True"; else $scanned = "False";
	echo "<td>$scanned</td>";
	
	echo "</tr>";
}
?>
</table>