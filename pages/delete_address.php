<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';

	delete_address($link, $_SESSION['logged_user_id']);
	header('Location: /edsa-instruments/pages/cab.php');
?>

  </body>
</html>