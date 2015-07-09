<link rel="stylesheet" href="/cpm/default.css" type="text/css">
<script
	language="javascript" src="/cpm/functions.js"></script>
<script language="javascript">
function edit(opening,closing) {
	var form = document.getElementById('formhours').elements;
	for(var i=0;i<form.length;i++) {
		if ((form[i].name == opening || form[i].name == closing || (opening == 'all' && closing == 'all')) && (form[i].type == "select-one")) {
			if (form[i].name != 'day' && form[i].name != 'month' && form[i].name != 'year') {
				document.getElementById(form[i].name).disabled = !document.getElementById(form[i].name).disabled;
			}
		}
	}
}

function repost() {
	var info;
	var curl = location.href;
	var form = document.getElementById('formhours').elements;
	for(var i=0;i<form.length;i++) {
		if ((form[i].checked && form[i].type == "radio") || form[i].type != "radio") {
			info += "&" + form[i].name + "=" + form[i].value;
		}
	}
	//info += "&fid=" + fid;
	try {
		var xmlhttp = new XMLHttpRequest();
	} catch (e) {
		try {
			var xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (e) {
			//document.write('get a decent browser');
		}
	}
	
	xmlhttp.onreadystatechange = handler;
	xmlhttp.open("POST","/cpm/index.php/personnel/proc_hours", true);
	xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xmlhttp.send(info);
	
	function handler() {
		if(xmlhttp.readyState == 4 && xmlhttp.responsetext == "1") {
			location.href = curl;
		}
	}
}
</script>

<?php
function date_sel($allow_future,$goback = 100,$mth,$d,$y) {
	$date_select = array();
	$cur_month=date('m');
	$cur_year=date('Y');
	for($i=01;$i<=12;$i++) {
		$mnth[$i] = date('M',mktime(0,0,0,$i));
	}
	$option = "<select name=\"month\" onchange=\"return repost();\">";
	foreach($mnth as $num=>$text) {
		if ($num<10) {
			$num="0".$num;
		}
		$option.="<option value=\"$num\"";
		if ($cur_month==$num && !isset($mth)) {
			$option.=" selected";
		} elseif (isset($mth) && $mth == $num) {
			$option.=" selected";
		}
		$option.=">$text</option>\n";
	}
	$option.="</select>";
	$date_select['month'] = $option;
	$cur_day=date('d');
	$nod='';
	if (isset($mth)) {
		$nod=$mth;
	} else {
		$nod=$cur_month;
	}
	if (isset($y)) {
		$ye=$y;
	} else {
		$ye=$cur_year;
	}
	$num_of_days=date('t',mktime(0,0,0,$nod,1,$ye));
	$day="<select name=\"day\" onchange=\"return repost();\">";
	for ($t=1;$t<=$num_of_days;$t++) {
		if ($t<10) {
			$t="0".$t;
		}
		$day.="<option value=\"$t\"";
		if ($cur_day==$t && !isset( $d )) {
			$day.=" selected";
		} elseif (isset($d) && $d == $t) {
			$day.=" selected";
		}
		$day.=">$t</option>\n";
	}
	$day.="</select>";
	$date_select['day'] = $day;
	$year="<select name=\"year\" id=\"year\" onchange=\"return repost();\">";
	for($i=$cur_year;$i<=($cur_year-$goback);$i++) {
		$year.="<option value=\"$i\"";
		if ($cur_year==$i && !isset($y)) {
			$year.=" selected";
		} elseif (isset($y) && $y == $i) {
			$year.=" selected";
		}
		$year.=">$i</option>\n";
	}
	$year.="</select>";
	$date_select['year'] = $year;

	return $date_select;
}
if ($_POST && $_POST['go'] == 'Submit') {
	require_once "/var/www/htdocs/forms/swift/Swift.php";
	require_once "/var/www/htdocs/forms/swift/Swift/Connection/SMTP.php";
	$days = array ('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
	$message  = "Store ID: " . $_POST['store'] . "\n";
	$message .= "Employee ID: " . $_POST['employee'] . "\n";
	$message .= "Name: " . $_POST['name'] . "\n\n";
	$message .= "Starting: " . $_POST['month'] . "/" . $_POST['day'] . "/" . $_POST['year'] . "\n\n";
	foreach ($days as $day) {
		if (isset($_POST[$day]) && $_POST[$day] == 'on') {
			$message .= ucwords($day).": ".$_POST[$day.'_opening']." - ".$_POST[$day.'_closing']."\n";
		}
	}
	$subject = "Store Hours Change";
	$body =& new Swift_Message($subject,$message);
	$smtp =& new Swift_Connection_SMTP("192.168.0.6",25);
	$swift =& new Swift($smtp);
	$swift->send($body,"techsupport@carischinc.com","forms@carischinc.com");
	echo "Your request has been received.";
} else {
	?>
<body onload="edit('all','all')">
<form name="formhours" method="POST">
<table>
	<tr>
		<td>
		<table cellpadding="4" cellspacing="0" width="269">
			<tr>
				<td colspan="2"><?php
				/*
				 $xday = $zday;
				 $xmonth = $zmonth;
				 $xyear = $zyear;
				 */
				$xmonth = date('m');
				$xday = date('d');
				$xyear = date('Y');
				if ($this->session->userdata('f')) {
					foreach ($this->session->userdata('f') as $key=>$val) {
						$$key = $val;
					}
					$xmonth = $month;
					$xday = $day;
					$xyear = $year;
				}

				$dateselect =  date_sel(1,-1,$xmonth,$xday,$xyear);
				echo "Starting: " . $dateselect['month'] . $dateselect['day'] . $dateselect['year'];
				?></td>
			</tr>
			<tr bgcolor="dddddd">
				<td>Store ID: <input type="text" name="store" size="3" maxlength="3"></td>
				<td>Employee ID: <input type="text" name="employee" size="6"
					maxlength="6"></td>
			</tr>
			<tr>
				<td colspan="2">Your Name: <input type="text" name="name" size="20"
					maxlength="35"></td>
			</tr>
		</table>
		<br />
		<table cellpadding="4" cellspacing="0" width="269">
			<tr bgcolor="dddddd">
				<td><input type="checkbox" name="sunday" style="border: none;"
					onclick="edit('sunday_opening','sunday_closing')">Sunday</td>
				<td><select name="sunday_opening" id="sunday_opening" style="">
					<option value="0"></option>
					<option value="6:00 AM">6:00</option>
					<option value="6:30 AM">6:30</option>
					<option value="7:00 AM">7:00</option>
					<option value="7:30 AM">7:30</option>
					<option value="8:00 AM">8:00</option>
					<option value="8:30 AM">8:30</option>
					<option value="9:00 AM">9:00</option>
					<option value="9:30 AM">9:30</option>
					<option value="10:00 AM">10:00</option>
					<option value="10:30 AM">10:30</option>
					<option value="11:00 AM">11:00</option>
				</select></td>
				<td>-</td>
				<td><select name="sunday_closing">
					<option value="0"></option>
					<option value="6:00 PM">6:00</option>
					<option value="6:30 PM">6:30</option>
					<option value="7:00 PM">7:00</option>
					<option value="7:30 PM">7:30</option>
					<option value="8:00 PM">8:00</option>
					<option value="8:30 PM">8:30</option>
					<option value="9:00 PM">9:00</option>
					<option value="9:30 PM">9:30</option>
					<option value="10:00 PM">10:00</option>
					<option value="10:30 PM">10:30</option>
					<option value="11:00 PM">11:00</option>
					<option value="11:30 PM">11:30</option>
					<option value="12:00 AM">12:00</option>
					<option value="12:30 AM">12:30</option>
					<option value="1:00 AM">1:00</option>
					<option value="1:30 AM">1:30</option>
					<option value="2:00 AM">2:00</option>
				</select></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="monday" style="border: none;"
					onclick="edit('monday_opening','monday_closing')">Monday</td>
				<td><select name="monday_opening">
					<option value="0"></option>
					<option value="6:00 AM">6:00</option>
					<option value="6:30 AM">6:30</option>
					<option value="7:00 AM">7:00</option>
					<option value="7:30 AM">7:30</option>
					<option value="8:00 AM">8:00</option>
					<option value="8:30 AM">8:30</option>
					<option value="9:00 AM">9:00</option>
					<option value="9:30 AM">9:30</option>
					<option value="10:00 AM">10:00</option>
					<option value="10:30 AM">10:30</option>
					<option value="11:00 AM">11:00</option>
				</select></td>
				<td>-</td>
				<td><select name="monday_closing">
					<option value="0"></option>
					<option value="6:00 PM">6:00</option>
					<option value="6:30 PM">6:30</option>
					<option value="7:00 PM">7:00</option>
					<option value="7:30 PM">7:30</option>
					<option value="8:00 PM">8:00</option>
					<option value="8:30 PM">8:30</option>
					<option value="9:00 PM">9:00</option>
					<option value="9:30 PM">9:30</option>
					<option value="10:00 PM">10:00</option>
					<option value="10:30 PM">10:30</option>
					<option value="11:00 PM">11:00</option>
					<option value="11:30 PM">11:30</option>
					<option value="12:00 AM">12:00</option>
					<option value="12:30 AM">12:30</option>
					<option value="1:00 AM">1:00</option>
					<option value="1:30 AM">1:30</option>
					<option value="2:00 AM">2:00</option>
				</select></td>
			</tr>
			<tr bgcolor="dddddd">
				<td><input type="checkbox" name="tuesday" style="border: none;"
					onclick="edit('tuesday_opening','tuesday_closing')">Tuesday</td>
				<td><select name="tuesday_opening">
					<option value="0"></option>
					<option value="6:00 AM">6:00</option>
					<option value="6:30 AM">6:30</option>
					<option value="7:00 AM">7:00</option>
					<option value="7:30 AM">7:30</option>
					<option value="8:00 AM">8:00</option>
					<option value="8:30 AM">8:30</option>
					<option value="9:00 AM">9:00</option>
					<option value="9:30 AM">9:30</option>
					<option value="10:00 AM">10:00</option>
					<option value="10:30 AM">10:30</option>
					<option value="11:00 AM">11:00</option>
				</select></td>
				<td>-</td>
				<td><select name="tuesday_closing">
					<option value="0"></option>
					<option value="6:00 PM">6:00</option>
					<option value="6:30 PM">6:30</option>
					<option value="7:00 PM">7:00</option>
					<option value="7:30 PM">7:30</option>
					<option value="8:00 PM">8:00</option>
					<option value="8:30 PM">8:30</option>
					<option value="9:00 PM">9:00</option>
					<option value="9:30 PM">9:30</option>
					<option value="10:00 PM">10:00</option>
					<option value="10:30 PM">10:30</option>
					<option value="11:00 PM">11:00</option>
					<option value="11:30 PM">11:30</option>
					<option value="12:00 AM">12:00</option>
					<option value="12:30 AM">12:30</option>
					<option value="1:00 AM">1:00</option>
					<option value="1:30 AM">1:30</option>
					<option value="2:00 AM">2:00</option>
				</select></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="wednesday" style="border: none;"
					onclick="edit('wednesday_opening','wednesday_closing')">Wednesday</td>
				<td><select name="wednesday_opening">
					<option value="0"></option>
					<option value="6:00 AM">6:00</option>
					<option value="6:30 AM">6:30</option>
					<option value="7:00 AM">7:00</option>
					<option value="7:30 AM">7:30</option>
					<option value="8:00 AM">8:00</option>
					<option value="8:30 AM">8:30</option>
					<option value="9:00 AM">9:00</option>
					<option value="9:30 AM">9:30</option>
					<option value="10:00 AM">10:00</option>
					<option value="10:30 AM">10:30</option>
					<option value="11:00 AM">11:00</option>
				</select></td>
				<td>-</td>
				<td><select name="wednesday_closing">
					<option value="0"></option>
					<option value="6:00 PM">6:00</option>
					<option value="6:30 PM">6:30</option>
					<option value="7:00 PM">7:00</option>
					<option value="7:30 PM">7:30</option>
					<option value="8:00 PM">8:00</option>
					<option value="8:30 PM">8:30</option>
					<option value="9:00 PM">9:00</option>
					<option value="9:30 PM">9:30</option>
					<option value="10:00 PM">10:00</option>
					<option value="10:30 PM">10:30</option>
					<option value="11:00 PM">11:00</option>
					<option value="11:30 PM">11:30</option>
					<option value="12:00 AM">12:00</option>
					<option value="12:30 AM">12:30</option>
					<option value="1:00 AM">1:00</option>
					<option value="1:30 AM">1:30</option>
					<option value="2:00 AM">2:00</option>
				</select></td>
			</tr>
			<tr bgcolor="dddddd">
				<td><input type="checkbox" name="thursday" style="border: none;"
					onclick="edit('thursday_opening','thursday_closing')">Thursday</td>
				<td><select name="thursday_opening">
					<option value="0"></option>
					<option value="6:00 AM">6:00</option>
					<option value="6:30 AM">6:30</option>
					<option value="7:00 AM">7:00</option>
					<option value="7:30 AM">7:30</option>
					<option value="8:00 AM">8:00</option>
					<option value="8:30 AM">8:30</option>
					<option value="9:00 AM">9:00</option>
					<option value="9:30 AM">9:30</option>
					<option value="10:00 AM">10:00</option>
					<option value="10:30 AM">10:30</option>
					<option value="11:00 AM">11:00</option>
				</select></td>
				<td>-</td>
				<td><select name="thursday_closing">
					<option value="0"></option>
					<option value="6:00 PM">6:00</option>
					<option value="6:30 PM">6:30</option>
					<option value="7:00 PM">7:00</option>
					<option value="7:30 PM">7:30</option>
					<option value="8:00 PM">8:00</option>
					<option value="8:30 PM">8:30</option>
					<option value="9:00 PM">9:00</option>
					<option value="9:30 PM">9:30</option>
					<option value="10:00 PM">10:00</option>
					<option value="10:30 PM">10:30</option>
					<option value="11:00 PM">11:00</option>
					<option value="11:30 PM">11:30</option>
					<option value="12:00 AM">12:00</option>
					<option value="12:30 AM">12:30</option>
					<option value="1:00 AM">1:00</option>
					<option value="1:30 AM">1:30</option>
					<option value="2:00 AM">2:00</option>
				</select></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="friday" style="border: none;"
					onclick="edit('friday_opening','friday_closing')">Friday</td>
				<td><select name="friday_opening">
					<option value="0"></option>
					<option value="6:00 AM">6:00</option>
					<option value="6:30 AM">6:30</option>
					<option value="7:00 AM">7:00</option>
					<option value="7:30 AM">7:30</option>
					<option value="8:00 AM">8:00</option>
					<option value="8:30 AM">8:30</option>
					<option value="9:00 AM">9:00</option>
					<option value="9:30 AM">9:30</option>
					<option value="10:00 AM">10:00</option>
					<option value="10:30 AM">10:30</option>
					<option value="11:00 AM">11:00</option>
				</select></td>
				<td>-</td>
				<td><select name="friday_closing">
					<option value="0"></option>
					<option value="6:00 PM">6:00</option>
					<option value="6:30 PM">6:30</option>
					<option value="7:00 PM">7:00</option>
					<option value="7:30 PM">7:30</option>
					<option value="8:00 PM">8:00</option>
					<option value="8:30 PM">8:30</option>
					<option value="9:00 PM">9:00</option>
					<option value="9:30 PM">9:30</option>
					<option value="10:00 PM">10:00</option>
					<option value="10:30 PM">10:30</option>
					<option value="11:00 PM">11:00</option>
					<option value="11:30 PM">11:30</option>
					<option value="12:00 AM">12:00</option>
					<option value="12:30 AM">12:30</option>
					<option value="1:00 AM">1:00</option>
					<option value="1:30 AM">1:30</option>
					<option value="2:00 AM">2:00</option>
				</select></td>
			</tr>
			<tr bgcolor="dddddd">
				<td><input type="checkbox" name="saturday" style="border: none;"
					onclick="edit('saturday_opening','saturday_closing')">Saturday</td>
				<td><select name="saturday_opening">
					<option value="0"></option>
					<option value="6:00 AM">6:00</option>
					<option value="6:30 AM">6:30</option>
					<option value="7:00 AM">7:00</option>
					<option value="7:30 AM">7:30</option>
					<option value="8:00 AM">8:00</option>
					<option value="8:30 AM">8:30</option>
					<option value="9:00 AM">9:00</option>
					<option value="9:30 AM">9:30</option>
					<option value="10:00 AM">10:00</option>
					<option value="10:30 AM">10:30</option>
					<option value="11:00 AM">11:00</option>
				</select></td>
				<td>-</td>
				<td><select name="saturday_closing">
					<option value="0"></option>
					<option value="6:00 PM">6:00</option>
					<option value="6:30 PM">6:30</option>
					<option value="7:00 PM">7:00</option>
					<option value="7:30 PM">7:30</option>
					<option value="8:00 PM">8:00</option>
					<option value="8:30 PM">8:30</option>
					<option value="9:00 PM">9:00</option>
					<option value="9:30 PM">9:30</option>
					<option value="10:00 PM">10:00</option>
					<option value="10:30 PM">10:30</option>
					<option value="11:00 PM">11:00</option>
					<option value="11:30 PM">11:30</option>
					<option value="12:00 AM">12:00</option>
					<option value="12:30 AM">12:30</option>
					<option value="1:00 AM">1:00</option>
					<option value="1:30 AM">1:30</option>
					<option value="2:00 AM">2:00</option>
				</select></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<center><input type="submit" name="go" value="Submit"
			style="font-family: Arial;" style="border: solid 1px"></center>
		</td>
	</tr>
</table>
</form>
<?php
}
?>