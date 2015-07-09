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
		$("#doh").datepicker({
			duration: '',
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true
		});
	});
</script>
<table>
<tr>
<td style="font-family: Arial;">Date of Hire</td>
<td><input  style="font-family: Arial;" type="text" id="doh" name="doh" class="text ui-widget-content ui-corner-all" size="11"/></td>
</tr>
<tr>
<td style="font-family: Arial;">Wage</td>
<td><input  style="font-family: Arial;" type="text" id="wage" name="wage" class="text ui-widget-content ui-corner-all" size="4"/></td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" value="Rehire" />
</td>
</tr>
</table>