<link type="text/css" href="/cpm/css/start/jquery-ui-1.7.2.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/cpm/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/cpm/js/jquery-ui-1.7.2.custom.min.js"></script>
<input type="hidden" name="store_id" id="store_id" value="<?=$store?>" />
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
<script type="text/javascript">
	function process(data) {
		var bArray = data.split(",,");
		$('#users tbody').append('<tr>' +
			'<td>' + bArray[0] + '</td>' + 
			'<td>' + bArray[1] + '</td>' + 
			'<td>' + bArray[2] + '</td>' +
			'<td>' + bArray[3] + '</td>' +
			'<td>' + bArray[4] + '</td>' +
			'<td>' + bArray[5] + '</td>' +
			'<td>' + bArray[6] + '</td>' +
			'</tr>'); 
	}
</script>
	<script type="text/javascript">
	
	$(function() {
		
		var inspection_date = $("#inspection_date"),
			service = $("#service"),
			product_quality = $("#product_quality"),
			cleanliness = $("#cleanliness"),
			brand = $("#brand"),
			store_id = $("#store_id"),
			allFields = $([]).add(inspection_date).add(service).add(product_quality).add(cleanliness).add(brand),
			tips = $("#validateTips");

		function updateTips(t) {
			tips.text(t).effect("highlight",{},1500);
		}

		function checkLength(o,n,min,max) {

			if ( o.val().length > max || o.val().length < min ) {
				o.addClass('ui-state-error');
				updateTips("Length of " + n + " must be between "+min+" and "+max+".");
				return false;
			} else {
				return true;
			}

		}

		function checkRegexp(o,regexp,n) {

			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass('ui-state-error');
				updateTips(n);
				return false;
			} else {
				return true;
			}

		}
		

		
		$("#dialog").dialog({
			bgiframe: true,
			autoOpen: false,
			height: 375,
			modal: true,
			buttons: {
				'Add Basic': function() {
					var bValid = true;
					allFields.removeClass('ui-state-error');
					if (bValid) {
						$.post("/cpm/index.php/personnel/add_basic",{
						inspection_date: ''+inspection_date.val()+'',
						service_perc: ''+service.val()+'',
						quality_perc: ''+product_quality.val()+'',
						clean_perc: ''+cleanliness.val()+'',
						brand_perc: ''+brand.val()+'',
						store_id: ''+store_id.val()+''
						},
							function(data){
								process(data);
							});
						$(this).dialog('close');
					}
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			},
			close: function() {
				allFields.val('').removeClass('ui-state-error');
			}
		});
		
		$('#add-basic').click(function() {
			$('#dialog').dialog('open');
		})
		.hover(
			function(){ 
				$(this).addClass("ui-state-hover"); 
			},
			function(){ 
				$(this).removeClass("ui-state-hover"); 
			}
		).mousedown(function(){
			$(this).addClass("ui-state-active"); 
		})
		.mouseup(function(){
				$(this).removeClass("ui-state-active");
		});

	});
</script>

<style type="text/css">
		body { font-size: 62.5%; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain {  width: 500px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
		.ui-button { outline: 0; margin:0; padding: .4em 1em .5em; text-decoration:none;  !important; cursor:pointer; position: relative; text-align: center; }
		.ui-dialog .ui-state-highlight, .ui-dialog .ui-state-error { padding: .3em;  }
</style>
<div class="basic">

<div id="dialog" title="Add Basic">
	<form>
	<fieldset>
		<label for="inspection_date">Inspection Date</label>
		<input type="text" id="inspection_date" name="inspection_date" class="text ui-widget-content ui-corner-all" /><p>
		<label for="service">Service</label>
		<input type="text" name="service" id="service" value="" class="text ui-widget-content ui-corner-all" />
		<label for="product_quality">Product Quality</label>
		<input type="text" name="product_quality" id="product_quality" value="" class="text ui-widget-content ui-corner-all" />
		<label for="cleanliness">Cleanliness</label>
		<input type="text" name="cleanliness" id="cleanliness" value="" class="text ui-widget-content ui-corner-all" />
		<label for="brand">Brand</label>
		<input type="text" name="brand" id="brand" value="" class="text ui-widget-content ui-corner-all" />
		<input type="hidden" id="store_id" name="store_id" value="<?=$store?>" />
	</fieldset>
	</form>
</div>



<div id="users-contain" class="ui-widget">

		<h1>Basics:</h1>


	<table id="users" class="ui-widget ui-widget-content">
		<thead>
			<tr class="ui-widget-header">
				<th>Inspection&nbsp;Date</th>
				<th>S</th>
				<th>Q</th>
				<th>C</th>
				<th>B</th>
				<th>T</th>
				<th>Inspected&nbsp;By</th>
			</tr>
			<tbody>
			<tr>
			<?php
				$results = $this->db->query("SELECT DATE_FORMAT(`inspection_date`,'%b %d, %Y') AS `inspection_date`,`service_perc`,`quality_perc`,`clean_perc`,`brand_perc`,`total_perc`,CONCAT(`first_name`,' ',`last_name`) AS `done_by` FROM `store_basics` LEFT JOIN `users` ON `done_by` = `user_id` WHERE `store_id` = $store ORDER BY `inspection_date` DESC");
				foreach ($results->result_array() as $result) {
					echo"<tr>
						<td>$result[inspection_date]</td>
						<td>$result[service_perc]</td>
						<td>$result[quality_perc]</td>
						<td>$result[clean_perc]</td>
						<td>$result[brand_perc]</td>
						<td>$result[total]</td>
						<td>".ucwords(strtolower($result['done_by']))."</td>
						</tr>";
				}
			
			?>
			</tr>
		</tbody>
		</thead>
	</table>
</div>
<button id="add-basic" class="ui-button ui-state-default ui-corner-all">Add Basic</button>
</div>