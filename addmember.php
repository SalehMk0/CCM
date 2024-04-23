<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish a database connection (replace with your actual database credentials)
    $servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinicmanagement";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the data for insertion
    $fullName = $_POST['fullName'];
    $provider_id = $_POST['provider_id'];
    $phoneNumber = $_POST['phoneNumber'];
    $dob = $_POST['dob'];

    // SQL query to insert data into the "members" table
    $sql = "INSERT INTO members (provider_id, fullname, phone, dob)
            VALUES ('$provider_id', '$fullName', '$phoneNumber', '$dob')";

    // Perform the query
    if ($conn->query($sql) === TRUE) {
        header("Location: home.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
