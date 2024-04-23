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
if(isset($_SESSION['provider_id'])) {
    $provider_id = $_SESSION['provider_id'];
  }
$sql = "SELECT COUNT(*) AS patient_count FROM patients WHERE provider_id = :provider_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':provider_id', $provider_id, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $patientCount = $result['patient_count'];
    
} 
$sql = "SELECT * FROM patients WHERE provider_id='$provider_id'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
   




?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
  .ccm-cell {
    background-color: darkgreen; 
    font-weight: bold;
    color: #fff;
}

.tcm-cell {
    background-color: darkgoldenrod; 
    font-weight: bold;
    color: #fff;
}
        #stopwatch {
  font-size: 3em;
  text-align: center;
  margin: 20px;
  }
  .popup {
    display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: white;
      padding: 10px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      z-index: 9999;
    }
    svg{
      width: 40px;
      height: 40px;
      cursor: pointer;
      margin: 5px;
    }
    #searchInput {
    border: 0.5 px #50c8f6;
    width: 150px;
    padding: 10px;
    margin-left:50px;
    display: block; 
    
    margin-bottom: 20px;
}

    </style>
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
              
 
            
      
    
       
            <li class="nav-item"><a class="nav-link" href="#content1" onclick="loadContent('content1')">My Patients</a></li>
           
            <li class="nav-item"><a class="nav-link" href="#new" onclick="loadContent('new')">Enroll New Patient</a></li>
            <li class="nav-item"><a class="nav-link" href="#myforms" onclick="loadContent('myforms')">My Forms</a></li>
            <li class="nav-item"><a class="nav-link" href="#vitals" onclick="loadContent('vitals')" disabled>Vitals</a></li>
            <li class="nav-item"><a class="nav-link" href="#calls" onclick="loadContent('calls')"> Add Call</a></li>
            <li class="nav-item"><a class="nav-link" href="#callHistory" onclick="loadContent('callHistory')">Calls History</a></li>



          
            
            
           
                     <li class="nav-item">
                <form method="POST" action="logout.php">
    <button type="submit" name="logout" class="btn btn-outline-danger" style="justify-content:flex-end;">Logout</button>
</form>
                </li>  <li class="nav-item">
                    <a class="nav-link" href="#settings" onclick="loadContent('settings')">Account Settings</a>
                </li></ul>  
     </div>
    </nav><body>
    <main class="content" class="shadow p-4 mb-4 bg-white rounded">
    <div id="settings">
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

    if (isset($_SESSION['provider_id'])) {
        $provider_id = $_SESSION['provider_id'];
        $sql = "SELECT * FROM providers WHERE provider_id='$provider_id'";
        $result = mysqli_query($conn, $sql);
      
        while ($row = mysqli_fetch_array($result)) {
            $provider_name = $row['provider_name'];
            $email = $row['email'];
            $password = $row['password'];
            $phonenb = $row['contact_number'];
    ?>
            <h3 style="text-align:center;">Account Settings</h3>
            <form method="POST" class="mx-auto" style="max-width: 300px;">
            
                <div class="row">
                    <label for="provider_name">Fullname:</label><br>
                    <input type="text" class="form-control" name="provider_name" value="<?php echo $provider_name; ?>">
                  </div>

                <div class="row">
                    <label for="email">Email:</label><br>
                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
          

            
                <div class="row">
                    <label for="contact_number">Phone Number:</label><br>
                    <input type="text" class="form-control" name="contact_number" value="<?php echo $phonenb; ?>">
                </div>

                <div class="row">
                    <label for="password">Password:</label><br>
                    <input type="password" class="form-control" name="password" value="<?php echo $password; ?>"><br>
                </div>
                <div id="teamMembers">
        
    </div>
 
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
            </div>
        </form>  <div style="padding: 20px;">
    <button type="button" onclick="addTeamMember()" class="btn btn-primary">Add Team Member</button></div>
    <div id="teamMemberForm" style="display: none;">
    <form method="POST" id="memberForm" action="addmember.php" class="mx-auto" style="max-width:300px;">
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required>
        </div>

        <input type="hidden" name="provider_id" value="<?php echo $provider_id; ?>">

        <div class="form-group">
            <label for="phoneNumber">Phone Number:</label>
            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required>
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>



    <?php
        }
    }

   
    if (isset($_POST['submit'])) {
        $new_provider_name = $_POST['provider_name'];
        $new_email = $_POST['email'];
        $new_phonenb = $_POST['contact_number'];
        $new_password = $_POST['password'];

        
        $update_query = "UPDATE providers SET provider_name='$new_provider_name', email='$new_email', contact_number='$new_phonenb', password='$new_password' WHERE provider_id='$provider_id'";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
          header("Location:index.php");
            
        } else {
            echo "Error updating provider information: " . mysqli_error($conn);
        }
    }
    ?>
</div>

    <div id="content1">
    <div class="popup" id="popup">
<div id="stopwatch">00:00:00</div>

<svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 384 512" onclick="start()"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#12d933" d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/></svg>
<svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" onclick="stop()"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#ea0b0b" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM224 192V320c0 17.7-14.3 32-32 32s-32-14.3-32-32V192c0-17.7 14.3-32 32-32s32 14.3 32 32zm128 0V320c0 17.7-14.3 32-32 32s-32-14.3-32-32V192c0-17.7 14.3-32 32-32s32 14.3 32 32z"/></svg>
<svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" onclick="reset()">
  <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#f1e90e" d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V256c0 17.7 14.3 32 32 32s32-14.3 32-32V32zM143.5 120.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z"/></svg>
