<?php

include_once("arc2/ARC2.php");
include_once("graphinfo.php");

$inchi = $_GET["inchi"];

/* configuration */ 
$config = array(
  /* remote endpoint */
  'remote_store_endpoint' => 'http://localhost:8893/sparql',
);

/* instantiation */
$store = ARC2::getRemoteStore($config);

$q = <<<SPARQL
SELECT ?graph ?p ?o WHERE {
  GRAPH ?graph {
    ?mol cheminf:CHEMINF_000200 [ a cheminf:CHEMINF_000113 ; cheminf:SIO_000300 ?inchi . ]
      ?p ?o .
  }
} LIMIT 2
SPARQL;

$rows = $store->query($q, 'rows');

echo "<html>\n";

echo "$rows";

echo "<pre>\n";
print_r($rows);
echo "</pre>\n";

echo "<table>\n";
foreach ($graphs as $graph) {
  foreach ($rows as $row) {
    if ($row['graph'] == $graph) {
      foreach (array_keys($row) as $key) {
        echo "<tr>\n";
        echo "<td>$key</td><td>" . $row[$key] . "</td>\n";
        echo "</tr>\n";
      }
    }
  }
}
echo "</table>\n";
echo "</html>\n";

?>
