<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['login_email'])){
        $login_email = $_POST['login_email'];
        $login_pass = $_POST['login_pass'];
        if ($login_email == "paty" && $login_pass == "czak"){
            $_SESSION['adminlogin'] = true;
        }
		else
		{
			echo "Niepoprawne dane logowania";
		}
    }
	elseif (isset($_POST['wyloguj']))
	{
		unset($_SESSION['adminlogin']);
	}
}

if (!isset($_SESSION['adminlogin'])){
	echo FormularzLogowania();
}
else
{
	echo '<form method="post"> 
	     Udane logowanie &nbsp&nbsp&nbsp<input type="submit" name="wyloguj" class="logowanie" value="Wyloguj" />
		</form>';

}
function FormularzLogowania()
{
	$wynik = '
	<div class="logowanie">
			<div class="logowanie">
				<form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
					<table class="logowanie">
						<tr><td class="log4_t">Panel CMS:</td>
						<td class="log4_t">Email</td><td class="log4_t"><input type="text" name="login_email" class="logowanie" /></td>
						<td class="log4_t">Haslo</td><td class="log4_t"><input type="password" name="login_pass" class="logowanie" /></td>
						<td class="log4_t"><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
					</table>
				</form>
			</div>
	</div>
	';
	
	return $wynik;
}

function ListaPodstron($conn, $id_clear)
{
$hostname = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'moja_strona';

    $con = mysqli_connect($hostname, $username, $password, $dbname);
    if(!$con)
    {
        die("Connection failed!" . mysqli_connect_error());
    }
    else 
    {
        echo "Successfully Connected! <br>";
    }
    $sql = "SELECT * FROM page_list";
    $result = mysqli_query($con, $sql);
	
	if(mysqli_num_rows($result) > 0)
			{
				$query="SELECT * FROM page_list WHERE id='$id_clear' ORDER BY data DESC LIMIT 100";
				$row = mysqli_fetch_assoc($result);
				echo $row["id"].' '.$row["page_title"].'<br />';
				while($row = mysqli_fetch_array($result))
				{
					echo $row["id"].' '.$row["page_title"].'<br />';
				}
				

			}
			else
			{
			  echo "0 results";
			}
}

?>

