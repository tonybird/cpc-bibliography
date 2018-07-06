<?php
$filename = $_POST['filename'];

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename={$filename}.ris");
   echo $_POST['tofile'];

 ?>
