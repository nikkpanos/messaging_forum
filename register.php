<?php
define('TITLE', 'Σελίδα Εγγραφής');
define('USERNAME', 0);
define('EMAIL', 1);
define('PASSWORD1', 2);
define('PASSWORD2', 3);
define('MATCHED_PASSWORDS', 4);
define('USERNAME_EXISTS', 5);
define('EMAIL_EXISTS', 6);
define('FIRSTNAME', 7);
define('LASTNAME', 8);
require_once('templates/header.inc.php');

/*
    1.Checking for post
    2.Checking if any of the form's inputs were empty
    3.Checking if passwords match
    4.Checking if username/email already exists in the db
*/
print "<h2>Δημιουργία νέου λογαριασμού</h2>\n";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $firstname = filter_input(INPUT_POST, 'firstname');
    $lastname = filter_input(INPUT_POST, 'lastname');
    $username = filter_input(INPUT_POST, 'username');
    $email = filter_input(INPUT_POST, 'email');
    $password1 = filter_input(INPUT_POST, 'password1');
    $password2 = filter_input(INPUT_POST, 'password2');
    $errors = [];
    if (empty($firstname)) $errors[FIRSTNAME]='<p class="text--error">Πρέπει να εισάγετε ένα όνομα</p>';
    if (empty($lastname)) $errors[LASTNAME]='<p class="text--error">Πρέπει να εισάγετε ένα επίθετο</p>';
    if (empty($username)) $errors[USERNAME]='<p class="text--error">Πρέπει να εισάγετε ένα username</p>';
    if (empty($email)) $errors[EMAIL]='<p class="text--error">Πρέπει να εισάγετε ένα email</p>';
    if (empty($password1)) $errors[PASSWORD1]='<p class="text--error">Πρέπει να εισάγετε ένα password</p>';
    if (empty($password2)) $errors[PASSWORD2]='<p class="text--error">Πρέπει να εισάγετε ξανά το password</p>';
    if (!empty($password1) && !empty($password2) && $password1!=$password2)
        $errors[MATCHED_PASSWORDS]='<p class="text--error">Τα password που εισάγετε πρέπει να είναι ίδια</p>';

    if (empty($errors)){
        require_once('mysqli_connect.php'); // This is supposed to be located outside of the web application's folder
        $query1 = "select username from user where username=?";
        $query2 = "select email from user where email=?";
        $stmt1 = mysqli_prepare($dbc, $query1);
        $stmt2 = mysqli_prepare($dbc, $query2);
        mysqli_stmt_bind_param($stmt1, 's', $username);
        mysqli_stmt_bind_param($stmt2, 's', $email);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_store_result($stmt1);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_store_result($stmt2);
        if (mysqli_stmt_num_rows($stmt1)>=1)
            $errors[USERNAME_EXISTS]='<p class="text--error">Αυτό το username υπάρχει ήδη. Παρακαλώ επιλέξτε διαφορερικό</p>';
        if (mysqli_stmt_num_rows($stmt2)>=1)
            $errors[EMAIL_EXISTS]='<p class="text--error">Αυτό το email υπάρχει ήδη. Παρακαλώ επιλέξτε διαφορερικό</p>';
        mysqli_stmt_close($stmt1);
        mysqli_stmt_close($stmt2);
        if (!empty($errors)) mysqli_close($dbc);
    }
       
    if (empty($errors)){
        $password = password_hash($password1, PASSWORD_DEFAULT);
        $level = 1; // 1 is users, 0 is for admin
        $query = "insert into user(username, password, firstname, lastname, level, email) values(?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 'ssssis', $username, $password, $firstname, $lastname, $level, $email);
        mysqli_stmt_execute($stmt);
        print '<p class="text--success">Η αίτησή σας καταχωρήθηκε επιτυχώς!</p>'; print "\n";
        print '<p>Ένας διαχειριστής θα εγκρίνει το αίτημά σας το συντομότερο δυνατόν!</p>'; print "\n";
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
        ob_flush();
        exit();
    }
}
?>

<form action="" method="post" class="form--inline">
    <p><label for="firstname">Εισαγωγή ονόματος: </label>
    <input type="text" name="firstname" value="<?php if (!empty($firstname)) print htmlentities($firstname); ?>"></p>
    <?php if (!empty($errors[FIRSTNAME])) print $errors[FIRSTNAME]; ?>

    <p><label for="lastname">Εισαγωγή επίθετου: </label>
    <input type="text" name="lastname" value="<?php if (!empty($lastname)) print htmlentities($lastname); ?>"></p>
    <?php if (!empty($errors[LASTNAME])) print $errors[LASTNAME]; ?>

    <p><label for="username">Δημιουργία username: </label>
    <input type="text" name="username" value="<?php if (!empty($username)) print htmlentities($username); ?>"></p>
    <?php if (!empty($errors[USERNAME])) print $errors[USERNAME]; ?>
    <?php if (!empty($errors[USERNAME_EXISTS])) print $errors[USERNAME_EXISTS]; ?>

    <p><label for="email">Εισαγωγή email: </label>
    <input type="email" name="email" value="<?php if (!empty($email)) print htmlentities($email); ?>"></p>
    <?php if (!empty($errors[EMAIL])) print $errors[EMAIL]; ?>
    <?php if (!empty($errors[EMAIL_EXISTS])) print $errors[EMAIL_EXISTS]; ?>

    <p><label for="password1">Δημιουργία password: </label>
    <input type="password" name="password1"></p>
    <?php if (!empty($errors[PASSWORD1])) print $errors[PASSWORD1]; ?>
    <p><label for="password2">Επιβεβαίωση password: </label>
    <input type="password" name="password2"></p>
    <?php if (!empty($errors[PASSWORD2])) print $errors[PASSWORD2]; ?>
    <?php if (!empty($errors[MATCHED_PASSWORDS])) print $errors[MATCHED_PASSWORDS]; ?>

    <p><input type="submit" name="submit" value="Εγγραφή" class="button--pill"></p>	
</form>

<?php require_once('templates/footer.inc.php'); ?>