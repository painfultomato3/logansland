<?php 

	session_start();

	if(!isset($_SESSION["id"])){

		header("location: password.php");

	}

if($_SERVER["REQUEST_METHOD"] == "POST"){

	$mystr = $_POST["mystr"];

	$encStr = shell_exec("string_encrypt " . $mystr);
}

?>

<!doctype HTML>
<html>

	<head>
		<title>Pin Tracker</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<style>

			.page-cont{

				width: 100%;
				display: flex;
				justify-content: center;


			}

			@media screen{

				.form{

					display: flex;
					flex-direction: column;
					justify-content: center;
					width: 400px;
					max-width: 90vw;
				}

				.input-cont{
						margin: 5px;
						width: 100%;
						flex-grow: 1;
						display: flex;
						height: 5vh;
				}

				button, input, select{

						-webkit-appearance: none;
						border-radius: 0;
						text-align: center;
						align-content: center;
				}

			}

		</style>

	</head>
	<body>

		<div style="width:100%"><a href="index.html">Return To Main Page</a></div>
			<div class="page-cont">

				<form class='form' method="post" enctype="multipart/form-data">

					<div class="input-cont">
						<input style="flex-grow:1" type="text" name="mystr" placeholder="Enter String To Encrypt">
					</div>

					<div class="input-cont">
						<input type="submit" value="Encrypt" style="flex-grow: 1;">
					</div>
				</form>
			</div>
			<div class="page-cont">
				<div style="display: flex; justify-content: center; flex-direction: column;">
					<?php echo "<div style='display:flex;justify-content:center; overflow-y:auto;'>Encrypted String:</div><div></div><div style='display:flex;justify-content:center; overflow-y:auto;'>"; echo $encStr; echo "</div>"; ?>
				</div>
			</div>
		</div>

	</body>

</html>
