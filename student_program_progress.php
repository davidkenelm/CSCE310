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
        echo "<h3>List of Students</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['Name']} (ID: {$row['Program_Num']})</li>"; // Modify column names as needed
            // Display other student information as needed
        }
        echo "</ul>";

        // Close the statement
        $stmt->close();
    } else {
        echo "Student ID is empty.";
    }
  }

  ?>

</body>
</html>

<?php
// Close the database mysql when done
mysqli_close($mysql);
?>