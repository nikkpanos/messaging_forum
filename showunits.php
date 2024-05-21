<?php
define('TITLE', 'Θεματικές Ενότητες');
require_once('templates/header.inc.php');

require('mysqli_connect.php');
    $q = "SELECT id, name FROM unit";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $unit);
    if (mysqli_stmt_num_rows($stmt) == 0) {
        print "<p>Δεν βρέθηκαν θεματικές ενότητες</p>\n";
    } else {
        print "<table style='text-align: center'>\n";
        print "<h2>Όλες οι Ενότητες</h2>\n";
        print "<tr>\n<th>Θεματική Ενότητα</th>\n<th>Ενέργεια</th>\n</tr>\n";
        while (mysqli_stmt_fetch($stmt)) {
            print "<tr>\n<td>$unit</td>\n<td><a href='showthreads.php?unitid=$id'>Μετάβαση</a></td>\n</tr>\n";
        }
        print "</table>\n";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);

require_once('templates/footer.inc.php');
?>