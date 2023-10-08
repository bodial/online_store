<?php
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
	if (isset($_SESSION['logged_user_id']) and $_SESSION['logged_user_id'] == 18):
	show_array($_POST);
		$catalog_open = true; #костыль для открытия каталога при перезагрузки страницы (отправка формы)
		if (isset($_POST['action'])) #редактирование инструментов
		{
			hide_or_delete_instruments($link, $_POST['action'], $_POST['instr_id']);
		}
 		elseif (isset($_POST['current_path'])) #создание нового каталога
		{
			add_new_catalog($link, $_POST['current_path'], $_POST['new_catalog_name']);
		} 
		elseif(isset($_POST['catalog_edit_action'])) #редактирование каталога
		{
			if ($_POST['catalog_edit_action'] == 'delete') #удаление папки
			{
				delete_catalog($link, $_POST['path']);
			}
			elseif($_POST['catalog_edit_action'] == 'change') #смена имени папки
			{
				rename_catalog($link, $_POST['path'], $_POST['change_catalog_name']);
			}
		}
		else
		{
			$catalog_open = false;
		}
		require 'header_menu.php';
		$admin_info = admin_info($link); #возвращает массив строк таблицы бд
		echo '<p>Добрый день, ' . $admin_info['personal_data']['name'] . '</p>';
?>


		<!--ИНФА О ПОЧТЕ-->
		<p>
			Почта
			<button type="submit" id="show_email">Показать</button>
			<button type="submit" id="hide_email" class="hidden">Скрыть</button>
			<div id="email_hidden" class="hidden"><?php echo $admin_info['personal_data']['email'] /*. ' <a href="change_email.php">Изменить</a>'; */?></div>
		</p>

<?php 

		#ИНФА О ЗАВЕРШЕННЫХ ЗАКАЗАХ
		if (!empty($admin_info['finished_orders'])):
		$finished_orders_in_admin_panel = show_orders_in_dates_in_admin_panel($admin_info['finished_orders']);
?>	
			<p>
				Завершенные заказы
				<button type="submit" id="show_finished_orders">Показать</button>
				<button type="submit" id="hide_finished_orders" class="hidden">Скрыть</button>
				<div id="finished_orders_hidden" class="hidden"><?php echo $finished_orders_in_admin_panel; ?></div>
			</p>	
			
<?php   else: ?>	

			<p>
				Нет завершенных заказов
			</p>
			
<?php 
		endif;

		#ИНФА О ДОСТАВЛЯЮЩИХСЯ ЗАКАЗАХ
		if (!empty($admin_info['orders_in_process'])):
		$orders_in_process_in_admin_panel = show_orders_in_dates_in_admin_panel($admin_info['orders_in_process']);
?>	
			<p>
				Заказы в процессе доставки
				<button type="submit" id="show_orders_in_process">Показать</button>
				<button type="submit" id="hide_orders_in_process" class="hidden">Скрыть</button>
				<div id="orders_in_process_hidden" class="hidden"><?php echo $orders_in_process_in_admin_panel; ?></div>
			</p>	
			
<?php 	else: ?>	

			<p>
				Нет доставляющихся заказов
			</p>
			
<?php 	
		endif;


		#РЕДАКТИРОВАНИЕ ИНСТРУМЕНТОВ
		if (isset($_POST['instruments_article_search']))
		{
			$instruments_to_edit = instruments_to_edit($link, $_POST['instruments_article_search']);
			#открыть список с помощью js
			
		}
		else
		{
			$instruments_to_edit = instruments_to_edit($link);
		}
		#if (count($instruments_to_edit) > 0):
			$edit_instruments_tools = edit_instruments_article_search();
			$edit_instruments_tools .= edit_instruments_btns_list($instruments_to_edit);
?>	
			<p>
				Редактирование инструментов
				<button type="submit" id="show_edit_instruments">Показать</button>
				<button type="submit" id="hide_edit_instruments" class="hidden">Скрыть</button>
				<div id="edit_instruments_hidden" class="hidden"><?php echo $edit_instruments_tools; ?></div>
			</p>	
			
<?php 	#else: ?>	

<!-- 			<p>
				Нет инструментов
			</p>  -->
			
<?php 	#endif;?>	


<?php 
		#РЕДАКТИРОВАНИЕ КАТАЛОГА
		$catalogs_to_edit = catalogs_to_edit($link);
		$edit_catalog_tools = '';
		$edit_catalog_tools = edit_catalog_search();
		$edit_catalog_tools .= edit_catalog_btns_list($catalogs_to_edit);
		if (isset($_POST['catalog_chapter_search']))
		{
			$catalog_open = true;
			$current_path = catalog_chapter_search($link, $_POST['catalog_chapter_search']);
		}
		else
		{
			$current_path = '0';
		}
		
		#для JS
		$catalog_paths_for_js = catalog_paths_for_js($catalogs_to_edit); #массив возможных путей
		$down_map_for_js = down_map_for_js($catalog_paths_for_js); #массив движения вверх по иерархии
		$up_map_for_js = up_map_for_js($catalog_paths_for_js); #массив дивжения вниз по иерархии		
		#show_array($down_map_for_js);
		#show_array($up_map_for_js);
		###
