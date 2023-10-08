<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
	
	if (isset($_POST['new_amounts_of_instruments_data']))
	{
		echo 'Получил данные';
		#show_array($_POST);
		#show_array($_POST['new_amount_of_instr_id']);
		#$array_of_amounts_of_instruments = create_array_of_amounts_of_instruments($_POST);
		add_new_amounts_of_instruments_in_cart($link, $_SESSION['logged_user_id'], $_POST['new_amount_of_instr_id']);
	}
	if (isset($_POST['order_data']))
	{
		#нажали кнопку заказать
		$order = true;
		#array_from_cart($link, $_SESSION['logged_user_id']);
		create_processing_order($link, $_SESSION['logged_user_id']);
		#send_order($link, $_SESSION['logged_user_id']);
		#clear_cart()

	}
	require 'header_menu.php';

	#echo 'Корзина для пользователя с id ' . $_SESSION['logged_user_id'] . '<br>';
	$prices_of_every_instrument = read_user_cart_table($link, $_SESSION['logged_user_id']);
	$how_many_positions_in_cart = count($prices_of_every_instrument);
	#echo 'prices_of_every_instrument<br>';
	#show_array($prices_of_every_instrument);

	#if (!cart_empty($link, $_SESSION['logged_user_id'])):
	if ($how_many_positions_in_cart>0):
?>


	<form action="cart.php" method="POST">
		<button type='submit' name='order_data'>Заказать</button>
	</form>
	
	<form id="new_amounts_of_instruments" action="cart.php" method="POST"> 
		<input class="hidden" name="how_many_positions_in_cart" value='<?php if (isset($how_many_positions_in_cart)) echo $how_many_positions_in_cart ?>'>
		<button type='submit' class="hidden" name="new_amounts_of_instruments_data"></button>
	</form>
	
	<script>
		var length = <?php echo $how_many_positions_in_cart ?>;
		var prices_of_instruments = <?php echo json_encode($prices_of_every_instrument); ?>;

		initialize_cart_data(length, prices_of_instruments);
		
	</script>
	
<?php endif ?>	
	
	
<?php
	if (isset($order))
	{
		echo 'Ваш заказ отправлен, вы можете посмотреть информацию по нему в личном кабинете<br>';
	}
?>
	</body>
</html>