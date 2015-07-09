You have failed to enter the following information correctly, please go back and review.<br>
<b><a href="/cpm/index.php/personnel/new_hire/1">Go Back</a></b>
<br>
<font color="red">
<?php
$array = $this->session->userdata('failure_array');
foreach ($array[0] as $val) {
	echo "$val<br>";
}
?>
</font>