<?php

include_once('./connection.php');

//<!--Check user inputs-->
//    <!--Define error messages-->
$missingFirstName = '<p><strong>Please enter a First Name!</strong></p>';
$missingLastName = '<p><strong>Please enter a Last Name!</strong></p>';
$missingUsername = '<p><strong>Please enter a username!</strong></p>';
$missingContact = '<p><strong>Please enter a contact!</strong></p>';
$missingEmail = '<p><strong>Please enter your email address!</strong></p>';
$invalidEmail = '<p><strong>Please enter a valid email address!</strong></p>';
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and inlcude one capital letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your password</strong></p>';
//    <!--Get username, email, password, password2-->
//Get username
$errors="";
if(empty($_POST["first_name"])){
    $errors = $missingFirstName;
}else{
    $first_name = filter_var($_POST["first_name"], FILTER_SANITIZE_STRING);   
}
if(empty($_POST["last_name"])){
    $errors = $missingLastName;
}else{
    $last_name = filter_var($_POST["last_name"], FILTER_SANITIZE_STRING);   
}
if(empty($_POST["username"])){
    $errors = $missingUsername;
}else{
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);   
}
if(empty($_POST["contact_no"])){
    $errors = $missingContact;
}else{
    $contact_no = filter_var($_POST["contact_no"], FILTER_SANITIZE_STRING);   
}

$name = $first_name.' '.$last_name;
//Get email
if(empty($_POST["email"])){
    $errors = $missingEmail;   
}else{
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors = $invalidEmail;   
    }
}
//Get passwords
if(empty($_POST["password"])){
    $errors = $missingPassword; 
}elseif(!(strlen($_POST["password"])>6
         and preg_match('/[A-Z]/',$_POST["password"])
         and preg_match('/[0-9]/',$_POST["password"])
        )
       ){
    $errors = $invalidPassword; 
}else{
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING); 
    if(empty($_POST["confirm_password"])){
        $errors = $missingPassword2;
    }else{
        $password2 = filter_var($_POST["confirm_password"], FILTER_SANITIZE_STRING);
        if($password !== $password2){
            $errors = $differentPassword;
        }
    }
}
//If there are any errors print error
if($errors){
    $resultMessage = '<div class="alert alert-danger">' . $errors .'</div>';
    echo $resultMessage;
    exit;
}

//no errors

//Prepare variables for the queries
$username = mysqli_real_escape_string($conn, $username);
$email = mysqli_real_escape_string($conn, $email);
$password = mysqli_real_escape_string($conn, $password);
$hash = md5($password);
//$password = hash('sha256', $password);
//128 bits -> 32 characters
//256 bits -> 64 characters
//If username exists in the users table print error
$sql = "SELECT * FROM user_details WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error running the query!</div>';
//    echo '<div class="alert alert-danger">' . mysqli_error($conn) . '</div>';
    exit;
}
$results = mysqli_num_rows($result);
if($results){
    echo '<div class="alert alert-danger">That username is already registered. Do you want to log in?</div>';  exit;
}
//If email exists in the users table print error
$sql = "SELECT * FROM user_details WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
if(!$result){
    echo '<div class="alert alert-danger">Error running the query!</div>'; exit;
}
$results = mysqli_num_rows($result);
if($results){
    echo '<div class="alert alert-danger">That email is already registered. Do you want to log in?
    <a class="btn btn-default" href="#id01"> YES </a>
    </div>'; exit;
}
//Create a unique  activation code
/*$activationKey = bin2hex(openssl_random_pseudo_bytes(16));*/
   /* byte: unit of data = 8 bits
    bit: 0 or 1
    16 bytes = 16*8 = 128 bits
    (2*2*2*2)*2*2*2*2*...*2
    16*16*...*16
    32 characters*/

//Insert user details and activation code in the users table

$sql = "INSERT INTO user_details(`name`, `username`, `hash`, `password`, `email`, `contact_no`) VALUES ('$name', '$username', '$hash', '$password', '$email','$contact_no')";
$result = mysqli_query($conn, $sql);
if(!$result){
    echo '<div class="alert alert-danger">There was an error inserting the users details in the database!</div>'; 
    exit;
}

//Send the user an email with a link to activate.php with their email and activation code
/*$message = "Please click on this link to activate your account:\n\n";*/
//$message .= "http://mynotes.thecompletewebhosting.com/activate.php?email=" . urlencode($email) . "&key=$activationKey";
/*if(mail($email, 'Confirm your Registration', $message, 'From:'.'developmentisland@gmail.com')){
       echo "<div class='alert alert-success'>Thank for your registring! A confirmation email has been sent to $email. Please click on the activation link to activate your account.</div>";
}*/
        echo '<div class="alert alert-success">You have been succesfully signed up, go to login !!!</div>'; 
        header("location:login.html");
exit();
?>