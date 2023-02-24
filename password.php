<?php 

	if($_SERVER["REQUEST_METHOD"] == "POST"){

		$passcode = $_POST["1"] . $_POST["a"] . $_POST["b"] . $_POST["c"];

		if($passcode == "8146"){
			session_start();
			$_SESSION["id"] = time();

			header("location: index.php");
		}else{

			$pinerror = "Incorrect Pin";
		}
	}

?>
<!doctype HTML>
<html>
	<head>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<style>

			h1 { 
				font-family: helvetica;
				text-align:center;
			}

			.pin-code{ 
				padding: 0; 
				margin: 0 auto; 
				display: flex;
				justify-content:center;
				
			} 
 
			.pin-code input { 
				border: none; 
				text-align: center; 
				width: 48px;
				height:48px;
				font-size: 36px; 
				background-color: #F3F3F3;
				margin-right:5px;
			} 



			.pin-code input:focus { 
				border: 1px solid #573D8B;
				outline:none;
			} 


			input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}
			
		</style>

		<script>

			moveOnMax =function (field, nextFieldID) {
				if (field.value.length == 1) {
					document.getElementById(nextFieldID).focus();
				}
			}

			submitForm = function() {
				document.myform.submit();
			}

		</script>

	</head>
	<body>

		<div style="width:100%;height:100px;"></div>
		<h1>Enter Passcode</h1>

		<div class="pin-code">
			<form name="myform" method="post" action="" enctype="multipart/form-data">
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="1" name="1" onkeyup="moveOnMax(this,'a')" />
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="a" name="a" onkeyup="moveOnMax(this,'b')" />
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="b" name="b" onkeyup="moveOnMax(this,'c')" />
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="c" name="c" onkeyup="submitForm()" />
			</form>
		</div>
		<div style="width:100%;display:flex;justify-content:center;margin-top:10px;"><h3 style="color:red;"><?php echo $pinerror; ?></h3></div>

	</body>
</html>