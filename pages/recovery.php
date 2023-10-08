<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
?>


<?php
	if (isset($_POST['recovery_data']))
	{
		#нажали кнопку
		$errors = array();
		if (trim($_POST['email']) == '')
		{
			$errors[] = 'введите почту';
		}
		$check_email = check_email($link, $_POST['email']);
		if (!$check_email)
		{
			$errors[] = 'Такая почта не зарегестрирована';
		}
		if (empty($errors))
		{
			#ошибок нет, можно выдать пароль
			$recovery_password = true;
			#echo 'Перейти в <a href = "/edsa-instruments/pages/cab.php">личный кабинет</a>';
			header('Location: /edsa-instruments/pages/login.php');
		}
		else
		{
			#есть ошибки
			$recovery_password = false;
			#show_array($errors);
		}
	}
	else
	{
		#кнопку не нажимали
	}
	
	require 'header_menu.php';	
?>	

	<h1>Восстановление пароля</h1>
	<form action="recovery.php" method="POST">
		<p>
			<strong>Почта</strong>
			<input type="email" name="email" value='<?php if (isset($_POST['email'])) echo $_POST['email'] ?>'>
		</p>
		<button type='submit' name='recovery_data'>Восстановить</button>
	</form>
	
<?php
	if (isset($recovery_password) and (!$recovery_password))
	{
		echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
	}

?>

  </body>
</html>