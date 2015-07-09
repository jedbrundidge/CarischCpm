<form name="edit_policies" method="POST" style="border: none">
<table cellspacing="0" cellpadding="0">
<?php
$policies = $this->db->query("SELECT `reason_code`,`reason_name`,`reason_desc` FROM `termination_definitions`");
$n=0;
foreach ($policies->result() as $result) {
	$n++;
	$bgc="ffffff";
	if ($n & 1) {
		$bgc="dddddd";
	}
	echo "<tr bgcolor=\"$bgc\"><td><input type=\"text\" size=\"1\" style=\"font-family: Arial;\" value=\"$result->reason_code\" name=\"name$result->reason_code\"></td><td><input type=\"text\" size=\"30\" style=\"font-family: Arial;\" value=\"$result->reason_name\" name=\"name$result->reason_code\"></td><td><input type=\"text\" value=\"$result->reason_desc\" style=\"font-family: Arial;\" name=\"desc$result->reason_code\" size=\"100\"></td></tr>";
}
?>
<tr><td><input type="text" size="1" style="font-family: Arial;" value="" name="new_code"></input></td><td><input type="text" size="30" style="font-family: Arial;" value="" name="new_name"></td><td><input type="text" size="100" name="new_desc" style="font-family: Arial;"></td></tr>
</table>
<input type="submit" name="go" value="Submit Changes" style="font-family: Arial;">
</form>
