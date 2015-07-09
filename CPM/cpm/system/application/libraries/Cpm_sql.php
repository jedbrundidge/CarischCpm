<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * SQL Transactions
 */

class Cpm_sql {

	function cpm_sql() {
		log_message('debug','CPM SQL Class initialized');
	}

	function commit_warning($array) {
		$CI =& get_instance();
		$db = $CI->load->database('default',TRUE);

		$submitting_user_id = $CI->session->userdata('uid');

		foreach ($array as $key=>$var) {
			if (!is_array($var)) {
				$$key = mysql_real_escape_string($var);
			}
		}


		$terminated = 0;
		if (isset($array['terminated'][1])) {
			$terminated = 1;
		}

		$incident_date = "$year-$month-$day";
		$store = $CI->session->userdata('csid');
		
		if ($store == '' || !$store) {
			$store = $db->query("SELECT `store` FROM `employees` WHERE `uniq_id` = $eid LIMIT 0,1")->first_row()->store;
		}

		//$db->trans_start();
		
		$regional_uid = $db->query("SELECT `idgroups` FROM `groups` WHERE `group_stores` = '$store' AND `idgroups` != 14");
		foreach ($regional_uid->result() as $val) {
			$rrid = $db->query("SELECT `user_id` FROM `users` WHERE `group_id` = '$val->idgroups' AND user_type = 3");
			foreach ($rrid->result() as $_id) {
				$rr = $_id->user_id;
			}
		}

		$db->trans_start();

		if (!isset($rr)) {
			$rr = 9138;
		}
		if ($terminated) {
			$db->query("UPDATE `employee_info` SET `dot` = CURRENT_DATE() WHERE `unique_id` = $eid");
			$db->query("UPDATE `employees` SET `active` = 0 WHERE `uniq_id` = $eid");
			$check_user = $db->query("SELECT `user_id` FROM `users` WHERE `eid` = $eid");
			if ($check_user->num_rows() > 0) {
				$check_user = $check_user->first_row();
				$db->query("UPDATE `users` SET `active` = 0 WHERE `user_id` = $check_user->user_id");
			}
			$get_emp_for_notice = $db->query("SELECT `first_name`,`last_name`,substring(`ssn_hash`,-4,4) as `ssn_hash` FROM `employees` WHERE `uniq_id` = $eid AND `position` <= 5 LIMIT 0,1");
			if ($get_emp_for_notice->num_rows() === 1) {
				$notice_info = $get_emp_for_notice->first_row();
				$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
				$swift 			=& new Swift($smtp);

				$mailbody 		= 'A termination process has been initiated for ' . ucwords(strtolower($notice_info->first_name . ' ' . $notice_info->last_name)) . ' (XXX-XX-' . $notice_info->ssn_hash . ').';
				$subject 		= 'Termination Notification';
				$mailto 		= "hr@carischinc.com";
	
				$message		=& new Swift_Message($subject,$mailbody);

				$swift->send($message, $mailto, 'cpm@carischinc.com');
			}
		}
		$db->query("INSERT INTO warning_incidents (`incident_date`,`incident_submitted`) VALUES ('$incident_date','$incident_submitted')");
		$incident_num = $db->insert_id();
		$db->query("INSERT INTO warnings (`employee_id`,`incident_num`,`store`,`warning_date`,`submitting_user_id`,`suspension_length`,`other_action`,`requested_reviewer`,`status`,`terminated`) VALUES ($eid,$incident_num,$store,'$warning_date','$submitting_user_id','$suspension_length','$other_disc_desc','$rr',1,$terminated)");
		$warning_num = $db->insert_id();
		$db->query("UPDATE `warnings` SET `hash` = md5($warning_num) WHERE `warning_id` = $warning_num");

		if (isset($array['policies_violated'])) {
			foreach ($array['policies_violated'] as $policy_v) {
				$db->query("INSERT INTO warning_violated (`idwarning_ids`,`policy_violated`) VALUES ($warning_num,$policy_v)");
			}
		}

		$db->query("INSERT INTO warning_descriptions (`warning_num`,`description`) VALUES ($warning_num,'$desc')");
		$db->query("UPDATE `saved_forms` SET `visible` = 0 WHERE `user_id` = $submitting_user_id AND `e_id` = $eid AND `visible` = 1");
		$db->trans_complete();

		if ($db->trans_status() === FALSE) {
			return FALSE;
		} else {
			return $warning_num;
		}

	}
}
?>
