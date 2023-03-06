<?php

// Include the db.php file which contains database connection code.
include "db.php";

// Check if the request has an HTTP origin header.
if (isset($_SERVER['HTTP_ORIGIN'])) {
	// If the origin is allowed, set the necessary headers for cross-origin requests.
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 1000');
	header('Content-type:application/json;charset=utf-8');
}

// If the request method is OPTIONS, it means the browser is checking
// whether it can make a cross-origin request. In this case, set the necessary headers
// and exit the script immediately without executing further code.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
		header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
	}

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
		header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
	}
	exit(0);
}

// Create an array to hold the response data, with an initial error status of false.
$res = array('error' => false);

// Initialize a variable to hold the action parameter passed in the query string.
$action = '';

// Check if an action parameter was passed in the query string.
if (isset($_GET['action'])) {
	// If an action parameter was passed, assign its value to the $action variable.
	$action = $_GET['action'];
}

// ... additional code to handle specified actions are written below ...

// Check if the action is 'login'
if ($action == 'login') {
	$email = $_POST['email']; // Get the email from the POST request
	$password = $_POST['password']; // Get the password from the POST request
	$sql = "Select * from users where email='$email' AND password= PASSWORD('$password')"; // Query to check if the email and password are valid
	$result = $conn->query($sql); // Execute the query
	$num = mysqli_num_rows($result); // Get the number of rows returned by the query
// Check if there is at least one row returned by the query
	if ($num > 0) {
		$res['message'] = "Login Successfully"; // Set the message to 'Login Successfully'
	} else {
		$res['error'] = true; // Set the error flag to true
		$res['message'] = "Your Login email or Password is invalid"; // Set the message to 'Your Login email or Password is invalid'
	}
}

// Check if the action is 'online'
if ($action == 'online') {
	$email = $_POST['email']; // Get the email from the POST request
	$logintime = date('Y-m-d H:i:s'); // Get the current datetime in 'Y-m-d H:i:s' format
	$sql = "INSERT INTO onlineusers( email, logintime) VALUES('$email', '$logintime')"; // Query to insert the email and logintime into the 'onlineusers' table
	$result = $conn->query($sql); // Execute the query
	$num = mysqli_num_rows($result); // Get the number of rows returned by the query
}

