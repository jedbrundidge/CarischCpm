<?php
//All scheduled events should go in this controller.
//Functions should be run via cronjobs.

require_once "/var/www/htdocs/forms/swift/Swift.php"; //email sending lib
require_once "/var/www/htdocs/forms/swift/Swift/Connection/SMTP.php"; //smtp support
class Scheduled extends Controller {
	public function _review_alerts() {
		$smtp =& new Swift_Connection_SMTP("192.168.0.6", 25);
		$swift =& new Swift($smtp);
		$user_group = $this->db->query("SELECT `group_id`,`user_id`,`email` FROM `users` WHERE `user_type` = 3 AND `active` = 1 AND (`group_id` != 14 OR `user_id` = 15812)");
		foreach ($user_group->result() as $group) {
			$emp_array = array();
			$user_id = $group->user_id;
			$emps = $this->db->query("SELECT CONCAT(`first_name`,' ',`last_name`) AS `name`,`uniq_id`,DATE_FORMAT(`doh`,'%b %e, %Y') AS `doh`,DATE_FORMAT(`dop`,'%b %e, %Y') AS `dop`,`position`,`store_name` FROM `employees` LEFT JOIN `employee_info` ON `uniq_id` = `unique_id` LEFT JOIN `stores` ON `store` = `store_id` WHERE `store` IN (SELECT `group_stores` FROM `groups` WHERE `idgroups` = $group->group_id) AND IF(!ISNULL(`dop`),MONTH(`dop`) = MONTH(DATE_ADD(CURRENT_DATE(),INTERVAL 1 MONTH)),MONTH(`doh`) = MONTH(DATE_ADD(CURRENT_DATE(),INTERVAL 1 MONTH))) AND `active` = 1 AND (`position` = 4 OR `position` = 5 OR `position` = 3)");
			foreach($emps->result() as $emp) {
				$emp_array[$emp->uniq_id] = array('name'=>ucwords(strtolower($emp->name)),'doh'=>$emp->doh,'dop'=>$emp->dop,'title'=>$emp->position,'store'=>$emp->store_name);
			}
			$rev_month = date('F',mktime(0,0,0,date('m') + 1,1));
			$mailbody = "<html><head><body>The following employees have anniversary/promotion dates next month.\n\n<table border=\"1\"><tr><td></td><td>Name</td><td>Hire Date</td><td>Promotion Date</td><td>Store</td></tr>";
			if (count($emp_array) == 0) {
				$mailbody .= "<tr><td></td><td colspan=\"100\">No reviews in $rev_month</td></tr>";
			}
			foreach ($emp_array as $emp) {
				switch ($emp['title']) {
					case 3: $title = 'RM'; break;
					case 4: $title = 'GM'; break;
					case 5: $title = 'AM'; break;
					default: $title = ''; break;
				}
				if (!$emp['dop']) $emp['dop'] = 'NA';
				$mailbody .= "<tr><td>$title</td><td>".$emp['name']."</td><td>".$emp['doh']."</td><td>".$emp['dop']."</td><td>".$emp['store']."</td></tr>";
			}
			$mailbody .= "</table></body></html>";
			$subject = "$rev_month Employee Reviews";
			//$mailto	= $group->email;
			$mailto = 'david@carischinc.com';
			$message =& new Swift_Message($subject,$mailbody,'text/html');
			$swift->send($message, $mailto, 'cpm@carischinc.com');
		}
	}
} 