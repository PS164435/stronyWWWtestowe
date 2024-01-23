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
    DodajNowaPodstrone($conn);
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
        <!-- id_kierowcy -->
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
				<td class="popupl" > <label class="popup" or="aktywna">Aktywna: </label> </td>
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

// funkcja odpowiadająca za możliwość dodania nowej podstrony
function DodajNowaPodstrone($conn){
	
	$row = array('id' => '', 'page_title' => '', 'page_content' => '', 'status' => '');
	
	FormularzStrony($row, $czynnosc="dodajstrone");
   
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
					<input class="kategorie" type="submit" name="usuniecie_kategorii" onclick="return confirm('Czy na pewno chcesz usunąć ten rekord?')" value="Usuń Kategorię"></form>
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


?>

