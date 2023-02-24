<?php 

	session_start();

	if(!isset($_SESSION["id"])){

		header("location: password.php");

	}

	require_once("config.php");


	//********************************************************
	//				GATHER PAST PINS
	//********************************************************

	$pastpins = array();

	$sql = "SELECT * FROM pins";

	if($result = $link->query($sql)){

		if($result->num_rows != 0){

			while($row = mysqli_fetch_array($result)){

				$temp = array("id" => $row['id'], "ester" => $row['ester'], "amount" => $row['amount'], "location" => $row['location'], "datetime" => $row['dateandtime']);

				array_push($pastpins, $temp);

			}
		}
	}

	//********************************************************
	//				SORT PAST PINS
	//********************************************************

	$pastpinssort = array_column($pastpins, 'datetime');
	array_multisort($pastpinssort, SORT_DESC, $pastpins);


	//********************************************************
	//				UPCOMING PINS
	//********************************************************

	$upcomingpins = array();



	foreach($pastpins as $items){

		if(count($upcomingpins) != 0){

			foreach($upcomingpins as $keys){

				if($items['ester'] == $keys['ester']){

					break;
				}else{

					$time = new DateTime($items['datetime']);
					if($items['ester'] == 'HCG'){
						$time->add(new DateInterval('PT' . 2280 . 'M'));
					}else{
						$time->add(new DateInterval('PT' . 4320 . 'M'));
					}
					$stamp = $time->format('m-d');

					$temp = array('ester' => $items['ester'], 'next' => $stamp);

					array_push($upcomingpins, $temp);

					break;
				}
			}
		}elseif(count($upcomingpins) == 0){

			$time = new DateTime($items['datetime']);
			if($items['ester'] == 'HCG'){
				$time->add(new DateInterval('PT' . 2280 . 'M'));
			}else{
				$time->add(new DateInterval('PT' . 4320 . 'M'));
			}
			$stamp = $time->format('m-d');

			$temp = array('ester' => $items['ester'], 'next' => $stamp);

			array_push($upcomingpins, $temp);

			continue;

		}
	}




	if($_SERVER["REQUEST_METHOD"] == "POST"){


		//********************************************************
		//				SUBMIT NEW PINS
		//********************************************************

		if(isset($_POST['submitnewpinbtn'])){

			$dateandtime = $_POST['dateandtime'];
			$ester = $_POST["ester"];
			$amount = $_POST['amount'] . " " . $_POST['measurement'];
			$location = $_POST['location'];

			if(empty($dateandtime) or empty($ester) or empty($amount) or empty($location)){

				echo "Missing required fields";
			}else{

				$sql = "INSERT INTO pins (dateandtime, ester, amount, location) VALUES ('".$dateandtime."', '".$ester."', '".$amount."', '".$location."')";

				if($link->query($sql)){

					echo "SUCCESS";
				}
			}

			

		}else{

			//********************************************************
			//				DELETE PINS FROM DB
			//********************************************************

			$sql = "SELECT * FROM pins";

			$totalrows = 0;
			$allids = array();

			if($result = $link->query($sql)){

				$totalrows = $result->num_rows;

				if($result->num_rows != 0){

					while($row = mysqli_fetch_array($result)){

						array_push($allids, $row['id']);
					}
				}
			}

			for($i = 0; $i < $totalrows; $i++){

				if(isset($_POST['delete' . $allids[$i]])){

					$sql = "DELETE FROM pins WHERE id = '".$allids[$i]."'";

					if($link->query($sql)){

						echo "DONE";
						break;
					}
				}
			}

		}
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

			.addnewpin-cont{
				width: 100%;
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				z-index: 10;
				background-color: white;

				display: none;

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

			th{
				border: solid 1px black;
			}

		</style>
		<script type='text/javascript'>

										
			autofilldate = function(){

				var d = new Date().toLocaleString('en-US', {timeZone: 'America/Denver'})

				minutes = d.split(", ")[1].split(":");
				dates = d.split(", ")[0].split("/");

				if(minutes[2].split(" ")[1] == "PM"){

					minutes[0] = parseInt(minutes[0]) + 12;

				}

				if (minutes[0].length === 1){

					minutes[0] = "0" + minutes[0];

				}

				if (minutes[1].length === 1){

					minutes[1] = "0" + minutes[1];
				}

				if (dates[0].length === 1){

					dates[0] = "0" + dates[0];
				}

				if (dates[1].length === 1){

					dates[1] = "0" + dates[1];
				}

				standardtime = dates[2] + "-" + dates[0] + "-" + dates[1] + "T" + minutes[0] + ":" + minutes[1];

				console.log(standardtime)

														
				document.getElementById('date').value = standardtime;

			}

			showaddnewpin = function(){

				document.getElementById('addnewpin-cont').style.display = "block";
			}

			closeaddnewpin = function(){

				document.getElementById('addnewpin-cont').style.display = "none";
			}

		</script>

	</head>
	<body>

		<div style="width:100%"><a href="index.php">Return To Main Page</a></div>

		<div class="page-cont"><button onclick="showaddnewpin()">Record New Pin</button></div>
		<div class="page-cont"><table style='width:100%;'><tbody><tr><th>Current Time: <?php date_default_timezone_set('MST'); echo date('m/d/Y h:i:s a', time()); ?></th></tr><!--<tr><th><h4>Next Pins</h4></th></tr>--></tbody></table></div>

			<?php 

				//echo "<table style='width:100%;'><tbody><tr>";

				//foreach($upcomingpins as $items){

				//	echo "<th>".$items['ester']."<br>".$items["next"]."</th>";
				//}

				//echo "</tbody></table>";


				//********************************************************
				//				GET PIN HISTORY
				//********************************************************

				echo "<table style='width:100%;'><tbody><tr><th><h4>Ester</h4></th><th>Amount</th><th>Location</th><th>DateTime</th><th>Action</th></tr>";

				foreach($pastpins as $items){

					if (explode(":", explode("T", $items['datetime'])[1])[0] > 12){
						$datetime = explode("-", explode("T", $items['datetime'])[0])[1] . "-" . explode("-", explode("T", $items['datetime'])[0])[2] . " " . explode(":", explode("T", $items['datetime'])[1])[0] - 12 . ":" . explode(":", explode("T", $items['datetime'])[1])[1] . " PM";
					}else{

						$datetime = explode("-", explode("T", $items['datetime'])[0])[1] . "-" . explode("-", explode("T", $items['datetime'])[0])[2] . " " . explode(":", explode("T", $items['datetime'])[1])[0] . ":" . explode(":", explode("T", $items['datetime'])[1])[1] . " AM";
					}

					echo "<tr><th>".$items['ester']."</th><th>".$items['amount']."</th><th>".$items['location']."</th><th>".$datetime."</th><th><form style='display:flex;justify-content:center;' method='post'><input type='hidden'  name='".$items['id']."'><input type='submit' class='button' value='Delete' name='delete".$items['id']."'/></form></th></tr>";
				}

				echo "</tbody></table>";
			?>

			<div class="addnewpin-cont" id="addnewpin-cont">
				<div class="page-cont">

					<form class='form' method="post" enctype="multipart/form-data">

						<div style="margin-top: 5px;margin-left:5px;">
							<button type="button" onclick="closeaddnewpin()">X</button>
						</div>
						<div class="input-cont">
							<select name="ester" style="width: 100%;">
								<option hidden>Ester</option>
								<option value='test-P'>Test Prop</option>
								<option value='TriTest'>TriTest</option>
								<option value='Mass/Stack'>Mass/Stack</option>
								<option value='HCG'>HCG</option>
							</select>
						</div>

						<div class="input-cont">
							<select name='location' style="width: 100%;">
								<option hidden>Location</option>
								<option value='RLeg'>Right Leg</option>
								<option value='LLeg'>Left Leg</option>
								<option value='RGlute'>Right Glute</option>
								<option value='LGlute'>Left Glute</option>
								<option value='RDelt'>Right Delt</option>
								<option value='LDelt'>Left Delt</option>
							</select>
						</div>

						<div class="input-cont">
							<input type='text' name='amount' placeholder='Amount' style="flex">
							<select name="measurement" style="margin-left: 3px; width: 65px">
								<option value="ml">mL</option>
								<option value="mg">mg</option>
							</select>
						</div>

						<div class="input-cont">
							<input style="flex-grow:1" type="datetime-local" id="date" name="dateandtime">
							<button onclick="autofilldate()" type="button" style="margin-left: 3px; width: 65px">AutoFill</button>
						</div>

						<div class="input-cont">
							<input name="submitnewpinbtn" type="submit" value="Submit" style="flex-grow: 1;">
						</div>

					</form>
				</div>
			</div>
		</div>

	</body>

</html>
