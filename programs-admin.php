<?php
// Include the configuration file to establish the database mysql
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['programName']) && isset($_POST['programDescription'])) {

    $reportName = $_POST["programName"];
    $programDescription = $_POST["programDescription"];

    $query = "INSERT INTO programs (Name, Description) VALUES ('$reportName', '$programDescription')";
    mysqli_query($mysql, $query);

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editName']) && isset($_POST['editDescription'])) {
    $editName = $_POST["editName"];
    $editDescription = $_POST["editDescription"];
    echo "run";

    if (isset($_POST["editUndo"])) {
        $query = "UPDATE programs SET Description = '$editDescription', isDeleted = NULL WHERE Name = '$editName'";
    } else {
        $query = "UPDATE programs SET Description = '$editDescription' WHERE Name = '$editName'";

    }

    mysqli_query($mysql, $query);

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removeName'])) {
    $removeName = $_POST["removeName"];

    if (isset($_POST["removeCheck"])) {
        $removeCheck = $_POST["removeCheck"];

        $sql = "SELECT Program_Num FROM programs WHERE Name = ?";
        $stmt = $mysql->prepare($sql);

        $stmt->bind_param("s", $removeName);
        $stmt->execute();

        $stmt->bind_result($programNum);
        $stmt->fetch();
        $stmt->close();

        if ($programNum !== null) {
            $query = "DELETE FROM events WHERE Program_Num = $programNum";
            mysqli_query($mysql, $query);

            $query = "DELETE FROM applications WHERE Program_Num = $programNum";
            mysqli_query($mysql, $query);

            $query = "DELETE FROM track WHERE Program_Num = $programNum";
            mysqli_query($mysql, $query);

            $query = "DELETE FROM cert_enrollment WHERE Program_Num = $programNum";
            mysqli_query($mysql, $query);

            $query = "DELETE FROM programs WHERE Program_Num = $programNum";
            mysqli_query($mysql, $query);
        }

    } else {
        $query = "UPDATE programs SET isDeleted = 1 WHERE Name = '$removeName'";
        mysqli_query($mysql, $query);

    }

    //echo "<script> console.log('Run') </script>";
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Student Tracking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body style="background-color: rgb(240, 241, 245);">
    <!-- Basic navigation links -->
    <?php
    include 'navbar.php';
    ?>
    <div class="container mt-5">
        <div id="student-functionalities" class="mt-4 row justify-content-center text-center">
            <h1 class="h1">Current Programs</h1>
            <?php
            $studentQuery = "SELECT * FROM programs";
            $studentResult = mysqli_query($mysql, $studentQuery);

            echo "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>#</th>
                            <th scope='col'>Name</th>
                            <th scope='col'>Description</th>
                            <th scope='col'>Deleted?</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                echo "
                <tr>
                    <th scope='row'>{$studentRow['Program_Num']}</th>
                    <td>{$studentRow['Name']}</td>
                    <td>{$studentRow['Description']}</td>
                    <td>{$studentRow['isDeleted']}</td>
                </tr>";
            }

            echo "</tbody>
                        </table>";
            ?>
        </div>
        <div class="row mt-5 justify-content-center text-center">
            <h1 class="h1"> Add Programs </h1>
            <div class="col-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label for="programNameInput">Program Name</label>
                        <input name="programName" type="text" class="form-control" id="programNameInput"
                            placeholder="Enter Program Name">
                    </div>
                    <div class="form-group mt-2">
                        <label for="programDescriptionInput">Program Description</label>
                        <input name="programDescription" type="text" class="form-control" id="programDescriptionInput"
                            placeholder="Enter Program Description">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
        <div class="row mt-5 justify-content-center text-center">
            <h1 class="h1"> Edit Programs </h1>
            <div class="col-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <select name="editName" class="form-select" aria-label="Default select example">
                        <option selected>Select A Program to Edit</option>
                        <?php
                        $studentQuery = "SELECT * FROM programs;";
                        $studentResult = mysqli_query($mysql, $studentQuery);

                        while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                            echo "<option value='{$studentRow['Name']}'>{$studentRow['Name']}</option>";
                        }
                        ?>
                    </select>
                    <div class="form-group mt-3">
                        <input name="editDescription" type="text" class="form-control" id="programDescriptionInput"
                            placeholder="Edit Program Description">
                    </div>
                    <div class="my-2 justify-content-center align-items-center text-center">
                        <input name="editUndo" type="checkbox" class="form-check-input" id="editUndo">
                        <label class="form-check-label" for="editUndo">Undo Partial Delete</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
            <div class="row mt-5 justify-content-center text-center">
                <h1 class="h1"> Remove Programs </h1>
                <div class="col-5">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <select name="removeName" class="form-select" aria-label="Default select example">
                            <option selected>Select A Program to Remove</option>
                            <?php
                            $studentQuery = "SELECT * FROM programs";
                            $studentResult = mysqli_query($mysql, $studentQuery);

                            while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                                echo "<option value='{$studentRow['Name']}'>{$studentRow['Name']}</option>";
                            }
                            ?>
                        </select>
                        <div class="my-2 justify-content-center align-items-center text-center">
                            <input name="removeCheck" type="checkbox" class="form-check-input" id="removeCheck">
                            <label class="form-check-label" for="removeCheck">Full Delete</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>
            </div>
            <div class="row mt-5 mb-4 justify-content-center text-center">
                <h1 class="h1"> Generate Program Reports </h1>
                <div class="col">
                    <div class="w-50 justify-content-center text-center m-auto">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <select name="reportName" class="form-select" aria-label="Default select example">
                                <option selected>Select A Program</option>
                                <?php
                                $studentQuery = "SELECT * FROM programs";
                                $studentResult = mysqli_query($mysql, $studentQuery);

                                while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                                    echo "<option value='{$studentRow['Name']}'>{$studentRow['Name']}</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reportName'])) {

                        $reportName = $_POST["reportName"];

                        $query = "SELECT users.UIN, users.First_Name, users.M_Initial, users.Last_Name,
                                    applications.Uncom_Cert, applications.Com_Cert, applications.Purpose_Statement
                                    FROM users
                                    JOIN college_student ON users.UIN = college_student.UIN
                                    JOIN applications ON college_student.UIN = applications.UIN
                                    JOIN programs ON applications.Program_Num = programs.Program_Num
                                    WHERE programs.name = '$reportName';";

                        $studentResult = mysqli_query($mysql, $query);

                        $tableClass = 'table table-striped';
                        $tableScope = '"col"';
                        $tableScopeRow = '"row"';

                        echo "<h2 class='h2 mt-2'> Program: {$reportName}</h2>";
                        echo "<table class='table table-striped'>
                            <thead>
                                <tr>
                                    <th scope='col'>#</th>
                                    <th scope='col'>Name</th>
                                    <th scope='col'>Uncommon Certifications</th>
                                    <th scope='col'>Common Certifications</th>
                                    <th scope='col'>Purpose Statement</th>
                                </tr>
                            </thead>
                            <tbody>";

                        while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                            echo "
                                    <tr>
                                        <th scope='row'>{$studentRow['UIN']}</th>
                                        <td>{$studentRow['First_Name']} {$studentRow['M_Initial']} {$studentRow['Last_Name']}</td>
                                        <td>{$studentRow['Uncom_Cert']}</td>
                                        <td>{$studentRow['Com_Cert']}</td>
                                        <td>{$studentRow['Purpose_Statement']}</td>
                                    </tr>";
                        }

                        echo "</tbody>
                        </table>";

                    }

                    ?>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
</body>

</html>

<?php
// Close the database mysql when done
mysqli_close($mysql);
?>