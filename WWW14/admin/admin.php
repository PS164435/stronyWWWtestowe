<!DOCTYPE html>
<?php

// nawiązanie połączenia
$conn = mysqli_connect('localhost', 'root', '', 'moja_strona');
// co jeśli nie da się połączyć
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// obsługa formularza po edycji strony / zapisanie danych po edycji
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zapiszstrone'])) {
	// pobranie danych z formularza
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
	$tresc = mysqli_real_escape_string($conn, $_POST['tresc']);
	$aktywna = isset($_POST['aktywna']) ? 1 : 0;
	// zapytanie UPDATE
	$sql = "UPDATE page_list SET page_title = '$tytul', page_content = '$tresc', status = '$aktywna' WHERE id = '$id' LIMIT 1";
	$result = mysqli_query($conn, $sql);
	// powrót do strony
	if ($result){
		header("Location: adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}

// obsługa formularza po utworzeniu strony / zapisanie danych nowej strony
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dodajstrone'])) {
	// pobranie danych z formularza
    $tytul = $_POST['tytul'];
    $tresc = $_POST['tresc'];
    $aktywna = isset($_POST['aktywna']) ? 1 : 0;
    // zabezpieczenie
    $tytul = mysqli_real_escape_string($conn, $tytul);
    $tresc = mysqli_real_escape_string($conn, $tresc);
    // zapytanie INSERT
    $sql = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', '$aktywna') LIMIT 1";
    $result = mysqli_query($conn, $sql);
	// powrót do strony
	if ($result){
		header("Location: adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}

// żeby działało edytowanie strony
if (isset($_GET['action']) && $_GET['action'] == 'EdytujStrone' && isset($_GET['id'])) {
	EdytujPodstrone($conn, $_GET['id']);
}

// żeby działało usuwanie strony
if (isset($_GET['action']) && $_GET['action'] == 'UsunStrone' && isset($_GET['id'])) {
    UsunStrone($conn, $_GET['id']);
}

// żeby działało dodawanie strony
if (isset($_GET['action']) && $_GET['action'] == 'DodajStrone') {
	$row = array('id' => '', 'page_title' => '', 'page_content' => '', 'status' => '');
	
	FormularzStrony($row, $czynnosc="dodajstrone");
}

// żeby działało usuwanie kategorii
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuniecie_kategorii'])) {
	UsunKategorie($conn, $_POST["kategorie"]);
}

// żeby działało edytowanie kategorii
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edycja_kategorii'])) {
	EdytujKategorie($conn, $_POST["kategorie"]);
}

// żeby działało dodawanie kategorii
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dodanie_kategorii'])) {
	DodajKategorie($conn, $_POST["kategorie"]);
}

// obsługa formularza po edycji kategorii / zapisanie danych po edycji
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zapiszkategorie'])) {
	// pobranie danych z formularza
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$matka = mysqli_real_escape_string($conn, $_POST['matka']);
	$nazwa = mysqli_real_escape_string($conn, $_POST['nazwa']);
	// zapytanie UPDATE
	$sql = "UPDATE kategory_list SET id = '$id', matka = '$matka', nazwa = '$nazwa' WHERE id = '$id' LIMIT 1";
	$result = mysqli_query($conn, $sql);
	// powrót do strony
	if ($result){
		header("Location: ../adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}

// obsługa formularza po utworzeniu kategorii / zapisanie danych nowej strony
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dodajkategorie'])) {
	// pobranie danych z formularza
    $matka = $_POST['matka'];
    $nazwa = $_POST['nazwa'];
    // zabezpieczenie
    $matka = mysqli_real_escape_string($conn, $matka);
    $nazwa = mysqli_real_escape_string($conn, $nazwa);
    // zapytanie INSERT
    $sql = "INSERT INTO kategory_list ( matka, nazwa) VALUES ( '$matka', '$nazwa')";
    $result = mysqli_query($conn, $sql);
	// powrót do strony
	if ($result){
		header("Location: ../adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}

// funkcja odpowiadająca za formularz logowania
function FormularzLogowania()
{
	echo '
		<div class="logowanie">
			<div class="logowanie">
				<form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
					<table class="logowanie">
<!-- napis -->			<tr><td class="log4_t">Panel CMS:</td>
<!-- pole email -->		<td class="log4_t">Email</td><td class="log4_t"><input type="text" name="login_email" class="logowanie" /></td>
<!-- pole hasło-->		<td class="log4_t">Haslo</td><td class="log4_t"><input type="password" name="login_pass" class="logowanie" /></td>
<!-- przycisk -->		<td class="log4_t"><input type="submit" name="x1_submit" class="gornefunkcje" value="zaloguj" /></td></tr>
					</table>
				</form>
			</div>
		</div>
	';
}

// funkcja odpowiadająca za listę podstron
function ListaPodstron()
{
	// połączenie z serwerem
    $con = mysqli_connect('localhost', 'root', '', 'moja_strona');
    if(!$con)
    {	// co jeśli nie można się połączyć
        die("Connection failed!" . mysqli_connect_error());
    }
	// pobieranie danych
    $sql = "SELECT * FROM page_list";
    $result = mysqli_query($con, $sql);
	
	// jeśli są elementy do wyświetlenia
	if (mysqli_num_rows($result) > 0) {
		?> <!-- wyświetlanie danych w formie tabelki -->
		<table style="border: 10px solid black; background: white;">
			<tr> <!-- wiersz z nazwami kolumn -->
				<th style="border: 5px solid green;">ID</th>
				<th style="border: 5px solid green;">TYTUŁ</th>
				<th style="border: 5px solid green;">OPCJA</th>
			</tr>
		<?php // wyświetlanie wierszy z danymi elementami
		while ($row = mysqli_fetch_array($result)) {
			?>
			<tr>
				<td style="border: 5px solid lime; font-size: 23px;"><?php echo $row["id"]; ?></td>
				<td style="border: 5px solid lime; font-size: 23px;"><?php echo $row["page_title"]; ?></td>
				<td style="border: 5px solid lime; font-size: 23px;">
					<a href="?action=EdytujStrone&id=<?php echo $row["id"]; ?>">Edytuj</a>
					<a href="?action=UsunStrone&id=<?php echo $row["id"]; ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten rekord?')">Usuń</a>
				</td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td colspan="3" style="border: 5px solid lime; font-size: 23px; text-align: center;">
					<a href="?action=DodajStrone">Dodaj stronę</a>
				</td>
			</tr>
		</table>
		<?php
	} 
	else // jeśli nie ma elementów do wyświetlenie
	{
		echo "0 results";
	}
}


// formualrz
function FormularzStrony($row, $czynnosc){
   ?> <!-- formularz do wypełniania podczas edycji -->
    <form class="popup" method="post" action="adminsite.php">
        <table class="popup">  
			<tr class="popup">
				<td class="popup" colspan='2'>
					<?php
					if ($czynnosc === 'dodajstrone') { echo '<h2 class="popup" >Dodawanie nowej strony</h2>'; }
					if ($czynnosc === 'zapiszstrone') { echo '<h2 class="popup" >Edytowanie strony</h2>'; }
					?>			
				</td>
			</tr> 
			<tr>	 <!-- czarna przedziałka -->
				<td colspan='2' style="border: 1px solid black; background: black"> </td>
				</tr> <tr> </tr> <tr> </tr> <tr> </tr> <tr> </tr> <tr> </tr> 
			<tr class="popup"> 	 <!-- id -->
				<td class="popupl" > <label class="popup" for="id">ID:</label> </td>
				<td class="popupp" >  <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></label> </td>
            </tr> 
			<tr class="popup" class="popup">  <!-- tytuł -->
                <td class="popupl" > <label class="popup" for="tytul">Tytuł: </label> </td>
				<td class="popupp" > <input class="popup" type="text" name="tytul" value="<?php echo $row['page_title']; ?>"> </input> </td>
            </tr>
			<tr class="popup">	<!-- treść -->
                <td class="popupl"> <label class="popup" for="tresc">Treść strony: </label><br> </td>
				<td class="popupp" > <textarea class="popup" name="tresc"><?php echo $row['page_content']; ?></textarea> </td>
            </tr>
			<tr class="popup">	<!-- aktywna -->
				<td class="popupl" > <label class="popup" for="aktywna">Aktywna: </label> </td>
				<td class="popupp" > <input type="checkbox" name="aktywna" <?php echo $row['status'] ? 'checked' : ''; ?>> </td>
			</tr>
			<tr class="popup">	<!-- przyciski -->
                <td class="popupl" > <input class="popup" type="submit" name="<?php echo $czynnosc; ?>" value="Ok"> </td>
                <td class="popupp" > 
					<a href="adminsite.php">
						<input class="popup" type="button" value="Cancel">
					</a>
                </td>
            </tr>
        </table>
    </form>
    <?php
}

// funkcja odpowiadająca za możliwość edycji strony
function EdytujPodstrone($conn, $id)
{
	// pobranie danych strony do edycji
	$sql = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
	
	// zapytanie do bazy danych
	$result = mysqli_query($conn, $sql);
	
	// jeśli istnieją wyniki
	if ($result && mysqli_num_rows($result) > 0) {

		// pobranie pojedyńczego wiersza
		$row = mysqli_fetch_assoc($result);
		
		FormularzStrony($row, $czynnosc="zapiszstrone");
		
	} else { // co gdy nie ma wyników?
		echo "Nie znaleziono podstrony o podanym ID.";
	}
}

// funkcja odpowiadająca za możliwość usunięcia strony
function UsunStrone($conn, $id)
{
    // zabezpieczenie przed SQL Injection
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "DELETE FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
	// powrót do strony
	if ($result){
		header("Location: adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}


// funkcja odpowiadająca za wyświetlanie kategorii sklepowych
function PokazKategorie()
{
	// nawiązanie połączenia
	$conn = mysqli_connect('localhost', 'root', '', 'moja_strona');
	
    // pobieranie kategorii głównych
    $query = "SELECT * FROM kategory_list WHERE matka = 0";
    $result = mysqli_query($conn, $query);

	// dla każdej matkii
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
			// wypisanie kategorii głównej
            ?> <h4 class='kategoria'> <?php echo $row['nazwa'] . ' ID=' . $row['id']; ?> </h4> <?php

            // pobieranie pod-kategorii dla danej kategorii głównej
            $podquery = "SELECT * FROM kategory_list WHERE matka = " . $row['id'];
            $podresult = mysqli_query($conn, $podquery);

			// dla każdego dziecka
            if ($podresult) {
                echo '<ul>';
                while ($podrow = mysqli_fetch_assoc($podresult)) {
					// wypisanie pod-kategorii
                    echo '<li>' . $podrow['nazwa'] . ' ID=' . $podrow['id'] . '</li>';
                }
                echo '</ul>';
            } else { // gdy bląd dzieci
                echo 'Błąd przy pobieraniu kategorii (dzieci).';
            }
        }
    } else { // gdy bląd matek
        echo 'Błąd przy pobieraniu kategorii (matki).';
    }  
}

// wyświetlanie drzewa kategorii i pola modyfikacji
function DrzewoKategorii($conn){
?>
	<div style="width:30%">
	<?php PokazKategorie($conn); // wyświetlenie listy kategorii ?>
				
		<!-- formularz z rozwijanym polem do wyboru kategorii -->
			<form class="kategorie" method="post" action="">
				<label class="kategorie" for="kategorie">Wybierz kategorię:</label>
				<select class="kategorie" name="kategorie" id="kategorie">
					<?php echo PobierzKategorie($conn); ?>
				</select>
				<input class="kategorie" type="submit" name="dodanie_kategorii" value="Dodaj Kategorię">
				<input class="kategorie" type="submit" name="edycja_kategorii" value="Edytuj Kategorię">
				<input class="kategorie" type="submit" name="usuniecie_kategorii" onclick="return confirm('Czy na pewno chcesz usunąć ten rekord?')" value="Usuń Kategorię">
			</form>
		</div>
	<?php
}

// funkcja odpowiadająca za możliwość edycji kategorii
function EdytujKategorie($conn, $id)
{
	// zabezpiecz przed SQL Injection
    $id = mysqli_real_escape_string($conn, $id);

	// pobranie danych strony do edycji
	$sql = "SELECT * FROM kategory_list WHERE id = '$id' LIMIT 1";
	
	// zapytanie do bazy danych
	$result = mysqli_query($conn, $sql);
	
	// jeśli istnieją wyniki
	if ($result && mysqli_num_rows($result) > 0) {

		// pobranie pojedyńczego wiersza
		$row = mysqli_fetch_assoc($result);
		
		FormularzKategorii($row, $czynnosc="zapiszkategorie");

	} else { // co gdy nie ma wyników?
		echo "Nie znaleziono kategorii o podanym ID.";
	}

}

// formularz dodawania / edycji kategorii
function FormularzKategorii($row, $czynnosc){
	?> <!-- formularz do wypełniania podczas edycji -->
	<form class="popup" style="width: 30%;" method="post" action="admin/admin.php" >
        <!-- id_kierowcy -->
        <table class="popup">  
			<tr class="popup">
				<td class="popup" colspan='2'>	<!-- tytuł -->
					<?php
					if ($czynnosc === 'dodajkategorie') { echo '<h2 class="popup" >Dodawanie nowej kategorii</h2>'; }
					if ($czynnosc === 'zapiszkategorie') { echo '<h2 class="popup" >Edytowanie kategorii</h2>'; }
					?>			
				</td>
			</tr> 
			<tr>	<!-- czarna przedziałka -->
				<td colspan='2' style="border: 1px solid black; background: black"> </td>
				</tr> <tr> </tr> <tr> </tr> <tr> </tr> <tr> </tr> <tr> </tr> 
			<tr class="popup"> 	<!-- id -->
				<td class="popupl" > <label class="popup" for="id">ID:</label> </td>
				<td class="popupp" >  <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></label> </td>
            </tr> 
			<tr class="popup" class="popup"> <!-- matka -->
                <td class="popupl"> <label class="popup" for="matka">Matka: </label> </td>
				<td class="popupp"> <input class="popup" style="width: 90%;" name="matka" value="<?php echo $row['matka']; ?>"> </td>
            </tr>
			<tr class="popup">	<!-- nazwa -->
                <td class="popupl"> <label class="popup" for="Nazwa">nazwa: </label> </td>
				<td class="popupp"> <input class="popup" style="width: 90%;" type="text" name="nazwa" value="<?php echo $row['nazwa']; ?>"> </td>
            </tr>
			<tr class="popup"> <!-- przyciski do zatwierdzenia -->
                <td class="popup" > <input class="popup" type="submit" name="<?php echo $czynnosc; ?>" value="Ok"> </td>
                <td class="popup"> 
					<a href="adminsite.php">
						<input class="popup" type="button" value="Cancel"> </input>
					</a>
                </td>
            </tr>
        </table>
    </form>
    <?php
}

// funkcja odpowiadająca za możliwość dodania nowej kategorii
function DodajKategorie($conn){
	
	$row = array('id' => '', 'matka' => '', 'nazwa' => '');
	
	FormularzKategorii($row, $czynnosc="dodajkategorie");
   
}

// funkcja odpowiadająca za możliwość usunięcia kategorii
function UsunKategorie($conn, $id)
{
    // zabezpieczenie przed SQL Injection
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "DELETE FROM kategory_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
	
	// powrót do strony
	if ($result){
		header("Location: adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}

// funkcja odpowiadająca pobieranie kategorii (wybór w polu)
function PobierzKategorie()
{
	
    $options = '';
    $sql = "SELECT * FROM kategory_list";
    $result = mysqli_query(mysqli_connect('localhost', 'root', '', 'moja_strona'), $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= '<option value="' . $row['id'] . '">' . $row['nazwa'] . '</option>';
        }
    }

    return $options;
}


?>

<!-- przejście do strony adminowej -->
<script>
function IntoAdminSite(){
    window.location.href = 'adminsite.php';
}
</script>

<?php

// funkcja odpowiadająca za listę produktów
function ListaProduktow($conn)
{
	// połączenie z serwerem
    $con = mysqli_connect('localhost', 'root', '', 'moja_strona');
    if(!$con)
    {	// co jeśli nie można się połączyć
        die("Connection failed!" . mysqli_connect_error());
    }
	// pobieranie danych
    $sql = "SELECT * FROM product_list";
    $result = mysqli_query($con, $sql);
	
		?> <!-- wyświetlanie danych w formie tabelki -->
		<table class="product">
			<tr> <!-- wiersz z nazwami kolumn -->
				<th class="product">ID</th>
				<th class="product">TYTUŁ</th>
				<th class="product">OPIS</th>
				<th class="product">UTWORZENIE.</th>
				<th class="product">MODYFIKACJA</th>
				<th class="product">WYGAŚNIĘCIE</th>
				<th class="product">CENA NETTO</th>
				<th class="product">VAT</th>
				<th class="product">DOSTĘPNYCH SZTUK</th>
				<th class="product">STATUS SZTUK</th>
				<th class="product">KATEGORIA</th>
				<th class="product">GABARYT</th>
				<th class="product">ZDJĘCIE</th>
				<th class="product">OPCJA</th>
			</tr>
		<?php // wyświetlanie wierszy z danymi elementami
		while ($row = mysqli_fetch_array($result)) {
			?>
			<tr>
				<td class="product"><?php echo $row["id"]; ?></td>
				<td class="product"><?php echo $row["tytul"]; ?></td>
				<td class="product"> 
					<textarea readonly name="opis"><?php echo $row['opis']; ?></textarea> <!-- readonly = nie można pisać -->
				</td>
				<td class="product"><?php echo $row["data_utworzenia"]; ?></td>
				<td class="product"><?php echo $row["data_modyfikacji"]; ?></td>
				<td class="product"><?php echo $row["data_wygasniecia"]; ?></td>
				<td class="product"><?php echo $row["cena_netto"]; ?></td>
				<td class="product"><?php echo $row["podatek_vat"]; ?></td>
				<td class="product"><?php echo $row["ilosc_dostepnych_sztuk_w_magazynie"]; ?></td>
				<td class="product"><?php echo $row["status_dostepnosci"]; ?></td>
				<td class="product"> <?php
					$podquery = "SELECT * FROM kategory_list WHERE id =" . $row['kategoria'];
					$podresult = mysqli_query($conn, $podquery);
					$podrow = mysqli_fetch_assoc($podresult);
					echo $podrow['nazwa']; 
				 ?> </td>
				<td class="product"><?php echo $row["gabaryt_produktu"]; ?></td>
				<td class="product">
					<img class="product" src="data:image/jpeg;base64,<?php echo base64_encode($row["zdjecie"]); ?>" alt="Zdjęcie">
				</td>
				<td class="product">
					<a class="product" href="?action=EdytujProdukt&id=<?php echo $row["id"]; ?>">Edytuj</a>
					<a class="product" href="?action=UsunProdukt&id=<?php echo $row["id"]; ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten rekord?')">Usuń</a>
				</td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td colspan="14" class="product" style="text-align: center;">
					<a class="product" href="?action=DodajProdukt">Dodaj produktę</a>
				</td>
			</tr>
		</table>
		<?php
	
}


// żeby działało dodawanie produktu 
if (isset($_GET['action']) && $_GET['action'] == 'DodajProdukt') {
	
	$row = array('id' => '', 'tytul' => '' , 'opis' => '', 'data_utworzenia' => '', 'data_modyfikacji' => '', 'data_wygasniecia' => '', 
	'cena_netto' => '', 'podatek_vat' => '', 'ilosc_dostepnych_sztuk_w_magazynie' => '', 'status_dostepnosci' => '', 'kategoria' => '', 'gabaryt_produktu' => '', 'zdjecie' => '');
	
	FormularzProduktu($row, $czynnosc="dodajprodukt");
}

// żeby działało edytowanie produktu
if (isset($_GET['action']) && $_GET['action'] == 'EdytujProdukt' && isset($_GET['id'])) {
	EdytujProdukt($conn, $_GET['id']);
}

// funkcja odpowiadająca za możliwość edycji produktu
function EdytujProdukt($conn, $id)
{
	// pobranie danych strony do edycji
	$sql = "SELECT * FROM product_list WHERE id = $id LIMIT 1";
	
	// zapytanie do bazy danych
	$result = mysqli_query($conn, $sql);
	
	// jeśli istnieją wyniki
	if ($result && mysqli_num_rows($result) > 0) {

		// pobranie pojedyńczego wiersza
		$row = mysqli_fetch_assoc($result);
		
		FormularzProduktu($row, $czynnosc="zapiszprodukt");
		
	} else { // co gdy nie ma wyników?
		echo "Nie znaleziono produktu o podanym ID.";
	}
}

// formualrz produktu
function FormularzProduktu($row, $czynnosc){
   ?> <!-- formularz do wypełniania podczas edycji -->
    <form class="popup" method="post" action="adminsite.php" enctype="multipart/form-data">
        <table class="popup">  
			<tr class="popup">
				<td class="popup" colspan='2'>
					<?php
					if ($czynnosc === 'dodajprodukt') { echo '<h2 class="popup" >Dodawanie nowego produktu</h2>'; }
					if ($czynnosc === 'zapiszprodukt') { echo '<h2 class="popup" >Edytowanie produktu </h2>'; }
					?>			
				</td>
			</tr> 
			<tr>	 <!-- czarna przedziałka -->
				<td colspan='2' style="border: 1px solid black; background: black"> </td>
				</tr> <tr> </tr> <tr> </tr> <tr> </tr> <tr> </tr> <tr> </tr> 
			<tr class="popup"> 	 <!-- id -->
				<td class="popupl" > <label class="popup" for="id">ID:</label> </td>
				<td class="popupp" >  <input type="hidden" name="id" value="<?php echo $row['id']; ?>"><?php echo $row['id']; ?></label> </td>
            </tr> 
			<tr class="popup" class="popup">  <!-- tytuł -->
                <td class="popupl" > <label class="popup" for="tytul">Tytuł: </label> </td>
				<td class="popupp" > <input class="popup" type="text" name="tytul" value="<?php echo $row['tytul']; ?>"> </input> </td>
            </tr>
			<tr class="popup" class="popup">  <!-- opis -->
                <td class="popupl" > <label class="popup" for="opis">Opis: </label> </td>
				<td class="popupp" > <textarea class="popup" name="opis"><?php echo $row['opis']; ?></textarea> </td>
			</tr>
			<tr class="popup" class="popup">  <!-- data utworzenia -->
                <td class="popupl" > <label class="popup" for="data_utworzenia">Data utworzenia: </label> </td>
				<td class="popupp" > <input class="popup" type="date" name="data_utworzenia" value="<?php echo $row['data_utworzenia']; ?>"> </input> </td>
            </tr>
			<tr class="popup" class="popup">  <!-- data modyfikacji -->
                <td class="popupl" > <label class="popup" for="data_modyfikacji">Data modyfikacji: </label> </td>
				<td class="popupp" > <input class="popup" type="date" name="data_modyfikacji" value="<?php echo $row['data_modyfikacji']; ?>"> </input> </td>
            </tr>
			<tr class="popup" class="popup">  <!-- data wygasniecia -->
                <td class="popupl" > <label class="popup" for="data_wygasniecia">Data wygaśnięcia: </label> </td>
				<td class="popupp" > <input class="popup" type="date" name="data_wygasniecia" value="<?php echo $row['data_wygasniecia']; ?>"> </input> </td>
            </tr>
			<tr class="popup">	<!-- cena netto -->
                <td class="popupl"> <label class="popup" for="cena_netto">Cena netto: </label><br> </td>
				<td class="popupp"> <input class="popup" type="number" step="0.01" name="cena_netto" value="<?php echo $row['cena_netto']; ?>"></input> </td>
            </tr>
			<tr class="popup">	<!-- podatek vat -->
                <td class="popupl"> <label class="popup" for="podatek_vat">Podatek vat: </label><br> </td>
				<td class="popupp"> <select class="popup" name="podatek_vat" value="<?php echo $row['podatek_vat']; ?>"> 
				<option value="0.23">23%</option> 
				<option value="0.08">8%</option> 
				<option value="0.05">5%</option> 
				<option value="0.00">0%</option> 
				</select> </td>
			</tr>
			<tr class="popup">	<!-- ilosc dostepnych sztuk w magazynie -->
                <td class="popupl"> <label class="popup" for="ilosc_dostepnych_sztuk_w_magazynie">Ilość dostępnych sztuk w magazynie: </label><br> </td>
				<td class="popupp"> <input class="popup" type="number" name="ilosc_dostepnych_sztuk_w_magazynie" value="<?php echo $row['ilosc_dostepnych_sztuk_w_magazynie']; ?>"></input> </td>
            </tr>
			<tr class="popup">	<!-- status dostepnosci -->
                <td class="popupl"> <label class="popup" for="status_dostepnosci">Status: </label><br> </td>
				<td class="popupp"> <input type="checkbox" name="status_dostepnosci" <?php echo $row['status_dostepnosci'] ? 'checked' : ''; ?>> </td>
            </tr>
			<tr class="popup">	<!-- kategoria -->
                <td class="popupl"> <label class="popup" for="kategoria">Kategoria: </label><br> </td>
				<td class="popupp"> <select class="kategorie" name="kategoria" id="kategorie" value="<?php echo $row['kategoria']; ?>"> <?php echo PobierzKategorie($conn); ?> </select></td>
            </tr>	
			<tr class="popup">	<!-- gabaryt produktu -->
                <td class="popupl"> <label class="popup" for="gabaryt_produktu">Gabaryt: </label><br> </td>
				<td class="popupp"> <input class="popup" type="text" name="gabaryt_produktu"value="<?php echo $row['gabaryt_produktu']; ?>"> </input>  </td>
            </tr>
			<tr class="popup">	<!-- obrazek -->
                <td class="popupl"> <label class="popup" for="zdjecie">Zdjecie: </label><br> </td>
				<td class="popupp"> <input class="popup" type="file" name="zdjecie">  </td>
            </tr>
			<tr class="popup">	<!-- przyciski -->
                <td class="popupl" > <input class="popup" type="submit" name="<?php echo $czynnosc; ?>" value="Ok"> </td>
                <td class="popupp" > 
					<a href="adminsite.php">
						<input class="popup" type="button" value="Cancel">
					</a>
                </td>
            </tr>
        </table>
    </form>
    <?php
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zapiszprodukt'])) {
	
	if ($_POST["gabaryt_produktu"] == null){
		echo "brak/null";
	}
	else
	{
		echo "tuturu";
	}
	echo $_POST["gabaryt_produktu"];
	
	// pobranie danych z formularza
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	$tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
	$opis = mysqli_real_escape_string($conn, $_POST['opis']);
	$data_utworzenia = mysqli_real_escape_string($conn, $_POST['data_utworzenia']);
	$data_modyfikacji = mysqli_real_escape_string($conn, $_POST['data_modyfikacji']);
	$data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia']);
	$cena_netto = mysqli_real_escape_string($conn, $_POST['cena_netto']);
	$podatek_vat = mysqli_real_escape_string($conn, $_POST['podatek_vat']);
	$ilosc_dostepnych_sztuk_w_magazynie = mysqli_real_escape_string($conn, $_POST['ilosc_dostepnych_sztuk_w_magazynie']);
	$status_dostepnosci = isset($_POST['status_dostepnosci']) ? 1 : 0;
	$kategoria = mysqli_real_escape_string($conn, $_POST['kategoria']);
	$gabaryt_produktu = mysqli_real_escape_string($conn, $_POST['gabaryt_produktu']);
/*	$testzdjecie = mysqli_real_escape_string($conn, $_POST['zdjecie']);
	if ($testzdjecie !== null) {
	
		$zdjecie = mysqli_real_escape_string($conn, $_POST['zdjecie']);
		
		if ($_FILES['zdjecie']['error'] == UPLOAD_ERR_OK) {
			// odczytanie zawartości pliku
			$zdjecie_tmp = $_FILES['zdjecie']['tmp_name'];
			$zdjecie = file_get_contents($zdjecie_tmp);
		}
	}
	else
	{
		$sqlSelect = "SELECT zdjecie FROM product_list WHERE id = $id LIMIT 1";
		$zdjecie = mysqli_prepare($conn, $sqlSelect);
	}
	*/

    // zabezpieczenie
    $tytul = mysqli_real_escape_string($conn, $tytul);
    $opis = mysqli_real_escape_string($conn, $opis);
    $data_utworzenia = mysqli_real_escape_string($conn, $data_utworzenia);
    $data_modyfikacji = mysqli_real_escape_string($conn, $data_modyfikacji);
    $data_wygasniecia = mysqli_real_escape_string($conn, $data_wygasniecia);
    $cena_netto = mysqli_real_escape_string($conn, $cena_netto);
    $podatek_vat = mysqli_real_escape_string($conn, $podatek_vat);
    $ilosc_dostepnych_sztuk_w_magazynie = mysqli_real_escape_string($conn, $ilosc_dostepnych_sztuk_w_magazynie);
    $status_dostepnosci = mysqli_real_escape_string($conn, $status_dostepnosci);
    $kategoria = mysqli_real_escape_string($conn, $kategoria);
    $gabaryt_produktu = mysqli_real_escape_string($conn, $gabaryt_produktu);
/*	$zdjecie = mysqli_real_escape_string($conn, $zdjecie);
	
	$nowezdjecie = " ";
	// uaktualnienie pliku zdjecie tylko jeśli został przesłany
	if ($testzdjecie !== null) {
		$nowezdjecie = ", zdjecie = '$zdjecie'";
	}
    */
   // zapytanie UPDATE
    $sql = "UPDATE product_list SET tytul = '$tytul', opis = '$opis', data_utworzenia = '$data_utworzenia',
        data_modyfikacji = '$data_modyfikacji', data_wygasniecia = '$data_wygasniecia', cena_netto = '$cena_netto', podatek_vat = '$podatek_vat',
        ilosc_dostepnych_sztuk_w_magazynie = '$ilosc_dostepnych_sztuk_w_magazynie', status_dostepnosci = '$status_dostepnosci',
        kategoria = '$kategoria', gabaryt_produktu = '$gabaryt_produktu' WHERE id = '$id' LIMIT 1";
	
    $result = mysqli_query($conn, $sql);
/*
    // powrót do strony
	if ($result){
		header("Location: adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}*/
	
	echo $_POST["gabaryt_produktu"];
}

// obsługa formularza po utworzeniu produktu / zapisanie danych nowego produktu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dodajprodukt'])) {
	// pobranie danych z formularza
	$tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
	$opis = mysqli_real_escape_string($conn, $_POST['opis']);
	$data_utworzenia = mysqli_real_escape_string($conn, $_POST['data_utworzenia']);
	$data_modyfikacji = mysqli_real_escape_string($conn, $_POST['data_modyfikacji']);
	$data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia']);
	$cena_netto = mysqli_real_escape_string($conn, $_POST['cena_netto']);
	$podatek_vat = mysqli_real_escape_string($conn, $_POST['podatek_vat']);
	$ilosc_dostepnych_sztuk_w_magazynie = mysqli_real_escape_string($conn, $_POST['ilosc_dostepnych_sztuk_w_magazynie']);
	$status_dostepnosci = isset($_POST['status_dostepnosci']) ? 1 : 0;
	$kategoria = mysqli_real_escape_string($conn, $_POST['kategoria']);
	$gabaryt_produktu = mysqli_real_escape_string($conn, $_POST['gabaryt_produktu']);
	if ($_FILES['zdjecie']['error'] == UPLOAD_ERR_OK) {
    // odczytanie zawartości pliku
    $zdjecie_tmp = $_FILES['zdjecie']['tmp_name'];
    $zdjecie = file_get_contents($zdjecie_tmp);
	} else {
		// gdy plik nie zostanie przesłany
		$zdjecie = null;
	}
      // zabezpieczenie
    $tytul = mysqli_real_escape_string($conn, $tytul);
    $opis = mysqli_real_escape_string($conn, $opis);
	$data_utworzenia = mysqli_real_escape_string($conn, $data_utworzenia);
	$data_modyfikacji = mysqli_real_escape_string($conn, $data_modyfikacji);
	$data_wygasniecia = mysqli_real_escape_string($conn, $data_wygasniecia);
	$cena_netto = mysqli_real_escape_string($conn, $cena_netto);
	$podatek_vat = mysqli_real_escape_string($conn, $podatek_vat);
	$ilosc_dostepnych_sztuk_w_magazynie = mysqli_real_escape_string($conn, $ilosc_dostepnych_sztuk_w_magazynie);
	$status_dostepnosci = mysqli_real_escape_string($conn, $status_dostepnosci);
	$kategoria = mysqli_real_escape_string($conn, $kategoria);
	$gabaryt_produktu = mysqli_real_escape_string($conn, $gabaryt_produktu);
	$zdjecie = mysqli_real_escape_string($conn, $zdjecie);
    // zapytanie INSERT
    $sql = "INSERT INTO product_list	 (tytul, opis , data_utworzenia, data_modyfikacji , data_wygasniecia, cena_netto, podatek_vat ,
	ilosc_dostepnych_sztuk_w_magazynie, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie) 
	VALUES ('$tytul', '$opis',  '$data_utworzenia',
	'$data_modyfikacji','$data_wygasniecia','$cena_netto', '$podatek_vat',
	'$ilosc_dostepnych_sztuk_w_magazynie', 
	'$status_dostepnosci','$kategoria','$gabaryt_produktu', '$zdjecie') LIMIT 1";
	
    $result = mysqli_query($conn, $sql);
	
	// powrót do strony
	if ($result){
		header("Location: adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}

// żeby działało usuwanie produktu
if (isset($_GET['action']) && $_GET['action'] == 'UsunProdukt' && isset($_GET['id'])) {
    UsunProdukt($conn, $_GET['id']);
}

// funkcja odpowiadająca za możliwość usunięcia produktu
function UsunProdukt($conn, $id)
{
    // zabezpieczenie przed SQL Injection
    $id = mysqli_real_escape_string($conn, $id);

    $sql = "DELETE FROM product_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
	// powrót do strony
	if ($result){
		header("Location: adminsite.php");
	} else{	// dodatkowa informacja
		echo 'coś poszło nie tak';
	}
}




?>
