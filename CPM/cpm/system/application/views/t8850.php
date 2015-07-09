<?php
$this->db->query("SELECT COUNT(uniq_id) FROM `employee_docs` WHERE `document` = 8850");
$this->db->query("SELECT COUNT(uniq_id) FROM `employee_docs` WHERE `document` = 8850 AND `scanned` = 0");
$this->db->query("SELECT COUNT(uniq_id) FROM `employee_docs` WHERE `document` = 8850 AND `scanned` = 0 AND DATEDIFF(CURRENT_DATE,`date_set`) > 30");
$this->db->query("SELECT SUM(DATEDIFF(`date_scanned`,`date_set`))/COUNT(`uniq_id`) AS `avg` FROM `employee_docs` WHERE `document` = 8850 AND `scanned` = 1");

?>
