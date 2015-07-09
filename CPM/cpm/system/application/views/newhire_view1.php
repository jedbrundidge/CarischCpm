<head>
<link rel="stylesheet" href="/cpm/default.css" type="text/css">
</head>
<script src="/cpm/functions.js">
</script>
<form name="new_hire" id="new_hire" autocomplete="off">
<table width="355">
	<tr>
		<td><?php $this->load->view('info_ident'); ?></td>
	</tr>
	<tr>
		<td><?php $this->load->view('info_address'); ?></td>
	</tr>
	<tr>
		<td><?php $this->load->view('info_personal'); ?></td>
	</tr>
	<tr>
		<td align="right"><a href="javascript:on_Change(true,2,1)"
			style="font-size: x-small">Next Page</a></td>
	</tr>
	<tr>

	</tr>
</table>

</form>
