<?php  
define('TITLE', 'Σφάλμα Εξουσιοδότησης');
require_once('templates/header.inc.php');

print "<h1>Σφάλμα 401</h1>\n";
print "<p>Μη εξουσιοδοτημένο αίτημα πρόσβασης</p>\n";
print "<p>Παρακαλώ για να συνεχίσετε θα πρέπει πρώτα να <a href='login.php'>συνδεθείτε</a>.\n";

require('templates/footer.inc.php');
?>
