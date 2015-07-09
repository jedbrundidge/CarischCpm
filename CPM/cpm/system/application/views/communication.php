<?php if ( ! defined('BASEPATH')) exit(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd" />
<html>
<head>
<title>&nbsp;</title>
<link rel="stylesheet" href="/cpm/css/docs.css" type="text/css" />
</head>
<body>
<img src="/cpm/tmp/{barcode}.jpg" />

<h3>{communication_type}</h3>
<table cellpadding="1">
	<tr>
		<td>Employee</td><td>:</td><td>{employee_name} ({employee_id})</td>
	</tr>
	<tr>
		<td>Issued By</td><td>:</td><td>{manager_name}, {manager_position} ({manager_id})</td>
	</tr>
	<tr>
		<td>Location</td><td>:</td><td>{store_name} ({store_id})</td>
	</tr>
	<tr>
		<td>Incident Date</td><td>:</td><td>{incident_date}</td>
	</tr>
	<tr>
		<td>Communication Date</td><td>:</td><td>{communication_date}</td>
	</tr>
</table>
<p>
All employees are expected to abide by all rules and policies at all times.
</p>
<h6>Carisch, Inc. personal conduct or work rule that was violated:</h6>
<ul>
	{policies}
		<li>{policy}</li>
	{/policies}
</ul>
<p>
{employee_name} is now under disciplinary action as of this date as the result of unsatisfactory job performance or conduct as described below:
</p>
<hr />
{description}
<hr />
{appeal}
{agreement}
<center>
<table style="width=100%;border:none;" cellspacing="0" cellpadding="0">
<tr height="20"><td width="260px" class="signature">Employee Signature</td><td class="signature">Date</td><td width="75px"></td><td width="110px"> </td><td width="260px" class="signature">Manager Signature</td></td><td class="signature">Date</td></tr>
<tr><td colspan="10">&nbsp;</tr>
<tr height="70"><td colspan="10" style="vertical-align: top;"><p>In the event that the employee refuses to sign, the manager should ask a third person to witness refusal and ask them to sign.</p></td></tr>
<tr><td class="signature" width="260px">Witness Signature</td><td class="signature">Date</td><td></td><td></td><td></td></tr>
</table>
</center>
</body>
</html>

