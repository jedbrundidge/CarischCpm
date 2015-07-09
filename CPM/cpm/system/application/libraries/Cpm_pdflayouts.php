<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cpm_pdflayouts {



	var $h = -5; //Horizontal offset
	var $v = 0; //Vertical offset

	var $mt = array(016,017,025,030,013,038,015,034,018,036,023,006,009,020,019,035,024);

	var $font = "setFont";
	var $text = "Text";
	var $rect = "Rect";
	var $cell = "MultiCell";
	var $set = "SetXY";
	
	var $barcode_font = "BarCode39fHR";
	var $barcode_font_src = "bar39fh.php";

	function cpm_pdflayouts() {
		log_message('debug','CPM PDFLayouts Class initialized');
	}

	function warning_bottom($warning_id) {
		$CI =& get_instance();
		$CI->load->library('Cpm_functions');
		$cpm = new Cpm_functions();
		$db = $CI->load->database('default',TRUE);
		
		$term = $db->query("SELECT `terminated` FROM `warnings` WHERE `warning_id` = $warning_id");
		$term = $term->first_row();
		$line1 = $line2 = $line3 = "";
		
		if ($term->terminated === 0) {
			$line1 = "I understand that my job performance has been rated as unsatisfactory and must be improved or I will be subject to disciplinary action.";
			$line2 = "Further unsatiffactory job performance or the violation of any rule or policy may result in disciplinary actopn up to , and including,";
			$line3 = "termination.";
		}
		$_bottom = array
		(
		array ("type" => $this->font,"Arial","",9),
		array ("type" => $this->text,$this->h+10,$this->v+240,$line1),
		array ("type" => $this->text,$this->h+10,$this->v+245,$line2),
		array ("type" => $this->text,$this->h+10,$this->v+250,$line3),
		array ("type" => $this->text,$this->h+115,$this->v+260,"__________________________________________________"),
		array ("type" => $this->text,$this->h+120,$this->v+264,"Employee Signature"),
		array ("type" => $this->text,$this->h+188,$this->v+264,"Date"),
		array ("type" => $this->text,$this->h+8,$this->v+269,"In the event that the employee refuses to sign, the manager should ask a third person to witness refusal and ask them to sign."),
		array ("type" => $this->text,$this->h+20,$this->v+278,"_______________________________________"),
		array ("type" => $this->text,$this->h+120,$this->v+278,"_______________________________________"),
		array ("type" => $this->text,$this->h+22,$this->v+282,"Manager"),
		array ("type" => $this->text,$this->h+80,$this->v+282,"Date"),
		array ("type" => $this->text,$this->h+180,$this->v+282,"Date"),
		array ("type" => $this->text,$this->h+122,$this->v+282,"Witness")
		);
		return $_bottom;
	}

	function warning_info($warning_id) {
		$CI =& get_instance();
		$CI->load->library('Cpm_functions');
		$cpm = new Cpm_functions();
		$db = $CI->load->database('default',TRUE);

		$failure = "SQL transaction failure. ";
		$_info = FALSE;


		$db->trans_start();
		$warning_info = $db->query("SELECT `terminated`,`employee_id`,`incident_num`,`store`,`warning_date`,`incident_date`,`submitting_user_id` AS `mid`,`suspension_length`,`other_action` FROM `warnings` JOIN `warning_incidents` ON `incident_num` = `incident_id` WHERE `warning_id` = $warning_id");
		$description_info = $db->query("SELECT `description` FROM `warning_descriptions` WHERE `warning_num` = '$warning_id'");
		$policies_info = $db->query("SELECT `policy_violated` FROM `warning_violated` WHERE `idwarning_ids` = '$warning_id'");
		$db->trans_complete();

		if ($db->trans_status() !== TRUE) die ($failure . "12");

		foreach ($warning_info->result_array() as $row) {
			foreach ($row as $key=>$val) {
				$info[$key] = $val;
			}
		}
		$db->trans_start();
		$submitter_info = $db->query("SELECT `first_name`,`last_name`,`eid` FROM users WHERE `user_id` = '" . $info['mid'] . "'");
		$employee_info = $db->query("SELECT `first_name`,`last_name` FROM employees WHERE `uniq_id` = '" . $info['employee_id'] . "'");
		$store_name = $db->query("SELECT `store_name`,`state` as `store_state` FROM `stores` WHERE `store_id` = " . $info['store']);
		$db->trans_complete();
		$disc_type = 2;
		if ($info['terminated'] == 1) {
			$this->understand = '';
			$disc_type = 8;
		}
		
		if ($db->trans_status() !== TRUE) die ($failure . "13");


		foreach ($submitter_info->result_array() as $row) {
			foreach ($row as $key=>$val) {
				$info["submitter_$key"] = ucwords(strtolower($val));
			}
		}
		foreach ($employee_info->result_array() as $row) {
			foreach ($row as $key=>$val) {
				$info["employee_$key"] = ucwords(strtolower($val));
			}
		}
		foreach ($policies_info->result_array() as $row) {
			foreach ($row as $key=>$val) {
				$get_desc = $db->query("SELECT `policy_desc` FROM `warning_definitions` WHERE `policy_id` = $val");
				$get_desc = $get_desc->first_row();
				$info['policies_violated'][$val] = $get_desc->policy_desc;
			}
		}
		$description = $description_info->first_row();
		$info['description'] = $description->description;

		$store_name = $store_name->first_row();
		$info['store_name'] = $store_name->store_name;
		$info['store_state'] = $store_name->store_state;

		$info['warning_id'] =  $warning_id;

		$pv = $this->v + 77;

		if ($info['terminated']) {
			$title = "Disciplinary Termination";
			$understand = '';
		} else {
			$understand = "I understand what is expected of me to improve my job performance.";	
			$title = "Disciplinary Warning";
		}
		if ($warning_id = 1059) {
			$desc_extra = 2;
		}
		$_info = array
		(
		/*layout*/
		array ("type" => $this->font,"Arial","",11),
		array ("type" => $this->text,$this->h+10,$this->v+38,"Store ID"),
		array ("type" => $this->text,$this->h+50,$this->v+38,"Store Name"),
		array ("type" => $this->text,$this->h+10,$this->v+44,"Employee"),
		array ("type" => $this->text,$this->h+10,$this->v+50,"Date of incident"),
		array ("type" => $this->text,$this->h+10,$this->v+56,"Date of warning"),
		array ("type" => $this->text,$this->h+10,$this->v+62,"Person issuing warning"),
		array ("type" => $this->font,"Arial","",9),
		array ("type" => $this->text,$this->h+10,$this->v+68,"All employees are expected to abide by all rules and policies at all times."),
		array ("type" => $this->font,"Arial","B",9),
		array ("type" => $this->text,$this->h+12,$this->v+73,"Carisch, Inc. personal conduct or work rule that was violated:"),
		array ("type" => $this->set,$this->h+125,$this->v+20),
		array ("type" => $this->font,"Arial","",11),
		//array ("type" => $this->text,$this->h+10,$this->v+110,$info['employee_first_name']." ".$info['employee_last_name']." (".$info['employee_id'].") is now under disciplinary action as of this date as the result of unsatisfactory"),
		//array ("type" => $this->text,$this->h+10,$this->v+115,"job performance or conduct as described below:"),
		/* info */
		array ("type" => $this->font,$this->barcode_font,"",20),
		array ("type" => $this->text,$this->h+10,$this->v+18,$cpm->barcode($disc_type,$info['warning_id'])),
		array ("type" => $this->font,"Arial","B","20"),
		array ("type" => $this->text,$this->h+10,$this->v+30,$title),
		array ("type" => $this->font,"Arial","",11),
		array ("type" => $this->text,$this->h+10,$this->v+110+$desc_extra,$info['employee_first_name'] . " " .$info['employee_last_name'] . " (" . $info['employee_id'] . ") is now under disciplinary action as of this date as the result of unsatisfactory"),
		array ("type" => $this->text,$this->h+10,$this->v+115+$desc_extra,"job performance or conduct as described below:"),
		array ("type" => $this->font,"Courier","B",11),
		array ("type" => $this->text,$this->h+26,$this->v+38,$info['store']),
		array ("type" => $this->text,$this->h+73,$this->v+38,$info['store_name']),
		array ("type" => $this->text,$this->h+42,$this->v+44,$info['employee_first_name'] . " " .$info['employee_last_name']),
		array ("type" => $this->text,$this->h+41,$this->v+50,$info['incident_date']),
		array ("type" => $this->text,$this->h+41,$this->v+56,$info['warning_date']),
		array ("type" => $this->font,"Courier","B",11),
		array ("type" => $this->text,$this->h+53,$this->v+62,$info['submitter_first_name'] . " " .$info['submitter_last_name'] . " (" . $info['submitter_eid'] . ")"),
		array ("type" => $this->font,"Arial","",9),
		array ("type" => $this->text,$this->h+31,$this->v+250,$understand)
		);

		if ($info['store_state'] == 'MT'  &&  $info['terminated']) {
			$_info[] = array ("type" => $this->font,"Courier","B",12);
			$_info[] = array ("type" => $this->text,$this->h+126,$this->v+19,"NOTICE:");
			$_info[] = array ("type" => $this->font,"Arial","B",8);
			$_info[] = array ("type" => $this->cell,80,4,"If you wish to appeal this discharge, you may present a written statement to your Regional Manager, Human Resources, the Director of Operations, or the President within ten (10) calendar days after you are informed of the discharge.  The written statement shall recite any facts which you believe appropriate.  Your statement will be considered, and you will be advised of a final decision regarding your grievance within thirty (30) calendar days after you initiate the process.",1,"L");
		}

		$_info[] = array ("type" => $this->font,"Arial","I",9);
		$linespace = 4;
		$desc_extra = 0;
		$move_up = 0;
		if ($warning_id == 1059) {
				$_info[] = array ("type" => $this->font,"Arial","I",7);
				$linespace = 3;
				$move_up = 2;
		}
		foreach ($info['policies_violated'] as $key=>$val) {
			$_info[] = array ("type" => $this->text,$this->h+15,$this->v+$pv-$move_up,$val);
			$pv = $pv + $linespace;
		}
		$_info[] = array ("type" => $this->font,"Arial","I",9);
		$_info[] = array ("type" => $this->set,8,117+$desc_extra);
		$_info[] = array ("type" => $this->cell,190,4,$info['description'],1,"J");
		return $_info;
	}
}
?>