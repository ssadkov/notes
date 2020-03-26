<?php


function InitDB()
{
	global $db;

	// Создание таблицы Users
	if (mysqli_query($db, "DROP TABLE IF EXISTS Users;") === TRUE)
	{
		print "Таблица Users удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	$SQL = "CREATE TABLE Users 
	( 
	`iduser` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`login` VARCHAR(50) NOT NULL, 
	`password` VARCHAR(255) NOT NULL,
	`reg_time` TIMESTAMP NOT NULL
	);";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		print "Таблица Users создана<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}


	// Создание таблицы Товары 
	if (mysqli_query($db, "DROP TABLE IF EXISTS Товары;") === TRUE)
	{
		//print "Таблица Товары удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	
	
	$SQL = "CREATE TABLE Товары ( 
	`Код товара` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`Товар` VARCHAR(50) NOT NULL, 
	`Цена` INT NOT NULL
	);";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		//print "Таблица Товары создана<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}

	// Создание таблицы Группы 
	if (mysqli_query($db, "DROP TABLE IF EXISTS Группы;")  === TRUE)
	{
		//print "Таблица Группы удалена<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}


	
	$SQL = "CREATE TABLE Группы ( 
	`Код группы` INT NOT NULL  AUTO_INCREMENT PRIMARY KEY, 
	`Группа` VARCHAR(50) NOT NULL, 
	`Менеджер` VARCHAR(50) NOT NULL);";
	
	if (mysqli_query($db, $SQL) === TRUE)
	{
		//print "Таблица Группы создана<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
}

function PutDB()
{
	global $db;

	$SQL = "INSERT INTO Товары
					(`Товар`, `Цена`) 
			VALUES 	('Телевизор', '20000'), 
					('Холодильник', '45000'),
					('Диктофон', '5000')
		";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		//print "Записи в таблицу Товары добавлены.<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
	
	$SQL = "INSERT INTO Группы
					(`Группа`, `Менеджер`) 
			VALUES 	('Электроника', 'Иванов'), 
					('Бытовая техника', 'Петров')
		";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		//print "Записи в таблицу Группы добавлены.<br>";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}

}

function GetDB()
{
	global $db;
	$SQL = "SELECT * FROM пользователи";
	
	if ($result = mysqli_query($db, $SQL)) 
	{
	/*	print "<table border=1 cellpadding=5><tr><td><b>Логин</b></td><td><b>Пароль</b></td></tr>"; 
		// Выборка результатов запроса 
		while( $row = mysqli_fetch_assoc($result) )
		{ 
			print "<tr>"; 
			printf("<td>%s</td><td>%s</td>", $row['Логин'], $row['Пароль']); 
			print "</tr>"; 
		} 
		print "</table>"; 
	*/	
		// считаем количество пользователей и выводим
		$usercount = mysqli_num_rows($result);
		print 'Количество пользователей: '.$usercount;
		
		mysqli_free_result($result);
	}
	else
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	 
}	

// Вывод формы для добавления пользователей
function AddDB()
{
	global $db;
	// Получение списка пользователей
	$SQL = "SELECT * FROM пользователи";
	
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}

?>
<form action="add.php" method="post">
	    <table>
        <tr><td>Логин</td><td><input name="login" maxlength=55 size=30></td></tr>
        <tr><td>Пароль</td><td><input name="password" maxlength=255 size=30></td></tr>
        <tr><td colspan=2><input type="submit" value="Добавить"></td></tr>
    </table>
</form>
	
<?php	
	
}


// Вывод таблицы с функциями редактирования
function EditDB()
{
	global $db;
	if ($result = mysqli_query($db, "SELECT * FROM пользователи")) 
	{
		print "<table border=1 cellpadding=5>";
		while ($row = mysqli_fetch_assoc($result)) 
		{
			print "<tr>"; 
			printf("<td>%s</td><td>%s</td><td>%s</td>", $row['Логин'], $row['Пароль'], $row['Дата регистрации']); 
			print "<td><a href='edit.php?id=".$row['Код пользователя']."'>Открыть</a></td>";
			print "<td><a href='delete.php?id=".$row['Код пользователя']."'>Удалить</a></td>";
			if ($row['Код пользователя'] == $_SESSION['id']) { print "<td>ЭТО ВЫ</td>"; }
			print "</tr>"; 			
		}	 
		print	"</table><br>";
	}
}	


