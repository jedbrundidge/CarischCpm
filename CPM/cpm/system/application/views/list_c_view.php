<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td colspan="3"><select name="c_doc_title" style="font-family: Times New Roman; font-size: xx-small; width: 339;">
			<option value="noselect" <?php if($this->session->userdata('c_doc_title') == "noselect") echo "selected";?>></option>
			<option value="ssc" <?php if($this->session->userdata('c_doc_title') == 'ssc') echo "selected";?>>US Social Security Card</option>
			<option value="coba" <?php if($this->session->userdata('c_doc_title') == 'coba') echo "selected";?>>Certification of Birth issued by the Department of State (DS-1350)</option>
			<option value="cobc" <?php if($this->session->userdata('c_doc_title') == 'cobc') echo "selected";?>>Original/Certified copy of state issued birth
			certificate</option>
			<option value="cobc2" <?php if($this->session->userdata('c_doc_title') == 'cobc2') echo "selected";?>>Certification of Birth Abroad issued by the
			Department of State (FS-545)</option>
			<option value="natd" <?php if($this->session->userdata('c_doc_title') == 'natd') echo "selected";?>>Native American Tribal Document</option>
			<option value="uscid" <?php if($this->session->userdata('c_doc_title') == 'uscid') echo "selected";?>>US Citizen ID Card (Form I-197)</option>
			<option value="idcard" <?php if($this->session->userdata('c_doc_title') == 'idcard') echo "selected";?>>ID Card for use of Resident Citizen in US (Form I-179)</option>
			<option value="dhs" <?php if($this->session->userdata('c_doc_title') == 'dhs') echo "selected";?>>Department of Homeland Security issued unexpired
			employement authorization doc</option>
		</select></td>
	</tr>
	<tr>
		<td width="175">Issuing Authority</td>
		<td>:</td>
		<td width="155"><input type="text" name="c_issuing_auth" size="18" value="<?php echo $this->session->userdata('c_issuing_auth'); ?>"></td>
	</tr>
	<tr>
		<td width="175">Document #</td>
		<td>:</td>
		<td width="155"><input type="text" name="c_docnum" size="18"value="<?php echo $this->session->userdata('c_docnum'); ?>"></td>
	</tr>
	<tr>
		<td width="175">Expiration Date (mm/dd/yyyy)</td>
		<td>:</td>
		<td width="155"><input type="text" name="c_expiredate" size="18"value="<?php echo $this->session->userdata('c_expiredate'); ?>"></td>
	</tr>

<!-- Believe this was accidentally pasted code
	<tr>
		<td width="175">Expiration Date (mm/dd/yyyy)</td>
		<td>:</td>
		<td width="155"><input type="text" name="c_expiredate" size="18"value="<?php echo $this->session->userdata('c_expiredate'); ?>"></td>
	</tr>
-->

	<tr>
		<b>Note:</b>&nbsp;<u>US Social Security Card</u> can be chosen below from the List C options unless it includes any of the following restrictions:<br>
		<ul>
		  <li>NOT VALID FOR EMPLOYMENT</li>
		  <li>VALID FOR WORK ONLY WITH INS AUTHORIZATION</li>
		  <li>VALID FOR WORK ONLY WITH DHS AUTHORIZATION</li>
		</ul>
	</tr>


</table>
