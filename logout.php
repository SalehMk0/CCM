<?php
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to the login page (replace with your login page URL)
    header("Location: index.php");
    exit();
}
?>