// Вывод таблицы с функциями редактирования заметок
function EditNotesDB()
{
	global $db;
	$id_user = $_SESSION['id'];
	
	// реализуем поиск
	$search_sql = ''; // строка для запроса
	if (isset($_GET['search'])) // если есть переменная, добавляем в запрос
	{
		$search = $_GET['search'];
		$search_sql = " AND (`заголовок` LIKE'%".$search."%' OR `текст` LIKE'%".$search."%')"; 
	}
	else $search = '';
	
	$SQL = "SELECT * FROM `заметки` WHERE `код пользователя` = $id_user AND `удалена` = 0 ".$search_sql." ORDER BY `дата изменения` DESC";
	// print $SQL;
	
	if ($result = mysqli_query($db, $SQL)) 
	{
		print "<table border=1 cellpadding=5><tr><td>Заголовок</td><td>Текст</td><td>Изображение</td></td><td>Дата создания</td><td>Дата изменения</td><td>Метки</td><td colspan=2>Правка данных</td></tr>";
		while ($row = mysqli_fetch_assoc($result)) 
		{
			$imageurl = $row['изображение'];
			if ($imageurl == "")
			{
			  $imagehtml = "";
			}
			else 
			{
				$imagehtml = "<a href=".$imageurl." target=_blank><img width=150 src='".$imageurl."'></a>";
			}
			
			print "<tr>"; 
			printf("<td><b>%s</b></td><td>%s</td><td>$imagehtml</td><td>%s</td><td>%s</td>", $row['заголовок'], $row['текст'], $row['дата создания'], $row['дата изменения']); 
			// колонка с метками
			print "<td>".ShowTags($row['код заметки'])."</td>";
			print "<td><a href='edit_note.php?id=".$row['код заметки']."'>Изменить</a></td>";
			print "<td><a href='edit_note.php?id=".$row['код заметки']."'>Изменить</a></td>";
			print "<td><a href='delete_note.php?id=".$row['код заметки']."'>Удалить</a></td>";
			print "</tr>"; 			
		}	 
		print	"</table><br>";
	}
}	

function AddNotesDB()
{
	global $db;
	$SQL = "SELECT * FROM заметки";
	
	if (!$result = mysqli_query($db, $SQL)) 
	{
		printf("Ошибка в запросе: %s\n", mysqli_error($db));
	}
	
	$id_user = $_SESSION['id'];

?>
<form action="add_note.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id_user" value=<?php print $id_user; ?>>
	    <table>
        <tr><td>Заголовок</td><td><input name="heading" size=50></td></tr>
        <tr><td><p>Текст заметки<br></td><td>
		<textarea name="note" cols="40" rows="3"></textarea></p></td></tr>
		<tr><td>Метки (через запятую)</td><td><input name="tags" size=50></td></tr>
		<tr><td>Изображение (URL)</td><td><input name="imageurl" type="url" size=40><input type="file" name="uploadfile"></td></tr>
        
        <tr><td colspan=2><input type="submit" value="Добавить"></td></tr>
    </table>
</form>
	
<?php	
	
}

function FilterNotesDB()
{
	if (isset($_GET['search'])) // если есть переменная, добавляем в запрос
	{
		$search = $_GET['search'];
	} 
	else { $search = ''; }
 	
	
	print '
	<form action="edit_table.php" method="GET">
	    <table>
        <tr><td>Поиск по заметкам</td><td><input name="search" value="'.$search.'" size=50></td></tr>     
        <tr><td colspan=2><input type="submit" value="Найти"></td></tr>
		</table>
	</form>';
}

	
function StartPage()
{	
?>	
	<div id="wrapper">
<div id="header">
	<h2>Сервис заметок</h2>
</div> 


<div id="content">
<?php
	
}

function EndPage()
{	
?>	
</div>
<div id="footer">
</div>

</div>

<?php
	
}

// Проверка авторизации
function CheckLogin()
{
	// если есть id пользователя в сессии, пусть правит данные
	if (isset($_SESSION['id'])) 
	{ 
		print "<a href='edit_table.php'>Все заметки</a>"; 
		print "<br> Все метки: ".ShowTags();
	}
	else
	{	
		
		// Проверка логина
		if(isset($_POST['userlogin']))
		{
			$_SESSION['login'] = $_POST['userlogin'];
			$_SESSION['password'] = $_POST['userpass'];
			print "<br>Логин ".$_SESSION['login'];
			// Проверка пароля
			if(CheckPassword())
			{
				print "<a href='edit_table.php'>Все заметки</a>";
				ShowTags(); 
			}
			else
			{
				print "<br>Доступ запрещен";
				print "<a href='login.php'><br>Введите логин и пароль повторно</a>";
			}
		}
		else
		{
			print "<a href='login.php'>Для правки данных введите логин и пароль</a>";
		}
	}	
}

