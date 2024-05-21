<?php
define('TITLE', 'Προφίλ Χρήστη');
require_once('templates/header.inc.php');

if (!isloggedin()){
    ob_end_clean();
    header("Location: login_error.php");
    exit();
}
?>

<div>
<?php
    if(isadmin()){
        print "<h2>Δημιουργία Χρήστη</h2>\n";
        print "<p><a href='create_user.php'>Δημιουργία</a></p>\n";

        print "<h2>Δημιουργία Θεματικής Ενότητας</h2>\n";
        print "<p><a href='create_unit.php'>Δημιουργία</a></p>\n";

        print "<h2>Επεξεργασία Θεματικών Ενοτήτων</h2>\n";
        require('mysqli_connect.php');
        $q = "SELECT id, name FROM unit";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $id, $unit);
        if (mysqli_stmt_num_rows($stmt) == 0) {
            print "<p>Δεν βρέθηκαν θεματικές ενότητες</p>\n";
        } else {
            print "<table>\n";
            print "<tr>\n<th>Θεματική Ενότητα</th>\n<th>Επεξεργασία</th>\n<th>Διαγραφή</th>\n</tr>\n";
            while (mysqli_stmt_fetch($stmt)) {
                print "<tr>\n<td>$unit</td>\n";
                print "<td><a href='edit_unit.php?unitid=$id'><img src='icons/upd.png' height='20px'></a></td>\n";
                print "<td><a href='delete_unit.php?unitid=$id'><img src='icons/del.png' height='20px'></a></td></tr>\n";
            }
            print "</table>\n";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);

        print "<h2>Επεξεργασία δικαιωμάτων χρηστών</h2>\n";
        require('mysqli_connect.php');
        $q = "SELECT id, username, firstname, lastname, level, email FROM user";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $id, $username, $firstname, $lastname, $level, $email);
        if (mysqli_stmt_num_rows($stmt) == 0) {
            print "<p>Δεν βρέθηκαν χρήστες</p>\n";
        } else {
            print "<table>\n";
            print "<tr>\n<th>ID</th>\n";
            print "<th>Username</th>\n";
            print "<th>Όνομα</th>\n";
            print "<th>Επίθετο</th>\n";
            print "<th>Δικαιώματα</th>\n";
            print "<th>Email</th>\n";
            print "<th>Αλλαγή δικαιωμάτων</th>\n";
            print "<th>Διαγραφή λογαριασμού</th></tr>\n";
            while (mysqli_stmt_fetch($stmt)) {
                print "<tr>\n<td>$id</td>\n";
                print "<td>$username</td>\n";
                print "<td>$firstname</td>\n";
                print "<td>$lastname</td>\n";
                print "<td>$level</td>\n";
                print "<td>$email</td>\n";
                print "<td><a href='change_user_level.php?userid=$id&level=$level'><img src='icons/upd.png' height='20px'></a></td>\n";
                print "<td><a href='delete_user.php?userid=$id'><img src='icons/del.png' height='20px'></a></td></tr>\n";
            }
            print "</table>\n";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);


    }
?>
</div>

<?php require_once('templates/footer.inc.php'); ?>