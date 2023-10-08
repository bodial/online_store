<?php
		require 'header_menu.php';
		require 'sql_functions.php';
		require 'db_connect.php';
		if (isset($_GET['instrument_id']))
		{
			$instrument_id = $_GET['instrument_id'];
		}
		else
		{
			$instrument_id = 0;
		}
		#echo '$instrument_id = ' . $instrument_id;
		read_single_instrument_name_image_price($link, $instrument_id);
		if (isset($_POST['cart_options_data'])):
			#нажали на кнопку 'В корзину'
			#$amount =  how_many_instrument_in_cart($link, $instrument_id, $_SESSION['logged_user_id']);
			if (isset($_SESSION['logged_user_id']))
			{
				#юзер зарегестрирован
				if (isset($_POST['amount']))
				{
					#нажата кнопка добавить amount элементов в корзину
					#echo '$instrument_id ' . $instrument_id .  ' $_SESSION["logged_user_id"] ' . $_SESSION['logged_user_id'] . ' $_POST["amount"] ' . $_POST['amount'];
					edit_instrument_in_cart($link, $instrument_id, $_SESSION['logged_user_id'], how_many_instrument_in_cart($link, $instrument_id, $_SESSION['logged_user_id']), $_POST['amount']);
				}
				else
				{
					#кнопку добавить amount элементов в корзину еще не нажимали
				}
				
?>

	<form action="single_instrument.php?instrument_id=<?php echo $instrument_id ?>" method="POST">
		<p>
			<strong>Корзина</strong>
		</p>
		<p>
			<input style="width: 25px; type="text" name="amount" value=<?php echo how_many_instrument_in_cart($link, $instrument_id, $_SESSION['logged_user_id']) ?> >
			<button type='submit' name='cart_options_data'>В корзину</button>
		</p>
	</form>

<?php
			}
			else
			{
				#юзер не зареган
				echo '<p>Вы не авторизованы</p>';
				echo "<a href='login.php'>Авторизация</a><br>";
			}
		else:
			#кнопку 'В корзину' еще не нажали
?>	
		
	<form action="single_instrument.php?instrument_id=<?php echo $instrument_id ?>" method="POST">
		<button type='submit' name='cart_options_data'>В корзину</button>
	</form>
	
<?php 	endif ?>

<?php
	read_single_instrument_parameters($link, $instrument_id);
?>

	</body>
</html>