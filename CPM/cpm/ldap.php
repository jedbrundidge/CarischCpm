#!/usr/bin/php
<?php

mysql_connect('192.168.0.16','root','server681');
mysql_select_db('personnel');

$x = mysql_query("SELECT CONCAT(REPLACE(`last_name`,'III',''),SUBSTR(`first_name`,1,1)) as full_name FROM `users`");

$ad = ldap_connect('ldaps://rticad.rticdomain.com',636) or die('Could not connect to RTI AD');
                ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
                ldap_bind($ad) or die('cannot bind anonymously');
              ldap_bind($ad,'passwdadmin@rticdomain.com','Carisch681') or die('cannot bind w/ user');



while ($full_name = mysql_fetch_assoc($x)) {
//		if ($x>0) continue;
		$sResults = ldap_search($ad,'dc=rticdomain,dc=com',"(samaccountname=CarTemp.david)");
		$info = ldap_get_entries($ad,$sResults);
	$entry = ldap_first_entry($ad,$sResults);
print_r($info);
print(chr(10));

		$dn = ldap_get_dn($ad,$entry);

$uac['useraccountcontrol'][0]="512";
ldap_modify($ad,$dn,$uac);
echo ldap_error($ad);
		

//		echo $full_name['full_name'] . ' ' . $dn . chr(10);
		if (strlen($dn) > 1) {
			$s++;
		}
			$f++;
//		$newpass = 'test';
//		$newpass = "\"". $newpass ."\"";
//		$newpw = mb_convert_encoding($newpass,"UTF-16LE");		

//		$userdata['unicodePwd'] = $newpw;


	

		//ldap_mod_replace($ad,$dn,$userdata);
		//echo ldap_error($ad);
}

echo "$s/$f";
?>
