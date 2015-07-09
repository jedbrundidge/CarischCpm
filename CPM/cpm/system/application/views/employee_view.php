<link type="text/css" href="/cpm/css/start/jquery-ui-1.7.2.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/cpm/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/cpm/js/jquery-ui-1.7.2.custom.min.js"></script>
<style type="text/css">
    #ui-datepicker-div
    {
        z-index: 9999999;
    }
</style>
<script type="text/javascript">
	$(function() {
		$("#from").datepicker({
			duration: '',
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
	$(function() {
		$("#to").datepicker({
			duration: '',
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
	
    function popup(mylink, windowname)
    {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, windowname, 'width=800,height=600,scrollbars=no');
        return false;
    }
    
    </script>

<table width="300">
	<tr>
		<td>
			<table width="100%" rules="none" cellpadding="0" cellspacing="0">
				<thead>
					<b>Actions</b>
				</thead>
				<tr> <?php
						$uid = $this->session->userdata('uid');
						$session_key = md5(time().$uid);
						$ip = getenv("REMOTE_ADDR");
						$create_session = $this->db->query("INSERT INTO `sessions` VALUES ($uid,'$session_key',CURRENT_TIMESTAMP,'$ip')");
						$this->db->query("DELETE FROM `sessions` WHERE `session_key` != '$session_key' AND `user_id` = $uid");
						if ($this->session->userdata('utype') <= 3) {
							$group_id=$this->db->query("SELECT `group_id` FROM `users` WHERE `user_id` = $uid");
							$group_id=$group_id->first_row()->group_id;
							$_stores=$this->db->query("SELECT `group_stores` AS `store`,`store_name` FROM `groups` LEFT JOIN `stores` ON `stores`.`store_id` = `group_stores` WHERE `idgroups` = $group_id ORDER BY `store_name`");
							$sel_options='';
							$cstore = $this->db->query("SELECT `store` FROM `employees` WHERE `uniq_id` = $e_id");
							$cstore = $cstore->last_row();
							$cstore = $cstore->store;
							foreach ($_stores->result() as $_store) {
								$_S = '';
								$_name=(int)$_store->store;
								if ($_store->store == $cstore) {
									$_S = ' SELECTED ';
								}
								$sel_options .= "<option $_S value=\"$_store->store\">$_store->store_name ($_name)</option>";
							}
					?>
					<td>
					<form name="transfer" method="POST" action="/cpm/index.php/personnel/trans_emp/<?=$e_id?>">
					Transfer to Store<br>
					<select name="employee_transfer" style="font-family: Arial;"><?=$sel_options?></select> <input style="font-family: Arial;" type="submit" name="submit" class="ui-corner-all" value="Submit">
					</form>
					</td>
					<?php } ?>
				</tr>
				<tr><td>
				<?php if ($this->session->userdata('utype') == 1) { 
					echo "<a href=\"/cpm/index.php/users/index/$e_id\">Edit User</a>";
					}
				?>
				</td></tr>
				
				<tr>
				<td>
					<?php
						 //echo "<a href=\"/cpm/index.php/personnel/load_form/$eid\">Load</a>";
					?>
				<td>
				</tr>
			</table>	
		</td>
	</tr>

	<tr>
		<td>

		<table width="100%" rules="none" cellpadding="0" cellspacing="0">
			<thead>
				<b>Employee Information</b>
			</thead>
			<?php

			//$employee_info = $this->db->query("SELECT `e_id`,`first_name`,`last_name`,SUBSTRING(`ssn_hash`,-4,4) AS `ssn`,`position`,`store`,`active`,`street_addr`,`city`,`state`,`zip` FROM `employees` JOIN `employee_info` ON `uniq_id` = `unique_id` WHERE `uniq_id` = $e_id");

			$employee_info = $this->db->query("
			SELECT  
				`e_id`,
				`first_name`,
				`last_name`,
				`middle_initial`,
				SUBSTRING(`ssn_hash`,-4,4) AS `ssn`,
				`active`,
				`position`,
				`uniq_id`,
				`store`,
				`street_addr`,
				`city`,
				`zip`,
				`employee_info`.`state`,
				`phone`,
				`dob`,
				`doh`,
				`dor`,
				`dop`,
				`dot`,
				`start_date`,
				`exemption`,
				`marital_status`,
				`allowances`,
				`additional_withholdings`,
				`gender`,
				`ethnicity`,
				`rate`,
				`pay_type`,
				`carisch_dimes`,
				`store_name`,
				`franchise_num`,
				`pms_num`,
				`family`,
				`submitter_id`,
				`stores`.`state` AS `store_state`,
				`stores`.`store_name` AS `store_name`
			FROM
				`employees`
			JOIN
				`employee_info`
				ON
					`employees`.`uniq_id` = `employee_info`.`unique_id`
			JOIN
				`employee_wage`
				ON
					`employees`.`uniq_id` = `employee_wage`.`unique_id`
			JOIN
				`stores`
				ON
					`employees`.`store` = `stores`.`store_id`
			WHERE
				`uniq_id` = $e_id
			ORDER BY `dor`
		");
			//$info = $info->first_row();
			$std_text_width = 19;



			$positions = array('GM','AM','SM','Crew');
			$result = $employee_info->last_row();
			
			if ($result->dot == '0000-00-00' && $result->active == 0) {
				$get_t = $this->db->query("SELECT `termination_date` FROM `terminations` WHERE `employee_id` = $e_id");
				if ($get_t->num_rows() > 0) {
					$result->dot = $get_t->last_row()->termination_date;
				}
				$get_r = $this->db->query("SELECT `warning_date` FROM `warnings` WHERE `employee_id` = $e_id");
				if ($get_r->num_rows() > 0) {
					$result->dot = $get_r->last_row()->warning_date;
				}
			}
			
			//foreach ($employee_info->result() as $result) {
			if ($this->session->userdata('utype') > 4) {
				$result->rate = "XXXXXXX";
				$result->dor = "XXXX/XX/XX";
			}
			$result->first_name = ucwords(strtolower($result->first_name));
			$result->last_name = ucwords(strtolower($result->last_name));
			switch ($result->position) {
				case 1:
					$pos = "Admin";
					break;
				case 3:
					$pos = "RM";
					break;
				case 4:
					$pos = "GM";
					break;
				case 5:
					$pos = "AM";
					break;
				case 6:
					$pos = "SM";
					break;
				case 8:
					$pos = "Crew";
					break;
			}
			if ($result->active == 0) {
				echo "<tr><td colspan=\"2\" align=\"center\"><a href=\"/cpm/index.php/personnel/reactivate/$e_id\">Reactivate</a></td></tr>";
			}

			echo "<tr><td>Name:</td><td>$result->first_name $result->last_name</td></tr>";
			echo "<tr bgcolor=\"dddddd\"><td>Carisch ID:</td><td>$result->e_id</td></tr>";
			echo "<tr><td>SSN:</td><td>XXX-XX-$result->ssn</td></tr>";
			echo "<tr bgcolor=\"dddddd\"><td>Position:</td><td>$pos";
			if ($this->session->userdata('utype') < 4 && $result->active == 1) echo "<font size=\"1\"> <a href=\"/cpm/index.php/personnel/promote/$e_id/$result->store/1\">Promote</a>"; /// <a href=\"/cpm/index.php/personnel/promote/$e_id/0\">Demote</a></font>
			echo "</td></tr>";
			//echo "<tr><td>Position:</td><td><select disabled>";
			//foreach ($positions as $vak) {
			//	echo "<option ";
			//	if ($pos == $vak) echo "selected";
			//	echo ">$vak</option>";
			//}
			$dis = "";
			if ($result->active == 0) {
				$dis = "disabled";
			}
			if ($this->session->userdata('utype') <= 4) {

				echo "<form name=\"edit_rate\" method=\"POST\" action=\"/cpm/index.php/personnel/set_rate/$e_id\">";
				echo "<tr><td>Pay Rate:</td><td><input type=\"text\" name=\"new_rate\" size=\"8\"  $dis value=\"$result->rate\"></input> "; // <font size=\"1\"><a href=\"/cpm/index.php/personnel/edit_wages/$e_id\" disabled>Rate Change</a></font>
				echo "<input type=\"submit\" size=\"10\" style=\"font-family: Arial; font-size: 10; height: 2em;width: 7em; border: dotted; border-width: 1px;\" value=\"Submit Rate\"  $dis ></td></tr>";
			} else {
				echo "<tr><td>Pay Rate</td><td>$result->rate</td>";
			}
			echo "<tr bgcolor=\"dddddd\"><td>Last Rate Change:</td><td>$result->dor</td></tr></form>";
			//echo "<tr><td colspan=\"2\" align=\"center\"><font size=\"1\"><a href=\"/cpm/index.php/personnel/edit_wages/$e_id\">Edit Wage Information</font></td></tr>";

			echo "</select></td></tr>"; ?>
			<tr>
				<td>Date of Hire:</td>
				<td><?=$result->doh?></td>
			</tr>
			<?php
			if ($this->session->userdata('utype') <= 3) { ?>
			<tr bgcolor="dddddd">
				<td>Date of Birth:</td>
				<td><?=$result->dob?></td>
			</tr>
			<?php
			} 
			if ($result->active == 0) {
				?>
				<tr>
				<td>Termination Date:</td>
				<td><?=$result->dot?></td>
				</tr>
				<?php
			}
			?>
			<form name="edit_employee" method="POST"
				action="/cpm/index.php/personnel/update_employee/<?=$e_id?>"><?php
				echo "<tr><td colspan=\"2\"><hr></td></tr>";
				if ($this->session->userdata('utype') <= 3) {
					echo "<tr><td>Promotion Date (yyyy-mm-dd):</td><td><input type=\"text\" $dis size=\"$std_text_width\" name=\"dop\" value=\"$result->dop\"></td></tr>";
				}
				echo "<tr bgcolor=\"dddddd\"><td>Street Addr:</td><td><input type=\"text\"   size=\"$std_text_width\" name=\"street_addr\" value=\"$result->street_addr\"></td></tr>";
				echo "<tr><td>City:</td><td><input type=\"text\" size=\"$std_text_width\" name=\"city\"    value=\"$result->city\"></td></tr>";
				echo "<tr bgcolor=\"dddddd\"><td>State:</td><td><input type=\"text\" maxlength=\"2\"   size=\"2\" name=\"state\" value=\"$result->state\">&nbsp;&nbsp;&nbsp;&nbsp; Zip: <input type=\"text\" size=\"5\" maxlength=\"5\"name=\"zip\"   value=\"$result->zip\"></td></tr>";
				//echo "<tr><td>Zip:</td><td></td></tr>";
				echo "<tr><td>Phone:</td><td><input type=\"text\" size=\"10\" name=\"phone\"   value=\"$result->phone\"></td></tr>";
				$m_options='';
				for ($i=0;$i<=2;$i++) {
					$s = '';
					switch ($i) {
						case 0:
							$d = "Single";
							break;
						case 1:
							$d = "Married";
							break;
						case 2:
							$d = "Married @ Single";
							break;
					}
					if ($result->marital_status == $i)
					$s = "SELECTED";
					$m_options .= "<option value=\"$i\" $s>$d</option>";
				}
				echo "<tr bgcolor=\"dddddd\"><td>Marital Status:</td><td><select name=\"marital_status\"  >$m_options</select></td></tr>";
				echo "<tr><td>Withholdings:</td><td><input type=\"text\" size=\"5\"   name=\"additional_withholdings\" value=\"$result->additional_withholdings\"></td></tr>";
				echo "<tr bgcolor=\"dddddd\"><td>Allowances:</td><td><input type=\"text\" size=\"3\"   name=\"allowances\" value=\"$result->allowances\"></td></tr>";
				//echo "<tr><td>Zip:</td><td><input type=\"text\" size=\"$std_text_width\" name=\"zip\" value=\"$result->zip\"></td></tr>";
				$exempt = '';
				if ($result->exemption == 1) {
					$exempt = "CHECKED";
				}
				//echo "<input type=\"hidden\" value=\"0\" name=\"exemption[]\">";
				echo "<tr><td>Exemption:</td><td><input style=\"border: none;\" type=\"checkbox\"  $dis $exempt value=\"1\" name=\"exemption[]\"></td></tr>";
				//echo "<tr><td>Street Addr:</td><td>$result->street_addr</td></tr>";
				//}
				?>
			
			
			<tr>
				<td align="center" colspan="2"><input type="submit" <?=$dis?> name="Commit"
					value="Submit Changes" class="ui-corner-all" style="font-family: Arial;"></td>
			</tr>
		</table>

		</td>
	</tr>
	<tr>
		<td>
		<table width="100%" rules="none" cellpadding="0" cellspacing="0">
			<thead>
				<b>Warning/Termination History</b>
			</thead>
			<td></td>
			<td>Date</td>
			<td align="right">By</td>
			<?php
			//$session_id = $this->session->userdata('session_id');
			$terms = $this->db->query("SELECT `first_name`,`last_name`,`termination_id`,DATE_FORMAT(`termination_date`,'%b %d, %Y') AS `termination_date`,`manager_id` FROM `terminations` JOIN `users` ON `manager_id` = `user_id` WHERE `employee_id` = $e_id AND `status` = 0");
			$warns = $this->db->query("SELECT `first_name`,`last_name`,`warning_id`,DATE_FORMAT(`warning_date`,'%b %d, %Y') AS `warning_date`,`submitting_user_id`,`terminated` FROM `warnings` JOIN `users` ON `submitting_user_id` = `user_id` WHERE `employee_id` = $e_id AND `status` = 0");
			if ($warns->num_rows() < 1 && $terms->num_rows() < 1) {
				echo "<tr><td colspan=\"3\" align=\"center\"><b>--- No Warnings/Terms ---</b></td></tr>";
			} else {
				if ($warns->num_rows > 0) {
					foreach ($warns->result() as $warn) {
						$_disp = "Warn";
						if ($warn->terminated == 1) {
							$_disp = "Term";
						}
						$name = ucwords(strtolower("$warn->first_name $warn->last_name"));
						echo "<tr><td>$_disp <a href=\"/cpm/index.php/personnel/warning/$warn->warning_id/1/$session_key\"  onClick=\"return popup(this, 'PDF')\"><img src=\"/cpm/pdf-document.png\" border=\"0\"></a></td><td align=\"left\">$warn->warning_date</td><td align=\"right\">$name</td></tr>";
					}
				}
				if ($terms->num_rows > 0) {
					foreach ($terms->result() as $term) {
						$name = ucwords(strtolower("$term->first_name $term->last_name"));
						echo "<tr><td>Term <a href=\"/cpm/index.php/personnel/term_pdf/$term->termination_id/$session_key\"  onClick=\"return popup(this, 'PDF')\"><img src=\"/cpm/pdf-document.png\" border=\"0\"></a></td><td align=\"left\">$term->termination_date</td><td align=\"right\">$name</td></tr>";
					}
				}
			}
			$file = $this->db->query("SELECT `last_i9` FROM `employee_info` WHERE `unique_id` = $e_id");
			$file = $file->first_row();
			if (!empty($file->last_i9)) {
				$existing_i9 = true;
			}
			?>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table width="100%" rules="none" cellpadding="0" cellspacing="0">
			<thead>
				<b>Notes</b>
				<font size="2">[<a
					href="/cpm/index.php/personnel/new_note/<?=$e_id?>">New</a>] [<a
					href="/cpm/index.php/personnel/view_notes/<?=$e_id?>">View All</a>]</font>
			</thead>
			<td></td>
			<td>Date</td>
			<td align="right">By</td>

			<?php

			$notes = $this->db->query("SELECT `note_id`,DATE_FORMAT(`date`,'%b %d, %Y') AS `date`,`date` AS `raw`,`first_name`,`last_name`,`subject` FROM `notes` JOIN `users` ON `manager` = `user_id` WHERE `emp_id` = $e_id ORDER BY `raw` DESC");
			if ($notes->num_rows() > 0) {
				foreach($notes->result() as $val) {
					$name = ucwords(strtolower("$val->first_name $val->last_name"));
					echo "<tr><td><a href=\"/cpm/index.php/personnel/view_note/$val->note_id\" title=\"$val->subject\" ><img src=\"/cpm/note.gif\" border=\"0\"></a></td><td>$val->date</td><td align=\"right\">$name</td></tr>";
					echo "<tr><td></td><td colspan=\"2\">$val->subject</td></tr>";
				}
			}
			?>

		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" rules="none" cellpadding="0" cellspacing="0">
			<thead>
				<b>RTI Access</b>
				<tr>
					<td>
						
					</td>
				</tr>
			</thead>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<table width="100%" rules="none" cellpadding="0" cellspacing="0">
			<thead>
				<b>View/Print Employment Forms</b>
			</thead>

			<tr bgcolor="dddddd">
				<td><a
					href="/cpm/index.php/personnel/pdf/<?=$e_id?>/9/0/<?=$session_key?>"
					onClick="return popup(this, 'PDF')"><img
					src="/cpm/pdf-document.png" border="0"></a><font color="green">W4</font></td>
			</tr>
			<tr>
				<td><a
					href="/cpm/index.php/personnel/pdf/<?=$e_id?>/7/0/<?=$session_key?>"
					onClick="return popup(this, 'PDF')"><img
					src="/cpm/pdf-document.png" border="0"></a><font color="green">I9

					<!-- CPMe1003: Below line was removed by request of Payroll.  Was a link that that pulled up an employee's original I9 form.
						<font size="1"><a href="/cpm/index.php/personnel/get_pdf/<?=$e_id?>"><?php if (isset($existing_i9)) echo "Update Existing"; ?></a></font>
					-->
					
					</font></td>
			</tr>
			<tr bgcolor="dddddd">
				<td><a
					href="/cpm/index.php/personnel/pdf/<?=$e_id?>/10/0/<?=$session_key?>"
					onClick="return popup(this, 'PDF')"><img
					src="/cpm/pdf-document.png" border="0"></a><font color="green">Deposit</font></td>
			</tr>
			<tr>
				<td><a
					href="/cpm/index.php/personnel/pdf/<?=$e_id?>/1.7.10.9.8850.11/0/<?=$session_key?>"
					onClick="return popup(this, 'PDF')"><img
					src="/cpm/pdf-document.png" border="0"></a><font color="green">All
				Forms</font></td>
			</tr>

			<tr bgcolor="dddddd">
				<!-- <td><img src="/cpm/pdf-document.png"><font color="green">All forms
				w/ Signoffs</font></td> -->
			</tr>
		</table>
	
	</tr>
	<?php if ($this->session->userdata('utype') <= 5) { ?>
	<tr><td>
	<table width="100%" rules="none" cellpadding="0" cellspacing="0">
		<thead>
			<b>Paystubs</b>
		</thead>
		</form>
		<form method="post" action="/cpm/index.php/personnel/get_ind_stubs">
		<tr>
		<td><input type="hidden" name="ids" id="ids" value="<?=$e_id?>" style="border: none;"/>Date Range: <input type="text" id="from" name="from" class="ui-corner-all" size="11"/> - <input type="text" id="to" name="to" class="ui-corner-all" size="11"/></td></tr>
		<tr height="1"><td height="1"></td></tr>
		<tr><td align="center"><input type="submit" value="Get Paystubs" class="ui-corner-all" /></td></tr>
		</form>
	</table></td>
	</tr>
	<?php } ?>
</table>

