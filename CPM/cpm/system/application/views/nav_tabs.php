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
	$(function() {
		
		var inspection_date = $("#inspection_date"),
			service = $("#service"),
			product_quality = $("#product_quality"),
			cleanliness = $("#cleanliness"),
			brand = $("#brand"),
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
						$('#users tbody').append('<tr>' +
							'<td>' + inspection_date.val() + '</td>' + 
							'<td>' + service.val() + '</td>' + 
							'<td>' + product_quality.val() + '</td>' +
							'<td>' + cleanliness.val() + '</td>' +
							'<td>' + brand.val() + '</td>' +
							'<td>' + '</td>' +
							'</tr>'); 
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
		<li><a href="#tabs-1">Pending (1)</a></li>
		<li><a href="#tabs-2">Managment</a></li>
		<li><a href="#tabs-3">Inbox (1)</a></li>
		<li><a href="#tabs-4">Settings</a></li>
		<li><a href="#tabs-5">Logout</a></li>
	</ul>
	<div id="tabs-1">
		<p>Welcome Message</p>
	</div>
	<div id="tabs-2">
		<?php $this->load->view('manage_ajax'); ?>
	</div>
		<div id="tabs-3">
		<p>Inbox</p>
	</div>
		<div id="tabs-4">
		<p>Settings tab</p>
	</div>
		<div id="tabs-5">
		<p>Logout</p>
	</div>
</div>

</div>