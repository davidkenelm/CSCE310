<?php
include 'config.php';


//Checks to ensure the UIN is set to make sure the user is logged in
if (!isset($_SESSION['UIN'])) {
    header("Location: login.php"); 
    exit();
}

//storing uin to search for student's avaiable applications
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

//drop down for available applications given uin
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

//Displaying the documents and associated application number
function displayDocuments($mysql, $appNum) {
    // Bind to avoid SQL injection
    $sql = "SELECT * FROM documentation WHERE App_Num = ?";
    $stmt = $mysql->prepare($sql);

    if (!$stmt) {
        echo "Error: " . $mysql->error;
        return;
    }

    $stmt->bind_param("s", $appNum);

    // Execute the statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<div class='container mt-4'>";
            echo "<h2 class='mb-4'>Uploaded Documents</h2>";
            echo "<table class='table table-bordered'>";
            echo "<thead class='thead-dark'>";
            echo "<tr><th>Type</th><th>File</th><th>Edit</th><th>Delete</th></tr>";
            echo "</thead><tbody>";
            //loops through result to display all files and their information
            while ($row = $result->fetch_assoc()) {
                $filename = basename($row['Link']);

                echo "<tr>";
                echo "<td>" . $row['Doc_Type'] . "</td>";
                echo "<td><a href='" . $row['Link'] . "' target='_blank'>" . $filename . "</a></td>"; //opens file in seperate window
                echo "<td><a href='javascript:void(0);' class='btn btn-primary btn-sm' onclick='editDocument(" . $row['Doc_Num'] . "," . $row['App_Num'] . ",\"" . $row['Doc_Type'] . "\")'>Edit</a></td>"; //Calling JS script to show edit popup
                echo "<td><a href='document_student.php?action=delete&docNum=" . $row['Doc_Num'] . "' class='btn btn-danger btn-sm'>Delete</a></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
            echo "</div>";
        } else {
            echo "<div class='container mt-4'>No documents uploaded for this application.</div>";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}


//Deleting document given document number
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

//Uploading document links to file system location
if (isset($_POST['submit'])) {
    $appNum = $_POST['appNum'];

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    $extension = pathinfo($targetFile, PATHINFO_EXTENSION);

    if (!in_array($extension, ['pdf', 'docx'])) {
        echo "Your file extension must be .pdf or .docx";
    } else if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        $link = $targetFile;

        // Using bind and prepared statements to avoid SQL injection
        $sql = "INSERT INTO documentation (App_Num, Link, Doc_Type) VALUES (?, ?, ?)";
        $stmt = $mysql->prepare($sql);

        if (!$stmt) {
            echo "Error: " . $mysql->error;
            exit();
        }

        $stmt->bind_param("sss", $appNum, $link, $extension);

        if ($stmt->execute()) {
            echo "File uploaded successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newAppNum = isset($_POST['application_num']) ? $_POST['application_num'] : '';
    $newDocType = isset($_POST['file_type']) ? $_POST['file_type'] : '';
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

            // Using bind and prepared statements to avoid SQL injection
            $updateSql = "UPDATE documentation SET App_Num = ?, Doc_Type = ?, Link = ? WHERE Doc_Num = ?";
            $stmt = $mysql->prepare($updateSql);

            if (!$stmt) {
                echo "Error: " . $mysql->error;
                exit();
            }

            $stmt->bind_param("sssi", $newAppNum, $newDocType, $link, $docNum);

            if ($stmt->execute()) {
                echo "Document updated successfully.";
            } else {
                echo "Error updating document: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $updateSql = "UPDATE documentation SET App_Num = ?, Doc_Type = ? WHERE Doc_Num = ?";
        $stmt = $mysql->prepare($updateSql);

        if (!$stmt) {
            echo "Error: " . $mysql->error;
            exit();
        }

        $stmt->bind_param("ssi", $newAppNum, $newDocType, $docNum);

        if ($stmt->execute()) {
            echo "Document updated successfully.";
        } else {
            echo "Error updating document: " . $stmt->error;
        }

        $stmt->close();
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
            var application_num = document.getElementById('application_num');
            var file_type = document.getElementById('file_type');
            var docNumInput = document.getElementById('docNumInput');
            var fileInput = document.getElementById('fileInput');

            application_num.value = appNum;
            file_type.value = docType;
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

        <!-- Editing Documents -->
        <div class="popup" id="popup">
            <span class="close-btn" onclick="closePopup()">X</span>
            <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="application_num" class="form-label">Application Number:</label>
                    <input type="text" id="application_num" name="application_num" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="file_type" class="form-label">Document Type:</label>
                    <input type="text" id="file_type" name="file_type" class="form-control" required>
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
