<FORM NAME="login" ID="login" METHOD="POST">
<INPUT TYPE="text" NAME="username" ID="username" />
<INPUT TYPE="password" NAME="passwd" ID="passwd" />
<INPUT TYPE="submit" NAME="login" ID="login" VALUE="Login" />
</FORM>
<?php
if ($_POST) {
	include('init.php');
	$login = false;
	$username = mysql_real_escape_string($_POST['username']);
	$query = "SELECT `passwd` FROM `users` WHERE `user_name` = '$username' AND (`user_type` = 20 OR `user_type` = 1) AND `active` = 1";
	$passwd = mysql_fetch_row(mysql_query($query));
	$passwd = $passwd[0];
	if (md5($_POST['passwd']) == $passwd) {
		$login = true;
	}
	if ($login === true) {
		$get_id = "SELECT `user_id` FROM `users` WHERE `user_name` = '$username'";
		$get_id = mysql_fetch_row(mysql_query($get_id));
		session_start();
		$_SESSION['username']	= $_POST['username'];
		$_SESSION['userid']		= $get_id[0];
		header("Location: /cpm/dv/verification.php");
	}
}