<?php
class Users extends Controller {


	function Users()
	{
		parent::Controller();
		$utype = $this->session->userdata('utype');
		if ($utype !== '1') {
			die("DENIED");
		}
	}

	public function set()
	{
		if ($_POST) {
			if (!empty($_POST['passwd1'])) {
				$passwd = md5($_POST['passwd1']);
				$pwd = ",passwd = '$passwd'";
			}
			
			foreach ($_POST as $key=>$val) {
				$_POST[$key] = mysql_real_escape_string($val); 
			}
			
			$q = "update users set active = $_POST[active] $pwd ,user_type = $_POST[utype] where user_id = $_POST[uid] limit 1";
			$this->db->simple_query($q);
		}
		
		$this->index($_POST['eid']);
	}

	public function index($eid)
	{
		$q = "select `user_type`,`active`,`user_id`,`user_name` FROM `users` WHERE `eid` = $eid limit 0,1";
		
		 
		$result = $this->db->query($q);

		if ($result->num_rows() < 1) {
			$result = array (
				'utype'=>false,
				'active'=>0,
				'uid'=>0,
			);
			$result['user_type'] = 100;
			$result = (object) $result;
		} else {
			$result = $result->last_row();
		}
		
		
		$utypes = array (
			1 => array ('type'=>1,'name'=>'Admin','selected'=>false),
			3 => array ('type'=>3,'name'=>'RM','selected'=>false),
			4 => array ('type'=>4,'name'=>'GM','selected'=>false),
			5 => array ('type'=>5,'name'=>'AM','selected'=>false),
			6 => array ('type'=>6,'name'=>'SM','selected'=>false),
			100 => array ('type'=>100,'name'=>'None','selected'=>false)
		);
		
		$aTrue='';
		$aFalse='';
		
		if ($result->active == 1) {
			$aTrue = 'selected';
		} else {
			$aFalse = 'selected';
		}
		
		$utypes[$result->user_type]['selected'] = 'selected';
		
		$data = array(
			'utype'=>$result->user_type,
			'active'=>$result->active,
			'uid'=>$result->user_id,
			'uname'=>$result->user_name,
			'aTrue'=>$aTrue,
			'aFalse'=>$aFalse,
			'eid'=>$eid,
			'utypes'=>$utypes
		);
		$this->load->view("top_menu_view");
		$this->parser->parse("edit_user",$data);
	}
	
	public function reset_passwd($user_id)
	{
		$user_id = (int) mysql_real_escape_string($user_id);
		$t1 = "update `users` set `passwd` = md5((select substring(`ssn_hash`,-4,4) from `employees` where `uniq_id` = `eid`)),`last_pw_change` = '1970-01-01' where `eid` = $user_id";
		$this->db->query($t1);
		$this->index($user_id);
	}
}
?>
