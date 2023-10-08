<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';


	if (isset($_GET['un_user_id']))
	{
		#перешли по ссылке с почты
		echo 'Номер ' . $_GET['un_user_id'];
		$user_id = from_preregistrated_table_to_registrated_table($link, $_GET['un_user_id']);
		if ($user_id == 0)
		{
			header('Location: /edsa-instruments/pages/cab.php');
		}
		else
		{
			$_SESSION['logged_user_id'] = $user_id;
			header('Location: /edsa-instruments/pages/cab.php');
		}
	}
	else
	{
		#номера юзера нет, лучшше уйти
		header('Location: /edsa-instruments/index.php');
	}
?>