<?php
#GET ПАРАМЕТР ID ИНСТРУМЕНТА, ЕСЛИ НЕТ, ТО СОЗДАЕМ НОВЫЙ
	session_start();
	require 'db_connect.php';
	require 'sql_functions.php';
	if (isset($_SESSION['logged_user_id']) and $_SESSION['logged_user_id'] == 18):
		show_array($_POST);
		if (isset($_POST['editing_instrument_data']))
		{
			if ($_POST['action'] == 'editing')
			{
				echo 'editing<br>';
				edit_instrument($link, $_POST, $_FILES);
			}
			if ($_POST['action'] == 'creating')
			{
				echo 'creating<br>';
				create_instrument($link, $_POST, $_FILES);
/* 				$created_id = create_instrument($link, $_POST, $_FILES);
				if ($created_id)
				{
					$header = 'Location: ../pages/cab.php?id=' . $created_id;
					header($header);
				} */
			}
			#add_new_instrument($link, $_POST, $_FILES);
		}
		require 'header_menu.php';
		if (isset($_GET['id']))
		{
			echo 'редачу ' . $_GET['id'] . '<br>';
			$action = 'editing';
			$instrument_data = instrument_data($link, $_GET['id']);
			#show_array($instrument_data);
			$path_options = path_options($link, $instrument_data['path']);
		}
		else
		{
			echo 'создаю новый';
			$action = 'creating';
			$path_options = path_options($link);
		}
		$form_action = 'editing_instrument.php';
		if (isset($_GET['id']))
		{
			$form_action .= '?id=' . $_GET['id'];
		}
			
?>		
	


	<form id="editing_instrument" action="<?php echo $form_action ?>" method="POST" enctype="multipart/form-data" onsubmit="return sendform_editing_instrument();">
<?php	if ($action == 'editing'): ?>
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
		<input type="hidden" name="old_path" value="<?php echo $instrument_data['path'] ?>">
<?php	endif; ?>
		<input type="hidden" name="action" value="<?php echo $action ?>">
		<p>
			<strong>Артикул</strong><br>
			<input type="text" name="article" value='<?php if (isset($instrument_data['article'])) echo $instrument_data['article'] ?>'>
		</p>
		<p>
			<strong>Навание</strong><br>
			<textarea cols="70" rows="3" name='name'><?php if (isset($instrument_data['name'])) echo $instrument_data['name'] ?></textarea>
		</p>
		<p>
			<strong>Описание</strong><br>
			<textarea cols="70" rows="10" name='parameters'><?php if (isset($instrument_data['parameters'])) echo $instrument_data['parameters'] ?></textarea>
		</p>
		<p>
			<strong>Остаток</strong><br>
			<input type="number" name='remain' value='<?php if (isset($instrument_data['remain'])) echo $instrument_data['remain'] ?>'>
		</p>
		<p>
			<strong>Цена</strong><br>
			<input type="number" name='price' value='<?php if (isset($instrument_data['price'])) echo $instrument_data['price'] ?>'>
		</p>
<?php	
		if (isset($instrument_data['image']) and !empty($instrument_data['image'])):
?>
		<p>
			<strong>Изображение</strong><br>
			<img id="image_preview" height="150" width="200" src='../images/<?php echo $instrument_data['image'] ?>'><br>
			<input type="file" id="image_upload" name='image' accept="image/*">
		</p>
<?php	
		else:
?>
		<p>
			<strong>Изображение</strong><br>
				<img id="image_preview" height="150" width="200" class='hidden' src=""><br>
				<input type="file" id="image_upload" name='image'>
		</p>
<?php	
		endif;
?>
		<p>
			<strong>Каталог</strong><br>
			<select name='path'>
				<?php echo $path_options ?>
			</select>
		</p>
		<button type='submit' name='editing_instrument_data'>Сохранить изменения</button>
	</form>

	<script>
		var image_preview = document.getElementById('image_preview');
		var image_upload = document.getElementById('image_upload');
		image_upload.addEventListener('change', function(e) {
			if (e.target.files[0])
			{
				image_preview.classList.remove('hidden');
				//document.body.append('Я поймал событие загрузки ' + e.target.files[0].name);
				image_preview.src = URL.createObjectURL(e.target.files[0]);
			}
		});
	</script>
	
<?php		
	else:
		header('Location: /edsa-instruments/pages/cab.php');
	endif; 
?>