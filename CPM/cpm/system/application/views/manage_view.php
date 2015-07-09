

<?php
$uid = $this->session->userdata('uid');
if ($uid) {
	$stores = $this->cpm_functions->verify_access($uid);
	foreach ($stores as $val) {
		//$this->db->query();
		//echo $val."<br>";
	}
}

?>
<!--<table width="338">
	<tr>
		<td valign="top" colspan="3"><img src="/cpm/box-exclaim.gif"> This software is in testing and will change often. I
		urge you to contact me with bugs, suggestions, feedback and
		complaints. The more input from users I get the more useful and
		stable this will become.</td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr>
		<td></td>
		<td width="200"></td>
		<td>David Martin</td>
	</tr>
	<tr>
		<td></td>
		<td width="200"></td>
		<td>david@carischinc.com</td>
	</tr>
	<tr>
		<td></td>
		<td width="200"></td>
		<td>952-476-7854</td>
	</tr>

</table>-->
<br />
<?php 
if ($this->session->userdata('utype') <= 2) {
	echo "<a href=\"/cpm/index.php/personnel/basic_report\">BASIC Reporting</a><br /><br />";	
}
?>
<table>
	<!--
	<tr>
		<td>
		<table border="0" width="330">
			<thead>
				<font face="Arial"><strong>Actions</strong></font>
			</thead>
			<tr>
				<td><a href="/cpm/index.php/personnel/new_hire"><img
					src="/cpm/user-plus.gif" alt="New Hire" border="0"></a></td>
				<td>New Hire</td>
			</tr>
			<tr>
				<td><a href="/cpm/index.php/personnel/new_warning"><img
					src="/cpm/user-edit.gif" alt="New Warning" border="0"></a></td>
				<td>New Warning/Disciplinary Termination</td>
			</tr>
			<tr>
				<td><a href="/cpm/index.php/personnel/termination"><img
					src="/cpm/user-delete.gif" alt="New Termination"
					border="0"></a></td>
				<td>New Termination</td>
			</tr>
			<tr>
				<td><a href="/cpm/index.php/personnel/employee"><img
					src="/cpm/user-blue.gif"
					alt="View/Edit Employee Information" border="0"></a></td>
				<td>View/Edit Employee</td>
			</tr>
			<tr>
				<td><a href="/cpm/index.php/personnel/rti"><img
					src="/cpm/graph-edit.gif" alt="RTI Credentials"
					border="0"></a></td>
				<td>View/Edit RTI Access</td>
			</tr>
		</table>
		</td>
	</tr>
	-->
	<tr>
		<td><?php
		$utype = $this->db->query("SELECT `user_type` FROM `users` WHERE `user_id` = $uid");
		$utype = $utype->first_row();
		$utype = $utype->user_type;
		$pending = $this->db->query("SELECT `status`,`employee_id`,`warning_id`,`store`,DATE_FORMAT(`warning_date`,'%b %d') AS `warning_date`,`submitting_user_id`,`requested_reviewer` FROM `warnings` WHERE `status` = 1 OR `status` = 2 OR `status` = 3 OR `status` = 4 ORDER BY `warning_date` DESC");
		if ($pending->num_rows() > 0) {
			foreach ($pending->result() as $key=>$val) {
				$access = false;
				$access = $this->cpm_functions->verify_access($uid,$val->store);
				$mng = false;
				$uid_array = explode(',',$val->requested_reviewer);
				foreach ($uid_array as $id) {
					if ($id == $uid || ($access === true && $utype != 1)) {
						$sid = $val->store;
						$emp_id = $val->employee_id;
						$suid = $val->submitting_user_id;
						$store_name = $this->db->query("SELECT `store_name` FROM `stores` WHERE `store_id` = $sid");
						$store_name = $store_name->first_row();
						$emp_name = $this->db->query("SELECT `first_name`,`last_name` FROM `employees` WHERE `uniq_id` = $emp_id");
						$emp_name = $emp_name->first_row();
						$emp_name = strtolower("$emp_name->first_name $emp_name->last_name");
						if ($access && ($utype == 4 || $utype == 5)) {
							$mng = true;
						}
						$pending_warnings['W'.$val->warning_id] = array ("t" => 'W', "warn_id" => $val->warning_id, "store" => $store_name->store_name,"employee_id" => $val->employee_id,"date" => $val->warning_date,"emp_name" => $emp_name,"requested_user" => $val->requested_reviewer, "status" => $val->status, "manager" => $mng);
					}
				}
			}
		}

		$terms_pending = $this->db->query("SELECT `status`,`termination_id`,`employee_id`,`store`,`manager_id`,`requested_reviewer`,DATE_FORMAT(`termination_date`,'%b %d') AS `termination_date` FROM `terminations` WHERE `status` = 1 OR `status` = 2 OR `status` = 3 OR `status` = 4");
		$rr='';
		if ($terms_pending->num_rows() > 0) {
			foreach ($terms_pending->result() as $key=>$val) {
				$access = $this->cpm_functions->verify_access($uid,$val->store);
				$mng = false;
				$uid_array = explode(',',$val->requested_reviewer);
				foreach ($uid_array as $id) {
					if ($id == $uid || ($access === true && $utype != 1)) {
						$sid = $val->store;
						$emp_id = $val->employee_id;
						$suid = $val->manager_id;
						$regional_uid = $this->db->query("SELECT `idgroups` FROM `groups` WHERE `group_stores` = $sid AND `idgroups` != 14 AND `idgroups` != 18");
						foreach ($regional_uid->result() as $reg) {
							$rrid = $this->db->query("SELECT `user_id` FROM `users` WHERE `group_id` = '$reg->idgroups' AND user_type = 3");
							foreach ($rrid->result() as $_id) {
								$rr = $_id->user_id;
							}
						}
	
						$store_name = $this->db->query("SELECT `store_name` FROM `stores` WHERE `store_id` = $sid");
						$store_name = $store_name->first_row();
						$store_name = $store_name->store_name;
						$emp_name = $this->db->query("SELECT `first_name`,`last_name` FROM `employees` WHERE `uniq_id` = $emp_id");
						$emp_name = $emp_name->first_row();
						$emp_name = strtolower("$emp_name->first_name $emp_name->last_name");
						if ($access && ($utype == 4 || $utype == 5)) {
							$mng = true;
						}
						$pending_terminations['T'.$val->termination_id] = array ("t" => 'T', "term_id" => $val->termination_id, "store" => $store_name,"employee_id" => $val->employee_id,"date" => $val->termination_date,"emp_name" => $emp_name,"requested_user" => $val->requested_reviewer,"status" => $val->status, "manager" => $mng);
					}
				}
			}
		}

		$reaction = " style=\"cursor: hand;\" onmouseover=\"this.style.color='#3333FF'\" onmouseout=\"this.style.color='#000000'\" onclick=\"location.replace('/cpm/index.php/personnel";
		echo "<table border=\"1\" width=\"350\" rules=\"cols\" cellpadding=\"1\" cellspacing=\"0\">";
		echo "<thead><font face=\"Arial\"><strong>Pending Reviews and Alerts</strong></font></thead>";

		if (isset($pending_warnings) && isset($pending_terminations)) {
			$merge = array_merge((array)$pending_warnings,(array)$pending_terminations);
			usort($merge, array("Personnel","cmpx"));
			
			$n=0;
			foreach ($merge as $key=>$val) {
				if ($n & 1) {
					$color = "dddddd";
				} else {
					$color = "ffffff";
				}
				if ($val['t'] == 'W') {
					$icon = "<img src=\"/cpm/user-edit.gif\" border=\"0\" alt=\"Warning/Disciplinary Termination\">";
					$type = 'warning';
					$id = $val['warn_id'];
				}
				if ($val['t'] == 'T') {
					$icon = "<img src=\"/cpm/user-delete.gif\" border=\"0\" alt=\"Voluntary Termination\">";
					$type = 'termination';
					$id = $val['term_id'];
				}
				$display = "<td>$icon</td><td>".$val['date']."</td><td>".ucwords($val['emp_name'])."</td><td>".$val['store']."</td>";
				if ($val['status'] == 1 && !$val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/$type/$id');\">$display</tr>";
				} elseif ($val['status'] == 2 && $val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/$type/$id');\">$display</tr>";
				}  elseif ($val['status'] == 3 && $val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/$type/$id/1');\">$display</tr>";
				} elseif ($val['status'] == 4 && $val['requested_user'] == $uid) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/$type/$id');\">$display</tr>";
				}
				echo "</tr>";
			}
		} elseif (isset($pending_warnings)) {
			$n=0;
			foreach ($pending_warnings as $key=>$val) {
				if ($n & 1) {
					$color = "dddddd";
				} else {
					$color = "ffffff";
				}
				$oid = $val['warn_id'];
				if ($val['t'] == 'W') {
					$icon = "<img src=\"/cpm/user-edit.gif\" border=\"0\" alt=\"Warning/Disciplinary Termination\">";
					$type = 'warning';
					$id = $val['warn_id'];
				}
				$display = "<td><img src=\"/cpm/user-edit.gif\" border=\"0\" alt=\"Warning/Disciplinary Termination\"></td><td>".$val['date']."</td><td>".ucwords($val['emp_name'])."</td><td>".$val['store']."</td>";
				if ($val['status'] == 1 && !$val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/warning/$oid');\">$display</tr>";
				} elseif ($val['status'] == 2 && $val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/warning/$oid');\">$display</tr>";
				}  elseif ($val['status'] == 3 && $val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/warning/$oid/1');\">$display</tr>";
				} elseif ($val['status'] == 4 && isset($val['requested_user']) && $val['requested_user'] == $uid) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/$type/$id');\">$display</tr>";
				}
				echo "</tr>";
			}
		} elseif (isset($pending_terminations)) {
			$n=0;
			foreach ($pending_terminations as $key=>$val) {
				if ($n & 1) {
					$color = "dddddd";
				} else {
					$color = "ffffff";
				}
				$oid = $val['term_id'];
				$icon = "<img src=\"/cpm/user-delete.gif\" border=\"0\" alt=\"Voluntary Termination\">";
				$type = 'termination';
				$id = $val['term_id'];
				$display = "<td><img src=\"/cpm/user-delete.gif\" border=\"0\" alt=\"Voluntary Termination\"></td><td>".$val['date']."</td><td>".ucwords($val['emp_name'])."</td><td>".$val['store']."</td>";
				if ($val['status'] == 1 && !$val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/termination/$oid');\">$display</tr>";
				} elseif ($val['status'] == 2 && $val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/termination/$oid');\">$display</tr>";
				} elseif ($val['status'] == 3 && $val['manager']) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/termination/$oid/1');\">$display</tr>";
				} elseif ($val['status'] == 4 && $val['requested_user'] == $uid) {
					$n++;
					echo "<tr bgcolor=\"$color\" $reaction/$type/$id');\">$display</tr>";
				}
				echo "</tr>";
			}
		}

		echo "</table>";
		?></td>
	</tr>
	<tr>
		<td>
		<table width="350" rules="none" cellpadding="1" cellspacing="0">
			<thead>
				<font face="Arial"><strong>Locations</strong></font>
			</thead>
			<?php
			$stores = $this->cpm_functions->verify_access($uid);
			$n=0;
			foreach ($stores as $val) {
				if ($val != 0) {
					$n++;
					if ($n & 1) {
						$color = "dddddd";
					} else {
						$color = "ffffff";
					}
					$store_name = $this->db->query("SELECT `store_name`,`region` FROM `stores` WHERE `store_id` = $val");
					$store_name = $store_name->first_row();
					echo "<tr bgcolor=\"$color\" $reaction/view_employees/$val/1');\"><td>$val</td><td>$store_name->store_name</td><td></td></tr>";
				}
			}
			?>
		</table>
		</td>
	</tr>
</table>
{memory_usage}
