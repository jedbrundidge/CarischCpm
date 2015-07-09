<?php
$NAME = ucwords(strtolower($this->db->query("SELECT CONCAT(`first_name`,' ',`last_name`) AS `name` FROM `employees` WHERE `uniq_id` = (SELECT `emp_id` FROM `notes` WHERE `note_id` = $note_id)")->last_row()->name));
echo "Note(s) for $NAME<br /><br />";
?>