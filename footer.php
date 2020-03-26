<?php
// возможность выхода
if (isset($_SESSION['id'])) 
	{ 
		print "<p align=center><a href='exit.php'>Выход для пользователя с логином ".$_SESSION['login']."</a></p>"; 
	}
?>
</body>
</html>
