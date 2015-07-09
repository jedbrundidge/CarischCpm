<font face="Arial" size="2">
<b>Form 8850</b><br><br>

<input type="hidden" name="katrina[]" value="0">
<input type="hidden" name="tanf[]" value="0">
<input type="hidden" name="swa[]" value="0">
<input type="hidden" name="memfam[]" value="0"> 
<input type="hidden" name="vet[]" value="0">
<input type="hidden" name="vet2[]" value="0">
<input type="hidden" name="vet3[]" value="0">



<input type="checkbox" style="border none;" name="swa[]" <?php if ($this->session->userdata('swa')) { echo "CHECKED"; } ?>></input>Check here if you received a conditional certification from the state workforce agency (SWA) or a participating local agency<br />
&nbsp;&nbsp;&nbsp;&nbsp; for the work opportunity credit.<br>
<br>

<input type="checkbox" style="border none;" name="tanf[]" <?php if ($this->session->userdata('tanf')) { echo "CHECKED"; } ?>></input>Check here if <b>any</b> of the following statements apply to you. <br />
<ul>
<li> I am a member of a family that has received assistance from Temporary Assistance for Needy Families (TANF) for any <br />
9 months during the past 18 months. <br /></li>
<li> I am a veteran and a member of a family that received Supplemental Nutrition Assistance Program (SNAP) benefits<br />
(foodstamps) for at least a 3-month period during the past 15 months.<br /> </li>
<li> I was referred here by a rehabilitation agency approved by the state, an employment network under the Ticket to Work<br />
program, or the Department of Veterans Affairs.<br /></li>
<li> I am at least age 18 but <b>not</b> age 40 or older and I am a member of a family that:<br />
<b>a</b>&nbsp;&nbsp;Received SNAP benefits (food stamps) for the past 6 months, <b>or</b><br />
<b>b</b>&nbsp;&nbsp;Received SNAP benefits (food stamps) for at least 3 of the past 5 months, but is no longer eligible to receive them.<br /></li>
<li>During the past year, I was convicted of a felony or released from prison for a felony.<br /></li>
<li>I received supplemental security income (SSI) benefits for any month ending during the past 60 days.<br /></li>
<li>I am a veteran and I was unemployed for a period or periods totaling at least 4 weeks but less than 6 months during the past year.<br /></ui>
</ul>
<br />

<input type="checkbox" style="border none;" name="vet[]" <?php if ($this->session->userdata('vet')) { echo "CHECKED"; } ?>></input>Check here if you are a veteran and you were unemployed for a period or periods totaling at least 6 months during the past year.<br />
<br />

<input type="checkbox" style="border none;" name="vet2[]" <?php if ($this->session->userdata('vet2')) { echo "CHECKED"; } ?>></input>Check here if you are a veteran entitled to compensation for a service-connected disability and you were discharged or<br />
&nbsp;&nbsp;&nbsp;&nbsp; released from active duty in the U.S. Armed Forces during the past year.<br />
<br />

<input type="checkbox" style="border none;" name="vet3[]" <?php if ($this->session->userdata('vet3')) { echo "CHECKED"; } ?>></input>Check here if you are a veteran entitled to compensation for a service-connected disability and you were unemployed for a<br />
&nbsp;&nbsp;&nbsp;&nbsp; period or periods totaling at least 6 months during the past year.<br />
<br />

<input type="checkbox" style="border none;" name="memfam[]" <?php if ($this->session->userdata('memfam')) { echo "CHECKED"; } ?>></input>Check here if you are a member of a family that:<br />
<ul>
<li>Received TANF payments for at least the past 18 months, <b>or</b><br /></li>
<li>Received TANF payments for any 18 months beginning after August 5, 1997, and the earliest 18-month period beginning after August 5, 1997, ended during the past 2 years, <b>or</b><br /></li>
<li>Stopped being eligible for TANF payments during the past 2 years because federal or state law limited the maximum time those payments could be made.<br /></li>
</ul>
<br />

<input type="checkbox" style="border none;" name="katrina[]" <?php if ($this->session->userdata('katrina')) { echo "CHECKED"; } ?>></input>
<input type="text" size="90%" name="katrina_address" value="<?php echo $this->session->userdata('katrina_address'); ?>"<br /><br />

</font>
