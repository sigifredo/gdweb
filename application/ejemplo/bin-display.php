<?php 
// Connect to the database
$dbconn  = pg_connect("user=gdadmin password=plokijuh dbname=gfifdev host=localhost");

// Get the bytea data
$res = pg_query("SELECT encode(image, 'base64') AS data FROM tb_user WHERE cc='1'");  
$raw = pg_fetch_result($res, 'data');

// Convert to binary and send to the browser
header('Content-type: image/jpeg');
echo base64_decode($raw);
?>
