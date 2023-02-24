<?php

	session_start();

	if(!isset($_SESSION["id"])){

		header("location: password.php");

	}

exec('tellmeajoke', $joke, $joke2);

echo $joke[1];
echo $joke2;
?>
<!doctype HTML>
<html>
<head>
</head>
<body>
</body>
</html>
