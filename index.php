<?php
	session_start();
	if (isset($_SESSION['logged_user_id']))
	{
		#require 'pages/main.php';
		header('Location: /edsa-instruments/pages/main.php');
	}
	else
	{
		#require 'pages/about.php';
		header('Location: /edsa-instruments/pages/about.php');
	}
?>