<p style="color: red; font-weight: bold;">Click reset First !</p></div>
    <?php
    
    $displayTableHead = true;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($displayTableHead) {
            // Display table head once
            ?>
            <h2 style="text-align: left; display:flex; align-content:left; font-size:20px;">Dr. <?php echo"$provider_name"; ?></h2>
            <h6 style="text-align: left; display:flex; align-content:left;">Team Members</h6><p style="text-align: left; display:flex; align-content:left;"><em><?php $sql = "SELECT * FROM members WHERE provider_id = '$provider_id'";

// Perform the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($r = $result->fetch_assoc()) {
        $memberfullname = $r["fullname"];
          $provider_id =$r["provider_id"];
        $memberphone =  $r["phone"] ;
      $memberdob = $r["dob"] ;
   echo"$memberfullname <br>"; }
} else {
    echo "0 results";
}  ?></em></p>
            <label style="border: 1px solid #000; border-radius:15px; color: #000; text-align:center; font-size: larger; padding:20px; margin-top:20px;">
                <?php echo $patientCount; ?> patient(s) in your queue
            </label>
            <table class="table table-hover" style="margin-top:30px; font-size: 14px;" title="Patients">
            <tr style="background-color: #50c8f6;"><td>MRN#</td><td>Last Name</td><td>First Name</td><td>DOB</td><td>Insurance</td><td>Flags</td><td>Diagnosis</td><td>Notes</td><td></td><td>TTC</td><td>Phone Number</td><td>Timer</td><td>Consent</td><td>Vitals</td></tr>
            <?php
            $displayTableHead = false; // Set the flag to prevent further table head display
        }

        // Display individual rows
        $patientCount++;
        $patient_id= $row['patient_id'];
                $MRN = $row['MRN'];
    $last_name = $row['last_name'];
    $first_name = $row['first_name'];
    $M_name = $row['M_name'];
    $email = $row['email'];
    $DOB = $row['DOB'];
    $Sex = $row['Sex'];
    $Race = $row['Race'];
    $Status = $row['Status'];
    $address = $row['address'];
    $Street = $row['Street'];
    $City = $row['City'];
    $Zipcode = $row['Zipcode'];
    $description = $row['description'];
    $State = $row['State'];
    $Apt = $row['Apt#'];
    $phone_type = $row['phone_type'];
    $phone = $row['phone'];
    $best_time = $row['best_time'];
    $msg = $row['msg'];
    $note = $row['note'];
    $Flag = $row['Flag'];
    $Insurance = $row['Insurance'];
    $Client = $row['Client'];
    $Allergies = $row['Allergies'];
    $Diagnosis = $row['Diagnosis'];
    $imageData = $row['Consent'];
    if (!empty($imageData)) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $imageType = $finfo->buffer($imageData);
} ?>
        <tr><td><a href="#" onclick="openPopup('<?php echo $MRN; ?>')"> <?php echo $MRN; ?></a></td><td><?php echo "$last_name";?></td><td><?php echo "$first_name";?></td><td><?php echo "$DOB";?></td>
        <td><?php echo "$Insurance";?></td><td><?php $text = $Flag; if ($Flag === "CCM") {
    $class = "ccm-cell"; // Add a class for CCM
} elseif ($Flag === "TCM") {
    $class = "tcm-cell"; // Add a class for TCM
}

// Apply class to the <td> for styling
echo "<p class='$class'>$text</p>";?></td><td><?php echo "$Diagnosis";?></td>
        <td style="width:300px;"><?php echo "$note";?></td><td></td><td><?php echo "$best_time";?></td><td><?php echo '<a href="tel:' . $phone . '">' . $phone . '</a>';?></td><td><i class="fas fa-clock" onclick="togglePopup()" style="cursor: pointer;"></i></td>
        <td>
    <?php if (!empty($imageData)) : ?>
      <a href="data:<?php echo $imageType; ?>;base64,<?php echo base64_encode($imageData); ?>" target="_blank">
    <img src="data:<?php echo $imageType; ?>;base64,<?php echo base64_encode($imageData); ?>" alt="Image" width="50" height="50">
</a>
    <?php else : ?>
        No Consent Image
    <?php endif; ?>
</td>      <td><form method="POST" action="vitals.php"><input type="hidden" name="provider_id" value="<?php echo "$provider_id"; ?>"><input type="hidden" name="patient_id" value="<?php echo "$patient_id"; ?>"><input type="submit" name="vitals" class="btn btn-danger" value="Vitals"></form></td>
        <td><a href="#form"><button class="btn btn-outline-success" onclick="loadContent('form')">Form </button></a></td>
        <td><form method="POST" action="edit.php" target="_blank"><input type="hidden" name="patient_id" value="<?php echo "$patient_id"; ?>" >
        <input type="hidden" name="provider_id" value="<?php echo "$provider_id"; ?>" >
        <input type="submit" name="edit" class="btn btn-primary" value="Edit"></form></td><td><form method="POST" action="remove.php">
        <button class="btn btn-primary" type="submit" name="remove_patient">
   X</button>

                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>"></form></td></tr>
        <?php
    } 
    ?>
    </table>
</div>


        <div id="form">
         
          
          
          <form method="POST" action="form.php" >
            <input type="hidden" name="patient_id" value ="<?php echo"$patient"; ?>">
            <div class="mb-4 row">
              <label class="form-label col-sm-3 col-form-label">Who are you speaking with:</label>
              <div class="row">
                <div class="form-check mb-6" style="margin-left: 10px;">
                  <input class="form-check-input" type="checkbox" name="contactMethod" id="patient" value="Patient">
                  <label class="form-check-label" for="patient">Patient</label>
                </div>
                <div class="form-check mb-6" style="margin-left: 10px;">
                  <input class="form-check-input" type="checkbox" name="contactMethod" id="homehealthnurse" value="homehealthnurse">
                  <label class="form-check-label" for="homehealthnurse">Home Health Nurse</label>
                </div>
                <div class="form-check mb-6" style="margin-left: 10px;">
                  <input class="form-check-input" type="checkbox" name="contactMethod" id="hospicenurse" value="Hospicenurse">
                  <label class="form-check-label" for="hospicenurse">Hospice Nurse</label>
                </div>
                <div class="form-check mb-6" style="margin-left: 10px;">
                  <input class="form-check-input" type="checkbox" name="contactMethod" id="Caregiver" value="Caregiver">
                  <label class="form-check-label" for="Caregiver">Caregiver</label>
                </div>
                <div class="form-check mb-6" style="margin-left: 10px;">
                  <input class="form-check-input" type="checkbox" name="contactMethod" id="Familymember" value="Familymember">
                  <label class="form-check-label" for="Familymember">Family Member</label>
                </div>
              </div>
            </div>
            <div class="mb-4 row">
              <label class="col-sm-3 form-label" for="takingmedication">Is the patient taking all medication as prescribed?</label>
              <div class="col-sm-3">
                <select id="takingmedication" name="takingmedication" class="form-select">
                  <option value="">Select an option</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>
            <div class="mb-4 row">
            <label class="col-sm-3 form-label" for="whynot">Why Not?</label><div class="col-sm-3">
            <input type="text" class="form-control" placeholder="" aria-label="">
            </div></div>
            <div class="mb-4 row">
              <label class="col-sm-3 form-label" for="takingmedication">Does the patient have enough to cover a 30-day supply?</label>
              <div class="col-sm-3">
                <select id="takingmedication" name="supply" class="form-select">
                  <option value="">Select an option</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>
            <div class="mb-4 row">
              <label class="col-sm-3 form-label" for="takingmedication">Is the patient experiencing any side effects?</label>
              <div class="col-sm-3">
                <select id="takingmedication" name="side" class="form-select">
                  <option value="">Select an option</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>
            <div class="mb-4 row">
              <label class="col-sm-3 form-label" for="takingmedication">Is the patient currently experiencing any pain:</label>
              <div class="col-sm-3">
                <select id="takingmedication" name="side" class="form-select">
                  <option value="">Select an option</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>
            <div class="mb-4 row">
              <label class="col-sm-3 form-label" for="frequency">Location and Frequency</label><div class="col-sm-3">
              <input type="text" class="form-control" placeholder="" aria-label="">
              </div></div>
              
