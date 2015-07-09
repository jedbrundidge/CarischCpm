<form method="POST">
<input type="hidden" name="full_name" value="<?php
$uid = $this->session->userdata('uid');
echo $this->db->query("SELECT CONCAT(`first_name`,' ',`last_name`) AS `full_name` FROM `users` WHERE `user_id` = $uid LIMIT 0,1")->first_row()->full_name;
?>">
Your password must be 6 or more characters and include atleast 1 number.<br />
New Password: <input type="password" name="passwd1"><br />
Re-enter Password: <input type="password" name="passwd2"></br />
<input type="submit">
</form>