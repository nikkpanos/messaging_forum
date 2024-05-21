<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require_once('includes/functions.inc.php');

if (isloggedin()){
    $threadid = filter_input(INPUT_POST, 'threadid');
    $message = filter_input(INPUT_POST, 'message');
    $userid = $_SESSION['userid'];

    if(empty($threadid) || empty($message) || empty($userid)){   
        ob_end_clean();
        header("Location: showunits.php");
        exit();
    }

    require('mysqli_connect.php');

    $q = "INSERT INTO message(text, userid, threadid) VALUES(?, ?, ?);";
    $stmt = mysqli_prepare($dbc, $q);
    mysqli_stmt_bind_param($stmt, 'sii', $message, $userid, $threadid);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
    ob_end_flush();

    header("Location: thread.php?threadid=$threadid");
}else{
    print "<h1>Σφάλμα 401</h1>\n";
    print "<p>Μη εξουσιοδοτημένο αίτημα πρόσβασης</p>\n";   
}

ob_end_flush();

?>