<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require_once('includes/functions.inc.php');

if (isloggedin()){
    $threadname = filter_input(INPUT_POST, 'threadname');
    $text = filter_input(INPUT_POST, 'text');
    $userid = $_SESSION['userid'];
    $unitid = filter_input(INPUT_POST, 'unitid');

    if(empty($threadname) || empty($text) || empty($userid) || empty($unitid)){   
        ob_end_clean();
        header("Location: showunits.php");
        exit();
    }

    require('mysqli_connect.php');

    $q = "INSERT INTO thread(header, userid, unitid) VALUES(?, ?, ?);";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'sii', $threadname, $userid, $unitid);
    mysqli_stmt_execute($stmt);

    $q = "SELECT id from thread WHERE header=?;";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 's', $threadname);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $threadid);
    mysqli_stmt_fetch($stmt);

    $q = "INSERT INTO message(text, userid, threadid) VALUES(?, ?, ?);";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'sii', $text, $userid, $threadid);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
    ob_end_flush();

    header("Location: showunits.php");
}else{
    print "<h1>Σφάλμα 401</h1>\n";
    print "<p>Μη εξουσιοδοτημένο αίτημα πρόσβασης</p>\n";   
}

ob_end_flush();

?>