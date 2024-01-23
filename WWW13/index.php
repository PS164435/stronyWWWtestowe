<!DOCTYPE html>
<?php
	// includy, wczytania z innych plików
	include('cfg.php');
	include('admin/admin.php');
	include('contact.php');
	include('showpage.php');
	
	// wyłączenie warningoów
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	
	// domyślna strona = brak idp
    if($_GET['idp'] == '') $strona = './html/glowny.html';
	
	// statyczne tło dla podstrony BUDOWA, reszta podstawowe
	if($_GET['idp'] == 'BUDOWA') {
        $backgroundClass = 'custombackground';
    } else {
        $backgroundClass = 'defaultbackground';
    }

?>

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="pl" />
		<meta name="Author" content="Jacek Szymański" />
		<title>Patyczaki i Badyle</title>
		<script src="./js/kolorujtlo.js" type="text/javascript"></script>
		<script src="./js/timedate.js" type="text/javascript"></script>
		<script src="./js/jquery-3.7.1.js"></script>
		<link rel="stylesheet" href="css/stylowa.css">
	</head> 
	
	<body class="<?php echo $backgroundClass; ?>"  onload="startclock()"> <!-- wywołanie tła i zegar -->
	
	<!-- pasek na górze strony -->
	<table class="gornefunkcje"> 
		<tr>
			<!-- część z zegarem -->
			<td class="gornefunkcje" style=" width: 10%;">
				<div id="zegarek"></div>
				<div id="data"></div>
			</td>
			<!-- część z wyborem koloru tła -->
			<td class="gornefunkcje" style="text-align: center; width: 45%;">
				<form METHOD="POST" NAME="background">
					<input class="gornefunkcje" type="button" value="żółty" onclick="changeBackground('#FFF000')">
					<input class="gornefunkcje" type="button" value="czarny" onclick="changeBackground('#000000')">
					<input class="gornefunkcje" type="button" value="biały" onclick="changeBackground('#FFFFFF')">
					<input class="gornefunkcje" type="button" value="zielony" onclick="changeBackground('#00FF00')">
					<input class="gornefunkcje" type="button" value="niebieski" onclick="changeBackground('#0000FF')">
					<input class="gornefunkcje" type="button" value="pomarańczowy" onclick="changeBackground('#FF8000')">
					<input class="gornefunkcje" type="button" value="szary" onclick="changeBackground('#c0c0c0')">
					<input class="gornefunkcje" type="button" value="czerwony" onclick="changeBackground('#FF0000')">
					<input class="gornefunkcje" type="button" value="domyślny" onclick="changeBackground('#375f18')">	
				</form>	
			</td>
			<!-- część panelem CMS -->
			<td class="gornefunkcje"> <?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
				// kod odpowiedzialny za logowanie
				if (isset($_POST["login_email"])) {
					$login_email = $_POST["login_email"];
					$login_pass = $_POST["login_pass"];
					if ($login_email == "paty" && $login_pass == "czak") {
						$_SESSION["adminlogin"] = true;
					} else {
							   echo "Niepoprawne dane logowania";
					}
				}
				// kod odpowiedzialny za wylogowywanie
				elseif (isset($_POST["wyloguj"])) {
					unset($_SESSION["adminlogin"]);
					header("Location: index.php");
					
				} 
			}

			// wyświetlanie formularza
			if (!isset($_SESSION["adminlogin"])) {
				echo FormularzLogowania();
			} else {
				// informacja o udanym logowaniu i przyciski
				echo '
					<form method="post"> 
						Udane logowanie
						&nbsp;&nbsp;&nbsp;
						<input class="gornefunkcje" type="submit" name="wyloguj" value="Wyloguj" />
						&nbsp;&nbsp;&nbsp;
						<input class="gornefunkcje" type="button" onclick="IntoAdminSite()" value="AdminSite">
					</form>';
			} 
				?>
			</td>
		</tr>
	</table>

		<!-- h1 i h2, wybory stron -->
			<h1><a <?php if ($_GET['idp'] == '') echo 'class="this"'; ?> href="./index.php?idp=">MENU</a></h1>
			<h2>
				<a <?php if ($_GET['idp'] == 'LOREMIPSUM') echo 'class="this"'; ?> href="./index.php?idp=LOREMIPSUM">LOREMIPSUM</a>
				<a <?php if ($_GET['idp'] == 'BUDOWA') echo 'class="this"'; ?> href="./index.php?idp=BUDOWA">BUDOWA</a>
				<a <?php if ($_GET['idp'] == 'WYSTĘPOWANIE') echo 'class="this"'; ?> href="./index.php?idp=WYSTĘPOWANIE">WYSTĘPOWANIE</a> 
				<a <?php if ($_GET['idp'] == 'TABELA') echo 'class="this"'; ?> href="./index.php?idp=TABELA">TABELA</a>
				<a <?php if ($_GET['idp'] == 'FILMY') echo 'class="this"'; ?> href="./index.php?idp=FILMY">FILMY</a>
			</h2>
		
		<?php
	
			// wyświetlenie zawartości strony
			if ($strona == null) {
				echo PokazPodstrone($_GET['idp']);
			} else if (basename($_SERVER['PHP_SELF']) != 'adminsite.php'){
					include($strona);
			}
			
		// linijka na kośnu strony
		if (basename($_SERVER['PHP_SELF']) != 'adminsite.php'){
            $nr_indeksu = '164435';
            $nrGrupy = '4ISI';
            echo 'Autor: Jacek Szymański' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br><br><br><br>';	
		}
        ?>
	</body>
</html>