<?php
    session_start();

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';
	
	$login = 'paty';
    $pass = 'czak';

    $link = mysqli_connect($dbhost, $dbuser, $dbpass);
    $GLOBALS['link'] = $link;
    if (!$link) echo '<b>przerwane połączenie</b>';
    if (!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';
?>