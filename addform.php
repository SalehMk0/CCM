<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection parameters
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'clinicmanagement';

    try {
        // Create a PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("ERROR: Could not connect. " . $e->getMessage());
    }

    // Get form data
    $formName = $_POST['form_name'];
    $formDescription = $_POST['form_description'];

    // File handling
    $fileUpload = $_FILES['form_file'];
    $fileName = $fileUpload['name'];
    $fileTmpName = $fileUpload['tmp_name'];
    $fileError = $fileUpload['error'];

    // Check if file is uploaded without errors
    if ($fileError === UPLOAD_ERR_OK) {
        // Get file extension
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Define a unique file name to avoid overwriting files with the same name
        $uniqueFileName = uniqid('form_') . '.' . $fileExtension;

        // Move uploaded file to a desired directory
        $uploadPath = 'uploads/' . $uniqueFileName; // Change 'uploads/' to your desired directory
        move_uploaded_file($fileTmpName, $uploadPath);

        // Insert data into the database
        $sql = "INSERT INTO forms (form_name, form_details, form_file) VALUES (:formName, :formDescription, :uploadPath)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':formName', $formName);
        $stmt->bindParam(':formDescription', $formDescription);
        $stmt->bindParam(':uploadPath', $uploadPath);

        // Execute the query
        try {
            $stmt->execute();
            // Redirect to a success page or perform any desired action
            header("Location: home.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error uploading the file.";
    }
}
?>
