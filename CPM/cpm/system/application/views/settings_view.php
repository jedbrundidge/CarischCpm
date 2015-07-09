<p>
<a href="/cpm/index.php/personnel/change_pw">Change Password</a><br />
<a href="/cpm/index.php/personnel/email">Set Email Address</a><br />
<a href="/cpm/index.php/personnel/rtipasswd">Set RTI Password</a></br />
<?php 
if ($this->session->userdata('utype') == 1) {
	echo "<a href=\"/cpm/index.php/personnel/edit_term_reasons\">Edit Voluntary Termination Reasons</a><br>";
	echo "<a href=\"/cpm/index.php/personnel/edit_policies\">Edit Policies</a><br>";
	echo "<a href=\"/cpm/index.php/exports/employees\">Employee Export</a><br>";
}

if ($this->session->userdata('utype') <= 3) {
	//echo "<a href=\"/cpm/index.php/personnel/transfer\">Transfer Employee</a><br>";
	//echo "<a href=\"/cpm/index.php/personnel/assign_cred\">Assign Credentials</a><br>";
}
?>