<div class="mb-4 row" >
  <label class="form-label col-sm-3 col-form-label">Which disciplines are in the home?</label>
  <div class="row">
    <div class="form-check mb-6" style="margin-left: 10px;">
      <input class="form-check-input" type="checkbox" name="disciplines" id="patient" value="Patient">
      <label class="form-check-label" for="patient">Skilled Nursing</label>
    </div>
    <div class="form-check mb-6" style="margin-left: 10px;">
      <input class="form-check-input" type="checkbox" name="disciplines" id="homehealthnurse" value="homehealthnurse">
      <label class="form-check-label" for="homehealthnurse">HHA</label>
    </div>
    <div class="form-check mb-6" style="margin-left: 10px;">
      <input class="form-check-input" type="checkbox" name="disciplines" id="hospicenurse" value="Hospicenurse">
      <label class="form-check-label" for="hospicenurse">Physical Therapy</label>
    </div>
    <div class="form-check mb-6" style="margin-left: 10px;">
      <input class="form-check-input" type="checkbox" name="disciplines" id="Caregiver" value="Caregiver">
      <label class="form-check-label" for="Caregiver">Occupational Therapy</label>
    </div>
    <div class="form-check mb-6" style="margin-left: 10px;">
      <input class="form-check-input" type="checkbox" name="disciplines" id="Familymember" value="Familymember">
      <label class="form-check-label" for="Familymember">Speech Therapy</label>
    </div>
  </div>
</div><hr>
<div class="mb-4 row">
  <label class="form-label col-sm-3 col-form-label">Any changes in urine:</label>
  <div class="col-sm-2">
    <div class="form-check mb-2 d-flex flex-column">
      <label class="form-check-label" for="patient">
        <input class="form-check-input me-2" type="checkbox" name="urine" id="patient" value="Patient">No
      </label>
    </div>
    <div class="form-check mb-2 d-flex flex-column">
      <label class="form-check-label" for="homehealthnurse">
        <input class="form-check-input me-2" type="checkbox" name="urine" id="homehealthnurse" value="homehealthnurse">Abnormal odor
      </label>
    </div>
    <div class="form-check mb-2 d-flex flex-column">
      <label class="form-check-label" for="hospicenurse">
        <input class="form-check-input me-2" type="checkbox" name="urine" id="hospicenurse" value="Hospicenurse">Abnormal color
      </label>
    </div>
    <div class="form-check mb-2 d-flex flex-column">
      <label class="form-check-label" for="Caregiver">
        <input class="form-check-input me-2" type="checkbox" name="urine" id="Caregiver" value="Caregiver">Increased frequency/urgency which
      </label>
    </div>
    <div class="form-check mb-2 d-flex flex-column">
      <label class="form-check-label" for="Familymember1">
        <input class="form-check-input me-2" type="checkbox" name="urine" id="Familymember1" value="Familymember1">Decreased frequency
      </label>
    </div>
    <div class="form-check mb-2 d-flex flex-column">
      <label class="form-check-label" for="Familymember2">
        <input class="form-check-input me-2" type="checkbox" name="urine" id="Familymember2" value="Familymember2">Incontinence
      </label>
    </div>
  </div>
</div><hr>


<div class="mb-4 row">
  <label class="col-sm-3 form-label" for="takingmedication">Any skin breakdown, open sores, or wounds?
  </label>
  <div class="col-sm-3">
    <select id="takingmedication" name="wounds" class="form-select">
      <option value="">Select an option</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
  </div>
</div><hr>
<div class="mb-4 row">
  <label class="col-sm-3 form-label" for="takingmedication">Is any wound care being provided?</label>
  <div class="col-sm-3">
    <select id="takingmedication" name="woundscare" class="form-select">
      <option value="">Select an option</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
  </div>
</div><hr>
<div class="mb-4 row">
  <label class="col-sm-3 form-label" for="takingmedication">Is patient ambulatory</label>
  <div class="col-sm-3">
    <select id="takingmedication" name="ambulatory" class="form-select">
      <option value="">Select an option</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
  </div>
</div><hr>
<div class="mb-4 row">
  <label class="col-sm-3 form-label" for="takingmedication">Is the patient participating in activities</label>
  <div class="col-sm-3">
    <select id="takingmedication" name="activities" class="form-select">
      <option value="">Select an option</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
  </div>
</div><hr>
<div class="mb-4 row">
  <label class="col-sm-3 form-label" for="takingmedication">Does the patient maintain a good appetite?</label>
  <div class="col-sm-3">
    <select id="takingmedication" name="appetite" class="form-select">
      <option value="">Select an option</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
  </div>
