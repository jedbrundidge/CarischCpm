
<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td>
		<table style="border: none;">
			<tr>
				<td><input type="radio" name="citizenship" value="citizen" style="border: none;" <?php if($this->session->userdata('citizenship')==0) echo "checked";?>>Citizen or US National</td>
			</tr>
			<tr>
				<td><input type="radio" name="citizenship" value="noncitizen" style="border: none;"<?php if($this->session->userdata('citizenship')==1) echo "checked";?>>A noncitizen national of the United States</td>
			</tr>
			<tr>
				<td><input type="radio" name="citizenship" value="lawfulresident" style="border: none;"<?php if($this->session->userdata('citizenship')==2) echo "checked";?>>A Lawful Permanent Resident</input></td>
			</tr>
			<tr><td align="right">Alien Registration # / USCIS # <input type="text" name="alien_admission_number_permres" size="15" value="<?php echo $this->session->userdata('alien_admission_number_permres');?>"></input></td></tr>
			<tr>
				<td><input type="radio" name="citizenship" value="alien_authorized" style="border: none;"<?php if($this->session->userdata('citizenship')==3) echo "checked";?>>Alien Authorized to work until: <input type="text" name="alien_auth_until" size="15" value="<?php echo $this->session->userdata('alien_auth_until');?>"></input></td>
				
			</tr>
			<tr><td align="right">Alien Registration # / USCIS # <input type="text" name="alien_admission_number" size="15" value="<?php echo $this->session->userdata('alien_admission_number');?>"></input></td></tr>
			<tr><td align="center">OR</td></tr>
			<tr><td align="right">Form I-94 Admission # <input type="text" name="alien_i94_number" size="15" value="<?php echo $this->session->userdata('alien_i94_number');?>"></input></td></tr>

		</table>
		</td>
	</tr>
</table>
