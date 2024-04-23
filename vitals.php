
<meta charset="UTF-8">
    <title>My Patients</title>
    <link rel="icon" type="image/png" href="./images/icons/logo.png">
    <link rel="stylesheet" href="styles.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>


    <nav class="shadow-bottom">
        <div class="row">
        
            <ul class="nav nav-underline">
              
 
            
      
    
       
            <li class="nav-item"><a class="nav-link" href="home.php#content1" onclick="loadContent('content1')">My Patients</a></li>
           
            <li class="nav-item"><a class="nav-link" href="home.php#new" onclick="loadContent('new')">Enroll New Patient</a></li>
            <li class="nav-item"><a class="nav-link" href="home.php#myforms" onclick="loadContent('myforms')">My Forms</a></li>
            <li class="nav-item"><a class="nav-link" href="home.php#vitals" onclick="loadContent('vitals')">Vitals</a></li>
            <li class="nav-item"><a class="nav-link" href="home.php#calls" onclick="loadContent('calls')"> Add Call</a></li>
            <li class="nav-item"><a class="nav-link" href="home.php#callHistory" onclick="loadContent('callHistory')">Calls History</a></li>



          
            
            
           
                     <li class="nav-item">
                <form method="POST" action="logout.php">
    <button type="submit" name="logout" class="btn btn-outline-danger" style="justify-content:flex-end;">Logout</button>
</form>
                </li>  <li class="nav-item">
                    <a class="nav-link" href="#settings" onclick="loadContent('settings')">Account Settings</a>
                </li></ul>  
     </div>
    </nav>
    <style>
        .box {
  height: 20px;
  width: 20px;
  margin-bottom: 15px;
  border: 1px solid black;
}

.red {
  background-color: red;
}

.green {
  background-color: green;
}

.yellow {
  background-color: orange;
}
    </style>
 <div id="vitals">
 <h2>Vitals</h2><br><br>  
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

session_start();
if (isset($_SESSION['provider_id']) && isset($_POST['vitals'])) {
    $provider_id = $_SESSION['provider_id'];
    $patientID = $_POST['patient_id'];
    $sql = "SELECT * FROM vitals WHERE patient_id=:patientID ORDER BY `date_modified` DESC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':patientID', $patientID);
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>
        <div class="container">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Patient Fullname</th>
                        <th>Blood Pressure</th>
                        <th>Temperature</th>
                        <th>SPO2</th>
                        <th>Respiration</th>
                        <th>Heart Rate</th>
                        <th>Date Modified</th>
                    </tr>
                </thead>
                <tbody>
                <?php
foreach ($rows as $row) {
    $name = $row['patient_name'];
    $dateFromDatabase = $row['date_modified'];
    $bloodPressure = $row['blood_pressure'];
    $spo2 = $row['SPO2'];
    $respiration = $row['Respiration'];
    $temperature = $row['Temperature'];
    $heartRate = $row['Heart_rate'];

    $tempColor = ($temperature > 100.3 || $temperature < 97.8) ? 'red' : (($temperature > 99.1 || $temperature < 97.9) ? 'orange' : 'green');
    $spo2Color = ($spo2 < 92) ? 'red' : (($spo2 < 95) ? 'orange' : 'green');
    $bpSystolicColor = ($bloodPressure > 140 || $bloodPressure < 90) ? 'red' : (($bloodPressure > 120 || $bloodPressure < 80) ? 'orange' : 'green');
    $respColor = ($respiration > 24 || $respiration < 12) ? 'red' : (($respiration > 20 || $respiration < 16) ? 'orange' : 'green');
    $hrColor = ($heartRate > 100 || $heartRate < 60) ? 'red' : (($heartRate > 80 || $heartRate < 50) ? 'orange' : 'green');
    $formattedDate = date("l, F jS Y", strtotime($dateFromDatabase));
?>
    <tr>
        <td><?php echo $name; ?></td>
        <td style="font-weight:bold; color:#fff; background-color: <?php echo $bpSystolicColor; ?>"><?php echo "$bloodPressure mmHg"; ?></td>
        <td style="font-weight:bold; color:#fff; background-color: <?php echo $tempColor; ?>"><?php echo "$temperature °F"; ?></td>
        <td style="font-weight:bold; color:#fff; background-color: <?php echo $spo2Color; ?>"><?php echo "$spo2 %"; ?></td>
        <td style="font-weight:bold; color:#fff; background-color: <?php echo $respColor; ?>"><?php echo "$respiration b/min"; ?></td>
        <td style="font-weight:bold; color:#fff; background-color: <?php echo $hrColor; ?>"><?php echo "$heartRate BPM"; ?></td>
        <td><?php echo $formattedDate; ?></td>
    </tr>
<?php
}
?>

                </tbody>
            </table>
            <div class="row">
                
            <div class='box red'></div><label style="margin-right: 20px; margin-left:10px;">Concerning</label>
<div class='box green'></div><label style="margin-right: 20px; margin-left:10px;">Normal</label>
<div class='box yellow'></div><label style="margin-right: 20px; margin-left:10px;">Cautious</label></div>
        </div>    <?php
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinicmanagement";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM medication WHERE patient_id='$patientID'");
    $stmt->execute();

    // Check if there are any results
    if ($stmt->rowCount() > 0) {
        // Display data in a well-organized box
        echo '<div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">';
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
            <h3 style="margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Medication Information</h3>

            <div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                <p style="font-weight: bold; margin-bottom: 5px;">Pharmacy: <?php echo $r['Pharmacy']; ?></p>
                <p style="margin-bottom: 5px;">Location: <?php echo $r['location']; ?></p>
                <p style="font-weight: bold; margin-bottom: 5px;">Brand: <?php echo $r['Brand']; ?></p>
                <p style="margin-bottom: 5px;">Generic: <?php echo $r['Generic']; ?></p>
                <p style="font-weight: bold; margin-bottom: 5px;">Start: <?php echo $r['start']; ?></p>
                <p style="margin-bottom: 5px;">Stop: <?php echo $r['stop']; ?></p>
                <p style="font-weight: bold; margin-bottom: 5px;">Dosage: <?php echo $r['Dosage']; ?></p>
                <p style="margin-bottom: 5px;">Frequency: <?php echo $r['Frequency']; ?></p>
                <p style="font-weight: bold; margin-bottom: 5px;">Unit: <?php echo $r['Unit']; ?></p>
                <p style="margin-bottom: 5px;">Notes: <?php echo $r['Notes']; ?></p>
                <p style="font-weight: bold; margin-bottom: 5px;">Hold: <?php echo $r['hold']; ?></p>

                <?php
                // Check if 'hold' is 1 or 0 to display 'Until'
                if ($r['hold'] == 1) {
                    echo '<p style="font-weight: bold;">Until: ' . $r['hold_until'] . '</p>';
                }
                ?>
            </div>
<?php
        }
        echo"<a href='edit.php#medication'><button class='btn btn-primary'>Add Medication</button></a>";
        echo '</div>';
    } else {
        echo "No records found";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>









</div>
<script>
      // Get today's date in the format yyyy-mm-dd
const today = new Date().toISOString().split('T')[0];

// Set the value of the input field with ID 'today-date' to today's date
document.getElementById('today-date').value = today;</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>