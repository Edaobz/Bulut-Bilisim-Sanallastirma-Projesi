<?php
require_once('database.php');

$highestScore=0;
$kullaniciId=0;
session_start();
// Database connection
$conn = new mysqli('db', 'eda_docker', '123', 'hangman_db');
if ($conn->connect_error) {
    echo "$conn->connect_error";
    die("Connection Failed : " . $conn->connect_error);
} else {
    if(isset($_SESSION['posta'])) 
{
    $posta = $_SESSION['posta'];
}

$kayit="SELECT * FROM user WHERE email='$posta'";
$result = $conn->query($kayit);
$kullaniciId="0";
if ($result) {
    // Sorgudan dönen sonuçların kontrolü
    if ($result->num_rows > 0) {
        // Eğer bir sonuç varsa, ID'yi al
        $row = $result->fetch_assoc();
        $kullaniciId = $row['id'];
    }
}

$sql = "SELECT * FROM game WHERE user_id = $kullaniciId";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result); // Fetch a row from the result set
    $sql = "DELETE FROM game WHERE user_id = $kullaniciId";
    $conn->query($sql);

    $play=$_POST['playinput'];
    
        if($play!=6){
            $highestScore = $row["highest_score"] + 1;
        }else{
            $highestScore = $row["highest_score"] ;
        }
    
   
 
    $sql = "INSERT INTO game (user_id, highest_score) VALUES ('$kullaniciId','$highestScore')";
    
    $conn->query($sql);
} else {
    $highestScore = 1; // Set the initial highest score if no row is found
    $sql = "INSERT INTO game (user_id, highest_score) VALUES ('$kullaniciId','$highestScore')";
   
    $conn->query($sql);
}


}

if(isset($_SESSION['posta'])) 
{
    $posta = $_SESSION['posta'];
}

$kayit="SELECT * FROM user WHERE email='$posta'";
$result = $conn->query($kayit);
$kullaniciId="0";
if ($result) {
    // Sorgudan dönen sonuçların kontrolü
    if ($result->num_rows > 0) {
        // Eğer bir sonuç varsa, ID'yi al
        $row = $result->fetch_assoc();
        $kullaniciId = $row['id'];
        header("Location: game.html");
    }
}




?>
