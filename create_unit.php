<?php
define('TITLE', 'Δημιουργία Ενότητας');
define('NAME', 0);
define('NAME_EXISTS', 1);
require_once('templates/header.inc.php');

if (!isloggedin() || !isadmin()){
    ob_end_clean();
    print "<h1>Σφάλμα 401</h1>\n";
    print "<p>Μη εξουσιοδοτημένο αίτημα πρόσβασης</p>\n";
    exit();
}

/*
    1.Checking for post
    2.Checking if any of the form's inputs were empty
    3.Checking if passwords match
    4.Checking if username/email already exists in the db
*/
print "<h2>Δημιουργία νέας ενότητας</h2>\n";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = filter_input(INPUT_POST, 'name');
    $errors = [];
    if (empty($name)) $errors[NAME]='<p class="text--error">Πρέπει να εισάγετε όνομα</p>';

    if (empty($errors)){
        require_once('mysqli_connect.php'); // This is supposed to be located outside of the web application's folder
        $query = "select name from unit where name=?";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt)!=0)
            $errors[NAME_EXISTS]='<p class="text--error">Αυτό το όνομα υπάρχει ήδη. Παρακαλώ επιλέξτε διαφορερικό</p>';
        mysqli_stmt_close($stmt);
        if (!empty($errors)) mysqli_close($dbc);
    }
       
    if (empty($errors)){
        $query = "insert into unit(name) values(?)";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        print '<p class="text--success">Η ενότητα δημιουργήθηκε επιτυχώς!</p>'; print "\n";
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
        ob_flush();
        exit();
    }
}
?>

<form action="" method="post" class="form--inline">
    <p><label for="name">Εισαγωγή ονόματος: </label>
    <input type="text" name="name" value="<?php if (!empty($name)) print htmlentities($name); ?>"></p>
    <?php
    if (!empty($errors[NAME])) print $errors[NAME];
    if (!empty($errors[NAME_EXISTS])) print $errors[NAME_EXISTS];
    ?>


    <p><input type="submit" name="submit" value="Δημιουργία" class="button--pill"></p>	
</form>

<?php require_once('templates/footer.inc.php'); ?>