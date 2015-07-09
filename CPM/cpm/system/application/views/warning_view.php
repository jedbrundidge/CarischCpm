<script language="javascript" src="/cpm/functions.js"></script>
<link rel="stylesheet"
	href="/cpm/spell_checker/spell_checker.css" type="text/css">
<style type="text/css">
select,ul {
	height: 115px;
	overflow: auto;
	width: 250px;
	border: 1px solid #000;
}

ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow-x: hidden;
}

li {
	margin: 0;
	padding: 0;
}

label {
	display: block;
	color: WindowText;
	background-color: Window;
	margin: 0;
	padding: 0;
	width: 100%;
}

label:hover {
	background-color: Highlight;
	color: HighlightText;
}
</style>
<script
	src="/cpm/spell_checker/mootools-1.2-core-yc.js"></script>
<script
	src="/cpm/spell_checker/spell_checker.js"></script>
<script type="text/javascript">

	function confirmfinal() {
		if (document.getElementById('sub').value == "Finalize") {
			var subm=confirm("Are you sure you want to finalize this document?");
			if (subm) {
				return true;
			} else {
				return false;
			}
		}
	}

    function checkForm() {
        sc_spellCheckers.resumeAll();
        return true;
    }
function edit_warning(edit) {
	var form = document.getElementById('review').elements;
	for(var i=0;i<form.length;i++) {
		if ((form[i].type == "text" || form[i].type == "textarea" || form[i].type == "checkbox" || form[i].type == "radio" || form[i].type == "submit")) {
			form[i].disabled=!edit;
		}
	}
}

        function load_form() {
		var uniq_id=document.review.uniq_id.value;
	    	var warning_id=document.review.warning_id.value;
	    	try {
	    		var xmlhttp = new XMLHttpRequest();
	    	} catch (e) {
	    		try {
	    			var xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	    		} catch (e) {
	    			document.write('XML Request problem (xmlnh2), please report this issue to david@carischinc.com');
	    		}
	    	}
		xmlhttp.open( "post", "/cpm/index.php/personnel/load_form",false);
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
		xmlhttp.send(uniq_id,0);
   	 }


    function save_form() {
        var uniq_id= document.review.uniq_id.value;
    	var warning_id = document.review.warning_id.value;
        var description='';
        var selected='';
        var terminated='';
        var a=document.review['pol[]'];
        var d=document.review['terminated[]'];
        
        for(var i=0; i<a.length; i++){
            if(a[i].checked){
                selected += "d" + a[i].value + "=y&"
            }
        }


	if (d[1].checked) {
		terminated = 1;
	}
		

        //desc=encodeToHex(document.warning.full_desc.value);
        desc=document.review.description.value;

    	try {
    		var xmlhttp = new XMLHttpRequest();
    	} catch (e) {
    		try {
    			var xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    		} catch (e) {
    			document.write('XML Request problem (xmlnh2), please report this issue to david@carischinc.com');
    		}
    	}
        xmlhttp.open( "post", "/cpm/index.php/personnel/save_form",false);
        xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        xmlhttp.send("description="+description+
                     "&incident_date="+document.review.incident_date.value+
                     "&terminated="+terminated+
                     "&uniq_id="+uniq_id+
                     "&terminated="+terminated+
                     "&"+selected
                	 );
         alert('Saved sucessfully...');
    }

</script>
<body onload="edit_warning(false)">
<form name="review" method="POST" onsubmit="return confirmfinal()" 
	action="/cpm/index.php/personnel/update_warning"><?php
	if (strlen($warning_id) == 32) {
		$get_real_id = $this->db->query("SELECT `warning_id` FROM `warnings` WHERE `hash` = '$warning_id'");
		$get_real_id = $get_real_id->first_row();
		$warning_id = $get_real_id->warning_id;
	}

	$query = $this->db->query("SELECT `hr_rev`,`employee_id`,`first_name`,`last_name`,`employees`.`store`,`scanned`,`incident_date`,`warning_date`,`submitting_user_id`,(SELECT CONCAT(`first_name`,' ',`last_name`) FROM `users` WHERE `user_id` = `submitting_user_id`) AS `sub_name`,`status`,`suspension_length`,`other_action`,`requested_reviewer`,`terminated`,`description` FROM `warnings` JOIN `warning_descriptions` ON `warning_id` = `warning_num` JOIN `employees` ON `employee_id` = `uniq_id` JOIN `warning_incidents` ON `incident_num` = `incident_id` WHERE `warning_id` = $warning_id");
	$query = $query->first_row();
	$name = ucwords(strtolower($query->first_name)) . ' ' . ucwords(strtolower($query->last_name));

	$prev = $this->db->query("SELECT `prev_description` FROM `warning_prev_descriptions` WHERE `w_id` = $warning_id");
	$prev = $prev->last_row();
	$ip = getenv("REMOTE_ADDR");
	//$session_id = md5("$uid.time("))
	//$this->db->query("INSERT INTO `sessions` (`user_id`,`session_key`,`create_time`,`ip`) VALUES ($uid,'$session_key',CURRENT_TIMESTAMP(),'$ip')")
	$policies_violated = $this->db->query("SELECT `policy_violated` FROM `warning_violated` WHERE `idwarning_ids` = $warning_id");
	foreach ($policies_violated->result() as $policy) {
		$pol_desc = $this->db->query("SELECT `policy_name` FROM `warning_definitions` WHERE `policy_id` = $policy->policy_violated");
		$pol_desc = $pol_desc->first_row();
		$policies[$policy->policy_violated] = $pol_desc->policy_name;
	}
	?> <input type="hidden" name="warning_id" id="warning_id"
	value="<?php echo $warning_id; ?>"> <input type="hidden" name="uniq_id"
	id="uniq_id" value="<?php echo $query->employee_id; ?>">
