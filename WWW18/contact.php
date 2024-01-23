<link rel="stylesheet" href="css/stylowa.css">
<?php


// żeby działało wysyłanie formularza
if (isset($_GET['action']) && $_GET['action'] == 'wyślij_formularz') {
	WyslijMailaKontakt('jacekszymanskii@onet');
}

// żeby działało przypominanie hasła
if (isset($_GET['action']) && $_GET['action'] == 'przypomnij_haslo') {
    PrzypomnijHaslo('jacekszymanskii@onet');
}

// formularz kontaktowy
function PokazKontakt()
{
    echo '
    <!-- skrypt -->
    <script>
        function roziwnformularz() {
            var formularz = document.getElementById("formularz");
            formularz.style.display = (formularz.style.display === "none" || formularz.style.display === "") ? "block" : "none";
        }
    </script>
	
	<!-- pola formularza -->
    <div style="text-align: center;">
        <!-- nagłówek "formularz" do rozwijania i zwijania -->
        <h2 class="formularz" onclick="roziwnformularz()">Formularz</h2>

        <!-- formularz z identyfikatorem "formularz" -->
        <form method="post" action="index.php?action=wyślij_formularz" id="formularz" class="formularz-form" style="display: none;">
			
            <label for="email" class="formularz-label">Email:</label>
            <input type="email" name="email" id="email" class="formularz-input"><br>
			
			<label for="temat" class="formularz-label">Temat:</label>
            <input type="text" name="temat" id="temat" class="formularz-input"><br>

            <label for="tresc" class="formularz-label">Treść:</label>
            <textarea name="tresc" id="tresc" class="formularz-textarea"></textarea><br>

	        <input type="button" name="pokaz_okno_email" value="Wyślij" onclick="pokazOknoEmail()" class="formularz-submit">
        </form>
    </div>

    <script>
        function pokazOknoEmail() {
            var potwierdzenie = confirm("Czy na pewno chcesz wysłać ten formularz?");
            if (potwierdzenie) {
                window.location.href = "mailto:jacekszymanskii@onet.eu";
            }
        }
    </script>';
}

function WyslijMailaKontakt($odbiorca)
{	
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
    {
        echo '[nie_wypelniles_pola]';
    }
    else
    { 
    $mail['subject'] = $_POST['temat'];
    $mail['body'] = $_POST['tresc'];
    $mail['sender'] = $_POST['email'];
    $mail['reciptient'] = $odbiorca;

    ini_set("sendmail_from", $mail['sender']);

    $header = "Form: Formularz kontaktowy <".$mail['sender'].">\n";
    $header .= "MIME-Version: 1.0\n Content-Type: text/plain: charset-uft-8\n Content-Transfer-Encoding: ";
    $header .= "X-Sender: <". $mail['sender'].">\n";
    $header .= "X-pririty: 3\n";
    $header .= "Return_Path: <". $mail['sender'].">\n";

    mail($mail['reciptient'],$mail['subject'],$mail['body'],$header);

    ini_restore("sendmail_from");

    echo '[wiadomosc_wyslana]'; 
    }
}

function PrzypomnijHaslo($odbiorca)
{
    $mail['subject'] = 'Przypomnienie hasła';
    $mail['body'] = 'Hasło: czak';
    $mail['sender'] = 'jacekszymanskii@onet';
    $mail['reciptient'] = $odbiorca;

	$header = "Form: Formularz kontaktowy <".$mail['sender'].">\n";
    $header = "From: Przypomnienie hasła <".$mail['sender'].">\n";
    $header .= "MIME-Version: 1.0\n Content_Type: text/plain: charset=utf-8\n Content_Transfer_Encoding: ";
    $header .= "X-Sender: <". $mail['sender'].">/n";
    $header .= "X-Priority: 3\n";
    $header .= "Return_Path: <". $mail['sender'].">\n";

    mail($mail['reciptient'], $mail['subject'], $mail['body'], $header);

    echo '[wiadomosc_wyslana]';

}


?>