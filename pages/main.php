<?php
require 'header_menu.php';
#C:\programming\php\instruments\styles\
#<link rel="stylesheet" href="../styles/style.css">
?>

		<header>
			<h1>bison tools</h1>
		</header>
		<?php
		require 'db_connect.php';
		require 'sql_functions.php';
		read_types_of_instruments_table($link);
		?>
	</body>
</html>
