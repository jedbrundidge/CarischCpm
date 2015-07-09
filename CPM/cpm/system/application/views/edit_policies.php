<form name="edit_policies" method="POST" style="border: none;"
	action="/cpm/index.php/personnel/edit_policies">
<table cellspacing="0" cellpadding="0">
<?php
$policies = $this->db->query("SELECT `policy_id`,`policy_name`,`policy_desc` FROM `warning_definitions`");
$n=0;
foreach ($policies->result() as $result) {
	$n++;
	$bgc="ffffff";
	if ($n & 1) {
		$bgc="dddddd";
	}
	echo "<tr bgcolor=\"$bgc\"><td><input type=\"text\" size=\"30\" style=\"font-family: Arial;\" value=\"$result->policy_name\" name=\"$result->policy_id-NAME\"></td><td><input type=\"text\" value=\"$result->policy_desc\" style=\"font-family: Arial;\" name=\"$result->policy_id-DESC\" size=\"100\"></td></tr>";
}
?>
	<tr>
		<td><input type="text" size="30" style="font-family: Arial;" value=""
			name="new_name"></td>
		<td><input type="text" size="100" name="new_desc"
			style="font-family: Arial;"></td>
	</tr>
</table>
<input type="submit" name="go" value="Submit Changes"
	style="font-family: Arial;"></form>
