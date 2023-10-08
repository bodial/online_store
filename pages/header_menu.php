<?php 
if (!isset($_SESSION))
{
	session_start();
}
#$currrent = $_GET['current'];
if (isset($_SESSION['logged_user_id']))
{
	if ($_SESSION['logged_user_id'] == 18)
	{
		$menu = ["Каталог" , "O компании " , "Панель администрирования"];
		$menu_href = ["pages/catalog", "pages/about", "pages/admin_panel"];
	}
	else
	{
		$menu = ["Каталог" , "O компании " , "Личный кабинет", "Корзина"];
		$menu_href = ["pages/catalog", "pages/about", "pages/cab", "pages/cart"];
	}
}
else
{
	$menu = ["Каталог" , "O компании " , "Личный кабинет"];
	$menu_href = ["pages/catalog", "pages/about", "pages/cab"];
}

?>
<!DOCTYPE html>
<html lang="ru">
	<head>
	    <meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="../styles/style.css">
		<meta charset="utf-8">
		<link rel='icon' href='../images/tildafavicon.ico'>
		<?php
		#echo '<title>' . $menu[$current] . '</title>';
		?>
		<title>bizon tools</title>
		<meta name="description" content="интернет магазин bison tools">
		<meta name="keywords" content="bison tools, инструменты">
		<meta name="author" content="Borovinskiy">
		
	</head>


	<body>
		<script src="../scripts/lib.js"></script>
		<nav class="nav"><ul>
			<?php
/* 			foreach ($menu as $key => $value) {
				if $current = $key{
					echo '<li>' . $value . '</li>';
				}
				else{
					echo '<li><a href="' . $menu_href[$key] . '.php">' . $value . '</a></li>';
				}	
			} */
			foreach ($menu as $key => $value) {
					echo '<li><a href="../' . $menu_href[$key] . '.php">' . $value . '</a></li>';	
			}
			?>
		</ul></nav>
