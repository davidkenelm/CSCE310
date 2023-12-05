<?php
// Include the configuration file to establish the database mysql
include 'config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Progress Tracking </title>
    <style>
        /* Styling for the pop-up */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }
        .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
        }
        /* Additional style for labels */
        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
    <script>
        function togglePopup() {
            var popup = document.getElementById('popup');
            popup.style.display = (popup.style.display === 'none') ? 'block' : 'none';
        }

        function submitForm() {
            var popupContent = document.getElementById('popupContent');
            popupContent.innerHTML = "SUBMITTED";
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
<?php
    include 'navbar.php';
    ?>
<div class="popup" id="popup">
        <span class="close-btn" onclick="togglePopup()">X</span>
        <div id="popupContent">
        <?php
           echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>"; 
           echo "<label for='field1'>Field 1:</label>";
                echo "<input type='text' id='field1' name='field1'>";

                echo "<label for='field2'>Field 2:</label>";
                echo "<input type='text' id='field2' name='field2'>";

                echo "<button type='submit'>Submit</button>";
            echo "</form>";
          ?>
        </div>
    </div>


  <?php
  

    // Check if studentID is set and not empty
    
    
      
      $specific_student_id = $_SESSION['UIN'];
       // Retrieve the studentID from the form
       $sql = "SELECT * FROM users WHERE UIN = ?";
        // Prepare a statement
        $stmt = $mysql->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("i", $specific_student_id); // Assuming student_ID is an integer

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        $full_name = $row['First_Name'] . " " . $row['Last_Name'];

        $stmt->close();

        
 
        // Prepare the query to fetch student information based on student_ID
        $sql = "SELECT * FROM track JOIN programs ON track.Program_Num = programs.Program_Num WHERE track.Student_Num = ?";

        // Prepare a statement
        $stmt = $mysql->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("i", $specific_student_id); // Assuming student_ID is an integer

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();


        // Display the fetched student information as a list

        if ($result->num_rows > 0) {
          // Start table formatting
          
          echo "<h3>" . $full_name . "'s Programs</h3>";
          echo "<table border='1' style='width: 100%;'>";
          echo "<tr><th>Program Name</th><th>Description</th><th>Details</th></tr>";
          // Loop through the result set and display data in table rows
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
              echo "<td>" . $row['Description'] . "</td>"; // Modify column names as needed

              // Button column with a form to fetch and display additional information
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              echo "<input type='hidden' name='programNum' value='" . $row['Program_Num'] . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name='programName' value='" . $row['Name'] . "'>";
              echo "<input type='submit' name='showDetails' value='Show Details'>";
              echo "</form>";
              echo "</td>";

              echo "</tr>";
              
          }

          // Close table
          echo "</table>";
          
        // Close the statement
        $stmt->close();
        echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              echo "<input type='hidden' name='programNum' value='cancel'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='submit' name='showDetails' value='Close Details'>";
              echo "</form>";
    } else {
        echo "Student ID is not listed into any programs.";
    }
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['showDetails']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {
    if ($_POST['programNum']=='cancel'){
      echo " ";
    }
    else{
      $programNum = $_POST['programNum'];
      $uin = $_POST['studentID'];
      $sql = "SELECT * FROM cert_enrollment INNER JOIN certifications ON cert_enrollment.Cert_ID = certifications.Cert_ID WHERE cert_enrollment.UIN = ? AND cert_enrollment.Program_Num = ?";

        // Prepare a statement
        $stmt = $mysql->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("ii", $specific_student_id, $programNum); // Assuming student_ID is an integer

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Display the fetched student information as a list
        if ($result->num_rows > 0) {
          // Start table formatting
          echo "<h3>My Certifications for ". $_POST['programName'] ."</h3>";
          echo "<table border='1' style='width: 100%;'>";
          echo "<tr><th>Certification</th><th>Status</th><th>Training Status</th><th>Semester</th><th>Year</th><th>Delete</th></tr>";
          
          // Loop through the result set and display data in table rows
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              
              // Create a dropdown for Status
              echo "<select name='status' onchange='submitForm(" . $row['Program_Num'] . ")'>";
              $statusOptions = ['Not Started','In Progress', 'Finished']; // Replace with your status options
              foreach ($statusOptions as $option) {
                  $selected = ($option === $row['Status']) ? 'selected' : '';
                  echo "<option value='$option' $selected>$option</option>";
              }
              echo "</select>";
              echo "<input type='submit' name='updateCert' value='Update Certification'>";
              echo "<input type='hidden' name='programNum' value='" . $row['Program_Num'] . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
              
              echo "<input type='hidden' name='ce_id' value='" . $row['CertE_Num'] . "'>";
              
              echo "</form>";
              echo "</td>";

              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              
              // Create a dropdown for Status
              echo "<select name='status' onchange='submitForm(" . $row['Program_Num'] . ")'>";
              $statusOptions = ['Not Started','In Progress', 'Finished']; // Replace with your status options
              foreach ($statusOptions as $option) {
                  $selected = ($option === $row['Training_Status']) ? 'selected' : '';
                  echo "<option value='$option' $selected>$option</option>";
              }
              echo "</select>";
              echo "<input type='submit' name='updateTrainingCert' value='Update Training Status'>";
              echo "<input type='hidden' name='programNum' value='" . $row['Program_Num'] . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='ce_id' value='" . $row['CertE_Num'] . "'>";
              echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
              
              echo "</form>";
              echo "</td>";
              // Button column with a form to fetch and display additional information
              
              echo "<td>" . $row['Semester'] . "</td>";
              echo "<td>" . $row['Year'] . "</td>";

              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              echo "<input type='submit' name='deleteCert' value='Delete'>";
              echo "<input type='hidden' name='programNum' value='" . $row['Program_Num'] . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='ce_id' value='" . $row['CertE_Num'] . "'>";
              echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
              echo "</form>";
              echo "</td>";

             
              echo "</tr>";
              
          }

          // Close table
          echo "</table>";
        // Close the statement
        $stmt->close();
        }else{
          echo "<h3>My Certifications for ". $_POST['programName'] ."</h3>";
          echo "<h4>No Certification records found for this Program</h4>";
        }
        $showInsertCert;
        if (isset($_POST['showInsertCert'])){
          $showInsertCert = $_POST['showInsertCert'];
          if ($showInsertCert == 'show'){
            $showInsertCert = "no";
          }else {
            $showInsertCert = "show";
          }
        }else{
          $showInsertCert = "show";
        }
        
        echo "<form method = 'POST' action = '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
        <input type = 'hidden' name = 'showInsertCert' value = " . $showInsertCert . ">
        <button type = 'submit'>Insert Certification for this Program</button>
        <input type='hidden' name='programNum' value='" . $programNum . "'>
        <input type='hidden' name='studentID' value='" . $specific_student_id . "'>
        <input type='hidden' name = 'showDetails' value='showDetails'>";
        echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>
        </form>";


        if(isset($_POST['showInsertCert']) && $_POST['showInsertCert']=='show'){
          echo "<br></br>";
          $sql = "SELECT certifications.Name, certifications.Cert_ID
          FROM certifications
          LEFT JOIN cert_enrollment ON certifications.Cert_ID = cert_enrollment.Cert_ID
              AND cert_enrollment.UIN = ?
          WHERE cert_enrollment.UIN IS NULL";
          $stmt = $mysql->prepare($sql);
  
          // Bind the parameter
          $stmt->bind_param("i",$specific_student_id); // Assuming student_ID is an integer
  
          // Execute the query
          $stmt->execute();
  
          // Get the result
          $result = $stmt->get_result();
          $certifications = [];

          // Fetch associative array rows from the result set
          while ($row = $result->fetch_assoc()) {
              // Append each row to the $certifications array
              $certifications[] = [
                'Name' => $row['Name'],
                'Cert_ID' => $row['Cert_ID']
            ]; // Assuming 'Name' is the column name for the certification name
          }

          // Now $certifications array contains all the certification names
          $stmt->close();
          echo "<form method='POST' action= '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for='certification'>Certification:</label>
    <select id='certification' name='certification'>";
        
        foreach ($certifications as $certification) {
            echo "<option value='" . $certification['Cert_ID'] . "'>" . $certification['Name'] . "</option>";
        }
        
        
    echo "</select>

    <label for='semester'>Semester:</label>
    <select id='semester' name='semester'>
        <option value='Fall'>Fall</option>
        <option value='Spring'>Spring</option>
        <option value='Winter'>Winter</option>
        <option value='Summer'>Summer</option>
    </select>

    <label for='year'>Year:</label>
    <input type='text' id='year' name='year'>

    <input type='submit' name='submitNewCert' value='Submit'>
    <input type='hidden' name='programNum' value='" . $programNum . "'>
    <input type='hidden' name='studentID' value='" . $specific_student_id . "'>
    <input type='hidden' name = 'showDetails' value='showDetails'>";
    echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>
</form>";
        }

        $sql = "SELECT * FROM class_Enrollment INNER JOIN classes ON class_Enrollment.Class_ID = classes.Class_ID where UIN = ?";
        $stmt = $mysql->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("i", $specific_student_id); // Assuming student_ID is an integer

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          // Start table formatting
          echo "<h3>My Enrolled Classes</h3>";
          echo "<table border='1' style='width: 100%;'>";
          echo "<tr><th>Class</th><th>Status</th><th>Semester</th><th>Year</th><th>Delete</th></tr>";

          // Loop through the result set and display data in table rows
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              
              // Create a dropdown for Status
              echo "<select name='status' onchange='submitForm(" . $programNum . ")'>";
              $statusOptions = ['Not Started','In Progress', 'Finished']; // Replace with your status options
              foreach ($statusOptions as $option) {
                  $selected = ($option === $row['Status']) ? 'selected' : '';
                  echo "<option value='$option' $selected>$option</option>";
              }
              echo "</select>";
              echo "<input type='submit' name='updateClass' value='Update Certification'>";
              echo "<input type='hidden' name='programNum' value='" . $programNum . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='class_id' value='" . $row['CE_NUM'] . "'>";
              echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
              
              echo "</form>";
              echo "</td>"; // Modify column names as needed
              echo "<td>" . $row['Semester'] . "</td>";
              echo "<td>" . $row['Year'] . "</td>";
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              echo "<input type='submit' name='deleteClass' value='Delete'>";
              echo "<input type='hidden' name='programNum' value='" . $programNum . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='class_id' value='" . $row['CE_NUM'] . "'>";
              echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
              echo "</form>";
              echo "</td>";
              
              
              // Button column with a form to fetch and display additional information
              

              echo "</tr>";
              
          }

          // Close table
          echo "</table>";
        // Close the statement
        $stmt->close();





    
    
    // Fetch and display additional details for the specific program
    // Modify this part to fetch and display details from your database

    
    }else{
      echo "<h3>My Enrolled Classes</h3>";
      echo "<h4>No Classes enrolled</h4>";
    }
    $showInsertClass;
        if (isset($_POST['showInsertClass'])){
          $showInsertClass = $_POST['showInsertClass'];
          if ($showInsertClass == 'show'){
            $showInsertClass = "no";
          }else {
            $showInsertClass = "show";
          }
        }else{
          $showInsertClass = "show";
        }
        
        echo "<form method = 'POST' action = '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
        <input type = 'hidden' name = 'showInsertClass' value = " . $showInsertClass . ">
        <button type = 'submit'>Insert Class Enrolled</button>
        <input type='hidden' name='programNum' value='" . $programNum . "'>
        <input type='hidden' name='studentID' value='" . $specific_student_id . "'>
        <input type='hidden' name = 'showDetails' value='showDetails'>";
        echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>
        </form>";

        if(isset($_POST['showInsertClass']) && $_POST['showInsertClass']=='show'){
          echo "<br></br>";
          $sql = "SELECT classes.Name, classes.Class_ID FROM classes LEFT JOIN class_enrollment ON classes.Class_ID = class_enrollment.Class_ID   AND class_enrollment.UIN = ? WHERE class_enrollment.CE_NUM IS NULL";
          $stmt = $mysql->prepare($sql);
  
          // Bind the parameter
          $stmt->bind_param("i",$specific_student_id); // Assuming student_ID is an integer
  
          // Execute the query
          $stmt->execute();
  
          // Get the result
          $result = $stmt->get_result();
          $classes = [];

          // Fetch associative array rows from the result set
          while ($row = $result->fetch_assoc()) {
              // Append each row to the $certifications array
              $classes[] = [
                'Name' => $row['Name'],
                'Class_ID' => $row['Class_ID']
            ]; // Assuming 'Name' is the column name for the certification name
          }

          // Now $certifications array contains all the certification names
          $stmt->close();
          echo "<form method='POST' action= '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for='classes'>Class:</label>
    <select id='class' name='class'>";
        
        foreach ($classes as $class) {
            echo "<option value='" . $class['Class_ID'] . "'>" . $class['Name'] . "</option>";
        }
        
        
    echo "</select>

    <label for='semester'>Semester:</label>
    <select id='semester' name='semester'>
        <option value='Fall'>Fall</option>
        <option value='Spring'>Spring</option>
        <option value='Winter'>Winter</option>
        <option value='Summer'>Summer</option>
    </select>

    <label for='year'>Year:</label>
    <input type='text' id='year' name='year'>

    <input type='submit' name='submitNewClass' value='Submit'>
    <input type='hidden' name='programNum' value='" . $programNum . "'>
    <input type='hidden' name='studentID' value='" . $specific_student_id . "'>
    <input type='hidden' name = 'showDetails' value='showDetails'>";
    echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>
</form>";
        }

        $sql = "SELECT * FROM intern_app INNER JOIN internships ON intern_app.Intern_ID = internships.Intern_ID where UIN = ?";
        $stmt = $mysql->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("i", $specific_student_id); // Assuming student_ID is an integer

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          // Start table formatting
          echo "<h3>My Internships</h3>";
          echo "<table border='1' style='width: 100%;'>";
          echo "<tr><th>Internship</th><th>Status</th><th>Year</th><th>Delete</th></tr>";

          // Loop through the result set and display data in table rows
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              
              // Create a dropdown for Status
              echo "<select name='status' onchange='submitForm(" . $programNum . ")'>";
              $statusOptions = ['Not Started','In Progress', 'Finished']; // Replace with your status options
              foreach ($statusOptions as $option) {
                  $selected = ($option === $row['Status']) ? 'selected' : '';
                  echo "<option value='$option' $selected>$option</option>";
              }
              echo "</select>";
              echo "<input type='submit' name='updateIntern' value='Update Internship'>";
              echo "<input type='hidden' name='programNum' value='" . $programNum . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='intern_id' value='" . $row['IA_Num'] . "'>";
              echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
              
              echo "</form>";
              echo "</td>"; // Modify column names as needed
              echo "<td>" . $row['Year'] . "</td>";
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              echo "<input type='submit' name='deleteIntern' value='Delete'>";
              echo "<input type='hidden' name='programNum' value='" . $programNum . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='intern_id' value='" . $row['IA_Num'] . "'>";
              echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
              echo "</form>";
              echo "</td>";
              
              
              // Button column with a form to fetch and display additional information
              

              echo "</tr>";
              
          }

          // Close table
          echo "</table>";
        // Close the statement
        $stmt->close();





    
    
    // Fetch and display additional details for the specific program
    // Modify this part to fetch and display details from your database

    
    }else{
      echo "<h3>My Internships</h3>";
      echo "<h4>No Internships Found</h4>";
    }
    $showInsertIntern;
        if (isset($_POST['showInsertIntern'])){
          $showInsertIntern = $_POST['showInsertIntern'];
          if ($showInsertIntern == 'show'){
            $showInsertIntern = "no";
          }else {
            $showInsertIntern = "show";
          }
        }else{
          $showInsertIntern = "show";
        }
        
        echo "<form method = 'POST' action = '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
        <input type = 'hidden' name = 'showInsertIntern' value = " . $showInsertIntern . ">
        <button type = 'submit'>Insert Internship</button>
        <input type='hidden' name='programNum' value='" . $programNum . "'>
        <input type='hidden' name='studentID' value='" . $specific_student_id . "'>
        <input type='hidden' name = 'showDetails' value='showDetails'>";
        echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>
        </form>";

        if(isset($_POST['showInsertIntern']) && $_POST['showInsertIntern']=='show'){
          echo "<br></br>";
          $sql = "SELECT internships.Name, internships.Intern_ID FROM internships LEFT JOIN intern_app ON internships.Intern_ID = intern_app.Intern_ID   AND intern_app.UIN = ? WHERE intern_app.IA_Num IS NULL";
          $stmt = $mysql->prepare($sql);
  
          // Bind the parameter
          $stmt->bind_param("i",$specific_student_id); // Assuming student_ID is an integer
  
          // Execute the query
          $stmt->execute();
  
          // Get the result
          $result = $stmt->get_result();
          $internships = [];

          // Fetch associative array rows from the result set
          while ($row = $result->fetch_assoc()) {
              // Append each row to the $certifications array
              $internships[] = [
                'Name' => $row['Name'],
                'Intern_ID' => $row['Intern_ID']
            ]; // Assuming 'Name' is the column name for the certification name
          }

          // Now $certifications array contains all the certification names
          $stmt->close();
          echo "<form method='POST' action= '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for='internships'>Internship:</label>
    <select id='intern' name='intern'>";
        
        foreach ($internships as $internship) {
            echo "<option value='" . $internship['Intern_ID'] . "'>" . $internship['Name'] . "</option>";
        }
        
        
    echo "</select>



    <label for='year'>Year:</label>
    <input type='text' id='year' name='year'>

    <input type='submit' name='submitNewIntern' value='Submit'>
    <input type='hidden' name='programNum' value='" . $programNum . "'>
    <input type='hidden' name='studentID' value='" . $specific_student_id . "'>
    <input type='hidden' name = 'showDetails' value='showDetails'>";
    echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>
</form>";
        }


}
  }
  if (isset($_POST['updateCert']) && !empty($_POST['status']) && !empty($_POST['studentID'])) {
    // Process the "Update Certification" form here
    // ...
    // Display the form for "Update Certification" outside the table
    $sql = "UPDATE cert_enrollment SET Status = ? Where CertE_Num = ?";

    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("si",$_POST['status'],$_POST['ce_id']);
    $stmt->execute();

    if($stmt->affected_rows > 0) {
      // Update successful
      echo "Status updated successfully!";
  } else {
      // No rows affected (CE_ID not found or status already set to the same value)
      echo "No changes made.";
  }
  echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              

  echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
  echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
  echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
  echo "<input type='hidden' name='ce_id' value='" . $_POST['ce_id'] . "'>";
  echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
  
  echo "</form>";

  echo "
  <script>
  // Simulating automatic form submission after 3 seconds
  setTimeout(function() {
    document.getElementById('autoForm').submit();
  }, 0); // 1000 milliseconds (1 seconds)
</script>
  ";


  
  // Close the statement
  $stmt->close();

// Bind parameters and execute the statement

}
if (isset($_POST['updateClass']) && !empty($_POST['status']) && !empty($_POST['studentID'])) {
  // Process the "Update Certification" form here
  // ...
  // Display the form for "Update Certification" outside the table
  $sql = "UPDATE class_enrollment SET Status = ? Where CE_NUM = ?";

  $stmt = $mysql->prepare($sql);

  $stmt->bind_param("si",$_POST['status'],$_POST['class_id']);
  $stmt->execute();


echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            

echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";

echo "</form>";

echo "
<script>
// Simulating automatic form submission after 3 seconds
setTimeout(function() {
  document.getElementById('autoForm').submit();
}, 0); // 1000 milliseconds (1 seconds)
</script>
";



// Close the statement
$stmt->close();

// Bind parameters and execute the statement

}
if (isset($_POST['updateIntern']) && !empty($_POST['status']) && !empty($_POST['studentID'])) {
  // Process the "Update Certification" form here
  // ...
  // Display the form for "Update Certification" outside the table
  $sql = "UPDATE intern_app SET Status = ? Where IA_Num = ?";

  $stmt = $mysql->prepare($sql);

  $stmt->bind_param("si",$_POST['status'],$_POST['intern_id']);
  $stmt->execute();


echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            

echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";

echo "</form>";

echo "
<script>
// Simulating automatic form submission after 3 seconds
setTimeout(function() {
  document.getElementById('autoForm').submit();
}, 0); // 1000 milliseconds (1 seconds)
</script>
";



// Close the statement
$stmt->close();

// Bind parameters and execute the statement

}
if (isset($_POST['updateTrainingCert']) && !empty($_POST['status']) && !empty($_POST['studentID'])) {
  // Process the "Update Certification" form here
  // ...
  // Display the form for "Update Certification" outside the table
  $sql = "UPDATE cert_enrollment SET Training_Status = ? Where CertE_Num = ?";

  $stmt = $mysql->prepare($sql);

  $stmt->bind_param("si",$_POST['status'],$_POST['ce_id']);
  $stmt->execute();

  if($stmt->affected_rows > 0) {
    // Update successful
    echo "Status updated successfully!";
} else {
    // No rows affected (CE_ID not found or status already set to the same value)
    echo "No changes made.";
}
echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            

echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
echo "<input type='hidden' name='ce_id' value='" . $_POST['ce_id'] . "'>";
echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";

echo "</form>";

echo "
<script>
// Simulating automatic form submission after 3 seconds
setTimeout(function() {
  document.getElementById('autoForm').submit();
}, 0); // 1000 milliseconds (1 seconds)
</script>
";



// Close the statement
$stmt->close();

// Bind parameters and execute the statement

}

