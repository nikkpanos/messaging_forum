<?php
function isloggedin(){
    if (isset($_SESSION['username']) && isset($_SESSION['time']) && isset($_SESSION['agent'])
                                     && $_SESSION['agent'] == sha1($_SERVER['HTTP_USER_AGENT'])) return true;
    else return false;
}

function isadmin(){
    if (isset($_SESSION['username']) && $_SESSION['username'] == admin) return true;
    else return false;
}

function getunits(){
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
        print "<tr>\n<th>Θεματική Ενότητα</th>\n<th>Ενέργεια</th>\n</tr>\n";
        while (mysqli_stmt_fetch($stmt)) {
            print "<tr>\n<td>$unit</td>\n<td><a href='showthreads.php?unitid=$id'>Μετάβαση</a></td>\n</tr>\n";
        }
        print "</table>\n";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
}

function getthreads($unitid){
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
        print "<table>\n";
        print "Συζυτήσεις\n";
        print "<tr>\n<th>Επικεφαλίδα</th>\n<th>Χρήστης που τη ξεκίνησε</th>\n<th>Ενέργεια</th>\n</tr>\n";
        while (mysqli_stmt_fetch($stmt)) {
            print "<tr>\n<td>$header</td>\n<td>$username</td>\n<td><a href='thread.php?threadid=$threadid'>Άνοιγμα</a></td>\n</tr>\n";
        }
        print "</table>\n";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
}

?>