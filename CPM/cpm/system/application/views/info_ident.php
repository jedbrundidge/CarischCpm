
<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td width="175">Social Security Number</td>
		<td>:</td>
		<td width="155"><input type="text" maxlength="9"
			onkeypress="return onlyNumbers();" name="ssn" id="ssn" size="9" class="num"
			value="<?php echo $this->session->userdata('ssn'); ?>"></td>
	</tr>
	<tr>
		<td>First Name</td>
		<td>:</td>
		<td><input type="text" name="first_name" id="first_name" maxlength="32"
			value="<?php echo $this->session->userdata('first_name'); ?>"></input></td>
	</tr>
	<tr>
		<td>Last Name</td>
		<td>:</td>
		<td><input type="text" name="last_name" id="last_name" maxlength="32"
			value="<?php echo $this->session->userdata('last_name'); ?>"></input></td>
	</tr>
	<tr>
		<td>Middle Initial</td>
		<td>:</td>
		<td><input type="text" name="middle_initial" id="middle_initial" size="1" maxlength="1"
			value="<?php echo $this->session->userdata('middle_initial'); ?>"></input></td>
	</tr>
	<tr>
		<td>Other Names Used - If Any</td>
		<td>:</td>
		<td><input type="text" name="other_name" id="other_name" maxlength="32"
			value="<?php echo $this->session->userdata('other_name'); ?>"></input></td>
	</tr>

</table>
