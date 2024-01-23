<?php

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

?>