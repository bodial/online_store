<?php
	session_start();
	if (isset($_SESSION['logged_user_id']) AND ($_SESSION['logged_user_id'] == 18))
	{
		header('Location: ../pages/admin_panel.php');
	}
	require 'header_menu.php';
	require 'db_connect.php';
	require 'sql_functions.php';
	if (isset($_SESSION['logged_user_id'])):

		#echo 'Страница личного кабинета для пользователя с id ' . $_SESSION['logged_user_id'] . '<br>';
		#create_processing_order($link, $_SESSION['logged_user_id']);
		$user_info = user_info($link, $_SESSION['logged_user_id']); #возвращает массив строк таблицы бд
		echo '<p>Добрый день, ' . $user_info['personal_data']['name'] . '</p>';
?>
		<!--ДЛЯ ТРЕНИРОВКИ JS-->
		<!--<p>
			JS
			<div id="js_writing"></div>
		</p>-->
		<!--ИНФА О ПОЧТЕ-->
		<p>
			Почта
			<button type="submit" id="show_email">Показать</button>
			<button type="submit" id="hide_email" class="hidden">Скрыть</button>
			<div id="email_hidden" class="hidden"><?php echo $user_info['personal_data']['email'] /*. ' <a href="change_email.php">Изменить</a>'; */?></div>
		</p>
<?php
		#ИНФА ОБ АДРЕСЕ
		#$user_info['address'] = 1;
		if (isset($user_info['personal_data']['street'], $user_info['personal_data']['house'])and !empty($user_info['personal_data']['street'])): 
?>	
			<p>
				Адрес
				<button type="submit" id="show_address">Показать</button>
				<button type="submit" id="hide_address" class="hidden">Скрыть</button>
				<div id="address_hidden" class="hidden"><?php echo 'Улица ' . 
							$user_info['personal_data']['street'] . ', дом ' . 
							$user_info['personal_data']['house'] . '<br><a href="add_address.php">Изменить </a><a href="delete_address.php"> Удалить</a>'; ?></div>
			</p>	
			
<?php   else: ?>	

			<p>
				Адрес доставки не привязан
				<a href="add_address.php">добавить</a>
			</p>
			
<?php 
		endif;

		#ИНФА О ЗАВЕРШЕННЫХ ЗАКАЗАХ
		#$user_info['finished_orders'] = 1;
		#show_array($user_info);
		if (!empty($user_info['finished_orders'])):
?>	
			<p>
				Завершенные заказы
				<button type="submit" id="show_finished_orders">Показать</button>
				<button type="submit" id="hide_finished_orders" class="hidden">Скрыть</button>
				<div id="finished_orders_hidden" class="hidden"><?php foreach ($user_info['finished_orders'] as $number => $order) {show_order_in_cab($number, $order);} ?></div>
			</p>	
			
<?php else: ?>	

			<p>
				Нет завершенных заказов
			</p>
			
<?php 
		endif;

		#ИНФА О ДОСТАВЛЯЮЩИХСЯ ЗАКАЗАХ
		#$user_info['orders_in_process'] = 1;
		#echo $user_info['orders_in_process'];
		if (!empty($user_info['orders_in_process'])):
?>	
			<p>
				Заказы в процессе доставки
				<button type="submit" id="show_orders_in_process">Показать</button>
				<button type="submit" id="hide_orders_in_process" class="hidden">Скрыть</button>
				<div id="orders_in_process_hidden" class="hidden"><?php foreach ($user_info['orders_in_process'] as $number => $order) {show_order_in_cab($number, $order);} ?></div>
			</p>	
			
<?php else: ?>	

			<p>
				Нет доставляющихся заказов
			</p>
			
<?php endif; #show_array($user_info);?>		

		<a href='change_password.php'>Сменить пароль</a><br>
		<a href='logout.php'>Выйти</a><br>
		
<script>
	<!--ПОЧТА-->
	hide_email.addEventListener('click', function(event){
		hide_text_and_btns(email_hidden, hide_email, show_email);
	});
	show_email.addEventListener('click', function(){
		show_text_and_btns(email_hidden, hide_email, show_email);
	});
	<!--АДРЕС-->
	var elementExists = document.getElementById('address_hidden');
	if (elementExists)
	{
		hide_address.addEventListener('click', function(){
			hide_text_and_btns(address_hidden, hide_address, show_address);
		});
		show_address.addEventListener('click', function(){
			show_text_and_btns(address_hidden, hide_address, show_address);
		});
	};
	<!--ЗАВЕРШЕННЫЕ ЗАКАЗЫ-->
	var elementExists = document.getElementById('finished_orders_hidden');
	if (elementExists)
	{
		 hide_finished_orders.addEventListener('click', function(){
			hide_text_and_btns(finished_orders_hidden, hide_finished_orders, show_finished_orders);
		});
		 show_finished_orders.addEventListener('click', function(){
			show_text_and_btns(finished_orders_hidden, hide_finished_orders, show_finished_orders);
		});
	};
	<!--ДОСТАВЛЯЮЩИЕСЯ ЗАКАЗЫ-->
	var elementExists = document.getElementById('orders_in_process_hidden');
	if (elementExists)
	{
		 hide_orders_in_process.addEventListener('click', function(){
			hide_text_and_btns(orders_in_process_hidden, hide_orders_in_process, show_orders_in_process);
		});
		 show_orders_in_process.addEventListener('click', function(){
			show_text_and_btns(orders_in_process_hidden, hide_orders_in_process, show_orders_in_process);
		});
	};
</script>

<?php
	else:
?>
		<p>Вы не авторизованы</p>
		<a href='login.php'>Авторизация</a><br>
		<a href='sign_up.php'>Регистрация</a>
<?php endif; ?>

  </body>
</html>