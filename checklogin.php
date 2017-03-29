<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	$con = mysqli_connect("localhost", "root","") or die(mysqli_error()); //Connect to server
	mysqli_select_db($con,"marketplace") or die("Cannot connect to database"); //Connect to database
	
	$name = mysqli_real_escape_string($con,$_POST['name']);
	$key = mysqli_real_escape_string($con,$_POST['key']);
		
	$query = mysqli_query($con,"SELECT * from customer WHERE cname='$name'"); //Query the users table if there are matching rows equal to $username
	$exists = mysqli_num_rows($query); //Check if the customer exists
	
	if($exists > 0){ //IF there are no returning rows or no existing username
		session_start();
		$_SESSION['name'] = $name;
		$_SESSION['key'] = $key;
		//echo $_SESSION['name'];
		Print '<script>window.location.assign("home.php");</script>';
	}else{
		Print '<script>alert("Incorrect Username!");</script>';
		Print '<script>window.location.assign("index.php");</script>'; //
	}
}
?>