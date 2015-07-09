<?php
class Exports extends Controller {
	public function employees()
	{
		$uid = $this->session->userdata('uid');
		if (!$uid || $this->session->userdata('utype') > 1)
			die();
		if (!$_POST) {
			echo "<form method=\"post\">";
			echo "<input type=\"text\" name=\"from\" id=\"from\" />yyyy-mm-dd<br />";
			echo "<input type=\"text\" name=\"to\" id=\"to\" />yyyy-mm-dd<br />";
			echo "<input type=\"submit\">";
			die();
		}
		$from = mysql_real_escape_string($_POST['from']);
		$to = mysql_real_escape_string($_POST['to']);
		$query = "select e_id,ssn_hash,first_name,middle_initial,last_name,street_addr,city,employee_info.state as emp_state,zip,phone,date_format(dob,'%m%d%Y') as dob,date_format(doh,'%m%d%Y') as doh,gender,ethnicity,stores.state tax_state,store_id from employees left join employee_info on uniq_id = unique_id left join stores on store_id = store where doh >= '$from' and doh <= '$to' and store >= 5 and store <= 93";
		$result = $this->db->query($query);
		foreach ($result->result() as $row) {
			$row->first_name = ucwords(strtolower($row->first_name));
			$row->last_name = ucwords(strtolower($row->last_name));
			$row->ssn = 
				substr($row->ssn_hash,0,3) . '-' .
				substr($row->ssn_hash,3,2) . '-' .
				substr($row->ssn_hash,5,4);
			$row->phone = 
				substr($row->phone,0,3) . '-' .
				substr($row->phone,3,3) . '-' .
				substr($row->phone,6,4);
			$row->street_addr = str_replace(',','',$row->street_addr);
			//$row->last_name = str_replace(',','',$row_>last_name);
			$string = 
				"$row->e_id," .
				"$row->first_name," .
				"$row->middle_initial," .
				"$row->last_name," .
				"$row->ssn," .
				"$row->street_addr," .
				"$row->city," .
				"$row->emp_state," .
				"$row->zip," .
				"$row->phone," .
				"$row->dob," .
				"$row->doh," .
				"$row->ethnicity," .
				"$row->gender," .
				"$row->store_id," .
				"$row->tax_state" .
				chr(13) . chr(10); //CR+LF for windoze
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="Payroll_Import.csv"');
			print($string);
		}
	}
}
?>
