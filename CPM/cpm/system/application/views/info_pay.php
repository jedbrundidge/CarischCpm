<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td width="175">Position</td>
		<td>:</td>
		<td width="155"><select name="position">
			<option value="8"
			<?php if ($this->session->userdata('position') == 8) echo "selected"; ?>>Crew</option>
			<option value="6"
			<?php if ($this->session->userdata('position') == 6) echo "selected"; ?>>Shift
			Manager</option>
			<option value="5"
			<?php if ($this->session->userdata('position') == 5) echo "selected"; ?>>Assistant
			Manager</option>
			<option value="4"
			<?php if ($this->session->userdata('position') == 4) echo "selected"; ?>>Manager</option>
		</select></td>
	</tr>
	<tr>
		<td>Pay Rate</td>
		<td>:</td>
		<td><input type="text" onkeypress="return onlyNumbers();"
			name="payrate" maxlength="5" size="5" style="font-family: courier"
			value="<?php echo $this->session->userdata('payrate'); ?>"></input></td>
	</tr>
	<tr>
		<td>Carisch Dimes Deduction</td>
		<td>:</td>
		<td><input type="hidden" name="carisch_dimes[]" value="0"
			style="border: none"><input type="checkbox" name="carisch_dimes[]"
			value="1" style="border: none;"
			onClick="alert('You must be employed six months to be considered eligible to make a Carisch Dimes request.')"
			<?php if ($this->session->userdata('carisch_dimes')) echo "checked"; ?>></td>
	</tr>
</table>
