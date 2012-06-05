<?php

$conn  = pg_connect("user=gdadmin password=plokijuh dbname=gfifdev host=localhost");
$query = pg_query($conn, "SELECT image FROM tb_user WHERE cc = '1'");
$row   = pg_fetch_row($query);
$image = pg_unescape_bytea($row[0]);

header("Content-type: image/jpeg");
echo $image;

pg_close($conn);

