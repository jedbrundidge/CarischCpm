<?php
function auth() {
	if (isset($_SESSION['userid'])) {
		return true;
	}
	return false;
}
?>
