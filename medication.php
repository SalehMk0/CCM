<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $pharmacy = $_POST['pharmacy'];
    $location = $_POST['location'];
    $brand = $_POST['brand'];
    $generic = $_POST['generic'];
    $start = $_POST['start'];
    $stop = $_POST['stop'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $unit = $_POST['unit'];
    $notes = $_POST['notes'];
    $hold = isset($_POST['hold']) ? 1 : 0; // Convert checkbox value to 1 or 0
    $until = $_POST['until'];
    $patient_name = $_POST['patient_name'];
    $patient_id = $_POST['patient_id'];

  
 $servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinicmanagement";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO medication (Pharmacy, location, Brand, Generic, start, stop, Dosage, Frequency, Unit, Notes, hold, hold_until, patient_name, patient_id)
        VALUES (:pharmacy, :location, :brand, :generic, :start, :stop, :dosage, :frequency, :unit, :notes, :hold, :until, :patient_name, :patient_id)");

        $stmt->bindParam(':pharmacy', $pharmacy);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':generic', $generic);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':stop', $stop);
        $stmt->bindParam(':dosage', $dosage);
        $stmt->bindParam(':frequency', $frequency);
        $stmt->bindParam(':unit', $unit);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':hold', $hold);
        $stmt->bindParam(':until', $until);
        $stmt->bindParam(':patient_name', $patient_name);
        $stmt->bindParam(':patient_id', $patient_id);

        $stmt->execute();

        header("Location: home.php");
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null; // Close connection
}
?>
