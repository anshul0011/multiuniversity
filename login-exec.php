<?php
	include_once('./connection.php');
	// if(
	// echo '<script type="text/javascript">',
    //  'check();',
    //  '</script>';
	// )
	// {return ;}
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Function to sanitize values received from the form. Prevents SQL injection.
/*function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysqli_real_escape_string($conn,$str);
	}*/

	$email = $_POST["email"];
	$password = $_POST["password"];
	$hash = md5($password);
	//$pass= SHA1($password);
	
	//echo $name;
	//echo $password;
	
	//Create query
	$qry="SELECT * FROM `user_details` WHERE `email` ='$email' AND `hash`='$hash' ";
	$result=mysqli_query($conn,$qry);
	
	//Check whether the query was successful or not
	
	if($result) {
		
		if(mysqli_num_rows($result) == 1) {
			//Login Successful

			session_regenerate_id();
			while($row = mysqli_fetch_assoc($result)){
				$email=$row["email"];
				$username=$row["username"];
			}
			
			$_SESSION['username'] = $username;
			$_SESSION['email'] = $email;
			
			session_write_close();
				header("location:index.php");
				exit();
			
			
		}else {
			//Login failed
			
			header("location: login-failed.html");
			exit();
		}
	}
     
       else {
		die("Query failed");
	}
?>