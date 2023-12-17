<?php
session_start();
	$firstName = $_POST['name'];

	$email = $_POST['email'];
	$password = $_POST['password'];

    $password_confirmation = $_POST['password_confirmation'];
	
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
	// Database connection
	$conn = new mysqli('db','eda_docker','123','hangman_db');
	if($conn->connect_error){
		echo "$conn->connect_error";
        header("Location: index.html");
		die("Connection Failed : ". $conn->connect_error);
	} else {
		$stmt = $conn->prepare("insert into user(name, email, password_hash) values(?, ?, ?)");
		$stmt->bind_param("sss", $firstName, $email, $password_hash);
        if ($stmt->execute()) {
            $_SESSION['ad']=$firstName;
            $_SESSION['posta']=$email;
            header("Location: start.html");
            exit;
            
        } else {
            
            if ($mysqli->errno === 1062) {
                header("Location: index.html");
                die("email already taken");
            } else {
                die($mysqli->error . " " . $mysqli->errno);
            }
        }
	}

   
?>