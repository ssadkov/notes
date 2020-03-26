<?php $title = "Добавление заметки"; require_once "start_mysql.php"; require_once "main.php"; 

	$id_user  = htmlspecialchars($_POST['id_user']);
	$heading = htmlspecialchars($_POST['heading']);
	$note = htmlspecialchars($_POST['note']);
	$tags = htmlspecialchars($_POST['tags']);
	$imageurl = htmlspecialchars($_POST['imageurl']);
	
	$check = file_exists($_FILES["uploadfile"]["tmp_name"]);
    if($check == true) {
        $imageurl = ImageUpload();
    } 	
	
	StartDB();

	$SQL = "INSERT INTO заметки
					(`заголовок`, `текст`, `дата создания`, `дата изменения`, `код пользователя`, `удалена`, `изображение`) 
			VALUES 	('$heading', '$note', NOW(), NOW(), '$id_user', '0', '$imageurl')";		
	// print $SQL."<br>";
	if (mysqli_query($db, $SQL) === TRUE)
	{
	//	print "Запись в таблицу 'заметки' добавлены.<br>";
		// получаем код новой заметки
		$noteid = mysqli_insert_id($db);
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	// обработаем метки
	$notetags = explode(",", $tags);
	foreach ($notetags as $tag) // поработаем с каждым тегом
	{
			$tag = trim($tag); // удаляем пробелы по краям строки
			print $tag."<br>";
			$SQL = "SELECT * FROM `метки` WHERE `метка` LIKE '".$tag."' AND `код пользователя`='".$id_user."'";
			print $SQL."<br>";
			if ($result = mysqli_query($db, $SQL)) 
			{
				if (mysqli_num_rows($result) > 0) // работаем с существующей меткой
				{
					$row = mysqli_fetch_assoc($result);
					$tagid = $row['код метки'];
					$SQL = "INSERT INTO `заметки метки` (`код заметки`, `код метки`)
							VALUES						('$noteid',		'$tagid')";
					mysqli_query($db, $SQL);
				}
				else // если метки нет, добавляем её и затем связь
				{
					$SQL = "INSERT INTO `метки` (`метка`, `код пользователя`) VALUES ('$tag', '$id_user')";
					if (mysqli_query($db, $SQL) === TRUE)
					{
						$tagid = mysqli_insert_id($db);
					}
					$SQL = "INSERT INTO `заметки метки` (`код заметки`, `код метки`)
							VALUES						('$noteid',		'$tagid')";
					mysqli_query($db, $SQL);
				}
			}
	}
	// закончили обрабатывать метки
	
	EndDB();
	header("Location: edit_table.php");	
?>

