<script language="Javascript">
var sURL = unescape(window.location.pathname);

	function onlyNumbers(evt)
	{
		var e = event || evt;  // for trans-browser compatibility
		var charCode = e.which || e.keyCode;
		if (charCode > 31 && (charCode < 45 || charCode > 57 || charCode == 47))
			return false;
		return true;
	}
	function on_Change(next) {
        var ssn=document.new_hire.ssn.value;
        var first_name=document.new_hire.first_name.value;
        var last_name=document.new_hire.last_name.value;
        var middle_initial=document.new_hire.middle_initial.value;
        var ethnic_code=document.new_hire.ethnic_code.value;
		var gender=document.new_hire.gender.value;
		var marital_status=document.new_hire.marital_status.value;
		var month=document.new_hire.month.value;
		var day=document.new_hire.day.value;
		var year=document.new_hire.year.value;
		var street_address=document.new_hire.street_address.value;
		var city=document.new_hire.city.value;
		var state=document.new_hire.state.value;
		var zip=document.new_hire.zip.value;
		var phone=document.new_hire.phone.value;
        var carisch_dimes=0;

        var next = next;
        
        //var cd=document.new_hire['dimes[]'];
	    //if(cd[1].checked){
	    //    carisch_dimes = 1;
	    //}

        var xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

        var cInfo=	"&ssn="+ssn+
        			"&first_name="+first_name+
        			"&last_name="+last_name+
        			"&middle_initial="+middle_initial+
        			"&ethnic_code="+ethnic_code+
        			"&gender="+gender+
        			"&marital_status="+marital_status+
        			"&month="+month+
        			"&day="+day+
        			"&year="+year+
					"&street_address="+street_address+
					"&city="+city+
					"&state="+state+
					"&zip="+zip+
					"&phone="+phone;
        xmlhttp.open("post","http://192.168.0.12:81/cpm/index.php/personnel/new_hire/1",false);
        xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
        xmlhttp.send(cInfo);
        if (next == true) {
            window.location.href = "http://192.168.0.12:81/cpm/index.php/personnel/new_hire/2";
        } else {
			window.location.href = sURL;
        }
    }

</script>
<style type="text/css">
table {
	border: 1px solid #B0B0B0
}

td,tr {
	border: 0
}
</style>
<form name="new_hire">
<table>
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
		<td align="right"><a href="javascript:on_Change(true)">Continue (Next
		Page)</a></td>
	</tr>
</table>

</form>
