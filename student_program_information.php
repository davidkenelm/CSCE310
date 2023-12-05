<?php
// Include the configuration file to establish the database mysql
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Progress Record Information</title>
</head>
<body>
  <h2>All THINGS Available</h2>
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
      echo "<tr><th>Class Name</th><th>Description</th><th>Type</th></tr>";

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
</html>