?>	
			<p>
				Редактирование каталога
				<button type="submit" id="show_edit_catalog">Показать</button>
				<button type="submit" id="hide_edit_catalog" class="hidden">Скрыть</button>
				<div id="edit_catalog_hidden" class="hidden"><?php echo $edit_catalog_tools; ?></div>
			</p>




		<a href='change_password.php'>Сменить пароль</a><br>
		<a href='logout.php'>Выйти</a><br>
		

<?php
	#СОЗДАНИЕ СКРИПТОВ ДЛЯ КНОПОК ДАТ
	foreach ($admin_info['finished_orders'] as $date => $orders_in_one_date)
	{
			$show_btn_id = 'show_finished_order_date' . $date;
			$hide_btn_id = 'hide_finished_order_date' . $date;
			$hidden_text_id = 'finished_orders_hidden_date' . $date;
			$array_for_js_hide_show_script[] = [
			'show_btn_id' => $show_btn_id,
			'hide_btn_id' => $hide_btn_id,
			'hidden_text_id' => $hidden_text_id,
			];
	}
 	foreach ($admin_info['orders_in_process'] as $date => $orders_in_one_date)
	{
			$show_btn_id = 'show_finished_order_date' . $date;
			$hide_btn_id = 'hide_finished_order_date' . $date;
			$hidden_text_id = 'finished_orders_hidden_date' . $date;
			$array_for_js_hide_show_script[] = [
			'show_btn_id' => $show_btn_id,
			'hide_btn_id' => $hide_btn_id,
			'hidden_text_id' => $hidden_text_id,
			];
	} 
	#show_array($array_for_js_hide_show_script);
?>

<script src="../scripts/jquery.js"></script><!--ПОДКЛЮЧЕНИЕ JQUERY-->
<script>
	<!--СОЗДАНИЕ СКРИПТОВ ДЛЯ УДАЛЕНИЯ И СКРЫТИЯ ИНСТРУМЕНТОВ-->
	var hide_chosen_instruments = document.getElementById("hide_chosen_instruments");
	var delete_chosen_instruments = document.getElementById("delete_chosen_instruments");

	//hide_chosen_instruments.addEventListener("click", function (event) {
/* 		if (email.validity.typeMismatch)
		{
			email.setCustomValidity("I am expecting an e-mail address!");
		} 
		else 
		{
			email.setCustomValidity("");
		} */
	//});
	<!--СОЗДАНИЕ СКРИПТОВ ДЛЯ КНОПОК ДАТ-->
	var array_for_js_hide_show_script = <?php echo json_encode($array_for_js_hide_show_script); ?>;
	for (id in array_for_js_hide_show_script)
	{
		let hide_btn = document.getElementById(array_for_js_hide_show_script[id]['hide_btn_id']); //клавиша hide
		let show_btn = document.getElementById(array_for_js_hide_show_script[id]['show_btn_id']); //клаваиша show
		let hidden_text = document.getElementById(array_for_js_hide_show_script[id]['hidden_text_id']); //спрятанный текст
		hide_btn.addEventListener('click', function(event){
			hide_text_and_btns(hidden_text, hide_btn, show_btn);
		});
		show_btn.addEventListener('click', function(){
			show_text_and_btns(hidden_text, hide_btn, show_btn);
		});
	}
	
	<!--ПОЧТА-->
	hide_email.addEventListener('click', function(event){
		hide_text_and_btns(email_hidden, hide_email, show_email);
	});
	show_email.addEventListener('click', function(){
		show_text_and_btns(email_hidden, hide_email, show_email);
	});
	
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
	
	<!--РЕДАКТИРОВАНИЕ ИНСТРУМЕНТОВ-->
	hide_edit_instruments.addEventListener('click', function(){
		hide_text_and_btns(edit_instruments_hidden, hide_edit_instruments, show_edit_instruments);
	});
	show_edit_instruments.addEventListener('click', function(){
		show_text_and_btns(edit_instruments_hidden, hide_edit_instruments, show_edit_instruments);
	});
	
	<!--РЕДАКТИРОВАНИЕ КАТАЛОГА-->
	hide_edit_catalog.addEventListener('click', function(){
		hide_text_and_btns(edit_catalog_hidden, hide_edit_catalog, show_edit_catalog);
	});
	show_edit_catalog.addEventListener('click', function(){
		show_text_and_btns(edit_catalog_hidden, hide_edit_catalog, show_edit_catalog);
	});
	
	var paths = <?php echo json_encode($catalog_paths_for_js); ?>;
	var up_map = <?php echo json_encode($up_map_for_js); ?>;
	var down_map = <?php echo json_encode($down_map_for_js); ?>;
	var current_path = <?php echo json_encode($current_path); ?>;
	initialize_catalog(paths, up_map, down_map);

	<!--ИЗМЕНЕНИЕ НАЗВАНИЙ ЭЛМЕМЕНТОВ КАТАЛОГА-->
	initialize_catalog_name_change(paths);
	
	<!--ДОБАВЛЕНИЕ НОВОГО ЭЛЕМЕНТА КАТАЛОГА-->
	initialize_new_catalog_creation();
	

</script>
<?php
	if (isset($_POST['action']) or isset($_POST['instruments_article_search'])): use_JS_event('show_edit_instruments', 'click'); endif; #открытие списка инструментов после отправке формы
	if ($catalog_open): use_JS_event('show_edit_catalog', 'click'); endif; #открытие каталога после отправке формы
	else:
		header('Location: /edsa-instruments/pages/cab.php');
	endif; 
	#var catalog_paths = <?php echo json_encode($catalog_paths); 
?>

  </body>
</html>