<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<link rel="stylesheet" href="/cpm/spell_checker/spell_checker.css" type="text/css">
<script src="/cpm/spell_checker/mootools-1.2-core-yc.js"></script>
<script src="/cpm/spell_checker/spell_checker.js"></script>
<script src="/cpm/functions.js">
<script type="text/javascript">
    function checkForm() {
        sc_spellCheckers.resumeAll();
        return true;
    }
    

</SCRIPT>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
</head>
<body>
<table>
	<tr>
		<td>
		<table width="600" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
				<center><font size="3"><b>Voluntary Termination Form</b></font></center>
				</td>
			</tr>
		</table>
		<form name="form<?=$eid?>"
			onsubmit="return nonEmpty()" 
			action="/cpm/index.php/personnel/cvw/<?=$eid?>"
			method="post"><?php

			if ($this->session->userdata("F$eid")) {
				$array = $this->session->userdata("F$eid");
				foreach ($array as $key=>$val) {
					$$key = $val;
				}
			}

			?> <!-- <table width="600" cellpadding="0" border="0">
<table border="1" rules="groups" frame="box" width="598" cellpadding="3">
	<tr>
		<td><?php //echo drop_down_stores(); ?></td>
		<td colspan="3"><font color="red"> indicates required field.</font></td>
	</tr>
</table>
<br />

	<TR>
		<TD><!-- 
		<TABLE BORDER=1 RULES=GROUPS FRAME=BOX CELLPADDING=2>
			<TR>
			
							<TD>Employee ID<FONT COLOR=red>*</FONT></TD>
				<TD><INPUT TYPE="TEXT" NAME="employee_id" AUTOCOMPLETE="off" SIZE=15
					MAXLENGTH=6 onkeypress="return onlyNumbers();"></TD>
			</TR>
			<TR>
				<TD>Employee First Name<FONT COLOR=red>*</FONT></TD>
				<TD><INPUT TYPE="TEXT" NAME="employee_fname" AUTOCOMPLETE="off"
					SIZE=15 MAXLENGTH=28></TD>
			</TR>
			<TR>
				<TD>Employee Last Name<FONT COLOR=red>*</FONT></TD>
				<TD><INPUT TYPE="TEXT" NAME="employee_lname" AUTOCOMPLETE="off"
					SIZE=15 MAXLENGTH=28></TD>
		
		</TABLE>
		</TD>
		<TD>
		<TABLE BORDER=1 RULES=GROUPS FRAME=BOX ALIGN=RIGHT CELLPADDING=2>
			<TR>
				<TD>Manager ID<FONT COLOR=red>*</FONT></TD>
				<TD><INPUT TYPE="TEXT" NAME="manager_id" AUTOCOMPLETE="off" SIZE=15
					onkeypress="return onlyNumbers();"></TD>
			</TR>
			<TR>
				<TD>Manager First Name<FONT COLOR=red>*</FONT></TD>
				<TD><INPUT TYPE="TEXT" NAME="manager_fname" AUTOCOMPLETE="off"
					SIZE=15></TD>
			</TR>
			<TR>
				<TD>Manager Last Name<FONT COLOR=red>*</FONT></TD>
				<TD><INPUT TYPE="TEXT" NAME="manager_lname" AUTOCOMPLETE="off"
					SIZE=15></TD>
			</TR>
		</TABLE>
		</TD>
	</TR>
