<?php

// funkcja odpowiadająca za formularz logowania
function FormularzLogowania()
{
	echo '
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
}

// funkcja odpowiadająca za listę podstron
function ListaPodstron()
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
	
	
if (mysqli_num_rows($result) > 0) {
        ?>
        <table style="border: 10px solid black; background: white;">
            <tr>
                <th style="border: 5px solid green;">ID</th>
                <th style="border: 5px solid green;">TYTUŁ</th>
                <th style="border: 5px solid green;">OPCJA</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td style="border: 5px solid lime; font-size: 23px;"><?php echo $row["id"]; ?></td>
                    <td style="border: 5px solid lime; font-size: 23px;"><?php echo $row["page_title"]; ?></td>
                    <td style="border: 5px solid lime; font-size: 23px;">
                        <a href="update.php?id=<?php echo $row["id"]; ?>">Edytuj</a>
						<a href="edit.php?id=<?php echo $row["id"]; ?>">Edytuj</a>
                        <a href="delete.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten rekord?')">Usuń</a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
			}
			else
			{
			  echo "0 results";
			}
}


function EdytujPodstrone($conn, $id)
{
    $sql = "SELECT * FROM page_list WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        ?>
        <form method="post" action="zapisz_edycje.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <label for="tytul">Tytuł:</label>
            <input type="text" name="tytul" value="<?php echo $row['page_title']; ?>"><br>

            <label for="tresc">Treść strony:</label>
            <textarea name="tresc"><?php echo $row['page_content']; ?></textarea><br>
			
			<label for="aktywna">Aktywna:</label>
            <input type="checkbox" name="aktywna" <?php echo $row['status'] ? 'checked' : ''; ?>><br>
			
            <input type="submit" value="Zapisz zmiany">
        </form>
        <?php
    } else {
        echo "Nie znaleziono podstrony o podanym ID.";
    }
	
	
}

?>