// Check if the action is 'logout'
if ($action == 'logout') {
	$onlineuser = $_POST['onlineuser']; // Get the email of the user to be logged out from the POST request
	$sql = "DELETE FROM onlineusers WHERE email='$onlineuser'"; // Query to delete the row from 'onlineusers' table with the email matching the onlineuser
	$result = $conn->query($sql); // Execute the query
// Check if the query was executed successfully
	if ($result === true) {
		$res['error'] = false; // Set the error flag to false
		$res['message'] = "User removed Successfully"; // Set the message to 'User removed Successfully'
	} else {
		$res['error'] = true; // Set the error flag to true
		$res['message'] = "Something Went Wrong"; // Set the message to 'Something Went Wrong'
	}
}
if ($action == 'emptydiagnosis') {
	// Check if the action being requested is to empty diagnosis data
	$onlineuser = $_POST['onlineuser'];
	// Get the username from the POST request

	$sql = "UPDATE `users` SET `counter`='0',`economy-counter`='0' ,`economy-sum`='0' ,`safety-counter`= '0',`safety-counter2`= '0',`safety-counter3`= '0',`safety-sum`= '0',`satisfaction-counter`= '0' ,`satisfaction-sum`= '0' ,`time-counter`= '0',`counter-safety`='0',`counter-safety2`='0',`counter-safety3`='0',`notepad-title`='',`notepad-text`='' WHERE `email`='$onlineuser'";
	// Set various user counters to 0
	$result = $conn->query($sql);
	// Execute the query
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "Variables reset";
		$counter = 0;
		// If the query was successful, set the error flag to false and display a message
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
		// If the query failed, set the error flag to true and display an error message
	}

	$sql = "DELETE FROM `notepad` WHERE `user`='$onlineuser'";
	// Delete all notepad entries for the user
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "Note deleted Successfully";
		// If the query was successful, set the error flag to false and display a message
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
		// If the query failed, set the error flag to true and display an error message
	}

	$sql = "UPDATE `bluten_options` SET `kleines`=0, `grosses`= 0,`gerin`=0, `entz`= 0,`glucose`=0, `fetts`= 0,`eisen`=0, `leber`= 0,`pankreas`=0, `niere`= 0,`elektrolyte`=0, `schild`= 0,`herz`=0, `bvitamin`= 0,`ldh`=0, `harn`= 0,`psa`=0, `hcg`= 0, `serum`= 0 WHERE `user`='$onlineuser'";
	// Reset various blood test options to 0
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
		// If the query was successful, set the error flag to false and display a message
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
		// If the query failed, set the error flag to true and display an error message
	}

	// Update urine options for online user, setting all variables to 0
	$sql = "UPDATE urin_options SET stix=0, sediment= 0, kultur= 0 WHERE user='$onlineuser'";
	// Execute the SQL query
	$result = $conn->query($sql);
	// Check if the query was successful and set appropriate message in response
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	// Update stool options for online user, setting all variables to 0
	$sql = "UPDATE stuhl_options SET probe=0, kultur= 0, untersuchung= 0 WHERE user='$onlineuser'";
	// Execute the SQL query
	$result = $conn->query($sql);
	// Check if the query was successful and set appropriate message in response
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	// Update doctor options for online user, setting all variables to 0
	$sql = "UPDATE doctor_option SET augen=0,chiru=0,derma=0,gyna=0,hals=0,kardio=0,gastro=0,pulmo=0,nephro=0,onko=0,endo=0,neurochiru=0,neuro=0,ortho=0,padi=0,psychi=0,radio=0,uro=0 WHERE user='$onlineuser'";
	// Execute the SQL query
	$result = $conn->query($sql);
	// Check if the query was successful and set appropriate message in response
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	// Update submit options for online user, setting all variables to 0
	$sql = "UPDATE submit_options SET ambulance=0,hospital=0,noappointment=0,badappointment=0,twodays=0,fivedays=0,fourweeks=0,ausstellen=0,rezept=0,diagnosis='',rezeptext='',submitted=0,wiedereinbestellen=0, labloop=0, doctorloop=0 WHERE user='$onlineuser';";
	// Execute the SQL query
	$result = $conn->query($sql);
	// Check if the query was successful and set appropriate message in response
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	// Update isclicked options for online user, setting all variables to 0
	$sql = "UPDATE `isclicked` SET  `beschreiben`=0, `akutes`=0, `medikamen`=0, `gewohn`=0, `nikotin`=0, `allergien`=0, `vegetative`=0, `gyna`=0, `psyche`=0, `familien`=0,`patientenakte`=0,`kopfinspektion`=0,`kopfnase`=0,`kopfohren`=0,`kopfmund`=0,`kopfschild`=0,`kopflymph`=0,`kopfhals`=0,`kopforient`=0,`thoraxinspektion`=0,`thoraxauskultation`=0,`wirbelinspektion`=0,`wirbelhals`=0, `wirbelfunktion`=0, `abdomeninspektion`=0,`abdomenauskultation`=0, `obereinspektion`=0,`oberebeweg`=0,`obereneurolog`=0,`oberedurch`=0,`untereinspektion`=0,`unterebeweg`=0,`untereneurolog`=0,`unteredurch`=0, `genitalinspektion`=0,`genitalrektal`=0, `temperatur`=0,`blutzucker`=0,`blutdruck`=0,`sono`=0,`ekg`=0,`lung`=0, `blood`=0,`urine`=0,`stool`=0,`doctors`=0   WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	// Check if the query was successful and set appropriate message in response
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

