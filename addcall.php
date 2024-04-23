<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'clinicmanagement';
session_start();
if(isset($_SESSION['provider_id'])) {
    $provider_id = $_SESSION['provider_id'];
  }
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $who = $_POST['who'];
    $callDate = $_POST['date'];
    $callDuration = $_POST['amount'];
    $updates = $_POST['updates'];
    $Fullname = $_POST['fullname'];
    $start = $_POST['start'];
    $end = $_POST['end'];


    // SQL query to insert data into calls table
    $sql = "INSERT INTO calls (provider_id, Fullname, who, start_time, end_time,  date, amount, updates) VALUES (:provider_id, :Fullname, :who, :start, :end, :callDate, :callDuration, :updates)";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Bind parameters to the prepared statement
    $stmt->bindParam(':who', $who);
    $stmt->bindParam(':provider_id', $provider_id);
    $stmt->bindParam(':Fullname', $Fullname);
    $stmt->bindParam(':callDate', $callDate); // Make sure it matches the column name in your table
    $stmt->bindParam(':callDuration', $callDuration); // Make sure it matches the column name in your table
    $stmt->bindParam(':updates', $updates);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':end', $end);

    // Execute the prepared statement
    try {
        $stmt->execute();
        header("Location: home.php#calls");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>