<?php

	session_start();

	if(!isset($_SESSION["id"])){

		header("location: password.php");

	}

	require_once('config.php');

	if($_SERVER["REQUEST_METHOD"] == 'POST'){

		$bill = $_POST["bill"];

		$sql = "SELECT * FROM bills WHERE lender = '".$bill."'";

		if($result = $link->query($sql)){

			while($row = mysqli_fetch_array($result)){
				echo $row['lender'];

			}
		}
	}
?>
<!doctype HTML>
<html>
	<head>
	</head>
	<body>
		<form method="post" action="" enctype="multipart/form-data">
			<input type="text" name="bill" placeholder="Enter">
			<input type="Submit" value="Search">

		</form>
	</body>
</html>
