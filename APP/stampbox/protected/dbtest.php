<?php
// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=ds user=postgres password=Wfd9epa4")
    or die('Could not connect: ' . pg_last_error());

echo date();

// Performing SQL query
$query = "SELECT * from ds.t_customer;";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML
//echo "<table>\n";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
//    echo "\t<tr>\n";
    foreach ($line as $col_value) {
//        echo "\t\t<td>$col_value</td>\n";
    }
//    echo "\t</tr>\n";
}
echo "</table>\n";

// Free resultset
pg_free_result($result);

echo date();

// Closing connection
pg_close($dbconn);
?>
