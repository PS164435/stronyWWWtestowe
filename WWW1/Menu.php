<!DOCTYPE html>
<?php
	include('cfg.php');
	include('showpage.php');
	include('admin/admin.php');
	
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    if($_GET['idp'] == '') $strona = './html/glowny.html';
	
   /* if($strona == null || !file_exists($strona)){
        $strona = './html/glowny.html';
   } */
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
		
	 <?php
            if ($strona == null)
			{
				echo PokazPodstrone($_GET['idp']);
			} 
			else 
			{
			  include($strona);
			}
        ?>
		
	
		<div id="zegarek"></div>
		<div id="data"></div>
		
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
		
		<div id="animacjaTestowa1" class="test-block">kliknij, a się powiększe</div>
			<script>
				$("#animacjaTestowa1").on("click", function(){
					$(this).animate({
						width: "500px",
						opacity: 0.4,
						fontSize: "3em",
						borderWidth: "10px"
					}, 1500);
				});
			</script>
			
		<div id="animacjaTestowa2" class="test-block">
		Najedź kursorem, a się powiększe</div>
			<script>
				$("#animacjaTestowa2").on({
					"mouseover" : function(){
						$(this).animate({
							width: 300
							}, 800);
						},
					"mouseout" : function() {
						$(this).animate({
							width: 200
							}, 800);
						}
			});
			</script>
			
		<div id="animacjaTestowa3" class="test-block">
		Kliknij, abym urósł</div>
			<script>
				$("#animacjaTestowa3").on("click", function(){
					if(!$(this).is(":animated")) {
						$(this).animate({
							width: "+=" + 50,
							height: "+=" + 10,
							opacity: "-=" + 0.1,
							duraction : 3000
						});
					}
			});
			</script>
	 <?php
            $nr_indeksu = '164435';
            $nrGrupy = '4ISI';
            echo 'Autor: Jacek Szymański' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br><br><br><br>';
        ?>
</body>
</html>