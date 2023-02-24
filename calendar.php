<?php 

	session_start();

	if(!isset($_SESSION["id"])){

		header("location: password.php");

	}

?>
<!doctype HTML>
<html>
	<head>

		<title>Calendar</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<style>

			@media screen {
				.calendar{
					height: 100vh;
					width: 100vw;
					border:solid 1px #777
				}
			}

		</style>

	</head>
	<body>

		<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=America%2FDenver&src=bG9nYW5lcmNhbmJyYWNrQGdtYWlsLmNvbQ&color=%237986CB" class="calendar" frameborder="0" scrolling="no"></iframe>

	</body>
</html>