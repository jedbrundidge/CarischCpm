<?php
class Comm extends Model {


	function Comm()
	{
		parent::Model();
	}
	
	function get_info($id,$type) {
		$query = "SELECT " .
				"CONCAT(`employees`.`first_name`,' ',`employees`.`last_name`) AS `employee_name`," .
				"`uniq_id`," .
				"CONCAT(`users`.`first_name`,' ',`users`.`last_name`) AS `manager_name`," .
				"`user_type`," .
				"`eid`," .
				"`position`," .
				"`warnings`.`store` AS `store`," .
				"`terminated`," .
				"`incident_date`," .
				"`warning_date`," .
				"`description`," .
				"`warnings`.`store`," .
				"`stores`.`state` AS `state`,".
				"`stores`.`store_name` AS `store_name`" .
				"FROM " .
				"`warnings` LEFT JOIN `warning_descriptions` ON `warning_id` = `warning_num` " .
				"LEFT JOIN `employees` ON `employee_id` = `uniq_id` " .
				"LEFT JOIN `stores` ON `warnings`.`store` = `stores`.`store_id` " .
				"LEFT JOIN `users` ON `users`.`user_id` = `warnings`.`submitting_user_id` " .
				"LEFT JOIN `warning_incidents` ON `incident_id` = `incident_num` " .
				"WHERE " .
				"`warning_id` = $id LIMIT 0,1";
		$result = $this->db->query($query)
						   ->first_row();
		
		$result->employee_name = ucwords(strtolower($result->employee_name));
		$result->manager_name = ucwords(strtolower($result->manager_name));
		
		switch ($result->user_type) {
			case 3:
				$result->user_type = 'Regional Manager';
				break;
			case 4:
				$result->user_type = 'General Manager';
				break;
			case 5:
				$result->user_type = 'Assistant Manager';
				break;
			case 6:
				$result->user_type = 'Shift Manager';
				break;
		}
		
		$data = array (
			'employee_name'=>$result->employee_name,
			'employee_id'=>$result->uniq_id,
			'manager_name'=>$result->manager_name,
			'manager_position'=>$result->user_type,
			'manager_id'=>$result->eid,
			'store_name'=>$result->store_name,
			'store_id'=>$result->store,
			'store_state'=>$result->state,
			'incident_date'=>$result->incident_date,
			'communication_date'=>$result->warning_date,
			'description'=>nl2br(htmlspecialchars($result->description)),
			'terminated'=>$result->terminated,
			'store'=>$result->store,
			'position'=>$result->position
		);
		
		$policy_query = "SELECT " .
				"`policy_desc`" .
				"FROM " .
				"`warning_violated`" .
				"LEFT JOIN `warning_definitions` ON `policy_violated` = `policy_id` " .
				"WHERE " .
				"`idwarning_ids` = $id";
		$qr = $this->db->query($policy_query);
		foreach ($qr->result() as $row) {
			$array[] = array('policy'=>$row->policy_desc);
		}
		$data['policies'] = $array;
		
		$data['communication_type'] = 'Disciplinary Warning';
		if ($data['terminated'] == 1) {
			$data['communication_type'] = 'Disciplinary Termination';
		}
		
		return $data;
	}
}
?>
