<?php session_start(); $title = "Список заметок"; require_once "header.php"; ?>

<div id="wrapper">
<div id="header">
	<h2><?php print $title; ?></h2>
</div> 

<div id="content">
	
<?php	
	StartDB();
	AddNotesDB();
	EditNotesDB();
	FilterNotesDB();
	EndDB();
?>
<a href= "index.php">На главную</a>	
</div>
<div id="footer">
</div>

</div>

<?php require_once "footer.php"; ?>
