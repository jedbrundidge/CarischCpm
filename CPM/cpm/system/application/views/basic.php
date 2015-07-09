<link type="text/css" href="/cpm/css/start/jquery-ui-1.7.2.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/cpm/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/cpm/js/jquery-ui-1.7.2.custom.min.js"></script>
<style type="text/css">
    #ui-datepicker-div
    {
        z-index: 9999999;
    }
</style>
<script type="text/javascript">
	$(function() {
		$("#inspection_date").datepicker({
			duration: '',
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
</script>
<?php
$uid = $this->session->userdata('uid');
$myName = ucwords(strtolower($this->db->query("SELECT CONCAT(`first_name`,' ',`last_name`) AS `name` FROM `users` WHERE `user_id` = $uid")->last_row()->name));
//$gm = $this->db->query("SELECT CONCAT(`first_name`,' ',`last_name`) AS `name`,`user_id` FROM `users` WHERE `user_type` = 4 AND `active` = 1 AND `default_store` = $store")->last_row();
//$gmName = ucwords(strtolower($gm->name));
//$gmID = $gm->user_id;
?>
<form method="POST" action="/cpm/index.php/personnel/add_basic">
<table>
	<tr><th></th>
		<th>Date</th>
		<th>Service</th>
		<th>Quality</th>
		<th>Clean</th>
		<th>Brand</th>
		<th>Total</th>
		<th>Inspected By</th>
	</tr>
	<tr><td></td>
		<td><input  style="font-family: Arial;" type="text" id="inspection_date" name="inspection_date" class="text ui-widget-content ui-corner-all" size="11"/></td>
		<td><input  style="font-family: Arial;" type="text" id="service" name="service" class="text ui-widget-content ui-corner-all" size="4"/></td>
		<td><input  style="font-family: Arial;" type="text" id="product_quality" name="product_quality" class="text ui-widget-content ui-corner-all" size="4"/></td>
		<td><input  style="font-family: Arial;" type="text" id="cleanliness" name="cleanliness" class="text ui-widget-content ui-corner-all" size="4"/></td>
		<td><input  style="font-family: Arial;" type="text" id="brand" name="brand" class="text ui-widget-content ui-corner-all" size="4"/></td>
		<td><input  style="font-family: Arial;" type="text" id="total" name="total" class="text ui-widget-content ui-corner-all" size="4"/></td>
		<td><select style="font-family: Arial;" width="12" name="who"><option id="1"><?=$myName?></option></select></td>
		<td><input  style="font-family: Arial;" type="submit" id="submit" name="submit" value="Submit" class="text ui-widget-content ui-corner-all" /><input type="hidden" name="store_id" id="store_id" value="<?=$store?>"/></td>
	</tr>
	<tr><td></td><td> </td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	</form>
	<form method="POST" action="/cpm/index.php/personnel/del_basic">
	<?php
		$results = $this->db->query("SELECT `basic_id`,DATE_FORMAT(`inspection_date`,'%b %d, %Y') AS `inspection_date`,`service_perc`,`quality_perc`,`clean_perc`,`brand_perc`,`total_perc`,CONCAT(`first_name`,' ',`last_name`) AS `done_by` FROM `store_basics` LEFT JOIN `users` ON `done_by` = `user_id` WHERE `store_id` = $store ORDER BY `inspection_date` DESC");
		foreach ($results->result_array() as $result) {
			echo "<tr>
			<td><input type=\"checkbox\" name=\"basics[]\" value=\"$result[basic_id]\" class=\"noborder\" /></td>
			<td>$result[inspection_date]</td>
			<td>$result[service_perc]</td>
			<td>$result[quality_perc]</td>
			<td>$result[clean_perc]</td>
			<td>$result[brand_perc]</td>
			<td>$result[total_perc]</td>
			<td>".ucwords(strtolower($result['done_by']))."</td>
			</tr>";
		}

		$avgs = $this->db->query("SELECT SUM(`service_perc`)/COUNT(basic_id) AS `service_perc`,SUM(`quality_perc`)/COUNT(basic_id) AS `quality_perc`,SUM(`clean_perc`)/COUNT(basic_id) AS `clean_perc`,SUM(`brand_perc`)/COUNT(basic_id) AS `brand_perc`,SUM(`total_perc`)/COUNT(basic_id) AS `total_perc` FROM `store_basics` WHERE `store_id` = $store ORDER BY `inspection_date` DESC");
		foreach ($avgs->result_array() as $result) {
			foreach ($result as $key=>$val) {
				$result[$key]=round($val,1);
			}
			echo "<tr bgcolor=\"lightgrey\">
			<td colspan=\"2\"><input type=\"hidden\" name=\"store_id\" id=\"store_id\" value=\"$store\"/><input style=\"font-family: Arial;\" type=\"submit\" id=\"delete\" name=\"delete\" value=\"Delete Selected\" class=\"text ui-widget-content ui-corner-all\" /></td>
			<td>$result[service_perc]</td>
			<td>$result[quality_perc]</td>
			<td>$result[clean_perc]</td>
			<td>$result[brand_perc]</td>
			<td>$result[total_perc]</td>
			<td></td>"
			;
		}
	?>
</table>
</form>
