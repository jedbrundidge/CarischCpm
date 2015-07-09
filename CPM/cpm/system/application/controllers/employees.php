<?php
class Employees extends Controller {
	var $doc_dir = "/mnt/payroll";
	var $min_pw_length = 8;
	var $max_pw_age = 90;

	function emp_login() {
		$eid = $this->session->userdata('eid');
		if (!$eid) {
			if ($_POST) {
				$fail = false;
				$username = mysql_real_escape_string($_POST['username']);
				$password = dohash($_POST['password'], 'md5');
				$query = $this->db->query("SELECT `passwd` FROM `employees` WHERE `e_id` = '$username' AND `active` = 1");
				if ($query->num_rows() == 1) {
					$hash = $query->first_row();
					$hash = $hash->passwd;
					if ($hash == $password) {
						$user_info = $this->db->query("SELECT `uniq_id` FROM `employees` WHERE `e_id` = $username");
						foreach ($user_info->result() as $info) {
							$this->session->set_userdata('eid', $info->uniq_id);
						}
						$eid = $this->session->userdata('eid');
						if ($eid) {
							$ip = getenv("REMOTE_ADDR");
							$this->db->query("UPDATE `employees` SET `last_login` = CURRENT_TIMESTAMP WHERE `uniq_id` = $eid");
							//$this->db->query("INSERT INTO `user_log` (`iduser_log`,`logged_in`,`ip_address`) VALUES ('$eid',CURRENT_TIMESTAMP,'$ip')");
							$changepw = $this->db->query("SELECT DATEDIFF(DATE(NOW()),DATE(`last_pw_change`)) AS `days` FROM `employees` WHERE `uniq_id` = $eid");
							$changepw = $changepw->first_row();
							$uname = $this->db->query("SELECT CONCAT(`first_name`,' ',`last_name`) as `uname` FROM `employees` WHERE `uniq_id` = $eid");
							$uname = $uname->first_row();
							$this->session->set_userdata('uname', $uname->uname);
							//$this->session->set_userdata('utype',$uname->user_type);
							if ($changepw->days > $this->max_pw_age) {
								$data = array (
									"site" => "employees"
								);
								$this->load->view('changepw_view', $data);
							} else {
								header("Location: /cpm/index.php/employees/emp_home");
							}
						}
					} else {
						$fail = true;
					}
				} else {
					$fail = true;
				}
				if ($fail) {
					echo "Username or password incorrect.";
				}
			} else {
				$this->load->view('emp_login');
			}
		} else {
			header("Location: /cpm/index.php/employees/emp_home");
		}
	}

	function get_docs() {
		$eid = $this->session->userdata('eid');
		if (!$eid) {
			$this->emp_login();
		} else {
			if ($_POST) {
				$doc_array = $_POST['doc_array'];
				$pdfs = array ();
				foreach ($doc_array as $doc_id) {
					$pdf = $this->db->query("SELECT `file_hash`,`extension` FROM `payroll_docs` WHERE `trans_id` = '$doc_id' AND `emp_id` = $eid");
					$pdf = $pdf->last_row();
					$pdfs[] = "$this->doc_dir/$pdf->file_hash.$pdf->extension";
				}
	
				$qhash = sha1($eid . date('U'));
				$cmd_docs = "";
				foreach ($pdfs as $doc) {
					$cmd_docs .= "$doc ";
				}
				$cmd = "pdftk $cmd_docs cat output /dev/shm/$qhash.pdf";
				passthru($cmd);
				$handle = fopen("/dev/shm/$qhash.pdf", 'rb');
				$data = stream_get_contents($handle);
				fclose($handle);
				passthru("rm -rf /dev/shm/$qhash.pdf");
				header('Pragma: public');
				header('Expires: 0');
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline;filename="paystubs.pdf"');
				echo $data;
			}
		}
	}	

	function emp_home() {
		$eid = $this->session->userdata('eid');
		if (!$eid) {
			$this->emp_login();
		} else {
			$this->load->view('emp_home');
		}
	}

	function change_pw() {
		$eid = $this->session->userdata('eid');
		if ($eid) {
			if ($_POST && isset ($_POST['cur_passwd'])) {
				foreach ($_POST as $key => $val) {
					$$key = $val;
				}
				$get_pw = $this->db->query("SELECT `passwd` FROM `employees` WHERE `uniq_id` = $eid AND `active` = 1");
				if ($get_pw->num_rows() == 1) {
					$get_pw = $get_pw->first_row();
					if ($get_pw->passwd == md5($cur_passwd)) {
						if ($new_password1 == $new_password2) {
							if (md5($new_password1) != $get_pw->passwd && strlen($new_password1) >= $this->min_pw_length /* && other min reqs */
								) {
								$pw_hash = md5($new_password1);
								$this->db->query("UPDATE `employees` SET `passwd` = '$pw_hash', `last_pw_change` = CURRENT_TIMESTAMP WHERE `uniq_id` = $eid");
								echo "Your password has been changed, click <a href=\"/cpm/index.php/employees/manage\">here to continue.";
							} else {
								echo ("Your new password did not meet the minimum requirements, minumum 8 characters.");
							}
						} else {
							echo ("New passwords do not match.");
						}
					} else {
						echo ("Incorrect password.");
					}
				}
			} else {
				$data = array (
					'site' => 'employees'
				);
				$this->load->view('changepw_view', $data);
			}
		} else {
			$this->emp_login();
		}
	}
}
?>
