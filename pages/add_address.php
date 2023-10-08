<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
?>


<?php
	if (isset($_POST['add_address_data']))
	{
		#нажали кнопку
		$errors = array();
		if (trim($_POST['street']) == '')
		{
			$errors[] = 'введите название улицы';
		}
		if (trim($_POST['house']) == '')
		{
			$errors[] = 'введите номер дома';
		}
		if (empty($errors))
		{
			#ошибок нет, можно менять пароль
			$add_address = true;
			add_address($link, $_SESSION['logged_user_id'], $_POST['street'], $_POST['house']);
			header('Location: /edsa-instruments/pages/cab.php');
		}
		else
		{
			#есть ошибки
			$add_address = false;
			#show_array($errors);
		}
	}
	else
	{
		#кнопку не нажимали
	}
	
	require 'header_menu.php';	
?>	

	<h1>Добавление адреса</h1>
	<form action="add_address.php" method="POST">
		<p>
			<strong>Улица</strong>
			<input type="text" name="street" value='<?php if (isset($_POST['street'])) echo $_POST['street'] ?>'>
		</p>
		<p>
			<strong>Дом</strong>
			<input type="text" name="house" value='<?php if (isset($_POST['house'])) echo $_POST['house'] ?>'>
		</p>
		<button type='submit' name='add_address_data'>Добавить</button>
	</form>
	
<?php
	if (isset($add_address) and (!$add_address))
	{
		echo '<div style="color: red">' . array_shift($errors) . '</div><br>';
	}

?>

  </body>
</html>