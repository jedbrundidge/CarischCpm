<?php
$term_info = $this->db->query("SELECT `no_rehire_desc`,`termination_details` AS `description`,`employee_id`,`store`,`termination_date`,`last_day_worked`,`reason`,`rehire`,`status`,`store`,`manager_id` FROM `terminations` JOIN `termination_descriptions` ON `terminations`.`termination_id` = `termination_descriptions`.`termination_id` WHERE `terminations`.`termination_id` = $termination_id");
$term_info = $term_info->first_row();
$name = $this->db->query("SELECT `first_name`,`last_name` FROM `employees` WHERE `uniq_id` = $term_info->employee_id");
$name = $name->first_row();
$name = ucwords(strtolower("$name->first_name $name->last_name"));
?>
<link rel="stylesheet" href="/cpm/spell_checker/spell_checker.css" type="text/css">
<script src="/cpm/spell_checker/mootools-1.2-core-yc.js"></script>
<script src="/cpm/spell_checker/spell_checker.js"></script>
<script type="text/javascript">
    function checkForm() {
        sc_spellCheckers.resumeAll();
        return true;
    }
</script>
<form name="edit_term" method="POST" action="/cpm/index.php/personnel/update_term/<?=$termination_id?>">
<table width="380">
	<tr>
		<td width="20%">Employee ID</td>
		<td>:</td>
		<td><?php echo $term_info->employee_id ?></td>
	</tr>
	<tr>
		<td>Name</td>
		<td>:</td>
		<td><?php echo "$name"; ?></td>
	</tr>
	<tr>
		<td>Store</td>
		<td>:</td>
		<td><?php echo "$term_info->store"; ?></td>
	</tr>
	<tr>
		<td>Last Day Worked</td>
		<td>:</td>
		<td><input type="text" onkeypress="return onlyNumbers();"
			name="last_day_worked"
			value="<?php echo "$term_info->last_day_worked"; ?>" size="10"></td>
	</tr>
	<tr>
		<td>Termination Date</td>
		<td>:</td>
		<td><input type="text" onkeypress="return onlyNumbers();"
			name="termination_date"
			value="<?php echo "$term_info->termination_date"; ?>" size="10"></td>
	</tr>

	<tr>
		<td>Rehire</td>
		<td>:</td>
		<td><input type="checkbox" style="border: none;" name="rehire">Explain<input
			type="text" value="<?php echo $term_info->no_rehire_desc; ?>" name="no_rehire_desc"></td>
	</tr>
	<!--
	<tr>
		<td>Property Returned</td>
		<td></td>
	</tr>
	<tr></tr>
	<tr>
		<td></td>
		<td></td>
		<td><input type="checkbox" style="border: none;">placeholder</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><input type="checkbox" style="border: none;">placeholder</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><input type="checkbox" style="border: none;">placeholder</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><input type="checkbox" style="border: none;">placeholder</td>
	</tr>
	-->
	</tr>


	<tr>
		<td>Reason</td>
		<td>:</td>
		<td><select name="reason" style="font-family: Arial;">
		<?php
		$reasons = $this->db->query("SELECT `reason_code`,`reason_name`,`reason_desc` FROM `termination_definitions` WHERE `voluntary` = 1");
		foreach ($reasons->result() as $val) {
			$select_reason = "";
			if ($term_info->reason == $val->reason_code) {
				$select_reason = "SELECTED";
			}
			echo "<option $select_reason value=\"$val->reason_code\">$val->reason_name - $val->reason_desc</option>";
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td colspan="3">Description:</td>
	</tr>
	<tr>
		<td colspan="3"><textarea class="spell_check" name="description" rows="30" cols="85"><?=$term_info->description?></textarea></td>
	</tr>
	<tr>
		<td><input style="font-family: Arial;" type="button" name="edit"
			onclick="edit_warning(true)" value="Edit"></td>
		<td></td>
		<td align="right"><input style="font-family: Arial;" type="submit"
			value="Submit Changes" name="sub"></td>
	</tr>
</table>
</form>
<?php
if ($this->session->userdata('utype') <= 3) {
	echo "<a href=\"/cpm/index.php/personnel/revoke/t/$termination_id\">Revoke Termination</a>";
}
?>