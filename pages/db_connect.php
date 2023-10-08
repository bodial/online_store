<?php
	$link = mysqli_connect("localhost", "root", "", "instruments");
	mysqli_set_charset($link, "utf8");
	/*
	if ($link == false){
		echo "<p>Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error() . '</p>';
	}
	else {
		echo "<p>Соединение установлено успешно</p>";
	}
	
	if (!mysqli_set_charset($link, "utf8")) {
		echo "Ошибка при загрузке набора символов utf8: ", mysqli_error($link) . '</p>';
	} else {
		echo "<p>Текущий набор символов: " . mysqli_character_set_name($link) . "</p>";
	}	
	*/
?>
