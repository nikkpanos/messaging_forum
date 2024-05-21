<?php
define('TITLE', 'Συζητήσεις');
require_once('templates/header.inc.php');
require_once('includes/functions.inc.php');
$unitid = filter_input(INPUT_GET, 'unitid');

getthreads($unitid);

require_once('templates/footer.inc.php');
?>