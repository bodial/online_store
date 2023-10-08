<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
	
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
	$cart_full = read_user_cart_table($link, $_SESSION['logged_user_id']);
	
	#if (!cart_empty($link, $_SESSION['logged_user_id'])):
	if ($cart_full):
?>


	<form action="cart.php" method="POST">
		<button type='submit' name='order_data'>Заказать</button>
	</form>
<?php endif ?>	
	
	
<?php
	if (isset($order))
	{
		echo 'Ваш заказ отправлен, вы можете посмотреть информацию по нему в личном кабинете<br>';
	}
?>
	</body>
</html>