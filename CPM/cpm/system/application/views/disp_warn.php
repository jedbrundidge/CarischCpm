<?php
foreach ($info as $key=>$val) {
	if (!is_array($val))
	echo $key.' '.$val.'<br />';
}
print_r($info['violations']);
?>