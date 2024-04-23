<?php
// Your database connection code
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

// Fetch call records for each patient
$sql = "SELECT Fullname, date, amount FROM calls";
$stmt = $pdo->query($sql);
$callRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create an associative array to store call records by date for each patient
$calendar = [];
foreach ($callRecords as $record) {
    $name = $record['Fullname'];
    $callDate = date('Y-m-d', strtotime($record['date']));
    $callDuration = $record['amount'];

    // Store call duration for each patient on each date
    if (!isset($calendar[$name][$callDate])) {
        $calendar[$name][$callDate] = 0;
    }
    $calendar[$name][$callDate] += $callDuration;
}
?>

<!-- HTML displaying the calendar -->
<table border="1">
    <thead>
        <tr>
            <th>Patient Name</th>
            <th>Calendar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($calendar as $name => $dates) : ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Call Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dates as $date => $duration) : ?>
                                <tr>
                                    <td><?php echo $date; ?></td>
                                    <td><?php echo $duration; ?> minutes</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
