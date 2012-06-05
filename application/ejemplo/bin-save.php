<?php 
// Connect to the database
$dbconn  = pg_connect("user=gdadmin password=plokijuh dbname=gfifdev host=localhost");

// Read in a binary file
$data = file_get_contents( '1.jpg' );

echo "Imagen leida: ".strlen($data);

// Escape the binary data
$escaped = bin2hex( $data );

echo "<br>Imagen escapada: ".strlen($escaped);

// Insert it into the database
pg_query( "UPDATE tb_user SET image=decode('{$escaped}' , 'hex')" );

?>
