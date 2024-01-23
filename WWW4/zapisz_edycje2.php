<?php
// nawiązanie połączenia
$conn = new mysqli("localhost", "root", "", "moja_strona");
// co jeśli nie da się połączyć
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
// zapisanie danych
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zapiszstrone'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
    $tresc = mysqli_real_escape_string($conn, $_POST['tresc']);
    $aktywna = isset($_POST['aktywna']) ? 1 : 0;

    $sql = "UPDATE page_list SET page_title = '$tytul', page_content = '$tresc', status = '$aktywna' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "Zmiany zostały zapisane pomyślnie.";
		?>
		<form method="get" action="Menu.php">
		  <button type="submit">Przejdź do menu</button>
		</form>
		<?php

    } else {
        echo "Wystąpił błąd podczas zapisywania zmian.";
    }
}
?>
