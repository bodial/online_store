<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
	require 'header_menu.php';
	
	$order_number = $_GET['number'];
	$order_info = check_user_order($link, $_SESSION['logged_user_id'], $order_number);
	#show_array($order_info);
	if ($order_info or ($_SESSION['logged_user_id'] == $ADMIN_id))
	{
		echo '<h2>Номер заказа ' . $order_number . '</h2>';	
		echo 'Статус: ';
		if ($order_info['type'] == 'orders_in_process'):
			echo 'в доставке<br>';
		else:
			echo 'завершен<br>';
		endif;
		echo 'Дата заказа: ' . $order_info['date'] . '<br>';
		read_order_table($link, $_SESSION['logged_user_id'], $order_info['positions']);
		echo 'Стоимость ' . $order_info['price'] . '<br>';	
		
	}
	else
	{
		header('Location: ../pages/doesnt_exist.php');
	}

	
?>




  </body>
</html>