<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class cpm_functions {

	function cpm_functions() {
		log_message('debug','CPM Functions Class initialized');
	}
	function barcode($store_id,$eid,$first_name,$last_name,$doc_type,$doc_id) {

	}

	function verify_access($uid,$sid = 0) {
		$_stores = array();
		$grant = 0;
		$dsa = 0;

		if ($uid) {
			$group_id = $this->db->query("SELECT `group_id`,`user_type`,`default_store` FROM `users` WHERE `user_id` = $uid");
			$group_id = $group_id->first_row();

			$store_access = $this->db->query("SELECT `group_stores` AS `store` FROM `groups` WHERE `idgroups` = $group_id->group_id");
			foreach ($store_access->results() as $_id) {
				if ($sid == $_id['store']) {
					$grant = 1;
				}
				$_stores[] = $_id['store'];
				if ($group_id->default_store == $_id['store']) {
					$dsa = 1;
				}
			}

			if (!$dsa) {
				$_stores[] = $group_id->default_store;
			}

			if ($sid && $grant === TRUE) {
				return TRUE;
			} elseif ($sid && $grant === FALSE) {
				return FALSE;
			} elseif (!$sid) {
				return $_stores;
			}
		} else {
			return FALSE;
		}

	}
}
?>