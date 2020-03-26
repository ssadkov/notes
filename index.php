<?php session_start(); $title = "Сервис заметок1"; require_once "header.php"; StartPage(); ?>

<?php StartDB(); ?>

<?php // GetDB(); ?>

<h2>Метки</h2>
<?php CheckLogin(); ?>


<?php EndDB(); ?>

<?php EndPage(); require_once "footer.php";  ?>
