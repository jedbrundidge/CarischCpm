<BODY OnLoad="document.verification.DOCS.focus();">
<FORM METHOD="POST" NAME="verification" ID="verification">
<INPUT TYPE="TEXT" NAME="DOCS" ID="DOCS" SIZE="50"/><BR />
<INPUT TYPE="SUBMIT" />
</FORM>
<?php
include('init.php');
include('auth.php');
session_start();

function get_days($doc) {
	$days = "SELECT DATEDIFF(`date_scanned`,`date_set`) AS `days` FROM `employee_docs` WHERE `uniq_id` = '$doc' AND `document` = 8850";
	$days = mysql_fetch_row(mysql_query($days));
	$days = $days[0];
	return $days;
}

if (!auth()) {
	header('Location: /cpm/dv/login.php');
} else {
	session_cache_limiter('nocache');
	echo "<TABLE BORDER=\"1\"><TR><TD COLSPAN=\"2\" ALIGN=\"CENTER\">Verified this session</TD></TR>";
	if ($_POST) {
		$docs = explode(',',$_POST['DOCS']);
		foreach ($docs as $doc) {
			$doc = mysql_real_escape_string($doc);
			$query = "UPDATE `employee_docs` " .
					"SET `scanned` = 1,`status` = 1, `date_scanned` = CURRENT_DATE " .
					"WHERE `document` = 8850 AND `uniq_id` = $doc AND `scanned` = 0 AND `status` = 0";
			$result = mysql_query($query);
			if (mysql_affected_rows() > 0) {
					$_SESSION['verified'][$doc] = get_days($doc);
			} else {
				$check_id = mysql_query("SELECT `doh` FROM `employee_info` WHERE `unique_id` = $doc");
				if (mysql_num_rows($check_id) == 1) {
					while ($hiredate = mysql_fetch_assoc($check_id)) {
						$doh = $hiredate['doh'];
					}
					$doinsert = "INSERT INTO `employee_docs` (uniq_id,document,scanned,status,date_set,date_scanned) VALUES ($doc,8850,1,1,'$doh',CURRENT_DATE)";
					mysql_query($doinsert);
					$_SESSION['verified'][$doc] = get_days($doc);
				}
			}
			unset($result,$doc,$query);
		}
		foreach ($_SESSION['verified'] as $key=>$val) {
			echo "<TR><TD ALIGN=\"CENTER\">$key</TD></TR>";
		}
	} else {
		echo "<TR><TD ALIGN=\"CENTER\">None</TD></TR>";
	}
	echo "</TABLE><BR />";
	echo "<TABLE BORDER=\"1\">";
	$pend = "SELECT `employee_docs`.`uniq_id`,DATEDIFF(CURRENT_DATE,`date_set`) AS `date_set`,`store` FROM `employee_docs` LEFT JOIN `employees` ON `employees`.`uniq_id` = `employee_docs`.`uniq_id` WHERE `scanned` = 0 AND `status` = 0 AND `document` = 8850 AND `date_set` >= '2010-03-15' ORDER BY `date_set` DESC";
	$pending = mysql_query($pend);
	echo "<TR><TD COLSPAN=\"100\" ALIGN=\"CENTER\">Documents Pending</TD></TR>";
	echo "<TR><TD>ID</TD><TD>Days Old</TD></TR>";
	while ($pending_doc = mysql_fetch_assoc($pending)) {
		if ($pending_doc['date_set'] > 15) {
			$color = "yellow";
			if ($pending_doc['date_set'] > 28) $color = "red";
		} else {
			$color = "lightgrey";
		}
		echo "<TR BGCOLOR=\"$color\"><TD ALIGN=\"CENTER\">$pending_doc[uniq_id]</TD><TD ALIGN=\"CENTER\">$pending_doc[date_set]</TD></TR>";	
	}
	echo "</TABLE>";
}