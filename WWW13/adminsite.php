<!DOCTYPE html>
<?php
include("index.php");
include('cfg.php');

?> 
<body class="<?php echo $backgroundClass; ?>"  onload="startclock()"> <!-- wywołanie tła i zegar -->
	<script src="./js/kolorujtlo.js" type="text/javascript"></script>
	<script src="./js/timedate.js" type="text/javascript"></script>
	<script src="./js/jquery-3.7.1.js"></script>
<style> <?php
	include 'css/stylowa.css';
?> </style> <?php


	if (isset($_SESSION["adminlogin"])) {
		
		ListaPodstron();
		DrzewoKategorii($conn);
		ListaProduktow();
		
		
	}

	?><br><br><?php
	
	$nr_indeksu = '164435';
    $nrGrupy = '4ISI';
    echo 'Autor: Jacek Szymański' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br><br><br><br>';

?>
