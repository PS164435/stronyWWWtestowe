<!DOCTYPE html>
<?php
	include('cfg.php');
	include('showpage.php');
	
		
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    if($_GET['idp'] == '') $strona = './html/glowny.html';
	
   if($_GET['idp'] === 'BUDOWA') {
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
		<script src=".\js\kolorujtlo.js" type="text/javascript"></script>
		<script src=".\js\timedate.js" type="text/javascript"></script>
		<script src=".\js\jquery-3.7.1.js"></script>
		<link rel="stylesheet" href="css/stylówa.css">
	</head>
<body class="<?php echo $backgroundClass; ?>"  onload="startclock()">
	
	<table class="gornefunkcje">
		<tr>
			<td class="gornefunkcje" style=" width: 10%;">
				<div id="zegarek"></div>
				<div id="data"></div>
			</td>
				<td class="gornefunkcje" style="text-align: left; width: 40%;">
					<FORM METHOD="POST" NAME="background">
						<INPUT TYPE="button" VALUE="żółty" ONCLICK="changeBackground('#FFF000')">
						<INPUT TYPE="button" VALUE="czarny" ONCLICK="changeBackground('#000000')">
						<INPUT TYPE="button" VALUE="biały" ONCLICK="changeBackground('#FFFFFF')">
						<INPUT TYPE="button" VALUE="zielony" ONCLICK="changeBackground('#00FF00')">
						<INPUT TYPE="button" VALUE="niebieski" ONCLICK="changeBackground('#0000FF')">
						<INPUT TYPE="button" VALUE="pomarańczowy" ONCLICK="changeBackground('#FF8000')">
						<INPUT TYPE="button" VALUE="szary" ONCLICK="changeBackground('#c0c0c0')">
						<INPUT TYPE="button" VALUE="czerwony" ONCLICK="changeBackground('#FF0000')">
						<INPUT TYPE="button" VALUE="domyślny" ONCLICK="changeBackground('#375f18')">	
					</FORM>	
				</td>
				<td class="gornefunkcje">
				<?php
						include('admin/admin.php');
					?>
				</td>
		</tr>
	</table>	
		
		
	 <?php
			//include('admin/display.php');
			
			ListaPodstron($conn, $id_clear);
	 
            if ($strona == null)
			{
				echo PokazPodstrone($_GET['idp']);
			} 
			else 
			{
			  include($strona);
			}
			


?>
		
	
			
	 <?php
            $nr_indeksu = '164435';
            $nrGrupy = '4ISI';
            echo 'Autor: Jacek Szymański' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br><br><br><br>';
        ?>
</body>
</html>