</TABLE> --> <input type="hidden" value="<?=$eid?>" name="eid">
		<TABLE BORDER="1" RULES="GROUPS" FRAME="BOX" WIDTH="600"
			CELLPADDING="1">
			<TR>

				<TD COLSPAN="2"><font size="2">Termination Date</font> <?php
				$prefix = "e".$eid;
				if(isset($month)) $e_month = $month;
				if(isset($day)) $e_day = $day;
				if(isset($year)) $e_year = $year;

				if (empty($e_month) || empty($e_day) || empty($e_year)) {
					$e_month = date('m');
					$e_year = date('Y');
					$e_day = date('d');
				}

				$cur_month=date('m');
				$cur_year=date('Y');
				$cur_day=date('d');


				for($i=01;$i<=12;$i++) {
					if ($i <= $cur_month || $e_year < $cur_year) {
						if ($cur_month != 1) {
							$mnth[$i] = date('F',mktime(0,0,0,$i,1));
						} elseif ($i == 12 || $i == 1) {
							$mnth[$i] = date('F',mktime(0,0,0,$i,1));
						}
					}
				}
				$option = "<select name=\"month\" onchange=\"Post('$eid');\">";
				foreach($mnth as $num=>$text) {
					if ($num<10) {
						$num="0".$num;
					}
					$option.="<option value=\"$num\"";
					if ($cur_month==$num && empty($e_month)) {
						$option.=" selected";
					} elseif ($num == $e_month) {
						$option.=" selected";
					}
					$option.=">$text</option>\n";
				}
				$option.="</select>";
				echo $option;


				$nod='';
				if (!empty($e_month)) {
					$nod=$e_month;
				} else {
					$nod=$cur_month;
				}
				if (isset($e_year)) {
					$ye=$e_year;
				} else {
					$ye=$cur_year;
				}
				$num_of_days=date('t',mktime(0,0,0,$nod,1,$ye));
				$day="<select name=\"day\" onchange=\"Post('$eid');\">";
				for ($t=1;$t<=$num_of_days;$t++) {
					if (($cur_month == $e_month && $cur_year == $e_year && $t <= $cur_day) || ($cur_month != $e_month || $cur_year != $e_year)) {
						if ($t<10) {
							$t="0".$t;
						}
						$day.="<option value=\"$t\"";
						if ($cur_day==$t && empty( $e_day )) {
							$day.=" selected";
						} elseif (!empty($e_day) && $e_day == $t) {
							$day.=" selected";
						}
						$day.=">$t</option>\n";
					}
				}
				$day.="</select>";
				echo $day;
				$goback=1;
				$year="<select name=\"year\" id=\"year\" onchange=\"Post('$eid');\">";
				for($i=$cur_year;$i>=($cur_year-$goback);$i--) {
					$year.="<option value=\"$i\"";
					if ($cur_year==$i && empty($e_year)) {
						$year.=" selected";
					} elseif (!empty($e_year) && $e_year == $i) {
						$year.=" selected";
					}
					$year.=">$i</option>\n";
				}
				$year.="</select>";
				echo $year;
				?></TD>
				<TD COLSPAN="2"><font size="2">Last Day</font> <?php
				if(isset($lmonth)) $l_month = $lmonth;
				if(isset($lday)) $l_day = $lday;
				if(isset($lyear)) $l_year = $lyear;

				if (empty($l_month) || empty($l_day) || empty($l_year)) {
					$l_month = date('m');
					$l_year = date('Y');
					$l_day = date('d');
				}

				$cur_month=date('m');
				$cur_year=date('Y');
				$cur_day=date('d');


				for($i=01;$i<=12;$i++) {
					if ($i <= $cur_month || $l_year < $cur_year) {
						if ($cur_month != 1) {
							$mnth[$i] = date('F',mktime(0,0,0,$i,1));
						} elseif ($i == 12 || $i == 1) {
							$mnth[$i] = date('F',mktime(0,0,0,$i,1));
						}
					}
				}
				$option = "<select name=\"lmonth\" onchange=\"Post('$eid');\">";
				foreach($mnth as $num=>$text) {
					if ($num<10) {
						$num="0".$num;
					}
					$option.="<option value=\"$num\"";
					if ($cur_month==$num && empty($l_month)) {
						$option.=" selected";
					} elseif ($num == $l_month) {
						$option.=" selected";
					}
					$option.=">$text</option>\n";
				}
				$option.="</select>";
				echo $option;


				$nod='';
				if (!empty($l_month)) {
					$nod=$l_month;
				} else {
					$nod=$cur_month;
				}
				if (isset($l_year)) {
					$ye=$l_year;
				} else {
					$ye=$cur_year;
				}
				$num_of_days=date('t',mktime(0,0,0,$nod,1,$ye));
				$day="<select name=\"lday\" onchange=\"Post('$eid');\">";
				for ($t=1;$t<=$num_of_days;$t++) {
					if (($cur_month == $l_month && $cur_year == $l_year && $t <= $cur_day) || ($cur_month != $l_month || $cur_year != $l_year)) {
						if ($t<10) {
							$t="0".$t;
						}
						$day.="<option value=\"$t\"";
						if ($cur_day==$t && empty( $l_day )) {
							$day.=" selected";
						} elseif (!empty($l_day) && $l_day == $t) {
							$day.=" selected";
						}
						$day.=">$t</option>\n";
					}
				}
				$day.="</select>";
				echo $day;
				$goback=1;
				$year="<select name=\"lyear\" id=\"lyear\" onchange=\"Post('$eid');\">";
				for($i=$cur_year;$i>=($cur_year-$goback);$i--) {
					$year.="<option value=\"$i\"";
					if ($cur_year==$i && empty($l_year)) {
						$year.=" selected";
					} elseif (!empty($l_year) && $l_year == $i) {
						$year.=" selected";
					}
					$year.=">$i</option>\n";
				}
				$year.="</select>";
				echo $year;
				?></TD>
			</TR>
			<!-- 
	<TD>Employee Separation is:</TD>
	<TD><input type="radio" name="separation" value="voluntary">Voluntary</TD>
	<TD><input type="radio" name="separation" value="Involuntary">Involuntary
	</TD>
	</tr> -->
			<tr>
				<td colspan="100">Reason: <select name="reason" style="font-family: Arial;">
				
				<?php 
				$reasons = $this->db->query("SELECT `reason_code`,`reason_name`,`reason_desc` FROM `termination_definitions` WHERE `voluntary` = 1");
				foreach ($reasons->result() as $val) {
					echo "<option value=\"$val->reason_code\">$val->reason_name - $val->reason_desc</option>";
				}
				?>
				</select></td>
			</tr>
		</table>
		<br />
		<table border="1" rules="groups" frame="box" width="600"
			cellpadding="2">
			<THEAD>
				<TR>
					<TD>
					<CENTER>Describe any details you have about the circumstances
					regarding the termination of employment. List any witnesses to any
					incidents if applicable.<FONT COLOR=red></FONT></CENTER>
					</TD>
				</TR>
			</THEAD>
			<tr>
				<td><textarea name="term_details" class="spell_check" ROWS="20" COLS="71" AUTOCOMPLETE=\"off\"><?php if (isset($term_details)) echo $term_details; ?></textarea>
				</td>
			</tr>
		</table>
		<br>
		<table border="1" rules="groups" frame="box" width="600"
			cellpadding="2">
			<tr><td colspan="100" align="center"><font color="red">*** If less than 2 weeks notice was given, employee is not considered rehirable. ***</font></tr>
			<tr>
				<td width="220">Would you rehire this employee?</td>
				<td width="48"><input type="radio" id="rehire" name="rehire" value="Yes"
					style="border: 0"
					<?php if (isset($rehire) && $rehire == "Yes") echo "checked"; ?>>Yes</td>
				<td width="48"><input type="radio" id="rehire" name="rehire" value="No"
					style="border: 0"
					<?php if (isset($rehire) && $rehire == "No") echo "checked"; ?>>No</td>
				<td width="125"><input type="radio" id="rehire" name="rehire"
					value="Conditionally" style="border: 0"
					<?php if (isset($rehire) && $rehire == "Conditionally") echo "checked"; ?>>Conditionally
				</td>
				<td></td>
				<td></td>
			</tr>
			
			<tr>
				<td colspan="6">If not, why? <input type="text"
					name="no_rehire_desc" id="no_rehire_desc" size="60"
					value="<?php if (isset($no_rehire_desc)) echo $no_rehire_desc; ?>"></td>
			</tr>
			<tr>
				<td colspan="2" width="300">Has employee turned in all company
				property?</td>
				<td width="48"><input type="radio" name="turned_in_prop" value="Yes"
					style="border: 0"
					<?php if (isset($turned_in_prop) && $turned_in_prop == "Yes") echo "checked"; ?>>Yes</td>
				<td width="48"><input type="radio" name="turned_in_prop" value="No"
					style="border: 0"
					<?php if (isset($turned_in_prop) && $turned_in_prop == "No") echo "checked"; ?>>No</td>
				<td></td>
				<td></td>
			</tr>
		</table>
		
		</td>
	</tr>
	<tr>
		<td>
		<table width="600">
			<tr>
				<td>
				<center><input type="submit" id="submit" name="submit"></center>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>