<table width="380">
	<tr>
		<td width="20%">Employee ID</td>
		<td>:</td>
		<td align="left"><?php echo $query->employee_id ?></td>
		<td rowspan="6" align="right">
		<table>
			<tr>
				<td>Policies Violated:</td>
			</tr>
			<tr>
				<td>
				<ul>
				<?php
				$_pols=$this->db->query("SELECT `policy_id`,`policy_name` FROM `warning_definitions`");
				foreach ($_pols->result() as $row) {
					$pol_array[$row->policy_id] = $row->policy_name;
				}
				foreach ($pol_array as $key=>$val) {
					$priority = 1;
					if (isset($policies[$key])) {
						$priority = 0;
						$checked = "checked";
						$bold = "<b>";
					} else {
						$checked = "";
						$bold = "";
					}
					$pc_array[] = array('desc'=>"<input type=\"checkbox\" name=\"pol[]\" value=\"$key\" $checked style=\"border: none;\">$bold$val</b>",
							    'priority' => $priority,
								'key' => $key);
				}
				usort($pc_array, array("Personnel","cmpp"));
				foreach ($pc_array as $val) {
					echo "<li><label for=\"".$val['key']."\">".$val['desc']."</label></li>";
				}

				?>
				</ul>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>Name</td>
		<td>:</td>
		<td colspan="2"><?php echo "$name"; ?></td>
	</tr>
	<tr>
		<td>Store/Mgr</td>
		<td>:</td>
		<td colspan="2"><?php echo "$query->store / $query->sub_name"; ?></td>
	</tr>
	<!-- 
	<tr>
		<td>Warning Date</td>
		<td>:</td>
		<td><input type="text" onkeypress="return onlyNumbers();"
			name="warning_date" value="<?php //echo "$query->warning_date"; ?>"
			size="10"></td>
	</tr>
	-->
	<tr>
		<td>Incident</td>
		<td>:</td>
		<td colspan="2"><input type="text" onkeypress="return onlyNumbers();"
			name="incident_date" id="incident_date" value="<?php echo "$query->incident_date"; ?>"
			size="10"></td>
	</tr>
	<?php if (strlen($query->suspension_length) > 0) { echo "<tr><td>Suspension Length</td><td>:</td><td><input type=\"text\" name=\"suspension_length\" value=\"$query->suspension_length\" size=\"26\"></td></tr>"; } ?>
	<?php if (strlen($query->other_action) > 0) { echo "<tr><td>Disciplinary Action</td><td>:</td><td><input type=\"text\" name=\"other_action\" value=\"$query->other_action\" size=\"26\"></td></tr>"; } ?>
	<tr>
		<td>Terminate Employee</td>
		<td>:</td>
		<td colspan="2"><input style="border: none;" type="checkbox"
			name="terminated" <?php if ($query->terminated) echo "CHECKED"; ?>></td>
	</tr>
	<tr>
		<td colspan="4">Description:</td>
	</tr>
	<tr>
		<td colspan="4"><textarea class="spell_check" name="description" id="description" 
			rows="15" cols="87"><?=$query->description?></textarea></td>
	</tr>
	<tr>
		<td colspan="4"><!--<a href="/cpm/index.php/personnel/view_changes/<?=$warning_id?>--></td>
	</tr>
	<tr>
		<td colspan="100">
		<table width="100%">
			<tr>
				<td><input style="font-family: Arial;" type="button" name="edit"
					onclick="edit_warning(true)" value="Edit"></td>
				<td width="60%"><?php 
				if ($this->session->userdata('utype') >= 4) {
					echo "<input type=\"checkbox\" onclick=document.getElementById('sub').value=\"Resubmit\"  name=\"resubmit[]\" value=\"1\" style=\"border: none;\">Resubmit for review";
				}
				if ($this->session->userdata('utype') <= 3) {
					echo "<input type=\"checkbox\" name=\"lock[]\" value=\"1\" style=\"border: none;\">Lock (Prevent further editing)";
					if ($this->session->userdata('utype') == 3) {
						$_sel = "";
						if (($query->status == 1 && $query->requested_reviewer == 15827 && $query->hr_rev != 1) || ($query->store == 99 && $query->status == 1 && $query->hr_rev != 1)) { $_sel = " CHECKED DISABLED "; }
						echo " | <input type=\"checkbox\" $_sel name=\"review[]\" value=\"1\" onclick=document.getElementById('sub').value=\"Resubmit\" style=\"border: none;\">Submit to HR for review";
					}
				}
				
				if ($this->session->userdata('utype') == 1) {
					?>
					<input type='
					<?php
				}
				?>
				</td>
				<td align="right"><input style="font-family: Arial;" type="submit"
					value="<?php if ($query->status == 2) echo "Finalize"; else echo "Submit Changes"; ?>" name="sub" id="sub"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br>
<!--<table width="380">
	<tr>
		<td align="center"><input style="font-family: Arial Black;"
			type="button" name="complete" value="Complete" onclick="go_home()"></td>
	</tr>
</table>--></form>
<?php
if ($this->session->userdata('utype') <= 3) {
	echo "<a href=\"/cpm/index.php/personnel/revoke/w/$warning_id\">Revoke Warning</a>";
}
?>
