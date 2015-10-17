<?php

$db = new SQLite3("db/main.db");

echo $db->querySingle('SELECT Cat From Post WHERE ID="7"');
?>
