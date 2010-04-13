<?php
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS);

if (!$link) {
   die('Could not connect: ' . mysql_error());
}

mysql_select_db(DB_NAME,$link);
?>