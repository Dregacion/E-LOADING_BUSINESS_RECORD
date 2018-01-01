<?php require_once("../includes/db_connection.php"); ?> 
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>


<?php
	
	$order = "";
	$sort = "";

	if(isset($_GET['order'])){
		$order = $_GET['order'];
	}else{
		$order = 'id';
	}

	if(isset($_GET['sort'])){
		$sort = $_GET['sort'];
	}else{
		$sort = 'DESC';
	} 

	$result = mysqli_query($connection, "SELECT * FROM load_records ORDER BY $order $sort"); 


	if(isset($_POST['search'])){

	    $searchColumn = $_POST['column'];
        $searchValue = $_POST['valueToSearch'];

        if($searchColumn == 'Username'){

        	$query  = "SELECT * FROM load_records WHERE Username LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == 'Date'){

        	$Date_col = 'Date';

        	$query  = "SELECT * FROM load_records WHERE ".$Date_col." LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == 'Time'){

        	$Time_col = 'Time';

        	$query  = "SELECT * FROM load_records WHERE ".$Time_col." LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == 'Mobile Number'){

        	$Number_col = 'Mobile_Number';

        	$query  = "SELECT * FROM load_records WHERE ".$Number_col." LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == 'Load Amount'){

        	$query  = "SELECT * FROM load_records WHERE Amount_of_Load LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == 'Amount Paid'){

        	$query  = "SELECT * FROM load_records WHERE Amount_Paid LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == 'Network'){

        	$query  = "SELECT * FROM load_records WHERE Network LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == 'All'){

        	$Date_col = 'Date';
        	$Time_col = 'Time';

            $query = "SELECT * FROM load_records WHERE CONCAT(Username, ".$Date_col.", ".$Time_col.", Mobile_Number, Amount_of_Load, Amount_Paid, Network) LIKE '%".$searchValue."%'";

            $resultSet  = mysqli_query($connection, $query);

        }else if($searchColumn == NULL AND $searchValue == NULL){

            $message = "Please enter a value to search.";
            echo "<script type='text/javascript'>alert('$message');</script>"; 

            $resultSet = mysqli_query($connection, "SELECT * FROM load_records ORDER BY $order $sort");

        }else{

        	$resultSet = mysqli_query($connection, "SELECT * FROM load_records ORDER BY $order $sort");
        }

    } else {

        $resultSet = mysqli_query($connection, "SELECT * FROM load_records ORDER BY $order $sort"); 
    }
	
?>


<?php 

echo "
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='shortcut icon' href='Images/icon.ico'>
    <title>E-Loading Business Record</title>
    <link href='stylesheets/styles.css' rel='stylesheet' type='text/css'>
    <link href='bootstrap_css/bootstrap.min.css' rel='stylesheet'>
    <script src='bootstrap_js/bootstrap.min.js'></script>
    <script src='Jquery/jquery.min.js'></script>    
</head>

<body>
";

	if(mysqli_num_rows($result) > 0){

		$sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';

		echo "
			<div class='smart_loads'>

				<center><h2> Manage Mobile Loads </h2></center><br><br>
				
				<a href='calculate.php' class='a'><button style='float:left; font-size: 13px;' class='btn btn-default'><img src='images/calculate.png' width='20px;' height='20px;' style='margin-right:3px;'>Calculate</button></a>

				<form action='manage_loads.php' method='POST'>

					<input class='form-control' type='submit' name='search' value='SEARCH' style='display:inline; width: 80px; height: 35px; color:white; background-color: #2db7b9; float:right;'>

					<input class='form-control' style='display:inline; width: 250px; height: 35px; float:right; margin-right:20px;' name='valueToSearch' placeholder='Search..'>


					<div class='form-group'>                    
		                    <div class='col-sm-10'> 
		                        <select class='selectpicker form-control' style='display:inline; float:right; width: 250px; margin-right:20px;' required='required' name='column'>
		                        	
		                            <option selected='selected'>All</option>
		                            <option>Username</option>
									<option>Date</option>
									<option>Time</option>
		                            <option>Mobile Number</option>
									<option>Load Amount</option>
									<option>Amount Paid</option>
									<option>Network</option>

		                        </select>
		                    </div> 
		   
		            </div>

		            <br><br>
			";

		echo "
		<table class='table'>
			<tr>
				<th><a href='?order=Username&&sort=$sort' class='table_header' title='Sort ASC/DESC'> Username </a></th>
				<th><a href='?order=Date&&sort=$sort' class='table_header' title='Sort ASC/DESC'> Date<br>(YYYY-M-D) </a></th>
				<th><a href='?order=Time&&sort=$sort' class='table_header' title='Sort ASC/DESC'> Time 24-hour<br>(HH:MM:SS) </a></th>
				<th><a href='?order=Mobile_Number&&sort=$sort' class='table_header' title='Sort ASC/DESC'> Mobile Number </a></th>
				<th><a href='?order=Amount_of_Load&&sort=$sort' class='table_header' title='Sort ASC/DESC'> Load Amount </a></th>
				<th><a href='?order=Amount_Paid&&sort=$sort' class='table_header' title='Sort ASC/DESC'> Amount Paid </a></th>
				<th><a href='?order=Network&&sort=$sort' class='table_header' title='Sort ASC/DESC'> Network </a></th>
				<th> Action </th>
			</tr>	
		";


	if(mysqli_num_rows($resultSet) > 0){

		while($load = mysqli_fetch_assoc($resultSet)){

			$Username = $load['Username'];
			$Date = $load['Date'];
			$Time = $load['Time'];
			$Mobile_Number = $load['Mobile_Number'];
			$Amount_of_Load = $load['Amount_of_Load'];
			$Amount_Paid = $load['Amount_Paid'];
			$Network = $load['Network'];

			echo "
			<tr> 
				<td> $Username </td>
				<td> $Date </td>
				<td> $Time </td>
				<td> $Mobile_Number </td>
				<td> $Amount_of_Load </td>
				<td> $Amount_Paid </td>
				<td> $Network </td>

				<td><a href=delete_load.php?id=".$load["id"]." onclick=\"return confirm('Are you sure you want to delete this record?')\"; class='delete'><img src='images/delete.png' width='20px;' height='20px;' /></a></td>

			";

			echo "</tr>";

		}

	}else{
			echo "
				<tr>
	                <center>
	                    <td colspan='8'><h5> No results found.</h5></td>
	                </center>
	            </tr>
            ";
		}	

echo "
	</table>
	</form>
	";
		
	}else{	
		
	echo "
		<div style='width: 800px; margin-top: 100px; margin-left: 120px;'>
			<center>
				<h2> There are no recorded load transactions yet. </h2>
				<br> <hr> <br>
			</center>
		</div>

		";

  	}	

echo "
</body>
</html> 
";

?>