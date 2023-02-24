<?php 

	if($_SERVER["REQUEST_METHOD"] == "POST"){

		$passcode = $_POST["1"] . $_POST["a"] . $_POST["b"] . $_POST["c"];

		if($passcode == "8146"){
			session_start();
			$_SESSION["id"] = time();

			header("location: index.html");
		}else{

			echo "Incorrect Pin";
		}
	}

?>
<!doctype HTML>
<html>
	<head>

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

		<h1>PIN code 1.0</h1>
		<div class="pin-code">
			<form name="myform" method="post" action="" enctype="multipart/form-data">
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="1" name="1" onkeyup="moveOnMax(this,'a')" />
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="a" name="a" onkeyup="moveOnMax(this,'b')" />
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="b" name="b" onkeyup="moveOnMax(this,'c')" />
				<input type="password" inputmode="numeric" pattern="[0-9]*" maxlength=1 id="c" name="c" onkeyup="submitForm()" />
			</form>
		</div>

	</body>
</html>