<form name="v8850" id="v8850" action="/cpm/index.php/personnel/val_8850" method="POST">
<input type="text" name="id" id="id" />
<input type="submit" name="run" id="run" value="submit" />
</form>
<?php
if (isset($success)) echo "Success: $success";
if (isset($failures)) echo "Errors: $failures";
?>