if ($action == 'countertimevariable') { // If the action is to update time counter variable
	$time = $_POST['time']; // Get the time value from the form data
	$step = $_POST['step']; // Get the step value from the form data
	$steptime = date('Y-m-d H:i:s'); // Get the current date and time in the specified format
	$stepandtime = $step . ' um ' . $steptime . '.'; // Combine the step and time values into a string
	$onlineuser = $_POST['onlineuser']; // Get the user email from the form data

	// Update the time counter for the user in the database
	$sql = "UPDATE `users` SET `time-counter`= `time-counter`+$time WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) { // If the query was successful
		$res['error'] = false; // Set the error flag to false
		$res['message'] = "variables Added Successfully"; // Set the success message
	} else { // If the query failed
		$res['error'] = true; // Set the error flag to true
		$res['message'] = "Somthing Went Wrong"; // Set the error message
	}

	// Insert the user's activity into the user_history table
	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";
	$result = $conn->query($sql);
	if ($result === true) { // If the query was successful
		$res['error'] = false; // Set the error flag to false
		$res['message'] = "variables Added Successfully"; // Set the success message
	} else { // If the query failed
		$res['error'] = true; // Set the error flag to true
		$res['message'] = "Somthing Went Wrong"; // Set the error message
	}
}
// Check if the action is "currentpage"
if ($action == 'currentpage') {

	// Get all the necessary data from the POST request
	$main = $_POST['main'];
	$warte = $_POST['warte'];
	$patient = $_POST['patient'];
	$anamnese = $_POST['anamnese'];
	$patientenakte = $_POST['patientenakte'];
	$laboratory = $_POST['laboratory'];
	$blood = $_POST['blood'];
	$urine = $_POST['urine'];
	$stool = $_POST['stool'];
	$sendblood = $_POST['sendblood'];
	$sendurine = $_POST['sendurine'];
	$sendstool = $_POST['sendstool'];
	$doctors = $_POST['doctors'];
	$senddoctors = $_POST['senddoctors'];
	$untersuchen = $_POST['untersuchen'];
	$nicht = $_POST['nicht'];
	$kopf = $_POST['kopf'];
	$rumpf = $_POST['rumpf'];
	$thorax = $_POST['thorax'];
	$wirbel = $_POST['wirbel'];
	$abdomen = $_POST['abdomen'];
	$obere = $_POST['obere'];
	$untere = $_POST['untere'];
	$genital = $_POST['genital'];
	$apparative = $_POST['apparative'];
	$sono = $_POST['sono'];
	$ekg = $_POST['ekg'];
	$lungen = $_POST['lungen'];
	$sendsubmit = $_POST['sendsubmit'];
	$submit1 = $_POST['submit1'];
	$submit2 = $_POST['submit2'];
	$submit3 = $_POST['submit3'];
	$lab = $_POST['lab'];
	$afterlab = $_POST['afterlab'];
	$specialties = $_POST['specialties'];
	$afterspecialties = $_POST['afterspecialties'];
	$prints = $_POST['prints'];
	$onlineuser = $_POST['onlineuser'];

	// Update the "currentpage" table with all the data

	$sql = "UPDATE `currentpage` SET `main`=$main,`warte`=$warte, `patient`=$patient, `anamnese`=$anamnese, `patientenakte`=$patientenakte, `laboratory`=$laboratory, `blood`=$blood, `urine`=$urine, `stool`=$stool, `sendblood`=$sendblood, `sendurine`=$sendurine, `sendstool`=$sendstool, `doctors`=$doctors, `senddoctors`=$senddoctors, `untersuchen`=$untersuchen, `nicht`=$nicht, `kopf`=$kopf, `rumpf`=$rumpf, `thorax`=$thorax, `wirbel`=$wirbel, `abdomen`=$abdomen, `obere`=$obere, `untere`=$untere, `genital`=$genital, `apparative`=$apparative, `sono`=$sono, `ekg`=$ekg, `lungen`=$lungen, `sendsubmit`=$sendsubmit, `submit1`=$submit1, `lab`=$lab, `afterlab`=$afterlab, `specialties`=$specialties, `afterspecialties`=$afterspecialties, `submit2`=$submit2, `submit3`=$submit3, `prints`=$prints WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

// Check if the action is "countervariable"
if ($action == 'countervariable') {


	// Get all the necessary data from the POST request
	$economy = $_POST['economy'];
	$satisfaction = $_POST['satisfaction'];
	$time = $_POST['time'];
	$step = $_POST['step'];
	$steptime = date('Y-m-d H:i:s');
	$stepandtime = $step . ' um ' . $steptime . '.'; // Combine the step and time values into a string
	$onlineuser = $_POST['onlineuser'];
	$akutes = $_POST['akutes'];
	$allergien = $_POST['allergien'];
	$beschreiben = $_POST['beschreiben'];
	$gyna = $_POST['gyna'];
	$nikotin = $_POST['nikotin'];
	$medikamen = $_POST['medikamen'];
	$gewohn = $_POST['gewohn'];
	$psyche = $_POST['psyche'];
	$vegetative = $_POST['vegetative'];
	$familien = $_POST['familien'];
	$patientenakte = $_POST['patientenakte'];
	$kopfinspektion = $_POST['kopfinspektion'];
	$kopfnase = $_POST['kopfnase'];
	$kopfmund = $_POST['kopfmund'];
	$kopfohren = $_POST['kopfohren'];
	$kopflymph = $_POST['kopflymph'];
	$kopfschild = $_POST['kopfschild'];
	$kopfhals = $_POST['kopfhals'];
	$kopforient = $_POST['kopforient'];
	$thoraxinspektion = $_POST['thoraxinspektion'];
	$thoraxauskultation = $_POST['thoraxauskultation'];
	$wirbelinspektion = $_POST['wirbelinspektion'];
	$wirbelfunktion = $_POST['wirbelfunktion'];
	$wirbelhals = $_POST['wirbelhals'];
	$abdomeninspektion = $_POST['abdomeninspektion'];
	$abdomenauskultation = $_POST['abdomenauskultation'];
	$obereinspektion = $_POST['obereinspektion'];
	$oberebeweg = $_POST['oberebeweg'];
	$obereneurolog = $_POST['obereneurolog'];
	$oberedurch = $_POST['oberedurch'];
	$untereinspektion = $_POST['untereinspektion'];
	$unterebeweg = $_POST['unterebeweg'];
	$untereneurolog = $_POST['untereneurolog'];
	$unteredurch = $_POST['unteredurch'];
	$genitalinspektion = $_POST['genitalinspektion'];
	$genitalrektal = $_POST['genitalrektal'];
	$temperatur = $_POST['temperatur'];
	$blutzucker = $_POST['blutzucker'];
	$blutdruck = $_POST['blutdruck'];
	$sono = $_POST['sono'];
	$ekg = $_POST['ekg'];
	$lung = $_POST['lung'];
	$blood = $_POST['blood'];
	$urine = $_POST['urine'];
	$stool = $_POST['stool'];

	// Update user's counters in the database
	$sql = "UPDATE `users` SET `economy-counter`=`economy-counter`+$economy ,`counter`=`counter`+1 ,`economy-sum`=  `economy-counter`,`satisfaction-counter`= `satisfaction-counter`+$satisfaction ,`satisfaction-sum`=  `satisfaction-counter`*100/`counter`,`time-counter`= `time-counter`+$time WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	// Update the respecitve columns in the `isclicked` table 
	$sql = "UPDATE `isclicked` SET `akutes`=$akutes WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	$sql = "UPDATE `isclicked` SET `beschreiben`=$beschreiben WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `allergien`=$allergien WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `familien`=$familien WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `gewohn`=$gewohn WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `vegetative`=$vegetative WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `nikotin`=$nikotin WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `psyche`=$psyche WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `gyna`=$gyna WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `medikamen`=$medikamen WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `patientenakte`=$patientenakte WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopfinspektion`=$kopfinspektion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopfnase`=$kopfnase WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopfmund`=$kopfmund WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopfohren`=$kopfohren WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopfhals`=$kopfhals WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopfschild`=$kopfschild WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopforient`=$kopforient WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `kopflymph`=$kopflymph WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `thoraxinspektion`=$thoraxinspektion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `thoraxauskultation`=$thoraxauskultation WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `wirbelinspektion`=$wirbelinspektion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `wirbelfunktion`=$wirbelfunktion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `wirbelhals`=$wirbelhals WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";

	}
	$sql = "UPDATE `isclicked` SET `abdomeninspektion`=$abdomeninspektion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `abdomenauskultation`=$abdomenauskultation WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `obereinspektion`=$obereinspektion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `oberebeweg`=$oberebeweg WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `obereneurolog`=$obereneurolog WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `oberedurch`=$oberedurch WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `untereinspektion`=$untereinspektion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `unterebeweg`=$unterebeweg WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `untereneurolog`=$untereneurolog WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `unteredurch`=$unteredurch WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `genitalinspektion`=$genitalinspektion WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `genitalrektal`=$genitalrektal WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `temperatur`=$temperatur WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `blutzucker`=$blutzucker WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `blutdruck`=$blutdruck WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `sono`=$sono WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `ekg`=$ekg WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `lung`=$lung WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `blood`=$blood WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `urine`=$urine WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	$sql = "UPDATE `isclicked` SET `stool`=$stool WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	// Store user's actions in the user_history table
	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}
//Check if the action is "facharztvariable"
if ($action == 'facharztvariable') {
	$satisfaction = $_POST['satisfaction'];
	$time = $_POST['time'];
	$step = $_POST['step'];
	$steptime = date('Y-m-d H:i:s');
	$stepandtime = $step . ' um ' . $steptime . '.';
	$onlineuser = $_POST['onlineuser'];

    //Update the counters (satisfaction) of the online user based on the values received from POST
	$sql = "UPDATE `users` SET `counter`=`counter`+1 , `satisfaction-counter`= `satisfaction-counter`+$satisfaction ,`satisfaction-sum`=  `satisfaction-counter`*100/`counter`,`time-counter`= `time-counter`+$time WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	//Store the online user's actions in user_history
	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

// Check if the action is "facharzteconomyvariable"
if ($action == 'facharzteconomyvariable') {

	$economy = $_POST['economy'];
	$doctors = $_POST['doctors'];
	$onlineuser = $_POST['onlineuser'];
	//Update the counters (economy) of the online user based on the values received from POST

	$sql = "UPDATE `users` SET`economy-counter`=`economy-counter`+$economy ,`economy-sum`=  `economy-counter` WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	//Update 'isclicked' table to store whether or not the online user refered to any doctor
	$sql = "UPDATE `isclicked` SET `doctors`=$doctors WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

// Check if the action is "submitlabloop"
if ($action == 'submitlabloop') {
	$onlineuser = $_POST['onlineuser'];

//Store in submit_options table whether or not the online user chose Wiederbestellen option before the lab results. This is to restrict users to choose Wiederbestellen option only once and prevent looping forever.
	$sql = "UPDATE `submit_options` SET `labloop`=`labloop`+1  WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}
// Check if the action is "submitdoctorloop"
if ($action == 'submitdoctorloop') {
	$onlineuser = $_POST['onlineuser'];

	//Store in submit_options table whether or not the online user chose Wiederbestellen option before the specialties results. This is to restrict users to choose Wiederbestellen option only once and prevent looping forever.
	$sql = "UPDATE `submit_options` SET `doctorloop`=`doctorloop`+1  WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//Check if the action is "addnote"
if ($action == 'addnote') {
	$notetitle = $_POST['notetitle'];
	$notetext = $_POST['notetext'];
	$onlineuser = $_POST['onlineuser'];

	//Insert into notepad the note entered by the online user
	$sql = "INSERT INTO `notepad`( `user`,`title`, `text`) VALUES('$onlineuser','$notetitle','$notetext')";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "Note Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//Check if the action is "getnotes"
if ($action == 'getnotes') {

	//Get all the notes that are stored for the  user
	$sql = "SELECT * FROM `notepad` ";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row, );
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

//Check if the action is "getpagestatus"
if ($action == 'getpagestatus') {

	// Get information on which page the  user is currently at
	$sql = "SELECT * FROM `currentpage`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row, );
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

//Check if the action is "getclicks"

if ($action == 'getclicks') {

	//Retrieve information about the options selected by all the users based on information stored in isclicked table
	$sql = "SELECT * FROM `isclicked` ";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row, );
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

//Check whether the action is "getcounters"
if ($action == 'getcounters') {

	//Retrieve the counters (and format to appropriate decimal values) for all the users for the repetition
	$sql = "SELECT ROUND(`economy-sum`,2) as `economy`, ROUND(`safety-sum`,2) as `safety`, ROUND(`satisfaction-sum`,2) as `satisfaction`, `time-counter` as `time`, `email` as `email`, `tutor` as `tutor`, `studentid` as `studentid` FROM `users`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row, );
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

////Check whether the action is "getcounters"
if ($action == 'getoriginalcounters') {

	//Retrieve the counters (and format to appropriate decimal values) for all the users for the original diagnosis
	$sql = "SELECT ROUND(`economy-sum`,2) as `economy`, ROUND(`safety-sum`,2) as `safety`, ROUND(`satisfaction-sum`,2) as `satisfaction`, `time-counter` as `time`, `email` as `email`, `tutor` as `tutor`, `studentid` as `studentid` FROM `users_original`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row, );
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

// if the action is "removenotes", delete all the notes
if ($action == 'removenotes') {

	$notetitle = $_POST['notetitle'];
	$notetext = $_POST['notetext'];
	$onlineuser = $_POST['onlineuser'];

	$sql = "DELETE FROM notepad WHERE user='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "Note deleted Successfully";

	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

//if the action is "removenote", delete a particular note that the user wishes to delete
if ($action == 'removenote') {

	$notetitle = $_POST['notetitle'];
	$onlineuser = $_POST['onlineuser'];
	$ndate = $_POST['ndate'];

	$sql = "DELETE FROM notepad WHERE user='$onlineuser' AND title='$notetitle' AND `date`='$ndate'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "Note deleted Successfully";

	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

//if the action is "getsteps", retrieve the actions done by all the users
if ($action == 'getsteps') {
	$sql = "SELECT * FROM `user_history` ";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

//if the action is "getnotes", retrieve all the notes from all users
if ($action == 'getnotess') {
	$sql = "SELECT * FROM `notepad` ";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

//Check is the action is "sendblood"
if ($action == 'sendblood') {


	$kleines = $_POST['bloodkleines'];
	$grosses = $_POST['bloodgrosses'];
	$gerin = $_POST['bloodgerin'];
	$entz = $_POST["bloodentz"];
	$glucose = $_POST["bloodglucose"];
	$fetts = $_POST["bloodfetts"];
	$eisen = $_POST["bloodeisen"];
	$leber = $_POST["bloodleber"];
	$pankreas = $_POST["bloodpankreas"];
	$niere = $_POST["bloodniere"];
	$elektrolyte = $_POST["bloodelektrolyte"];
	$schild = $_POST["bloodschild"];
	$herz = $_POST["bloodherz"];
	$bvitamin = $_POST["bloodbvitamin"];
	$ldh = $_POST["bloodldh"];
	$harn = $_POST["bloodharn"];
	$psa = $_POST["bloodpsa"];
	$hcg = $_POST["bloodhcg"];
	$serum = $_POST["bloodserum"];
	$onlineuser = $_POST["onlineuser"];


	//For the online user, update the values of options for blood tests that the user selected
	$sql = "UPDATE `bluten_options` SET `kleines`=$kleines, `grosses`= $grosses,`gerin`=$gerin, `entz`= $entz,`glucose`=$glucose, `fetts`= $fetts,`eisen`=$eisen, `leber`= $leber,`pankreas`=$pankreas, `niere`= $niere,`elektrolyte`=$elektrolyte, `schild`= $schild,`herz`=$herz, `bvitamin`= $bvitamin,`ldh`=$ldh, `harn`= $harn,`psa`=$psa, `hcg`= $hcg, `serum`= $serum WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//Check if the action is "senddoctors"
if ($action == 'senddoctors') {


	$augen = $_POST['augen'];
	$chiru = $_POST['chiru'];
	$derma = $_POST['derma'];
	$gyna = $_POST["gyna"];
	$hals = $_POST["hals"];
	$kardio = $_POST["kardio"];
	$gastro = $_POST["gastro"];
	$pulmo = $_POST["pulmo"];
	$nephro = $_POST["nephro"];
	$onko = $_POST["onko"];
	$endo = $_POST["endo"];
	$neurochiru = $_POST["neurochiru"];
	$neuro = $_POST["neuro"];
	$ortho = $_POST["ortho"];
	$padi = $_POST["padi"];
	$psychi = $_POST["psychi"];
	$radio = $_POST["radio"];
	$uro = $_POST["uro"];
	$onlineuser = $_POST["onlineuser"];

	//For the online user, update the values of options for specialty referrals that the user selected
	$sql = "UPDATE `doctor_option` SET `augen`=$augen,`chiru`=$chiru,`derma`=$derma,`gyna`=$gyna,`hals`=$hals,`kardio`=$kardio,`gastro`=$gastro,`pulmo`=$pulmo,`nephro`=$nephro,`onko`=$onko,`endo`=$endo,`neurochiru`=$neurochiru,`neuro`=$neuro,`ortho`=$ortho,`padi`=$padi,`psychi`=$psychi,`radio`=$radio,`uro`=$uro WHERE  `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//If the action is "getblood", retrieve the information about the blood tests done by all users
if ($action == 'getblood') {
	$sql = "SELECT * FROM `bluten_options`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}
//If the action is "geturine", retrieve the information about the urine tests done by all users
if ($action == 'geturine') {
	$sql = "SELECT * FROM `urin_options`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}
//If the action is "sendurine", store the information about the selection of urine tests done by the online user
if ($action == 'sendurine') {
	$stix = $_POST['urinstix'];
	$sediment = $_POST['urinsediment'];
	$kultur = $_POST['urinkultur'];
	$onlineuser = $_POST["onlineuser"];


	$sql = "UPDATE `urin_options` SET `stix`=$stix, `sediment`= $sediment, `kultur`= $kultur WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

//If the action is "getstool", retrieve the information about the stool tests done by all users
if ($action == 'getstool') {
	$sql = "SELECT * FROM `stuhl_options`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

//If the action is "sendstool", store the information about the selection of stool tests done by the online user
if ($action == 'sendstool') {


	$probe = $_POST['stoolprobe'];
	$culture = $_POST['stoolculture'];
	$untersuchen = $_POST['untersuchen'];
	$onlineuser = $_POST["onlineuser"];


	$sql = "UPDATE `stuhl_options` SET `probe`=$probe, `kultur`= $culture, `untersuchung`= $untersuchen WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//if the action is "getusers", retreive user and counter information about all the users 
if ($action == 'getusers') {
	$sql = "SELECT * FROM `users`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}
// if ($action == 'getuserinfo') {
// 	$sql = "SELECT * FROM `usersdata`";
// 	$result = $conn->query($sql);
// 	$num = mysqli_num_rows($result);
// 	$userData = array();
// 	if ($num > 0) {
// 		while ($row = $result->fetch_assoc()) {
// 			array_push($userData, $row);
// 		}
// 		$res['error'] = false;
// 		$res['user_Data'] = $userData;

// 	} else {
// 		$res['error'] = false;
// 		$res['message'] = "No Data Found!";
// 	}

// }

//if the action is "sendsubmit1", store the values entered and selected by the online user as diagnosis in the submit_options table
if ($action == 'sendsubmit1') {


	$diagnose = $_POST['diagnose'];
	$ambulance = $_POST['ambulance'];
	$hospital = $_POST['hospital'];
	$noappointment = $_POST["noappointment"];
	$twodays = $_POST["twodays"];
	$fourweeks = $_POST["fourweeks"];
	$badappointment = $_POST["badappointment"];
	$fivedays = $_POST["fivedays"];
	$ausstellen = $_POST["ausstellen"];
	$rezept = $_POST["rezept"];
	$rezepttext = $_POST["rezepttext"];
	$onlineuser = $_POST["onlineuser"];


	$sql = "UPDATE `submit_options` SET `ambulance`=$ambulance,`hospital`=$hospital,`noappointment`=$noappointment,`badappointment`=$badappointment,`twodays`=$twodays,`fivedays`=$fivedays,`fourweeks`=$fourweeks,`ausstellen`=$ausstellen,`rezept`=$rezept,`diagnosis`='$diagnose',`rezeptext`='$rezepttext'  WHERE `user`='$onlineuser';";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}
//if the action is "sendsubmit2", update the values entered and selected by the online user as diagnosis in the submit_options table
if ($action == 'sendsubmit2') {


	$diagnose = $_POST['diagnose'];
	$ambulance = $_POST['ambulance'];
	$hospital = $_POST['hospital'];
	$noappointment = $_POST["noappointment"];
	$twodays = $_POST["twodays"];
	$fourweeks = $_POST["fourweeks"];
	$badappointment = $_POST["badappointment"];
	$fivedays = $_POST["fivedays"];
	$ausstellen = $_POST["ausstellen"];
	$rezept = $_POST["rezept"];
	$rezepttext = $_POST["rezepttext"];
	$wiedereinbestellen = $_POST["wiedereinbestellen"];
	$labloop = $_POST["labloop"];
	$onlineuser = $_POST["onlineuser"];


	$sql = "UPDATE `submit_options` SET `ambulance`=$ambulance,`hospital`=$hospital,`noappointment`=$noappointment,`badappointment`=$badappointment,`twodays`=$twodays,`fivedays`=$fivedays,`fourweeks`=$fourweeks,`ausstellen`=$ausstellen,`rezept`=$rezept,`diagnosis`='$diagnose',`rezeptext`='$rezepttext',`wiedereinbestellen`=$wiedereinbestellen, `labloop`=$labloop  WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

//if the action is "sendsubmit3", update the values entered and selected by the online user as diagnosis in the submit_options table
if ($action == 'sendsubmit3') {


	$diagnose = $_POST['diagnose'];
	$ambulance = $_POST['ambulance'];
	$hospital = $_POST['hospital'];
	$noappointment = $_POST["noappointment"];
	$twodays = $_POST["twodays"];
	$fourweeks = $_POST["fourweeks"];
	$badappointment = $_POST["badappointment"];
	$fivedays = $_POST["fivedays"];
	$ausstellen = $_POST["ausstellen"];
	$rezept = $_POST["rezept"];
	$rezepttext = $_POST["rezepttext"];
	$wiedereinbestellen = $_POST["wiedereinbestellen"];
	$doctorloop = $_POST["doctorloop"];
	$onlineuser = $_POST["onlineuser"];


	$sql = "UPDATE `submit_options` SET `ambulance`=$ambulance,`hospital`=$hospital,`noappointment`=$noappointment,`badappointment`=$badappointment,`twodays`=$twodays,`fivedays`=$fivedays,`fourweeks`=$fourweeks,`ausstellen`=$ausstellen,`rezept`=$rezept,`diagnosis`='$diagnose',`rezeptext`='$rezepttext',`wiedereinbestellen`=$wiedereinbestellen,`doctorloop`=$doctorloop, `submitted`=1  WHERE `user`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}
//if the action is "sendsubmit4", update the values entered and selected by the online user as diagnosis in the submit_options table
if ($action == 'sendsubmit4') {

	$onlineuser = $_POST["onlineuser"];


	$sql = "UPDATE `submit_options` SET  `submitted`=1  WHERE `user`='$onlineuser';";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

//if the action is "submitrezeptvariable", update the counters of the users table for the online user based on the values received from POST
if ($action == 'submitrezeptvariable') {

	$economy = $_POST["economy"];
	$step = $_POST['step'];
	$steptime = date('Y-m-d H:i:s');
	$stepandtime = $step . ' um ' . $steptime . '.';
	$onlineuser = $_POST["onlineuser"];

	$sql = "UPDATE `users` SET `economy-counter`=`economy-counter`+$economy ,`economy-sum`=  `economy-counter` WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

	//Add the user action for the online user to user_history table
	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";

	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//if the action is "submitvariable", update the counters of the users table for the online user based on the values received from POST
if ($action == 'submitvariable') {

	$safety = $_POST["safety"];
	$economy = $_POST["economy"];
	$step = $_POST['step'];
	$steptime = date('Y-m-d H:i:s');
	$stepandtime = $step . ' um ' . $steptime . '.';
	$onlineuser = $_POST["onlineuser"];

	$sql = "UPDATE `users` SET `economy-counter`=`economy-counter`+$economy ,`counter`=`counter`+1 ,`counter-safety`=`counter-safety`+1,`economy-sum`=  `economy-counter`,  `safety-counter`= (`safety-counter`+$safety),`safety-sum` = $safety WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	//Add the user action for the online user to user_history table
	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";

	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//if the action is "submitvariable2", update the counters of the users table for the online user based on the values received from POST

if ($action == 'submitvariable2') {


	$safety = $_POST["safety"];

	$step = $_POST['step'];
	$steptime = date('Y-m-d H:i:s');
	$stepandtime = $step . ' um ' . $steptime . '.';
	$onlineuser = $_POST["onlineuser"];

	$sql = "UPDATE `users` SET `counter`=`counter`+1 , `counter-safety2`=`counter-safety2`+1 , `safety-counter2`= (`safety-counter2`+$safety),`safety-sum` = $safety WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	//Add the user action for the online user to user_history table

	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";

	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}
//if the action is "submitvariable3", update the counters of the users table for the online user based on the values received from POST

if ($action == 'submitvariable3') {



	$safety = $_POST["safety"];

	$step = $_POST['step'];
	$steptime = date('Y-m-d H:i:s');
	$stepandtime = $step . ' um ' . $steptime . '.';
	$onlineuser = $_POST["onlineuser"];

	$sql = "UPDATE `users` SET `counter`=`counter`+1 , `counter-safety3`=`counter-safety3`+1 , `safety-counter3`= (`safety-counter3`+$safety),`safety-sum` = $safety WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
	//Add the user action for the online user to user_history table

	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";

	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//if the action is "sendthesteps", update the user_history table by adding the particular step done by the online user
if ($action == 'sendthesteps') {
	$step = $_POST['step'];
	$steptime = date('Y-m-d H:i:s');
	$stepandtime = $step . ' um ' . $steptime . '.';
	$onlineuser = $_POST["onlineuser"];

	$sql = "INSERT INTO `user_history`( `user`,`steps`,`steptime`) VALUES('$onlineuser','$step','$steptime')";

	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}
}

//if the action is "rezeptvariable", update the counters (economy) of the users table for the online user based on the values received from POST

if ($action == 'rezeptvariable') {


	$economy = $_POST["economy"];
	$onlineuser = $_POST["onlineuser"];


	$sql = "UPDATE `users` SET `economy-counter`=`economy-counter`+$economy ,`counter`=`counter`+1 ,`economy-sum`=  `economy-counter` WHERE `email`='$onlineuser'";
	$result = $conn->query($sql);
	if ($result === true) {
		$res['error'] = false;
		$res['message'] = "variables Added Successfully";
	} else {
		$res['error'] = true;
		$res['message'] = "Somthing Went Wrong";
	}

}

// 
// if ($action == 'getpatient') {
// 	$sql = "SELECT * FROM `patients`";
// 	$result = $conn->query($sql);
// 	$num = mysqli_num_rows($result);
// 	$userData = array();
// 	if ($num > 0) {
// 		while ($row = $result->fetch_assoc()) {
// 			array_push($userData, $row);
// 		}

// 		$res = $userData;

// 	} else {
// 		$res['error'] = false;
// 		$res['message'] = "No Data Found!";
// 	}

// }

//if the action is "getdoctors, retrieve information about the specialties selection done by all the users"
if ($action == 'getdoctors') {
	$sql = "SELECT * FROM `doctor_option`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}

//if the action is "getsubmit", retreive the information about the diagnosis decisions of all users for the repition diagnosis
if ($action == 'getsubmit') {
	$sql = "SELECT * FROM `submit_options`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}
//if the action is "getoriginalsubmit", retreive the information about the diagnosis decisions of all users for the original diagnosis
if ($action == 'getoriginalsubmit') {
	$sql = "SELECT * FROM `submit_options_original`";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}
//if the action is "getdownloadstuff", retreive the information about the diagnosis decision of the particular user for the repetition diagnosis
if ($action == 'getdownloadstuff') {
	$user = $_GET['user'];
	$sql = "SELECT * FROM `submit_options` WHERE `user`='$user' ";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}
//if the action is "getoriginaldownloadstuff", retreive the information about the diagnosis decision of the particular user for the original diagnosis
if ($action == 'getoriginaldownloadstuff') {
	$user = $_GET['user'];
	$sql = "SELECT * FROM `submit_options_original` WHERE `user`='$user' ";
	$result = $conn->query($sql);
	$num = mysqli_num_rows($result);
	$userData = array();
	if ($num > 0) {
		while ($row = $result->fetch_assoc()) {
			array_push($userData, $row);
		}

		$res = $userData;

	} else {
		$res['error'] = false;
		$res['message'] = "No Data Found!";
	}

}
//close the connection to the database
$conn->close();
header("Content-type: application/json");
echo json_encode($res);
die();
?>