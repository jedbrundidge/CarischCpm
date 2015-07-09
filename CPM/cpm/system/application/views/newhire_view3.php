<body onload="show()">
<link rel="stylesheet" href="/cpm/default.css" type="text/css">
<script src="/cpm/functions.js">
</script>
<form name="new_hire" id="new_hire" autocomplete="off">
<table width="352">
	<tr>
		<td colspan="2"><?php $this->load->view('info_i9'); ?></td>
	</tr>
	<tr>
		<td height="5" width="1"></td>
	</tr>
	<tr>
		<td width="7%"></td>
		<td width="93%" align="left"><font size="1">You must complete List A,
		or complete List B <strong>AND</strong> List C.</font></td>
	</tr>
	<tr>
		<td width="7%"></td>
		<td width="93%"><select name="list_id"
			style="font-size: x-small; font-family: Arial;"
			onchange="return show();">
			<option name="1" id="1" value="1" <?php if($this->session->userdata('list_id')==1) echo "selected";?>>List A</option>
			<option name="2" id="2" value="2" <?php if($this->session->userdata('list_id')==2) echo "selected";?>>List B and C</option>
		</select></td>
	</tr>


	<tr id="la">
		<td width="7%"></td>
		<td width="93%"><font size="1"><strong>List A:</strong></font></td>
	
	</tr>
	<tr>
		<td colspan="2" id="d1"><?php $this->load->view('list_a_view'); ?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr id="lb">
		<td></td>
		<td><font size="1" height="1"><strong>List B:</strong></font></td>
	</tr>
	<tr id="d2">
		<td colspan="2"><?php $this->load->view('list_b_view'); ?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr id="lc">
		<td></td>
		<td><font size="1" height="1"><strong>List C:</strong></font></td>
	</tr>
	<tr id="d3" name="d3">
		<td colspan="2"><?php $this->load->view('list_c_view'); ?></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">
		<table width="347">
			<tr>
				<td align="left" width="50%" style="font-size: x-small"><a
					href="javascript:on_Change(true,2,3)">Previous Page</a></td>
				<td align="right" width="50%" style="font-size: x-small"><a
					href="javascript:on_Change(true,4,3)">Next Page</a></td>
		
		</table>
	
	</tr>
	<tr>

	</tr>
</table>
</form>