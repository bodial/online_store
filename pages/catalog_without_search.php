<?php
require 'header_menu.php';
#C:\programming\php\instruments\styles\
#<link rel="stylesheet" href="../styles/style.css">
		/*<header>
			<h1>bison tools</h1>
		</header>*/
?>


		<?php
		require 'db_connect.php';
		require 'sql_functions.php';
		/*
		if (isset($_GET['search']))
		{
			$search = $_GET['search'];
			
			#
			#МЕСТО ДЛЯ ОКНА ПОИСКА
			#
			
			if (!search_catalog_table($link, $search))
			{
				echo 'Категории не найдены<br>';
			}
			if (!search_innstrument_table($link, $search))
			{
				echo 'позиции с "' . $search . '" не найдены<br>';
			}
		}
		else
		*/
		if (isset($_GET['path']))
		{
			$path = $_GET['path'];
		}
		else 
		{
			$path = '';
		}
		
		#echo '<p>';
		echo '<div class = "bread_crumbs">';
		bread_crumbs($link, $path); #хлебные крошки
		echo '</div>';
		#echo '</p>';
		
		$type_of_page = type_of_page_in_catalog($link, $path); #0-каталог 1-список инструментов 2-один инструмент
		
		if ($type_of_page == 0)
		{
			#echo '<p>не последний элемент в списке</p>';
			read_catalog_table($link, $path);
		}
		elseif($type_of_page == 1)
		{
			#echo '<p>последний элемент в списке</p>';
			read_last_page_catalog_table($link, $path);
		}
		else
		{
			#echo '<p>конкретный инструмент</p>';
			$sql = "SELECT id FROM instrument where path ='" . $path . "'";
			$result = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($result);
			$instrument_id = $row['id'];
			#read_single_instrument_name_image_price_ver_2($link, $path);
			
			#TEST__________________________________________________________________
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

	<form action="catalog.php?path=<?php echo $path ?>" method="POST">
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
		
	<form action="catalog.php?path=<?php echo $path ?>" method="POST">
		<button type='submit' name='cart_options_data'>В корзину</button>
	</form>
	
<?php 	endif ?>

<?php
	read_single_instrument_parameters($link, $instrument_id);
			#TEST__________________________________________________________________
		}
		?>
	</body>
</html>