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
<script type="text/javascript">
	function process(data) {
		var bArray = data.split(",");
		if (bArray[0] == 0) {
			for ( var i = 0; i < bArray.length; i++) {
				$('#users tbody').append('<tr>' +
				'<td>' + bArray[i] + '</td>' + 
				'</tr>'); 
			}
		} else {
			alert("Failed on IDs:\n"+data);
		}
	}
</script>
	<script type="text/javascript">
	$(function() {
		
		var ids = $("#ids"),
			allFields = $([]).add(ids),
			tips = $("#validateTips");


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
		
		$('#submit').click(function() {
			$.post("/cpm/index.php/personnel/val_8850",{ids: ''+ids.val()+''},
				function(data){
					process(data);
				}
			);
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
		div#users-contain {  width: 200px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-button { outline: 0; margin:0; padding: .4em 1em .5em; text-decoration:none;  !important; cursor:pointer; position: relative; text-align: center; }
		.ui-dialog .ui-state-highlight, .ui-dialog .ui-state-error { padding: .3em;  }
</style>

<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
	</script>
<div class="demo">

<div id="tabs">
	<ul>
		<li><a disabled>Test User</a></li>
		<li><a href="#tabs-1">Validation</a></li>
		<li><a href="#tabs-2">Pending</a></li>
		<li><a href="#tabs-3">Logout</a></li>
	</ul>
	<div id="tabs-1">
		<p>
			<input type="text" name="ids" id="ids" class="text ui-widget-content ui-corner-all" />
			<button id="submit" class="ui-button ui-state-default ui-corner-all">Submit IDs</button>
		</p>
		<p>
			<table id="users" class="ui-widget ui-widget-content">
				<thead>
					<tr class="ui-widget-header">
						<th>8850s verfied this session</th>
					</tr>
					<tbody>
					<tr>
					</tr>
				</tbody>
				</thead>
			</table>
		</p>
	</div>
	<div id="tabs-2">
		<p>Pending 8850s.</p>
	</div>
		<div id="tabs-3">
		<p>Logout</p>
	</div>
</div>

</div>