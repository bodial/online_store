<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
?>


<?php
	if (isset($_POST['change_email_data']))
	{
		#нажали кнопку
		$errors = array();
		if (trim($_POST['new_email']) == '')
		{
			$errors[] = 'введите почту';
		}
		if (empty($errors))
		{
			#ошибок нет, можно менять почту
			$change_email = true;
			change_email($link, $_SESSION['logged_user_id'], $_POST['new_email']);
			#echo 'Перейти в <a href = "/edsa-instruments/pages/cab.php">личный кабинет</a>';
			header('Location: /edsa-instruments/pages/cab.php');
		}
		else
		{
			#есть ошибки
			$change_email = false;
			#show_array($errors);
		}
	}
	else
	{
		#кнопку не нажимали
	}
	
	require 'header_menu.php';	
?>	

	<h1>Смена почты</h1>
	<form action="change_email.php" method="POST">
		<p>
			<strong>Новая почта</strong>
			<input type="email" name="new_email">
		</p>
		<button type='submit' name='change_email_data'>Сменить</button>
	</form>
	
<?php
	if (isset($change_email) and (!$change_email))
	{
		echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
	}

?>

  </body>
</html>