<?php 

	require_once('config.php');

	//********************************************************
	//				EDIT BILLS IN DB
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

			if(isset($_POST['submit' . $allids[$i]])){

				$sql = "SELECT * FROM bills WHERE id = '".$allids[$i]."'";

				if($result = $link->query($sql)){
					if($result->num_rows != 0){
						while($row = mysqli_fetch_array($result)){

							if(isset($_POST["lenderedit"])){
								$lender = $_POST["lenderedit"];
							}else{
								$lender = $row['lender'];
							}

							if(isset($_POST["amountedit"])){
								$amount = $_POST["amountedit"];
							}else{
								$amount = $row['amount'];
							}

							if(isset($_POST["duedayedit"])){
								$dueday = $_POST["duedayedit"];
							}else{
								$dueday = $row['dueday'];
							}

							$sql = "UPDATE bills SET dueday = '".$dueday."', amount = '".$amount."', lender = '".$lender."' WHERE id = ".$allids[$i];

							if($link->query($sql)){
								header("location: bills.php");
							}
						}
					}
				}
			}
		}


?>