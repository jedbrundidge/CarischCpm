<link rel="stylesheet" href="/cpm/default.css" type="text/css">
<style>
input, select, href {
	font-family: Arial;
}
select,ul {
	height: 105px;
	overflow: auto;
	width: 135px;
	border: 0px solid #B0B0B0;
}

ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow-x: hidden;
}

li {
	margin: 0;
	padding: 0;
}

label {
	display: block;
	color: WindowText;
	background-color: Window;
	margin: 0;
	padding: 0;
	width: 100%;
}
</style>
<table><tr><td valign="top">
<table border="0">
<tr>
<td height="20"><a href="">Change PW</a></td>
</tr>
<tr>
<td height="20"><a href="">Change Addr</a></td>
</tr>
<tr>
<td height="20"><a href="">Change Addr</td>
</tr>
<tr>
<td height="20"><a href="">Logout</td>
</tr>
</table>
</td><td>
<form name="stubs" id="stubs" method="POST" action="/cpm/index.php/employees/get_docs">
<table cellpadding="0" cellspacing="0"><tr bgcolor="dddddd"><td align="center"  height="24">Paystubs</td></tr>
<tr><td><ul>
<?php
$eid = $this->session->userdata('eid');
$all = $this->db->query("SELECT `trans_id`,DATE_FORMAT(`doc_date`,'%b %d, %Y') AS `doc_date`,`doc_type` FROM `payroll_docs` WHERE `emp_id` = $eid ORDER BY `doc_date` DESC");
$a_string = '';
foreach ($all->result() as $docs) {
	switch ($docs->doc_type) {
		case 1 :
			$docs->doc_type = "Stub";
			break;
		case 2 :
			$docs->doc_type = "W2";
			break;
	}
	echo "<li><label for=\"$docs->trans_id\"><input type=\"checkbox\" style=\"border: none;\" value=\"$docs->trans_id\" name=\"doc_array[]\">$docs->doc_date</label></li>";
	//echo <li><label for=\"".$val['key']."\">".$val['desc']."</label></li>
}
?>
</ul></td></tr><tr bgcolor="dddddd"><td align="center" valign="middle"><input style="width:125px" type="submit" name="Get Stubs" value="Retrieve Documents"/></form></td></tr>
</td></tr></table></td></tr><tr><td colspan="2" align="center">--- Placeholder text ---</td></tr></table>