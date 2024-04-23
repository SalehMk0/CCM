<?php
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

// Process the registration form
if(isset($_POST['reg'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $phone = $_POST['number'];
    
    try {
        // Insert user data into the database after confirmation
        $sql = "INSERT INTO Providers (provider_name, email, password, contact_number) VALUES (:name, :email, :password, :phone)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        
        // Execute the prepared statement
        $stmt->execute();
        
        header('Location: index.php');
    } catch(PDOException $e) {
        echo "ERROR: " . $e->getMessage();
    }
} else {
    echo "No registration form submitted!";
}
?>