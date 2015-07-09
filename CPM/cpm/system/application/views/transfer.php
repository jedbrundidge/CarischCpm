<form name="find" method="post"><input type="text" name="search"
	value="<?=$search_string?>" size="65"></input> <br><input type="submit"></input><input type="hidden" name="xfer" value="false"></form>
<br>
<table>
<tr><td>
<select>
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
			echo "<option id=\"$id\" style=\"font-family: Arial;\">$name</option>";
		}
	}
}
?>
</td>
<td>
<?php

?>
</td>
</tr>
</select>
</table>