<?php
define('TITLE', 'Σελίδα Εισόδου');
define('USERNAME', 0);
define('PASSWORD', 1);
define('VALIDATION', 2);
define('APPROVED', 3);
require_once('templates/header.inc.php');

if (isloggedin()){
    ob_end_clean();
    header("Location: profile.php");
    exit;
}
/*  
    1.  First we check if the form was submitted.
        1.1 If not, then the form is printed with no error messages.
    2.  If yes, we must check if both username and password were submitted.
        2.1 If not, the script will skip all if statements and go to the form's printing, with the appropriate error messages
    3.  If yes, a connection to the db is established, and will query using the username given
        3.1 If nothing is returned, then username is wrong, db connection is closed and the script will go to the form's printing
            with the appropriate error messages
    4.  If username exists and a record is returned from the db, password is then verified.
        4.1 If password is wrong, db connection is closed and the script will go to the form's printing with the appropriate error messages
    5.  If password is correct, session-cookie's variables are instantiated, db connection is closed, php buffer is cleaned, 
        http header is updated with a redirection to profile.php, and this script is exited.
*/  

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors = [];
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    if (empty($username)){
        $errors[USERNAME] = "Πρέπει να εισάγετε username";
    }
    if (empty($password)){
        $errors[PASSWORD] = "Πρέπει να εισάγετε password";
    }
    if (empty($errors)){
        require_once('mysqli_connect.php'); //Connection with db. This is supposed to be outside of the web application's folder
        $q = "SELECT id, username, password, firstname, lastname, level, email, approved FROM user WHERE username=?"; // Preformating a query
        $stmt = mysqli_prepare($dbc, $q); // Creating a prepared statement using the db connection and the preformated query
        mysqli_stmt_bind_param($stmt, 's', $username); // Filling the missing parameters of the prepared statement
        mysqli_stmt_execute($stmt); // Quering the db
        mysqli_stmt_store_result($stmt); // Storing the results in the $stmt
        if (mysqli_stmt_num_rows($stmt) == 1) { // Check if exactly one row(record) is stored
            mysqli_stmt_bind_result($stmt, $userid, $username, $password_hashed, $firstname, $lastname, $level, $email, $approved); // Matches the values of the record's columns in each variable
            mysqli_stmt_fetch($stmt); // Passes the values of the first record (we only have one record in this example) to the matched variables 
            if ($approved == 1){
                if (password_verify($password, $password_hashed)) {
                    $_SESSION['userid'] = $userid;
                    $_SESSION['username'] = $username;
                    $_SESSION['firstname'] = $firstname;
                    $_SESSION['lastname'] = $lastname;
                    $_SESSION['level'] = $level;
                    $_SESSION['email'] = $email;
                    $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);
                    $_SESSION['time'] = time();
                    mysqli_stmt_close($stmt);
                    mysqli_close($dbc);
                } else{
                    $errors[VALIDATION] = "Έχετε εισάγει λάθος username ή password";
                }
            } else {
                $errors[APPROVED] = "Λυπούμαστε...Ο λογαριασμός σας δεν έχει εγκριθεί ακόμη";
            }
            if (empty($errors)){
                ob_end_clean();
                header("Location: profile.php");
                exit();
            }
        } else {
            $errors[VALIDATION] = "Έχετε εισάγει λάθος username ή password";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
    }
}
print '<h2>Είσοδος Διαπιστευτηρίων</h2>'; print "\n";
print '<p>Παρακαλώ εισάγεται username και password. Μόνο εξουσιοδοτημένοι χρήστες μπορούν να κάνουν login</p>'; print "\n";
print '<form action="" method="post" class="form--inline">'; print "\n";
print '<p><label for="username">Username:</label><input type="text" name="username"></p>'; print "\n";
if (!empty($errors[USERNAME])){
    print "<p class='text--error'>";
    print $errors[USERNAME];
    print "</p>\n";
}
print '<p><label for="password">Password:</label><input type="password" name="password" size="20"></p>'; print "\n";
if (!empty($errors[PASSWORD])){
    print "<p class='text--error'>";
    print $errors[PASSWORD];
    print "</p>\n";
}
if (!empty($errors[VALIDATION])){
    print "<p class='text--error'>";
    print $errors[VALIDATION];
    print "</p>\n";
}
if (!empty($errors[APPROVED])){
    print "<p class='text--error'>";
    print $errors[APPROVED];
    print "</p>\n";
}
print '<p><input type="submit" name="submit" value="Log in" class="button--pill"></p>'; print "\n";
print '</form>';
require_once('templates/footer.inc.php');
?>
