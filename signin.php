<?php
session_start();

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('db', 'eda_docker', '123', 'hangman_db');
    if ($conn->connect_error) {
        echo "$conn->connect_error";
        die("Connection Failed : " . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password_hash'])) {
                // Password is correct, redirect to the home page or another secure page
                $_SESSION['posta']=$email;
               

                header("Location: start.html");
                exit;
            } else {
                // Password is incorrect
              
                header("Location: index.html");
        exit;
            }
        } else {
            header("Location: index.html");
        exit;
        }
    }
?>
