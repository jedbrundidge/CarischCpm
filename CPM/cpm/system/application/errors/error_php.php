<?php
/*
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p><b>Please contact Mike Tibodeau at miket@carischinc.com or 952-473-4291 ext 205.</b></p>

<p>Severity: <?php echo $severity; ?></p>
<p>Message:  <?php echo $message; ?></p>
<p>Filename: <?php echo $filepath; ?></p>
<p>Line Number: <?php echo $line; ?></p>
<?php
$smtp = & new Swift_Connection_SMTP("mail.carischinc.com", 25);
$swift = & new Swift($smtp);
$mailbody = "$severity\n";
$mailbody .= "$message\n";
$mailbody .= "$filepath\n";
$mailbody .= "$line\n";
$subject = "PHP Error";
$message =& new Swift_Message($subject,$mailbody);
$mailto='miket@carischinc.com';
$swift->send($message, $mailto, 'cpm@carischinc.com');
?>
</div>
*/ ?>