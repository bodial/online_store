<?php	
	#for MAILER
	require 'mailer/PHPMailer.php';
	require 'mailer/SMTP.php';
	require 'mailer/Exception.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	
	function show_array($a){
		echo '<pre>';
		print_r($a);
		echo '</pre>';		
	}
	
	function read_types_of_instruments_table($link){
		$sql = 'SELECT id, name, amount FROM types_of_instruments WHERE amount>0';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $number_in_table = 0; #номер инструмента в таблице
		#show_array($rows);
		echo '<table>';
		echo '<tr><th>№</th><th>Название</th><th>Элементов</th></tr>';
		foreach($rows as $row){
            $number_in_table +=1;
			$path = 'instrument.php?id=' . $row["id"];
			echo '<tr><td><a href="' . $path . '">' . $number_in_table . '</a></td><td>' . $row['name'] . '</td><td>' . $row['amount'] . '</td></tr>';
		}
		echo '</table>';
	}
		
	function read_instrument_name($link, $type_id){
		$sql = "SELECT name FROM `types_of_instruments` where id ='" . $type_id . "'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		echo '<header>';
		echo '<h1>' . $row['name'] . '</h1>';
		echo '</header>';
	}
	
	function read_single_instrument_name_image_price($link, $instrument_id){
		$sql = "SELECT name, image, price FROM `instrument` where id ='" . $instrument_id . "'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		echo '<header>';
		echo '<h1>' . $row['name'] . '</h1>';
		echo '</header>';
		$image_path = '../images/' . $row['image'];
		#echo '<a href = "' . $image_path . '"><img height=150 width=200 src= "' . $image_path . '"onmouseover="this.width=400;this.height=300" onmouseout="this.width=200;this.height=150"></a>';
		echo '<a href = "' . $image_path . '"><img  height=150 width=200 src= "' . $image_path . '"></a>';
		echo 'Цена ' . $row['price'] . '<br>';
		#height=50 width=70 
	}
	
	function read_single_instrument_parameters($link, $instrument_id){
		$sql = "SELECT parameters FROM `instrument` where id ='" . $instrument_id . "'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		echo '<p>' . $row['parameters'] . '</p>';
	}
	
	function read_instrument_table($link, $type_id){
		$sql = 'SELECT id, name, article, remain, image FROM `instrument` where type_id = ' . $type_id;
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		echo '<table>';
		echo '<tr><th>№</th><th>Название</th><th>Артикул</th><th>Остаток</th><th>Превью</th></tr>';
		foreach($rows as $row){
			$image_path = '../images/' . $type_id . '/' . $row['image'];
			$path = 'single_instrument.php?instrument_id=' . $row["id"];
			echo '<tr><td><a href="' . $path . '">' . $row['id'] . '</td><td>' . $row['name'] . '</td><td>' . $row['article'] . '</td><td>' . $row['remain'] . '</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></a></td></tr>';
		}
		echo '</table>';
        #onmouseover="this.width=210;this.height=150" onmouseout="this.width=70;this.height=50" - параметры для увеличение при наведении мышкой
	}
	
	function email_already_exists($link, $email){   #true если уже есть такой email
		$sql = 'SELECT user_id FROM users WHERE email = "' . $email . '"';
		$result = mysqli_query($link, $sql);
		#echo mysqli_num_rows($result); #выводит количество строк с таким email
		$row = mysqli_fetch_array($result);
		if (isset($row))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function delete_preregistrated_email_if_it_already_exists($link, $email){  
		$sql = 'DELETE from unregistered_users WHERE email = "' . $email . '"';
		$result = mysqli_query($link, $sql);
	}
	
	function registration($link, $name, $email, $password){
		$sql = 'INSERT INTO users SET email="' . $email . '", name="' . $name . '", password="' . $password . '"';
		$result = mysqli_query($link, $sql);
		$sql = 'SELECT user_id FROM users WHERE email="' . $email . '" and name="' . $name . '" and password="' . $password . '"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		return $row['user_id'];
	}
	
	function preregistration($link, $name, $email, $password){
		$sql = 'INSERT INTO unregistered_users SET email="' . $email . '", name="' . $name . '", password="' . $password . '"';
		$result = mysqli_query($link, $sql);
		$sql = 'SELECT user_id FROM unregistered_users WHERE email="' . $email . '" and name="' . $name . '" and password="' . $password . '"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		return $row['user_id'];
	}
	
	function user_info($link, $user_id){  #Целых 3 sql запроса, надо исправить
		$sql = 'SELECT name, email, street, house FROM users WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		#show_array($row);
		$rows_new['personal_data'] = [
		'name' => $row['name'],
		'email' => $row['email'],
		'street' => $row['street'],
		'house' => $row['house'],
		];
		$sql = 'SELECT order_number, price, date FROM finished_orders WHERE user_id = "' . $user_id . '"  ORDER BY date';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$rows_new['finished_orders'] = '';
		#show_array($rows_new);
		foreach($rows as $row)
		{
			#show_array($row);
			$rows_new['finished_orders'][$row["order_number"]] = [
			'price' => $row['price'],
			'date' => $row['date'],
			];
		}
		$sql = 'SELECT order_number, price, date FROM orders_in_process WHERE user_id = "' . $user_id . '"  ORDER BY date';
		/*$sql = 'SELECT orders_in_process.order_number, orders_in_process.price, orders_in_process.positions, 
		orders_in_process.date, finished_orders.order_number, finished_orders.price, finished_orders.positions, 
		finished_orders.date FROM orders_in_process, finished_orders WHERE finished_orders.user_id = "1" and orders_in_process.user_id = "1"';*/
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$rows_new['orders_in_process'] = '';
		show_array($rows_new);
		foreach($rows as $row)
		{
			#show_array($row);
			$rows_new['orders_in_process'][$row["order_number"]] = [
			'price' => $row['price'],
			'date' => $row['date'],
			];
		}
		#show_array($rows_new);
		#$rows_new['orders_in_process']
		return $rows_new;
	}
	
	function user_info_old_ver($link, $user_id){
		$sql = 'SELECT name, email, street, house FROM users WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		#show_array($row);
		$rows_new['personal_data'] = [
		'name' => $row['name'],
		'email' => $row['email'],
		'street' => $row['street'],
		'house' => $row['house'],
		];
		#$sql = 'SELECT users.user_id, name, email, street, house, instrument_id, amount FROM users, cart WHERE users.user_id = cart.user_id and users.user_id = "' . $user_id . '"';
		$sql = 'SELECT order_number, price, positions, date FROM orders_in_process WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$rows_new['orders_in_process'] = '';
		#show_array($rows_new);
		foreach($rows as $row)
		{
			#show_array($row);
			$rows_new['orders_in_process'][$row["order_number"]] = [
			'price' => $row['price'],
			'positions' => $row['positions'],
			'date' => $row['date'],
			];
		}
		#show_array($rows_new);
		#$rows_new['orders_in_process']
		return $rows_new;
	}
	
	function show_order_in_cab($number, $order){
		echo 'Номер заказа: <a href = "order.php?number=' . $number . '">' . $number . '</a>, Стоимость: ' . $order['price'] . ', Дата: ' . $order['date'] . '<br>';
	}
	
	function from_preregistrated_table_to_registrated_table($link, $un_user_id){
		$sql = 'SELECT name, email, password FROM unregistered_users WHERE user_id="' . $un_user_id . '"';
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return 0;
		}
		else
		{
			$row = mysqli_fetch_array($result);
			$user_id = registration($link, $row['name'], $row['email'], $row['password']);
			$sql = 'DELETE from unregistered_users WHERE user_id = "' . $un_user_id . '"';
			$result = mysqli_query($link, $sql);
			return $user_id;
		}
	}
	
	function login($link, $email, $password){ #возвращает id пользователя или 0 если такого нет
		$sql = 'SELECT user_id FROM users WHERE email = "' . $email . '" AND password = "' . $password . '"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		if (isset($row))
		{
			#такой пользователь нашелся
			return $row['user_id'];
		}
		else
		{
			return 0;
		}
	}
	
	function db_connect(){
		$link = mysqli_connect("localhost", "root", "", "instruments");
		mysqli_set_charset($link, "utf8");
		return $link;
	}
	
	/*function cart_empty($link, $user_id){ #true если корзина пустая, false если полная
		$sql = 'SELECT instrument_id, amount FROM `cart` where user_id = ' . $user_id;
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}*/
	
	/*function read_user_cart_table($link, $user_id){
		if (cart_empty($link, $user_id))
		{
			echo 'Корзина пустая';
		}
		else
		{
			$instuments_info_from_cart = array_from_cart($link, $user_id);
			$i = 0; #счетчик строки
			$full_price = 0; #стоимость всей корзины
			echo '<table>';
			echo '<tr><th>№</th><th>Название</th><th>Артикул</th><th>Превью</th><th>Заказано</th><th>Стоимость</th></tr>';
			foreach($instuments_info_from_cart as $instument_id => $instrument){
				#каждая строка корзины
				$i+=1;
				$price = $instrument['price']*$instrument['amount'];
				$full_price += $price;
				$image_path = '../images/' . $instrument['type_id'] . '/' . $instrument['image'];
				$path = 'single_instrument.php?instrument_id=' . $instument_id;
				echo '<tr><td><a href = "' . $path . '">' . $i . '</td><td>' . $instrument['name'] . '</td><td>' . $instrument['article'] . 
				'</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></td></a><td>' . $instrument['amount'] . '</td><td>' . $price . '</td></tr>';
			}
			echo '</table>';
			echo '<p>Суммарная цена: ' . $full_price . '</p>';
		}
	}*/
	
	function send_order($link, $user_id){
		#формирование начала письма(имя, почта) юзера сделававшего заказ
		$sql = 'SELECT name, email FROM users WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		$user = mysqli_fetch_array($result);
		#$message = 'Заказ для пользователя ' . $user['name'] . ' (почта ' . $user['email'] . ")\n";
		$message = '';
		
		#поиск инструментов из корзины
		$instuments_info_from_cart = array_from_cart($link, $user_id);
		$full_price = 0;
		if ($instuments_info_from_cart['amount_of_elements'] == 1)
		{
			#доделать
		}
		else{
			foreach($instuments_info_from_cart['elements_info'] as $instrument){
				$full_price += $instrument['amount']*$instrument['price'];
				$message .= 'Артикул ' . $instrument['article'] . ' в количестве ' . $instrument['amount'] . ' цена ' . $instrument['amount']*$instrument['price'] . "\n";
			}
		}
		$message .= 'Полная цена ' . $full_price;
		$subject = 'Заказ на сайте bison tools';
		send_email($subject, $message, $user['email']);
		$f = fopen('../email/email.txt', 'w');
		fwrite($f, $message);
		fclose($f);
		#echo $message;
	}
	
	function send_email($subject, $message, $email){
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$yourEmail = 'php.stuff@yandex.ru'; // ваш email на яндексе
		$password = 'gowczvfvwciewxyh'; // ваш пароль к яндексу или пароль приложения

		// настройки SMTP
		#$mail->Mailer = 'smtp';
		$mail->isSMTP();
		$mail->Host = 'smtp.yandex.ru';
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';
		$mail->SMTPAuth = true;
		$mail->Username = $yourEmail; // ваш email - тот же что и в поле From:
		$mail->Password = $password; // ваш пароль;
		
		// формируем письмо
		// от кого: это поле должно быть равно вашему email иначе будет ошибка
		$mail->setFrom($yourEmail, 'Bison Instruments');
		$mail->addAddress($email, 'Имя Получателя');  // кому 1
		#$mail->addAddress('bodial-2000@yandex.ru', 'Дима');  // кому 2
		$mail->Subject = $subject;  // тема письма
		/*$mail->msgHTML("<html><body>
						<h1>Проверка связи!</h1>
						<p>Это тестовое письмо.</p>
						</html></body>");*/
		$mail->Body = $message;  // тема письма				
		if ($mail->send()) { // отправляем письмо
			#echo 'Письмо отправлено!';
		} else {
			echo 'Ошибка: ' . $mail->ErrorInfo;
		}
	}
	
	function array_from_cart($link, $user_id){
		$sql = 'SELECT instrument_id, amount FROM cart WHERE status = "cart" and user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return false;
		}
		else
		{
			$instuments_info_from_cart['amount_of_elements'] = mysqli_num_rows($result);
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			#$full_price = 0;
			foreach($rows as $row){
				$sql = 'SELECT name, article, image, type_id, price, path FROM `instrument` where id = ' . $row['instrument_id'];
				$result = mysqli_query($link, $sql);
				$instrument_info = mysqli_fetch_array($result);
				$instuments_info_from_cart['elements_info'][$row['instrument_id']] = [
				'amount' => $row['amount'],
				'id' => $row['instrument_id'],
				'name' => $instrument_info['name'],
				'article' => $instrument_info['article'],
				'image' => $instrument_info['image'],
				'type_id' => $instrument_info['type_id'],
				'path' => $instrument_info['path'],
				'price' => $instrument_info['price']
				];
				#$full_price += $instrument_info['price'];
			}
			#$instuments_info_from_cart['full_price'] = $full_price; #полная цена корзины
			#show_array($instuments_info_from_cart);
			return $instuments_info_from_cart;
		}
	}
	
	function change_password($link, $user_id, $new_password){
		#пароль правильный, можно менять
		$sql = 'UPDATE users SET password = "' . $new_password . '" WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		#echo '<p>Пароль успешно заменен</p>';
	}
	
	function change_email($link, $user_id, $new_email){
		$subject = 'Смена почты на сайте bison tools';
		$message = 'Для завершения процедуры перейдите по ссылке\r\n ';
		$message .= '../pages/change_email.php? ';
		send_email($subject, $message, $new_email);
		$sql = 'UPDATE users SET password = "' . $new_password . '" WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		#echo '<p>Пароль успешно заменен</p>';
	}
	
	function correct_password($link, $user_id, $password){ #true если пароль правильный, false если нет
		$sql = 'SELECT user_id FROM users WHERE user_id = "' . $user_id . '" AND password = "' . $password . '"';
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			#пароль неверный
			return false;
		}
		else
		{
			#пароль верный
			return true;
		}
	}
	
	function check_email($link, $email){ #true если пароль правильный, false если нет
		$sql = 'SELECT user_id, password FROM users WHERE email = "' . $email . '"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		if (mysqli_num_rows($result) == 0)
		{
			#такой почты нет
			return false;
		}
		else
		{
			#почта есть
			$subject = 'Восстановление пароля на сайте bison tools';
			$message = 'Ваш пароль: ' . $row['password'];
			send_email($subject, $message, $email);
			return true;
		}
	}
	
	function cart_options($link, $instrument_id, $user_id){
		$amount = how_many_instrument_in_cart($link, $instrument_id, $user_id);
		#echo '$amount ' . $amount;
		#echo '<br>функция cart_options<br>';
		if (isset($_POST['cart_instrument_amount_data']))
		{
			echo 'получены данные от формы cart_instrument_amount_data<br>';
			edit_instrument_in_cart($link, $instrument_id, $user_id, $add_amount);
		}
	}
	
	function edit_instrument_in_cart($link, $instrument_id, $user_id, $amount, $new_amount){
		if ($new_amount > 0)
		{
			#новое количетво больше 0
			if (empty($amount))
			{
				#такого инструмента еще нет в корзине, добавляем новую строку
				$sql = 'INSERT INTO cart SET status = "cart", instrument_id="' . $instrument_id . '", user_id="' . $user_id . '", amount="' . $new_amount . '"';
				$result = mysqli_query($link, $sql);
			}
			else
			{
				#такой инструмент уже есть в корзине в каком-то количестве, редактируем строку
				$sql = 'UPDATE cart SET status = "cart", amount ="' . $new_amount . '" WHERE user_id = "' . $user_id . '" and instrument_id ="' . $instrument_id . '"';
				$result = mysqli_query($link, $sql);	
			}	
		}
		else
		{
			#обнуляем строку
			if (empty($amount))
			{
				#такой строки не было, обнулять нечего
			}
			else
			{
				#такая строка есть, удаляю
				$sql = 'DELETE from cart WHERE status = "cart", user_id = "' . $user_id . '" and instrument_id ="' . $instrument_id . '"';
				$result = mysqli_query($link, $sql);
			}
		}
		#return $new_amount;
	}
	
	function how_many_instrument_in_cart($link, $instrument_id, $user_id){ #возвращает количество данной позиции в корзине
		$sql = 'SELECT amount FROM cart WHERE status = "cart" and user_id = "' . $user_id . '" AND instrument_id = "' . $instrument_id . '"';
		#echo $sql;
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		if (mysqli_num_rows($result) == 0)
		{
			return 0;
		}
		else
		{
			return $row['amount'];
		}
	}
	
	function create_processing_order($link, $user_id){
		#echo ini_get('date.timezone') . '<br>';	
		#echo date('\t\i\m\e H:m:s \d\a\t\e d/m/y') . '<br>';
		$order_number = $user_id . date("zHis");  #составление номера заказа
		#echo 'Номер заказа ' . $order_number . '<br>';
		$sql = 'SELECT cart.id, cart.amount, instrument.price FROM instrument, cart WHERE cart.status = "cart" and cart.instrument_id = instrument.id and cart.user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$cart_id_in_order = ""; #список элементов корзины в заказе по их id
		$order_price = 0; #полная цена заказа
		foreach ($rows as $row)
		{
			$order_price += ($row['amount']*$row['price']);
			$cart_id_in_order .= $row['id'];
			$cart_id_in_order .= " ";
		}
			
		$cart_id_in_order = substr($cart_id_in_order, 0, -1); #удаление последнего пробела
		$date = date("Y-m-d"); #время заказа
		#echo 'cart_id_in_order ' . $cart_id_in_order . '<br>';
		#echo 'order_price ' . $order_price . '<br>';
		$sql = 'INSERT INTO orders_in_process SET order_number="' . $order_number . '", user_id="' . $user_id . '", price="' . $order_price . '", positions="' . $cart_id_in_order . '", date="' . $date . '"';
		#echo 'сложный sql<br>';
		#echo $sql . '<br>';
		$result = mysqli_query($link, $sql);
		$sql = 'UPDATE cart SET status="process" WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
		return true;
	}
	
	function add_address($link, $user_id, $street, $house){
		$sql = 'UPDATE users SET street="' . $street . '", house="' . $house . '" WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);
	}
	
	function delete_address($link, $user_id){
		$sql = 'UPDATE users SET street="", house="" WHERE user_id = "' . $user_id . '"';
		$result = mysqli_query($link, $sql);		
	}
	
	#FOR CATALOG VERSION 2
	/*function read_catalog_table($link, $path){
		if (empty($path))
		{
			$sql = 'SELECT name, amount_of_elements, path FROM catalog WHERE path LIKE "' . $path . '%" and path not like "' . $path . '%/%"';
		}
		else	
		{
			$sql = 'SELECT name, amount_of_elements, path FROM catalog WHERE path LIKE "' . $path . '/%" and path not like "' . $path . '/%/%"';
		}
		#echo $sql;
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return false;
		}
		else
		{
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			echo '<table>';
			echo '<tr><th>Название</th><th>Элементов</th></tr>';
			foreach($rows as $row){
				$href_path = 'catalog.php?path=' . $row['path'];
				echo '<tr><td><a href="' . $href_path . '">' . $row['name'] . '</td><td>' . $row['amount_of_elements'] . '</td></tr>';
			}
			echo '</table>';
			return true;
		}
	}*/

	function read_catalog_table($link, $path){
		if (empty($path))
		{
			$sql = 'SELECT name, amount_of_elements, path FROM catalog WHERE path LIKE "' . $path . '%" and path not like "' . $path . '%/%"';
		}
		else	
		{
			$sql = 'SELECT name, amount_of_elements, path FROM catalog WHERE path LIKE "' . $path . '/%" and path not like "' . $path . '/%/%"';
		}
		#echo $sql;
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		echo '<table>';
		echo '<tr><th>Название</th><th>Элементов</th></tr>';
		foreach($rows as $row){
			$href_path = 'catalog.php?path=' . $row['path'];
			echo '<tr><td><a href="' . $href_path . '">' . $row['name'] . '</td><td>' . $row['amount_of_elements'] . '</td></tr>';
		}
		echo '</table>';
	}
	
	function type_of_page_in_catalog($link, $path){
		if (empty($path))
		{
			return 0;
		}
		else
		{
			$sql = 'SELECT page_type FROM catalog WHERE path = "' . $path . '"';
			$result = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($result);
			return $row['page_type'];
		}
	}
	
	function read_last_page_catalog_table($link, $path){
		$sql = 'SELECT id, name, article, remain, image, path, type_id FROM instrument WHERE path LIKE "' . $path . '/%" and path not like "' . $path . '/%/%"';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$number = 0;
		echo '<table>';
		echo '<tr><th>№</th><th>Название</th><th>Артикул</th><th>Остаток</th><th>Превью</th></tr>';
		foreach($rows as $row){
			$number += 1;
			$image_path = '../images/' . $row['image'];
			$href_path = 'catalog.php?path=' . $row['path'];
			echo '<tr><td>' . $number	 . '</td><td><a href="' . $href_path . '">' . $row['name'] . '</td><td>' . $row['article'] . '</td><td>' . $row['remain'] . '</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></a></td></tr>';
		}
			
			#echo '<tr><td><a href="' . $href_path . '">' . $row['id'] . '</td><td>' . $row['name'] . '</td><td>' . $row['article']
		echo '</table>';
	}
	
	function read_single_instrument_name_image_price_ver_2($link, $path){
		$sql = "SELECT name, image, type_id, price FROM `instrument` where path ='" . $path . "'";
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		echo '<header>';
		echo '<h1>' . $row['name'] . '</h1>';
		echo '</header>';
		$image_path = '../images/' . $row['type_id'] . '/' . $row['image'];
		echo '<a href = "' . $image_path . '"><img height=150 width=200 src= "' . $image_path . '"onmouseover="this.width=400;this.height=300" onmouseout="this.width=200;this.height=150"></a>';
		echo 'Цена ' . $row['price'] . '<br>';
		#height=50 width=70 
	}
	
	function create_path_elements_names($link, $path_elements_hrefs){
		$sql = 'SELECT name, path, page_type FROM catalog where path in (';
		for ($i=0; $i<count($path_elements_hrefs); $i++){
			if ($i == (count($path_elements_hrefs)-1))
			{
				$sql .= '"' . $path_elements_hrefs[$i] . '")';
			}
			else
			{
				$sql .= '"' . $path_elements_hrefs[$i] . '", ';
			}
		}
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $rows;
	}

	/*function check_user_order_old_ver($link, $user_id, $order_number, $order_type){
		$sql = 'select positions from ' . $order_type . ' where user_id = ' . $user_id . ' and order_number = ' . $order_number;
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return false;
		}
		else
		{
			$row = mysqli_fetch_array($result);
			return $row['positions'];
		}	
	}*/
	
	function check_user_order($link, $user_id, $order_number){
		$sql = 'select positions, date, finish_date, price from finished_orders where user_id = ' . $user_id . ' and order_number = ' . $order_number;
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_array($result);
			$row['type'] = 'finished_orders';
			#return $row['positions'];
			return $row;
		}
		$sql = 'select positions, date, price from orders_in_process where user_id = ' . $user_id . ' and order_number = ' . $order_number;
		$result = mysqli_query($link, $sql);	
		if (mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_array($result);
			$row['type'] = 'orders_in_process';
			#return $row['positions'];
			return $row;
		}
		return false;
	}
	
	/*function read_order_table_old_ver($link, $user_id, $positions){
		$elements = explode(' ', $positions);
		$positions = implode($elements, ',');
		$sql = 'SELECT cart.amount, instrument.name, instrument.image, instrument.price, instrument.path FROM cart, instrument where cart.instrument_id = instrument.id and cart.id in (' . $positions . ')';
		#echo '<br>' . $sql;
		$result = mysqli_query($link, $sql);		
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		#show_array($rows);
		$full_price = 0; #стоимость всей корзины
		$element_price = 0; #стоимость текущего товара
			echo '<table>';
			echo '<tr><th>№</th><th>Название</th><th>Превью</th><th>Цена</th><th>Заказано</th><th>Стоимость</th></tr>';
			foreach($rows as $i => $row){
				#каждая строка заказа
				$element_price = $row['price']*$row['amount'];
				$full_price += $element_price;
				$image_path = '../images/' . $row['image'];
				$href_path = '../pages/catalog.php?path=' . $row['path'];
				echo '<tr><td>' . $i . '</td><td><a href = "' . $href_path . '">' . $row['name'] . '</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></a></td>
				<td>' . $row['price'] . '</td><td>' . $row['amount'] . '</td><td>' . $element_price . '</td></tr>';
			}
			echo '</table>';
			echo '<p>Итого: ' . $full_price . '</p>';
			return true;
	}*/
	
	function read_order_table($link, $user_id, $positions){
		$elements = explode(' ', $positions);
		$positions = implode($elements, ',');
		$sql = 'SELECT cart.amount, instrument.name, instrument.image, instrument.price, instrument.path FROM cart, instrument where cart.instrument_id = instrument.id and cart.id in (' . $positions . ')';
		#echo '<br>' . $sql;
		$result = mysqli_query($link, $sql);		
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		#show_array($rows);
		$full_price = 0; #стоимость всей корзины
		$element_price = 0; #стоимость текущего товара
			echo '<table>';
			echo '<tr><th>№</th><th>Название</th><th>Превью</th><th>Заказано</th></tr>';
			foreach($rows as $i => $row){
				#каждая строка заказа
				$element_number = $i + 1;
				$element_price = $row['price']*$row['amount'];
				$full_price += $element_price;
				$image_path = '../images/' . $row['image'];
				$href_path = '../pages/catalog.php?path=' . $row['path'];
				echo '<tr><td>' . $element_number . '</td><td><a href = "' . $href_path . '">' . $row['name'] . '</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></a></td>
				<td>' . $row['amount'] . '</td></tr>';
			}
			echo '</table>';
			return true;
	}
	
	function bread_crumbs($link, $path){
		echo '<a href="../pages/catalog.php">' . 'Все инструменты' . '</a>';
		if (empty($path))
		{
			return true;
		}
		else
		{
			$path_elements_hrefs = explode('/', $path);
			for ($i=1; $i<count($path_elements_hrefs); $i++){
				$path_elements_hrefs[$i] = $path_elements_hrefs[$i-1] . '/' . $path_elements_hrefs[$i];
			}			
			$path_elements_names = create_path_elements_names($link, $path_elements_hrefs);
			
			foreach ($path_elements_hrefs as $key => $value){
				if (empty($path_elements_names[$key]['name']))
				{
					$path_elements_names[$key]['name'] = 'инструмент';
				}
				echo '--<a href="../pages/catalog.php?path=' . $value . '">' . $path_elements_names[$key]['name'] . '</a>';	
			}
							
			return true;
		}
	}

	function add_new_amounts_of_instruments_in_cart($link, $user_id, $new_amount_of_instr_id){
		/*$sql = "UPDATE cart SET amount = (case when instrument_id = '1' then '105'
											   when instrument_id = '2' then '106'
										  end)
				WHERE user_id = '1' AND status = 'cart';";*/
		$sql = 'UPDATE cart SET amount = (case ';
		foreach($new_amount_of_instr_id as $id => $amount)
		{
			$sql .= 'when instrument_id = "' . $id . '" then "' .$amount  . '" ';
		}
		$sql .= 'end) WHERE user_id = ' . $user_id . ' AND status = "cart";';
		#echo $sql;
		$result = mysqli_query($link, $sql);
		
		$sql = 'DELETE from cart WHERE amount = "0"';
		$result = mysqli_query($link, $sql);
	}
	
				/* <button type="submit" id="show_finished_orders">Показать</button>
				<button type="submit" id="hide_finished_orders" class="hidden">Скрыть</button>
				<div id="finished_orders_hidden" class="hidden"><?php foreach ($user_info['finished_orders'] as $number => $order) {show_order_in_cab($number, $order);} ?></div> 
				
				function show_order_in_cab($number, $order){
					echo 'Номер заказа: <a href = "order.php?number=' . $number . '">' . $number . '</a>, Стоимость: ' . $order['price'] . ', Дата: ' . $order['date'] . '<br>';*/
	
	
	function show_orders_in_dates_in_admin_panel($orders_info){
		$HTMLtext = '';
		foreach($orders_info as $date => $orders_in_one_date)
		{
			$table_with_orders_on_this_date = orders_on_this_date($orders_in_one_date);
			#show_array($orders_in_one_date);
			$show = '<button type="submit" id="show_finished_order_date' . $date . '">Показать</button>';
			$hide = '<button type="submit" id="hide_finished_order_date' . $date . '" class="hidden">Скрыть</button><br>';
			#$hidden_info = '<div id="finished_orders_hidden_date' . $date . '" class="hidden">some text about this date</div>';
			$hidden_info = '<div id="finished_orders_hidden_date' . $date . '" class="hidden">' . $table_with_orders_on_this_date . '</div>';
			$HTMLtext .= $date . ' ' . $show . $hide . $hidden_info . '<br>';
		}

		#$HTMLtext = $date . ' ' . $show . $hide . $hidden_info;
		return $HTMLtext;
	}
	
	function orders_on_this_date($order_info){
		$kek = 'some text about this date';
		$text = '';
		foreach($order_info as $user_id => $order)
		{
			$text .= 'Номер заказа: <a href = "order.php?number=' . $order['order_number'] . '">' . $order['order_number'] . '</a>';
			$text .= ' Цена ' . $order['price'];
			$text .= ' Пользователь c id = ' . $user_id . '<br>';
		}
		#return $kek;
		return $text;
		#echo 'Номер заказа: <a href = "order.php?number=' . $number . '">' . $number . '</a>, Стоимость: ' . $order['price'] . ', Дата: ' . $order['date'] . '<br>';
	}
	
	function admin_info($link){  #Целых 3 sql запроса, надо исправить
		$sql = 'SELECT name, email FROM users WHERE user_id = "18"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		$admin_info['personal_data'] = [
		'name' => $row['name'],
		'email' => $row['email'],
		];

		#блок завершенных
		$sql = 'SELECT user_id, order_id, order_number, price, date FROM finished_orders ORDER BY date';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
		$admin_info['finished_orders'] = '';
		foreach($rows as $row)
		{
			$admin_info['finished_orders'][$row['date']][$row['user_id']] = [
			'order_number' => $row['order_number'],
			#'order_id' => $row['order_id'],
			'price' => $row['price'],
			'date' => $row['date'],
			];
		}
		
		#блок доставляющихся
		$sql = 'SELECT user_id, order_id, order_number, price, date FROM orders_in_process ORDER BY date';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$admin_info['orders_in_process'] = '';
		foreach($rows as $row)
		{
			$admin_info['orders_in_process'][$row['date']][$row['user_id']] = [
			'order_number' => $row['order_number'],
			#'order_id' => $row['order_id'],
			'price' => $row['price'],
			'date' => $row['date'],
			];
		}
		
		#show_array($admin_info);
		return $admin_info;
	}
	
	function reduce_amount_of_elements_for_instrument_ids($link, $positions){
		$sql = 'SELECT path from instrument WHERE id in (' . $positions . ')';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
		$sql = '';
		foreach($rows as $row){
			$sql .= reduce_amount_of_elements_to_old_path($row['path']);
		}
		return $sql;
	}
	
	function hide_or_delete_instruments($link, $action, $instr_ids){
		$positions = '0';
		foreach($instr_ids as $id => $on)
		{
			$positions .= ', ';
			$positions .= $id;
		}

		if ($action == 'delete_chosen_instruments')
		{
			$sql = 'DELETE from instrument where id in (' . $positions . ')';
			$sql .= reduce_amount_of_elements_for_instrument_ids($link, $positions);
			echo $sql . '<br>';
			echo 'ДУМАЮ ТЫ НЕ ХОЧЕШЬ ЭТО УДАЛЯТЬ<br>';
			$sql = '';
		}
		if ($action == 'hide_chosen_instruments')
		{
			$sql = 'UPDATE instrument SET status = "hidden" where id in (' . $positions . ')';
		}
		if ($action == 'show_chosen_instruments')
		{
			$sql = 'UPDATE instrument SET status = "visible" where id in (' . $positions . ')';
		}
		$result = mysqli_query($link, $sql);
	}
	
	function catalog_chapter_search($link, $search_request){
		$sql = 'SELECT path FROM `catalog` where name like "' . $search_request . '%" LIMIT 1';
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return '0';
		}
		else
		{
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			return $row['path'];
		}
	}
	
	function edit_catalog_search(){
		$search_html = '
	<form action="admin_panel.php" method="POST">
		<p>
			<strong>Поиск по названию раздела</strong>
		</p>
		<p>
			<input style="width: 300px; type="text" name="catalog_chapter_search">
			<button type="submit">Найти</button>
		</p>
	</form>';
		return $search_html;
	}
	
	function edit_instruments_article_search(){
		$search_html = '
	<form action="admin_panel.php" method="POST">
		<p>
			<strong>Поиск по артикулу</strong>
		</p>
		<p>
			<input style="width: 300px; type="text" name="instruments_article_search">
			<button type="submit">Найти</button>
		</p>
	</form>';
		return $search_html;
	}
	
	function use_JS_event($object, $event){ #принудительное использование события на обьекте
		$js_code = '<script>';
		$js_code .= 'let event = new Event("' . $event .'");';
		$js_code .= $object . '.dispatchEvent(event);';
		$js_code .= '</script>';
		echo $js_code;
	}
	
	function instrument_data($link, $instrument_id){
		$sql = 'SELECT id, name, article, parameters, remain, image, type_id, price, path FROM instrument where id = ' . $instrument_id;
		$result = mysqli_query($link, $sql);
		if (!$result)
		{	
			echo '<br>Ошибка: ' . mysqli_error($link);
			return false;
		}
		else 
		{
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			return $row;
		}
	}
	
	function path_options($link, $instrument_path = NULL){
		if ($instrument_path != NULL)
		{
			$instrument_path_arr = explode("/", $instrument_path);
			$elm = array_pop($instrument_path_arr);
			$instrument_path = implode('/', $instrument_path_arr);
		}
		$sql = 'SELECT id, name, path, page_type FROM catalog where page_type = "1" or page_type = "0" ORDER BY path';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$options = '<option value="">Выберите место в каталоге</option>';
		#echo $instrument_path . 'путь инструмента <br>';
		foreach ($rows as $row)
		{
			#echo $row['path'] . '<br>';
			$margin = margin_in_select($row['path']);
			if  ($instrument_path == $row['path']):
				$selected = 'selected="selected"';
			else:
				$selected = '';
			endif;
			if ($row['page_type'] == 0)
			{
				$selected .= 'disabled="disabled"';
			}
			$options .= '<option ' . $selected . ' title="путь ' . $row['path'] .'" value="' . $row['path'] . '">' . $margin . $row['name'] . '</option>';
		}
		return $options;
	}
	
	function create_path_for_new_instrument($link, $entered_path_str){
		$sql = 'select path from catalog where path like "' . $entered_path_str . '%" order by path';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
		$entered_path_arr = explode('/', $entered_path_str); //введенный путь в виде массива
		$entered_depth = count($entered_path_arr); //глубина введенного элемента
		echo '$entered_path_arr<br>';
		show_array($entered_path_arr);
		
		$catalog_path_str = array_pop($rows)['path']; //последний элемент начинающийся также в виде строки
		$catalog_path_arr = explode('/', $catalog_path_str); //последний элемент начинающийся также в виде масссива
		$catalog_depth = count($catalog_path_arr); //глубина последнего элемента
		echo '$catalog_path_arr<br>';
		#show_array($catalog_path_arr);
		
		if ($catalog_path_str ==  $entered_path_str)
		{
			echo 'Первый метод<br>';
			$entered_path_arr[count($entered_path_arr)] = 1;
			show_array($entered_path_arr);
			$entered_path_str = implode('/', $entered_path_arr);
			return $entered_path_str;
		}
		else
		{
			echo 'Второй метод<br>';
			for ($i = 0; $i <= $entered_depth; $i++)
			{
				$catalog_path_arr_updated[$i] = $catalog_path_arr[$i];
			}
			show_array($catalog_path_arr_updated);
			$catalog_path_arr_updated[count($catalog_path_arr_updated)-1] += 1;
			$catalog_path_str_updated = implode('/', $catalog_path_arr_updated);
			echo 'моя попытка ' . $catalog_path_str_updated . '<br>';
			return $catalog_path_str_updated;
		}
		
	}
	
	function save_image($files){
		if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK)
		{
			$dest_path = '../images/';
			$file_tmp_path = $files['image']['tmp_name'];
			$file_name = $files['image']['name'];
			#$file_size = $files['image']['size'];
			#$file_type = $files['image']['type'];
			#echo $file_tmp_path, '<br>', $file_name, '<br>', $file_size, '<br>', $file_type, '<br>'; 
			$file_name_cmps = explode(".", $file_name);
			$file_extension = strtolower(end($file_name_cmps));
			$new_file_name = date('Hmsdmy') . '.' . $file_extension;
			
			$dest_path .= $new_file_name;
			if(move_uploaded_file($file_tmp_path, $dest_path))
			{
				return $new_file_name;
			}
			else
			{
				return NULL;
			}
		}	
	}
	
	function reduce_amount_of_elements_to_old_path($path){
		#echo 'Уменьшаю по пути ' . $path . '<br>';
		$path_arr = explode('/', $path);
		$full_element = '';
		$sql = '';
		for ($i = 0; $i < count($path_arr); $i++)
		{
			if ($i>0):
				$full_element .= '/' . $path_arr[$i];
			else:
				$full_element .= $path_arr[$i];
			endif;
			#echo '$full_element ' . $full_element . '<br>';
			$sql .= '; UPDATE catalog SET ';
			$sql .= 'amount_of_elements = amount_of_elements - 1 WHERE path = "' . $full_element . '"';
		}
		return $sql;
	}
	
	function add_amount_of_elements_to_new_path($path){
		$path_arr = explode('/', $path);
		$full_element = '';
		$sql = '';
		for ($i = 0; $i < count($path_arr); $i++)
		{
			if ($i>0):
				$full_element .= '/' . $path_arr[$i];
			else:
				$full_element .= $path_arr[$i];
			endif;
			$sql .= '; UPDATE catalog SET ';
			$sql .= 'amount_of_elements = amount_of_elements + 1 WHERE path = "' . $full_element . '"';
		}
		return $sql;
	}
	
	function delete_last_element_in_path($path){
		$path_arr = explode('/', $path);
		array_pop($path_arr);
		$path = implode('/', $path_arr);
		return $path;
	}
	
	function select_path_mode($link, $instrument_id, $entered_path){ #если переноса нет, то возвращаю NULL
		$sql = 'select path from instrument where id = "' . $instrument_id . '"';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		echo 'ПУТЬ ИЗ INSTRUMENT ' . $row['path'] . ', НОВЫЙ ВВЕДЕННЫЙ ' . $entered_path . '<br>';
		if (delete_last_element_in_path($row['path']) == $entered_path)
		{
			return NULL;
		}
		else
		{
			return $entered_path;
		}
	}	
	
	function edit_path_in_catalog($instrument_id, $entered_path){
		$sql = 'UPDATE catalog 
				INNER JOIN instrument ON
					catalog.path = instrument.path 
				SET catalog.path = "' . $entered_path . '"
				WHERE instrument.id = "' . $instrument_id . '"; ';
		return $sql;
	}
	
	function get_created_id($link){
		$sql = 'SELECT id FROM instument WHERE id=LAST_INSERT_ID()';
		$result = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($result);
		return $row['id'];
	}
	
	function create_instrument($link, $instrument_info, $files){
		$instrument_info['image'] = save_image($files); #если файл есть, то сохраняет его и возвращает его имя, если нет, то возвращает NULL
		show_array($instrument_info);
		$instrument_info['path'] = create_path_for_new_instrument($link, $instrument_info['path']); #создает корректный путь инструмента исходя из выбранного для него каталога
		$sql = 'INSERT INTO instrument SET ';
		$sql .= 'name = "' . $instrument_info['name'] . '", ';
		$sql .= 'article = "' . $instrument_info['article'] . '", ';
		$sql .= 'parameters = "' . $instrument_info['parameters'] . '", ';
		$sql .= 'remain = "' . $instrument_info['remain'] . '", ';
		$sql .= 'price = "' . $instrument_info['price'] . '", ';
		if ($instrument_info['image']):
			$sql .= 'image = "' . $instrument_info['image'] . '", ';
		else:
			$sql .= 'image = "question.jpg", ';
		endif;
		if ($instrument_info['path'])
		{
			$sql .= 'path = "' . $instrument_info['path'] . '", ';
		}
		if  ($instrument_info['remain'] > 0):
			$sql .= 'status = "visible"';
		else:
			$sql .= 'status = "hidden"';
		endif;
		$sql .= '; INSERT INTO catalog SET ';
		$sql .= 'path = "' . $instrument_info['path'] . '", ';
		$sql .= 'page_type = "2", ';
		$sql .= 'amount_of_elements = "0"';
		
		$sql .= add_amount_of_elements_to_new_path($instrument_info['path']);
		echo $sql;
		if (mysqli_multi_query($link, $sql)) {
		   do {
			   /* store first result set */
			   if ($result = mysqli_store_result($link)) {
				   //do nothing since there's nothing to handle
				   mysqli_free_result($result);
			   }
			   /* print divider */
			   if (mysqli_more_results($link)) {
				   //I just kept this since it seems useful
				   //try removing and see for yourself
			   }
		   } while (mysqli_more_results($link) and mysqli_next_result($link));
		}
/* 		mysqli_multi_query($link, $sql);
		do {
			# сохранить набор результатов в PHP 
			if ($result = mysqli_store_result($link)) {
				while ($row = mysqli_fetch_row($result)) {
					printf("%s\n", $row[0]);
				}
			}
			# вывести разделитель 
			if (mysqli_more_results($link)) {
				printf("-----------------\n");
			}
		} while (mysqli_more_results($link)); */

	}
	
	function edit_instrument($link, $instrument_info, $files){
		$sql = '';
		$instrument_info['image'] = save_image($files); #если файл есть, то сохраняет его и возвращает его имя, если нет, то возвращает NULL
		show_array($instrument_info);
		$instrument_info['path'] = select_path_mode($link, $instrument_info['id'], $instrument_info['path']);
		if ($instrument_info['path'])
		{
			#создаем новый путь, удаляем старый
			echo 'create_path_for_new_instrument<br>';
			$instrument_info['path'] = create_path_for_new_instrument($link, $instrument_info['path']); #создает корректный путь инструмента исходя из выбранного для него каталога
			echo 'create_path_for_new_instrument' . $instrument_info['path'] . '<br>';
		}
		else
		{
			#ничего не делаем
			#path = NULL
		}
		if ($instrument_info['path'])
		{
			#редактируем каталог вставляя новый путь
			$sql .= edit_path_in_catalog($instrument_info['id'], $instrument_info['path']);
		}
		$sql .= 'UPDATE instrument SET ';
		$sql .= 'name = "' . $instrument_info['name'] . '", ';
		$sql .= 'article = "' . $instrument_info['article'] . '", ';
		$sql .= 'parameters = "' . $instrument_info['parameters'] . '", ';
		$sql .= 'remain = "' . $instrument_info['remain'] . '", ';
		$sql .= 'price = "' . $instrument_info['price'] . '", ';
		if ($instrument_info['image'])
		{
			$sql .= 'image = "' . $instrument_info['image'] . '", ';
		}
		if ($instrument_info['path'])
		{
			#обновляем путь в instrument
			$sql .= 'path = "' . $instrument_info['path'] . '", ';
		}
		if  ($instrument_info['remain'] > 0):
			$sql .= 'status = "visible" ';
		else:
			$sql .= 'status = "hidden" ';
		endif;	
		$sql .= 'WHERE id = "' . $instrument_info['id'] . '"';
		$sql .= reduce_amount_of_elements_to_old_path($instrument_info['old_path']);
		$sql .= add_amount_of_elements_to_new_path($instrument_info['path']);
		echo $sql;
		if (mysqli_multi_query($link, $sql)) {
		   do {
			   /* store first result set */
			   if ($result = mysqli_store_result($link)) {
				   //do nothing since there's nothing to handle
				   mysqli_free_result($result);
			   }
			   /* print divider */
			   if (mysqli_more_results($link)) {
				   //I just kept this since it seems useful
				   //try removing and see for yourself
			   }
		   } while (mysqli_more_results($link) and mysqli_next_result($link));
		}
	}	
	
	function margin_in_select($path){
		$count = substr_count($path, '/');
		$margin = '';
		#echo $count . 'count<br>';
		for ($i=1; $i<=$count; $i++)
		{
			$margin .= '--';
		}
		#echo 'смотрю отступ>' . $margin . '<<br>';
		return $margin;
	}
	
	function catalog_paths_for_js($catalogs){ #возвращает массив, в вкотором индексы - пути, а содержание - количество элементов внутри
		foreach($catalogs as $i => $catalog){
			$paths[] = $catalog['path'];
		}
		#show_array($paths);
		#up_map_for_js($paths);
		#down_map_for_js($paths);
		return $paths;
	}
	
	function up_map_for_js($paths){
		foreach($paths as $id => $path)
		{
			$up_path = external($path);
			#echo 'up_path[' . $id . '] = ' . $up_path . '<br>';
			
			if (empty($up_path)):
				$up_map[$path] = '0';
			else:
				$up_map[$path] = $up_path;
			endif;
		}
		#show_array($up_map);
		return $up_map;
	}
	
	function down_map_for_js($paths){
		$down_map = [];
		foreach($paths as $id => $path)
		{
			$up_path = external($path);			
			if (empty($up_path)):
				if(!isset($down_map['0']))
				{
					#echo 'создаю новый для ' . $path . ' , up_path = ' . $up_path . '<br>';
					$down_map['0'] = array();
				}
				$down_map['0'][] .= $path;
			else:
				if(!isset($down_map[$up_path]))
				{
					#echo 'создаю новый для ' . $path . ' , up_path = ' . $up_path . '<br>';
					$down_map[(string)$up_path] = array();
				}
				else
				{
					#echo 'дополняю для ' . $path . ' , up_path = ' . $up_path . '<br>';
				}
				$down_map[(string)$up_path][] .= $path;
			endif;
		}
		#show_array($down_map);
		return $down_map;
	}
	
	function down_map_for_js_old($paths){
		$down_map = [];
		foreach($paths as $id => $path)
		{
			$up_path = external($path);			
			if (empty($up_path)):
				continue;
			else:
				if(!isset($down_map[$up_path]))
				{
					#echo 'создаю новый для ' . $path . ' , up_path = ' . $up_path . '<br>';
					$down_map[(string)$up_path] = array();
				}
				else
				{
					#echo 'дополняю для ' . $path . ' , up_path = ' . $up_path . '<br>';
				}
				$down_map[(string)$up_path][] .= $path;
			endif;
		}
		#show_array($down_map);
		return $down_map;
	}
	
	function external($path){
		$path_arr = explode('/', $path);
		array_pop($path_arr);
		$path_str = implode('/', $path_arr);
		#echo $path_str . '<br>';
		return $path_str;
	}
	
	function add_new_catalog($link, $current_path, $new_catalog_name){
		echo 'ДОБАВЛЯЮ НОВЫЙ КАТАЛОГ ' . $new_catalog_name . ' внутри ' . $current_path . '<br>';
		if ($current_path = '0')
		{
			#добавление в корневой каталог
		}
		else
		{
			$sql = 'SELECT page_type FROM `catalog` where path = "' . $current_path . '"';
			$result = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($result);
			if ($row['page_type'] == '0')
			{
				#добавление в каталог с папками
				echo 'все хорошо, добавляю<br>';
			}
			else
			{
				#добавление в каталог с интрументами, нужен перенос всех инструментов в новую папку
				echo 'надо ебаться<br>';
			}
			return true;
		}
	}
	
	function delete_catalog($link, $path){
		echo 'УДАЛЯЮ КАТАЛОГ ПО ПУТИ ' . $path . '<br>';
		return true;
	}
	
	function rename_catalog($link, $path, $new_name){
		echo 'МЕНЯЮ НАЗВАНИЕ КАТАЛОГА ПО ПУТИ ' . $path . ' НА ' . $new_name . '<br>';
		$sql = 'update catalog set name = "' . $new_name . '" WHERE path = "' . $path . '"';
		$result = mysqli_query($link, $sql);
		return true;
	}
	
	function new_catalog(){
		$spisok = '<li class="hidden" id="create_new_catalog" style="list-style-type: none;">';
		$spisok .= '<form method="POST" style="display: inline;" onsubmit="return validate_create_new_catalog();" action="admin_panel.php">';
		$spisok .= '<input id="hidden_path" type="hidden" name="current_path" value="0">'; 
		$spisok .= '<input name="new_catalog_name" required placeholder="Название нового каталога">';
		$spisok .= '<button>Сохранить</button>';
		$spisok .= '</form>';
		$spisok .= '</li>';
		return $spisok;
	}
	
	function edit_catalog_btns_list($catalogs){
		#show_array($catalogs);
		$spisok = '';
		$spisok .= '<button id="up_btn">Вверх</button>  ';
		$spisok .= '<button id="create_new_catalog_btn">Добавить</button>  ';
		$spisok .= '<button id="create_new_catalog_btn_cancel" class="hidden">Отмена</button><br>';
		$spisok .= '<div class = "scrolling_list"><ul>'; 
		$spisok .= new_catalog();
		$spisok .= '<li id="empty_folder" class="hidden">Пусто</li>';
		foreach($catalogs as $catalog){
			#show_array($catalog);
			$spisok .= '<li class="admin_catalog" id="li_' . $catalog['path'] . '">';
			$spisok .= '<span style="cursor: pointer;" id="' . $catalog['path'] . '">' . $catalog['name'] . '</span>';
			$spisok .= ' (' . $catalog['amount_of_elements'] . ')  ';

			$spisok .= edit_catalog_name($catalog['path']);
			$spisok .= '</li>';
		}
        $spisok .= '</ul></div>'; 
		return $spisok;
	}
	
	function edit_catalog_name($catalog_path){
		$editing = '<span style="cursor: pointer;" id="change_name[' . $catalog_path . ']">Изменить</span>';
		$editing .= '<div class="hidden" id="editing_catalog[' . $catalog_path . ']">';
		$editing .= '<form action="admin_panel.php" method="POST" onsubmit="return validate_delete_catalog(this, this.submitted);">'; #onsubmit="return validate_delete_catalog();"
		$editing .= '<input type="hidden" name="path" value="' . $catalog_path . '">';
		$editing .= '<input id="change_catalog_name" name="change_catalog_name">';
		$editing .= '<button name="catalog_edit_action" onclick="this.form.submitted=this.value;" value="change">Сохранить</button>';
		$editing .= '<button name="catalog_edit_action" onclick="this.form.submitted=this.value;" value="delete">Удалить</button>';
		$editing .= '</form>';
		$editing .= '</div>';
		return $editing;
	}
  

	function edit_instruments_btns_list($instruments){
		#show_array($instruments);
		$spisok = '<form id="editing_instruments" action="admin_panel.php" method="POST">';
		$spisok .= '</form>';
		$spisok .= '<form id="create_new_instrument" action="editing_instrument.php">';
		$spisok .= '</form>';
		$spisok .= '<button form="editing_instruments" type="submit" title="Удалить позиции навсегда" id="delete_chosen_instruments" name="action" value="delete_chosen_instruments">Удалить</button>  ';
		$spisok .= '<button form="editing_instruments" type="submit" title="Скрыть от пользователей не удаляя" id="hide_chosen_instruments" name="action" value="hide_chosen_instruments">Убрать</button>  ';
		$spisok .= '<button form="editing_instruments" type="submit" title="Сделать видимым" id="show_chosen_instruments" name="action" value="show_chosen_instruments">Вернуть</button>  ';
		$spisok .= '<button form="create_new_instrument" type="submit" title="Добавить новый инструмент">Добавить</button><br>';
		$spisok .= '<div class = "scrolling_list"><ul>';
		foreach($instruments as $instrument){
			#show_array($intrument);
			$href = "'editing_instrument.php?id=" . $instrument['id'] . "'";
			($instrument['status'] == 'hidden') ? $spisok .= '<li class="hidden_instrument	">' : $spisok .= '<li>';
			$spisok .= '<input type="checkbox" form="editing_instruments" name="instr_id[' . $instrument['id'] . ']" id="instr_id' . $instrument['id'] . '"> ';
			$spisok .= '<span class="edit_instruments_tegs">Артикул:</span> ' . $instrument['article'] . '<br>';
			$spisok .= '<div title="Редактировать ' . $instrument['article'] . '" onclick="location.href=' . $href . ';" style="cursor: pointer;">';
			$spisok .= '<div class="cut-text"><span class="edit_instruments_tegs">Название:</span> ' . $instrument['name'] . '</div>';
			$spisok .= '<div class="cut-text"><span class="edit_instruments_tegs">Описание:</span> ' . $instrument['parameters'] . '</div>';
			$spisok .= '<span class="edit_instruments_tegs">Остаток:</span> ' . $instrument['remain'] . '<br>';
			$spisok .= '<span class="edit_instruments_tegs">Изображение:</span> ' . $instrument['image'] . '<br>';
			$spisok .= '<span class="edit_instruments_tegs">Цена:</span> ' . $instrument['price'] . ' ';
			$spisok .= '</div>';
			$spisok .= '</li>';
		}
        $spisok .= '</ul></div>'; 
		#echo '<div class="cut-text">Описание ' . $instruments[0]['parameters'] . '</div>';
		return $spisok;
	}
	
	function instruments_to_edit($link, $article = ''){
		$sql = 'SELECT * FROM instrument where article LIKE "' . $article . '%"';
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			$rows = [];
		}
		else
		{
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		return $rows;
	}
	
	function catalogs_to_edit($link){
		$sql = 'SELECT name, amount_of_elements, path, page_type FROM catalog WHERE page_type <= 1';
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $rows;
	}
	
	
	function read_user_cart_table($link, $user_id){
		$instuments_info_from_cart = array_from_cart($link, $user_id);
		#show_array($instuments_info_from_cart);
		$prices_of_instruments = [];
		if (!$instuments_info_from_cart)
		{
			echo 'Корзина пустая<br>';
		}
		else
		{
			#$prices_of_instruments = [];
			$i = 0; #счетчик строки
			$full_price = 0; #стоимость всей корзины
			echo '<table>';
			echo '<tr><th>№</th><th>Название</th><th>Артикул</th><th>Превью</th><th>Стоимость</th><th>Изменить количество</th></tr>';
			foreach($instuments_info_from_cart['elements_info'] as $instument_id => $instrument){
				#каждая строка корзины
				$i+=1;
				$prices_of_instruments[$instrument['id']] = $instrument['price'];
				$price = $instrument['price']*$instrument['amount'];
				$full_price += $price;
				$image_path = '../images/' . $instrument['type_id'] . '/' . $instrument['image'];
				$path = 'catalog.php?path=' . $instrument['path'];
				#echo '<tr><td><a href = "' . $path . '">' . $i . '</td><td>' . $instrument['name'] . '</td><td>' . $instrument['article'] . 
				#'</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></td></a><td>' . $instrument['amount'] . '</td><td>' . $price . '</td></tr>';
				echo '<tr><td>' . $i . '</td>';
				echo '<td><a href = "' . $path . '">' . $instrument['name'] . '</td>';
				echo '<td>' . $instrument['article'] . '</td>';
				echo '<td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></td></a>';
				#echo '<td>' . $instrument['amount'] . '</td>';
				#echo '<td><span id="full_price_of_instr_id' . $instrument['id'] . '">' . $price . '<span></td>';
				echo '<td id="full_price_of_instr_id' . $instrument['id'] . '">' . $price . '</td>';
				echo '<td><button type="submit" id="minus_' . $instrument['id'] . '">-</button>';
				echo '<input type="text" name="new_amount_of_instr_id[' . $instrument['id'] . ']" form="new_amounts_of_instruments" value = "' . $instrument['amount'] . '" id = "input_' . $instrument['id'] . '">';
				echo '<button type="submit" id="plus_' . $instrument['id'] . '">+</button></td></tr>';
			}
			echo '</table>';
			#echo '<p>>Суммарная цена: ' . $full_price . '</p>';
			echo '<p>Суммарная цена: <span id="full_cart_price">' . $full_price . '</span></p>';
		}
		return $prices_of_instruments;
	}
	
	function read_user_cart_table_old_ver($link, $user_id){
		$instuments_info_from_cart = array_from_cart($link, $user_id);
		#show_array($instuments_info_from_cart);
		if (!$instuments_info_from_cart)
		{
			echo 'Корзина пустая<br>';
			return false;
		}
		else
		{
			$i = 0; #счетчик строки
			$full_price = 0; #стоимость всей корзины
			echo '<table>';
			echo '<tr><th>№</th><th>Название</th><th>Артикул</th><th>Превью</th><th>Заказано</th><th>Стоимость</th></tr>';
			foreach($instuments_info_from_cart['elements_info'] as $instument_id => $instrument){
				#каждая строка корзины
				$i+=1;
				$price = $instrument['price']*$instrument['amount'];
				$full_price += $price;
				$image_path = '../images/' . $instrument['type_id'] . '/' . $instrument['image'];
				$path = 'catalog.php?path=' . $instrument['path'];
				#echo '<tr><td><a href = "' . $path . '">' . $i . '</td><td>' . $instrument['name'] . '</td><td>' . $instrument['article'] . 
				#'</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></td></a><td>' . $instrument['amount'] . '</td><td>' . $price . '</td></tr>';
				echo '<tr><td>' . $i . '</td><td><a href = "' . $path . '">' . $instrument['name'] . '</td><td>' . $instrument['article'] . 
				'</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></td></a><td>' . $instrument['amount'] . '</td><td>' . $price . '</td></tr>';
			}
			echo '</table>';
			echo '<p>Суммарная цена: ' . $full_price . '</p>';
			return true;
		}
	}
	
	#SEARCH FUNCTIONS FOR CATALOG2
	function search_catalog_table($link, $search){
		$sql = "SELECT path, page_type FROM `catalog` where name ='" . $search . "'";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return false;
		}
		else
		{
			$row = mysqli_fetch_array($result);
			#echo $row['path'] . ' ' . $row['page_type'] . '<br>';
			if ($row['page_type'] == 0)
			{
				#echo '<p>не последний элемент в списке</p>';
				read_catalog_table($link, $row['path']);
			}
			if ($row['page_type'] == 1)
			{
				#echo '<p>последний элемент в списке</p>';
				read_last_page_catalog_table($link, $row['path']);
			}
			return true;
		}
	}
	
	function search_instrument_table($link, $search){
		$sql = "SELECT id, name, article, remain, image, path, type_id FROM `instrument` where name LIKE '%" . $search . "%'";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) == 0)
		{
			return false;
		}
		else
		{
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			echo '<table>';
			echo '<tr><th>№</th><th>Название</th><th>Артикул</th><th>Остаток</th><th>Превью</th></tr>';
			foreach($rows as $row){
				$image_path = '../images/' . $row['type_id'] . '/' . $row['image'];
				$href_path = 'catalog.php?path=' . $row['path'];
				echo '<tr><td>' . $row['id'] . '</td><td><a href="' . $href_path . '">' . $row['name'] . '</td><td>' . $row['article'] . '</td><td>' . $row['remain'] . '</td><td><a href = "' . $image_path . '"><img height=50 width=70 src="' . $image_path . '"></a></td></tr>';
			}
			echo '</table>';
			return true;
		}
	}
	#END OF SEARCH FUNCTIONS FOR CATALOG2
	#END OF CATALOG VERSION 2
?>