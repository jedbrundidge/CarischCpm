<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cpm_functions {
	
	var $min_age = 14;
	var $clean_names = array (
		' ' => '',
		'\'' => '',
		'-' => '',
		'.' => '',
		',' => '',
		'_' => ''
		);
	
	function Cpm_functions() {
		log_message('debug','CPM Functions Class initialized');
	}
	
	function barcode($doc_type,$doc_id) {
		/*
		foreach ($this->clean_names as $bad=>$good) {
			$first_name = str_replace($bad,$good,$first_name);
			$last_name = str_replace($bad,$good,$last_name);
		}*/
		return "*$doc_type-$doc_id*";
	}
	
	function sdate($allow_future,$goback = 100,$mth,$d,$y) {
				$date_select = array();
		        $cur_month=date('m');
                $cur_year=date('Y');
                for($i=01;$i<=12;$i++) {
                    $mnth[$i] = date('M',mktime(0,0,0,$i,1));
                }
                $option = "<select name=\"smonth\" onchange=\"return on_Change(false,1,1);\">";
                foreach($mnth as $num=>$text) {
                    if ($num<10) {
                        $num="0".$num;
                    }
                    $option.="<option value=\"$num\"";
                    if ($cur_month==$num && empty($mth)) {
                        $option.=" selected";
                    } elseif (!empty($mth) && $mth == $num) {
                        $option.=" selected";
                    }
                    $option.=">$text</option>\n";
                }
                $option.="</select>";
                $date_select['smonth'] = $option;
                $cur_day=date('d');
                $nod='';
                if (isset($mth)) {
                    $nod=$mth;
                } else {
                    $nod=$cur_month;
                }
                if (isset($y)) {
                    $ye=$y;
                } else {
                    $ye=$cur_year;
                }
                $num_of_days=date('t',mktime(0,0,0,$nod,1,$ye));
                $day="<select name=\"sday\" onchange=\"return on_Change(false,1,1);\">";
                for ($t=1;$t<=$num_of_days;$t++) {
                    if ($t<10) {
                        $t="0".$t;
                    }
                    $day.="<option value=\"$t\"";
                    if ($cur_day==$t && empty( $d )) {
                        $day.=" selected";
                    } elseif (!empty($d) && $d == $t) {
                        $day.=" selected";
                    }
                    $day.=">$t</option>\n";
                }
                $day.="</select>";
                $date_select['sday'] = $day;
                $year="<select name=\"syear\" id=\"syear\" onchange=\"return on_Change(false,1,1);\">";
                for($i=$cur_year;$i>=($cur_year-$goback);$i--) {
                    $year.="<option value=\"$i\"";
                    if ($cur_year==$i && !isset($y)) {
                        $year.=" selected";
                    } elseif (isset($y) && $y == $i) {
                        $year.=" selected";
                    }
                    $year.=">$i</option>\n";
                }

                $year.="</select>";
                $date_select['syear'] = $year;
                
                return $date_select;
	}
	
	function date($allow_future,$goback = 100,$mth,$d,$y) {
				$date_select = array();
		        $cur_month=date('m');
                $cur_year=date('Y');
                for($i=01;$i<=12;$i++) {
                    $mnth[$i] = date('M',mktime(0,0,0,$i,1));
                }
                $option = "<select name=\"month\" onchange=\"return on_Change(false,1,1);\">";
                foreach($mnth as $num=>$text) {
                    if ($num<10) {
                        $num="0".$num;
                    }
                    $option.="<option value=\"$num\"";
                    if ($cur_month==$num && !isset($mth)) {
                        $option.=" selected";
                    } elseif (isset($mth) && $mth == $num) {
                        $option.=" selected";
                    }
                    $option.=">$text</option>\n";
                }
                $option.="</select>";
                $date_select['month'] = $option;
                $cur_day=date('d');
                $nod='';
                if (isset($mth)) {
                    $nod=$mth;
                } else {
                    $nod=$cur_month;
                }
                if (isset($y)) {
                    $ye=$y;
                } else {
                    $ye=$cur_year;
                }
                $num_of_days=date('t',mktime(0,0,0,$nod,1,$ye));
                $day="<select name=\"day\" onchange=\"return on_Change(false,1,1);\">";
                for ($t=1;$t<=$num_of_days;$t++) {
                    if ($t<10) {
                        $t="0".$t;
                    }
                    $day.="<option value=\"$t\"";
                    if ($cur_day==$t && !isset( $d )) {
                        $day.=" selected";
                    } elseif (isset($d) && $d == $t) {
                        $day.=" selected";
                    }
                    $day.=">$t</option>\n";
                }
                $day.="</select>";
                $date_select['day'] = $day;
                $year="<select name=\"year\" id=\"year\" onchange=\"return on_Change(false,1,1);\">";
                for($i=$cur_year-$this->min_age;$i>=($cur_year-$goback);$i--) {
                    $year.="<option value=\"$i\"";
                    if ($cur_year==$i && !isset($y)) {
                        $year.=" selected";
                    } elseif (isset($y) && $y == $i) {
                        $year.=" selected";
                    }
                    $year.=">$i</option>\n";
                }
                $year.="</select>";
                $date_select['year'] = $year;
                
                return $date_select;
	}

	function verify_access($uid,$sid = 0) {
		$CI =& get_instance();
		$db = $CI->load->database('default',TRUE);
		
		$_stores = array();
		$grant = 0;
		$dsa = 0;

		if ($uid) {
			$group_id = $db->query("
			    SELECT 
			        `group_id`,
			        `user_type`,
			        `default_store`
			    FROM 
			        `users`
			    WHERE 
			        `user_id` = $uid"
			);
			$group_id = $group_id->first_row();

			$store_access = $db->query("
			    SELECT 
			        `group_stores` AS `store`
			    FROM 
			        `groups`
			    WHERE 
			        `idgroups` = $group_id->group_id
			");
			foreach ($store_access->result() as $_id) {
				if ($sid == $_id->store) {
					$grant = 1;
				}
				$_stores[] = $_id->store;
				if ($group_id->default_store == $_id->store) {
					$dsa = 1;
				}
			}

			if ($sid == $group_id->default_store) {
				$grant = 1;
			}
			
			if (!$dsa) {
				$_stores[] = $group_id->default_store;
			}

			if ($sid && $grant) {
				return true;
			} elseif ($sid && !$grant) {
				return false;
			} elseif (!$sid) {
				return $_stores;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}
	
}
?>