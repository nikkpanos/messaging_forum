<?php
function isloggedin(){
    if (isset($_SESSION['username'])) return true;
    else return false;
}

function isadmin(){
    if (isset($_SESSION['username']) && $_SESSION['level'] == 0){
        return true;
    }  
    else return false;
}


?>