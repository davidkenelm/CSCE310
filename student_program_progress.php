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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
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
          echo "<h3>Student Progress Information</h3>";
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
    echo $_POST['programNum'];
    echo "<tr>";
    echo "<td colspan='3'>";

    // Additional table for program details
    echo "<table border='1'>";
    echo "<tr><th>Detail 1</th><th>Detail 2</th></tr>";
    echo "<tr><td>Detail Value 1</td><td>Detail Value 2</td></tr>";
    // Fetch and display additional details for the specific program
    // Modify this part to fetch and display details from your database

    echo "</table>";

    echo "</td>";
    echo "</tr>";
}
}

  ?>

</body>
</html>

<?php
// Close the database mysql when done
mysqli_close($mysql);
?>