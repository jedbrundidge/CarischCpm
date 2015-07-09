<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Employee Warning</title>
<link rel="stylesheet" href="/cpm/default.css" type="text/css">
<link rel="stylesheet" href="/cpm/spell_checker/spell_checker.css" type="text/css">
<link type="text/css" href="/cpm/css/start/jquery-ui-1.7.2.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/cpm/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/cpm/js/jquery-ui-1.7.2.custom.min.js"></script>
<script src="/cpm/spell_checker/mootools-1.2-core-yc.js"></script>
<script src="/cpm/spell_checker/spell_checker.js"></script>
<script type="text/javascript">
    function checkForm() {
        sc_spellCheckers.resumeAll();
        return true;
    }
    function onlyNumbers(evt) {
        var e = event || evt;
        var charCode = e.which || e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function extend_suspend(chkbox, group) {
        var visSetting = (chkbox.checked) ? "visible" : "hidden"
        document.getElementById(group).style.visibility = visSetting
    }
    function popup(mylink, windowname)
    {
        if (! window.focus)return true;
        var href;
        if (typeof(mylink) == 'string')
            href=mylink;
        else
            href=mylink.href;
        window.open(href, windowname, 'width=400,height=100,scrollbars=no');
        return false;
    }
    function extend_suspend(chkbox, group)
    {
        var visSetting = (chkbox.checked) ? "visible" : "hidden"
        document.getElementById(group).style.visibility = visSetting
    }

    function encodeToHex(str){
        var r="";
        var e=str.length;
        var c=0;
        var h;
        while(c<e){
            h=str.charCodeAt(c++).toString(16);
            while(h.length<2) h="0"+h;
            r+=h;
        }
        return r;
    }

    

    function on_Change() {
        var eid= document.warning.eid.value;
    	var id = "e"+document.warning.eid.value;
        var get_text='';
        var desc='';
        var selected='';
        var suspended='';
        var terminated='';
        var warning_type='';
        var a=document.warning['policies_violated[]'];
        var b=document.warning['suspended[]'];
        var c=document.warning['other_action[]'];
        var d=document.warning['terminated[]'];
        var other='';
        for(var i=0; i<a.length; i++){
            if(a[i].checked){
                selected += id + "d" + a[i].value + "=y&"
            }
        }

		if (b[1].checked) {
			suspended = 1;
		}

		if (c[1].checked) {
			other = 1;
		}

		if (d[1].checked) {
			terminated = 1;
		}
		

        //desc=encodeToHex(document.warning.full_desc.value);
        desc=document.warning.desc.value;
    	try {
    		var xmlhttp = new XMLHttpRequest();
    	} catch (e) {
    		try {
    			var xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    		} catch (e) {
    			document
    					.write('XML Request problem (xmlnh1), please report this issue to david@carischinc.com');
    		}
    	}

		
    	
		if (document.warning.warning_type[2].checked) {
			warning_type = "verbal";
		} else if (document.warning.warning_type[1].checked) {
			warning_type = "written";
		}
    	
        xmlhttp.open( "post", "new_warning", false );
        xmlhttp.setRequestHeader(
        'Content-Type',
        'application/x-www-form-urlencoded; charset=UTF-8'
    );
        xmlhttp.send(id+"desc="+desc+
                 	 "&"+id+"month="+document.warning.month.value+
                 	 "&"+id+"suspension_length="+document.warning.suspension_length.value+
                 	 "&"+id+"other_disc_desc="+document.warning.other_disc_desc.value+
                 	 "&"+id+"warning_type="+warning_type+
                     "&"+id+"day="+document.warning.day.value+
                     "&"+id+"year="+document.warning.year.value+
                     "&"+id+"suspended="+suspended+
                     "&"+id+"terminated="+terminated+
                     "&"+id+"other="+other+
                     "&"+selected
                	 );

        window.location.href="https://cpm.carischinc.com/cpm/index.php/personnel/new_warning/"+eid;


    }

    function save_form() {
        var eid= document.warning.eid.value;
    	var id = "e"+document.warning.eid.value;
        var get_text='';
        var desc='';
        var selected='';
        var suspended='';
        var terminated='';
        var warning_type='';
        var a=document.warning['policies_violated[]'];
        var b=document.warning['suspended[]'];
        var c=document.warning['other_action[]'];
        var d=document.warning['terminated[]'];
        var other='';
        for(var i=0; i<a.length; i++){
            if(a[i].checked){
                selected += id + "d" + a[i].value + "=y&"
            }
        }

		if (b[1].checked) {
			suspended = 1;
		}

		if (c[1].checked) {
			other = 1;
		}

		if (d[1].checked) {
			terminated = 1;
		}
		

        //desc=encodeToHex(document.warning.full_desc.value);
        desc=document.warning.desc.value;
		if (document.warning.warning_type[2].checked) {
			warning_type = "verbal";
		} else if (document.warning.warning_type[1].checked) {
			warning_type = "written";
		}

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
        xmlhttp.send(id+"desc="+desc+
                 	 "&"+id+"month="+document.warning.month.value+
                 	 "&"+id+"suspension_length="+document.warning.suspension_length.value+
                 	 "&"+id+"other_disc_desc="+document.warning.other_disc_desc.value+
                 	 "&"+id+"warning_type="+warning_type+
                     "&"+id+"day="+document.warning.day.value+
                     "&"+id+"year="+document.warning.year.value+
                     "&"+id+"suspended="+suspended+
                     "&"+id+"terminated="+terminated+
                     "&"+id+"other="+other+
                     "&eid="+eid+
                     "&"+id+"terminated="+terminated+
                     "&"+selected
                	 );
         alert('Saved sucessfully...');
    }
    
    
        function load_form() {
        var eid= document.warning.eid.value;
    	var id = "e"+document.warning.eid.value;
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
        xmlhttp.send(eid);
    }
    
</script>
</head>
<body>
<table class="default">
	<tr>
		<td>
		<TABLE border="0" WIDTH="600" CELLPADDING="0" CELLSPACING="0">
			<TR>
				<TD>
				<CENTER><FONT SIZE=3 FACE="Arial"><B> Employee Warning Form </B></FONT></CENTER>
				</TD>
			</TR>
		</TABLE>
		<FORM NAME="warning"
			action="/cpm/index.php/personnel/new_warning/<?php echo $eid;?>/1"
			METHOD="POST"><input type="hidden" name="eid" id="eid"
			value="<?php echo $eid; ?>"> <!--
		<TABLE class="default" WIDTH="600" CELLPADDING="3">
			<TR>
				<TD WIDTH=200>Store<FONT COLOR=red></FONT></TD>
				<TD ALIGN=RIGHT WIDTH=235>Employee <?php echo $eid;?></TD>
			</TR>
		</TABLE>
			-->
		<TABLE class="default" WIDTH=600 CELLPADDING=0>
			<TR>
				<TD COLSPAN=2>Incident Date <?php
				$prefix = "e".$eid;
				$e_month = $this->session->userdata($prefix.'month');
				$e_day = $this->session->userdata($prefix.'day');
				$e_year = $this->session->userdata($prefix.'year');

				if (empty($e_month) || empty($e_day) || empty($e_year)) {
					$e_month = date('m');
					$e_year = date('Y');
					$e_day = date('d');
				}

				$cur_month=date('m');
				$cur_year=date('Y');
				$cur_day=date('d');


				for($i=01;$i<=12;$i++) {
					if ($i <= $cur_month || $e_year < $cur_year) {
						if ($cur_month != 1) {
							$mnth[$i] = date('F',mktime(0,0,0,$i,1));
						} elseif ($i == 12 || $i == 1) {
							$mnth[$i] = date('F',mktime(0,0,0,$i,1));
						}
					}
				}
				$option = "<select name=\"month\" onchange=\"return on_Change();\">";
				foreach($mnth as $num=>$text) {
					if ($num<10) {
						$num="0".$num;
					}
					$option.="<option value=\"$num\"";
					if ($cur_month==$num && empty($e_month)) {
						$option.=" selected";
					} elseif ($num == $e_month) {
						$option.=" selected";
					}
					$option.=">$text</option>\n";
				}
				$option.="</select>";
				echo $option;


				$nod='';
				if (!empty($e_month)) {
					$nod=$e_month;
				} else {
					$nod=$cur_month;
				}
				if (isset($e_year)) {
					$ye=$e_year;
				} else {
					$ye=$cur_year;
				}
				$num_of_days=date('t',mktime(0,0,0,$nod,1,$ye));
				$day="<select name=\"day\" onchange=\"return on_Change();\">";
				for ($t=1;$t<=$num_of_days;$t++) {
					if (($cur_month == $e_month && $cur_year == $e_year && $t <= $cur_day) || ($cur_month != $e_month || $cur_year != $e_year)) {
						if ($t<10) {
							$t="0".$t;
						}
						$day.="<option value=\"$t\"";
						if ($cur_day==$t && empty( $e_day )) {
							$day.=" selected";
						} elseif (!empty($e_day) && $e_day == $t) {
							$day.=" selected";
						}
						$day.=">$t</option>\n";
					}
				}
				$day.="</select>";
				echo $day;
				$goback=1;
				$year="<select name=\"year\" id=\"year\" onchange=\"return on_Change();\">";
				for($i=$cur_year;$i>=($cur_year-$goback);$i--) {
					$year.="<option value=\"$i\"";
					if ($cur_year==$i && empty($e_year)) {
						$year.=" selected";
					} elseif (!empty($e_year) && $e_year == $i) {
						$year.=" selected";
					}
					$year.=">$i</option>\n";
				}
				$year.="</select>";
				echo $year;

				$_S = $this->session->userdata($prefix.'suspended');
				$_O = $this->session->userdata($prefix.'other');
				$_T = $this->session->userdata($prefix.'terminated');
				?></TD>
				<TD COLSPAN=1><input type="hidden" name="warning_type"
					class="noborder"><INPUT TYPE="radio" NAME="warning_type" value="2"
					class="noborder"
					<?php if ($this->session->userdata($prefix.'warning_type') == 'written') echo "checked"; ?>>Verbal</TD>
				<TD><INPUT TYPE="radio" NAME="warning_type" value="1"
					class="noborder"
					<?php if ($this->session->userdata($prefix.'warning_type') == 'verbal') echo "checked"; ?>>Written</TD>
			</TR>
			<TR>
				<TD COLSPAN="1"><input type="hidden" name="suspended[]" value="0"
					style="border: none"><INPUT TYPE="checkbox" value="1"
					NAME="suspended[]" id="suspended[]"
					<?php if ($_S) echo ' checked '; ?> class="noborder"
					onClick="extend_suspend(this, 'susgroup')">Suspended</TD>
				<TD COLSPAN="1"><SPAN ID="susgroup"
				<?php if ($_S) echo "style=\"visibility: visible\""; ?>>Length of
				Suspension <INPUT TYPE="text" NAME="suspension_length"
					value="<?php echo $this->session->userdata($prefix.'suspension_length'); ?>"
					SIZE="5"> </SPAN></TD>
			
			
			<TR>
			
			
			<TR>
				<TD><input type="hidden" name="other_action[]" value="0"
					style="border: none"><INPUT TYPE="CHECKBOX" NAME="other_action[]"
					class="noborder" value="1" <?php if ($_O) echo " checked " ; ?>
					onClick="extend_suspend(this, 'othergroup')">Other disciplinary
				action</TD>
				<TD COLSPAN=3><SPAN ID="othergroup"
				<?php if ($_O) echo "style=\"visibility: visible\""; ?>> <INPUT
					TYPE=TEXT
					value="<?php echo $this->session->userdata($prefix.'other_disc_desc'); ?>"
					NAME="other_disc_desc" size=51 ALIGN=RIGHT> </SPAN></TD>
			</TR>
			<tr>
				<td colspan="2"><input type="hidden" name="terminated[]" value="0"
					style="border: none"> <input type="checkbox" name="terminated[]"
					value="1" style="border: none" <?php if ($_T) echo "checked"; ?>>Employee
				is being terminated</td>
			</tr>
		</TABLE>
		<BR>
		<TABLE class="default" WIDTH=600 CELLPADDING=1 rules="none"
			cellspacing="0">
			<THEAD>
				<TR>
					<TD COLSPAN=4>
					<CENTER><B> Policies Violated (Select all that apply)<FONT
						COLOR=red>*</FONT> </B></CENTER>
					</TD>
				</TR>
			</THEAD>
			<TR>
			<?php
			$n;


			$p="<TR>";
			$count=mysql_query("SELECT COUNT(`policy_id`) AS `cnt` FROM `warning_definitions`");
			while ($total_policies = mysql_fetch_assoc($count)) {
				$policy_cnt = $total_policies['cnt'];
			}
			$query=mysql_query("SELECT `policy_id`,`policy_name`,`policy_desc` FROM `warning_definitions` ORDER BY `policy_name`");
			$n=0;
			$x=0;
			while ($policies=mysql_fetch_assoc($query)) {
				$x++;
				if ($x % 2 == 0) {
					$color = "dddddd";
				} else {
					$color = "ffffff";
				}
				$chk='';
				$_X=$policies['policy_id'];
				if ($this->session->userdata($prefix."d".$_X)=="y") {
					$chk=" CHECKED ";
				}
				$p.="<TD WIDTH=\"33%\" bgcolor=\"$color\">";
				$p.="<INPUT TYPE=\"CHECKBOX\" class=\"noborder\" $chk NAME=\"policies_violated[]\" VALUE=\"".$policies['policy_id']."\" ><a href=\"/cpm/index.php/personnel/show_policy/".$policies['policy_id']."\" onClick=\"return popup(this, 'Desc')\"><font size=\"1\">".$policies['policy_name']."</font>";
				$p.="</TD>\n";
				$n++;
				if ($n>=3) {
					$p.="</TR><TR>";
					$n=0;
				}
			}
			echo $p;
			?>
		
		</TABLE>
		<!-- </TABLE> --> <BR>
		<TABLE class="default" WIDTH=600 CELLPADDING=2>
			<THEAD>
				<TR>
					<TD>
					<CENTER><B>Warning Explanation<FONT COLOR=red>*</FONT></B></CENTER>
					</TD>
				</TR> 
			</THEAD>
			<TR>
				<TD>
				<div>
				<CENTER><TEXTAREA NAME="desc" ROWS="30" CLASS="spell_check" COLS="71" AUTOCOMPLETE="off"><?php if ($this->session->userdata($prefix.'desc') != '') echo $this->session->userdata($prefix.'desc'); 
				else {
					$get_info = $this->db->query("SELECT `gender`,`first_name`,CONCAT(`first_name`,' ',`last_name`) AS `name`,`position`,DATE_FORMAT(`doh`,'%M %e, %Y') AS `doh` FROM `employees` LEFT JOIN `employee_info` ON `uniq_id` = `unique_id` WHERE `uniq_id` = $eid"); 
					$name = ucwords(strtolower($get_info->last_row()->name));
					$first_name2 = ucwords(strtolower($get_info->last_row()->first_name));
					switch (strtolower($get_info->last_row()->gender)) {
						case 'm':
							$gen = 'his';
							$gen1 = 'he';
							break;
						case 'f':
							$gen = 'her';
							$gen1 = 'she';
							break;
						default:
							$gen = 'their';
							break;
					}
					
					switch ($get_info->last_row()->position) {
						case 8:
							$position = 'Crew Member';
							break;
						case 6:
							$position = 'Shift Manager';
							break;
						case 5:
							$position = 'Assistant Manager';
							break;
						case 4:
							$position = 'General Manager';
							break;
						case 3:
							$position = 'Regional Manager';
							break;
					}
					$doh = $get_info->last_row()->doh;
					echo "$name has been employed with Carisch Inc. since $doh and is currently employed as a $position.  $first_name2 signed $gen employee handbook acknowledgment on {***DATE***} indicating $gen1 read, understood, and agreed to abide by all company policies and procedures contained therein.";
				}
				
				?></TEXTAREA></CENTER>
				</div>
				</TD>
			</TR>
		</TABLE>
		<BR>
		<table class="default" width="600">
			<tr>
				<td><INPUT TYPE="CHECKBOX" NAME="C2"
					onClick="this.form['C3'].disabled=false" class="noborder"></td>
				<td><FONT FACE=ARIAL SIZE=1>Is the write up specific? Are all of the
				details included? Are words like numerous and many defined?If
				profanity was used, are the actual words that were used quoted in
				the write up?</FONT></td>
			</tr>
			<tr>
				<td><INPUT TYPE="CHECKBOX" NAME="C3" DISABLED
					onClick="this.form['C4'].disabled=false" class="noborder"></INPUT></td>
				<td><FONT FACE=ARIAL SIZE=1>Does it list all prior incidences of
				this kind and/or any/all others (such as job performance issues)
				leading up to this write up?</FONT></td>
			</tr>
			<tr>
				<td><INPUT TYPE="CHECKBOX" NAME="C4" DISABLED
					onClick="this.form['C5'].disabled=false" class="noborder"></td>
				<td><FONT FACE=ARIAL SIZE=1>Does the write up reference dates and
				times of any previous conversations or verbal warnings?</FONT></td>
			</tr>
			<tr>
				<td><INPUT TYPE="CHECKBOX" NAME="C5" DISABLED
					onClick="this.form['C6'].disabled=false" class="noborder"></td>
				<td><FONT FACE=ARIAL SIZE=1>Does the write up state what policy was
				violated and how it was violated?</FONT></td>
			</tr>
			<tr>
				<td><INPUT TYPE="CHECKBOX" NAME="C6" DISABLED
					onClick="this.form['C7'].disabled=false" class="noborder"></td>
				<td><FONT FACE=ARIAL SIZE=1>Does the write up state how the employee
				knows of the policy that was violated?</FONT></td>
			</tr>
			<tr>
				<td><INPUT TYPE="CHECKBOX" NAME="C7" DISABLED
					onClick="this.form['C8'].disabled=false" class="noborder"></td>
				<td><FONT FACE=ARIAL SIZE=1>Does the write up indicate what
				behaviors the employee needs to change?</FONT></td>
			</tr>
			<tr>
				<td><INPUT TYPE="CHECKBOX" NAME="C8" DISABLED
					onClick="this.form['go'].disabled=false" class="noborder"></td>
				<td><FONT FACE=ARIAL SIZE=1>Have all opinions or assumptions in the
				write up been removed?</FONT></td>
			</tr>
		</table>
		<br />
		<table class="default" width="600" cellspacing="0" cellpadding="0">
			<tr>
				<td rowspan="2"><INPUT style="font-family: Arial;" TYPE="submit"
					NAME="go" VALUE="Submit Warning" DISABLED></td>
				<td><!-- <input type="checkbox" name="hr" class="noborder"><font size="2">Submit
				to HR for review <a href="">?</a></font>--></td>
				<td><a href="javascript: save_form()">Save</a></td>
			</tr>
			<tr>
				<td>
<input type="checkbox" name="regional" checked disabled class="noborder"><font size="2">Submit to regional manager for
				review</font></td>
			</tr>
		</table>
		
		</td>
	</tr>
	</FORM>
</table>

<a href="https://cpm.carischinc.com/cpm/index.php/personnel/load_form/<?=$eid?>">Load last save point (careful!)</a>
