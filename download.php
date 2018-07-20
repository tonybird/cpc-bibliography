<?php
$filename = $_POST['filename'];

if ($_POST['filepath']) {
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Content-Length: ' . filesize($filepath));
  echo readfile($filepath);
} else {
  header("Content-type: text/plain");
  header("Content-Disposition: attachment; filename={$filename}");
  echo $_POST['tofile'];
}



 ?>
