<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td colspan="3"><select name="a_doc_title" id="a_doc_title"
			style="font-family: Times New Roman; width: 339; font-size: xx-small;"
			size="1" width="50">
			<option value="noselect" <?php if($this->session->userdata('a_doc_title') == 'noselect') echo "selected";?>></option>
			<option value="passport" <?php if($this->session->userdata('a_doc_title') == 'passport') echo "selected";?>>U.S. Passport OR U.S. Passport Card</option>
			<option value="perm" <?php if($this->session->userdata('a_doc_title') == 'perm') echo "selected";?>>Permanent Resident Card / Alien Registration Receipt Card (Form I-551)</option>
			<option value="alienreg" <?php if($this->session->userdata('a_doc_title') == 'alienreg') echo "selected";?>>Employment Authorization Document (I-766)</option>
			<option value="unexfpwst" <?php if($this->session->userdata('a_doc_title') == 'unexfpwst') echo "selected";?>>Unexpired foreign passport w/ I-551 Stamp</option>
			<option value="unexfpweadr" <?php if($this->session->userdata('a_doc_title') == 'unexfpweadr') echo "selected";?>>Foreign Passport w/ Form I-94 or I-94A</option>
			<option value="micro" <?php if($this->session->userdata('a_doc_title') == 'micro') echo "selected";?>>Passport from FSM/RMI w/ Form I-94 or I-94A</option>
		</select></td>
	</tr>
	<tr>
		<td width="175">Issuing Authority</td>
		<td>:</td>
		<td width="155"><input type="text" name="a_issuing_auth" size="18" value="<?php echo $this->session->userdata('a_issuing_auth'); ?>"></td>
	</tr>
		<tr>
		<td width="175">Document #</td>
		<td>:</td>
		<td width="155"><input type="text" name="a_docnum" size="18" value="<?php echo $this->session->userdata('a_docnum'); ?>"></td>
	</tr>
		<tr>
		<td width="175">Expiration Date (mm/dd/yyyy)</td>
		<td>:</td>
		<td width="155"><input type="text" name="a_expiredate" size="18" value="<?php echo $this->session->userdata('a_expiredate'); ?>"></td>
	</tr>
		<tr>
		<td width="175">Document #</td>
		<td>:</td>
		<td width="155"><input type="text" name="a_docnum2" size="18" value="<?php echo $this->session->userdata('a_docnum2'); ?>"></td>
	</tr>
		</tr>
		<tr>
		<td width="175">Expiration Date (mm/dd/yyyy)</td>
		<td>:</td>
		<td width="155"><input type="text" name="a_expiredate2" size="18" value="<?php echo $this->session->userdata('a_expiredate2'); ?>"></td>
	</tr>

</table>
