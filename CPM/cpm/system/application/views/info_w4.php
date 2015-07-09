<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td width="175">Total Allowances Claimed</td>
		<td>:</td>
		<td width="155"><input type="text" name="allowances" size="1"
			onkeypress="return onlyNumbers();" maxlength="1" style="font-family: courier"
			value="<?php echo $this->session->userdata('allowances');?>"></input></td>
	</tr>
	<tr>
		<td width="175">Additional Withholdings</td>
		<td>:</td>
		<td width="155"><input type="text" name="withheld" size="6"
			onkeypress="return onlyNumbers();" maxlength="6" style="font-family: courier"
			value="<?php echo $this->session->userdata('withheld');?>"></input></td>
	</tr>
	<tr>
		<td width="175">Check if exempt</td>
		<td>:</td>
		<td width="155"><input type="hidden" name="exempt[]" value="0" style="border: none"><input
			type="checkbox" name="exempt[]" value="1" style="border: none;" <?php if ($this->session->userdata('exempt')) echo "checked"; ?>></td>
	</tr>
</table>
