<?php
include 'config.php';


if (!isset($_SESSION['UIN'])) {
    // Redirect or handle the case where the user is not logged in
    header("Location: login.php"); // Adjust the redirection URL
    exit();
}

$uin = $_SESSION['UIN'];

function getApplications($mysql, $uin) {
    $applications = array();

    // Query applications based on the user's UIN
    $sql = "SELECT DISTINCT App_Num FROM applications WHERE UIN = '$uin'";
    $result = $mysql->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $applications[] = $row['App_Num'];
        }
    }

    return $applications;
}

// Example function to display dropdowns
function displayApplicationDropdowns($mysql, $uin) {
    $applications = getApplications($mysql, $uin);

    if (!empty($applications)) {
        echo '<label for="appNum">Select Application Number:</label>';
        echo '<select name="appNum" required>';
        foreach ($applications as $appNum) {
            echo "<option value=\"$appNum\">$appNum</option>";
        }
        echo '</select>';
    } else {
        echo 'No applications available for this user.';
    }
}

function displayDocuments($mysql, $appNum) {
    $sql = "SELECT * FROM documentation WHERE App_Num = '$appNum'";
    $result = $mysql->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Uploaded Documents</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Document Type</th><th>File</th><th>Edit</th><th>Delete</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $filename = basename($row['Link']);

            echo "<tr>";
            echo "<td>" . $row['Doc_Type'] . "</td>";
            echo "<td><a href='download.php?file=" . $filename . "' target='_blank'>" . $filename . "</a></td>";
            echo "<td><a href='javascript:void(0);' onclick='editDocument(" . $row['Doc_Num'] . "," . $row['App_Num'] . ",\"" . $row['Doc_Type'] . "\")'>Edit</a></td>";
            echo "<td><a href='document_student.php?action=delete&docNum=" . $row['Doc_Num'] . "'>Delete</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No documents uploaded for this application.";
    }
}

function deleteDocument($mysql, $docNum) {
    $sql = "DELETE FROM documentation WHERE Doc_Num = '$docNum'";
    
    if ($mysql->query($sql) === TRUE) {
        echo "Document deleted successfully.";
    } else {
        echo "Error deleting document: " . $mysql->error;
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'delete' && isset($_GET['docNum'])) {
        $docNum = $_GET['docNum'];
        deleteDocument($mysql, $docNum);
    }
}

if (isset($_POST['submit'])) {
    $appNum = $_POST['appNum'];

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    $extension = pathinfo($targetFile, PATHINFO_EXTENSION);

    if (!in_array($extension, ['pdf', 'docx'])) {
        echo "You file extension must be .pdf or .docx";
    } else if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        $link = $targetFile;

        $sql = "INSERT INTO documentation (App_Num, Link, Doc_Type) VALUES ('$appNum', '$link', '$extension')";

        if ($mysql->query($sql) === TRUE) {
            echo "File uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $mysql->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newAppNum = isset($_POST['field1']) ? $_POST['field1'] : '';
    $newDocType = isset($_POST['field2']) ? $_POST['field2'] : '';
    $docNum = isset($_POST['docNum']) ? $_POST['docNum'] : '';

    if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["fileInput"]["name"]);

        $extension = pathinfo($targetFile, PATHINFO_EXTENSION);

        if (!in_array($extension, ['pdf', 'docx'])) {
            echo "You file extension must be .pdf or .docx";
            exit;
        }

        if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $targetFile)) {
            $link = $targetFile;

            $updateSql = "UPDATE documentation SET App_Num = '$newAppNum', Doc_Type = '$newDocType', Link = '$link' WHERE Doc_Num = '$docNum'";
            if ($mysql->query($updateSql) === TRUE) {
                echo "Document updated successfully.";
            } else {
                echo "Error updating document: " . $mysql->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $updateSql = "UPDATE documentation SET App_Num = '$newAppNum', Doc_Type = '$newDocType' WHERE Doc_Num = '$docNum'";
        if ($mysql->query($updateSql) === TRUE) {
            echo "Document updated successfully.";
        } else {
            echo "Error updating document: " . $mysql->error;
        }
    }

    echo "<script>closePopup();</script>";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload and Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
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

        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>

    <script>
        function editDocument(docNum, appNum, docType) {
            var popup = document.getElementById('popup');
            var field1 = document.getElementById('field1');
            var field2 = document.getElementById('field2');
            var docNumInput = document.getElementById('docNumInput');
            var fileInput = document.getElementById('fileInput');

            field1.value = appNum;
            field2.value = docType;
            docNumInput.value = docNum;
            fileInput.value = '';

            popup.style.display = 'block';
        }

        function closePopup() {
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
        }
    </script>

</head>
<body>
    <?php 
        include 'navbar.php';
    ?>
    <div class="container mt-5">
        <h1 class="mb-4">Document Upload and Management</h1>

        <!-- Upload Document -->
        <h2>Upload Document</h2>
        <form action="document_student.php" method="post" enctype="multipart/form-data">
            <?php displayApplicationDropdowns($mysql, $uin); ?>
            
            <div class="mb-3">
                <label for="file" class="form-label">Choose File:</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary">Upload</button>
        </form>

        <!-- View Documents -->
        <h2 class="mt-4">View Documents</h2>
        <form action="" method="get">
            <?php displayApplicationDropdowns($mysql, $uin); ?>
            <button type="submit" class="btn btn-primary">View Documents</button>
        </form>

        <?php
        if (isset($_GET['appNum'])) {
            $appNum = $_GET['appNum'];
            displayDocuments($mysql, $appNum);
        }
        ?>

        <!-- Popup for Editing Documents -->
        <div class="popup" id="popup">
            <span class="close-btn" onclick="closePopup()">X</span>
            <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="field1" class="form-label">Application Number:</label>
                    <input type="text" id="field1" name="field1" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="field2" class="form-label">Document Type:</label>
                    <input type="text" id="field2" name="field2" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="fileInput" class="form-label">Choose New File:</label>
                    <input type="file" id="fileInput" name="fileInput" class="form-control">
                </div>

                <input type="hidden" id="docNumInput" name="docNum" value="">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

</body>
</html>

<?php
$mysql->close();
?>
