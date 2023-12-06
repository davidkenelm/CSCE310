<?php
// Include the configuration file to establish the database mysql
// student side for initative infromation created by Ameel Aziz
include 'config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Progress Record Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
<?php
    include 'navbar.php';
    ?>
  <h2>All Initiatives Available</h2>
  <?php
    $sql = "SELECT * FROM classes";
    // Prepare a statement
    $stmt = $mysql->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Start table formatting
      echo "<h3>All Class Offered</h3>";
      echo "<table border='1' style='width: 100%;'>";
      echo "<tr><th>Class Name</th><th>Description</th><th>Type</th></tr>";

      // Loop through the result set and display data in table rows
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
          echo "<td>" . $row['Description'] . "</td>"; // Modify column names as needed

          // Button column with a form to fetch and display additional information
          echo "<td>" . $row['Type'] . "</td>";
          echo "</tr>";
      }

      // Close table
      echo "</table>";
    } else {
        echo "No Classes In Database.";
    }

    // Close the statement
    $stmt->close();

    $sql = "SELECT * FROM certifications";
    // Prepare a statement
    $stmt = $mysql->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Start table formatting
      echo "<h3>All Certifications Offered</h3>";
      echo "<table border='1' style='width: 100%;'>";
      echo "<tr><th>Class Name</th><th>Description</th><th>Level</th></tr>";

      // Loop through the result set and display data in table rows
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
          echo "<td>" . $row['Description'] . "</td>"; // Modify column names as needed

          // Button column with a form to fetch and display additional information
          echo "<td>" . $row['Level'] . "</td>";
          echo "</tr>";
      }

      // Close table
      echo "</table>";
    } else {
        echo "No Classes In Database.";
    }

    // Close the statement
    $stmt->close();
    $sql = "SELECT * FROM internships";
    // Prepare a statement
    $stmt = $mysql->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // Start table formatting
      echo "<h3>All Internships Offered</h3>";
      echo "<table border='1' style='width: 100%;'>";
      echo "<tr><th>Class Name</th><th>Description</th><th>Gov_Type</th></tr>";

      // Loop through the result set and display data in table rows
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
          echo "<td>" . $row['Description'] . "</td>"; // Modify column names as needed

          // Button column with a form to fetch and display additional information
          echo "<td>" . $row['Is_Gov'] . "</td>";
          echo "</tr>";
      }

      // Close table
      echo "</table>";
    } else {
        echo "No Internships In Database.";
    }

    // Close the statement
    $stmt->close();

  ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
</html>
