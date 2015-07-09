<?php
class Uuid extends Model {
	
	function Uuid()
	{
		parent::Model();
	}
	
	public function generate_uuid () {
		$chars = md5(uniqid(mt_rand(), true));
		$uuid  = substr($chars,0,8) . '-';
		$uuid .= substr($chars,8,4) . '-';
		$uuid .= substr($chars,12,4) . '-';
		$uuid .= substr($chars,16,4) . '-';
		$uuid .= substr($chars,20,12);
		return strtoupper($uuid);
	}
}
?>
