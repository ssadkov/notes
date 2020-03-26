<?php $title = "Изменение заметки"; require_once "start_mysql.php"; require_once "main.php"; 
	
	StartDB();
	$id = $_POST['id'];
	$heading  = htmlspecialchars($_POST['heading']);
	$note = htmlspecialchars($_POST['note']);
	$imageurl = htmlspecialchars($_POST['imageurl']);
	
	$check = file_exists($_FILES["uploadfile"]["tmp_name"]);
    if($check == true) {
        $imageurl = ImageUpload();
    } 

	$SQL = "UPDATE заметки SET `заголовок`='$heading', `текст`='$note', `изображение`='$imageurl', `дата изменения` = NOW()  WHERE `код заметки`='$id'";


	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	EndDB();
	header("Location: edit_table.php");	
