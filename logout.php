<?php
define('TITLE', 'Αποσύνδεση');
require_once('templates/header.inc.php');
if (!isloggedin()){
    ob_end_clean();
    header("Location: login_error.php");
    exit();
}
$username = $_SESSION['username'];
$start = $_SESSION['time'];
$_SESSION = [];
session_destroy();
?>
<h2>Αποσύνδεση</h2>
<p>Αποσυνδεθήκατε ως χρήστης <?php print $username; ?>. Ήσαστε συνδεδεμένος
<?php $dur = time() - $start; print $dur; ?> sec.</p>
<p>Για να συνδεθείτε ξανά πατήστε <a href="login.php">εδώ</a>.</p>
<?php
require('templates/footer.inc.php');
?>