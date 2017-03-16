<?php
ob_start();
session_start();
if( isset($_SESSION['user'])!="" ){
	header("Location: dashbord.php");
}
include_once 'config.php';
$error = false;
if ( isset($_POST['btn-signup']) ) {

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

	$pass = trim($_POST['pass']);
	$pass = strip_tags($pass);
	$pass = htmlspecialchars($pass);

	$studentID = trim($_POST['studentID']);
	$studentID = strip_tags($studentID);
	$studentID = htmlspecialchars($studentID);
	
	// email validation
	if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$error = true;
		$emailError = "Please enter valid email address.";
	} else {
		//  email exist or not
		$query = "SELECT userEmail FROM users WHERE userEmail='$email'";
		$result = mysql_query($query);
		$count = mysql_num_rows($result);
		if($count!=0){
			$error = true;
			$emailError = "Provided Email is already in use.";
		}
	}
	
	//studentID val
	if(empty($studentID)){
		$error=true;
		$studentIDError="Please enter youur student ID.";
	}
	else if(strlen($studentID)!=8){
		$error=true;
		$studentIDError="Must be 8 characters long.";
	}
	else if(!preg_match("/^(?:0|[1-9][0-9]*)$/",$studentID)){
		$studentID=true;
		$studentIDError="May only contain numbers.";
	}
	// password validation
	if (empty($pass)){
		$error = true;
		$passError = "Please enter password.";
	} else if(strlen($pass) < 6) {
		$error = true;
		$passError = "Password must have atleast 6 characters.";
	}

	// password encrypt using SHA256();
	$password = hash('sha256', $pass);
	if( !$error ) {
		$query = "INSERT INTO users(firstName,lastName,studentId,userEmail,userPass) VALUES('$firstname','$lastname','$studentID','$email','$password')";
		$res = mysql_query($query);

		if ($res) {
			$errTyp = "success";
			$errMSG = "Successfully registered, you may login now";
			unset($firstname);
			unset($lastname);
			unset($studentID);
			unset($email);
			unset($pass);
		} else {
			$errTyp = "danger";
			$errMSG = "Something went wrong, try again later...";
		}

	}
}
?>
<?php ob_end_flush(); ?>