</div><hr>
<div class="mb-4 row">
  <label class="col-sm-3 form-label" for="frequency">Please Describe briefly</label><div class="col-sm-3">
  <input type="text" class="form-control" placeholder="" aria-label="" name="briefly">
  </div></div><hr>
  <div class="mb-4 row" >
    <label class="form-label col-sm-3 col-form-label">Is the patient experiencing any of the following symptoms:</label>
    <div class="col-ms-2">
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="patient" value="Patient">
        <label class="form-check-label" for="patient">Decreased urination</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="homehealthnurse" value="homehealthnurse">
        <label class="form-check-label" for="homehealthnurse">Arrhythmias</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="hospicenurse" value="Hospicenurse">
        <label class="form-check-label" for="hospicenurse">Edema/swelling</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Caregiver" value="Caregiver">
        <label class="form-check-label" for="Caregiver">Fatigue</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Familymember" value="Familymember">
        <label class="form-check-label" for="Familymember">Nausea</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Familymember" value="Familymember">
        <label class="form-check-label" for="Familymember">Headaches</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Familymember" value="Familymember">
        <label class="form-check-label" for="Familymember">Anemia</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Familymember" value="Familymember">
        <label class="form-check-label" for="Familymember">Unstable blood sugar</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Familymember" value="Familymember">
        <label class="form-check-label" for="Familymember">Unstable blood pressure
        </label>
      </div>
    </div>
  </div><hr>
  <div class="mb-4 row" >
    <label class="form-label col-sm-3 col-form-label">Education provided:</label>
    <div class="col-ms-2">
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="patient" value="Patient">
        <label class="form-check-label" for="patient">Advise against use of NSAIDS</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="homehealthnurse" value="homehealthnurse">
        <label class="form-check-label" for="homehealthnurse">Discuss healthy diet and exercise</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="hospicenurse" value="Hospicenurse">
        <label class="form-check-label" for="hospicenurse">Advise patient to limit salt, protein, and fluids</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Caregiver" value="Caregiver">
        <label class="form-check-label" for="Caregiver">Discuss disease process</label>
      </div>
      <div class="form-check mb-6" style="margin-left: 10px;">
        <input class="form-check-input" type="checkbox" name="symptoms" id="Familymember" value="Familymember">
        <label class="form-check-label" for="Familymember">Educate on edema and how to avoid</label>
      </div></div></div><hr>
      <div class="mb-4 row">
        <label class="form-label col-sm-3 col-form-label">Coordination of additional care:</label>
        <div class="col-sm-2">
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="nephrology">
              <input class="form-check-input me-2" type="checkbox" name="additionalCare" id="nephrology" value="Nephrology">Refer to Nephrology
            </label>
          </div>
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="workinappointment">
              <input class="form-check-input me-2" type="checkbox" name="additionalCare" id="workinappointment" value="Workinappointment">Work-in appointment scheduled
            </label>
          </div>
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="eyeexam">
              <input class="form-check-input me-2" type="checkbox" name="additionalCare" id="eyeexam" value="Eyeexam">Refer for eye exam
            </label>
          </div>
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="physiciannotified">
              <input class="form-check-input me-2" type="checkbox" name="additionalCare" id="physiciannotified" value="Physiciannotified">Physician notified
            </label>
          </div>
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="referbacktoPCP">
              <input class="form-check-input me-2" type="checkbox" name="additionalCare" id="referbacktoPCP" value="ReferbacktoPCP">Refer back to PCP
            </label>
          </div>
        </div>
      </div>
      <hr>
      <div class="mb-4 row">
        <label class="form-label col-sm-3 col-form-label">Is the patient experiencing any of the following symptoms:</label>
        <div class="col-sm-3">
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="sob">
              <input class="form-check-input me-2" type="checkbox" name="symptoms" id="sob" value="SOB">SOB (Shortness of Breath)
            </label>
          </div>
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="wheezing">
              <input class="form-check-input me-2" type="checkbox" name="symptoms" id="wheezing" value="Wheezing">Wheezing
            </label>
          </div>
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="knowledgedeficit">
              <input class="form-check-input me-2" type="checkbox" name="symptoms" id="knowledgedeficit" value="KnowledgeDeficit">Knowledge Deficit
            </label>
          </div>
          <div class="form-check mb-2 d-flex flex-column">
            <label class="form-check-label" for="riskforinfection">
              <input class="form-check-input me-2" type="checkbox" name="symptoms" id="riskforinfection" value="RiskForInfection">Risk for infection
            </label>
          </div>
        </div>
      </div><hr>
      <div class="mb-4 row">
        <label class="form-label col-sm-3 col-form-label">Is the patient smoking?</label>
        <div class="col-sm-2">
          <select class="form-select" name="smoking" id="smoking">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </div><hr>
      <div class="mb-4 row">
        <label class="form-label col-sm-3 col-form-label">Is the patient using O2?</label>
        <div class="col-sm-2">
          <select class="form-select" name="oxygen" id="oxygen">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </div><hr>
      <div class="mb-4 row">
        <label class="form-label col-sm-3 col-form-label">Education provided:</label>
        <div class="col-lg-5">
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="physician_adherence" value="PhysicianAdherence">
            <label class="form-check-label" for="physician_adherence">Encourage adherence with all physician and specialist appointments as recommended</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="spirometry" value="BaselineSpirometry">
            <label class="form-check-label" for="spirometry">Obtain a baseline spirometry measurement</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="smoking_cessation" value="SmokingCessation">
            <label class="form-check-label" for="smoking_cessation">Encourage smoking cessation and provide options</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="flu_vaccine" value="FluVaccine">
            <label class="form-check-label" for="flu_vaccine">Encourage and educate on importance of annual flu vaccine</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="pneumococcal_vaccine" value="PneumococcalVaccine">
            <label class="form-check-label" for="pneumococcal_vaccine">Encourage pneumococcal vaccine for appropriate population</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="medication_adherence" value="MedicationAdherence">
            <label class="form-check-label" for="medication_adherence">Adherence to medication regimen as directed by physician</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="disease_process" value="DiseaseProcess">
            <label class="form-check-label" for="disease_process">Provide education on disease process</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="exacerbation_signs" value="ExacerbationSigns">
            <label class="form-check-label" for="exacerbation_signs">Provide education on s/s of exacerbation of condition and when to notify PCP</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="rescue_medications" value="RescueMedications">
            <label class="form-check-label" for="rescue_medications">Provide education on use of rescue medications</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="notifyPCP_exacerbations" value="NotifyPCPExacerbations">
            <label class="form-check-label" for="notifyPCP_exacerbations">Education patient on when to notify PCP of exacerbations</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="medication_reconciliation" value="MedicationReconciliation">
            <label class="form-check-label" for="medication_reconciliation">Assist member with medication reconciliation</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="medication_refills" value="MedicationRefills">
            <label class="form-check-label" for="medication_refills">Monitor timely and appropriate medication refills</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="hospital_ER_usage" value="HospitalERUsage">
            <label class="form-check-label" for="hospital_ER_usage">Monitor hospital ER usage</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="education" id="hospital_admissions" value="MonitorHospitalAdmissions">
            <label class="form-check-label" for="hospital_admissions">Monitor inpatient hospital admissions</label>
          </div>
        </div>
      </div>
