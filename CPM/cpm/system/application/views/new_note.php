<style type="text/css">
select, ul { height: 283px; overflow: auto; width: 175px; border: 1px solid #B0B0B0; }
ul { list-style-type: none; margin: 0; padding: 0; overflow-x: hidden; }
li { margin: 0; padding: 0; }
label { display: block; color: WindowText; background-color: Window; margin: 0; padding: 0; width: 100%; }
label:hover { background-color: Highlight; color: HighlightText; }
</style>
<table>
	<form name="note" method="POST" action="/cpm/index.php/personnel/new_note/<?=$eid?>">
	<tr>
		<td colspan="2" align="center">
			Subject: <input type="text" size="65" maxlenth="128" name="subject" id="subject" />
		</td>
	</tr>
	<tr>
		<td rowspan="2">
		<ul>
		<?php
		$utype = $this->session->userdata('utype');
		$uid = $this->session->userdata('uid');
		$store = $this->db->query("SELECT `store` FROM `employees` WHERE `uniq_id` = $eid");
		$store = $store->last_row();
		$emps = $this->db->query("SELECT `first_name`,`last_name`,`uniq_id` FROM `employees` WHERE `position` > $utype AND `store` = $store->store ORDER BY `first_name`");
		
		foreach ($emps->result() as $emp) {
			$c = '';
			if ($emp->uniq_id == $eid)
				$c = "checked";
			
			$name = ucwords(strtolower("$emp->first_name $emp->last_name"));
			echo "<li><label for=\"$emp->uniq_id\"><input type=\"checkbox\" $c style=\"border: none;\" value=\"$emp->uniq_id\" name=\"additional[]\" id=\"$emp->uniq_id\">$name</label></li>";
		}
		?>
		</ul>
		
		</td>
		<td><textarea name="note" cols="50" rows="15" ></textarea></td>
	</tr>
	<tr>
		<td><input type="submit" value="Submit Note" style="font-family: Arial;"></td>
	</tr>
	</form>
</table>
