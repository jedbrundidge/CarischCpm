<?php
require_once "/var/www/htdocs/forms/swift/Swift.php"; //email sending lib
require_once "/var/www/htdocs/forms/swift/Swift/Connection/SMTP.php"; //smtp support
//include_once 'Text/Diff.php';
//include_once 'Text/Diff/Renderer/inline.php';

class Personnel extends Controller {
	var $doc_dir = "/mnt/payroll";
	var 	$hire_objects = array(
		'ssn'=>'',
	      'first_name'=>'',
	      'last_name'=>'',
	      'middle_initial'=>'',
             'other_name'=>'',
	      'street_address'=>'',
	      'apt_number'=>'',
	      'city'=>'',
	      'state'=>'',
	      'zip'=>'',
	      'county'=>'',
	      'phone'=>'',
             'email'=>'',
	      'ethnic_code'=>'',
	      'gender'=>'',
	      'marital_status'=>'',
	      'month'=>'',
	      'day'=>'',
	      'year'=>'',
	      'smonth'=>'',
	      'sday'=>'',
	      'syear'=>'',
	      'position'=>'',
	      'payrate'=>'',
	      'carisch_dimes'=>'',
	      'allowances'=>'',
	      'withheld'=>'',
	      'exempt'=>'',
	      'citizenship'=>'',
	      'alien_admission_number_permres'=>'',
	      'alien_admission_number'=>'',
	      'alien_i94_number'=>'',
	      'alien_auth_until'=>'',
	      'a_doc_title'=>'',
	      'a_issuing_auth'=>'',
	      'a_docnum'=>'',
	      'a_expiredate'=>'',
	      'a_docnum2'=>'',
	      'a_expiredate2'=>'',
	      'b_doc_title'=>'',
	      'b_issuing_auth'=>'',
	      'b_docnum'=>'',
	      'b_expiredate'=>'',
	      'c_doc_title'=>'',
	      'c_issuing_auth'=>'',
	      'c_docnum'=>'',
	      'c_expiredate'=>'',
	      'katrina'=>'',
	      'tanf'=>'',
	      'memfam'=>'',
	      'vet'=>'',
	      'vet2'=>'',
	      'vet3'=>'',
	      'katrina_address'=>'',
	      'swa'=>'');
	var $min_pw_length = 6; //chars
	var $max_pw_age = 90; //days

	var $acl = array();

