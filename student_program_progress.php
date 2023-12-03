<?php
// Include the configuration file to establish the database mysql
include 'config.php';
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
  <h2>Enter Student ID</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="studentID">Student ID:</label>
        <input type="text" id="studentID" name="studentID" required>
        <button type="submit">Submit</button>
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if studentID is set and not empty
    
    if (isset($_POST['studentID']) && !empty($_POST['studentID'])) {
      
      $specific_student_id = $_POST['studentID']; // Retrieve the studentID from the form
      echo $specific_student_id;  
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
          echo "<h3>Student's Programs</h3>";
          echo "<table border='1'>";
          echo "<tr><th>Name</th><th>Program Number</th><th>Action</th></tr>";

          // Loop through the result set and display data in table rows
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
              echo "<td>" . $row['Program_Num'] . "</td>"; // Modify column names as needed

              // Button column with a form to fetch and display additional information
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              echo "<input type='hidden' name='programNum' value='" . $row['Program_Num'] . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='submit' name='showDetails' value='Show Details'>";
              echo "</form>";
              echo "</td>";

              echo "</tr>";
              
          }

          // Close table
          echo "</table>";
        // Close the statement
        $stmt->close();
    } else {
        echo "Student ID is empty.";
    }
  }
  if (isset($_POST['showDetails']) && !empty($_POST['programNum']) && !empty($_POST['studentID'])) {
    if ($_POST['programNum']==4){
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
          echo "<h3>Student's Certifications</h3>";
          echo "<table border='1'>";
          echo "<tr><th>Name</th><th>Program Number</th><th>Action</th></tr>";

          // Loop through the result set and display data in table rows
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['CertE_Num'] . "</td>"; // Modify column names as needed
              echo "<td>" . $row['Name'] . "</td>";
              echo "<td>";
              echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
              
              // Create a dropdown for Status
              echo "<select name='status' onchange='submitForm(" . $row['Program_Num'] . ")'>";
              $statusOptions = ['Starting', 'Finished']; // Replace with your status options
              foreach ($statusOptions as $option) {
                  $selected = ($option === $row['Status']) ? 'selected' : '';
                  echo "<option value='$option' $selected>$option</option>";
              }
              echo "</select>";
              echo "<input type='submit' name='updateCert' value='Update Certification'>";
              echo "<input type='hidden' name='programNum' value='" . $row['Program_Num'] . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='hidden' name = 'showDetails' value='showDetails'>";
              echo "<input type='hidden' name='ce_id' value='" . $row['CertE_Num'] . "'>";
              
              echo "</form>";
              echo "</td>";
              // Button column with a form to fetch and display additional information
              

             
              echo "</tr>";
              
          }

          // Close table
          echo "</table>";
        // Close the statement
        $stmt->close();
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
          echo "<h3>Student's Programs</h3>";
          echo "<table border='1'>";
          echo "<tr><th>Name</th><th>Program Number</th><th>Action</th></tr>";

          // Loop through the result set and display data in table rows
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
              echo "<td>" . $row['Status'] . "</td>"; // Modify column names as needed
              echo "<td>" . $row['Year'] . "</td>";
              echo "<td>";
              echo "<form method='GET' action='test.php'>";
              echo "<input type='hidden' name='programNum' value='" . $row['Class_ID'] . "'>";
              echo "<input type='hidden' name='studentID' value='" . $specific_student_id . "'>";
              echo "<input type='submit' name='updateClass' value='Update Certification'>";
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
  
  // Close the statement
  $stmt->close();

// Bind parameters and execute the statement

}

  }

  ?>

</body>
</html>

<?php
// Close the database mysql when done
mysqli_close($mysql);
?>
