<?php

$data  = file_get_contents('1.jpg');
$image = pg_escape_bytea($data);
$conn  = pg_connect("user=gdadmin password=plokijuh dbname=gfifdev host=localhost");

pg_query($conn, "UPDATE tb_user SET image='{$image}' WHERE cc='1'");
pg_close($conn);
