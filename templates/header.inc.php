<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require_once('includes/functions.inc.php');
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/concise.min.css">
        <link rel="stylesheet" href="css/masthead.css">
        <title><?php print TITLE; ?></title>
    </head>
    <body>
        <header container class="siteHeader">
            <div row>
                <h1 column=4 class="logo" style="font-size:50px"><a href="index.php">Messaging Forum</a></h1>
                <nav column="8" class="nav">
                    <ul>
                        <li><a href="home.php">Αρχική Σελίδα</a></li>
                        <li><a href="units.php">Θεματικές Ενότητες</a></li>
                        <?php
                            if (isloggedin()){
                                print '<li><a href="profile.php">Προφίλ</a></li>'; print("\n");
                                print '<li><a href="logout.php">Αποσύνδεση</a></li>'; print("\n");
                            } else {                             
                                print '<li><a href="login.php">Είσοδος</a></li>'; print("\n");
                                print '<li><a href="register.php">Εγγραφή</a></li>'; print("\n");
                            }
                        ?>    
                    </ul>
                </nav>
            </div>
        </header>
        <main container class="siteContent">
            <!----- BEGIN CHANGABLE CONTENT HERE ----->