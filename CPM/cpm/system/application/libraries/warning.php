<?php
class warning
{
	var $db;

	var $emp_id;
	var $uniq_id;
	var $first_name;
	var $last_name;

	public function __construct() {
		$ci 			=& get_instance();
		$this->db 		= $ci->load->database('default',TRUE);
	}

	public function _init() {
		$args 			= func_get_args();
		$this->emp_id 	= $args[0];
		$ident 			= $this->db->query("SELECT `uniq_id`,`first_name`,`last_name` FROM `employees` WHERE `uniq_id` = '$this->emp_id'");
		if ($ident) {
			$result = $ident->first_row();
			$this->uniq_id 		= $result->uniq_id;
			$this->first_name 	= $result->first_name;
			$this->last_name 	= $result->last_name;
		}
	}

	public function commit($array) {
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

		$db->trans_start();
		
		$regional_uid = $db->query("SELECT `idgroups` FROM `groups` WHERE `group_stores` = '$store' AND `idgroups` != 14");
		foreach ($regional_uid->result() as $val) {
			$rrid = $db->query("SELECT `user_id` FROM `users` WHERE `group_id` = '$val->idgroups' AND user_type = 3");
			foreach ($rrid->result() as $_id) {
				$rr = $_id->user_id;
			}
		}
		
		if ($terminated) {
			$check_user = $this->db->query("SELECT `user_id` FROM `users` WHERE `eid` = $eid");
			if ($check_user->num_rows() > 0) {
				$check_user = $check_user->first_row();
				$this->db->query("UPDATE `users` SET `active` = 0 WHERE `user_id` = $check_user->user_id");
			}
		}

		$db->query("INSERT INTO warning_incidents (`incident_date`,`incident_submitted`) VALUES ('$incident_date','$incident_submitted')");
		$incident_num = $db->insert_id();
		$db->query("INSERT INTO warnings (`employee_id`,`incident_num`,`store`,`warning_date`,`submitting_user_id`,`suspension_length`,`other_action`,`requested_reviewer`,`status`,`terminated`) VALUES ('$eid',$incident_num,$store,'$warning_date','$submitting_user_id','$suspension_length','$other_disc_desc','$rr',1,$terminated)");
		$warning_num = $db->insert_id();

		if (isset($array['policies_violated']) && count($array['policies_violated']) > 0) {
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
			return TRUE;
		}

	}
	public function getWarningIDs() {
		$wArray = array();
		$warnings = $this->db->query("SELECT `warning_id` FROM `warnings` WHERE `employee_id` = '$this->emp_id'");
		foreach ($warnings->result() as $result) {
			$wArray[] = $result->warning_id;
		}
		return $wArray;
	}

	public function getWarning($W) {
		$warn = $this->db->query("SELECT `warning_id`,`incident_num`,`store`,`scanned`,`warning_date`,`submitting_user_id`,`status`,`suspension_length`,`other_action`,`requested_reviewer`,`terminated` FROM `warnings` WHERE `warning_id` = $W AND `employee_id` = '$this->emp_id'");
		$vArray = array();
		if ($warn) {
			$warn = $warn->first_row();
			$warn = get_object_vars($warn);
			foreach ($warn as $key=>$val) {
				$vArray[$key] = $val;
			}
		}
		$iid = $vArray['incident_num'];
		$icdn = $this->db->query("SELECT `incident_date`,`incident_submitted` FROM `warning_incidents` WHERE `incident_id` = $iid");
		if ($icdn) {
			$icdn = $icdn->first_row();
			$icdn = get_object_vars($icdn);
			foreach ($icdn as $key=>$val) {
				$vArray[$key] = $val;
			}
		}
		$desc = $this->db->query("SELECT `description` FROM `warning_descriptions` WHERE `warning_num` = $W");
		if ($desc) {
			$desc = $desc->first_row();
			$vArray['desc'] = $desc->description;	
		}
		
		$violations = $this->db->query("SELECT `policy_violated` FROM `warning_violated` WHERE `idwarning_ids` = $W");
		$v = array();
		if ($violations) {
			$violations = $violations->result();
			foreach ($violations as $val) {
				$v[] = $val->policy_violated;
			}
			$vArray['violations'] = $v;
		}
		return $vArray;
	}
}