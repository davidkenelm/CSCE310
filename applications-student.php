<?php
// Include the configuration file to establish the database mysql
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['applicationPNum']) && isset($_POST['applicationUncommon']) && isset($_POST['applicationCommon']) && isset($_POST['applicationPurpose'])) {

    $applicationUIN = $_SESSION['UIN'];
    $applicationPNum = $_POST["applicationPNum"];
    $applicationUncommon = $_POST["applicationUncommon"];
    $applicationCommon = $_POST["applicationCommon"];
    $applicationPurpose = $_POST["applicationPurpose"];

    $queryTrack = "INSERT INTO track (Program_Num, Student_Num) VALUES (?, ?)";
    $stmtTrack = $mysql->prepare($queryTrack);
    $stmtTrack->bind_param("ii", $applicationPNum, $applicationUIN);
    $stmtTrack->execute();
    $stmtTrack->close();

    $queryApplications = "INSERT INTO applications (Program_Num, UIN, Uncom_Cert, Com_Cert, Purpose_Statement) VALUES (?, ?, ?, ?, ?)";
    $stmtApplications = $mysql->prepare($queryApplications);
    $stmtApplications->bind_param("iisss", $applicationPNum, $applicationUIN, $applicationUncommon, $applicationCommon, $applicationPurpose);
    $stmtApplications->execute();
    $stmtApplications->close();

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editPNum']) && isset($_POST['editUncommon']) && isset($_POST['editCommon']) && isset($_POST['editPurpose'])) {
    $editUIN = $_SESSION['UIN'];
    $editPNum = $_POST["editPNum"];
    $editUncommon = $_POST["editUncommon"];
    $editCommon = $_POST["editCommon"];
    $editPurpose = $_POST["editPurpose"];

    $query = "UPDATE applications SET Uncom_Cert = ?, Com_Cert = ?, Purpose_Statement = ?  WHERE UIN = ? AND Program_Num = ?";

    $stmt = $mysql->prepare($query);
    $stmt->bind_param("sssii", $editUncommon, $editCommon, $editPurpose, $editUIN, $editPNum);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removePNum'])) {
    $removeUIN = $_SESSION['UIN'];
    $removePNum = $_POST["removePNum"];

    $queryTrack = "DELETE FROM track WHERE Program_Num = ? AND Student_Num = ?";
    $stmtTrack = $mysql->prepare($queryTrack);
    $stmtTrack->bind_param("ii", $removePNum, $removeUIN);
    $stmtTrack->execute();
    $stmtTrack->close();

    $queryApplications = "DELETE FROM applications WHERE Program_Num = ? AND UIN = ?";
    $stmtApplications = $mysql->prepare($queryApplications);
    $stmtApplications->bind_param("ii", $removePNum, $removeUIN);
    $stmtApplications->execute();
    $stmtApplications->close();

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
            <h1 class="h1">Your Applications</h1>
            <?php
            $UIN = $_SESSION['UIN'];

            $applicationQuery = "SELECT programs.Program_Num, programs.Name, applications.Uncom_Cert, applications.Com_Cert, applications.Purpose_Statement
            FROM applications 
            JOIN programs ON applications.Program_Num = programs.Program_Num
            WHERE UIN = $UIN AND (isDeleted <> 1 OR isDeleted IS NULL)";
            $applicationResult = mysqli_query($mysql, $applicationQuery);

            echo "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>Program</th>
                            <th scope='col'>Uncommon Certifications</th>
                            <th scope='col'>Common Certifications</th>
                            <th scope='col'>Purpose Statement</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($applicationRow = mysqli_fetch_assoc($applicationResult)) {
                echo "
                <tr>
                    <th scope='row'>{$applicationRow['Name']}</th>
                    <td>{$applicationRow['Uncom_Cert']}</td>
                    <td>{$applicationRow['Com_Cert']}</td>
                    <td>{$applicationRow['Purpose_Statement']}</td>
                </tr>";
            }

            echo "</tbody>
                        </table>";
            ?>
        </div>
        <div class="row mt-5 justify-content-center text-center">
            <h1 class="h1"> Submit Applications </h1>
            <div class="col-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <select name="applicationPNum" class="form-select" aria-label="Default select example">
                        <option selected>Select A Program</option>
                        <?php
                        $UIN = $_SESSION['UIN'];

                        $applicationQuery = "SELECT programs.Program_Num, programs.Name
                                            FROM programs
                                            WHERE programs.Program_Num NOT IN (
                                                SELECT applications.Program_Num
                                                FROM applications
                                                WHERE applications.UIN = $UIN
                                            )";

                        $applicationResult = mysqli_query($mysql, $applicationQuery);

                        while ($applicationRow = mysqli_fetch_assoc($applicationResult)) {
                            echo "<option value='{$applicationRow['Program_Num']}'>{$applicationRow['Name']}</option>";
                        }
                        ?>
                    </select>
                    <div class="form-group">
                        <label class="mt-2" for="applicationUncommonInput">
                            Are you currently enrolled in
                            other uncompleted certifications
                            sponsored by the Cybersecurity
                            Center?</label>
                        <input name="applicationUncommon" type="text" class="form-control" id="applicationUncommonInput"
                            placeholder="Enter Your Uncommon Certifications">
                    </div>
                    <div class="form-group">
                        <label class="mt-2" for="applicationCommonInput">
                            Have you completed any
                            cybersecurity industry
                            certifications via the
                            Cybersecurity Center?</label>
                        <input name="applicationCommon" type="text" class="form-control" id="applicationCommonInput"
                            placeholder="Enter Your Common Certifications">
                    </div>
                    <div class="form-group">
                        <label class="mt-2" for="applicationPurposeInput">Purpose Statement</label>
                        <input name="applicationPurpose" type="text" class="form-control" id="applicationPurposeInput"
                            placeholder="Enter Your Purpose Statement">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
        <div class="row mt-5 justify-content-center text-center">
            <h1 class="h1"> Edit Your Applications </h1>
            <div class="col-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <select name="editPNum" class="form-select" aria-label="Default select example">
                        <option selected>Select A Program</option>
                        <?php
                        $UIN = $_SESSION['UIN'];

                        $applicationQuery = "SELECT programs.Program_Num, programs.Name, applications.Uncom_Cert, applications.Com_Cert, applications.Purpose_Statement
                                            FROM applications 
                                            JOIN programs ON applications.Program_Num = programs.Program_Num
                                            WHERE UIN = $UIN AND (isDeleted <> 1 OR isDeleted IS NULL)";

                        $applicationResult = mysqli_query($mysql, $applicationQuery);

                        while ($applicationRow = mysqli_fetch_assoc($applicationResult)) {
                            echo "<option value='{$applicationRow['Program_Num']}'>{$applicationRow['Name']}</option>";
                        }
                        ?>
                    </select>
                    <div class="form-group">
                        <label class="mt-2" for="editUncommonInput">Uncommon Certifications</label>
                        <input name="editUncommon" type="text" class="form-control" id="editUncommonInput"
                            placeholder="Enter Your Uncommon Certifications">
                    </div>
                    <div class="form-group">
                        <label class="mt-2" for="editCommonInput">Common Certifications</label>
                        <input name="editCommon" type="text" class="form-control" id="editCommonInput"
                            placeholder="Enter Your Common Certifications">
                    </div>
                    <div class="form-group">
                        <label class="mt-2" for="editPurposeInput">Purpose Statement</label>
                        <input name="editPurpose" type="text" class="form-control" id="editPurposeInput"
                            placeholder="Enter Your Purpose Statement">
                    </div>
                    <button type="submit" class="btn btn-primary my-2">Submit</button>
                </form>
            </div>
        </div>
        <div class="row mt-5 justify-content-center text-center">
            <h1 class="h1"> Delete Application </h1>
            <div class="col-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <select name="removePNum" class="form-select" aria-label="Default select example">
                        <option selected>Select A Program</option>
                        <?php
                        $UIN = $_SESSION['UIN'];

                        $applicationQuery = "SELECT programs.Program_Num, programs.Name, applications.Uncom_Cert, applications.Com_Cert, applications.Purpose_Statement
                                            FROM applications 
                                            JOIN programs ON applications.Program_Num = programs.Program_Num
                                            WHERE UIN = $UIN AND (isDeleted <> 1 OR isDeleted IS NULL)";

                        $applicationResult = mysqli_query($mysql, $applicationQuery);

                        while ($applicationRow = mysqli_fetch_assoc($applicationResult)) {
                            echo "<option value='{$applicationRow['Program_Num']}'>{$applicationRow['Name']}</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary my-2">Submit</button>
                </form>
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