<hr>
<div class="mb-4 row">
  <label class="form-label col-sm-3 col-form-label">Coordination of additional care:</label>
  <div class="col-lg-5">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="additionalCare" id="workinappointment" value="Workinappointment">
      <label class="form-check-label" for="workinappointment">Work-in appointment scheduled</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="additionalCare" id="pneumococcalvaccine" value="Pneumococcalvaccine">
      <label class="form-check-label" for="pneumococcalvaccine">Refer for pneumococcal vaccine</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="additionalCare" id="fluvaccine" value="Fluvaccine">
      <label class="form-check-label" for="fluvaccine">Refer for flu vaccine</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="additionalCare" id="pulmonaryspecialist" value="Pulmonaryspecialist">
      <label class="form-check-label" for="pulmonaryspecialist">Refer to pulmonary specialist</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="additionalCare" id="pft_spirometry" value="PFT_spirometry">
      <label class="form-check-label" for="pft_spirometry">Refer for PFT/spirometry</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="additionalCare" id="referbacktoPCP" value="ReferbacktoPCP">
      <label class="form-check-label" for="referbacktoPCP">Refer back to PCP</label>
    </div>
  </div>
</div>
<div class="mb-4 row">
  <label class="form-label col-sm-3 col-form-label" for="other_notes">Other Notes:</label>
  <div class="col-sm-6">
    <input type="text" class="form-control" id="other_notes" name="other_notes">
  </div>
</div>

<div class="mb-4 row">
  <label class="form-label col-sm-3 col-form-label" for="general_note">General Note:</label>
  <div class="col-sm-6">
    <input type="text" class="form-control" id="general_note" name="general_note">
  </div>
</div>
<div class="mb-3 row">
              <div class="col-sm-3"></div>
              <div class="col-sm-1">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
            </div>
            <div id="vitals">
              <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#f01919" d="M228.3 469.1L47.6 300.4c-4.2-3.9-8.2-8.1-11.9-12.4h87c22.6 0 43-13.6 51.7-34.5l10.5-25.2 49.3 109.5c3.8 8.5 12.1 14 21.4 14.1s17.8-5 22-13.3L320 253.7l1.7 3.4c9.5 19 28.9 31 50.1 31H476.3c-3.7 4.3-7.7 8.5-11.9 12.4L283.7 469.1c-7.5 7-17.4 10.9-27.7 10.9s-20.2-3.9-27.7-10.9zM503.7 240h-132c-3 0-5.8-1.7-7.2-4.4l-23.2-46.3c-4.1-8.1-12.4-13.3-21.5-13.3s-17.4 5.1-21.5 13.3l-41.4 82.8L205.9 158.2c-3.9-8.7-12.7-14.3-22.2-14.1s-18.1 5.9-21.8 14.8l-31.8 76.3c-1.2 3-4.2 4.9-7.4 4.9H16c-2.6 0-5 .4-7.3 1.1C3 225.2 0 208.2 0 190.9v-5.8c0-69.9 50.5-129.5 119.4-141C165 36.5 211.4 51.4 244 84l12 12 12-12c32.6-32.6 79-47.5 124.6-39.9C461.5 55.6 512 115.2 512 185.1v5.8c0 16.9-2.8 33.5-8.3 49.1z"/></svg>
 <h2>Vitals</h2><br>  
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

// Fetch patient data and populate select options
$sql = "SELECT * FROM patients WHERE provider_id='$provider_id'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$patientOptions = "";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($row['last_name']) && !empty($row['first_name']) && isset($row['M_name']) && !empty($row['patient_id'])) {
        $lastName = $row['last_name'];
        $firstName = $row['first_name'];
        $middleName = !is_null($row['M_name']) ? $row['M_name'] : '';
        $fullname = $firstName . " " . $lastName; 
        $patientOptions .= "<option value='$fullname'>$fullname</option>";
    } else {
        $patientOptions .= "<option value=''>No Data Available</option>";
    }
}
?>
<form class="container" method="POST">
    <div class="col-md-12">
        <div class="col-md-12">
            <label for="fullName" class="form-label">Patient Name:</label>
        </div>
        <select class="form-control" id="patientSelect" name="fullname">
            <?php echo $patientOptions; ?>
        </select>
    </div>
    
    <br>

    <div class="row">
        <div class="col-lg-6" style="padding: 20px;">
            <label for="bloodPressure">Blood Pressure(mmHg)</label>
            <input type="text" class="form-control" id="bloodPressure" name="blood_pressure">
        </div>
        <div class="col-lg-6" style="padding: 20px;">
            <label for="heartRate">Heart Rate(BPM)</label>
            <input type="text" class="form-control" id="heartRate" name="heart_rate" required>
            
        </div></div>
        <div class="row">
        <div class="col-lg-6" style="padding: 20px;">
            <label for="spo2">SPO2(%)</label>
            <input type="text" class="form-control" id="spo2" name="spo2" >
            
        </div>
        <div class="col-lg-6" style="padding: 20px;">
            <label for="respirations">Respirations(breaths/min)</label>
            <input type="text" class="form-control" id="respirations" name="respirations">
            
        </div></div>
        <div class="row">
        <div class="col-lg-6" style="padding: 20px;">
            <label for="bloodPressure">Temperature(°F)</label>
            <input type="text" class="form-control" id="bloodPressure" name="temperature">
        </div>
        <div class="col-lg-6" style="padding: 20px;">
            <label for="heartRate">Today's Date</label>
            <input type="date" class="form-control" id="today-date" value="" disabled>
            
        </div>
        <div class="col-lg-6" style="padding: 20px;">
    <label for="heartRate">Time</label>
    <input type="time" class="form-control" id="time" name="time">
</div>
</div>
                
    <div class="row mt-3">
        <div class="col-md-12">
            <input type="submit" name="vitals_update" class="btn btn-warning" value="Update">
   
        </div>
    </div>
