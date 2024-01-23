<link rel="stylesheet" href="css/stylowa.css">
<?php


// żeby działało wysyłanie formularza
if (isset($_GET['action']) && $_GET['action'] == 'wyślij_formularz') {
    echo ' taktak yesyes';
	WyslijMailaKontakt('jacekszymanskii@onet');
}

/*

// żeby działało przypominanie hasła
if (isset($_GET['action']) && $_GET['action'] == przypomnij_haslo' && isset($_GET['id'])) {
    PrzypomnijHaslo('jacekszymanskii@onet');
}
*/

// formularz kontaktowy
function PokazKontakt()
{
    echo '
    <!-- skrypt -->
    <script>
        function toggleForm() {
            var formularz = document.getElementById("formularz");
            formularz.style.display = (formularz.style.display === "none" || formularz.style.display === "") ? "block" : "none";
        }
    </script>
	
	<!-- pola formularza -->
    <div style="text-align: center;">
        <!-- nagłówek "formularz" do rozwijania i zwijania -->
        <h2 class="formularz" onclick="toggleForm()">Formularz</h2>

        <!-- formularz z identyfikatorem "formularz" -->
        <form method="post" action="contact.php?action=wyślij_formularz" id="formularz" class="formularz-form" style="display: none;">
			
            <label for="email" class="formularz-label">Email:</label>
            <input type="email" name="email" id="email" class="formularz-input"><br>
			
			<label for="temat" class="formularz-label">Temat:</label>
            <input type="text" name="temat" id="temat" class="formularz-input"><br>

            <label for="tresc" class="formularz-label">Treść:</label>
            <textarea name="tresc" id="tresc" class="formularz-textarea"></textarea><br>

            <input type="submit" name="zapiszstrone" value="Wyślij" class="formularz-submit">
        </form>
    </div>';
}

function WyslijMailaKontakt($odbiorca)
{	
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['zapiszstrone'])) {
        if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
            echo '[nie_wypelniles_pola]';
            echo PokazKontakt();
        } else {
            $mail['subject']    = $_POST['temat'];
            $mail['body']       = $_POST['tresc'];
            $mail['sender']     = $_POST['email'];
            $mail['recipient']  = $odbiorca;

            $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: text/plain; charset=utf-8\r\n";
            $header .= "Content-Transfer-Encoding: 8bit\r\n";
            $header .= "X-Sender: <" . $mail['sender'] . ">\r\n";
            $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $header .= "X-Priority: 3\r\n";
            $header .= "Return-Path: <" . $mail['sender'] . ">\r\n";

            mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

            echo '[wiadomosc_wyslana]';
        }
    }
}


function WyslijMailKontakt2(){
	
	/*	
	?>
    <<h4>Formularz kontaktowy</h4>	
	<form action="mailto:jacekszymanskii@onet.eu" method="post" enctype="text/plain">
	
		Twój adres email: 
		<input type="text" name="mail"><br>
		
		Treść: <br> <textarea name="maintext"></textarea>  <br><br>
		<input type="submit" value="Wyślij">     		
	
	</form>
	<?php 
	*/
	

}



function PrzypomnijHaslo(){
	
	
	
	
	
	
	
}

?>