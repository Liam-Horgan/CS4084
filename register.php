<?php
//ob_start();
session_start();
 include('config.php');
 
 if ( isset($_SESSION['userId'])!="" ) {
  header("Location: dashboard.php");
  exit;
 }
$error = false;
 if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	 
	// clean user inputs to prevent sql injections
	
	$firstname = trim($_POST['firstname']);
	$firstname = strip_tags($firstname);
	$firstname = htmlspecialchars($firstname);
	
	$lastname = trim($_POST['lastname']);
	$lastname = strip_tags($lastname);
	$lastname = htmlspecialchars($lastname);
	
	$email = trim($_POST['email']);
	$email = strip_tags($email);
	$email = htmlspecialchars($email);
	
	$pwd = trim($_POST['pwd']);
	$pwd = strip_tags($pwd);
	$pwd = htmlspecialchars($pwd);
	
	$id = trim($_POST['id']);
	$id = strip_tags($id);
	$id = htmlspecialchars($id);
	
	// email validation
	if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$error = true;
		$emailError = "Please enter valid email address.";
	} else {
		//  email exist or not
		$sql = "SELECT userEmail FROM users WHERE userEmail='$email'";
		$result = mysqli_query($db,$sql);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		$count = mysqli_num_rows($result);
      
		// If result matched $myusername and $mypassword, table row must be 1 row
		
		if($count == 1) {
        
			$_SESSION['userId'] = $row["userId"];
         
			header("location: dashboard.php");
		}else {
         $error = true;
      }
	}
	
	//studentID val
	if(empty($id)){
		$error=true;
		$studentIDError="Please enter youur student ID.";
	}
	else if(strlen($id)!=8){
		$error=true;
		$studentIDError="Must be 8 characters long.";
	}
	else if(!preg_match("/^(?:0|[1-9][0-9]*)$/",$id)){
		$id=true;
		$studentIDError="May only contain numbers.";
	}
	// password validation
	if (empty($pwd)){
		$error = true;
		$passError = "Please enter password.";
	} else if(strlen($pwd) < 6) {
		$error = true;
		$passError = "Password must have atleast 6 characters.";
	}
	// password encrypt using SHA256();
	$password = hash('sha256', $pwd);
	if( !$error ) {
		$query = "INSERT INTO users(firstName,lastName,id,userEmail,userPass) VALUES('$firstname','$lastname','$id','$email','$pwd')";
		$res = mysql_query($query);
		if ($res) {
			$errTyp = "success";
			$errMSG = "Successfully registered, you may login now";
			unset($firstname);
			unset($lastname);
			unset($id);
			unset($email);
			unset($pwd);
		} else {
			$errTyp = "danger";
			$errMSG = "Something went wrong, try again later...";
		}
	}
} ?>

<html lang="en"><head>
    <meta charset="utf-8">


    <title>Register - Proofreaders</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS: You can use this stylesheet to override any Bootstrap styles and/or apply your own styles -->
    <link href="css/custom.css" rel="stylesheet">


</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <!-- Logo-->
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html"><span class="glyphicon glyphicon-education"></span> Proofreaders</a>
            </div>
        </div>
        <!-- /.container -->
    </nav>

<div class="container-fluid">

	
	  <div class="col-center">

			<!-- Form --> 
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<span class="glyphicon glyphicon-log-in"></span> 
						Register an account
					</h3>
				</div>
				<div class="panel-body">
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
						<div class="form-group">
							<input type="text" class="form-control" id="email" name="email" placeholder="Email">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="id" name="id" placeholder="Student/Staff ID">
						</div>
						<div class="form-group">
						<p>Select a discipline</p>
							<select class="form-control" name="discipline" id="discipline">
								<option value="one">Education and Health Sciences</option>
								<option value="two">Arts and Humanities</option>
								<option value="three">Science and Engineering</option>
								<option value="four">Business</option>
							</select>
						</div>			
						<div class="form-group">
							<input type="password" class="form-control" id="pwd" name="pwd" placeholder="Password">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="pwd2" name="pwd2" placeholder="Re-enter Password">
						</div>
						<button type="submit" class="btn btn-default">Register</button>
					</form>
					
				</div>
			</div>
			
	  </div>

	</div><!--/container-fluid-->
	</body>
</html>