</form>

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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['vitals_update'])) {
        $patientName = $_POST['fullname'];
        $time= $_POST['time'];
        $bloodPressure = $_POST['blood_pressure'];
        $heartRate = $_POST['heart_rate'];
        $spo2 = $_POST['spo2'];
        $respirations = $_POST['respirations'];
        $temperature = $_POST['temperature'];
        $todayDate = date('Y-m-d'); 
        $sql = "SELECT patient_id FROM patients WHERE CONCAT(first_name, ' ', last_name) = :fullname";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fullname', $patientName);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['patient_id']) && $row['patient_id'] !== '0') {
            $patientID = $row['patient_id'];
            $insertSql = "INSERT INTO vitals (patient_id, patient_name, blood_pressure, Heart_rate, SPO2, Respiration, Temperature, date_modified, Time) 
                          VALUES (:patientID, :patientName, :bloodPressure, :heartRate, :spo2, :respirations, :temperature, :todayDate, :time)";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':patientID', $patientID);
            $insertStmt->bindParam(':patientName', $patientName);
            $insertStmt->bindParam(':bloodPressure', $bloodPressure);
            $insertStmt->bindParam(':heartRate', $heartRate);
            $insertStmt->bindParam(':spo2', $spo2);
            $insertStmt->bindParam(':respirations', $respirations);
            $insertStmt->bindParam(':temperature', $temperature);
            $insertStmt->bindParam(':todayDate', $todayDate);
            $insertStmt->bindParam(':time', $time);
            if ($insertStmt->execute()) {
              header("Refresh:0"); 
            } else {
                echo "Error recording vitals.";
            }
        } else {
            echo "Invalid patient or patient ID not found.";
            
        }
    }
}
?>
</div>


        <div id="myforms">
        <div class="container mt-4">
        <form action="addform.php" method="post" enctype="multipart/form-data" style="display: none;">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="form_name" placeholder="Enter Name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="form_description" rows="3" placeholder="Enter Description"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fileUpload">Upload File</label>
                        <input type="file" class="form-control-file" id="fileUpload" name="form_file">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Save Form</button>
                </div>
            </div>
        </form>
    </div>
    <?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'clinicmanagement';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM forms";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $form_name = $row['form_name'];
        $form_details = $row['form_details'];
        $form_file = $row['form_file']; // Assuming the column name for the file data as BLOB

        // Display the fetched data or use it as needed in HTML
        echo "<div class='container mt-3'>";
        echo "<div class='row'>";
        echo "<div class='col-md-6 mb-3'>";
        echo "<div class='card'>";
        echo "<div class='card-header box-header'>";
        echo $form_name;
        echo "</div>";
        echo "<div class='card-body'>";
        echo "<p>$form_details</p>";

        if ($form_file !== null) {
    $fileType = mime_content_type($form_file);
    $base64Data = base64_encode(file_get_contents($form_file));
    $embeddableFile = "data:$fileType;base64,$base64Data";
    
    // Display the embedded file
    echo "<a href='$embeddableFile' download>Download File</a><br>";
}  else {
    echo "No file uploaded.";
}

        echo "<button class='btn btn-primary'>Send to...</button>";
        echo "</div></div></div></div></div>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?></div>

           
       
            
        
    
          
            
          
           
          
          
              
        </div>
        <div id="new" class="shadow p-4 mb-4 bg-white rounded">
            <form method="POST" action="new.php" enctype="multipart/form-data">
                <div class="container">
                  <h1>Enroll A New Patient</h1>
                  <div class="row">
                    <div class="col-md-2">
                      <label class="form-label" for="MRN" style="margin-top: 20px;">#MRN</label>
                      
                    </div><div class="col-md-6"><input type="text" id="MRN" name="MRN#" class="form-control" style="margin-top: 20px;">
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-2">
                      <label for="lastName " style="margin-top: 20px;">Last Name</label>
                    </div>
                    <div class="col-md-6">
                      <input type="text" id="lastName" name="last_name" class="form-control" style="margin-top: 20px;" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="firstName" style="margin-top: 20px;">First Name</label>
                    </div>
                    <div class="col-md-6">
                      <input type="text" id="firstName" name="first_name" class="form-control" style="margin-top: 20px;" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="MName" style="margin-top: 20px;">M Name</label>
                    </div>
                    <div class="col-md-6">
                      <input type="text" id="MName" name="M_name" class="form-control" style="margin-top: 20px;">
                    </div>
                  </div>
                  <div class="row">
                            <div class="col-md-2">
                              <label for="SEX" style="margin-top: 20px;">Gender</label>
                            </div>
                            <div class="col-md-6">
                              <select id="race" name="sex" class="form-control" style="margin-top: 20px;">
                                <option value="">Select an option</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                                
                              </select>
                            </div></div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="email" style="margin-top: 20px;">Email</label>
                    </div>
                    <div class="col-md-6">
                      <input type="email" id="email" name="email" class="form-control" style="margin-top: 20px;" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="dob" style="margin-top: 20px;">D.O.B</label>
                    </div>
                    <div class="col-md-6">
                      <input type="date" id="dob" name="dob" class="form-control" style="margin-top: 20px;" required>
                    </div>
                  </div>
                  <div class="row">
                   <div class="col-md-2">
                        <label for="dob" style="margin-top: 20px;">phone Number</label>
                      </div>
                      <div class="col-md-6">
                        <input type="phone" id="phone" name="phone" class="form-control" style="margin-top: 20px;" required>
                      </div></div>
                      <div class="row">
                        <div class="col-md-2">
                             <label for="dob" style="margin-top: 20px;">Address</label>
                           </div>
                           <div class="col-md-6">
                             <input type="text" id="phone" name="address" class="form-control" style="margin-top: 20px;" required>
                           </div></div>
                           <div class="row">
    <div class="col-md-2">
        <label for="insurance" style="margin-top: 20px;">Insurance</label>
    </div>
    <div class="col-md-6">
        <select id="insurance" name="insurance" class="form-control" style="margin-top: 20px;">
            <option value="">Select an option</option>
            <option value="Aetna Better Health">Aetna Better Health</option>
            <option value="Aetna Medicare">Aetna Medicare</option>
            <option value="Allsavers UHC (payer ID 81400)">Allsavers UHC (payer ID 81400)</option>
        
            <option value="Ascension Complete Health (MI Complete Health payer ID 68069)">Ascension Complete Health (MI Complete Health payer ID 68069)</option>
            <option value="BCN Advantage (XYK)">BCN Advantage (XYK)</option>
            <option value="Blue Cross Complete Medicaid">Blue Cross Complete Medicaid</option>
            <option value="Care Improvement Plus (through UHC payer ID 87726)">Care Improvement Plus (through UHC payer ID 87726)</option>
            <option value="Careplus Health Plan (Florida Medicare…we do not take)">Careplus Health Plan (Florida Medicare…we do not take)</option>
            <option value="Centene Venture">Centene Venture</option>
            <option value="HAP Senior">HAP Senior</option>
            <option value="Hap Midwest">Hap Midwest</option>
            <option value="Humana Advantage">Humana Advantage</option>
            <option value="Mclaren Advantage">Mclaren Advantage</option>
            <option value="McLaren Medicaid">McLaren Medicaid</option>
            <option value="Medicaid: Meridian Medicaid">Medicaid: Meridian Medicaid</option>
            <option value="Medicare (Traditional)">Medicare (Traditional)</option>
            <option value="Medicare Plus Blue (XYL)">Medicare Plus Blue (XYL)</option>
            <option value="Meridian Complete Care">Meridian Complete Care</option>
            <option value="Meridian Medicaid">Meridian Medicaid</option>
            <option value="Molina Advantage">Molina Advantage</option>
            <option value="Molina Medicaid">Molina Medicaid</option>
            <option value="Priority Medicaid">Priority Medicaid</option>
            <option value="Preferred Care Partners (through UHC)">Preferred Care Partners (through UHC)</option>
            <option value="Reliance (or CCA Health MI)">Reliance (or CCA Health MI)</option>
            <option value="Sierra Medicare (through UHC payer ID 87726)">Sierra Medicare (through UHC payer ID 87726)</option>
            <option value="UHC Dual Plan">UHC Dual Plan</option>
            <option value="UHC Medicare">UHC Medicare</option>
            <option value="Wabash Memorial (Medicare Railroad)">Wabash Memorial (Medicare Railroad)</option>
            <option value="Wellcare (Medicare plan)">Wellcare (Medicare plan)</option>
        </select>
    </div>
