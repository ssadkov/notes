<?php session_start(); $title = "Правка заметки"; require_once "header.php"; ?>

<div id="wrapper">
<div id="header">
	<h2>Правка заметки</h2>
</div> 

<div id="content">
<?php
	StartDB();
	
	$id = $_GET['id'];
	$SQL = "SELECT * FROM заметки WHERE `код заметки`=".$id;

	if ($result = mysqli_query($db, $SQL)) 
	{
		$row = mysqli_fetch_assoc($result);
		$heading  = $row['заголовок'];
		$note = $row['текст'];
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}


?>
<form action="update_note.php" method="post" enctype="multipart/form-data">
<?php			
		print "<input name='id' type='hidden' value=".$row['код заметки'].">";
	    print "<table>";
        print "<tr><td>Заголовок</td><td><input name='heading' value='".$row['заголовок']."' size=50></td></tr>";
        print '<tr><td><p>Текст заметки<br></td><td><textarea name="note" cols="40" rows="3">'.$row['текст'].'</textarea></p></td></tr>';
        print "<tr><td>Изображение (URL)</td><td><input name='imageurl' type='url' value='".$row['изображение']."' size=50></td></tr>";
        print '<tr><td>Загрузка изображения</td><td><input type="file" name="uploadfile"></td></tr>';
    
		
		$imageurl = $row['изображение'];
			if ($imageurl == "")
			{
			  $imagehtml = "";
			}
			else 
			{
				$imagehtml = "<a href=".$imageurl." target=_blank><img width=150 src='".$imageurl."'></a>";
			}
		
		print "<tr><td>".$imagehtml."</td></tr>";
     
    
		mysqli_free_result($result);
?>		
     <tr><td colspan=2><input type="submit" value="Изменить"></td></tr>
    </table>
</form>

	
</div>
<div id="footer">
</div>

</div>

<?php require_once "footer.php"; ?>

