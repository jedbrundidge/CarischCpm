<body OnLoad="document.find.search.focus();">

== equal<br>
!= not equal<br>
>= <= > <<br>
<br>
ssn, name, first_name, last_name, e_id (old 5 digit format), uniq_id, position, store, active<br><br>
If no search criteria is entered, it will default to "name==*input*"<br><br>
<form name="find" method="post"><input type="text" name="search"
	value="<?=$search_string?>" size="65"></input> <br><input type="submit"></input><input type="hidden" name="xfer" value="false"></input></form>
<br>
<table>
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
			echo "<tr><td><a href=\"/cpm/index.php/personnel/employee/$id\">$name</a> [$id] ";
			echo "</td></tr>";
		}
	}
}
?>
</table>