</div>
<div class="row">
                        <div class="col-md-2">
                             <label for="dob" style="margin-top: 20px;">Diagnosis</label>
                           </div>
                           <div class="col-md-6">
                             <input type="text" id="phone" name="diagnosis" class="form-control" style="margin-top: 20px;">
                           </div></div>
                           <div class="row">
                            <div class="col-md-2">
                              <label for="T2C" style="margin-top: 20px;">Best Time to Call</label>
                            </div>
                            <div class="col-md-6">
                              <select id="race" name="Best_time" class="form-control" style="margin-top: 20px;">
                                <option value="">Select an option</option>
                                <option value="AnyTime">Any Time</option>
                                <option value="8AM to 12PM">8AM to 12PM</option>
                                <option value="12PM to 4PM">12PM to 4PM</option>
                                <option value="After 4PM">After 4PM</option>
                                <option value="N/A">N/A</option>
                              </select>
                            </div></div><?php 
if (isset($_SESSION['provider_id'])) {
    $provider_id = $_SESSION['provider_id'];
?>
    <input type="hidden" name="provider_id" value="<?php echo $provider_id; ?>">
<?php 
}
?>
                            <div class="row">
                    <div class="col-md-2">
                      <label for="race" style="margin-top: 20px;">Flag</label>
                    </div>
                    <div class="col-md-6">
                      <select id="flag" name="Flag" class="form-control" style="margin-top: 20px;" required>
                        <option value="">Select a Flag</option>
                        <option value="CCM">CCM</option>
                        <option value="TCM">TCM</option>
                        
                      </select>
                    </div>
</div>
                    
                     
                    
                    <div class="row">
                    <div class="col-md-2">
                      <label for="email" style="margin-top: 20px;">Notes</label>
                    </div>
                    <div class="col-md-6">
                      <input type="text" id="note" name="note" class="form-control" style="margin-top: 20px;" required>
                    </div>
                  </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="race" style="margin-top: 20px;">Race</label>
                    </div>
                    <div class="col-md-6">
                      <select id="race" name="race" class="form-control" style="margin-top: 20px;" required>
                        <option value="">Select a race</option>
                        <option value="Asian">Asian</option>
                        <option value="Black">Black</option>
                        <option value="Hispanic/Latino">Hispanic/Latino</option>
                        <option value="White">White</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
</div>
                    
                    <div class="row">
                    <div class="col-md-2">
                      <label for="race" style="margin-top: 20px;">Consent</label>
                    </div>
                    <div class="col-md-6">
                      <input type="file" name="consent" style="margin-top: 20px;">
                    </div></div>
                  <br>
                  <input type="submit" class="btn btn-outline-primary" name="save">
                </div></div>
              </form>
              
        </div>
        <div id="myforms">
        <div class="container mt-4">
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fileUpload">Upload File</label>
                        <input type="file" class="form-control-file" id="fileUpload" name="fileUpload">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Save Form</button>
                </div>
            </div>
        </form>
    </div>
        <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header box-header">
                        Form 1
                    </div>
                    <div class="card-body">
                        <p>The following form is dedicated for X patients.</p>
                        <button class="btn btn-primary">Send to...</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header box-header">
                       Form 2
                    </div>
                    <div class="card-body">
                        <p>The following form is dedicated for X patients.</p>
                        <button class="btn btn-primary">Send to...</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header box-header">
                       Form 3
                    </div>
                    <div class="card-body">
                        <p>The following form is dedicated for X patients.</p>
                        <button class="btn btn-primary">Send to...</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header box-header">
                       Form 4
                    </div>
                    <div class="card-body">
                        <p>The following form is dedicated for X patients.</p>
                        <button class="btn btn-primary">Send to...</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
        </div>
        <div class="container" id="calls">
    <form action="addcall.php" method="POST">
        
    <div class="row mb-2">
            <div class="col-md-2">
                <label for="fullName" class="form-label">Who made the Call ?</label>
            </div>
            <div class="col-md-6">
    <select class="form-control" id="patientSelect" name="who">
        <?php
        $sql = "SELECT * FROM members WHERE provider_id='$provider_id'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($row['fullname'])) {
                $memberfullname = $row['fullname'];
                echo "<option value='$memberfullname'>$memberfullname</option>";
                
            } else {
                echo "<option value=''>No Data Available</option>";
            }
        }echo"<option value='$provider_name'>$provider_name</option>";
        ?>
    </select>
</div>
 



        </div><div class="row mb-2">
            <div class="col-md-2">
                <label for="fullName" class="form-label">Patient Name:</label>
            </div>
            <div class="col-md-6">
    <select class="form-control" id="patientSelect" name="fullname">
        <?php
        $sql = "SELECT * FROM patients WHERE provider_id='$provider_id'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($row['last_name']) && !empty($row['first_name']) && isset($row['M_name']) && !empty($row['patient_id'])) {
                $lastName = $row['last_name'];
                $firstName = $row['first_name'];
                $middleName = !is_null($row['M_name']) ? $row['M_name'] : '';
                $fullname = $firstName . " " . $lastName; // Concatenate first name and last name

                // Assuming you have a unique identifier for each patient like 'patient_id'
                $patientID = $row['patient_id']; // Replace 'patient_id' with the actual field name from your database
                
                // Generate an option for each patient using their name and set the value as the patient's ID
                echo "<option value='$fullname'>$fullname</option>";
            } else {
                echo "<option value=''>No Data Available</option>";
            }
        }
        ?>
    </select>
