<?php if (!isset($site)) $site = 'personnel'; ?>
<form name="form1" method="post" action="/cpm/index.php/<?=$site?>/change_pw">
<table width="375" border="0" align="left" cellpadding="0"
	cellspacing="1">
	<tr>
		<td>
		<table width="100%" border="0" cellpadding="3" cellspacing="1">
			<tr>
				<td colspan="3"><strong>Password Change</strong></td>
			</tr>
			<tr>
				<td width="175">Current Password</td>
				<td width="6">:</td>
				<td width="294"><input name="cur_passwd" type="password"
					id="cur_passwd"></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td>New Password</td>
				<td>:</td>
				<td><input name="new_password1" type="password" id="new_password1">
				</td>
			</tr>
			<tr>
				<td>Retype Password</td>
				<td>:</td>
				<td><input name="new_password2" type="password" id="new_password2"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" name="change_pw" value="Submit"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
