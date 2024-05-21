<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require_once('includes/functions.inc.php');

if (isloggedin() && isadmin()){
    $unitid = filter_input(INPUT_GET, 'unitid');
    $unitname = filter_input(INPUT_GET, 'unitname');

    require('mysqli_connect.php');
    $q = "UPDATE unit SET name=? WHERE id=?";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'si', $unitname, $unitid);
    mysqli_stmt_execute($stmt);

    header("Location: profile.php");
}

if (!isloggedin() || !isadmin()){
    print "<h1>Σφάλμα 401</h1>\n";
    print "<p>Μη εξουσιοδοτημένο αίτημα πρόσβασης</p>\n";   
}

ob_end_flush();

?>