<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
?>


<?php
	if (isset($_POST['change_password_data']))
	{
		#нажали кнопку
		$errors = array();
		if (trim($_POST['new_password']) == '')
		{
			$errors[] = 'введите пароль';
		}
		if ($_POST['new_password'] != $_POST['repeat_password'])
		{
			$errors[] = 'пароли не совпадают';
		}
		if (!correct_password($link, $_SESSION['logged_user_id'], $_POST['old_password']))
		{
			$errors[] = 'Неверный логин/пароль';
		}
		if (empty($errors))
		{
			#ошибок нет, можно менять пароль
			$change_password = true;
			change_password($link, $_SESSION['logged_user_id'], $_POST['new_password']);
			#echo 'Перейти в <a href = "/edsa-instruments/pages/cab.php">личный кабинет</a>';
			header('Location: /edsa-instruments/pages/cab.php');
		}
		else
		{
			#есть ошибки
			$change_password = false;
			#show_array($errors);
		}
	}
	else
	{
		#кнопку не нажимали
	}
	
	require 'header_menu.php';	
?>	

	<h1>Смена пароля</h1>
	<form action="change_password.php" method="POST">
		<p>
			<strong>Старый пароль</strong>
			<input type="password" name="old_password">
		</p>
		<p>
			<strong>Новый пароль</strong>
			<input type="password" name="new_password">
		</p>
		<p>
			<strong>Новый пароль еще раз</strong>
			<input type="password" name='repeat_password' >
		</p>
		<button type='submit' name='change_password_data'>Сменить</button>
	</form>
	
<?php
	if (isset($change_password) and (!$change_password))
	{
		echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
	}

?>

  </body>
</html>