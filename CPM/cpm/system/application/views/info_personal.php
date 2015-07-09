
<table rules="none" cellpadding="1" cellspacing="0" width="350">
	<tr>
		<td width="175">Ethnic Code</td>
		<td>:</td>
		<td width="155"><select name="ethnic_code">
			<option value="1"
			<?php if ($this->session->userdata('ethnic_code') == 1) echo "selected"; ?>>Caucasian</option>
			<option value="2"
			<?php if ($this->session->userdata('ethnic_code') == 2) echo "selected"; ?>>Black</option>
			<option value="3"
			<?php if ($this->session->userdata('ethnic_code') == 3) echo "selected"; ?>>Hispanic/Latino</option>
			<option value="4"
			<?php if ($this->session->userdata('ethnic_code') == 4) echo "selected"; ?>>Asian</option>
			<option value="5"
			<?php if ($this->session->userdata('ethnic_code') == 5) echo "selected"; ?>>American
			Indian</option>
			<option value="6"
			<?php if ($this->session->userdata('ethnic_code') == 6) echo "selected"; ?>>Native Hawaiian</option>
			<option value="7"
			<?php if ($this->session->userdata('ethnic_code') == 7) echo "selected"; ?>>Two or More</option>
		</select></td>
	</tr>
	<tr>
		<td width="175">Gender</td>
		<td>:</td>
		<td width="155"><select name="gender">
			<option value="male"
			<?php if ($this->session->userdata('gender') == 'male') echo "selected"; ?>>Male</option>
			<option value="female"
			<?php if ($this->session->userdata('gender') == 'female') echo "selected"; ?>>Female</option>
		</select></td>
	</tr>
	<tr>
		<td width="175">Marital Status</td>
		<td>:</td>
		<td width="155"><select name="marital_status">
			<option value="0"
			<?php if ($this->session->userdata('marital_status') == 0) echo "selected"; ?>>Single</option>
			<option value="1"
			<?php if ($this->session->userdata('marital_status') == 1) echo "selected"; ?>>Married</option>
			<option value="2"
			<?php if ($this->session->userdata('marital_status') == 2) echo "selected"; ?>>Married @ Single</option>
		</select></td>
	</tr>
	
	<tr>
		<td width="175">Birth Date</td>
		<td>:</td>
		<td width="155"><?php 
		$ds = $this->cpm_functions->date(0,100,$this->session->userdata('month'),$this->session->userdata('day'),$this->session->userdata('year'));
		echo $ds['month'];
		echo $ds['day'];
		echo $ds['year'];
		  ?></td>
	</tr>
	<tr>
		<td width="175">Start Date</td>
		<td>:</td>
		<td width="155"><?php
		$sd = $this->cpm_functions->sdate(1,0,$this->session->userdata('smonth'),$this->session->userdata('sday'),$this->session->userdata('syear'));
		echo $sd['smonth'];
		echo $sd['sday'];
		echo $sd['syear'];?>
	
	</tr>
</table>
