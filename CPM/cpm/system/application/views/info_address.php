
<table rules="none" cellpadding="1" cellspacing="0">
	<tr>
		<td width="175">Street Address</td>
		<td>:</td>
		<td width="155"><input type="text" name="street_address"
			id="street_address"
			value="<?php echo $this->session->userdata('street_address'); ?>"></input>
		</td>
	</tr>
	<tr>
		<td width="175">Apartment Number</td>
		<td>:</td>
		<td width="155"><input type="text" name="apt_number" size="6" maxlength="6" id="apt_number"
			value="<?php echo $this->session->userdata('apt_number'); ?>"></input>
		</td>
	</tr>
	<tr>
		<td>City or Town / State</td>
		<td>:</td>
		<td><input type="text" name="city" size="13" id="city"
			value="<?php echo $this->session->userdata('city'); ?>"><select
			name="state" id="state" style="margin: 0px">
			<?php
			$states = $this->db->query("SELECT `state` FROM `states` ORDER BY `state`");
			$csid = $this->session->userdata('csid');
			$c_state = $this->db->query("SELECT `state` FROM `stores` WHERE `store_id` = $csid");
			$c_state = $c_state->first_row();
			$c_state = $c_state->state;
			foreach ($states->result() as $state) {
				$session_state = $this->session->userdata('state');
				echo "<option value=\"$state->state\" ";
				if ($this->session->userdata('state') == $state->state) {
					echo "selected";
					$c_state = '';
				}
				if ($state->state == $c_state && empty($session_state)) {
					echo "selected";
				}

				echo ">$state->state</option>
					";
			}
			?>
		</select></td>
	</tr>
	<tr>
		<td>Zip Code</td>
		<td>:</td>
		<td><input type="text" name="zip" size="5" maxlength="5" id="zip"
			onkeypress="return onlyNumbers();" class="num"
			value="<?php echo $this->session->userdata('zip'); ?>"></input></td>
	</tr>
	<tr>
		<td>County</td>
		<td>:</td>
		<td><input type="text" name="county" size="14" maxlength="28"
			id="county" value="<?php echo $this->session->userdata('county'); ?>"></input></td>
	</tr>
	<tr>
		<td>Phone Number</td>
		<td>:</td>
		<td><input type="text" name="phone" size="10" maxlength="10"
			id="phone" onkeypress="return onlyNumbers();" class="num"
			value="<?php echo $this->session->userdata('phone'); ?>"></input></td>
	</tr>
	<tr>
		<td>Email Address (Optional)</td>
		<td>:</td>
		<td width="155"><input type="text" name="email"
			id="email" value="<?php echo $this->session->userdata('email'); ?>"></input>
	</tr>

</table>
