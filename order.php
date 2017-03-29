<html>
	<head>
		<title>Home</title>
	</head>
	<?php
		session_start();
		if($_SESSION['name']){ //checks if name is logged in
			$name = $_SESSION['name']; //assigns name value
		}
		else{
			header("location:index.php"); // redirects if name is not logged in
		}
		
	?>
	<body>
		<?php
			echo "<h4> Welcome " .$name . " !</h4>";
			?>
			<a href="logout.php">Click here to logout</a><br><br>
			<a href="home.php">Click here to go back</a><br><br>
			<?php
			$con = mysqli_connect("localhost", "root","") or die(mysql_error()); //Connect to server
			mysqli_select_db($con,"marketplace") or die("Cannot connect to database"); //connect to database
			?>
			<table border="1px">
				<tr>
					<th>Product</th>
					<th>Quantity</th>
					<th>Message</th>
				</tr>
			<?php
			foreach($_GET as $key=>$value){
				if(!empty($value) && isset($value)) {
					
					$product = urldecode($key);
					$value = urldecode($value);
					
					$query = $con -> prepare("select * from product where pstatus = 'available' and pname = ? ");
					$query->bind_param("s",$product);
					$query->execute();
					$result = $query -> get_result();
					
					if(!$result) {
						die("Database query failed.");
					}elseif(mysqli_num_rows($result) == 0){
						echo "<tr>";
						echo "<td>".$product."</td>";
						echo "<td>".$value."</td>";
						echo "<td>Product Not Available</td></tr>";
					}else{
						while($row = $result->fetch_assoc()){
							echo "<tr>";
							echo "<td>".$product."</td>";
							echo "<td>".$value."</td>";
							echo "<td>Order placed</td></tr>";
							
							$query = $con->prepare("select * from purchase where cname = ? and pname = ?");
							$query->bind_param("ss",$name,$product);
							$query->execute();
							$purchases = $query -> get_result();
							
							if(mysqli_num_rows($purchases) == 0){
								//INSERT NEW RECORD
								$price = $row["pprice"] * $value;
								$insert_query = $con -> prepare("insert into purchase values (?,?,now(),?,?,'pending')");
								$insert_query -> bind_param("ssss",$name,$product,$value,$price);
								$insert_query -> execute();
							}else{
								$flag = false;
								while($purchases_result = $purchases->fetch_assoc()){
									if($purchases_result["status"] === 'pending'){
										//UPDATE THE SAME RECORD
										$flag = false;
										$price = $row["pprice"] * $value;
										try{
											$pending_query = $con->prepare ("update purchase set putime = now(), quantity = quantity+?, puprice = puprice+? where cname = ? and pname = ? and status = 'pending';");
											$pending_query -> bind_param("ssss",$value,$price,$name,$product);
											$pending_query -> execute();
										}catch(PDOException $e){
											echo $e->getMessage();
										}
										
									}if($purchases_result["status"] === 'complete'){
										//INSERT NEW RECORD
										$flag = true;
										continue;
									}else{
										//INSERT NEW RECORD
										$flag = true;
										continue;
									}
								}
								if($flag === true){
									//INSERT NEW RECORD
									$flag = false;
									$price = $row["pprice"] * $value;
									$insert_query = $con -> prepare("insert into purchase values (?,?,now(),?,?,'pending')");
									$insert_query -> bind_param("ssss",$name,$product,$value,$price);
									$insert_query -> execute();
								}
							}
						}
					}
				}
			}
		?>
	</body>
</html>