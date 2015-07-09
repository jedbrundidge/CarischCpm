<?php
class employee
{
	var $db;
	var $eid;
	public function __construct()
	{
		$ci =& get_instance();
		$this->db = $ci->load->database('default',TRUE);
		$this->eid = 0;
	}

	
}
?>