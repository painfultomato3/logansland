<?php 

	session_start();

	if(!isset($_SESSION["id"])){

		header("location: password.php");

	}

?>
<!doctype HTML>
<html>
	<head>
		<title></title>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximun-scale=1">

		<style>

			.page-cont{

				width:100%;
				display: flex;
				justify-content: center;
			}

			.items-cont{

				display: flex;
				flex-direction: column;
			}

			@media screen{

				button{

				-webkit-appearance: none;
				border-radius: 0;
				font-size: 25px;
				margin: 5px;
				width: 90vw;
				height: 20vw;
				}

			}

		</style>

	</head>
	<body>

		<div class="page-cont">
		<div class="items-cont">
			<a href="pins.php"><button>Pins</button></a>
			<a href="bills.php"><button>Bills</button></a>
			<a href="calendar.php"><button>Calendar</button></a>
			<a href="encryptstring.php"><button>Encrypt A String</button></a>
		</div>
		</div>

	</body>
</html>
