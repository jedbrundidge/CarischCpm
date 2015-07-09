<link rel="stylesheet" href="/cpm/default.css" type="text/css">
<script src="/cpm/functions.js">
</script>
<form name="new_hire" id="new_hire" autocomplete="off">
<table width="352">
	<tr>
		<td colspan="2"><?php $this->load->view('info_pay'); ?></td>
	</tr>
	<tr>
		<td height="5" width="1"></td>
	</tr>

	<tr disabled>
		<td width="5%"></td>
		<td width="93%" align="left"><font size="1"><strong>Direct Deposit
		Information (Optional):</strong></font></td>
	</tr>
	
	<tr disabled>
		<td colspan="2"><?php $this->load->view('info_bank'); ?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td></td>
		<td><font size="1" height="1"><strong>W4 Information (Required):</strong></font></td>
	</tr>
	<tr>
		<td colspan="2"><?php $this->load->view('info_w4'); ?></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">
		<table width="347">
			<tr>
				<td align="left" width="50%" style="font-size: x-small"><a
					href="javascript:on_Change(true,1,2)">Previous Page</a></td>
				<td align="right" width="50%" style="font-size: x-small"><a
					href="javascript:on_Change(true,3,2)">Next Page</a></td>
		
		</table>
	
	</tr>
	<tr>

	</tr>
</table>
</form>
