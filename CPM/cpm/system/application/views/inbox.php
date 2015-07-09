<script language="javascript">
function search() {
	try {
		var xmlhttp = new XMLHttpRequest();
	} catch (e) {
		try {
			var xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (e) {
			//document.write('get a decent browser');
		}
	}
	var search_string = document.getElementById('to_search').value;
	info = "&search="+search_string;
	xmlhttp.onreadystatechange = handler;
	xmlhttp.open("POST","/cpm/index.php/personnel/get_users", true);
	xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xmlhttp.send(info);

	function handler() {
		if(xmlhttp.readyState == 4) {
			var array = xmlhttp.responsetext;
			var users_array = new Array();
			users_array = array.split("+");
			for(var i=0;i<users_array.length;i++) {
				var user_array = new Array();
				user_array = users_array[i].split(",");
				var user = new Array();
				user[user_array[0]] = user_array[1];
				var users = new Array();
				users[i] = user;
			}
			document.getelementbyid('search_result').value = users[0][0];
		}
	}
}

function show_message(n) {
	try {
		var xmlhttp = new XMLHttpRequest();
	} catch (e) {
		try {
			var xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (e) {
			//document.write('get a decent browser');
		}
	}
	info = "&message_id="+n;
	xmlhttp.onreadystatechange = handler;
	xmlhttp.open("POST","/cpm/index.php/personnel/get_message", true);
	xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xmlhttp.send(info);
	
	function handler() {
		if(xmlhttp.readyState == 4) {
			document.getElementById('m'+n).innerHTML = xmlhttp.responsetext + "<br><center><font size=1><a href=\"\">Reply</a></font></center>";
			var last_id = document.getElementById('last_id').value;
			if (last_id != 0) {
				document.getElementById(''+last_id).style.fontStyle="normal";
				document.getElementById('t'+last_id).style.display="none";
			}
			document.getElementById('last_id').value=n;
			document.getElementById(''+n).style.fontWeight="normal";
			document.getElementById(''+n).style.fontStyle="italic";
			if (document.getElementById('t'+n).style.display == "block") {
				document.getElementById('t'+n).style.display="none";
			} else {
				document.getElementById('t'+n).style.display="block";
			}
			
		}
	}
}
</script>
<input
	type="hidden" id="last_id" name="last_id" value="0">
<br>
<table>
	<tr>
		<td>
		<table width="100%">
			<tr>
				<td><input type="text" id="to_search" name="to_search"
					onkeypress="return search();"></input>
				<div id="search_result"></div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table style="border: collapse; -moz-border-radius: 6px;"
			cellspacing="0" cellpadding="0" width="450">
			<tr>
				<td width="25%">From</td>
				<td width="40%">Subject</td>
				<td width="32%">Received</td>
			</tr>
			<?php
			$uid = $this->session->userdata('uid');
			$messages = $this->db->query("SELECT `message_id`,`message_from`,`first_name`,`last_name`,`subject`,IF(DATE(`recieved`)=CURRENT_DATE(),DATE_FORMAT(`recieved`,'%l:%i %p'),DATE_FORMAT(`recieved`,'%b %e, %Y')) AS `recieved_v`,`read` FROM `inbox` JOIN `users` ON `message_from` = `user_id` WHERE `message_to` = $uid ORDER BY `read` ASC,`recieved` DESC");
			$n=0;
			foreach ($messages->result() as $msg) {
				$n++;
				$bg = "ffffff";
				if ($n & 1) {
					$bg = "dddddd";
				}
				$name = ucwords(strtolower("$msg->first_name $msg->last_name"));
				$m_id = $msg->message_id;
				$style = "font-weight: normal";
				if (!$msg->read) {
					$style = "font-weight: bold";
				}
				echo "<tr id=\"$m_id\" name=\"$m_id\" bgcolor=\"$bg\" style=\"cursor: hand;$style;\" onmouseover=\"this.style.color='#3333FF'\" onmouseout=\"this.style.color='#000000'\" onclick=\"return show_message('$m_id');\"><td>$name</td><td>$msg->subject</td><td>$msg->recieved_v</td></tr>";
				echo "<tr bgcolor=\"$bg\" style=\"display: none; padding-left: 8px; padding-top: 3px; padding-bottom: 5px;\" id=\"t$m_id\" name=\"t$m_id\" width=\"100%\"><td colspan=\"3\" name=\"m$m_id\" id=\"m$m_id\"></td></tr>";
			}
			?>
		</table>
		</td>
	</tr>
</table>
