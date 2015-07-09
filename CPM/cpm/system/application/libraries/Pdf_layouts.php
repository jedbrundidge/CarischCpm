<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class cpm_pdflayouts {

	var $h = 0; //Horizontal offset
	var $v = 0; //Vertical offset
	
	var $font = "setFont";
	var $text = "Text";
	var $rect = "Rect";
	var $cell = "MultiCell";

	var $barcode_font = "BarCode39fHR";
	var $barcode_font_src = "bar39fh.php";

	function cpm_pdflayouts() {
		log_message('debug','CPM PDFLayouts Class initialized');
	}

	function warning_blank() {
		$_blank = array
		(
			array ("type" => $this->font,"Arial","B","20"),
			array ("type" => $this->text,$this->h+10,$this->v+30,"Employee Warning Form"),

		);
		return $_blank;
	}

	function warning_info($warning_id) {
		$failure = "SQL transaction failure. ";
		$_info = FALSE;

		$this->db->trans_start();
		$warning_info = $this->db->query("SELECT `employee_id`,`incident_num`,`store`,`warning_date`,`incident_date`,`submitting_user_id`,`suspension_length`,`other_action`,`termination` FROM `warning` JOIN `warning_incidents` ON `incident_num` = `incident_id` WHERE `warning_id` = $warning_id");
		$description_info = $this->db->query("SELECT `description` FROM `warning_descriptions` WHERE `warning_num` = '$warning_id'");
		$policies_info = $this->db->query("SELECT `policy_violated` FROM `warning_violated` WHERE `idwarning_ids` = '$warning_id'");
		$this->db->trans_complete();

		if ($this->db->trans_status() !== TRUE) die ($failure . "12");
		
		foreach ($warning_info->result() as $key=>$val) {
			$info[$key] = $val;
		}
		$this->db->trans_start();
		$submitter_info = $this->db->query("SELECT `first_name`,`last_name`,`eid` FROM users WHERE `user_id` = '" . $info['submitting_user_id'] . "'");
		$employee_info = $this->db->query("SELECT `first_name`,`last_name` FROM employees WHERE `e_id` = '" . $info['employee_id'] . "'");
		$store_name = $this->db->query("SELECT `store_name` FROM `stores` WHERE `store_id` = '" . $info['store'] . "'");
		$this->db->trans_complete();
		
		if ($this->db->trans_status() !== TRUE) die ($failure . "13");

		foreach ($submitter_info->result() as $key=>$val) {
			$info[${"submitter_$key"}] = ucwords(strtolower($val));
		}
		foreach ($employee_info->result() as $key=>$val) {
			$info[${"employee_$key"}] = ucwords(strtolower($val));
		}
		foreach ($policies_info->result() as $key=>$val) {
			$get_desc = $this->db->query("SELECT `policy_desc` FROM `warning_definitions` WHERE `policy_id` = $val");
			$get_desc = $get_desc->first_row();
			$info['policies_violated'][$val] = $get_desc->policy_desc;
		}
		$description = $description_info->first_row();
		$info['description'] = $description->description;

		$store_name = $store_name->return->first_row();
		$info['store_id'] = $store_name->store_name;

		$info['warning_id'] =  $warning_id;

		$pv = $this->v + 77;

		$_info = array
		(
			array ("type" => $this->font,$this->barcode_font,"",$this->barcode_font_src),
			array ("type" => $this->text,$this->h+10,$this->v+18,barcode($info['store'],$info['employee_eid'],$info['employee_first_name'],$info['employee_last_name'],2,$info['warning_id'])),
			array ("type" => $this->font,"Arial","",11),
			array ("type" => $this->text,$this->h+0,$this->v+110,$info['employee_first_name'] . " " .$info['employee_last_name'] . " (" . $info['employee_eid'] . ") is now under disciplinary action as of this date as the result of unsatisfactory"),
			array ("type" => $this->text,$this->h+0,$this->v+115,"job performance or conduct as described below:"),
			array ("type" => $this->font,"Arial","B",11),
			array ("type" => $this->text,$this->h+26,$this->v+38,$info['store']),
			array ("type" => $this->text,$this->h+73,$this->v+38,$info['store_name']),
			array ("type" => $this->text,$this->h+42,$this->v+44,$info['employee_first_name'] . " " .$info['employee_last_name']),
			array ("type" => $this->text,$this->h+41,$this->v+50,$info['incident_date']),
			array ("type" => $this->text,$this->h+41,$this->v+56,$info['warning_date']),
			array ("type" => $this->font,"Arial","I",9),
			array ("type" => $this->text,$this->h+53,$this->v+62,$info['submitter_first_name'] . " " .$info['submitter_last_name'] . " (" . $info['submitter_eid'] . " )"),
			array ("type" => $this->cell,$this->h+170,$this->v+4,$info['description'])
		);
		$_info[] = array ("type" => "setFont","Arial","",11);
		foreach ($info['policies_violated'] as $key=>$val) {
			$_info[] = array ("type" => "Text",$this->h+15,$this->v+$pv,$val);
			$pv = $pv + 4;
		}
		return $_info;
	}
}
?>