if (isset($_POST['submitNewClass']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {
  $status = 'Not Started';

// Prepare the SQL statement
$sql = "INSERT INTO class_enrollment (UIN, Class_ID, Status, Semester, Year) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
$stmt->bind_param("iisss", $_POST['studentID'], $_POST['class'], $status, $_POST['semester'], $_POST['year']);
$stmt->execute();

// Close the statement
$stmt->close();
echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            

echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";

echo "</form>";

echo "
<script>
// Simulating automatic form submission after 3 seconds
setTimeout(function() {
  document.getElementById('autoForm').submit();
}, 0); // 1000 milliseconds (1 seconds)
</script>
";

}
if (isset($_POST['submitNewIntern']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {
  $status = 'Not Started';

// Prepare the SQL statement
$sql = "INSERT INTO intern_app (UIN, Intern_ID, Status,  Year) 
        VALUES (?, ?, ?, ?)";
$stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
$stmt->bind_param("iiss", $_POST['studentID'], $_POST['intern'], $status, $_POST['year']);
$stmt->execute();

// Close the statement
$stmt->close();
echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            

echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";

echo "</form>";

echo "
<script>
// Simulating automatic form submission after 3 seconds
setTimeout(function() {
  document.getElementById('autoForm').submit();
}, 0); // 1000 milliseconds (1 seconds)
</script>
";

}
if (isset($_POST['submitNewCert']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {
  $status = 'Not Started';
$trainingStatus = 'Not Started';

// Prepare the SQL statement
$sql = "INSERT INTO cert_enrollment (UIN, Cert_ID, Program_Num, Status, Training_Status, Semester, Year) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
$stmt->bind_param("iiissss", $_POST['studentID'], $_POST['certification'], $_POST['programNum'], $status, $trainingStatus, $_POST['semester'], $_POST['year']);
$stmt->execute();

// Close the statement
$stmt->close();
echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            

echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
echo "<input type='hidden' name='ce_id' value='" . $_POST['ce_id'] . "'>";
echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";

echo "</form>";

echo "
<script>
// Simulating automatic form submission after 3 seconds
setTimeout(function() {
  document.getElementById('autoForm').submit();
}, 0); // 1000 milliseconds (1 seconds)
</script>
";

}
if (isset($_POST['deleteCert']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {


// Prepare the SQL statement
$sql = "DELETE FROM cert_enrollment WHERE CertE_Num = ?";
$stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
$stmt->bind_param("i",$_POST['ce_id']);
$stmt->execute();

// Close the statement
$stmt->close();
echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            

echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
echo "<input type='hidden' name='ce_id' value='" . $_POST['ce_id'] . "'>";
echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";

echo "</form>";

echo "
<script>
// Simulating automatic form submission after 3 seconds
setTimeout(function() {
  document.getElementById('autoForm').submit();
}, 0); // 1000 milliseconds (1 seconds)
</script>
";

}
if (isset($_POST['deleteClass']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {


  // Prepare the SQL statement
  $sql = "DELETE FROM class_enrollment WHERE CE_NUM = ?";
  $stmt = $mysql->prepare($sql);
  
  // Bind parameters and execute the query
  $stmt->bind_param("i",$_POST['class_id']);
  $stmt->execute();
  
  // Close the statement
  $stmt->close();
  echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              
  
  echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
  echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
  echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
  echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
  
  echo "</form>";
  
  echo "
  <script>
  // Simulating automatic form submission after 3 seconds
  setTimeout(function() {
    document.getElementById('autoForm').submit();
  }, 0); // 1000 milliseconds (1 seconds)
  </script>
  ";
  
  }
  if (isset($_POST['deleteIntern']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {


    // Prepare the SQL statement
    $sql = "DELETE FROM intern_app WHERE IA_Num = ?";
    $stmt = $mysql->prepare($sql);
    
    // Bind parameters and execute the query
    $stmt->bind_param("i",$_POST['intern_id']);
    $stmt->execute();
    
    // Close the statement
    $stmt->close();
    echo "<form id='autoForm' method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                
    
    echo "<input type='hidden' name='programNum' value='" . $_POST['programNum'] . "'>";
    echo "<input type='hidden' name='studentID' value='" . $_POST['studentID'] . "'>";
    echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
    echo "<input type='hidden' name='programName' value='" . $_POST['programName'] . "'>";
    
    echo "</form>";
    
    echo "
    <script>
    // Simulating automatic form submission after 3 seconds
    setTimeout(function() {
      document.getElementById('autoForm').submit();
    }, 0); // 1000 milliseconds (1 seconds)
    </script>
    ";
    
    }

  }

  ?>

</body>
</html>

<?php
// Close the database mysql when done
mysqli_close($mysql);
?>
