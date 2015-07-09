<body bgcolor="fffffc">
<?php
if (!isset($bv)) {
	echo '<link rel="stylesheet" href="/cpm/default.css" type="text/css">';
}
?>
<?php $uid = $this->session->userdata('uid');
$unread = $this->db->query("SELECT COUNT(`message_id`) AS `cnt` FROM `inbox` WHERE `message_to` = $uid AND `read` = 0");
$unread = $unread->last_row();
//$this->db->query("SELECT ")
?>
<p><?php echo ucwords(strtolower($this->session->userdata('uname'))).' '; ?>
| <a href="/cpm/index.php/personnel/settings">Settings</a> | <a
	href="/cpm/index.php/personnel/manage">Manage</a> | <a
	href="/cpm/index.php/personnel/inbox">Inbox<?php if ($unread->cnt > 0) echo " ($unread->cnt)"; ?></a>
| <?php if ($this->session->userdata('utype') ==  1) { echo "<a href=\"/cpm/index.php/personnel/empsearch\">Search</a> | "; } ?><a
	href="/cpm/index.php/personnel/logout">Logout</a>


<hr>