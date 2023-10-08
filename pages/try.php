<?php
	require 'db_connect.php';
	require 'sql_functions.php';
/* 	$path = '3/1';
	$sql = update_amount_of_elements($path);
	echo $sql; */
	$sql = 'select path from instrument where id = 4; ';
	$sql .= 'select article from instrument where id = 4';
	echo $sql . '<br>';
	
/* 	mysqli_multi_query($link, $sql);
	while (mysqli_more_results($link))
	{
		$result = mysqli_next_result($link);
		$row = mysqli_fetch_array($result);
		show_array($row);
		echo 'qwe<br>';
	} */
	
	
	
	#$query  = "CREATE TABLE....;...;... blah blah blah;...";

	$path_1 = '1';
	$path_2 = '1/2';
	
	$path_arr_1 = explode('/', $path_1);
	show_array($path_arr_1);
	
	$path_arr_2 = explode('/', $path_2);
	show_array($path_arr_2);
	
	array_pop($path_arr_1);
	array_pop($path_arr_2);
	show_array($path_arr_1);
	show_array($path_arr_2);
	
	if (empty($path_1_arr)):
		echo 'empty<br>';
	endif;
	
	$path_1 = implode($path_arr_1);
	$path_2 = implode($path_arr_2);
	echo 'первый' . $path_1 . '<br>';
	echo 'второй' . $path_2 . '<br>';
	
	
	
	
?>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script>
	  $(document).ready(function(){
	   var text = $('#hide_text');//присваиваем переменной text блок с id=hide_text
	   text.hide();//скрываем полученный блок
	   $('.button').click(function(){ // при клике на HTML элемент с классом button
		text.show(200);// показываем скрытый блок с задержкой 200 милисекунд
	   });
	  });
</script>
 <h1>Контент страницы</h1>
  <a href="#" class="button">Нажмите, чтобы показать текст</a>
  <div id="hide_text">Демонстрационный скрытый текст</div>
  
  