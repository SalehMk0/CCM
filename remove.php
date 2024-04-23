<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinicmanagement";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_patient'])) {
    // Check if patient_id is set and is a valid integer
    if (isset($_POST['patient_id']) && is_numeric($_POST['patient_id'])) {
        $patient_id = $_POST['patient_id'];

        // Use proper query execution to remove the patient
        // Make sure to handle SQL injection by using prepared statements

        // Prepare and execute the DELETE query
        $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect after successful deletion
            header("Location: home.php#content1"); // Adjust the URL as needed
            exit(); // Ensure no further code execution after redirection
        } else {
            // Redirect with a query parameter to show the failure message
            header("Location: home.php?delete_failed=true");
            exit();
        }
    } else {
        echo "Invalid patient ID.";
    }
}
?>