<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'clinicmanagement';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Extract data from the POST request
    $provider_id = $_POST['provider_id'] ?? null;
    $mrn = $_POST['MRN#'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $first_name = $_POST['first_name'] ?? null;
    $m_name = $_POST['M_name'] ?? null;
    $sex = $_POST['sex'] ?? null;
    $note = $_POST['note'] ?? null;
    $email = $_POST['email'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $flag = $_POST['Flag'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;
    $race = $_POST['race'] ?? null;
    $best_time = $_POST['Best_time'] ?? null; // Adjusted parameter name
    $insurance = $_POST['insurance'] ?? null;
    $diagnosis = $_POST['diagnosis'] ?? null;

    // Handle the file upload for consent
    $consentData = null;
    if (isset($_FILES['consent']) && $_FILES['consent']['error'] === UPLOAD_ERR_OK) {
        $consentData = file_get_contents($_FILES['consent']['tmp_name']);
    }

    // Construct the SQL query with values directly inserted
    $sql = "INSERT INTO patients (provider_id, MRN, last_name, first_name, M_name, sex, note, email, dob, Flag, phone, address, race, best_time, Insurance, Diagnosis, Consent) 
            VALUES ('$provider_id', '$mrn', '$last_name', '$first_name', '$m_name', '$sex', '$note', '$email', '$dob', '$flag', '$phone', '$address', '$race', '$best_time', '$insurance', '$diagnosis', :consent)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':consent', $consentData, PDO::PARAM_LOB);

    // Execute the prepared statement
    $stmt->execute();

    // Redirect to a success page or perform other actions upon successful insertion
    header("Location: home.php");
    exit();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
