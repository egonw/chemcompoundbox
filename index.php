<?php

include_once("arc2/ARC2.php");

/* configuration */ 
$config = array(
  /* remote endpoint */
  'remote_store_endpoint' => 'http://localhost:8893/sparql',
);

/* instantiation */
$store = ARC2::getRemoteStore($config);

$q = 'SELECT * WHERE { [] ?p [] } LIMIT 10';
$rows = $store->query($q, 'rows');

echo "<html>\n";

echo "$rows";

foreach ($rows as $row) {
  foreach ($row as $key => $value) {
    echo "key: $key => $value \n";
  }
}

echo "</html>\n";

?>
