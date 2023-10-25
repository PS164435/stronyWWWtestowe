<?php
	$nr_indeksu = '164435';
	$nrGrupy = '4ISI';
	
	echo 'Jacek Szymański '.$nr_indeksu.' grupa '.$nrGrupy.' <br/><br/>';
	
	echo 'Zastosowanie metody include() <br />';
	include('państwamiasta_w.php');
	echo "Państwa miasta litera $litera: $państwo $miasto $rzeka $imię $kolor $roślina $zwierzę $czynność <br>";
	
	echo '<br />Zastosowanie require_once() <br />';
	require_once('państwamiasta_l.php');
	echo "Państwa miasta litera $litera: $państwo $miasto $rzeka $imię $kolor $roślina $zwierzę $czynność <br>";

	echo '<br />Zastosowanie if, else, elseif <br />';
	$a = 15;
	$b = 34;
	$c = 101;
	$d = 34;
	function porownaj(int $a,int $b) {
		if ($a > $b)
			echo ''.$a.' jest większe od '.$b.' <br />';
		elseif ($a < $b)
			echo ''.$a.' jest mniejsze od '.$b.' <br />';
		else 
			echo ''.$a.' jest równe '.$b.' <br />';
	}
	porownaj($b,$c);
	porownaj($b,$a);
	porownaj($b,$d);
	
	echo '<br />Zastosowanie swich <br />';
	$ii = ['marco', 'habemus', 'mori'];
	foreach ($ii as $i){
	switch ($i) {
		case 'marco':
			echo ''.$i.' polo <br />';
			break;
		case 'habemus':
			echo ''.$i.' papam <br />';
			break;
		case 'mori':
			echo 'memento '.$i.' <br />';
			break;
	}
	}
	
	echo '<br />Zastosowanie while()<br />';
	$wh = 14;
	while ($wh > 0){
		echo ''.$wh.' ';
		$wh--;
	}
	echo '<br>';
	
	echo '<br />Zastosowanie for()<br />';
	for ($i=1; $i<=14; $i++){
		echo $i.' ';
	}


?>
