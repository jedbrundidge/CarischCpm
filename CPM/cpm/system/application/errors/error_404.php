<?php header("HTTP/1.1 404 Not Found"); ?>
<html>
<head>
<title>404 Page Not Found</title>
<b>Please contact Mike Tibodeau at miket@carischinc.com or 952-473-4291 ext 205.</b>
<style type="text/css">

body {
background-color:	#fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			12px;
color:				#000;
}

#content  {
border:				#999 1px solid;
background-color:	#fff;
padding:			20px 20px 12px 20px;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#990000;
margin: 			0 0 4px 0;
}
</style>
</head>
<body>
	<div id="content">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>
<?php
$smtp = & new Swift_Connection_SMTP("mail.carischinc.com", 25);
$swift = & new Swift($smtp);
$mailbody = "$heading\n";
$mailbody .= "$message\n";
//$mailbody .= "$filepath\n";
//$mailbody .= "$line\n";
$subject = "404 Error";
$message =& new Swift_Message($subject,$mailbody);
$mailto='miket@carischinc.com';
$swift->send($message, $mailto, 'cpm@carischinc.com');
?>