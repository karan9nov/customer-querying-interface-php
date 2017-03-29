<html>
	<head>
		<title>Home</title>
	</head>
	<?php
		session_start(); //starts the session
		if($_SESSION['name']){ //checks if name is logged in
		}
		else{
			header("location:index.php"); // redirects if name is not logged in
		}
		$name = $_SESSION['name']; //assigns name value
		$key = $_SESSION['key'];
	?>
	<body>
		<h2>Hello <?php Print "$name"?>!</h2>
		<a href="logout.php">Click here to logout</a><br/><br/>
		
		<h3>Here are the Search Results</h3>
		
		<table border="1px" width="100%">
			<tr>
				<th>Product Name</th>
				<th>Details</th>
				<th>Price</th>
				<th>Status</th>
				<th>Quantity</th>
			</tr>
			<?php
				$con = mysqli_connect("localhost", "root","") or die(mysql_error()); //Connect to server
				mysqli_select_db($con,"marketplace") or die("Cannot connect to database"); //connect to database
				if($key === ""){
					$query = $con->prepare("Select pname, pdescription,pprice,pstatus from product");
					$query->execute();
				}else{
					$param = "%".$key."%";
					$query = $con->prepare("Select pname, pdescription,pprice,pstatus from product where pname LIKE ? ");
					$query->bind_param("s", $param);
					$query->execute();
				}
				
				$result = $query->get_result();
				?>
				
				<form action="order.php" method="get">
				
					<?php
					
					while($row = $result->fetch_assoc())
					{					
						Print "<tr>";
							Print '<td>'. $row['pname'] . "</td>";
							Print '<td>'. $row['pdescription'] . "</td>";
							Print '<td>'. $row['pprice'] . "</td>";
							Print '<td>'. $row['pstatus'] . "</td>";?>
							<td> <input type="number" name = <?php echo rawurlencode($row['pname']); ?> > </td>
						<?php 
						Print "</tr>";
					}
					?>
					</table>
					<br>
					<input type="submit" >
				</form>
				
			<?php 
			/*
				//releasing the data
				mysqli_free_result($result);
				//closing database
				mysqli_close($con);
			*/
			?>
	</body>
</html>