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

ListaProduktow($conn);

session_start();

// czy koszyk istnieje w sesji
if (!isset($_SESSION['koszyk'])) {
    $_SESSION['koszyk'] = array();
}

?>
<!-- formularz do dodawania produktów do koszyka -->
<form method="post" action="">
    Produkt: <select name="id_produktu">
        <?php echo PobierzProdukty($conn); ?> </select><br>
    Ilość: <input type="number" name="ilosc"><br>
    <input type="submit" name="dodajproduktdokoszyka" value="Dodaj do koszyka">
</form>
<?php

// dodawanie produktu do koszyka
if (isset($_POST['dodajproduktdokoszyka'])) {
    $id_produktu = $_POST['id_produktu'];
    $ilosc = $_POST['ilosc'];

    // czy istnieje w koszyku (utworzyć albo zwiększyć ilosć)
    $produkt_dodany = false;
	// dla każdego elementu w koszyku
    foreach ($_SESSION['koszyk'] as $key => $produkt) {
		// jeśli istnieje, to zwiększ liczbę
        if ($produkt['id_produktu'] == $id_produktu) {
            $_SESSION['koszyk'][$key]['ilosc'] += $ilosc;
            $produkt_dodany = true;
            break;
        }
    }

    // jeśli produkt nie jest w koszyku
    if ($produkt_dodany == false) {
		// utwórz go w koszyku
        $nowy_produkt = array(
            'id_produktu' => $id_produktu,
			'tytul' => $tytul,
            'ilosc' => $ilosc,
        );
        $_SESSION['koszyk'][] = $nowy_produkt;
    }
}

// usuwanie produktu z koszyka
if (isset($_POST['usunproduktzkoszyka'])) {
    $index = $_POST['index'];
    unset($_SESSION['koszyk'][$index]);
}

// edycja ilości produktu w koszyku
if (isset($_POST['edytujiloscproduktuwkoszyku'])) {
    $index = $_POST['index'];
    $nowa_ilosc = $_POST['nowa_ilosc'];
    $_SESSION['koszyk'][$index]['ilosc'] = $nowa_ilosc;
}

// Zliczanie wartości produktów w koszyku
$wartosc_koszyka = 0;
foreach ($_SESSION['koszyk'] as $produkt) {
    // Tutaj pobieraj dane produktu z bazy danych na podstawie $produkt['id_produktu']
    // i obliczaj wartość na podstawie ceny netto i podatku VAT
    // Poniżej przykładowe obliczenie wartości
    $cena_netto = 50; // Przykładowa cena netto
    $podatek_vat = 0.23; // Przykładowy podatek VAT
	$wartosc_koszyka += intval($produkt['ilosc']) * (floatval($cena_netto) + floatval($cena_netto) * floatval($podatek_vat));
}



function WyświetlenieKoszyka($conn)
{
	$razemcena=0;
	// wyświetlanie koszyka
	echo "Produkty w koszyku:<br> <br>";
	foreach ($_SESSION['koszyk'] as $key => $produkt) {
		
		$sqlid = "SELECT * FROM product_list WHERE id = '{$produkt['id_produktu']}' LIMIT 1";
		$resultid = mysqli_query($conn, $sqlid);
		$rowid = mysqli_fetch_assoc($resultid);
		$cena = number_format((doubleval($rowid['cena_netto']) + doubleval($rowid['cena_netto']) 
		* doubleval($rowid['podatek_vat'])) * intval($produkt['ilosc']), 2, '.', '');
		
		echo "ID: " . $produkt['id_produktu'] . ' | Nazwa: ' . $rowid['tytul'] . ' | Ilość: ' . $produkt['ilosc'] 
		. ' | Łączna Cena: ' . $cena;
		
		$razemcena+=$cena;
		

		// opcje produktu z koszyka
		echo '<form method="post" action="">
		<input type="hidden" name="index" value="' . $key . '">
		<input type="submit" name="usunproduktzkoszyka" value="Usuń">
		<input type="number" name="nowa_ilosc" value="' . $produkt['ilosc'] . '">
		<input type="submit" name="edytujiloscproduktuwkoszyku" value="Edytuj ilość">
		</form>';

		echo "<br>";
	}

	echo "Wartość koszyka: " . $razemcena;
}


















	?> <br><br> <?php
	
	 echo WyświetlenieKoszyka($conn); 


	?> <br><br> <?php
	
	$nr_indeksu = '164435';
    $nrGrupy = '4ISI';
    echo 'Autor: Jacek Szymański' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br><br><br><br>';

?>
