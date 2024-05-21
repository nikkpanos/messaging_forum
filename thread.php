<?php
define('TITLE', 'Συζητήσεις');
require_once('templates/header.inc.php');

$threadid = filter_input(INPUT_GET, 'threadid');
require('mysqli_connect.php');
$q = "SELECT m.id, text, username, t.header FROM message m INNER JOIN thread t ON m.threadid=t.id INNER JOIN user u ON m.userid=u.id  WHERE threadid=?;";
$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, 'i', $threadid);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $messageid, $text, $username, $header);
if (mysqli_stmt_num_rows($stmt) == 0) {
    print "<p>Δεν βρέθηκαν μυνήματα στη συγκεκριμένη συζήτηση</p>\n";
} else {
    $count = 1;
    while (mysqli_stmt_fetch($stmt)) {
        if ($count == 1) print "<h2>$header</h2>\n";
        $count++;
        print "<div style='border:1px solid black'><p>$username</p><p>$text</p></div><br>\n";
    }   
}
mysqli_stmt_close($stmt);
mysqli_close($dbc);


if (isloggedin()){
    print '<form action="create_message.php" method="post" class="form--inline">'; print "\n";
    print '<p><input type="text" name="message"></p>'; print "\n";
    print '<input type="hidden" name="threadid" value="';
    print $threadid;
    print '">';
    print '<p><input type="submit" value="Υποβολή Σχολίου" class="button--pill"></p>'; print "\n";
    print '</form>';
}


require_once('templates/footer.inc.php');
?>