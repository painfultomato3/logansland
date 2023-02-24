<?php 

	session_start();

	if(!isset($_SESSION["id"])){

		header("location: password.php");

	}

	require_once('config.php');

	if($_SERVER["REQUEST_METHOD"] == "POST"){

		if(isset($_POST['addnewbillbtn'])){

			//********************************************************
			//				ADD BILLS TO DB
			//********************************************************

			$lender = $_POST['lender'];
			$dueday = $_POST['dueday'];
			$amount = $_POST['amount'];

			if(empty($lender) or empty($dueday) or empty($amount)){

				echo "missing information";
			}else{

				$sql = "INSERT INTO bills (lender, dueday, amount) VALUES ('".$lender."', '".$dueday."', '".$amount."')";

				if($link->query($sql)){

					echo "GOOD";
				}

			}

		}else{

			//********************************************************
			//				DELETE BILLS FROM DB
			//********************************************************

			$sql = "SELECT * FROM bills";

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

					$sql = "DELETE FROM bills WHERE id = '".$allids[$i]."'";

					if($link->query($sql)){

						echo "DONE";
						break;
					}
				}
			}
		}
	}

?>
<!DOCTYPE html>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Add A Bill</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<style>

			.page-cont{

				width: 100%;
				display: flex;
				justify-content: center;


			}

			.addnewbill-cont{
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


				th{
					border: solid 1px black;

				}

			}

			.editforms {
				display: none;
			}

		</style>
		<script type="text/javascript">


			autofilldate = function(){

				var d = new Date().toISOString()

				document.getElementById('date').value = d.split("T")[0] + "T" + d.split("T")[1].split(":")[0].replace(d.split("T")[1].split(":")[0], d.split("T")[1].split(":")[0]-7) + ":" + d.split("T")[1].split(":")[1] + ":" + d.split("T")[1].split(":")[2].replace('Z', '');

			}

			showaddnewbill = function(){

				document.getElementById('addnewbill-cont').style.display = "block";

			}

			closeaddnewbill = function(){

				document.getElementById('addnewbill-cont').style.display = "none";
			}

			showedit = function(itemid) {

				document.getElementById('entry' + itemid).style.display = 'none';
				document.getElementById('entry' + itemid + 'editform').style.display = 'table-row';
			}

			canceledit = function(itemid) {

				document.getElementById('entry' + itemid).style.display = 'table-row';
				document.getElementById('entry' + itemid + 'editform').style.display = 'none';
			}

		</script>

	</head>
	<body>
		
		<div style="width:100%"><a href="index.php">Return To Main Page</a></div>


		<div class="page-cont"><button onclick="showaddnewbill()">Record New Bill</button></div>
		<div class="page-cont"><table style='width:100%;'><tbody><tr><th>Current Time: <?php date_default_timezone_set('MST'); echo date('m/d/Y h:i:s a', time()); ?></th></tr></tbody></table></div>

		<div class="page-cont" id="page-cont">

			<?php 
				//********************************************************
				//				GET UPCOMING BILLS
				//********************************************************

				$sql = "SELECT * FROM bills";

				$allbills = array();

				if($result = $link->query($sql)){

					if($result->num_rows != 0){

						while($row = mysqli_fetch_array($result)){

							$temp = array("id"=>$row['id'], "lender"=>$row['lender'], "dueday"=>$row['dueday'], "amount"=>$row['amount']);

							array_push($allbills, $temp);

						}
					}else{

						echo "No Results";
					}
				}

				//$billssorted = array_column($allbills, 'dueday');
				//array_multisort($billssorted, SORT_DESC, $allbills);

				usort($allbills, fn($a, $b) => $a['dueday'] <=> $b['dueday']);		//IF WANT TO SORT CHRONOLOGICALLY


				//********************************************************
				//				SORT BY NEXT UP
				//********************************************************


				$temp = array();
				$today = date('d', time());


				foreach ($allbills as $item) {

					if ($item['dueday'] >= $today){

						$temp2 = array("id"=>$item['id'], "lender"=>$item['lender'], "dueday"=>$item['dueday'], "amount"=>$item['amount']);

						array_push($temp, $temp2);
					}
					
				}

				foreach ($allbills as $item) {

					if ($item['dueday'] < $today){

						$temp2 = array("id"=>$item['id'], "lender"=>$item['lender'], "dueday"=>$item['dueday'], "amount"=>$item['amount']);

						array_push($temp, $temp2);
					}
					
				}

				$allbills = $temp;


				echo "<table style='width:100%;'><tbody><tr><th><h4>Lender</h4></th><th><h4>Due Day</h4></th><th><h4>Amount</h4></th><th><h4>Action</h4></th></tr>";

				foreach($allbills as $items){

					echo "<tr id='entry".$items['id']."'><th>".$items['lender']."</th><th>".$items['dueday']."</th><th>$".number_format($items['amount'], 2)."</th><th><form style='display:flex;justify-content:center;' method='post'><input type='hidden'  name='".$items['id']."'><input style='margin:2px;' type='submit' class='button' value='Delete' name='delete".$items['id']."'/><input style='margin:2px;' type='button' class='button' value='Edit' onclick='showedit(".$items['id'].")'/></form></th></tr>";

					echo "<tr id='entry".$items['id']."editform' class='editforms'><form method='post' action='submitedit.php' enctype='multipart/form-data'><th><input type='text' name='lenderedit' placeholder='Lender' value='".$items['lender']."'></th><th><input type='text' name='duedayedit' placeholder='DueDay' value='".$items['dueday']."'></th><th><input type='text' name='amountedit' placeholder='Amount' value='".$items['amount']."'></th><th><input type='hidden'  name='".$items['id']."'><input style='margin:2px;' type='submit' class='button' value='Done' name='submit".$items['id']."'/><input style='margin:2px;' type='button' class='button' value='Cancel' onclick='canceledit(".$items['id'].")'/></th></form></tr>";

				}


				$total = 0.00;

				foreach ($allbills as $item){

					$total = $total + $item['amount'];
				}


				echo "<tr style='height:10px;'></tr><tr><th style='border:none;'></th><th>TOTAL:</th><th>$".number_format($total, 2)."</th><th style='border:none;'></th></tr>";

				echo "</tbody></table>";
			?>

			<div class="addnewbill-cont" id="addnewbill-cont">
				<div class="page-cont">

					<form class='form' method="post" enctype="multipart/form-data">

						<div style="margin-top: 5px;margin-left:5px;">
							<button type="button" onclick="closeaddnewbill()">X</button>
						</div>
						<div class="input-cont">
							<input style="width: 100%;" type="text" name="lender" placeholder="Lender">
						</div>

						<div class="input-cont">
							<input style="width: 100%;" type="text" name="dueday" placeholder="Due Day">
						</div>

						<div class="input-cont">
							<input style="width: 100%;" type="text" name="amount" placeholder="Amount">
						</div>

						<div class="input-cont">
							<input type="submit" value="Submit" style="flex-grow: 1;" name='addnewbillbtn'>
						</div>

					</form>
				</div>
			</div>
		</div>

	</body>
</html>











