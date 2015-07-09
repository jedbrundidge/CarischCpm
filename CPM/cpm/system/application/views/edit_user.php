<form action="/cpm/index.php/users/set" method="POST">
<input type="hidden" id="eid" name="eid" value="{eid}" />
<input type="hidden" id="uid" name="uid" value="{uid}" />
<input type="hidden" id="uname" name="uname" value="{uname}" />

<table>
<tr>
	<td align="center" colspan="5">User ID: {uid}</td>
</tr>
<tr>
	<td align="center" colspan="5">User Name: {uname}</td>
</tr>
<tr>
	
	<td>Access Level:</td><td><select id="utype" name="utype">{utypes}<option value="{type}" {selected}>{name}</option>{/utypes}</select></td>
	
</tr>
<tr>
	<td>Password (<a href="/cpm/index.php/users/reset_passwd/{eid}">reset</a>):</td><td><input type="password" id="passwd1" name="passwd1" /></td>
</tr>
<!-- <tr><td></td><td><input type="password" id="passwd2" name="passwd2" /></td></tr> -->
<tr>
	<td>Active:</td><td><select id="active" name="active"><option value="1" {aTrue}>True</option><option value="0" {aFalse}>False</option></select></td>
</tr>
<tr>
	<td colspan="5" align="center"><input type="submit" value="Save"  /></td>
</table>
</form>
