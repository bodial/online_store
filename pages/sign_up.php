<?php
	require 'header_menu.php';	
	require 'db_connect.php';
	require 'sql_functions.php';

	#$data = &_POST; #есть вариант копировать $_POST в отдельный массив и работать с ним
	if (isset($_POST['sign_up_data']))
	{
		#нажали кнопку
		$errors = array();
		if (trim($_POST['name']) == '')
		{
			$errors[] = 'введите имя';
		}
		if (trim($_POST['email']) == '')
		{
			$errors[] = 'введите почту';
		}
		if (trim($_POST['password']) == '')
		{
			$errors[] = 'введите пароль';
		}
		if ($_POST['password'] != $_POST['repeat_password'])
		{
			$errors[] = 'пароли не совпадают';
		}
		if (email_already_exists($link, $_POST['email']))
		{
			$errors[] = 'Такой email уже заегистрирован';
		}
		if (empty($errors))
		{
			#ошибок нет, можно регистрировать
			$sign_up = true;
			#pre_registration();
			#email_confirm($_POST['email']);
			#registration($link, $_POST['name'], $_POST['email'], $_POST['password']);
			#echo '<div style="color: green">Регистрация прошла успешно!</div><br>';
		}
		else
		{
			$sign_up = false;
			#echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
			#show_array($errors);
		}
	}
	else
	{
		#кнопку не нажимали
	}
?>	
    <h1>Регистрация</h1>

	<form action="sign_up.php" method="POST">
		<p>
			<strong>Имя</strong>
			<input type="text" name="name" value='<?php if (isset($_POST['name'])) echo $_POST['name'] ?>'>
		</p>
		<p>
			<strong>Почта</strong>
			<input type="email" name="email" value='<?php if (isset($_POST['email'])) echo $_POST['email'] ?>'>
		</p>
		<p>
			<strong>Пароль</strong>
			<input type="password" name='password' >
		</p>
		<p>
			<strong>Пароль еще раз</strong>
			<input type="password" name='repeat_password'>
		</p>
		<button type='submit' name='sign_up_data'>Зарегистрироваться</button>
	</form>
	
<?php
	if (isset($sign_up) and (!$sign_up))
	{
		#есть ошибки, выводим их
		echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
		#show_array($errors);
	}
	if (isset($sign_up) and ($sign_up))
	{
		#ошибок нет, можно регистрировать
		delete_preregistrated_email_if_it_already_exists($link, $_POST['email']);
		$un_user_id = preregistration($link, $_POST['name'], $_POST['email'], $_POST['password']);
		$message = 'http://127.0.0.1/edsa-instruments/pages/email_confirm.php?un_user_id=' . $un_user_id;
		send_email('Подтверждение регистрации', $message, $_POST['email']);
		echo '<div style="color: green">Подтвердите письмо на почте!</div><br>';
	}

?>
	
	<p>Уже есть аккаунт? <a href = "../pages/login.php">Войти</a></p>
  </body>
</html>