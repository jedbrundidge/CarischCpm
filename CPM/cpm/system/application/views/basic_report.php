<link type="text/css" href="/cpm/css/start/jquery-ui-1.7.2.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/cpm/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/cpm/js/jquery-ui-1.7.2.custom.min.js"></script>
<style type="text/css">
    #ui-datepicker-div
    {
        z-index: 9999999;
    }
</style>
<style type="text/css">
g.select {
	height: 115px;
	overflow: auto;
	width: 250px;
	border: 1px solid #000;
}

ul {
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

g.li {
	margin: 0;
	padding: 0;
}

g.label {
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
<script type="text/javascript">
	$(function() {
		$("#from").datepicker({
			duration: '',
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
	$(function() {
		$("#to").datepicker({
			duration: '',
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
</script>
<?php
$query = "SELECT `users`.`group_id`,`group_desc` FROM `group_descriptions` LEFT JOIN `users` ON `users`.`group_id` = `group_descriptions`.`group_id` WHERE `user_type` = 3 AND `eid` IN (SELECT `uniq_id` FROM `employees` WHERE `store` = 2) AND `active` = 1 ORDER BY `group_id`";
$result = $this->db->query($query);
foreach ($result->result_array() as $row) {
	$option .= "<li class=\"g\"><label class=\"g\" for=\"$row[group_id]\"><input type=\"checkbox\" class=\"g\" style=\"border: none;\" name=\"groups[]\" value=\"$row[group_id]\" />$row[group_desc]</label></li>";
}

$query2 = "SELECT `store_id`,`store_name` FROM `stores` WHERE `store_id` BETWEEN 5 AND 89 ORDER BY `store_name`";
$result2 = $this->db->query($query2);
foreach ($result2->result_array() as $row) {
	$option2 .= "<li><label for=\"S$row[store_id]\"><input type=\"checkbox\" style=\"border: none;\" value=\"$row[store_id]\" name=\"stores[]\" />$row[store_name]</label></li>";
}


if (isset($data[0])) {
	//$result_data = $this->db->query("SELECT AVG(`service_perc`) service,AVG(`quality_perc`) quality,AVG(`clean_perc`) clean,AVG(`brand_perc`) brand,AVG(total_perc) total FROM `store_basics` WHERE `store_id` IN ($data)");
	$res = $data[0];
	foreach ($res as $key=>$num) {
		$res[$key] = round($num,1);
	}
	echo "Stores: ";
	foreach ($info['store_group'] as $sg=>$sv) {
		echo "$sg,";
	}
	if (!empty($info['stores'])) {
		$st = $this->db->query("SELECT `store_name` FROM `stores` WHERE `store_id` IN ($info[stores])");
		foreach ($st->result_array() as $sn) {
			echo "$sn[store_name],";
		}
	}
	echo "<br />From: $info[from] <br />To: $info[to]";
	?>
	<table width="311px">
	<tr>
		<th></th>
		<th>Service</th>
		<th>Quality</th>
		<th>Clean</th>
		<th>Brand</th>
		<th>Total</th>
		<th></th>
	</tr>
	<tr><td></td><td> </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	<?php
		echo "<tr>
		<td></td>
		<td><center>$res[service]</center></td>
		<td><center>$res[quality]</center></td>
		<td><center>$res[clean]</center></td>
		<td><center>$res[brand]</center></td>
		<td><center>$res[total]</center></td>
		<td></td>
		</tr>";
	?>
</table>
<br />
<?php
}
?>
<form method="POST" action="/cpm/index.php/personnel/basic_report">
<table>
<tr><td>From: </td><td><input  style="font-family: Arial;" type="text" id="from" name="from" class="text ui-widget-content ui-corner-all" size="11"/><br /></td></tr>
<tr><td>To: </td><td><input  style="font-family: Arial;" type="text" id="to" name="to" class="text ui-widget-content ui-corner-all" size="11"/></td></tr>
<tr><td>Group: </td><td><ul><?=$option?></ul></td></tr>
<tr><td>Stores: </td><td><ul><?=$option2?></ul></td></tr>
<tr><td colspan="2"><center><input type="submit" /></center></td></tr>
</table>
</form>
