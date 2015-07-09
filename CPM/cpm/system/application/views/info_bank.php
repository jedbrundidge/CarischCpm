
<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td width="175">Account Type</td>
		<td>:</td>
		<td width="155"><input type="radio" name="account_type"
			value="checking" style="border: none;" <?php if ($this->session->userdata('account_type') == 'checking') echo "checked"; ?>>Checking
		<input type="radio" name="account_type" value="savings" style="border: none;"
		<?php if ($this->session->userdata('account_type') == 'savings') echo "checked"; ?>>Saving</td>
	</tr>
	<tr>
		<td>Transit/ABA Number</td>
		<td>:</td>
		<td><input type="hidden" disabled name="aba_number" size="9" maxlength="9"
			onkeypress="return onlyNumbers();" style="font-family: courier"
			value="<?php echo $this->session->userdata('aba_number'); ?>"></td>
	</tr>
	<tr>
		<td>Account Number</td>
		<td>:</td>
		<td><input type="hidden" disabled name="account_number" size="17" maxlength="17"
			onkeypress="return onlyNumbers();" style="font-family: courier"
			value="<?php echo $this->session->userdata('account_number'); ?>"></td>
	</tr>
</table>