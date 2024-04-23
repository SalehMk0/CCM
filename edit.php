<style>
#medication {
  display: flex;
  background-image: url('images/medication.jpg');
  background-size: cover; /* Ensure the background image covers the entire element */
  background-position: center; /* Center the background image */
  padding: 20px; /* Add padding if needed */
}

#medication form {
  width: 60%; /* Adjusted width */
  padding: 20px;
  background-color: rgba(0, 147, 59, 0.8); /* Adjusted background color with some transparency */
  color: white; /* Text color */
  font-weight: bold;
}




</style>

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
if(isset($_POST['edit'])){
    $patient_id= $_POST['patient_id'];
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id = :patient_id");
    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
    $stmt->execute();

    $pa = $stmt->fetch(PDO::FETCH_ASSOC);

    // Storing each column record in a separate variable
    $MRN = $pa['MRN'];
    $last_name = $pa['last_name'];
    $first_name = $pa['first_name'];
    $M_name = $pa['M_name'];
    $email = $pa['email'];
    $DOB = $pa['DOB'];
    $Sex = $pa['Sex'];
    $Race = $pa['Race'];
    $Status = $pa['Status'];
    $address = $pa['address'];
    $Street = $pa['Street'];
    $City = $pa['City'];
    $Zipcode = $pa['Zipcode'];
    $description = $pa['description'];
    $State = $pa['State'];
    $Apt = $pa['Apt#'];
    $phone_type = $pa['phone_type'];
    $phone = $pa['phone'];
    $best_time = $pa['best_time'];
    $msg = $pa['msg'];
    $note = $pa['note'];
    $Flag = $pa['Flag'];
    $insurance = $pa['Insurance'];
    $Client = $pa['Client'];
    $Allergies = $pa['Allergies'];
    $Diagnosis = $pa['Diagnosis'];

    $fullname = $first_name . " " . $last_name;
echo $fullname;
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    

<div id="edit">
              <?php echo "$patient_id"; ?>
            <form method="POST" action="update.php">
                <div class="container">
                  <h1>Edit Patient</h1>
                  <div class="row">
                    <div class="col-md-2">
                      <label class="form-label" for="MRN" style="margin-top: 20px;">#MRN</label>
                      
                    </div><div class="col-md-4"><input type="text" id="MRN" name="MRN#" class="form-control" style="margin-top: 20px;" value="<?php echo"$MRN"; ?>">
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-2">
                      <label for="lastName " style="margin-top: 20px;">Last Name</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="lastName" name="last_name" value="<?php echo"$last_name"; ?>" class="form-control" style="margin-top: 20px;" >
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="firstName" style="margin-top: 20px;">First Name</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="firstName" name="first_name" value="<?php echo"$first_name"; ?>" class="form-control" style="margin-top: 20px;" >
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="MName" style="margin-top: 20px;">M Name</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="MName" name="M_name" value="<?php echo"$M_name"; ?>" class="form-control" style="margin-top: 20px;" >
                    </div>
                  </div>
                  <div class="row">
                            <div class="col-md-2">
                              <label for="SEX" style="margin-top: 20px;">Gender</label>
                            </div>
                            <div class="col-md-4">
                              <input type="sex" id="sex" name="sex" value="<?php echo"$Sex"; ?>" class="form-control" style="margin-top: 20px;">
                            
                            </div></div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="email" style="margin-top: 20px;">Email</label>
                    </div>
                    <div class="col-md-4">
                      <input type="email" id="email" name="email" value="<?php echo"$email"; ?>" class="form-control" style="margin-top: 20px;" >
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="dob" style="margin-top: 20px;">D.O.B</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="dob" name="dob" value="<?php echo"$DOB"; ?>  "class="form-control" style="margin-top: 20px;" disabled>
                    </div>
                  </div>
                  <div class="row">
                   <div class="col-md-2">
                        <label for="dob" style="margin-top: 20px;">phone Number</label>
                      </div>
                      <div class="col-md-4">
                        <input type="phone" id="phone" name="phone" value="<?php echo"$phone"; ?>" class="form-control" style="margin-top: 20px;" >
                      </div></div>
                      <div class="row">
                        <div class="col-md-2">
                             <label for="dob" style="margin-top: 20px;">Address</label>
                           </div>
                           <div class="col-md-4">
                             <input type="text" id="phone" name="address" value="<?php echo"$address"; ?>" class="form-control" style="margin-top: 20px;" >
                           </div></div>
                           <div class="row">
    <div class="col-md-2">
        <label for="insurance" style="margin-top: 20px;">Insurance</label>
    </div>
    <div class="col-md-4">
        <input type="text" id="insurance" value="<?php echo"$insurance"; ?>" name="insurance" class="form-control" style="margin-top: 20px;">
        
    </div>
</div>
<div class="row">
                        <div class="col-md-2">
                             <label for="dob" style="margin-top: 20px;">Diagnosis</label>
                           </div>
                           <div class="col-md-4">
                             <input type="text" id="phone" value="<?php echo"$Diagnosis"; ?>" name="diagnosis" class="form-control" style="margin-top: 20px;">
                           </div></div><br>
                           <div class="row mb-2">
            <div class="col-md-2">
                <label for="updates" class="form-label">Notes:</label>
            </div>
            <div class="col-md-6">
                <textarea class="form-control" id="updates" rows="5" placeholder="Enter updates or notes" name="notes"></textarea>
            </div>
        </div>
                           <div class="row">
                            <div class="col-md-2">
                              <label for="T2C" style="margin-top: 20px;">Best Time to Call</label>
                            </div>
                            <div class="col-md-4">
                              <input type="text" id="ttc" name="Best_time" value="<?php echo"$best_time"; ?>" class="form-control" style="margin-top: 20px;">
                            
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
                    <div class="col-md-4">
                      <input type="text" id="flag" name="flag" value="<?php echo"$Flag"; ?>" class="form-control" style="margin-top: 20px;" >
                       
                    </div>
                    
                     
                    
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <label for="race" style="margin-top: 20px;">Race</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" id="race" value="<?php echo"$Race"; ?>" name="race" class="form-control" style="margin-top: 20px;" >
                     
                    </div>
                    
                     
                    
                  </div><br>
                  <input type="submit" class="btn btn-outline-primary" name="save">
                </div>
              </form>
              
        </div>
        <div id="medication">
        <form method="POST" action="medication.php"><h2 style="font-size: 45px; text-align:center; padding:30px; color:white;">Medication</h2>
    <div class="form-group row mb-5">
        <label for="pharmacy" class="col-sm-2 col-form-label">Pharmacy:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="pharmacy" name="pharmacy" required>
        </div>
    </div>

    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Location:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="location" name="location" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Brand:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="location" name="brand" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Generic:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="location" name="generic" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Start:</label>
        <div class="col-md-3">
            <input type="date" class="form-control" id="location" name="start" required>
        </div>
    
    
        <label for="location" class="col-sm-1 col-form-label">Stop:</label>
        <div class="col-md-3">
            <input type="date" class="form-control" id="location" name="stop" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Dosage:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="location" name="dosage" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Frequency:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="location" name="frequency" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Unit:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="location" name="unit" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Notes:</label>
        <div class="col-md-6">
            <input type="text" class="form-control" id="location" name="notes" required>
        </div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Hold </label>
        <div class="col-md-1">
        <input class="form-control" type="checkbox" id="hold" name="hold"></div>
    </div>
    <div class="form-group row mb-5">
        <label for="location" class="col-sm-2 col-form-label">Until:</label>
        <div class="col-md-6">
            <input type="date" class="form-control" id="location" name="until">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <input type="hidden" class="form-control" id="location" name="patient_name" value="<?php echo"$fullname"; ?>" required>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <input type="hidden" class="form-control" id="location" name="patient_id" value="<?php echo"$patient_id"; ?>"   required>
        </div>
    </div>
    <!-- Repeat the same structure for other fields -->
    
    <button type="submit" class="btn btn-primary offset-sm-2">Submit</button>
</form>

   
  </div></body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</html>
    