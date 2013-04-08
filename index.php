<?php

include_once("arc2/ARC2.php");
include_once("graphinfo.php");
include_once("labels.php");

function startswith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

$inchi = $_GET["inchi"];
$inchikey = $_GET["inchikey"];

/* configuration */ 
$config = array(
  /* remote endpoint */
  'remote_store_endpoint' => 'http://localhost:8893/sparql',
);

/* instantiation */
$store = ARC2::getRemoteStore($config);

// cheminf:CHEMINF_000200 [ a cheminf:CHEMINF_000059 ; cheminf:SIO_000300 \"?inchikey\" . ]

$q = <<<SPARQL
PREFIX cheminf: <http://semanticscience.org/resource/>
SELECT ?graph ?p ?o WHERE {
  GRAPH ?graph {
    ?mol cheminf:CHEMINF_000200 [ a cheminf:CHEMINF_000059 ; cheminf:SIO_000300 "$inchikey" ] ;
      ?p ?o .
  }
}
SPARQL;

$rows = $store->query($q, 'rows');

echo "<html>\n";
echo "<head><title>$inchikey</title></head>\n";
echo "<body>\n";

// echo "<pre>\n";
// echo "$q\n";
// echo "</pre>\n";

// echo "<pre>\n";
// print_r($rows);
// echo "</pre>\n";

echo "<table>\n";
foreach ($graphs as $graph) {
  echo "<h4>" . $graphInfo[$graph]['name'] . "</h4>";
  echo "<p>Provider: " . $graphInfo[$graph]['provider'] . "; license: <a href=\"" . $graphInfo[$graph]['licenseURL'] . "\">" . $graphInfo[$graph]['licenseName'] . "</a></p>";
  foreach ($rows as $row) {
    if ($row['graph'] == $graph) {
      if ($row['p'] != 'http://semanticscience.org/resource/CHEMINF_000200') {
        echo "<tr>\n";
          $predicate = $row['p'];
          if ($labels[$predicate]) {
            $predicate = $labels[$predicate];
          }
          $object = $row['o'];

          echo "<td>$predicate</td>\n";
          $object = $row['o'];
          if (startswith($object, "http://")) {
            echo "<td><a href=\"$object\">$object</a></td>\n";
          } else {
            echo "<td>" . $row['o'] . "</td>\n";
          }
        echo "</tr>\n";
      }
    }
  }
}
echo "</table>\n";
echo "</body>\n";
echo "</html>\n";

?>