	function index()
	{
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->manage();
		}
	}
	
	function trans_emp($e_id) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST) {
				$new_store = mysql_real_escape_string($_POST['employee_transfer']);
				$this->db->query("UPDATE `employees` SET `store` = $new_store WHERE `uniq_id` = $e_id");
				$this->db->query("UPDATE `users` SET `default_store` = $new_store WHERE `eid` = $e_id");
				header("location: /cpm/index.php/personnel/employee/$e_id");
			}	
		}
	}
	
	function del_basic() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST) {
				$this->db->trans_start();
				foreach ($_POST['basics'] as $id) {
					$id = mysql_real_escape_string($id);
					$this->db->query("DELETE FROM `store_basics` WHERE `basic_id` = $id LIMIT 1");
				}
				$this->db->trans_complete();
			}
			header("Location: /cpm/index.php/personnel/basics/$_POST[store_id]");
		}
		
	}
	
	function passwd_reset() {
		$this->db->trans_start();
		$user_id = $_POST['user_id'];
		$uniq_id = $this->db->query("SELECT `eid` FROM `users` WHERE `user_id` = $user_id")->last_row()->eid;
		$newpw = $this->db->query("SELECT SUBSTRING(-4,4,`ssn_hash`) AS `newpw` FROM `employees` WHERE `uniq_id` = $uniq_id")->last_row()->newpw;
		$this->db->query("UPDATE `users` SET `passwd` = MD5('$newpw'),`last_pw_change` = '0000-00-00' WHERE `user_id` = $user_id LIMIT 1");
		$this->db->trans_complete();
	}

	function update_employee($id) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			//print_r($_POST);
			foreach ($_POST as $key=>$val) {
				if (!is_array($val))
				$$key = mysql_real_escape_string($val);
			}

			$exempt = 0;
			if (isset($_POST['exemption']))
			$exempt = 1;

			if ($this->session->userdata('utype') <= 3) {
				$this->db->query("UPDATE `employee_info` SET `dop` = '$dop' WHERE `unique_id` = $id");
			}
			$this->db->query("
				UPDATE 
					`employee_info` 
				SET 
					`street_addr` = '$street_addr',
					`city` = '$city',
					`zip` = '$zip',
					`state` = '$state',
					`phone` = '$phone',
					`exemption` = $exempt,
					`marital_status` = $marital_status,
					`allowances` = $allowances,
					`additional_withholdings` = '$additional_withholdings'
				WHERE
					`unique_id` = $id
			");
			header("Location: /cpm/index.php/personnel/employee/$id");
		}
	}

	function view_note($note_id) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$array = array('note_id'=>$note_id);
			$this->load->view('top_menu_view');
			$this->load->view('note_name',$array);
			$this->load->view('note_view',$array);
		}
	}

	function view_notes($eid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$notes = $this->db->query("SELECT `note_id` FROM `notes` WHERE `emp_id` = $eid ORDER BY `date` ASC");
			$n=0;
			foreach ($notes->result() as $val) {
				$array = array('note_id'=>$val->note_id);
				$n++;
				if ($n <= 1) {
					$this->load->view('note_name',$array);
				}
				$this->load->view('note_view',$array);
			}
		}
	}
	function valssn() {
		$this->session->unset_userdata($this->hire_objects);
		$uid = $this->session->userdata('uid');
		if (!$_POST) {
			$this->load->view('top_menu_view');
			$this->load->view('valssn');
		} else {
			$ssn = mysql_real_escape_string($_POST['ssn']);
			$query = $this->db->query("SELECT `uniq_id`,`store`,`active` FROM `employees` WHERE `ssn_hash` = '$ssn'");
			if ($query->num_rows > 0) {
				$query = $query->first_row();
				$func = new cpm_functions;
				if ($func->verify_access($uid,$query->store) === true) {
					if ($query->active == 0) {
						echo "This employee is already in the system, please reactive and edit as necessary.";
					}
					echo "<br> You are being redirected...";
					header("Refresh: 4; url=/cpm/index.php/personnel/employee/$query->uniq_id");
				} elseif ($func->verify_access($uid,$query->store) === false) {
					echo "This employee is currently in the system, however you do not posses the proper security credentials to access them.  An email has been sent on your behalf to request access to this employee.";
					//echo "<input type=\"submit\" style=\"font-family: Arial;\">";
				}
			} else {
				$this->new_hire();
			}
		}
	}


	function doc_tracking() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$this->load->view('doc_tracking');
		}
	}

	function login($wid='',$type='')
	{
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			if ($_POST) {
				$fail = false;
				$username = mysql_real_escape_string($_POST['username']);
				$password = dohash($_POST['password'],'md5');
				$query = $this->db->query("SELECT `passwd` FROM `users` WHERE `user_name` = '$username' AND `active` = 1");
				if ($query->num_rows() == 1) {
					$hash = $query->first_row();
					$hash = $hash->passwd;
					if ($hash == $password) {
						$user_info = $this->db->query("SELECT `user_id` FROM `users` WHERE `user_name` = '$username'");
						foreach ($user_info->result() as $info) {
							$this->session->set_userdata('uid',$info->user_id);
						}
						$uid = $this->session->userdata('uid');
						if ($uid) {
							$ip = getenv("REMOTE_ADDR");
							$this->db->query("UPDATE `users` SET `last_login` = CURRENT_TIMESTAMP,`ip` = '$ip' WHERE `user_name` = '$username'");
							$this->db->query("INSERT INTO `user_log` (`iduser_log`,`logged_in`,`ip_address`) VALUES ('$uid',CURRENT_TIMESTAMP,'$ip')");
							$changepw = $this->db->query("SELECT DATEDIFF(DATE(NOW()),DATE(`last_pw_change`)) AS `days` FROM `users` WHERE `user_name` = '$username'");
							$changepw = $changepw->first_row();
							$uname = $this->db->query("SELECT CONCAT(`first_name`,' ',`last_name`) as `uname`,`user_type` FROM `users` WHERE `user_id` = $uid");
							$uname = $uname->first_row();
							$this->session->set_userdata('uname',$uname->uname);
							$this->session->set_userdata('utype',$uname->user_type);
							if ($changepw->days > $this->max_pw_age) {
								$this->load->view('changepw_view');
							} else {
								if ($wid && $type) {
									if ($type=="W")
									$this->warning($wid,0);
									if ($type=="T")
									$this->update_term($wid);
								} else {
									header("Location: /cpm/index.php/personnel/manage");
								}
							}
						}
					} else {
						$fail = true;
					}
				} else {
					$fail = true;
				}
				if ($fail) {
					echo "Username or password incorrect. <a href=\"/cpm/index.php/personnel/login\">Back</a>";
				}
			} else {
				$data = array('wid'=>$wid,'type'=>$type);
				$this->load->view('login_view',$data);
			}
		} else {
			if ($wid) {
				$this->warning($wid,3);
			} else {
				header("Location: /cpm/index.php/personnel/manage");
			}
		}
	}
	
	
	function emp_login() {
		$eid = $this->session->userdata('eid');
		if (!$eid) {
			if ($_POST) {
				$fail = false;
				$username = mysql_real_escape_string($_POST['username']);
				$password = dohash($_POST['password'],'md5');
				$query = $this->db->query("SELECT `passwd` FROM `employees` WHERE `e_id` = $username AND `active` = 1");
				if ($query->num_rows() == 1) {
					$hash = $query->first_row();
					$hash = $hash->passwd;
					if ($hash == $password) {
						$user_info = $this->db->query("SELECT `uniq_id` FROM `employees` WHERE `e_id` = $username");
						foreach ($user_info->result() as $info) {
							$this->session->set_userdata('eid',$info->uniq_id);
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
							$this->session->set_userdata('uname',$uname->uname);
							//$this->session->set_userdata('utype',$uname->user_type);
							if ($changepw->days > $this->max_pw_age) {
								$data = array("site"=>"personnel");
								$this->load->view('changepw_view',$data);
							} else {
								header("Location: /cpm/index.php/personnel/emp_home");
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
			header("Location: /cpm/index.php/personnel/emp_home");
		}
	}

	/*
	 public function build_acl() {
	 $query = $this->db->query(""); //get store/group access
	 //build session array of all access
	 //set $this->acl
	 }
	 */


	function show_policy($pid) {
		$query=$this->db->query("SELECT policy_desc,policy_name FROM warning_definitions WHERE policy_id = $pid");
		foreach($query->result() as $policy) {
			print "<TITLE>$policy->policy_name</TITLE>";
			print $policy->policy_desc;
		}
	}

	function manage() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$this->load->view('manage_view');
		}
	}
	
	function all_docs() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($this->session->userdata('utype') == 1) {
				$this->load->view('top_menu_view');
				$this->load->view('all_docs');
			}
		}
	}

	function settings() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$this->load->view('settings_view');
		}
	}

	function get_message() {
		$message_id = $_POST['message_id'];
		$this->db->trans_start();
		$message = $this->db->query("SELECT `message_text` FROM `inbox` WHERE `message_id` = $message_id");
		$this->db->query("UPDATE `inbox` SET `read` = 1,`read_time` = CURRENT_TIMESTAMP() WHERE `message_id` = $message_id");
		$this->db->trans_complete();
		$message = $message->last_row();
		echo $message->message_text;
	}
	
	function search_users() {
		if ($this->session->userdata('utype') == 1) {
			$data=array();
			$data['search_string'] = '';
			if ($_POST) {
				$data['search_string'] = $_POST['search'];
				$data['employees'] = $this->user_search($_POST['search']);
				if ($data['employees'] === false) {
					$data['failure'] = true;
				}
			}
			$this->load->view('top_menu_view');
			$this->load->view('user_manage',$data);
		}
	}
	
	function proc_sessions() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			//$this->load->view('login_view');
		} else {
			$fid = $_POST['fid'];
			foreach ($_POST as $key=>$val) {
				$array[$key]=$val;
			}
			$this->session->set_userdata("F$fid",$array);
			echo "1";
		}
	}

	function proc_hours() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			echo "0";
		} else {
			foreach ($_POST as $key=>$val) {
				$array[$key]=$val;
			}
			$this->session->set_userdata('f',$array);
			echo "1";
		}
	}

	function change_pw() {
		$uid = $this->session->userdata('uid');
		if ($uid) {
			if ($_POST && isset($_POST['cur_passwd'])) {
				foreach ($_POST as $key=>$val) {
					$$key = $val;
				}
				$get_pw = $this->db->query("SELECT `passwd` FROM `users` WHERE `user_id` = $uid AND `active` = 1");
				if ($get_pw->num_rows() == 1) {
					$get_pw = $get_pw->first_row();
					if ($get_pw->passwd == md5($cur_passwd)) {
						if ($new_password1 == $new_password2) {
							if (md5($new_password1) != $get_pw->passwd && strlen($new_password1) >= $this->min_pw_length /* && other min reqs */ ) {
								$pw_hash = md5($new_password1);
								$this->db->query("UPDATE `users` SET `passwd` = '$pw_hash', `last_pw_change` = CURRENT_TIMESTAMP WHERE `user_id` = $uid");
								echo "Your password has been changed, click <a href=\"/cpm/index.php/personnel/manage\">here to continue.";
							} else { echo ("Your new password did not meet the minimum requirements."); }
						} else { echo ("New passwords do not match."); }
					} else { echo ("Incorrect password."); }
				}
			} else {
				$data = array('site'=>'personnel');
				$this->load->view('changepw_view',$data);
			}
		} else {
			$this->login();
		}
	}

	private function time_sel($date,$early,$late,$inc,$span_days,$sel) {
		$retval='';
		for ($t=0;mktime($early,$t,0,date('n',$date),date('j',$date),date('Y',$date))<=mktime($late,0,0,date('n',$date),date('j',$date)+$span_days,date('Y',$date));$t=$t+30) {
			$select='';
			if (date('H:i:s',mktime($early,$t,0))==$sel) $select="SELECTED";
			$retval .= "<option $select value=\"".date('H:i:s',mktime($early,$t,0))."\">".date('h:i A',mktime($early,$t,0))."</option>";
		}
		return $retval;
	}

	function store_hours($m,$d,$y) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if (date('w',mktime(0,0,0,$m,$d,$y))==='0') {
				$week = mktime(0,0,0,$m,$d,$y);
				ob_start();
				$day_inc=0;
				echo "<table><tr><td><table width=\"100%\"><tr><td align=\"center\">";
				echo "Week (Sun - Sat) <select name=\"week\" id=\"week\" style=\"font-family: Arial;\">";
				$_d = $d;
				for ($i=0;$i<=4;$i++) {
					echo "<option value=\"".date('Y-m-d',mktime(0,0,0,$_d,$m,$y))."\">".date('M d',mktime(0,0,0,$m,$_d,$y))." - ".date('M d',mktime(0,0,0,$m,$_d+6,$y))."</option>";
					$_d=$_d+7;
				}
				echo "</select>";
				echo "</td></tr></table><br>";
				echo "<table>";
				echo "<tr><td></td><td></td><td>Open</td><td>Close</td></tr>";
				$store_id = $this->session->userdata('csid');
				if (!$store_id) {
					$store_id=99;
				}
				for ($i=1;$i<=7;$i++) {
					$date = mktime(0,0,0,$m,$d+$day_inc,$y);
					$bis_date = date('Y-m-d',$date);
					$day_string = strtolower(date('l',$date));
					unset($get_times);
					$get_times = $this->db->query("SELECT DATE_FORMAT(`open`,'%H:%i:%S') AS `open`,DATE_FORMAT(`close`,'%H:%i:%S') AS `close` FROM `store_hours` WHERE `business_day` = '$bis_date' AND `store_id` = $store_id");
					if ($get_times->num_rows() === 0) {
						$get_times = $this->db->query("SELECT DATE_FORMAT(`open`,'%H:%i:%S') AS `open`,DATE_FORMAT(`close`,'%H:%i:%S') AS `close`,MAX(`business_day`) FROM `store_hours` WHERE DAYOFWEEK(`business_day`) = $i AND `store_id` = $store_id GROUP BY `store_id`");
					}
					$get_times=$get_times->last_row();
					//if (!isset($get_times->open)) $get_times->open=0;
					//if (!isset($get_times->close)) $get_times->close=0;
					if ($i & 1) $color = "dddddd"; else $color = "ffffff";
					echo "<tr bgcolor=\"$color\"><td>".date('d',$date)."</td><td>".ucwords($day_string)."</td><td><select style=\"font-family: Arial;\" name=\"".$day_string."_opening\" id=\"".$day_string."_opening\" style=\"\">";
					echo "<option></option>";
					echo $this->time_sel($date,6,11,30,0,$get_times->open);
					echo "</select></td><td><select style=\"font-family: Arial;\" name=\"".$day_string."_closing\" id=\"".$day_string."_closing\" style=\"\">";
					echo "<option></option>";
					echo $this->time_sel($date,1,2,30,1,$get_times->close);
					echo "</select></td></tr>";
					$day_inc++;
				}
				echo "</table></td></tr><tr><td align=\"center\"><input style=\"font-family: Arial;\" type=\"submit\" name=\"submit\" value=\"Submit Hours\" /></td></tr></table>";
				$buffer = ob_get_contents();
				ob_end_clean();
				$output=array('buffer'=>$buffer);
				$this->load->view('top_menu_view');
				$this->load->view('sthours',$output);
			} else {
				echo "Day specified must be a Sunday.";
			}
		}
	}
	
	function rtipasswd() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST) {
				$this->set_rti_passwd();
			} else {
				$this->load->view('top_menu_view');
				$this->load->view('rtipwd');
			}
		}
	}
	
	
	function set_rti_passwd() {
		$username = $_POST['full_name'];
		$passwd1 = $_POST['passwd1'];
		$passwd2 = $_POST['passwd2'];
		if (sha1($passwd1) == sha1($passwd2)) {
			$ad = ldap_connect('ldaps://rticad.rticdomain.com',636) or die('Could not connect to RTI AD');

			ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
			ldap_bind($ad) or die('cannot bind anonymously');
			ldap_bind($ad,'passwdadmin@rticdomain.com','Carisch681') or die('cannot bind w/ user');

			$sResults = ldap_search($ad,'dc=rticdomain,dc=com',"(&(name=$username)(samaccountname=Carisch*))");
			$entry = ldap_first_entry($ad,$sResults);
			$dn = ldap_get_dn($ad,$entry);
			$newpass = $passwd1;
	
			$newpass = "\"". $newpass ."\"";
			$newpw = mb_convert_encoding($newpass,"UTF-16LE");		
	
			$userdata['unicodePwd'] = $newpw;
	
			ldap_mod_replace($ad,$dn,$userdata);
			$uac['useraccountcontrol'][0]="512";
			ldap_modify($ad,$dn,$uac);
			
			if (ldap_error($ad) == 'Success') {
				header("refresh:3;url=https://cpm.carischinc.com/cpm/index.php/personnel/manage");
				echo "Your password has been set.  You may now login to RTI.";
			} else {
				$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
				$swift 			=& new Swift($smtp);

				$mailbody 		= ucwords(strtolower($username)) . ' - ' . $passwd1;
				$subject 		= 'RTI Passwd Request';
				$mailto 		= "techsupport@carischinc.com";
			
				$message		=& new Swift_Message($subject,$mailbody);

				$swift->send($message, $mailto, 'cpm@carischinc.com');

				header("refresh:6;url=https://cpm.carischinc.com/cpm/index.php/personnel/manage");
				echo "There was an issue setting your password, an email has been sent to IT requesting your access.";
			}
		} else {
			
			echo 'passwords do not match';
		}
		
	}

	function disp_warn($_wid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$uid			= $this->session->userdata('uid');
			$x 			= $this->db->query("SELECT `employee_id` FROM `warnings` WHERE `warning_id` = $_wid");
			$x 			= $x->first_row();
			$eid 			= $x->employee_id;
			$store 			= substr($eid,0,2);
			$access 		= $this->cpm_functions->verify_access($uid,$store);
			$warning 		= new warning;
			$warning->_init($eid);
			$array['info'] 	= $warning->getWarning($_wid);
			$this->load->view('top_menu_view');
			$this->load->view('disp_warn',$array);
		}
	}

	function warning($warning_id = 0,$pdf = 0,$session = 0) {
		$max_session_age = 300; //seconds, 300 = 5 minutes
		$ip = getenv("REMOTE_ADDR");
		if ($session) {
			$sage = $this->db->query("SELECT `user_id`,`session_key`,TIMESTAMPDIFF(SECOND,`create_time`,CURRENT_TIMESTAMP) AS session_age,`ip` FROM `sessions` WHERE `session_key` = '$session' AND `ip` = '$ip'");
			if ($sage->num_rows() > 0) {
				$sage = $sage->first_row();
				if ($sage->session_age < $max_session_age) {
					$uid = $sage->user_id;
					$this->session->set_userdata('uid',$uid);
				} else {
					$this->db->query("DELETE FROM `sessions` WHERE `session_key` = '$session'");
				}
			}
		}

		$uid = $this->session->userdata('uid');

		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->db->query("DELETE FROM `sessions` WHERE `session_key` != '$session' AND `user_id` = $uid");
			if ($pdf) {
				if ($pdf == 3) {
					if (strlen($warning_id) == 32) {
						$tWid = $this->db->query("SELECT `warning_id` FROM `warnings` WHERE `hash` = '$warning_id'");
						$tWid = $tWid->first_row();
						$warning_id = $tWid->warning_id;
						unset($tWid);
					}
				}
				if (strlen($warning_id) < 32) {
					$this->db->query("UPDATE `warnings` SET `status` = 0 WHERE `warning_id` = $warning_id");
					$id = $this->db->query("SELECT `employee_id`,`terminated` FROM `warnings` WHERE `warning_id` = $warning_id");
					$id = $id->last_row();
					//if ($id->terminated == 1) {
					//	$this->term_user($id->employee_id);
					//}
				}
				mssql_connect('carischsql2','sa','carischsql681');
				mssql_select_db('webdocs');
				$webdocs_doc = mssql_query("SELECT DocumentKey FROM dmDocument WHERE (UserDefined7 = '$warning_id' AND (UserDefined6 = '2' OR UserDefined6 = '8') AND DocTypeKey = 'E08E96FE-1C6D-48BB-83EC-6E19BDEFFC34') ORDER BY (InsertDate)");
				if (mssql_num_rows($webdocs_doc) > 0) {
					$docu='';
					while ($ms_result = mssql_fetch_assoc($webdocs_doc)) {
						$docu .= '/webdocs/'.trim($ms_result['DocumentKey']).'_0000.PDF ';	
					}
					$qhash = sha1($uid . date('U'));
					$cmd = "pdftk $docu cat output /dev/shm/$qhash.pdf";
					passthru($cmd);
					$handle = fopen("/dev/shm/$qhash.pdf", 'rb');
					$data = stream_get_contents($handle);
					fclose($handle);
					passthru("rm -rf /dev/shm/$qhash.pdf");
					header('Pragma: public');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); //reset the content header?? fixes strange issues with ie8 and some ie6
					header('Content-Type: application/pdf');
					header('Content-Disposition: inline;filename="'.$qhash.'.pdf"');
					echo $data;
				} else {
				
					//$data = array(
				    	 //'warning_id' => $warning_id,
				     	//'pdf' => $pdf);
					//$this->load->view('pdf_warning_view',$data);
					
					
					$termed = $this->db->query("select `terminated` from `warnings` where `warning_id` = $warning_id limit 0,1")->last_row()->terminated;
					$Type = 2;
					if ($termed == 1) {
						$Type = 8;
					}
					
					redirect("/communication/doc/$warning_id/$Type","location");
					
					
				}
				//$this->session->unset_userdata('uid');
				//$this->session->destroy();
			} else {
				if (strlen($warning_id) == 32) {
					$status = $this->db->query("SELECT `status`,`warning_id` FROM `warnings` WHERE `hash` = '$warning_id'");
					$status = $status->first_row();
					if ($status->status == 3) {
						$warning_id = $status->warning_id;
						$id = $this->db->query("SELECT `employee_id`,`terminated` FROM `warnings` WHERE `warning_id` = $warning_id");
						$id = $id->last_row();
						if ($id->terminated == 1) {
							$this->term_user($id->employee_id);
						}
						$this->db->query("UPDATE `warnings` SET `status` = 0 WHERE `warning_id` = $warning_id");
						$data = array(
				     		'warning_id' => $warning_id,
				     		'pdf' => 3);
						$this->load->view('pdf_warning_view',$data);
					} else {
						$data = array('warning_id' => $warning_id,'pdf' => $pdf);
						$this->load->view('top_menu_view');
						$this->load->view('warning_view',$data);
					}
				} else {
					$data = array('warning_id' => $warning_id,'pdf' => $pdf);
					$this->load->view('top_menu_view');
					$this->load->view('warning_view',$data);
				}
			}
		}
	}

	function term_user($id) {
		$this->db->query("UPDATE `users` SET `active` = 0 WHERE `eid` = $id");
	}

	function validate_ssn($ssn) {
		$validate = false;
		for (; ; ) {
			if (strlen($ssn) != 9) break;
			$area = substr($ssn,-9,3);
			$group = substr($ssn,-6,2);
			$serial = substr($ssn,-4,4);
			if ($area == '000' ||
			$area == '666' ||
			$area > 900 ||
			$group == '00' ||
			$serial == '0000' ||
			$ssn == '078051120')
			break;
			$validate = true;
			break;
		}
		return $validate;
	}

	function view_employees($sid,$a=0) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$func = new cpm_functions;
			if ($func->verify_access($uid,$sid) === true) {
				$only_active = "";
				if ($a==1)
				$only_active = ' AND `active` = 1 ';
				$utype = $this->db->query("SELECT `user_type` FROM `users` WHERE `user_id` = $uid");
				$utype = $utype->first_row();
				$utype = $utype->user_type;
				$p = 0;
				$employees = $this->db->query("SELECT `uniq_id`,CONCAT(`first_name`,' ',`last_name`) as `name`,`position` FROM `employees` WHERE `store` = $sid AND position > $utype $only_active ORDER BY first_name,last_name");
				$this->session->set_userdata('csid',$sid);
				foreach ($employees->result() as $val) {
					$data['data'][] = array($val->uniq_id => "$val->name,$val->position");
				}
				$data['info']['sid'] = $sid;
				$data['info']['terms'] = $a; 
				$this->load->view('top_menu_view');
				$this->load->view('employee_listing',$data);
			} else {
				echo "ACCESS DENIED";
			}
		}
	}

	function create_import($uid) {

		$x = new newhire;
		$i = $x->create_import($uid);
		
		echo $i;
	}

	function edit_policies() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST) {
				foreach ($_POST as $key=>$val) {
					if ($key == "new_name") {
						$new_name = mysql_real_escape_string($val);
					}
					if ($key == "new_desc") {
						$new_desc = mysql_real_escape_string($val);
					}
					unset($name);
					unset($desc);
					$val = mysql_real_escape_string($val);
					$key = explode("-",$key);
					if (isset($key[1])) {
						if ($key[1] == "NAME") {
							$name_array[$key[0]] = $val;
						}
						if ($key[1] == "DESC") {
							$desc_array[$key[0]] = $val;
						}
					}
				}
				foreach($name_array as $key=>$val) {
					$this->db->query("UPDATE `warning_definitions` SET `policy_name` = '$val',`policy_desc` = '$desc_array[$key]' WHERE `policy_id` = $key");
				}
				if ($new_name && $new_desc) {
					$this->db->query("INSERT INTO `warning_definitions` (`policy_name`,`policy_desc`) VALUES ('$new_name','$new_desc')");
				}
			}
			$this->load->view('top_menu_view');
			$this->load->view('edit_policies');
		}
	}

	function get_pdf($eid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$store = $this->db->query("SELECT `store` FROM `employees` WHERE `uniq_id` = $eid");
			$store = $store->first_row();
			$func = new cpm_functions;
			if ($func->verify_access($uid,$store->store) === true) {
				$file = $this->db->query("SELECT `last_i9` FROM `employee_info` WHERE `unique_id` = $eid");
				$file = $file->first_row();
				header("Location: /cpm/pdf/$file->last_i9");
				//$handle = fopen("/var/pdf/$file->last_i9",'rb');
				//$data = stream_get_contents($handle);
				//fclose($handle);
				//header("Content-Type: application/pdf");
				//echo $data;
			} else {
				echo "ACCESS DENIED";
			}
		}
	}

	function set_status($id,$complete=0,$commit=0) {
		$status = $this->db->query("SELECT `status` FROM `warnings` WHERE `warning_id` = $id");
		$status = $status->first_row();
		$status = $status->status;
		$new_status = $status;
		if ($status == 1) {
			if ($this->session->userdata('uid') <= 3) {
				$new_status = 2;
			}
			if ($this->session->userdata('uid') >= 4) {
				$new_status = 2;
			}
		}
		if ($status == 2) {
			if ($complete) {
				$new_status = 3;
			} elseif (!$complete) {
				$new_status = 1;
			}
		}
		if ($commit) {
			$this->db->query("UPDATE `warnings` SET `status` = $new_status WHERE `warning_id` = $id");
		}
		return $new_status;
	}

	function add_basic() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			foreach ($_POST as $key=>$val) {
				$_POST[$key]=mysql_real_escape_string($val);
			}
			$this->db->trans_start();
			$this->db->query("INSERT INTO `store_basics` (`store_id`,`inspection_date`,`service_perc`,`quality_perc`,`clean_perc`,`brand_perc`,`total_perc`,`done_by`)VALUES ('$_POST[store_id]','$_POST[inspection_date]','$_POST[service]','$_POST[product_quality]','$_POST[cleanliness]','$_POST[brand]','$_POST[total]','$uid')");
			$this->db->trans_complete();
			/*
			$BID = $this->db->insert_id();
			$results = $this->db->query("SELECT DATE_FORMAT(`inspection_date`,'%b %d, %Y') AS `inspection_date`,`service_perc`,`quality_perc`,`clean_perc`,`brand_perc`,`total_perc`,CONCAT(UCASE(MID(LOWER(`first_name`),1,1)),MID(LOWER(`first_name`),2),' ',UCASE(MID(LOWER(`last_name`),1,1)),MID(LOWER(`last_name`),2)) AS `done_by` FROM `store_basics` LEFT JOIN `users` ON `done_by` = `user_id` WHERE `basic_id` = $BID");
			
			foreach ($results->result_array() as $res) {
				echo implode(',,',$res);
			}
			*/
			header("Location: /cpm/index.php/personnel/basics/$_POST[store_id]");
		}
	}

	function edit_term_reasons() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$this->load->view('edit_term_reasons');
		}
	}

	function promote($eid,$store) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$query = $this->db->query("SELECT `ssn_hash` AS `ssn`,`position`,`rate`,`pay_type` FROM `employees` JOIN `employee_wage` ON `uniq_id` = `unique_id` WHERE `uniq_id` = $eid");
			$query = $query->first_row();
			$func = new cpm_functions;
			$newhire = new newhire;
			if (($func->verify_access($uid,$store) === true && $this->session->userdata('utype') < $query->position) || $this->session->userdata('utype') <= 2) {
				switch ($query->position) {
					case 8:
						$new_pos = 6;
						$paytype = 0;
						$payrate = $query->rate;
						$override = 0;
						break;
					case 6:
						$new_pos = 5;
						$paytype = 0;
						$payrate = $query->rate;
						$override = 1;
						break;
					case 5:
						$new_pos = 4;
						$paytype = 1;
						$payrate = $query->rate * 80 * 26;
						$override = 1;
						break;
					default:
						$new_pos = $query->position;
						$payrate = $query->rate;
						$paytype = $query->pay_type;
						$override = 0;
						break;
				}
				$new_cid = $newhire->get_carisch_id($query->ssn,$store,$new_pos,$override);
				$this->db->trans_start();
				$this->db->query("UPDATE `employees` SET `position` = $new_pos WHERE `uniq_id` = $eid");
				$this->db->query("UPDATE `employee_wage` SET `rate` = '$payrate', pay_type = $paytype WHERE `unique_id` = $eid");
				$this->db->query("UPDATE `employee_info` SET `dop` = CURRENT_DATE() WHERE `unique_id` = $eid");
				$this->db->trans_complete();
				header("location:/cpm/index.php/personnel/employee/$eid");

			}
		}
	}

	function demote($eid) {

	}

	function set_rate($eid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login');
		} else {
			if ($_POST) {
				$new_rate = mysql_real_escape_string($_POST['new_rate']);
				$this->db->query("INSERT INTO `employee_wage` (`rate`,`dor`,`approved_by`,`unique_id`) VALUES ('$new_rate',CURRENT_DATE(),$uid,$eid)");

			}
			header("Location: /cpm/index.php/personnel/employee/$eid");
		}
	}

	function new_note($eid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login');
		} else {
			if (!$_POST) {
				$arr = array ('eid'=>$eid);
				$this->load->view('top_menu_view');
				$this->load->view('new_note',$arr);
			} else {
				$additional = $_POST['additional'];
				$note = mysql_real_escape_string($_POST['note']);
				$subject = mysql_real_escape_string($_POST['subject']);
				foreach ($additional as $id) {
					$this->db->query("INSERT INTO `notes` (`emp_id`,`date`,`description`,`manager`,`subject`) VALUES ($id,CURRENT_TIMESTAMP(),'$note',$uid,'$subject')");
				}
				header("Location: /cpm/index.php/personnel/employee/$eid");
			}
		}
	}

	function new_warning($eid=0,$commit=0) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$ident = array('eid'=>$eid);

			if (!$commit) {
				foreach ($_POST as $key=>$val) {
					$this->session->set_userdata($key,$val);
				}
				$this->load->view('top_menu_view');
				$this->load->view('newwarning_view',$ident);
			} elseif ($commit==1) {
				//commit the warning if $commit == 2, set the finish later flag (and allow an incomplete document)
				//remember to set reviewers id (regional and/or hr)
				//


				$info = $_POST;
				$info['incident_submitted'] = date('Y-m-d');
				$info['warning_date'] = date('Y-m-d');
				$WarningID = $this->cpm_sql->commit_warning($info);
				if ($WarningID > 1) {
					$data = array ('warning_id' => $WarningID);
					$warning_hash 	= md5($WarningID);
					$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
					$swift 			=& new Swift($smtp);
					$mailbody 		= "You have a review pending.  https://cpm.carischinc.com      [$WarningID]";
					$subject 		= 'Review Pending';
					$mid 			= $this->db->query("SELECT `requested_reviewer` FROM `warnings` WHERE `warning_id` = $WarningID");
					$mid 			= $mid->first_row();
					$mid			= explode(',',$mid->requested_reviewer);
					$mid			= $mid[0];
					$mailto 		= $this->db->query("SELECT `email` FROM `users` WHERE `user_id` = $mid");
					if ($mailto->num_rows() > 0) {
						$mailto			= $mailto->first_row();
						$mailto			= $mailto->email;
						$message		=& new Swift_Message($subject,$mailbody);
						$swift->send($message, $mailto, 'cpm@carischinc.com');
					}
					//$this->load->view('top_menu_view');
					//$this->load->view('distp',$data);
					//$this->disp_warn($WarningID);

					$this->manage();
				} else {
					$this->load->view('top_menu_view');
					echo "There was an error in the request.";
				}
			} elseif ($commit==2) {
				$uid = $this->session->userdata('uid');
				if (isset($_POST['day'])) {
					$this->session->set_userdata('saved',mysql_real_escape_string(serialize($_POST)));
				}
				$saved = $this->session->userdata('saved');

			}
		}
	}
	
	function basic_report() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST) {
				$groups = implode(',',$_POST['groups']);
				$stores = implode(',',$_POST['stores']);
				
				$p_stores = $stores;
				
				if (!empty($groups)) {
					$gResolve = $this->db->query("SELECT `group_stores`,`group_desc` FROM `groups` LEFT JOIN `group_descriptions` ON `group_id` = `idgroups` WHERE `idgroups` IN ($groups)");
					foreach ($gResolve->result_array() as $row) {
						$stores_array[] = $row['group_stores'];
						$store_group[$row['group_desc']] = true;
					}
					$stores .= implode(',',$stores_array);
				}
				$stores = (string) $stores;				
				$results = $this->db->query("SELECT AVG(`service_perc`) service,AVG(`quality_perc`) quality,AVG(`clean_perc`) clean,AVG(`brand_perc`) brand,AVG(total_perc) total FROM `store_basics` WHERE `store_id` IN ($stores) AND `inspection_date` BETWEEN '$_POST[from]' AND '$_POST[to]'");
				$results = $results->result_array();
				
				
				$info = array('stores'=>$p_stores,'from'=>$_POST['from'],'to'=>$_POST['to'],'store_group'=>$store_group);
				$data = array ('data' => $results,'info' => $info);
			}
			$this->load->view('top_menu_view');
			$this->load->view('basic_report',$data);
		}
	}
	
	
	function revoke($type,$id) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			switch ($type) {
				case 't':
				$table = 'terminations';
				$id_type = 'termination_id';
				$termd = true;
				break;
				case 'w':
				$termd=$this->db->query("SELECT `terminated` FROM `warnings` WHERE `warning_id` = $id");
				$termd=$termd->first_row();
				if ($termd->terminated == 1) {
					$termd = true;
				} else {
					$termd = false;
				}
				$table = 'warnings';
				$id_type = 'warning_id';
				break;
			}
			$this->db->trans_start();
			$this->db->query("UPDATE `$table` SET `status` = 99 WHERE `$id_type` = $id");
			if ($termd) {
				$get_eid=$this->db->query("SELECT `employee_id` FROM `$table` WHERE `$id_type` = $id");
				$eid=$get_eid->first_row();
				$this->db->query("UPDATE `employees` SET `active` = 1 WHERE `uniq_id` = $eid->employee_id");
				$this->db->query("UPDATE `employee_info` SET `dot` = '0000-00-00' WHERE `unique_id` = $eid->employee_id");
			}
			$this->db->trans_complete();
			header("Location: /cpm/index.php/personnel/manage");
		}
	}
	
	function val_8850() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			echo 'NO!';
		} else {
			$iArray=explode(',',$_POST['ids']);
			foreach ($iArray as $doc) {
				$this->db->trans_start();
				$this->db->query("UPDATE `employee_docs` SET `status` = 1,`scanned` = 1,`date_scanned` = CURRENT_DATE() WHERE `uniq_id` = $doc AND `document` = 8850");
				if ($this->db->affected_rows() === 0 || $this->db->trans_status() === false) {
					$failures[0] = 1;
					$failures[] = $doc;
					$this->db->trans_rollback();
				} else {
					$success[0] = 0;
					$sucesss[] = $doc;
					$this->db->trans_complete();
				}
			}
			if (isset($failures)) {
				echo implode(',',$failures);
			} else {
				echo implode(',',$success);
			}
		}
	}
	
	function basic_view($store) {
		$data = array ('store'=>$store);
		//$this->load->view('top_menu_view');
		$this->load->view('basic_view',$data);
	}
	
	function basics($store) {
		$data = array ('store'=>$store);
		$this->load->view('top_menu_view');
		$this->load->view('basic',$data);
	}
	function nav() {
		$this->load->view('nav_tabs');
	}
	function get_ind_stubs() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST && isset($_POST['ids'])) {
				$eid = $_POST['ids'];
				$from = mysql_real_escape_string($_POST['from']);
				$to = mysql_real_escape_string($_POST['to']);
				$pdfs = array ();
				//foreach ($doc_array as $eid) {
					$pdf = $this->db->query("SELECT `file_hash`,`extension` FROM `payroll_docs` WHERE `emp_id` = $eid AND `doc_date` >= '$from' AND `doc_date` <= '$to'");
					foreach ($pdf->result() as $res) {
						$pdfs[] = "$this->doc_dir/$res->file_hash.$res->extension";
					}
				//}
	
				$qhash = sha1($uid . date('U'));
				$cmd_docs = "";
				foreach ($pdfs as $doc) {
					$cmd_docs .= "$doc ";
				}
				if ($cmd_docs) {
					$cmd = "pdftk $cmd_docs cat output /dev/shm/$qhash.pdf";
					passthru($cmd);
					$handle = fopen("/dev/shm/$qhash.pdf", 'rb');
					$data = stream_get_contents($handle);
					fclose($handle);
					passthru("rm -rf /dev/shm/$qhash.pdf");
					header('Pragma: public');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Content-Type: application/pdf');
					header('Content-Disposition: inline;filename="paystubs.pdf"');
					echo $data;
				} else {
					$this->load->view("top_menu_view");
					echo "There are no paystubs on file for the employee(s) selected. (Check Date: $from -> $to)";
				}
			} else {
				$this->load->view("top_menu_view");
				echo "You did not select any employees.";
			}
		}
	}
	
	function getstubs() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST && isset($_POST['ids'])) {
				$doc_array = $_POST['ids'];
				$doc_date = $_POST['dp'];
				$pdfs = array ();
				foreach ($doc_array as $eid) {
					$pdf = $this->db->query("SELECT `file_hash`,`extension` FROM `payroll_docs` WHERE `emp_id` = $eid AND `doc_date` = '$doc_date'");
					foreach ($pdf->result() as $res) {
						$pdfs[] = "$this->doc_dir/$res->file_hash.$res->extension";
					}
				}
	
				$qhash = sha1($uid . date('U'));
				$cmd_docs = "";
				foreach ($pdfs as $doc) {
					$cmd_docs .= "$doc ";
				}
				if ($cmd_docs) {
					$cmd = "pdftk $cmd_docs cat output /dev/shm/$qhash.pdf";
					passthru($cmd);
					$handle = fopen("/dev/shm/$qhash.pdf", 'rb');
					$data = stream_get_contents($handle);
					fclose($handle);
					passthru("rm -rf /dev/shm/$qhash.pdf");
					header('Pragma: public');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Content-Type: application/pdf');
					header('Content-Disposition: inline;filename="paystubs.pdf"');
					echo $data;
				} else {
					$this->load->view("top_menu_view");
					echo "There are no paystubs on file for the employee(s) selected. (Check Date: $doc_date)";
				}
			} else {
				$this->load->view("top_menu_view");
				echo "You did not select any employees.";
			}
		}
	}

	function do_error() {
		echo $soda;
	}

	function tools() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			if ($this->session->userdata('utype') == 1) {
				$this->load->view('tools');
			}
		}
	}

	function update_warning() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$warning_id 		= mysql_real_escape_string($_POST['warning_id']);
			$desc 				= mysql_real_escape_string($_POST['description']);
			$incident_date 		= mysql_real_escape_string($_POST['incident_date']);
			//$warning_date 		= mysql_real_escape_string($_POST['warning_date']);
			$uniq_id			= mysql_real_escape_string($_POST['uniq_id']);
			$old_desc = $this->db->query("SELECT `description` FROM `warning_descriptions` WHERE `warning_num` = $warning_id");
			$check_status = $this->db->query("SELECT `status`,`store`,`requested_reviewer`,`submitting_user_id` FROM `warnings` WHERE `warning_id` = $warning_id");
			$check_status = $check_status->first_row();
			$store = $check_status->store;
			$status = $check_status->status;
			$reqrev = $check_status->requested_reviewer;
			$subid = $check_status->submitting_user_id;
			$old_desc = $old_desc->first_row();
			$old_desc = explode('\n',$old_desc->description);
			$new_desc = explode('\n',$desc);
			//$diff = new Text_Diff('native', array($old_desc,$new_desc));
			//$renderer = new Text_Diff_Renderer_inline();
			//$track_desc = mysql_real_escape_string($renderer->render($diff));
			$regional_uid = $this->db->query("SELECT `idgroups` FROM `groups` WHERE `group_stores` = '$store' AND `idgroups` != 14");
			foreach ($regional_uid->result() as $val) {
				$rrid = $this->db->query("SELECT `user_id` FROM `users` WHERE `group_id` = '$val->idgroups' AND user_type = 3");
				foreach ($rrid->result() as $_id) {
					$review_id = $_id->user_id;
				}
			}
			
			if (!isset($review_id) || $review_id < 1) {
				$review_id = 9138;
			}
			/*
			 if ($check_status->status == 2) {
				$status = 3;
				$warning_date = date('Y-m-d');
				}
				*/
			$resubmit = 0;
			if (isset($_POST['resubmit'])) {
				$resubmit = 1;
			}

			$lock = 0;
			if (isset($_POST['lock'])) {
				$lock = 1;
			}

			$status = $this->set_status($warning_id,!$resubmit,0);
			if ($lock) {
				$status = 3;
			}

			$terminate = 0;
			if (isset($_POST['terminated'])) {
				$terminate = 1;
			}
			if ($status == 4) {
				$this->db->query("UPDATE `warnings` SET `hr_rev` = 1 WHERE `warning_id` = $warning_id");
				$status = 1;

			}
			if ($reqrev == $subid) {
				$status = 5;
			}
			if (isset($_POST['review'])) {
				$lock = 0;
				$review_id = 9138; //missy
				$status = 4;
			}
			$this->db->trans_start();

			$incident_id 		= $this->db->query("
			    SELECT 
			        `incident_num`,
			        `store` 
			    FROM 
			        `warnings` 
			    WHERE 
			        `warning_id` = $warning_id
			");
			$incident_id 		= $incident_id->first_row();
			$i_id 				= $incident_id->incident_num;
			$store_id			= $incident_id->store;
			//$this->db->query("INSERT INTO `warning_prev_descriptions` VALUES ($warning_id,'$track_desc')");
			$this->db->query("
			    UPDATE 
			        `warning_descriptions` 
			    SET 
			        `description` = '$desc' 
			    WHERE 
			        `warning_num` = $warning_id
			");
			$this->db->query("
			    UPDATE 
			        `warning_incidents` 
			    SET 
			        `incident_date` = '$incident_date' 
			    WHERE 
			        `incident_id` = $i_id
			");
			$this->db->query("
			    UPDATE 
			        `warnings` 
			    SET 
			        `status` = $status,
			    	`review_date` = CURRENT_DATE(),
			    	`terminated` = $terminate,
			    	`requested_reviewer` = $review_id
			    WHERE 
			        `warning_id` = $warning_id
			");
			if ($terminate) {
				$this->db->query("
					UPDATE
						`employees`
					SET
						`active` = 0
					WHERE
						`uniq_id` = $uniq_id
				");
			}

			$this->db->query("DELETE FROM `warning_violated` WHERE `idwarning_ids` = $warning_id");
			//if (isset($_POST['pol']) && count($_POST['pol']) > 0) {
				foreach ($_POST['pol'] as $val) {
					$this->db->query("INSERT INTO `warning_violated` (`idwarning_ids`,`policy_violated`) VALUES ($warning_id,$val)");
				}
			//}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				$this->load->view("top_menu_view");
				echo "Transaction Failure. (cp278)";
			} else {
				if ($status == 0) {
					$this->warning($warning_id,3);
				}
				if ($status == 3) {
					if (!$lock) {
						$this->db->query("UPDATE `warnings` SET `warning_date` = CURRENT_DATE() WHERE `warning_id` = $warning_id");
						$this->warning($warning_id,3);
					} else {
						$this->send_comp_email($store_id,$warning_id);
						$this->load->view('top_menu_view');
						$this->load->view('manage_view');
					}
				}
				if ($status == 2) {
					$this->send_comp_email($store_id,$warning_id);
					$this->load->view('top_menu_view');
					$this->load->view('manage_view');


				}
				if ($status == 1) {
					$this->load->view('top_menu_view');
					$this->load->view('manage_view');
					$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
					$swift 			=& new Swift($smtp);
					$mailbody 		= "You have a review pending.  https://cpm.carischinc.com    [$warning_id]";
					$subject 		= 'Review Pending';
					$mid 			= $this->db->query("SELECT `requested_reviewer` FROM `warnings` WHERE `warning_id` = $warning_id");
					$mid 			= $mid->first_row();
					$mid			= explode(',',$mid->requested_reviewer);
					$mid			= $mid[0];
					$mailto 		= $this->db->query("SELECT `email` FROM `users` WHERE `user_id` = $mid");
					if ($mailto->num_rows() > 0) {
						$mailto			= $mailto->first_row();
						$mailto			= $mailto->email;
						$message		=& new Swift_Message($subject,$mailbody);
						$swift->send($message, $mailto, 'cpm@carischinc.com');
					}
					$this->load->view('top_menu_view');
					$this->load->view('manage_view');
				}
				if ($status == 4) {
					$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
					$swift 			=& new Swift($smtp);
					$mailbody 		= "You have a review pending.  https://cpm.carischinc.com    [$warning_id]";
					$subject 		= 'Review Pending';
					$mid 			= $this->db->query("SELECT `requested_reviewer` FROM `warnings` WHERE `warning_id` = $warning_id");
					$mid 			= $mid->first_row();
					$mid			= explode(',',$mid->requested_reviewer);
					$mid			= $mid[0];
					$mailto 		= $this->db->query("SELECT `email` FROM `users` WHERE `user_id` = $mid");
					if ($mailto->num_rows() > 0) {
						$mailto			= $mailto->first_row();
						$mailto			= $mailto->email;
						$message		=& new Swift_Message($subject,$mailbody);
						$swift->send($message, $mailto, 'cpm@carischinc.com');
					}
					$this->load->view('top_menu_view');
					$this->load->view('manage_view');
				}
				if ($status == 5) {
					$this->db->query("UPDATE `warnings` SET `warning_date` = CURRENT_DATE(),`status` = 0 WHERE `warning_id` = $warning_id");
					$this->warning($warning_id,3);
				}
			}
		}
	}

	function send_manager_email() {

	}

	function send_comp_email($store_id,$warning_id) {
		$warning_hash 	= md5($warning_id);
		$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
		$swift 			=& new Swift($smtp);
		$mailbody 		= "Your employee documents have been completed and can be reviewed and printed from the following link:\n https://cpm.carischinc.com/cpm/index.php/personnel/login/$warning_hash/W \n[    [$warning_id]";
		$subject 		= 'Carisch Personnel Management';
		$mailto 		= $this->db->query("SELECT `email` FROM `stores` WHERE `store_id` = $store_id");
		$mailto			= $mailto->first_row();
		$mailto			= $mailto->email;
		$message		=& new Swift_Message($subject,$mailbody);
		$swift->send($message, $mailto, 'cpm@carischinc.com');
	}
	function save_form() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$info = $_POST;
			$uid = $this->session->userdata('uid');
			$eid = $info['eid'];
			$data = mysql_real_escape_string(serialize($_POST));
			$this->db->query("INSERT INTO `saved_forms` (`user_id`,`e_id`,`data`) VALUES ('$uid','$eid','$data')");
			echo 1;
		}
	}
	
	function load_form($eid,$new=true) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($uid == 15808) {
				$uid_filter = '';
			} else {
				$uid_filter = " AND `user_id` = $uid ";
			}
			$eid = mysql_real_escape_string($eid);
			$serial = $this->db->query("SELECT `data` FROM `saved_forms` WHERE `e_id` = $eid $uid_filter AND `visible` = 1");
			$data = unserialize($serial->last_row()->data);
			foreach ($data as $key=>$val) {
				$this->session->set_userdata("$key",$val);
			}
			$sodar = array('eid'=>$eid);
			$this->load->view('top_menu_view');
			if ($new) $this->load->view('newwarning_view',$sodar);
			else $this->load->view('warning_view',$sodar);
		}
	}

	function new_termination($eid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$info = array("eid"=>$eid);
			$this->load->view('top_menu_view');
			$this->load->view('newtermination_view',$info);
		}
	}

	//function reactivate($id,$date,$wage,$paytype,$dimes) {
	function reactivate($id) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			//@TODO Track all date changes (rehires etc)
			$this->db->trans_start();
			$this->db->query("UPDATE `employees` SET `active` = 1 WHERE `uniq_id` = $id");
			//$this->db->query("UPDATE `employee_info` SET `doh` = '$date' WHERE `unique_id` = $id");
			//$this->db->query("INSERT INTO `employee_wage` VALUES ($id,'$wage','$date',$paytype,$dimes,0,$uid)");
			$this->db->trans_complete();
			header("Location: /cpm/index.php/personnel/employee/$id");
		}
	}



	function update_term($tid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if (strlen($tid) == 32) {
				$tid = $this->db->query("SELECT `termination_id`,`status` FROM `terminations` WHERE `thash` = '$tid'");
				$tid = $tid->first_row();
				$status = $tid->status;
				$tid = $tid->termination_id;
				$arr =  array('termination_id'=>$tid);
				if ($status == 3) {
					$this->db->query("UPDATE `terminations` SET `status` = 0 WHERE `termination_id` = $tid");
					$arr = array ('tid'=>$tid);
					$this->load->view('pdf_termination',$arr);
				} else {
					$this->load->view('top_menu_view');
					$this->load->view('edit_term',$arr);
				}
			} else {
				$status = $this->db->query("SELECT `status`,`store` FROM `terminations` WHERE `termination_id` = $tid");
				$store = $status->first_row();
				$store = $store->store;
				$status = $status->first_row();
				$status = $status->status;
				$status++;

				$rehire='';
				$no_rehire_desc='';
				if ($_POST) {
					foreach ($_POST as $key=>$val) {
						$$key = mysql_real_escape_string($val);
					}

					$this->db->trans_start();
					$this->db->query("UPDATE `terminations` SET `last_day_worked` = '$last_day_worked', `termination_date` = '$termination_date', `rehire` = '$rehire',`status` = $status, `reason` = '$reason' WHERE `termination_id` = $tid");
					$this->db->query("UPDATE `termination_descriptions` SET `termination_details` = '$description', `no_rehire_desc` = '$no_rehire_desc' WHERE `termination_id` = $tid");
					$this->db->trans_complete();
				}
				if ($this->db->trans_status() === TRUE) {
					if ($status == 2) {
						$this->load->view('top_menu_view');
						$this->load->view('manage_view');

						$warning_hash 	= md5($tid);
						$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
						$swift 			=& new Swift($smtp);
						$mailbody 		= "Your employee documents (voluntary termination # $tid) have been completed and can be reviewed and printed by logging into the CPM and looking under Pending Reviews and Alerts";  // from the following link:\n https://cpm.carischinc.com/cpm/index.php/personnel/login/$warning_hash/T \n [$tid]";
						$subject 		= 'Carisch Personnel Management';
						$mailto 		= $this->db->query("SELECT `email` FROM `stores` WHERE `store_id` = $store");
						$mailto			= $mailto->first_row();
						$mailto			= $mailto->email;
						$message		=& new Swift_Message($subject,$mailbody);
						$swift->send($message, $mailto, 'cpm@carischinc.com');
						header("Location: /cpm/index.php/personnel");
					} elseif ($status == 3) {
						$this->db->query("UPDATE `terminations` SET `status` = 0 WHERE `termination_id` = $tid");
						$arr = array ('tid'=>$tid);
						$this->load->view('pdf_termination',$arr);
					}
				} elseif ($this->db->trans_status === FALSE)
				echo "Transaction failure, please contact david@carischinc.com (Error Code: CtlUpTrmTrans)";
			}
		}
	}

	function termination($tid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$info = array ("termination_id" => $tid);
			$this->load->view('top_menu_view');
			$this->load->view('edit_term',$info);
		}
	}

	function employee($eu_id,$a=0) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$info = array ('e_id'=>$eu_id,'actives'=>$a);
			$this->load->view('top_menu_view');
			$this->load->view('employee_view',$info);
		}
	}

	function term_pdf($tid,$session) {
		$max_session_age = 300; //seconds, 300 = 5 minutes
		$ip = getenv("REMOTE_ADDR");
		if ($session) {
			$sage = $this->db->query("SELECT `user_id`,`session_key`,TIMESTAMPDIFF(SECOND,`create_time`,CURRENT_TIMESTAMP) AS session_age,`ip` FROM `sessions` WHERE `session_key` = '$session' AND `ip` = '$ip'");
			if ($sage->num_rows() > 0) {
				$sage = $sage->first_row();
				if ($sage->session_age < $max_session_age) {
					$uid = $sage->user_id;
					$this->session->set_userdata('uid',$uid);
				} else {
					$this->db->query("DELETE FROM `sessions` WHERE `session_key` = '$session'");
				}
			}
		}
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			mssql_connect('carischsql2','sa','carischsql681');
			mssql_select_db('webdocs');
			$webdocs_doc = mssql_query("SELECT DocumentKey FROM dmDocument WHERE (UserDefined7 = '$tid' AND UserDefined6 = '4' AND DocTypeKey = 'E08E96FE-1C6D-48BB-83EC-6E19BDEFFC34') ORDER BY (InsertDate)");
			if (mssql_num_rows($webdocs_doc) > 0) {
				$docu='';
				while ($ms_result = mssql_fetch_assoc($webdocs_doc)) {
					$docu .= '/webdocs/'.trim($ms_result['DocumentKey']) . '_0000.PDF ';
				}
				$qhash = sha1($uid . date('U'));
				$cmd = "pdftk $docu cat output /dev/shm/$qhash.pdf";
				passthru($cmd);
				$handle = fopen("/dev/shm/$qhash.pdf", 'rb');
				$data = stream_get_contents($handle);
				fclose($handle);
				passthru("rm -rf /dev/shm/$qhash.pdf");
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline;filename="'.$qhash.'.pdf"');
				echo $data;
			} else {
				$info = array ('tid'=>$tid);
				$this->load->view('pdf_termination.php',$info);
			}
		}
	}

	function post_hire() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$hire_obj = new newhire;
			//$new_id = $hire_obj->get_carisch_id(
			//$this->session->userdata('ssn'),
			//$this->session->userdata('csid'),
			//$this->session->userdata('position')
			//);

			$uniq_id = $hire_obj->commit();
			//$this->session->unset_userdata('uid');
			//$this->session->destroy();
			//$this->session->set_userdata('uid',$uid);
			//$TODO Display a menu to either edit or generate PDF documents, flag as pending for doc tracking.
			if (!$uniq_id) {
				$this->load->view('top_menu_view');
				$this->load->view('failures');
			} else {
				//print('<meta http-equiv="refresh" content="2; URL=/cpm/index.php/personnel/manage">');
				$this->pdf($uniq_id,"1.9.10.7.5.8850.11",1);
				$x = new newhire;
				$i = $x->create_import($uniq_id);
				//
				//$this->manage();
			}
		}
	}
	
	public function create_weeks_import($date) {
		$ids = $this->db->query("select unique_id from employee_info where doh >= '$date' order by doh");
		foreach ($ids->result() as $id) {
			$this->create_import($id->unique_id);
		}
	}

	//usort functions, MUST BE DECLARED static!!!
	public static function cmpx($a,$b) {
		return strcmp(strftime('%s',strtotime($a['date'])), strftime('%s',strtotime($b['date'])));
	}
	public static function cmpp($a,$b) {
		return strcmp($a['priority'],$b['priority']);
	}

	function email() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			if ($_POST) {
				$email = mysql_real_escape_string($_POST['email_addr']);
				$this->db->query("UPDATE `users` SET `email` = '$email' WHERE `user_id` = $uid");
			}
			$email_addr = $this->db->query("SELECT `email` FROM `users` WHERE `user_id` = $uid");
			$email_addr = $email_addr->first_row();
			$info = array ('email_addr'=>$email_addr->email);
			$this->load->view('top_menu_view');
			$this->load->view('mail',$info);
		}
	}

	function cvw($eid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$store = $this->session->userdata('csid');
			$info = $_POST;
			$reason = "000";
			foreach ($info as $key=>$val) {
				$$key = mysql_real_escape_string($val);
			}
			$term_date = "$year-$month-$day";
			$last_date = "$lyear-$lmonth-$lday";
			if ($rehire == "No") {
				$rehire = 0;
			} elseif ($rehire == "Yes") {
				$rehire = 1;
			} elseif ($rehire == "Conditionally") {
				$rehire = 2;
			}
			
			$this->db->trans_start();
			$regional_uid = $this->db->query("SELECT `idgroups` FROM `groups` WHERE `group_stores` = '$store' AND `idgroups` != 14");
			foreach ($regional_uid->result() as $val) {
				$rrid = $this->db->query("SELECT `user_id` FROM `users` WHERE `group_id` = '$val->idgroups' AND user_type = 3");
				foreach ($rrid->result() as $_id) {
					$rr = $_id->user_id;
				}
			}
			if (!isset($rr)) {
				$rr = 9138;
			}
			$this->db->query("INSERT INTO `terminations` (`employee_id`,`manager_id`,`termination_date`,`last_day_worked`,`separation`,`reason`,`rehire`,`store`,`status`,`requested_reviewer`) VALUES
		 ($eid,$uid,'$term_date','$last_date','0','$reason',$rehire,$store,1,$rr)");
			$term_id = $this->db->insert_id();
			$this->db->query("UPDATE `terminations` SET `thash` = MD5(`termination_id`) WHERE `termination_id` = $term_id");
			$this->db->query("INSERT INTO `termination_descriptions` (`termination_id`,`termination_details`,`no_rehire_desc`) VALUES ($term_id,'$term_details','$no_rehire_desc')");
			$this->db->query("UPDATE `employees` SET `active` = 0 WHERE `uniq_id` = $eid");
			$this->db->query("UPDATE `employee_info` SET `dot` = '$term_date' WHERE `unique_id` = $eid");
			//$this->db->query("UPDATE `users` SET `active` = 0 WHERE `eid` = $eid");
			$check_user = $this->db->query("SELECT `user_id` FROM `users` WHERE `eid` = $eid");
			if ($check_user->num_rows() > 0) {
				$check_user = $check_user->first_row();
				$this->db->query("UPDATE `users` SET `active` = 0 WHERE `user_id` = $check_user->user_id");
			}
			$this->db->trans_complete();
			$get_emp_for_notice = $this->db->query("SELECT `first_name`,`last_name`,substring(`ssn_hash`,-4,4) as `ssn_hash` FROM `employees` WHERE `uniq_id` = $eid AND `position` <= 5 LIMIT 0,1");
			if ($get_emp_for_notice->num_rows() === 1) {
				$notice_info = $get_emp_for_notice->first_row();
				$smtp 			=& new Swift_Connection_SMTP("mail.carischinc.com", 25);
				$swift 			=& new Swift($smtp);

				$mailbody 		= 'A termination process has been initiated for ' . ucwords(strtolower($notice_info->first_name . ' ' . $notice_info->last_name)) . ' (XXX-XX-' . $notice_info->ssn_hash . ').';
				$subject 		= 'Termination Notification';
				$mailto 		= "hr@carischinc.com";
	
				$message		=& new Swift_Message($subject,$mailbody);

				$swift->send($message, $mailto, 'cpm@carischinc.com');
			}
			header('location:/cpm/index.php/personnel/manage');
		}
	}

	function view_changes($session=0) {
		$max_session_age = 300; //seconds, 300 = 5 minutes
		$ip = getenv("REMOTE_ADDR");
		if ($session) {
			$sage = $this->db->query("SELECT `user_id`,`session_key`,TIMESTAMPDIFF(SECOND,`create_time`,CURRENT_TIMESTAMP) AS session_age,`ip` FROM `sessions` WHERE `session_key` = '$session' AND `ip` = '$ip'");
			if ($sage->num_rows() > 0) {
				$sage = $sage->first_row();
				if ($sage->session_age < $max_session_age) {
					$uid = $sage->user_id;
					$this->session->set_userdata('uid',$uid);
				} else {
					$this->db->query("DELETE FROM `sessions` WHERE `session_key` = '$session'");
				}
			}
		}
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
		}
	}

	function inbox() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$this->load->view('inbox');
		}
	}

	function news() {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$this->load->view('news');
		}
	}
	


	function new_hire($page = 1) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$data = array();
			if ($_POST) {
				foreach($_POST as $key=>$val) {
					$this->session->set_userdata($key,$val);
				}
			}
			$this->load->view('top_menu_view');
			$this->load->view("newhire_view$page",$data);
		}
	}

	public function edit_wages($ueid) {
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$this->load->view('top_menu_view');
			$this->load->view("edit_wages");
		}
	}

	public function pdf($eid,$docs,$arch=0,$session=0) {
		$max_session_age = 300; //seconds, 300 = 5 minutes
		$ip = getenv("REMOTE_ADDR");
		if ($session) {
			$sage = $this->db->query("SELECT `user_id`,`session_key`,TIMESTAMPDIFF(SECOND,`create_time`,CURRENT_TIMESTAMP) AS session_age,`ip` FROM `sessions` WHERE `session_key` = '$session' AND `ip` = '$ip'");
			if ($sage->num_rows() > 0) {
				$sage = $sage->first_row();
				if ($sage->session_age < $max_session_age) {
					$uid = $sage->user_id;
					$this->session->set_userdata('uid',$uid);
				} else {
					$this->db->query("DELETE FROM `sessions` WHERE `session_key` = '$session'");
				}
			}
		}
		$uid = $this->session->userdata('uid');
		if (!$uid) {
			$this->load->view('login_view');
		} else {
			$docs = explode(".",$docs);
			$pdf = new FPDF('P','mm','Letter');
			$cs = new newhire;
			foreach ($docs as $doc) {
				switch ($doc) {
					case 1:
						$pdf = $cs->emp_coversheet($pdf,$eid);
						break;
					case 5:
						//$pdf = $cs->emp_signoffs($pdf,$eid);
						break;
					case 7:
						$pdf = $cs->emp_i9($pdf,$eid,$arch);
						break;
					case 9:
						$pdf = $cs->emp_w4($pdf,$eid);
						break;
					case 10:
						$pdf = $cs->emp_deposit($pdf,$eid);
						break;
					case 8850:
						$pdf = $cs->emp_8850($pdf,$eid);
						break;
					case 11:
						//$pdf = $cs->emp_w11($pdf,$eid);
						break;
				}

			}
			$cs->generate_pdf($pdf);
		}
	}

	//function user_search($string) {
	//	$this->db->query("SELECT `user_id`,`")
	//}
	function transfer() {
		$data=array();
		$data['search_string'] = '';
		if ($_POST && $_POST['xfer'] == true) {
			$data['search_string'] = $_POST['search'];
			$data['employees'] = $this->employee_search($_POST['search']);
			if ($data['employees'] === false) {
				$data['failure'] = true;
			}
		}
		$this->load->view('top_menu_view');
		$this->load->view('transfer',$data);
	}
	function empsearch() {
		if ($this->session->userdata('utype') == 1) {
			$data=array();
			$data['search_string'] = '';
			if ($_POST) {
				$data['search_string'] = $_POST['search'];
				$data['employees'] = $this->employee_search($_POST['search']);
				if ($data['employees'] === false) {
					$data['failure'] = true;
				}
			}
			$this->load->view('top_menu_view');
			$this->load->view('search',$data);
		}
	}	
	function employee_search($keyword) {
		if ($this->session->userdata('utype') == 1) {
			$keywords = explode(',',$keyword);
			$count = count($keywords);
			$k=0;
			$sql_filter='';
			$delims = array('==','!=','>','<','>=','<=');
			foreach ($keywords as $search_string) {
				$k++;
				$delim=false;
				foreach ($delims as $expr) {
					if(preg_match("/$expr/",$search_string)) {
						$delim=$expr;
					}
				}
				if (!$delim) {
					$sss=$search_string;
					$search_string = array();
					$search_string[0]='name';
					$search_string[1]=$sss;
					$delim='==';
				} else {
					$search_string = explode($delim,$search_string);
					$search_string[0]=trim($search_string[0]);
				}
				$breaks='';
				switch ($search_string[0]) {
					case 'name':
						$search_string[0] = "CONCAT(`first_name`,' ',`last_name`)";
						break;
					case 'ssn':
						$search_string[0] = '`ssn_hash`';
						break;
					default:
						$search_string[0] = '`'.mysql_real_escape_string($search_string[0]).'`';
						break;
				}
				if ($delim == '==') {
					$delim = 'LIKE';
					$breaks = '%';
				} elseif ($delim == '!=') {
					$delim = 'NOT LIKE';
					$breaks = '%';
				}
				$sql_filter .= $search_string[0]." $delim '$breaks".mysql_real_escape_string($search_string[1])."$breaks'";
				if ($k < $count) {
					$sql_filter .= " AND ";
				}
			}
			$search = $this->db->query("SELECT `uniq_id`,`first_name`,`last_name` FROM `employees` WHERE $sql_filter AND `store` >= 5 ORDER BY `first_name`");
			$results = $search->result();
			$employees = false;
			foreach ($results as $employee) {
				$employees[] = array(
					'id'=>$employee->uniq_id,
					'first_name'=>$employee->first_name,
					'last_name'=>$employee->last_name
				);
			}
			return $employees;
		}
	}
	
	function user_search($keyword) {
		if ($this->session->userdata('utype') == 1) {
			$keywords = explode(',',$keyword);
			$count = count($keywords);
			$k=0;
			$sql_filter='';
			$delims = array('==','!=','>','<','>=','<=');
			foreach ($keywords as $search_string) {
				$k++;
				$delim=false;
				foreach ($delims as $expr) {
					if(preg_match("/$expr/",$search_string)) {
						$delim=$expr;
					}
				}
				if (!$delim) {
					$sss=$search_string;
					$search_string = array();
					$search_string[0]='name';
					$search_string[1]=$sss;
					$delim='==';
				} else {
					$search_string = explode($delim,$search_string);
					$search_string[0]=trim($search_string[0]);
				}
				$breaks='';
				switch ($search_string[0]) {
					case 'name':
						$search_string[0] = "CONCAT(`first_name`,' ',`last_name`)";
						break;
					case 'ssn':
						$search_string[0] = '`ssn_hash`';
						break;
					default:
						$search_string[0] = '`'.mysql_real_escape_string($search_string[0]).'`';
						break;
				}
				if ($delim == '==') {
					$delim = 'LIKE';
					$breaks = '%';
				} elseif ($delim == '!=') {
					$delim = 'NOT LIKE';
					$breaks = '%';
				}
				$sql_filter .= $search_string[0]." $delim '$breaks".mysql_real_escape_string($search_string[1])."$breaks'";
				if ($k < $count) {
					$sql_filter .= " AND ";
				}
			}
			$search = $this->db->query("SELECT `user_id`,`first_name`,`last_name` FROM `users` WHERE $sql_filter ORDER BY `first_name`");
			$results = $search->result();
			$employees = false;
			foreach ($results as $employee) {
				$employees[] = array(
					'id'=>$employee->user_id,
					'first_name'=>$employee->first_name,
					'last_name'=>$employee->last_name
				);
			}
			return $employees;
		}
	}
	
	

	function logout() {
		$this->load->view('logout_view');
	}
}
?>
