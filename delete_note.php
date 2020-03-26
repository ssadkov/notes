<?php $title = "Удаление заметки"; require_once "start_mysql.php"; 
	
	StartDB();
	$id_note = $_GET['id'];
	$SQL = "UPDATE заметки SET `удалена`='1'  WHERE `код заметки`='$id_note'";

	
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	EndDB();
	header("Location: ".$_SERVER['HTTP_REFERER']);
