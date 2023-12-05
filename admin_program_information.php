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
  <h2>All Initiatives Available</h2>
  <h4> Warning: All deletions will result in all current progress records being deleted too</h4>
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
      $classes = [];
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
          echo "<td>" . $row['Description'] . "</td>"; // Modify column names as needed

          // Button column with a form to fetch and display additional information
          echo "<td>" . $row['Type'] . "</td>";
          echo "</tr>";
          $classes[] = [
            'Name' => $row['Name'],
            'Class_ID' => $row['Class_ID']
        ]; 
      }

      // Close table
      echo "</table>";
    } else {
        echo "No Classes In Database.";
    }

    echo "<h4>Insert New Class</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for= 'className'>Name: </label>
    <input type = 'text' name = 'className' id = 'className'>
    <label for= 'classDescription'>Description: </label>
    <input type = 'text' name = 'classDescription' id = 'classDescription'>
    <label for= 'classType'>Type: </label>
    <select id='classType' name='classType'>
        <option value='Freshman'>Freshman</option>
        <option value='Sophomore'>Sophomore</option>
        <option value='Junior'>Junior</option>
        <option value='Senior'>Senior</option>
    </select>
    <input type = 'submit' name = 'insertClass'>
    </form>";

    echo "<h4>Update Class</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for= 'className'>Name: </label>
    <select id='className' name='className'>";
        
        foreach ($classes as $class) {
            echo "<option value='" . $class['Class_ID'] . "'>" . $class['Name'] . "</option>";
        }
        
        
    echo "</select>
    <label for= 'newclassName'>New Name: </label>
    <input type = 'text' name = 'newclassName' id = 'newclassName'>
    <label for= 'classDescription'>Description: </label>
    <input type = 'text' name = 'classDescription' id = 'classDescription'>
    <label for= 'classType'>Type: </label>
    <select id='classType' name='classType'>
        <option value='Freshman'>Freshman</option>
        <option value='Sophomore'>Sophomore</option>
        <option value='Junior'>Junior</option>
        <option value='Senior'>Senior</option>
    </select>
    <input type = 'submit' name = 'updateClass'>
    </form>";

    echo "<h4>Delete Class</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for= 'className'>Name: </label>
    <select id='className' name='className'>";
        
        foreach ($classes as $class) {
            echo "<option value='" . $class['Class_ID'] . "'>" . $class['Name'] . "</option>";
        }
        
        
    echo "</select>";
    echo "<input type = 'submit' name = 'deleteClass' value = 'Delete'>";
    echo "</form>";

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
      $certifications = [];
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
          echo "<td>" . $row['Description'] . "</td>"; // Modify column names as needed

          // Button column with a form to fetch and display additional information
          echo "<td>" . $row['Level'] . "</td>";
          echo "</tr>";
          $certifications[] = [
            'Name' => $row['Name'],
            'Cert_ID' => $row['Cert_ID']
        ];
      }
      


      // Close table
      echo "</table>";
    } else {
        echo "No Classes In Database.";
    }
    echo "<h4>Insert New Certification</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for= 'certName'>Name: </label>
    <input type = 'text' name = 'certName' id = 'certName'>
    <label for= 'certDescription'>Description: </label>
    <input type = 'text' name = 'certDescription' id = 'certDescription'>
    <label for= 'certType'>Type: </label>
    <input type = 'text' id = 'certType' name = 'certType'>
    <input type = 'submit' name = 'insertCert'>
    </form>";

    echo "<h4>Update Certification</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for= 'certName'>Name: </label>
    <select id='certName' name='certName'>";
        
        foreach ($certifications as $certification) {
            echo "<option value='" . $certification['Cert_ID'] . "'>" . $certification['Name'] . "</option>";
        }
        
        
    echo "</select>
    <label for= 'newCertName'>New Name: </label>
    <input type = 'text' name = 'newCertName' id = 'newCertName'>
    <label for= 'certDescription'>Description: </label>
    <input type = 'text' name = 'certDescription' id = 'certDescription'>
    <label for= 'certType'>Type: </label>
    <input type = 'text' id = 'certType' name = 'certType'>
    <input type = 'submit' name = 'updateCert'>
    </form>";

    echo "<h4>Delete Certification</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for= 'certName'>Name: </label>
    <select id='certName' name='certName'>";
        
        foreach ($certifications as $certification) {
            echo "<option value='" . $certification['Cert_ID'] . "'>" . $certification['Name'] . "</option>";
        }
        
        
    echo "</select>";
    echo "<input type = 'submit' name = 'deleteCert' value = 'Delete'>";
    echo "</form>";


    



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
      $internships = [];
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['Name'] . "</td>"; // Modify column names as needed
          echo "<td>" . $row['Description'] . "</td>"; // Modify column names as needed

          // Button column with a form to fetch and display additional information
          echo "<td>" . $row['Is_Gov'] . "</td>";
          echo "</tr>";
          $internships[] = [
            'Name' => $row['Name'],
            'Intern_ID' => $row['Intern_ID']
        ];
      }

      // Close table
      echo "</table>";
    } else {
        echo "No Internships In Database.";
    }
    echo "<h4>Insert New Internship</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    <label for= 'internName'>Name: </label>
    <input type = 'text' name = 'internName' id = 'internName'>
    <label for= 'internDescription'>Description: </label>
    <input type = 'text' name = 'internDescription' id = 'internDescription'>
    <label for= 'internType'>Goverment Type: </label>
    <select id='internType' name='internType'>
        <option value='Y'>Y</option>
        <option value='N'>N</option>
    </select>
    <input type = 'submit' name = 'insertInternship'>
    </form>";

    echo "<h4>Update Internship</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    
    <label for= 'internName'>Name: </label>
    <select id='internName' name='internName'>";
        
        foreach ($internships as $internship) {
            echo "<option value='" . $internship['Intern_ID'] . "'>" . $internship['Name'] . "</option>";
        }
        
        
    echo "</select>
    <label for= 'newinternName'>New Name: </label>
    <input type = 'text' name = 'newInternName' id = 'newInternName'>
    
    <label for= 'internDescription'>Description: </label>
    <input type = 'text' name = 'internDescription' id = 'internDescription'>
    <label for= 'internType'>Goverment Type: </label>
    <select id='internType' name='internType'>
        <option value='Y'>Y</option>
        <option value='N'>N</option>
    </select>
    <input type = 'submit' name = 'updateInternship'>
    </form>";

    echo "<h4>Delete Internship</h4>";
    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
    
    <label for= 'internName'>Name: </label>
    <select id='internName' name='internName'>";
        
        foreach ($internships as $internship) {
            echo "<option value='" . $internship['Intern_ID'] . "'>" . $internship['Name'] . "</option>";
        }
        
        
    echo "</select>";
    echo "<input type = 'submit' name = 'deleteInternship' value = 'Delete'>";

    // Close the statement
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['insertClass']) && !empty($_POST['insertClass']) ){
        $sql = "INSERT INTO classes (Name, Description, Type) 
        VALUES (?, ?, ?)";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("sss", $_POST['className'], $_POST['classDescription'], $_POST['classType']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['updateClass']) && !empty($_POST['updateClass']) ){
        $sql = "UPDATE classes SET Name = ?, Description = ?, Type = ? WHERE Class_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("ssss", $_POST['newclassName'], $_POST['classDescription'], $_POST['classType'],$_POST['className']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['deleteClass']) && !empty($_POST['deleteClass']) ){
        
        $sql = "DELETE FROM class_enrollment WHERE Class_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("s",$_POST['className']);
        $stmt->execute();
        $stmt->close();
        
        $sql = "DELETE FROM classes WHERE Class_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("s",$_POST['className']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['insertCert']) && !empty($_POST['insertCert']) ){
        $sql = "INSERT INTO certifications (Name, Description, Level) 
        VALUES (?, ?, ?)";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        echo $_POST['certType'];
        echo $_POST['certName'];
        $stmt->bind_param("sss", $_POST['certName'], $_POST['certDescription'], $_POST['certType']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['updateCert']) && !empty($_POST['updateCert']) ){

        $sql = "UPDATE certifications SET Name = ?, Description = ?, Level = ? WHERE Cert_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("ssss", $_POST['newCertName'], $_POST['certDescription'], $_POST['certType'],$_POST['certName']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['deleteCert']) && !empty($_POST['deleteCert']) ){
        
        $sql = "DELETE FROM cert_enrollment WHERE Cert_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("s",$_POST['certName']);
        $stmt->execute();
        $stmt->close();
        
        $sql = "DELETE FROM certifications WHERE Cert_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("s",$_POST['certName']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['insertInternship']) && !empty($_POST['insertInternship']) ){
        $sql = "INSERT INTO internships (Name, Description, Is_Gov) 
        VALUES (?, ?, ?)";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query

        $stmt->bind_param("sss", $_POST['internName'], $_POST['internDescription'], $_POST['internType']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['updateInternship']) && !empty($_POST['updateInternship']) ){

        $sql = "UPDATE internships SET Name = ?, Description = ?, Is_Gov = ? WHERE Intern_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("ssss", $_POST['newInternName'], $_POST['internDescription'], $_POST['internType'],$_POST['internName']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
      if (isset($_POST['deleteInternship']) && !empty($_POST['deleteInternship']) ){
        
        $sql = "DELETE FROM intern_app WHERE Intern_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("s",$_POST['internName']);
        $stmt->execute();
        $stmt->close();
        
        $sql = "DELETE FROM internships WHERE Intern_ID = ?";
        $stmt = $mysql->prepare($sql);

// Bind parameters and execute the query
        $stmt->bind_param("s",$_POST['internName']);
        $stmt->execute();

// Close the statement
        $stmt->close();
      }
    }

  ?>
</body>
</html>
