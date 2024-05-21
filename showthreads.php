<?php
define('TITLE', 'Συζητήσεις');
require_once('templates/header.inc.php');
$unitid = filter_input(INPUT_GET, 'unitid');

require('mysqli_connect.php');
$q = "SELECT t.id, header, username FROM thread t INNER JOIN user u ON t.userid=u.id WHERE unitid=?;";
$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, 'i', $unitid);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $threadid, $header, $username);
if (mysqli_stmt_num_rows($stmt) == 0) {
    print "<p>Δεν βρέθηκαν συζητήσεις στη συγκεκριμένη θεματική ενότητα</p>\n";
} else {
    print "<table style='text-align: center'>\n";
    print "<h2>Όλες οι Συζητήσεις</h2>\n";
    print "<tr>\n<th>Επικεφαλίδα</th>\n<th>Χρήστης που τη ξεκίνησε</th>\n<th>Ενέργεια</th>\n</tr>\n";
    while (mysqli_stmt_fetch($stmt)) {
        print "<tr>\n<td>$header</td>\n<td>$username</td>\n<td><a href='thread.php?threadid=$threadid'>Άνοιγμα</a></td>\n</tr>\n";
    }
    print "</table>\n";
}
mysqli_stmt_close($stmt);
mysqli_close($dbc);

if (isloggedin()){
    print '<form action="create_thread.php" method="post" class="form--inline">'; print "\n";
    print '<p>Επικεφαλίδα Συζήτησης: <input type="text" name="threadname"></p>'; print "\n";
    print '<p>Μύνημα: <input type="text" name="text" style="width:50%"></p>'; print "\n";
    print '<input type="hidden" name="unitid" value="';
    print $unitid;
    print '">';
    print '<p><input type="submit" value="Δημιουργία Νέας Συζήτησης" class="button--pill"></p>'; print "\n";
    print '</form>';
}

require_once('templates/footer.inc.php');
?>