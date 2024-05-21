<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require_once('includes/functions.inc.php');

if (isloggedin() && isadmin()){
    $userid = filter_input(INPUT_GET, 'userid');
    $level = filter_input(INPUT_GET, 'level');

    require('mysqli_connect.php');
    if ($level==1){
        $q = "UPDATE user SET level=0 WHERE id=?;";
    } else{
        $q = "UPDATE user SET level=1 WHERE id=?;";
    }

    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'i', $userid);
    mysqli_stmt_execute($stmt);

    header("Location: profile.php");
}

if (!isloggedin() || !isadmin()){
    print "<h1>Σφάλμα 401</h1>\n";
    print "<p>Μη εξουσιοδοτημένο αίτημα πρόσβασης</p>\n";   
}

ob_end_flush();

?>