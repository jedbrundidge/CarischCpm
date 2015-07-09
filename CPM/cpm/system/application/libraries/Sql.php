<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * SQL Transactions
 */

class cpm_sql {
	
	function cpm_sql() {
		log_message('debug','CPM SQL Class initialized');
	}

	function commit_warning($array) {
		foreach ($array as $key=>$var) {
			if (!is_array($var)) {
				$$key = $var;
			}
		}

		$this->db->trans_start();
		$this->db->query("INSERT INTO warning_incidents (`incident_date`,`incident_submitted`) VALUES ('$incident_date','$incident_submitted'");
		$incident_num = $this->db->insert_id();
		$this->db->query("INSERT INTO warnings (`employee_id`,`incident_num`,`store`,`warning_date`,`submitting_user_id`,`suspension_length`,`other_action`,`termination`) VALUES ($employee_id,$incident_num,$store,'$warning_date','$submitting_user_id','$suspension_length','$other_action',$termination");
		$warning_num = $this->db->insert_id();
		
		foreach ($array['policies_violated'] as $policy_v) {
			$this->db->query("INSERT INTO warning_violated (`idwarning_ids`,`policy_violated`) VALUES ($warning_num,$policy_v)");
		}
		
		$this->db->query("INSERT INTO warning_descriptions (`warning_num`,`description`) VALUES ($warning_num,'$description')");
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			return FALSE;
		} else {
			return TRUE;
		}
		
	}
}
?>