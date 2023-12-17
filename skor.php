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
    $highestScore = $row["highest_score"] ;
    $sql = "INSERT INTO game (user_id, highest_score) VALUES ('$kullaniciId','$highestScore')";
    
    $conn->query($sql);
} else {
    $highestScore = 0; // Set the initial highest score if no row is found
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
    }
}




?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=	, initial-scale=1.0">
    <title>Leaderboard</title>
 
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Sıra</th>
            <th>Kullanıcı Adı</th>
            <th>Kazanma Sayısı</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // SQL sorgusu - leaderboard tablosundan verileri çek
        $sql = "SELECT user.name, game.highest_score 
        FROM game  
        INNER JOIN user ON user.id = game.user_id
        ORDER BY game.highest_score DESC";


        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Sıra numarası için bir değişken tanımla
            $rank = 1;

            // Verileri döngü ile al ve tabloya ekle
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $rank++ . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["highest_score"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tabloda veri bulunamadı</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>