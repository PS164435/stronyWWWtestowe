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
	if ($result) { 
		// gdy udało się zapisać
		echo "Zmiany zostały zapisane pomyślnie."; 
		?>	<!-- przycisk przejścia do menu -->
		<form method="get" action="../Menu.php">
		  <button type="submit">Przejdź do menu</button>
		</form>
		<?php
	} else { 
		// gdy nie udało się zapisać
		echo "Wystąpił błąd podczas zapisywania zmian.";
		?>	<!-- przycisk przejścia do menu -->
		<form method="get" action="../Menu.php?>">
		  <button type="submit">Przejdź do menu</button>
		</form>
		<?php
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
    $sql = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$tytul', '$tresc', '$aktywna')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // gdy udało się dodać
		echo "Dodano nową podstonę.";
		?>	<!-- przycisk przejścia do menu -->
		<form method="get" action="../Menu.php">
		  <button type="submit">Przejdź do menu</button>
		</form>
		<?php
	} else {
        // gdy nie udało się dodać
        echo "Nie udało się dodać nowej podstrony.";
		?>	<!-- przycisk przejścia do menu -->
		<form method="get" action="../Menu.php">
		  <button type="submit">Przejdź do menu</button>
		</form>
		<?php
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
    DodajNowaPodstrone($conn);
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
<!-- przycisk -->		<td class="log4_t"><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
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

function FormularzStrony($row, $czynnosc){
	?> <!-- formularz do wypełniania podczas edycji -->
	<form method="post" action="admin/admin.php">
		<!-- id -->
		<label for="id">ID: <?php echo $row['id']; ?></label>
		<input type="hidden" name="id" value="<?php echo $row['id']; ?>"><br><br>
		<!-- tytuł -->
		<label for="tytul">Tytuł: </label>
		<input type="text" name="tytul" value="<?php echo $row['page_title']; ?>"><br><br>
		<!-- treść strony -->
		<label for="tresc">Treść strony: </label><br>
		<textarea name="tresc"><?php echo $row['page_content']; ?></textarea><br><br>
		<!-- aktywność strony -->
		<label for="aktywna">Aktywna: </label>
		<input type="checkbox" name="aktywna" <?php echo $row['status'] ? 'checked' : ''; ?>><br><br>
		<!-- przycisk do zatwierdzenia -->
		<input type="submit" name="<?php echo $czynnosc; ?>" value="Zapisz zmiany">
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
}

// funkcja odpowiadająca za możliwość dodania nowej podstrony
function DodajNowaPodstrone($conn){
	
	$row = array('id' => '', 'page_title' => '', 'page_content' => '', 'status' => '');
	
	FormularzStrony($row, $czynnosc="dodajstrone");
   
}

?>