function CheckPassword() 
{
	global $db;
    // Составляем строку запроса
    $SQL = "SELECT * FROM `пользователи` WHERE `логин` LIKE '".$_SESSION['login']."'";

	if ($result = mysqli_query($db, $SQL)) 
	{
		// Если нет пользователя с таким логином, то завершаем функцию
		if(mysqli_num_rows($result)== 0) 
		{
			print "<br>Нет такого логина";
			return FALSE;
		}
		// Если логин есть, то проверяем пароль
		$row = mysqli_fetch_assoc($result); 
		if (password_verify($_SESSION['password'], $row['Пароль']))
		{
			print "Пароль совпадает<br>";
			$_SESSION['id'] = $row['Код пользователя'];
			return TRUE;
		}
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	}
    print "Нет такого пароля<br>";
    return FALSE;
}


// Функция регистрации пользователя
function RegUser() 
{
	global $db;
	// Проверка данных
	if(!$_POST['user_login']) 
	{
		print "<br>Не указан логин";
		return FALSE;
	} 
	elseif(!$_POST['user_password']) 
	{
		print "<br>Не указан пароль";
		return FALSE;
	}
	
	// Проверяем не зарегистрирован ли уже пользователь
	$SQL = "SELECT `логин` FROM `пользователи` WHERE `логин` LIKE '".$_POST['user_login']. "'";

	// Делаем запрос к базе
	if ($result = mysqli_query($db, $SQL)) 
	{
		// Если есть пользователь с таким логином, то завершаем функцию
		if(mysqli_num_rows($result) > 0) 
		{
			print "<br>Пользователь с указанным логином уже зарегистрирован.";
			return FALSE;
		}
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
	} 
	// Если такого пользователя нет, регистрируем его
	$hash_pass = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
	$SQL = "INSERT INTO `пользователи` 
			(`логин`,`пароль`, `дата регистрации`) VALUES 
			('".$_POST['user_login']. "','".$hash_pass. "', NOW())";

	if (mysqli_query($db, $SQL) === TRUE)
	{
		//print "<br>Пользователь зарегистрирован";
	}
	else
	{
		printf("Ошибка: %s\n", mysqli_error($db));
		return FALSE;
	}
	
	return TRUE;
}

function ImageUpload() 
{

    @mkdir("files", 0777); // создаем папку, если ее нет, то ошибки не будет, задаем права

    $uploaddir = 'files/';
    $uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);

    if(copy($_FILES['uploadfile']['tmp_name'], $uploadfile)){
		$url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$url = explode('?', $url);
		$url = $url[0];
		$array = parse_url($url);
		$arr_path = explode('/', $array['path']);
		$url = $array['scheme'].'://'.$array['host'].'/'.$arr_path[1];
       
        $imageurl = $url."/files/".$_FILES['uploadfile']['name'];
     //   echo "<h3>Файл успешно загружен на сервер</h3>".$imageurl;
     //   echo "<img src='".$imageurl."'>";
        
    }else{
       // echo "<h3>Не удалось загрузить файл на сервер</h3>";
        return FALSE;
    }

    //Данные о загруженном файле
   /* echo "<h3>Информация о загруженном на сервер файле: </h3>";
    echo "<p>Оригинальное имя загруженного файла:<b> ".$_FILES['uploadfile']['name']."</b></p>";
    echo "<p>Mime-тип загруженного файла:<b> ".$_FILES['uploadfile']['type']."</b></p>";
    echo "<p>Размер загруженного файла в байтах:<b> ".$_FILES['uploadfile']['size']."</b></p>";
    echo "<p>Временное имя файла: <b>".$_FILES['uploadfile']['tmp_name']."</b></p>";
    */
    return $imageurl;
}

function ShowTags($noteid = NULL) 
{
	global $db;
	if ($noteid == NULL)
	{
		$SQL = "SELECT * FROM метки";
		if ($result = mysqli_query($db, $SQL)) 
		{
			$tags_html = "";
			while ($row = mysqli_fetch_assoc($result)) 
			{	
				$tags_html = $row['метка']." ".$tags_html;
			}
		}
		return $tags_html;
	}
	else
	{
		$SQL = "SELECT `метки`.`метка` FROM `метки` JOIN `заметки метки` ON `метки`.`код метки` = `заметки метки`.`код метки` WHERE `заметки метки`.`код заметки` =".$noteid;
		if ($result = mysqli_query($db, $SQL)) 
		{
			$tags_html = "";
			while ($row = mysqli_fetch_assoc($result)) 
			{	
				$tags_html = $row['метка']." ".$tags_html;
			}
		}
		return $tags_html;;
	}
}
