<?php

$a = "'";

echo "a: $a<br>";
$e = pg_escape_bytea($a);
echo "e: $e<br>";
