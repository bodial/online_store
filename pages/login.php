<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
?>


<?php
	#$data = &_POST; #есть вариант копировать $_POST в отдельный массив и работать с ним
	if (isset($_POST['login_data']))
	{
		#нажали кнопку
		$errors = array();
		if (trim($_POST['email']) == '')
		{
			$errors[] = 'введите почту';
		}
		if (trim($_POST['password']) == '')
		{
			$errors[] = 'введите пароль';
		}
		$user_id = (login($link, $_POST['email'], $_POST['password']));
		if ($user_id == 0)
		{
			$errors[] = 'Неверный логин/пароль';
		}
		if (empty($errors))
		{
			#ошибок нет, можно заходить
			$login = true;
			#echo '<div style="color: green">Вы успешно вошли в аккаунт</div><br>';
			$_SESSION['logged_user_id'] = $user_id;
			#echo 'Перейти в <a href = "/edsa-instruments/pages/cab.php">личный кабинет</a>';
			header('Location: /edsa-instruments/pages/cab.php');
		}
		else
		{
			#есть ошибки
			$login = false;
			#echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
			#show_array($errors);
		}
	}
	else
	{
		#кнопку не нажимали
	}
	
	require 'header_menu.php';	
?>	

	<h1>Вход</h1>
	<form action="login.php" method="POST">
		<p>
			<strong>Почта</strong>
			<input type="email" name="email" value='<?php if (isset($_POST['email'])) echo $_POST['email'] ?>'>
		</p>
		<p>
			<strong>Пароль</strong>
			<input type="password" name='password' >
		</p>
		<button type='submit' name='login_data'>Войти</button>
	</form>
<?php
	if (isset($login) and (!$login))
	{
		echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
	}

?>
	
	<p><a href = "/edsa-instruments/pages/recovery.php">Забыли пароль?</a></p>
	<p><a href = "/edsa-instruments/pages/sign_up.php">Создать новый аккаунт</a></p>
  </body>
</html>