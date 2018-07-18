<?php
$filename = $_POST['filename'];

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename={$filename}");
   echo $_POST['tofile'];

 ?>
