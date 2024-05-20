<?php
function isloggedin(){
    if (isset($_SESSION['username']) && isset($_SESSION['time']) && isset($_SESSION['agent'])
                                     && $_SESSION['agent'] == sha1($_SERVER['HTTP_USER_AGENT'])) return true;
    else return false;
}

?>