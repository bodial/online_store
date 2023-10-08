		<?php
		require 'header_menu.php';
		require 'sql_functions.php';
		require 'db_connect.php';
		if (isset($_GET['id']))
		{
			$type_id = $_GET['id'];
		}
		else
		{
			$type_id = 0;
		}
		read_instrument_name($link, $type_id);
		read_instrument_table($link, $type_id);
		?>
	</body>
</html>
