
<style type="text/css">
table {
	border: 1px solid #B0B0B0
}

td,tr {
	border: 0
}
</style>
<script src="/cpm/functions.js">
</script>
<table><tr><td>
<a href="/cpm/index.php/personnel/valssn"><img
	src="/cpm/user-plus.gif" border="0"
	alt="Add Employee (New Hire Process)"></a>New Employee
</td></tr><tr><td>
<table border="0" rules="none" cellpadding="1" cellspacing="0" width="100%">
<form name="stubs" method="POST" action="/cpm/index.php/personnel/getstubs">
<?php
$n=0;
$uid = $this->session->userdata('uid');

if (isset($data)) {
	foreach ($data as $array) {
		$p=0;
		foreach ($array as $key=>$val) {
			$arr = explode(",",$val);
			$val = $arr[0];
			$position = $arr[1];
			$n++;
			if ($n & 1) {
				$color = "dddddd";
			} else {
				$color = "ffffff";
			}
			
			
			$active = $this->db->query("SELECT `active` FROM `employees` WHERE `uniq_id` = $key");
			$active = $active->first_row();
			$active = $active->active;
                     $flag = "<font color=\"green\">A";
			if ($position == 4)  {
                            $flag = "<font color=\"green\">GM";
                     }
			if ($position == 5)  {
                            $flag = "<font color=\"green\">AM";
                     }
			if ($position == 6)  {
                            $flag = "<font color=\"green\">SM";
                     }
			if ($position == 8)  {
                            $flag = "<font color=\"green\">Crew";
                     }
			if (!$active) {
				$flag = "<font color=\"red\">T";
			}
			$pending = $this->db->query("SELECT `requested_reviewer`,`warning_id` FROM `warnings` WHERE `employee_id` = $key AND `status` = 1");
			if ($pending->num_rows() > 0) {
				foreach ($pending->result() as $pid) {
					$rr = explode(",",$pid->requested_reviewer);
					$warning_id = $pid->warning_id;
					foreach ($rr as $reviewer) {
						if ($reviewer == $uid) {
							$p = 1;
						}
					}
				}
			}
			$val = ucwords(strtolower($val));
			echo "<tr bgcolor=\"$color\">";
			echo "<td>$flag</td>";
			
			if ($active) {
				echo "<td><input type=\"checkbox\" id=\"ids\" style=\"border: none;\" name=\"ids[]\" value=\"$key\"></td>";
				echo "<td><a href=\"/cpm/index.php/personnel/new_warning/$key\"><img src=\"/cpm/user-edit.gif\" border=\"0\" alt=\"Warning/Disciplinary Termination\"></a></td>";
				echo "<td><a href=\"/cpm/index.php/personnel/new_termination/$key\"><img src=\"/cpm/user-delete.gif\" border=\"0\" alt=\"Voluntary Termination\"></td>";
				if ($position <= 6 && $position >= 4) {
					//<a href=\"/cpm/index.php/personnel/rti\"></a>
					//echo "<td><img src=\"/cpm/graph-edit.gif\" border=\"0\" alt=\"Edit RTI Access\"></td>";
					echo "<td></td>";
				} else {
					echo "<td></td>";
				}
			} else {
				echo "<td><input type=\"checkbox\" id=\"ids\" style=\"border: none;\" name=\"ids[]\" value=\"$key\"></td><td></td><td></td><td></td>";
			}
			echo "<td><a href=\"/cpm/index.php/personnel/employee/$key\"><img src=\"/cpm/user-blue.gif\" border=\"0\" alt=\"View/Edit Employee Information\"></td><td width=\"14\">";
			echo "</td><td width=\"14\">";
			if ($p) {
				echo "<a href=\"/cpm/index.php/personnel/warning/$warning_id\"><img src=\"/cpm/box-exclaim.gif\" border=\"0\" alt=\"Pending Documents\"></a>";
			}
			echo "</td><td>$val";
			echo "</td></tr>";
		}
	}
}
?>
</table>
<?php
$sid = $info['sid'];
if ($info['terms'])
	echo "<font size=\"1\"><a href=\"/cpm/index.php/personnel/view_employees/$sid/0\">Show Inactives</a> | ";
else
	echo "<font size=\"1\"><a href=\"/cpm/index.php/personnel/view_employees/$sid/1\">Hide Inactives</a> | ";

echo "Select [<a href=\"javascript:checked(true);\">All</a>] / [<a href=\"javascript:checked(false);\">None</a>]";
?>
<br>

<?php if ($this->session->userdata('utype') <= 5) {
$periods = $this->db->query("SELECT DISTINCT(`doc_date`),DATE_FORMAT(`doc_date`,'%b %d, %Y') AS `fdate` FROM `payroll_docs` ORDER BY `doc_date` DESC");
$select_periods="";
foreach ($periods->result() as $doc_periods) {
	$select_periods .= "<option value=\"$doc_periods->doc_date\">$doc_periods->fdate</option>";
}
$i = $this->db->query("SELECT `eid` FROM `users` WHERE `user_id` = $uid");
$i = $i->first_row();

echo "<table width=\"100%\"><tr><td>
<input type=\"checkbox\" style=\"border: none;\" name=\"ids[]\" id=\"ids\" value=\"$i->eid\">".ucwords(strtolower($this->session->userdata('uname')))."
<br>Select employees you wish to print<br>
pay stubs for.  Then select the correct<br>" .
"check date and click the button below.<br>
<center><select name=\"dp\">$select_periods</select> <input type=\"submit\" name=\"Get Stubs\" value=\"Get Stub(s)\"></center>
</form></td></tr></table>";
} 
?><br>
<?php if ($this->session->userdata('utype') <= 3) { ?>
<table width="100%"><tr><td><a href="/cpm/index.php/personnel/basics/<?=$sid?>">Add/View BASIC</a></td><tr></table>
<br>
<?php } ?>
<table width="100%"><tr><td><img src="/cpm/user-blue.gif"></td><td>View/Edit User Information<br></td></tr>
<tr><td><img src="/cpm/user-edit.gif"></td><td>Warning/Disciplinary Termination<br></td></tr>
<tr><td><img src="/cpm/user-delete.gif"></td><td>Voluntary Termination<br></td></tr>
<!-- <tr><td><img src="/cpm/graph-edit.gif"></td><td>RTI Credentials<br></td></tr> -->
</table>
</td></tr>
</table>