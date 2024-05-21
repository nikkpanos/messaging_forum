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
        print "<h2>Εκκρεμής Αιτήσης Δημιουργίας Λογαριασμών</h2>";
        require('mysqli_connect.php');
        $q = "SELECT id, username, firstname, lastname, level, email FROM user WHERE approved=0;";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $id, $username, $firstname, $lastname, $level, $email);
        if (mysqli_stmt_num_rows($stmt) == 0) {
            print "<p>Δεν βρέθηκαν εκκρεμής αιτήσεις</p>\n";
        } else {
            print "<table style='text-align: center'>\n";
            print "<tr>\n<th>ID</th>\n";
            print "<th>Username</th>\n";
            print "<th>Όνομα</th>\n";
            print "<th>Επίθετο</th>\n";
            print "<th>Δικαιώματα</th>\n";
            print "<th>Email</th>\n";
            print "<th>Επιβεβαίωση Αίτησης</th>\n";
            print "<th>Ακύρωση Αίτησης</th></tr>\n";
            while (mysqli_stmt_fetch($stmt)) {
                print "<tr>\n<td>$id</td>\n";
                print "<td>$username</td>\n";
                print "<td>$firstname</td>\n";
                print "<td>$lastname</td>\n";
                print "<td>";
                if ($level == 0) print "Πλήρη";
                else print "Περιορισμένα";
                print "</td>\n";
                print "<td>$email</td>\n";
                print "<td><a href='approve_user.php?userid=$id&approved=1'><img src='icons/green_tick.png' height='20px'></a></td>\n";
                print "<td><a href='approve_user.php?userid=$id&approved=0'><img src='icons/del.png' height='20px'></a></td></tr>\n";
            }
        }    

        print "</table>\n";

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
            print "<table style='text-align: center'>\n";
            print "<tr>\n<th>Θεματική Ενότητα</th>\n<th>Επεξεργασία</th>\n<th>Διαγραφή</th>\n</tr>\n";
            while (mysqli_stmt_fetch($stmt)) {
                print "<tr>\n<td>$unit</td>\n";
                print '<td><form action="change_unit.php" method="get" class="form--inline">'; print "\n";
                print '<p><input type="text" name="unitname">'; print "\n";
                print '<input type="hidden" name="unitid" value=';
                print "$id>";
                print '<input type="submit" value="Υποβολή Νέου Ονόματος" class="button--pill"></p>'; print "\n";
                print '</form>';
                print "<td><a href='delete_unit.php?unitid=$id'><img src='icons/del.png' height='20px'></a></td></tr>\n";
            }
            print "</table>\n";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);

        print "<h2>Επεξεργασία δικαιωμάτων χρηστών</h2>\n";
        require('mysqli_connect.php');
        $q = "SELECT id, username, firstname, lastname, level, email FROM user WHERE approved=1";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $id, $username, $firstname, $lastname, $level, $email);
        if (mysqli_stmt_num_rows($stmt) == 0) {
            print "<p>Δεν βρέθηκαν χρήστες</p>\n";
        } else {
            print "<table style='text-align: center'>\n";
            print "<tr>\n<th>ID</th>\n";
            print "<th>Username</th>\n";
            print "<th>Όνομα</th>\n";
            print "<th>Επίθετο</th>\n";
            print "<th>Δικαιώματα</th>\n";
            print "<th>Email</th>\n";
            print "<th>Αλλαγή δικαιωμάτων</th></tr>\n";
            while (mysqli_stmt_fetch($stmt)) {
                print "<tr>\n<td>$id</td>\n";
                print "<td>$username</td>\n";
                print "<td>$firstname</td>\n";
                print "<td>$lastname</td>\n";
                print "<td>";
                if ($level == 0) print "Πλήρη";
                else print "Περιορισμένα";
                print "</td>\n";
                print "<td>$email</td>\n";
                print "<td><a href='change_user_level.php?userid=$id&level=$level'><img src='icons/upd.png' height='20px'></a></td>\n";
            }
            print "</table>\n";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);

        print "<h2>Στατιστικά<h2>\n";
        print "<h3>Μέσος όρος μυνημάτων ανά συζήτηση</h3>\n";
        require('mysqli_connect.php');
        $q = "select avg(cnt) from (select t.id, t.header, count(*) as cnt from thread t inner join message m on t.id=m.threadid group by t.id) as middle_table";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        print "<table style='text-align: center'>\n";
        print "<tr><th>Μέσος όρος</th></tr>\n";
        print "<tr><td>$count</td></tr>\n";
        print "</table>";
        

        print "<h3>Eνότητες με τις περισσότερες αναρτήσεις</h3>\n";
        require('mysqli_connect.php');
        $q = "select u.id, u.name, count(*) from unit u inner join thread t on u.id=t.unitid inner join message m on t.id=m.threadid group by u.id order by count(*) desc";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $unitid, $unitname, $count);
        print "<table style='text-align: center'>\n";
            print "<tr>\n<th>ID</th>\n";
            print "<th>Ενότητα</th>\n";
            print "<th>Αριθμός Αναρτήσεων</th></tr>\n";
        for ($i=1; $i<4; $i++){
            mysqli_stmt_fetch($stmt);
            print "<tr>\n<td>$unitid</td>\n";
            print "<td>$unitname</td>\n";
            print "<td>$count</td></tr>\n";
        }
        print "</table>";

        print "<h3>Xρήστες με τις περισσότερες αναρτήσεις</h3>\n";
        require('mysqli_connect.php');
        $q = "select us.id, us.username, count(*) from unit u inner join thread t on u.id=t.unitid inner join message m on t.id=m.threadid inner join user us on m.userid=us.id group by m.userid order by count(*) desc";
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $userid, $username, $count);
        print "<table style='text-align: center'>\n";
            print "<tr>\n<th>User ID</th>\n";
            print "<th>Username</th>\n";
            print "<th>Αριθμός Αναρτήσεων</th></tr>\n";
        for ($i=1; $i<4; $i++){
            mysqli_stmt_fetch($stmt);
            print "<tr>\n<td>$userid</td>\n";
            print "<td>$username</td>\n";
            print "<td>$count</td></tr>\n";
        }
        print "</table>";
    }



    if (!isadmin()){
        $username = $_SESSION['username'];
        print "<p>Γεια $username!!</p>\n<p>Χαιρόμαστε που σε έχουμε δίπλα μας!!</p>\n";
        print "<h2>Ενεργητικότητα Χρήστη</h2>\n";
        $userid = $_SESSION['userid'];

        require('mysqli_connect.php');
        $q1 = "SELECT COUNT(*) FROM thread WHERE userid=$userid;";
        $stmt1 = mysqli_prepare($dbc, $q1);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_store_result($stmt1);
        mysqli_stmt_bind_result($stmt1, $threads);
        $q2 = "SELECT COUNT(*) FROM message WHERE userid=$userid";
        $stmt2 = mysqli_prepare($dbc, $q2);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_store_result($stmt2);
        mysqli_stmt_bind_result($stmt2, $messages);

        if (mysqli_stmt_num_rows($stmt1) == 0 || mysqli_stmt_num_rows($stmt2) == 0) {
            print "<p>Δεν έχετε υποβάλλει ακόμη κάποιο μύνημα στο site μας</p>\n";
        } else {
            print "<table style='text-align: center'>\n";
            print "<tr>\n<th>Αριθμός συζυτήσεων που δημιουργήσατε</th>\n";
            print "<th>Αριθμός μυνημάτων που έχετε υποβάλλει</th></tr>\n";
            mysqli_stmt_fetch($stmt1);
            mysqli_stmt_fetch($stmt2);
            print "<tr>\n<td>$threads</td>\n";
            print "<td>$messages</td></tr>\n";
            print "</table>\n";
        }
        mysqli_stmt_close($stmt1);
        mysqli_stmt_close($stmt2);
        mysqli_close($dbc);


    }
?>
</div>

<?php require_once('templates/footer.inc.php'); ?>