<?

function FormularzLogowania()
{
	$wynik = '
	<div class="logowanie">
		<h4>Panel CMS:</h4>
			<div class="logowanie">
				<form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
					<table class="logowanie">
						<tr><td class="log4_t">Email</td><td class="log4_t"><input type="text" name="login_email" class="logowanie" /></td></tr>
						<tr><td class="log4_t">Haslo</td><td class="log4_t"><input type="password" name="login_pass" class="logowanie" /></td></tr>
						<tr><td style="background-color: black" class="log4_t">&nbsp;</td><td class="log4_t"><input type="submit" name="x1_submit" class="logowanie" value="zaloguj" /></td></tr>
					</table>
				</form>
			</div>
	</div>
	';
	
	return $wynik;

}

?>