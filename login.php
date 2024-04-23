<?php
session_start(); // Start the session at the beginning of the script

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'clinicmanagement';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT provider_id FROM Providers WHERE email = :email AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['email'] = $email;
        $_SESSION['provider_id'] = $user['provider_id']; 
        header('Location: home.php');
    } else {
        header('Location: index.php');
    }
} else {
    echo "No login form submitted!";
}
?>
