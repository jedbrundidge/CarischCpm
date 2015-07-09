<table width="100%">
<tr>
<td>
<?php
$note = $this->db->query("SELECT DATE(`date`) AS `date`,`description`,`first_name`,`last_name`,`subject` FROM `notes` JOIN `users` ON `user_id` = `manager` WHERE `note_id` = $note_id");
$note = $note->first_row();
$name = ucwords(strtolower("$note->first_name $note->last_name"));

echo "Date: <b>$note->date</b> | By: <b>$name</b><br />Subject: <b>$note->subject</b><br /><hr>";
echo "$note->description";
?>
</td>
</tr>
</table>
<br />