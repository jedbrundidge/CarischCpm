<link rel="stylesheet" href="/cpm/default.css" type="text/css">
<style>
input, select {
	font-family: Arial;
}
</style>
<body OnLoad="document.login_form.username.focus();">
<?php 
$maintain = false;
if ($maintain) {
	echo "Down for maintenance.";
} else {
$attributes = array(
	'class' => 'login',
	'id' => 'login_form',
	'name' => 'login_form',
	'autocomplete' => 'off'
	);
	
$username = array(
	'name' => 'username',
	'id' => 'username',
	);
	
$password = array(
	'name' => 'password',
	'id' => 'password'
	);
	
$template = array(
	'heading_cell_start' => '<th colspan="2" align="left">'
	);
if (!isset($wid)) {
	$wid="";
}
if (!isset($type)) {
	$type='';
}
echo "<title>Carisch Inc. Personnel Login</title>";
echo form_open("/personnel/login/$wid/$type",$attributes);

$this->table->set_template($template);
$this->table->set_heading('Carisch, Inc. Personnel Login');

$this->table->add_row('Username',form_input($username));
$this->table->add_row('Password',form_password($password));

echo $this->table->generate();

echo br(1);
echo form_submit('login_submit','Login');

}

?>
