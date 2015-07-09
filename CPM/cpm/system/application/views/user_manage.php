User Management
<FORM NAME="find" METHOD="POST">
<INPUT TYPE="TEXT" SIZE="50" NAME="search" />
<INPUT TYPE="SUBMIT" VALUE="Search" />
</FORM>
<TABLE>
<?php
if (isset($employees)) {
	if (isset($failure) && $failure) {
		echo "Malformed expression.";
	} elseif (empty($employees)) {
		echo "No results.";
	} else {
		foreach ($employees as $val) {
			$id = $val['id'];
			$name = ucwords(strtolower($val['first_name'])) . ' ' . ucwords(strtolower($val['last_name']));
			echo "<tr><td>$name</td></tr>";
		}
	}
}
?>
</TABLE>