</div>
 



        </div>

        <div class="row mb-2">
            <div class="col-md-2">
                <label for="callDate" class="form-label">Call Date:</label>
            </div>
            <div class="col-md-6">
                <input type="date" class="form-control" id="callDate" name="date">
            </div>
        </div>
        <div class="row mb-2">
        <div class="col-md-2">
        <label for="startTime">From:</label></div>
        <div class="col-md-1"> <input type="time" id="startTime" name="start" required></div></div>
        <div class="row mb-2">
  <div class="col-md-2">
  <label for="endTime">To:</label></div>
  <div class="col-md-1"><input type="time" id="endTime" name="end" required></div></div>
        <div class="row mb-2">
            <div class="col-md-2">
                <label for="callDuration" class="form-label">Call Duration:</label>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" id="timerInput" placeholder="Enter call duration" name="amount">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-2">
                <label for="updates" class="form-label">Updates:</label>
            </div>
            <div class="col-md-6">
                <textarea class="form-control" id="updates" rows="5" placeholder="Enter updates or notes" name="updates"></textarea>
            </div>
        </div>
     <button type="submit" class="btn btn-primary">Submit</button>
            
        
    </form>
 

  </div>
  <div id="callHistory">
 
  <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for patient...">

  <table class="table table-bordered">
    <thead style="background-color: #50c8f6;">
      <tr>
        <th>Patient Fullname</th>
        
        <th>Call date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Call duration</th>
        <th>Updates</th>
      </tr>
    </thead>
    <tbody>
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
$sql = "SELECT * FROM calls WHERE provider_id='$provider_id' ORDER BY `date` DESC";
$stmt= $pdo -> prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $row){
$fullname = $row['Fullname'];
$calldate = $row['date'];
$callduration = $row['amount'];
$updates = $row['updates'];
$start = $row['start_time'];
$end = $row['end_time'];

?>
 
  
  
 
      <tr>
        <td><?php echo"$fullname"; ?></td>
        <td><?php echo"$calldate"; ?></td>
        <td><?php echo"$start"; ?></td>
        <td><?php echo"$end"; ?></td>
        <td><?php echo"$callduration"; ?></td>
        <td><?php echo"$updates"; ?></td>
        
      </tr><?php
    } ?> 
    </tbody>
  </table>
</div>




    </main>

  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

   
    <script>
      // Get today's date in the format yyyy-mm-dd
const today = new Date().toISOString().split('T')[0];

// Set the value of the input field with ID 'today-date' to today's date
document.getElementById('today-date').value = today;

      function searchTable() {
    // Get input value
    var input = document.getElementById('searchInput').value.toUpperCase();
    var table = document.getElementById('callHistory');
    var rows = table.getElementsByTagName('tr');

    // Loop through all table rows
    for (var i = 0; i < rows.length; i++) {
      var fullNameColumn = rows[i].getElementsByTagName('td')[0]; // Assuming the full name is in the first column

      if (fullNameColumn) {
        var fullNameValue = fullNameColumn.textContent || fullNameColumn.innerText;

        if (fullNameValue.toUpperCase().indexOf(input) > -1) {
          rows[i].style.display = '';
        } else {
          rows[i].style.display = 'none';
        }
      }
    }
  }
        function loadContent(contentId, patientId = null) {
    var contentDivs = document.querySelectorAll('.content > div');
    contentDivs.forEach(function(div) {
        div.style.display = 'none';
    });

    var selectedContent = document.getElementById(contentId);
    if (selectedContent) {
        selectedContent.style.display = 'block';
    }

    // Additional logic to handle patient ID if provided
    if (patientId !== null) {
        // Perform actions with patientId here, such as fetching data, etc.
        console.log("Patient ID:", patientId);
        // You can use the patientId variable here to load specific patient data, etc.
    }
}

    
        document.querySelectorAll('.content > div').forEach(function(el) {
            el.style.display = 'none';
        });
        document.getElementById('content1').style.display = 'block';
    
      
        let startTime = 0;
  let timerInterval;
  let elapsedTime = 0;

  // Function to toggle the display of the popup
  function togglePopup() {
    const popup = document.getElementById('popup');
    if (popup.style.display === 'none') {
      popup.style.display = 'block';
    } else {
      popup.style.display = 'none';
      stop(); // Stop the timer when the popup is closed
    }
  }

  // Start the timer when the "Start" button is clicked
  function start() {
  startTime = new Date() - elapsedTime;
  timerInterval = setInterval(updateTime, 1000); // Update time every 1000ms (1 second)
}

function stop() {
  clearInterval(timerInterval);
  elapsedTime = new Date() - startTime;

  // Get the input field element by ID
  let timerInput = document.getElementById("timerInput");

  // Format the elapsed time
  let formattedTime = formatTime(elapsedTime);

  // Set the formatted time in the input field
  timerInput.value = formattedTime;
}

function formatTime(time) {
  let hours = Math.floor(time / 3600000);
  let minutes = Math.floor((time % 3600000) / 60000);
  let seconds = Math.floor((time % 60000) / 1000);

  return (
    (hours < 10 ? "0" : "") + hours + ":" +
    (minutes < 10 ? "0" : "") + minutes + ":" +
    (seconds < 10 ? "0" : "") + seconds
  );
}

function reset() {
  clearInterval(timerInterval);
  elapsedTime = 0;
  document.getElementById("stopwatch").innerHTML = "00:00:00";
}

function updateTime() {
  let currentTime = new Date() - startTime;
  let hours = Math.floor(currentTime / 3600000);
  let minutes = Math.floor((currentTime % 3600000) / 60000);
  let seconds = Math.floor((currentTime % 60000) / 1000);

  document.getElementById("stopwatch").innerHTML =
    (hours < 10 ? "0" : "") + hours + ":" +
    (minutes < 10 ? "0" : "") + minutes + ":" +
    (seconds < 10 ? "0" : "") + seconds;
}
function addTeamMember() {
        var teamMemberForm = document.getElementById('teamMemberForm');
        teamMemberForm.style.display = 'block';
    }


</script>
    
</body>
</html>
