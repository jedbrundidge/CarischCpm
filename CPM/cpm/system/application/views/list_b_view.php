<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td colspan="3"><select name="b_doc_title"
			style="font-family: Times New Roman; font-size: xx-small; width: 339;">
			<option value="noselect" <?php if($this->session->userdata('b_doc_title')=='noselect') echo 'selected';?>></option>
			<option value="drvl" <?php if($this->session->userdata('b_doc_title')=='drvl') echo 'selected';?>>Driver's License</option>
			<option value="stateid" <?php if($this->session->userdata('b_doc_title')=='stateid') echo 'selected';?>>State Issued ID Card w/ photo or personal info</option>
			<option value="idcard" <?php if($this->session->userdata('b_doc_title')=='idcard') echo 'selected';?>>ID issued by fed/state/local gov't w/ photo or personal info</option>
			<option value="schid" <?php if($this->session->userdata('b_doc_title')=='schid') echo 'selected';?>>School ID w/ Photo</option>
			<option value="voterid" <?php if($this->session->userdata('b_doc_title')=='voterid') echo 'selected';?>>Voter's registration card</option>
			<option value="milit" <?php if($this->session->userdata('b_doc_title')=='milit') echo 'selected';?>>US Military Card or Draft Record</option>
			<option value="midepc" <?php if($this->session->userdata('b_doc_title')=='midepc') echo 'selected';?>>Military Dependent's ID Card</option>
			<option value="coastg" <?php if($this->session->userdata('b_doc_title')=='coastg') echo 'selected';?>>US Coast Guard Merchant Mariner Card</option>
			<option value="natamtr" <?php if($this->session->userdata('b_doc_title')=='natamtr') echo 'selected';?>>Native American tribal document</option>
			<option value="candr" <?php if($this->session->userdata('b_doc_title')=='candr') echo 'selected';?>>Canadian Driver's license</option>
			<option value="u18sch" <?php if($this->session->userdata('b_doc_title')=='u18sch') echo 'selected';?>>U18-School record/report card</option>
			<option value="u18clin" <?php if($this->session->userdata('b_doc_title')=='u18clin') echo 'selected';?>>U18-Clinic, doctor or hospital record</option>
			<option value="u18day" <?php if($this->session->userdata('b_doc_title')=='u18day') echo 'selected';?>>U18-Day-care/nursery school record</option>
		</select></td>
	</tr>
	<tr>
		<td width="175">Issuing Authority</td>
		<td>:</td>
		<td width="155"><input type="text" name="b_issuing_auth" size="18" value="<?php echo $this->session->userdata('b_issuing_auth'); ?>"></td>
	</tr>
	<tr>
		<td width="175">Document #</td>
		<td>:</td>
		<td width="155"><input type="text" name="b_docnum" size="18" value="<?php echo $this->session->userdata('b_docnum'); ?>"></td>
	</tr>
	<tr>
		<td width="175">Expiration Date (mm/dd/yyyy)</td>
		<td>:</td>
		<td width="155"><input type="text" name="b_expiredate" size="18" value="<?php echo $this->session->userdata('b_expiredate'); ?>"></td>
	</tr>
</table>
