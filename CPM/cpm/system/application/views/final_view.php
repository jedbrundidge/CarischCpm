<table width="100%">
<tr>
<td>
<?php
	foreach ($this->hire_objects as $key=>$val) {
		$obj = $this->session->userdata($key);
		$desc = ucwords($key);
		//echo "$desc - $obj<br />";
	}
?>
<font color="red">Once you submit this information it will become part of this employee's permanent file; therefore, your submission indicates you have reviewed all entries and have verified them to be accurate.</font>
</td>
</tr>
</table>
